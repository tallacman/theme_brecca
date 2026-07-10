<?php defined('C5_EXECUTE') or die('Access Denied.'); ?>

<?php
$pageUrl = $view->url('/dashboard/pages/container_maker');
$canSave = !empty($themes);
$hasTheme = is_object($theme) && $theme->getThemeID();
$lockHandle = is_array($editingContainer ?? null) && empty($editingContainer['import_allow_handle']);
$isImportedParsed = is_array($editingContainer ?? null) && !empty($editingContainer['imported_parsed']);
$isEditingContainer = is_array($editingContainer ?? null);
$themeContainerFiles = $themeContainerFiles ?? [];
$showImport = !empty($themes) && (int) $selectedThemeID > 0;
?>

<p class="text-muted mb-3" id="cmIntroText">
    <?php if ($hasTheme) { ?>
        <?= t('Building for your active theme %s. Pick CSS Grid or Bootstrap 5, drag presets onto the canvas, and compose complex layouts.', '<strong>' . h($theme->getThemeName()) . '</strong>') ?>
    <?php } else { ?>
        <?= t('Pick CSS Grid or Bootstrap 5, drag presets onto the canvas, and compose complex layouts.') ?>
    <?php } ?>
</p>

<?php if ($isImportedParsed) { ?>
    <div class="alert alert-info py-2"><?= t('This container was imported from existing PHP/CSS. Review the layout, then save with Overwrite to store full designer state.') ?></div>
<?php } elseif (is_array($editingContainer ?? null) && !empty($editingContainer['imported'])) { ?>
    <div class="alert alert-info py-2"><?= t('Container imported into the designer. Adjust the layout and save when ready.') ?></div>
<?php } ?>

<?php if ($showImport) { ?>
<div class="cm-import-wrap card mb-3" id="cmImportSection">
    <div class="card-header py-2 d-flex align-items-center justify-content-between gap-2">
        <span class="fw-semibold"><?= t('Import container') ?></span>
        <span class="small text-muted"><?= t('Load an existing container into the designer to modify it') ?></span>
    </div>
    <div class="card-body py-3">
        <form method="post" action="<?= $view->action('import') ?>" enctype="multipart/form-data" id="cmImportThemeForm" class="cm-import-panel">
            <?php $token->output('container_maker_import'); ?>
            <input type="hidden" name="themeID" value="<?= (int) $selectedThemeID ?>">
            <input type="hidden" name="importSource" value="theme">
            <div class="row g-2 align-items-end">
                <div class="col-md-8 col-lg-9">
                    <label class="form-label mb-1" for="cmImportThemeHandle"><?= t('Theme container file') ?></label>
                    <select name="importHandle" id="cmImportThemeHandle" class="form-select form-select-sm" <?= empty($themeContainerFiles) ? 'disabled' : '' ?>>
                        <?php if (empty($themeContainerFiles)) { ?>
                            <option value=""><?= t('No container files in this theme yet') ?></option>
                        <?php } else { ?>
                            <?php foreach ($themeContainerFiles as $file) { ?>
                                <option value="<?= h($file['handle']) ?>">
                                    <?= h($file['name']) ?> (<?= h($file['handle']) ?>)<?= !empty($file['has_designer_state']) ? ' · ' . t('designer') : ' · ' . t('parse on import') ?>
                                </option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-4 col-lg-3">
                    <button type="submit" class="btn btn-outline-primary btn-sm w-100" <?= empty($themeContainerFiles) ? 'disabled' : '' ?>><?= t('Import & edit') ?></button>
                </div>
            </div>
            <p class="small text-muted mb-0 mt-2"><?= t('Only container files from the active theme (%s) are listed here.', h(is_object($theme) ? $theme->getThemeName() : '')) ?></p>
        </form>
    </div>
</div>
<?php } ?>

<form method="post" action="<?= $view->action('save') ?>" id="cmForm" class="cm-app">
    <?php $token->output('container_maker_save'); ?>
    <input type="hidden" name="layout_mode" id="cmLayoutMode" value="css_grid">
    <input type="hidden" name="bootstrap_settings" id="cmBootstrapSettingsField" value="">
    <input type="hidden" name="theme_has_bootstrap" id="cmThemeHasBootstrapField" value="0">
    <input type="hidden" name="areas" id="cmAreas" value="">
    <input type="hidden" name="area_placement" id="cmPlacement" value="">
    <input type="hidden" name="responsive_columns" id="cmResponsive" value="">
    <input type="hidden" name="area_full_rows" id="cmFullRows" value="">
    <input type="hidden" name="nested_areas" id="cmNested" value="">
    <input type="hidden" name="area_padding" id="cmPadding" value="">
    <input type="hidden" name="grid_columns" id="cmGridColumns" value="repeat(12, minmax(0, 1fr))">
    <input type="hidden" name="grid_rows" id="cmGridRows" value="minmax(64px, auto)">
    <input type="hidden" name="grid_tracks" id="cmGridTracks" value="">
    <input type="hidden" name="area_responsive" id="cmAreaResponsive" value="">
    <input type="hidden" name="viewport_settings" id="cmViewportSettings" value="">

    <ul class="nav nav-tabs mb-3 cm-layout-tabs" role="tablist" aria-label="<?= h(t('Container layout type')) ?>">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" type="button" data-layout-mode="css_grid"><?= t('CSS Grid') ?></button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" type="button" data-layout-mode="bootstrap5"><?= t('Bootstrap 5') ?></button>
        </li>
    </ul>

    <div class="cm-workspace">
        <aside class="cm-palette">
            <div class="cm-panel-title"><?= t('Presets') ?></div>
            <p class="small text-muted mb-2"><?= t('Drop onto the canvas to add — stack as many as you need.') ?></p>
            <div id="cmPresets" class="cm-presets"></div>
            <details class="cm-bootstrap-settings d-none" id="cmBootstrapSettings" open>
                <summary class="cm-panel-title mb-0"><?= t('Bootstrap 5') ?></summary>
                <div class="form-check mt-2 mb-2">
                    <input class="form-check-input" type="checkbox" id="cmThemeHasBootstrap" value="1" <?= !empty($themeHasBootstrap) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="cmThemeHasBootstrap"><?= t('Theme already includes Bootstrap 5') ?></label>
                </div>
                <?php if (!empty($themeHasBootstrap)) { ?>
                    <p class="small text-success mb-2"><?= t('Detected Bootstrap in the active theme — checked automatically. Uncheck it to inject scoped Bootstrap CSS instead.') ?></p>
                <?php } ?>
                <p class="small text-muted mb-2" id="cmBootstrapInjectNote"><?= t('When unchecked, scoped Bootstrap grid CSS is injected in the page header for this container block only.') ?></p>
                <label class="form-label mb-1" for="cmBootstrapGutter"><?= t('Row gutter') ?></label>
                <select id="cmBootstrapGutter" class="form-select form-select-sm">
                    <option value="g-0">g-0</option>
                    <option value="g-1">g-1</option>
                    <option value="g-2">g-2</option>
                    <option value="g-3" selected>g-3</option>
                    <option value="g-4">g-4</option>
                    <option value="g-5">g-5</option>
                </select>
                <p class="small text-muted mb-0 mt-2"><?= t('Areas on the same row become one Bootstrap .row with .col-* children. Overlapping areas are not supported in this mode.') ?></p>
            </details>
            <details class="cm-grid-settings mt-2" open id="cmGridSettings">
                <summary class="cm-panel-title mb-0"><?= t('Column tracks') ?></summary>
                <p class="small text-muted mb-2"><?= t('Each row is one grid column. Type sets the unit; Size is the value (e.g. 2 + fr → 2fr, 200 + px → 200px).') ?></p>
                <div class="cm-track-header" aria-hidden="true">
                    <span><?= t('Type') ?></span>
                    <span><?= t('Size') ?></span>
                    <span></span>
                </div>
                <div id="cmTracks" class="cm-tracks"></div>
                <div class="d-flex gap-1 flex-wrap mt-2">
                    <button type="button" id="cmAddTrack" class="btn btn-sm btn-outline-primary" title="<?= h(t('Add track')) ?>">+</button>
                    <button type="button" id="cmEqual12" class="btn btn-sm btn-outline-secondary"><?= t('12 × 1fr') ?></button>
                </div>
                <div class="mt-2">
                    <label class="form-label mb-1" for="cmRowMode"><?= t('Row height') ?></label>
                    <div class="input-group input-group-sm">
                        <select id="cmRowMode" class="form-select" style="max-width:7rem">
                            <option value="minmax">minmax</option>
                            <option value="px">px</option>
                            <option value="rem">rem</option>
                            <option value="percent">%</option>
                            <option value="clamp">clamp()</option>
                            <option value="auto">auto</option>
                        </select>
                        <input type="text" id="cmRowValue" class="form-control" value="64px|auto" placeholder="64px|auto">
                    </div>
                </div>
                <code id="cmGridPreview" class="cm-grid-preview d-block mt-2"></code>
                <div class="mt-2">
                    <label class="form-label mb-1" for="cmSideMargin"><?= t('Side margin') ?> <span class="text-muted">(<?= t('left & right') ?>)</span></label>
                    <input type="text" name="side_margin" id="cmSideMargin" class="form-control form-control-sm" placeholder="<?= h(t('e.g. 1rem — inset from viewport edges')) ?>">
                    <p class="small text-muted mb-0 mt-1"><?= t('Adds horizontal margin around the whole container on the front end.') ?></p>
                </div>
                <div class="mt-2">
                    <div class="cm-panel-title mb-1"><?= t('Narrow viewports') ?></div>
                    <label class="form-label mb-1" for="cmTabletColumns"><?= t('Tablet columns') ?> <span class="text-muted">(≤991px)</span></label>
                    <input type="text" id="cmTabletColumns" class="form-control form-control-sm mb-2" placeholder="<?= h(t('Leave blank to keep desktop tracks')) ?>">
                    <label class="form-label mb-1" for="cmMobileColumns"><?= t('Mobile columns') ?> <span class="text-muted">(≤767px)</span></label>
                    <input type="text" id="cmMobileColumns" class="form-control form-control-sm" placeholder="<?= h(t('e.g. 1fr — single column')) ?>">
                </div>
            </details>
            <?php if ($isEditingContainer) { ?>
            <button type="button" id="cmClear" class="btn btn-sm btn-outline-secondary w-100 mt-3"><?= t('Clear canvas') ?></button>
            <p class="small text-muted mt-1 mb-0"><?= t('Clears the layout, name, handle, and unchecks Overwrite so you can start fresh.') ?></p>
            <?php } ?>
        </aside>

        <section class="cm-main">
            <div class="cm-viewport-bar btn-group btn-group-sm mb-2" role="group" aria-label="<?= h(t('Preview viewport')) ?>">
                <button type="button" class="btn btn-outline-secondary active" data-viewport="desktop"><?= t('Desktop') ?></button>
                <button type="button" class="btn btn-outline-secondary" data-viewport="tablet"><?= t('Tablet') ?> ≤991px</button>
                <button type="button" class="btn btn-outline-secondary" data-viewport="mobile"><?= t('Mobile') ?> ≤767px</button>
            </div>
            <div id="cmCanvas" class="cm-canvas" data-viewport="desktop">
                <div id="cmRowLines" class="cm-row-lines" aria-hidden="true"></div>
                <div id="cmColLines" class="cm-col-lines" aria-hidden="true"></div>
                <div id="cmTiles" class="cm-tiles"></div>
                <div id="cmDropHint" class="cm-drop-hint"><?= t('Drag presets here') ?></div>
            </div>

            <div id="cmSelection" class="cm-selection d-none">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label mb-1"><?= t('Name') ?></label>
                        <input type="text" id="cmSelName" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label mb-1"><?= t('Column') ?></label>
                        <input type="number" id="cmSelCol" class="form-control form-control-sm" min="1">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label mb-1"><?= t('Span') ?></label>
                        <input type="number" id="cmSelSpan" class="form-control form-control-sm" min="1">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label mb-1"><?= t('Row') ?></label>
                        <input type="number" id="cmSelRow" class="form-control form-control-sm" min="1">
                    </div>
                    <div class="col-md-1" id="cmSelRowSpanWrap">
                        <label class="form-label mb-1"><?= t('Row span') ?></label>
                        <input type="number" id="cmSelRowSpan" class="form-control form-control-sm" min="1" value="1">
                    </div>
                    <div class="col-md-2">
                        <button type="button" id="cmSelDelete" class="btn btn-outline-danger btn-sm w-100"><?= t('Remove') ?></button>
                    </div>
                </div>
                <div class="form-check form-check-inline mt-2">
                    <input class="form-check-input" type="checkbox" id="cmSelFull">
                    <label class="form-check-label" for="cmSelFull"><?= t('Full row') ?></label>
                </div>
                <details class="mt-2">
                    <summary class="small text-muted"><?= t('Narrow viewport (per area)') ?></summary>
                    <div class="row g-2 mt-1 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label mb-1"><?= t('Tablet span') ?></label>
                            <input type="number" id="cmSelTabletSpan" class="form-control form-control-sm" min="1" placeholder="<?= h(t('Same as desktop')) ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label mb-1"><?= t('Mobile span') ?></label>
                            <input type="number" id="cmSelMobileSpan" class="form-control form-control-sm" min="1" placeholder="<?= h(t('Full width')) ?>">
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="cmSelMobileStack" checked>
                                <label class="form-check-label" for="cmSelMobileStack"><?= t('Stack full width on mobile') ?></label>
                            </div>
                        </div>
                    </div>
                </details>
                <details class="mt-2" id="cmSelGridOverrides">
                    <summary class="small text-muted"><?= t('CSS grid values (optional overrides)') ?></summary>
                    <div class="row g-2 mt-1">
                        <div class="col-md-4">
                            <label class="form-label mb-1">grid-column</label>
                            <input type="text" id="cmSelGridCol" class="form-control form-control-sm" placeholder="3 / span 4">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label mb-1">grid-row</label>
                            <input type="text" id="cmSelGridRow" class="form-control form-control-sm" placeholder="2 / span 2">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label mb-1">min-height</label>
                            <input type="text" id="cmSelMinH" class="form-control form-control-sm" placeholder="200px, clamp(4rem, 20vh, 12rem)">
                        </div>
                    </div>
                </details>
            </div>
        </section>

        <aside class="cm-code-wrap">
            <div class="d-flex align-items-center justify-content-between gap-2">
                <div class="cm-panel-title mb-0"><?= t('Generated code') ?></div>
                <div class="form-check form-switch mb-0" title="<?= h(t('Advanced: hand-edit the code that gets written to the theme file.')) ?>">
                    <input class="form-check-input" type="checkbox" id="cmEditCode">
                    <label class="form-check-label small" for="cmEditCode"><?= t('Edit code') ?></label>
                </div>
            </div>
            <input type="hidden" name="use_custom_code" id="cmUseCustomCode" value="0">
            <pre id="cmCode" class="cm-code"></pre>
            <textarea name="custom_code" id="cmCodeEdit" class="cm-code d-none" spellcheck="false" wrap="off" disabled></textarea>
            <div id="cmEditCodeControls" class="d-none mt-2">
                <div class="alert alert-warning py-2 px-2 small mb-2 d-flex align-items-start gap-2">
                    <span><?= t('You are editing the raw PHP that will be written to the theme. Only do this if you know what you are doing — manual edits are saved verbatim and the visual designer will no longer round-trip perfectly.') ?></span>
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="cmResetCode"><?= t('Reset to generated code') ?></button>
            </div>
        </aside>
    </div>

    <div class="cm-toolbar">
        <div class="row g-2 align-items-end">
            <div class="col-lg-2">
                <label class="form-label"><?= t('Save to theme') ?></label>
                <input type="hidden" name="themeID" id="cmTheme" value="<?= (int) $selectedThemeID ?>">
                <div class="form-control form-control-sm bg-light border-0 text-truncate" title="<?= h(is_object($theme) ? $theme->getThemeName() : '') ?>">
                    <?= h(is_object($theme) ? $theme->getThemeName() : t('No active theme')) ?>
                    <span class="badge bg-secondary align-middle"><?= t('active') ?></span>
                </div>
            </div>
            <div class="col-lg-2">
                <label class="form-label" for="cmName"><?= t('Name') ?></label>
                <input type="text" name="name" id="cmName" class="form-control form-control-sm" required <?= $editingContainer ? '' : 'autofocus' ?>>
            </div>
            <div class="col-lg-2">
                <label class="form-label" for="cmHandle"><?= t('Handle') ?></label>
                <input type="text" name="handle" id="cmHandle" class="form-control form-control-sm" <?= $lockHandle ? 'readonly' : '' ?>>
            </div>
            <div class="col-lg-1" id="cmGapWrap">
                <label class="form-label" for="cmGap"><?= t('Gap') ?> <span class="text-muted small">(<?= t('CSS Grid') ?>)</span></label>
                <input type="text" name="gap" id="cmGap" class="form-control form-control-sm" value="1rem">
            </div>
            <div class="col-lg-2">
                <label class="form-label" for="cmInner"><?= t('Inner wrapper') ?></label>
                <select name="inner" id="cmInner" class="form-select form-select-sm">
                    <option value=""><?= t('None') ?></option>
                    <option value="container">container</option>
                    <option value="container-fluid">container-fluid</option>
                </select>
            </div>
            <div class="col-lg-3 d-flex gap-3 align-items-center flex-wrap">
                <div class="form-check mb-0">
                    <input class="form-check-input" type="checkbox" name="register" id="cmRegister" value="1" checked>
                    <label class="form-check-label" for="cmRegister"><?= t('Register') ?></label>
                </div>
                <div class="form-check mb-0">
                    <input class="form-check-input" type="checkbox" name="overwrite" id="cmOverwrite" value="1" <?= ($lockHandle || $editingContainer) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="cmOverwrite"><?= t('Overwrite') ?></label>
                </div>
                <button type="submit" class="btn btn-primary btn-sm" id="cmSaveBtn" <?= $canSave ? '' : 'disabled' ?>><?= t('Save') ?></button>
                <?php if ($showImport) { ?>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="cmImportScroll"><?= t('Import') ?></button>
                <?php } ?>
            </div>
        </div>
    </div>
</form>

<?php if ($hasTheme && !empty($registeredContainers)) { ?>
<details class="cm-installed mt-3">
    <summary class="text-muted"><?= t('Containers in %s (%s)', h($theme->getThemeName()), count($registeredContainers)) ?></summary>
    <div class="table-responsive mt-2">
        <table class="table table-sm table-striped mb-0">
            <thead><tr><th><?= t('Name') ?></th><th><?= t('Handle') ?></th><th></th></tr></thead>
            <tbody>
            <?php foreach ($registeredContainers as $container) { ?>
                <tr>
                    <td><?= h($container['name'] ?? '') ?></td>
                    <td><code><?= h($container['handle'] ?? '') ?></code></td>
                    <td class="text-end">
                        <?php if (!empty($container['template_exists'])) { ?>
                            <a class="btn btn-sm btn-outline-primary" href="<?= h($pageUrl) ?>?themeID=<?= (int) $selectedThemeID ?>&amp;edit=<?= h(urlencode($container['handle'] ?? '')) ?>"><?= t('Edit') ?></a>
                        <?php } else { ?>
                            <span class="text-muted small"><?= t('No theme file') ?></span>
                        <?php } ?>
                        <form method="post" action="<?= $view->action('uninstall') ?>" class="d-inline" onsubmit="return confirm('<?= h(t('Uninstall this container?')) ?>');">
                            <?php $token->output('container_maker_uninstall'); ?>
                            <input type="hidden" name="themeID" value="<?= (int) $selectedThemeID ?>">
                            <input type="hidden" name="containerHandle" value="<?= h($container['handle'] ?? '') ?>">
                            <input type="hidden" name="confirm" value="1">
                            <button class="btn btn-sm btn-outline-danger" type="submit"><?= t('Remove') ?></button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</details>
<?php } ?>

<style>
.cm-app{--cm-accent:#0d6efd;--cm-border:#dee2e6;--cm-bg:#f8f9fa;--cm-row-h:72px}
.cm-workspace{display:grid;grid-template-columns:minmax(250px,280px) minmax(0,1fr) minmax(220px,30%);gap:1rem;min-height:460px}
.cm-panel-title{font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:#6c757d;margin-bottom:.35rem}
.cm-palette,.cm-code-wrap{background:var(--cm-bg);border:1px solid var(--cm-border);border-radius:.375rem;padding:.75rem}
.cm-presets{display:grid;gap:.45rem}
.cm-preset{display:flex;align-items:center;gap:.5rem;padding:.4rem .5rem;border:1px solid var(--cm-border);border-radius:.25rem;background:#fff;cursor:grab;font-size:.82rem;user-select:none}
.cm-preset:active{cursor:grabbing}
.cm-preset-icon{font-family:monospace;min-width:2.5rem;text-align:center;opacity:.65}
.cm-preset-preview{display:grid;grid-template-columns:repeat(12,minmax(0,1fr));gap:1px;width:2.5rem;height:.85rem;background:#ddd;border-radius:2px;overflow:hidden}
.cm-preset-preview span{background:var(--cm-accent);opacity:.6}
.cm-main{display:flex;flex-direction:column;gap:.65rem;min-width:0}
.cm-canvas{position:relative;flex:1;min-height:360px;background:#fff;border:2px dashed var(--cm-border);border-radius:.375rem;padding:1rem;transition:border-color .15s,background .15s,max-width .2s;margin:0 auto;width:100%}
.cm-canvas[data-viewport="tablet"]{max-width:991px}
.cm-canvas[data-viewport="mobile"]{max-width:767px}
.cm-viewport-bar .btn.active{background:var(--cm-accent);border-color:var(--cm-accent);color:#fff}
.cm-canvas.cm-drag-over{border-color:var(--cm-accent);background:#f0f6ff}
.cm-col-lines,.cm-row-lines{position:absolute;inset:1rem;pointer-events:none;z-index:0}
.cm-col-lines{display:grid;height:100%}
.cm-col-lines span{border-left:1px dashed rgba(173,181,189,.45);height:100%}
.cm-row-lines{background:repeating-linear-gradient(to bottom,transparent 0,transparent calc(var(--cm-row-h) - 1px),rgba(173,181,189,.35) calc(var(--cm-row-h) - 1px),rgba(173,181,189,.35) var(--cm-row-h))}
.cm-tiles{position:relative;display:grid;grid-auto-rows:var(--cm-row-h);gap:var(--cm-gap,1rem);min-height:calc(var(--cm-row-h) * 3);z-index:1}
.cm-tile{grid-column:var(--col-start,1)/span var(--span,12);border:2px solid var(--cm-accent);background:rgba(13,110,253,.08);padding:.55rem .85rem;position:relative;cursor:grab;touch-action:none;align-self:stretch;display:flex;flex-direction:column;justify-content:center}
.cm-tile.active{box-shadow:0 0 0 3px rgba(13,110,253,.28)}
.cm-tile.cm-dragging{opacity:.88;z-index:5;cursor:grabbing}
.cm-tile-name{font-weight:600;font-size:.85rem;line-height:1.2}
.cm-tile-meta{font-size:.7rem;color:#6c757d;margin-top:.1rem}
.cm-tile-resize-left,.cm-tile-resize-right{position:absolute;top:0;width:14px;height:100%;cursor:col-resize;touch-action:none;z-index:4}
.cm-tile-resize-left{left:0}
.cm-tile-resize-right{right:0}
.cm-tile-resize-left::after,.cm-tile-resize-right::after{content:'';position:absolute;top:50%;width:4px;height:22px;transform:translateY(-50%);border-left:2px dotted rgba(0,0,0,.35);border-right:2px dotted rgba(0,0,0,.35)}
.cm-tile-resize-left::after{left:2px}
.cm-tile-resize-right::after{right:2px}
.cm-tile-resize-left:hover::after,.cm-tile-resize-right:hover::after,.cm-tile.active .cm-tile-resize-left::after,.cm-tile.active .cm-tile-resize-right::after{border-color:var(--cm-accent)}
.cm-canvas[data-viewport="tablet"] .cm-tile-resize-left::after,.cm-canvas[data-viewport="tablet"] .cm-tile-resize-right::after{border-color:rgba(255,153,0,.55)}
.cm-canvas[data-viewport="mobile"] .cm-tile-resize-left::after,.cm-canvas[data-viewport="mobile"] .cm-tile-resize-right::after{border-color:rgba(111,66,193,.55)}
.cm-drop-hint{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;color:#adb5bd;font-size:.95rem;pointer-events:none;z-index:0}
.cm-tiles:not(:empty)~.cm-drop-hint{display:none}
.cm-selection{background:var(--cm-bg);border:1px solid var(--cm-border);border-radius:.375rem;padding:.65rem .75rem}
.cm-code{background:#1e1e1e;color:#d4d4d4;font-size:11px;line-height:1.45;padding:.75rem;border-radius:.25rem;max-height:400px;overflow:auto;margin:0;white-space:pre-wrap;word-break:break-word}
textarea.cm-code{width:100%;min-height:320px;max-height:520px;resize:vertical;border:0;display:block;font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,monospace}
textarea.cm-code:focus{outline:2px solid var(--cm-accent);outline-offset:-2px}
.cm-toolbar{margin-top:1rem;padding-top:1rem;border-top:1px solid var(--cm-border)}
.cm-installed summary{cursor:pointer}
.cm-layout-tabs .nav-link{padding:.4rem .85rem;font-size:.875rem}
.cm-bootstrap-settings{border-top:1px solid var(--cm-border);padding-top:.5rem}
.cm-import-wrap .card-header{background:var(--cm-bg)}
.cm-import-wrap.cm-import-highlight{box-shadow:0 0 0 3px rgba(13,110,253,.25)}
.nav-tabs-sm .nav-link{padding:.25rem .6rem;font-size:.82rem}
.cm-tracks{display:grid;gap:.35rem;max-height:220px;overflow:auto}
.cm-track-header{display:grid;grid-template-columns:4.5rem minmax(5.5rem,1fr) 1.75rem;gap:.35rem;font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:#6c757d;padding:0 .1rem .15rem}
.cm-track-row{display:grid;grid-template-columns:4.5rem minmax(5.5rem,1fr) 1.75rem;gap:.35rem;align-items:center}
.cm-track-fields{display:grid;grid-template-columns:repeat(3,minmax(3.25rem,1fr));gap:.25rem;min-width:0}
.cm-track-fields.cm-track-fields-1{grid-template-columns:minmax(5.5rem,1fr)}
.cm-track-row .form-control,.cm-track-row .form-select{font-size:.8125rem;padding:.25rem .4rem;min-width:0;width:100%}
.cm-track-row input[type="text"],.cm-track-row input:not([type]){font-variant-numeric:tabular-nums}
.cm-grid-preview{font-size:10px;white-space:pre-wrap;word-break:break-word;color:#495057;background:#fff;border:1px solid var(--cm-border);border-radius:.25rem;padding:.35rem .45rem}
@media (max-width:1199px){.cm-workspace{grid-template-columns:1fr}.cm-code-wrap{order:3}}
</style>

<script>
(function(){
    const EDIT = <?= $editingContainer ? json_encode($editingContainer) : 'null' ?>;
    const PAGE_URL = <?= json_encode($pageUrl) ?>;
    const ROW_H = 72;
    const PRESETS = [
        { id:'free', label:'<?= h(t('Free block')) ?>', areas:[{name:'Block',span:6}], hint:'<?= h(t('One area — drag edges to size, drag body to place')) ?>' },
        { id:'full', label:'<?= h(t('Full width')) ?>', areas:[{name:'Main',span:12}] },
        { id:'half', label:'<?= h(t('50 / 50')) ?>', areas:[{name:'Left',span:6},{name:'Right',span:6}] },
        { id:'thirds', label:'<?= h(t('Three columns')) ?>', areas:[{name:'Column 1',span:4},{name:'Column 2',span:4},{name:'Column 3',span:4}] },
        { id:'two-one', label:'<?= h(t('2/3 + 1/3')) ?>', areas:[{name:'Main',span:8},{name:'Aside',span:4}] },
        { id:'one-two', label:'<?= h(t('1/3 + 2/3')) ?>', areas:[{name:'Aside',span:4},{name:'Main',span:8}] },
        { id:'quarters', label:'<?= h(t('Four columns')) ?>', areas:[{name:'Col 1',span:3},{name:'Col 2',span:3},{name:'Col 3',span:3},{name:'Col 4',span:3}] },
        { id:'sidebar-left', label:'<?= h(t('Sidebar left')) ?>', areas:[{name:'Sidebar',span:3},{name:'Content',span:9}] },
        { id:'sidebar-right', label:'<?= h(t('Sidebar right')) ?>', areas:[{name:'Content',span:9},{name:'Sidebar',span:3}] },
        { id:'hero', label:'<?= h(t('Hero + content')) ?>', areas:[{name:'Hero',span:12,full:true},{name:'Content',span:12,full:true}] },
    ];
    const $ = id => document.getElementById(id);
    let handleTouched = false;
    let selected = -1;
    let areas = [];
    let drag = null;
    let tracks = Array.from({length:12}, ()=>({mode:'fr', value:'1', min:'200px', preferred:'1fr', max:'100%'}));
    let rowSize = { mode:'minmax', value:'64px|auto', min:'200px', preferred:'1fr', max:'100%' };
    let previewViewport = 'desktop';
    let layoutMode = 'css_grid';

    function isBootstrap(){ return layoutMode === 'bootstrap5'; }
    function clamp(v,min,max){return Math.max(min,Math.min(max,v));}
    function slug(s){return (s||'').toLowerCase().trim().replace(/[^a-z0-9]+/g,'_').replace(/^_+|_+$/g,'')||'container';}
    function esc(s){return String(s).replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]));}
    function colCount(){ return isBootstrap() ? 12 : Math.max(1, tracks.length);}
    function defaultArea(name,col,span,row){
        row = row || 1;
        return {name, col:col||1, span:span||6, row, rowSpan:1, full:false, padding:'', gridColumn:'', gridRow:'', minHeight:'', tabletSpan:null, mobileSpan:null, mobileStack:true, tabletCol:null, mobileCol:null, tabletRow:row, mobileRow:row};
    }
    function token(v,f){v=String(v==null?'':v).trim(); if(!v) return f; if(/^\d+(\.\d+)?$/.test(v)){ if(f&&/px$/i.test(f)) return v+'px'; if(f&&/%$/i.test(f)) return v+'%'; if(f&&/rem$/i.test(f)) return v+'rem'; return v+'fr'; } return v;}
    function trackToCss(t){
        t=t||{mode:'fr',value:'1'};
        switch(t.mode){
            case 'px': return token(t.value,'200px');
            case 'rem': return token(t.value,'1rem');
            case 'percent': return token(t.value,'100%');
            case 'auto': return 'auto';
            case 'clamp': return 'clamp('+token(t.min,'0px')+', '+token(t.preferred||t.value,'1fr')+', '+token(t.max,'100%')+')';
            case 'minmax': return 'minmax('+token(t.min,'0px')+', '+token(t.max||t.value,'1fr')+')';
            case 'fr':
            default: return token(t.value,'1fr');
        }
    }
    function buildGridColumnsCss(){return tracks.map(trackToCss).join(' ');}
    function buildGridRowsCss(){
        const m=$('cmRowMode')?.value||rowSize.mode||'minmax';
        let css;
        if(m==='auto') css = 'auto';
        else if(m==='clamp') css = 'clamp('+token(rowSize.min,'0px')+', '+token(rowSize.preferred||rowSize.value,'1fr')+', '+token(rowSize.max,'100%')+')';
        else if(m==='minmax'){
            const parts=String($('cmRowValue')?.value||rowSize.value||'64px|auto').split('|').map(s=>s.trim());
            css = 'minmax('+token(parts[0],'64px')+', '+token(parts[1]||parts[0],'auto')+')';
        }
        else if(m==='px') css = token($('cmRowValue')?.value||rowSize.value,'64px');
        else if(m==='rem') css = token($('cmRowValue')?.value||rowSize.value,'4rem');
        else if(m==='percent') css = token($('cmRowValue')?.value||rowSize.value,'100%');
        else css = 'minmax(64px, auto)';
        if (/^clamp\([^)]*(?:fr|%).*?(?:fr|%)/i.test(css)) return 'minmax(64px, auto)';
        return css;
    }
    function syncGridSettings(){
        $('cmGridColumns').value = buildGridColumnsCss();
        $('cmGridRows').value = buildGridRowsCss();
        $('cmGridTracks').value = JSON.stringify({tracks, rowSize:{mode:$('cmRowMode')?.value||rowSize.mode, value:$('cmRowValue')?.value||rowSize.value, min:rowSize.min, preferred:rowSize.preferred, max:rowSize.max}});
        if($('cmGridPreview')) $('cmGridPreview').textContent = 'grid-template-columns: '+buildGridColumnsCss()+'\ngrid-auto-rows: '+buildGridRowsCss();
    }
    function areaDesktopSpan(a){return a.full ? colCount() : a.span;}
    function areaResponsiveSpans(a){
        const cols = colCount();
        const desktop = areaDesktopSpan(a);
        const tablet = (a.tabletSpan === null || a.tabletSpan === '' || a.tabletSpan === undefined)
            ? desktop
            : clamp(parseInt(a.tabletSpan, 10) || desktop, 1, cols);
        let mobile;
        if (a.mobileStack !== false && (a.mobileSpan === null || a.mobileSpan === '' || a.mobileSpan === undefined)) {
            mobile = cols;
        } else if (a.mobileSpan === null || a.mobileSpan === '' || a.mobileSpan === undefined) {
            mobile = tablet;
        } else {
            mobile = clamp(parseInt(a.mobileSpan, 10) || tablet, 1, cols);
        }
        return { desktop, tablet, mobile };
    }
    function areaTabletCol(a){
        return (a.tabletCol === null || a.tabletCol === '' || a.tabletCol === undefined) ? a.col : a.tabletCol;
    }
    function areaMobileCol(a){
        return (a.mobileCol === null || a.mobileCol === '' || a.mobileCol === undefined) ? 1 : a.mobileCol;
    }
    function previewGridColumnsCss(){
        if (isBootstrap()) return 'repeat(12, minmax(0, 1fr))';
        if (previewViewport === 'mobile') {
            const v = ($('cmMobileColumns')?.value || '').trim();
            if (v) return v;
        }
        if (previewViewport === 'tablet') {
            const v = ($('cmTabletColumns')?.value || '').trim();
            if (v) return v;
        }
        return buildGridColumnsCss();
    }
    function previewColCount(){
        if (isBootstrap()) return 12;
        const css = previewGridColumnsCss();
        if (css === buildGridColumnsCss()) return colCount();
        let depth = 0, count = 1;
        for (let i = 0; i < css.length; i++) {
            const c = css[i];
            if (c === '(') depth++;
            else if (c === ')') depth--;
            else if (c === ' ' && depth === 0) count++;
        }
        return Math.max(1, count);
    }
    function effectiveLayout(a){
        const r = areaResponsiveSpans(a);
        const cols = previewColCount();
        if (previewViewport === 'desktop') {
            return { col: a.full ? 1 : a.col, span: a.full ? colCount() : a.span, full: !!a.full };
        }
        if (previewViewport === 'tablet') {
            const span = Math.min(r.tablet, cols);
            const full = span >= cols || !!a.full;
            const col = full ? 1 : clamp(areaTabletCol(a), 1, Math.max(1, cols - span + 1));
            return { col, span: full ? cols : span, full };
        }
        const stack = a.mobileStack !== false && (a.mobileSpan === null || a.mobileSpan === '' || a.mobileSpan === undefined);
        const span = stack ? cols : Math.min(r.mobile, cols);
        const full = stack || span >= cols;
        const col = full ? 1 : clamp(areaMobileCol(a), 1, Math.max(1, cols - span + 1));
        return { col, span, full };
    }

    function isMobileStacked(a){
        return a.mobileStack !== false && (a.mobileSpan === null || a.mobileSpan === '' || a.mobileSpan === undefined);
    }

    function viewportRowCss(row, rowSpan){
        return rowSpan > 1 ? (row + ' / span ' + rowSpan) : String(row);
    }

    function computePreviewPlacements(){
        if (isBootstrap()) {
            const cols = 12;
            if (previewViewport === 'desktop') {
                return areas.map(a => ({
                    col: a.full ? 1 : a.col,
                    span: a.full ? cols : a.span,
                    row: a.row,
                    rowSpan: 1,
                    gridColumn: a.full ? '1 / -1' : (a.col + ' / span ' + a.span),
                    gridRow: String(a.row)
                }));
            }
            return areas.map(a => {
                if (previewViewport === 'tablet') {
                    const r = areaResponsiveSpans(a);
                    const span = Math.min(a.full ? cols : (a.tabletSpan != null && a.tabletSpan !== '' ? parseInt(a.tabletSpan, 10) : r.tablet), cols);
                    const full = !!a.full || span >= cols;
                    const col = full ? 1 : clamp(areaTabletCol(a), 1, Math.max(1, cols - span + 1));
                    const row = a.tabletRow;
                    return {
                        col,
                        span: full ? cols : span,
                        row,
                        rowSpan: 1,
                        gridColumn: full ? '1 / -1' : (col + ' / span ' + span),
                        gridRow: String(row)
                    };
                }
                const stack = isMobileStacked(a);
                const r = areaResponsiveSpans(a);
                const span = stack ? cols : Math.min(a.mobileSpan != null && a.mobileSpan !== '' ? parseInt(a.mobileSpan, 10) : r.mobile, cols);
                const full = stack || span >= cols;
                const col = full ? 1 : clamp(areaMobileCol(a), 1, Math.max(1, cols - span + 1));
                const row = a.mobileRow;
                return {
                    col,
                    span: full ? cols : span,
                    row,
                    rowSpan: 1,
                    gridColumn: full ? '1 / -1' : (col + ' / span ' + span),
                    gridRow: String(row)
                };
            });
        }
        if (previewViewport === 'desktop') {
            return areas.map(a => ({
                col: a.full ? 1 : a.col,
                span: a.full ? colCount() : a.span,
                row: a.row,
                rowSpan: a.rowSpan || 1,
                gridColumn: a.gridColumn || '',
                gridRow: a.gridRow || ''
            }));
        }

        const cols = previewColCount();
        return areas.map(a => {
            const rowSpan = Math.max(1, a.rowSpan || 1);
            if (previewViewport === 'tablet') {
                const r = areaResponsiveSpans(a);
                const span = Math.min(a.full ? cols : (a.tabletSpan != null && a.tabletSpan !== '' ? parseInt(a.tabletSpan, 10) : r.tablet), cols);
                const full = !!a.full || span >= cols;
                const col = full ? 1 : clamp(areaTabletCol(a), 1, Math.max(1, cols - span + 1));
                const row = a.tabletRow;
                return {
                    col,
                    span: full ? cols : span,
                    row,
                    rowSpan,
                    gridColumn: full ? '1 / -1' : (col + ' / span ' + span),
                    gridRow: a.gridRow || (rowSpan > 1 ? (row + ' / span ' + rowSpan) : String(row))
                };
            }

            const stack = isMobileStacked(a);
            const r = areaResponsiveSpans(a);
            const span = stack ? cols : Math.min(a.mobileSpan != null && a.mobileSpan !== '' ? parseInt(a.mobileSpan, 10) : r.mobile, cols);
            const full = stack || span >= cols;
            const col = full ? 1 : clamp(areaMobileCol(a), 1, Math.max(1, cols - span + 1));
            const row = a.mobileRow;
            return {
                col,
                span: full ? cols : span,
                row,
                rowSpan,
                gridColumn: full ? '1 / -1' : (col + ' / span ' + span),
                gridRow: rowSpan > 1 ? (row + ' / span ' + rowSpan) : String(row)
            };
        });
    }

    function previewMaxRow(){
        if (isBootstrap()) {
            if (!areas.length) return 0;
            if (previewViewport === 'desktop') return maxRow();
            return Math.max(...areas.map(a => previewViewport === 'tablet' ? (a.tabletRow || a.row) : (a.mobileRow || a.row)));
        }
        if (previewViewport === 'desktop') return maxRow();
        if (!areas.length) return 0;
        return Math.max(...areas.map(a => {
            const row = previewViewport === 'tablet' ? a.tabletRow : a.mobileRow;
            return row + Math.max(1, a.rowSpan || 1) - 1;
        }));
    }
    function syncBootstrapSettings(){
        const hasTheme = $('cmThemeHasBootstrap')?.checked ? 1 : 0;
        if ($('cmThemeHasBootstrapField')) $('cmThemeHasBootstrapField').value = String(hasTheme);
        if ($('cmBootstrapSettingsField')) {
            $('cmBootstrapSettingsField').value = JSON.stringify({
                theme_has_bootstrap: !!hasTheme,
                gutter: $('cmBootstrapGutter')?.value || 'g-3'
            });
        }
        if ($('cmBootstrapInjectNote')) {
            $('cmBootstrapInjectNote').classList.toggle('d-none', !!hasTheme);
        }
    }

    function syncLayoutModeField(){
        if ($('cmLayoutMode')) $('cmLayoutMode').value = layoutMode;
    }

    function bootstrapGutterGap(){
        const map = { 'g-0': '0', 'g-1': '.25rem', 'g-2': '.5rem', 'g-3': '1rem', 'g-4': '1.5rem', 'g-5': '3rem' };
        return map[$('cmBootstrapGutter')?.value] || '1rem';
    }

    function applyLayoutModeUi(){
        const bootstrap = isBootstrap();
        $('cmGridSettings')?.classList.toggle('d-none', bootstrap);
        $('cmBootstrapSettings')?.classList.toggle('d-none', !bootstrap);
        $('cmSelGridOverrides')?.classList.toggle('d-none', bootstrap);
        $('cmSelRowSpanWrap')?.classList.toggle('d-none', bootstrap);
        $('cmGapWrap')?.classList.toggle('d-none', bootstrap);
        const gapInput = $('cmGap');
        if (gapInput) {
            gapInput.disabled = bootstrap;
            if (bootstrap) gapInput.removeAttribute('name');
            else gapInput.setAttribute('name', 'gap');
        }
        const sideMarginInput = $('cmSideMargin');
        if (sideMarginInput) {
            sideMarginInput.disabled = bootstrap;
            if (bootstrap) sideMarginInput.removeAttribute('name');
            else sideMarginInput.setAttribute('name', 'side_margin');
        }
        document.querySelectorAll('[data-layout-mode]').forEach(btn=>{
            btn.classList.toggle('active', btn.dataset.layoutMode === layoutMode);
        });
        syncLayoutModeField();
        syncBootstrapSettings();
    }

    function setLayoutMode(mode){
        if (mode !== 'css_grid' && mode !== 'bootstrap5') return;
        layoutMode = mode;
        if (isBootstrap()) {
            tracks = Array.from({length:12}, ()=>({mode:'fr', value:'1', min:'200px', preferred:'1fr', max:'100%'}));
            areas.forEach(a=>{
                if (!a.full) a.span = clamp(a.span, 1, 12);
                a.col = clamp(a.col, 1, 12);
            });
            normalizeBootstrapLayout();
            renderTracks();
        }
        applyLayoutModeUi();
        renderTiles();
    }

    function bootstrapColClasses(a){
        if (a.full) return 'col-12';
        const r = areaResponsiveSpans(a);
        const mobileStack = isMobileStacked(a);
        const mobile = mobileStack ? 12 : r.mobile;
        const tablet = (a.tabletSpan != null && a.tabletSpan !== '') ? parseInt(a.tabletSpan, 10) : r.tablet;
        const mobileSpan = (a.mobileSpan != null && a.mobileSpan !== '') ? parseInt(a.mobileSpan, 10) : mobile;
        const desktopCol = a.col || 1;
        const tabletCol = (a.tabletCol != null && a.tabletCol !== '') ? a.tabletCol : desktopCol;
        const mobileCol = (a.mobileCol != null && a.mobileCol !== '') ? a.mobileCol : 1;
        const classes = ['col-' + mobileSpan];
        if (mobileCol > 1 && mobileSpan < 12) classes.push('offset-' + (mobileCol - 1));
        classes.push('col-md-' + tablet);
        if (tabletCol > 1 && tablet < 12) classes.push('offset-md-' + (tabletCol - 1));
        classes.push('col-lg-' + r.desktop);
        if (desktopCol > 1 && r.desktop < 12) classes.push('offset-lg-' + (desktopCol - 1));
        return classes.join(' ');
    }

    function bootstrapRowsHtml(cls){
        const gutter = $('cmBootstrapGutter')?.value || 'g-3';
        const hasThemeBootstrap = $('cmThemeHasBootstrap')?.checked;
        const byRow = {};
        areas.forEach(a=>{
            const row = a.row || 1;
            if (!byRow[row]) byRow[row] = [];
            byRow[row].push(a);
        });
        const rows = Object.keys(byRow).map(Number).sort((a,b)=>a-b);
        let itemIndex = 0;
        const parts = [];
        rows.forEach((rowNum, rowIndex)=>{
            if (rowIndex > 0) {
                parts.push('<div class="' + (hasThemeBootstrap ? 'w-100 ' : '') + cls + '__row-break" aria-hidden="true"></div>');
            }
            byRow[rowNum].slice().sort((a,b)=>(a.col||1)-(b.col||1)).forEach(a=>{
                itemIndex++;
                const colClass = cls + '__item ' + cls + '__item--' + itemIndex + ' ' + bootstrapColClasses(a);
                let style = '';
                if (a.minHeight) style += 'min-height:' + a.minHeight + ';';
                if (a.padding) style += 'padding:' + a.padding + ';';
                const styleAttr = style ? ' style="'+style+'"' : '';
                parts.push('<div class="'+colClass+'"'+styleAttr+'>\n'+indent(areaBlock(a.name),4)+'\n</div>');
            });
        });
        return '<div class="row '+gutter+'">\n'+indent(parts.join('\n'),4)+'\n</div>';
    }

    function buildBootstrapScopedCssPreview(cls){
        const gutter = $('cmBootstrapGutter')?.value || 'g-3';
        const scope = '.'+cls;
        return '/* Scoped Bootstrap 5 grid for '+scope+' */\n'+scope+' .row { display: flex; flex-wrap: wrap; }\n'+scope+' [class*="col-"] { box-sizing: border-box; }\n/* … col-1 … col-12, col-md-*, col-lg-* … */';
    }

    function renderBootstrapCode(){
        const handle = slug($('cmHandle').value || $('cmName').value);
        const cls = 'cm-'+handle.replace(/_/g,'-');
        const inner = $('cmInner').value;
        const rows = bootstrapRowsHtml(cls);
        const wrapped = inner ? '<div class="'+inner+'">\n'+indent(rows,4)+'\n</div>' : rows;
        const body = '<div class="'+cls+'">\n'+indent(wrapped,4)+'\n</div>';
        const hasThemeBootstrap = $('cmThemeHasBootstrap')?.checked;
        let css = '';
        if (!hasThemeBootstrap) css += buildBootstrapScopedCssPreview(cls) + '\n';
        css += '.ccm-edit-mode .'+cls+' .ccm-area { min-height: 48px; outline: 1px dashed rgba(0,0,0,.25); outline-offset: -1px; }';
        const headerPhp = "\n$cmContainerClass = '"+cls+"';\n"
            +"$cmContainerCss = "+JSON.stringify(css)+";\n"
            +"if (!isset($GLOBALS['cm_container_styles_added'])) {\n"
            +"    $GLOBALS['cm_container_styles_added'] = [];\n"
            +"}\n"
            +"if (!in_array($cmContainerClass, $GLOBALS['cm_container_styles_added'], true)) {\n"
            +"    \\Concrete\\Core\\View\\View::getInstance()->addHeaderItem('<style>' . $cmContainerCss . '</style>');\n"
            +"    $GLOBALS['cm_container_styles_added'][] = $cmContainerClass;\n"
            +"}\n";
        setGeneratedCode("<?php\ndefined('C5_EXECUTE') or die('Access Denied.');\n\nuse Concrete\\Core\\Area\\ContainerArea;\n"+headerPhp+"?>\n"+body+'\n');
    }

    function syncViewportSettings(){
        if ($('cmViewportSettings')) {
            $('cmViewportSettings').value = JSON.stringify({
                tablet_columns: ($('cmTabletColumns')?.value || '').trim(),
                mobile_columns: ($('cmMobileColumns')?.value || '').trim()
            });
        }
        syncBootstrapSettings();
    }
    function areaCssParts(a){
        const cc=a.full?'1 / -1':(a.gridColumn||(a.col+' / span '+(a.full?colCount():a.span)));
        let gr=a.gridRow;
        if(!gr){ gr=a.rowSpan>1?(a.row+' / span '+a.rowSpan):String(a.row); }
        const parts=['grid-column: '+cc,'grid-row: '+gr];
        if(a.minHeight) parts.push('min-height: '+a.minHeight);
        return parts;
    }

    function usedNames(){return new Set(areas.map(a=>a.name));}
    function uniqueName(base){
        base = (base||'Area').trim() || 'Area';
        if(!usedNames().has(base)) return base;
        let i = 2;
        while(usedNames().has(base+' '+i)) i++;
        return base+' '+i;
    }

    function maxRow(){return areas.length ? Math.max(...areas.map(a=>a.row)) : 0;}

    function areaDesktopBounds(a){
        const cols = colCount();
        return {
            colStart: a.full ? 1 : (a.col || 1),
            colEnd: a.full ? cols : (a.col || 1) + Math.max(1, a.span || 1) - 1,
            rowStart: a.row || 1,
            rowEnd: (a.row || 1) + Math.max(1, a.rowSpan || 1) - 1
        };
    }

    function boundsOverlap(b1, b2){
        return b1.colStart <= b2.colEnd && b2.colStart <= b1.colEnd
            && b1.rowStart <= b2.rowEnd && b2.rowStart <= b1.rowEnd;
    }

    function bootstrapAreaBounds(a, viewport){
        const cols = 12;
        if (viewport === 'desktop') {
            return {
                colStart: a.full ? 1 : (a.col || 1),
                colEnd: a.full ? cols : (a.col || 1) + Math.max(1, a.span || 1) - 1,
                rowStart: a.row || 1,
                rowEnd: a.row || 1
            };
        }
        const r = areaResponsiveSpans(a);
        let span, col, row;
        if (viewport === 'tablet') {
            span = Math.min(a.full ? cols : (a.tabletSpan != null && a.tabletSpan !== '' ? parseInt(a.tabletSpan, 10) : r.tablet), cols);
            const full = !!a.full || span >= cols;
            col = full ? 1 : clamp(areaTabletCol(a), 1, Math.max(1, cols - span + 1));
            row = a.tabletRow || a.row || 1;
            return { colStart: col, colEnd: col + span - 1, rowStart: row, rowEnd: row };
        }
        const stack = isMobileStacked(a);
        span = stack ? cols : Math.min(a.mobileSpan != null && a.mobileSpan !== '' ? parseInt(a.mobileSpan, 10) : r.mobile, cols);
        const full = stack || span >= cols;
        col = full ? 1 : clamp(areaMobileCol(a), 1, Math.max(1, cols - span + 1));
        row = a.mobileRow || a.row || 1;
        return { colStart: col, colEnd: col + span - 1, rowStart: row, rowEnd: row };
    }

    function wouldBootstrapViewportOverlap(viewport, excludeIndex, col, span, row, full, extraAreas){
        if (!isBootstrap()) return false;
        const cols = 12;
        const bounds = {
            colStart: full ? 1 : col,
            colEnd: full ? cols : col + Math.max(1, span) - 1,
            rowStart: row,
            rowEnd: row
        };
        for (let i = 0; i < areas.length; i++) {
            if (i === excludeIndex) continue;
            if (boundsOverlap(bounds, bootstrapAreaBounds(areas[i], viewport))) return true;
        }
        for (const a of (extraAreas || [])) {
            if (boundsOverlap(bounds, bootstrapAreaBounds(a, viewport))) return true;
        }
        return false;
    }

    function findFreeBootstrapViewportPlacement(viewport, span, excludeIndex, extraAreas){
        span = Math.max(1, span);
        const cols = 12;
        const maxRowAtViewport = viewport === 'desktop'
            ? maxRow()
            : Math.max(0, ...areas.map(a => viewport === 'tablet' ? (a.tabletRow || a.row || 1) : (a.mobileRow || a.row || 1)));
        const maxScan = Math.max(maxRowAtViewport + 4, 8);
        for (let row = 1; row <= maxScan; row++) {
            for (let col = 1; col <= cols - span + 1; col++) {
                if (!wouldBootstrapViewportOverlap(viewport, excludeIndex, col, span, row, false, extraAreas)) {
                    return { col, row };
                }
            }
        }
        return { col: 1, row: maxScan };
    }

    function applyBootstrapViewportPlacement(a, viewport, col, span, row, full){
        const cols = 12;
        if (viewport === 'desktop') {
            if (full) {
                a.full = true;
                a.col = 1;
                a.span = cols;
            } else {
                a.full = false;
                a.col = col;
                a.span = span;
            }
            a.row = row;
            return;
        }
        if (viewport === 'tablet') {
            a.tabletCol = col;
            a.tabletSpan = span;
            a.tabletRow = row;
            return;
        }
        a.mobileCol = col;
        a.mobileSpan = span;
        a.mobileRow = row;
        a.mobileStack = full || span >= cols;
    }

    function normalizeBootstrapAreaAtViewport(a, index, viewport){
        const saved = previewViewport;
        previewViewport = viewport;
        const layout = effectiveLayout(a);
        previewViewport = saved;
        let col = layout.col;
        let span = layout.span;
        let row = viewport === 'desktop' ? (a.row || 1) : (viewport === 'tablet' ? (a.tabletRow || a.row || 1) : (a.mobileRow || a.row || 1));
        const full = !!layout.full;
        if (wouldBootstrapViewportOverlap(viewport, index, col, span, row, full)) {
            const free = findFreeBootstrapViewportPlacement(viewport, full ? 12 : span, index);
            col = full ? 1 : free.col;
            row = free.row;
        }
        applyBootstrapViewportPlacement(a, viewport, col, full ? 12 : span, row, full);
    }

    function tryBootstrapGeometry(index, col, span, row, rowSpan, full, viewport){
        if (!isBootstrap()) return true;
        viewport = viewport || previewViewport;
        return !wouldBootstrapViewportOverlap(viewport, index, col, span, row, full);
    }

    function normalizeBootstrapLayout(){
        if (!isBootstrap()) return;
        areas.forEach(a => {
            a.rowSpan = 1;
            a.gridColumn = '';
            a.gridRow = '';
        });
        ['desktop', 'tablet', 'mobile'].forEach(viewport => {
            areas.forEach((a, i) => normalizeBootstrapAreaAtViewport(a, i, viewport));
        });
    }

    function gridMetrics(){
        const tiles = $('cmTiles');
        const rect = tiles.getBoundingClientRect();
        const cols = previewViewport === 'desktop' ? colCount() : previewColCount();
        return { rect, colW: rect.width / Math.max(1, cols), rowH: ROW_H, cols };
    }

    function pointerToCell(clientX, clientY, span){
        const { rect, colW, rowH, cols } = gridMetrics();
        span = span || 1;
        let col = Math.floor((clientX - rect.left) / colW) + 1;
        let row = Math.floor((clientY - rect.top) / rowH) + 1;
        col = clamp(col, 1, cols + 1 - span);
        row = Math.max(1, row);
        return { col, row };
    }

    function layoutPresetAreas(presetAreas, startCol, startRow){
        const cols = colCount();
        let col = startCol;
        let row = startRow;
        const out = [];
        presetAreas.forEach(a=>{
            const span = a.full ? cols : a.span;
            if(col + span - 1 > cols){ col = 1; row++; }
            let placeCol = a.full ? 1 : col;
            let placeRow = row;
            if (isBootstrap()) {
                if (wouldBootstrapViewportOverlap('desktop', -1, placeCol, span, placeRow, !!a.full, out)) {
                    const free = findFreeBootstrapViewportPlacement('desktop', span, -1, out);
                    placeCol = a.full ? 1 : free.col;
                    placeRow = free.row;
                }
            }
            out.push(defaultArea(uniqueName(a.name), placeCol, span, placeRow));
            out[out.length-1].full = !!a.full;
            if(a.full){ col = 1; row = placeRow + 1; }
            else {
                col = placeCol + span;
                if(col > cols){ col = 1; row = placeRow + 1; }
                else { row = placeRow; }
            }
        });
        return out;
    }

    function nextFreeRow(){
        return maxRow() + 1;
    }

    function updateCanvasHeight(){
        const rows = Math.max(4, previewMaxRow() + 2);
        $('cmCanvas').style.setProperty('--cm-rows', rows);
        $('cmRowLines').style.height = (rows * ROW_H) + 'px';
        $('cmTiles').style.minHeight = (rows * ROW_H) + 'px';
    }

    function presetPreviewHtml(list){
        if(list.length === 1 && !list[0].full){
            const span = list[0].span;
            const start = Math.max(1, Math.floor((12 - span) / 2) + 1);
            return '<span class="cm-preset-preview"><span style="grid-column:'+start+' / span '+span+'"></span></span>';
        }
        return '<span class="cm-preset-preview">'+list.map(a=>'<span style="grid-column:span '+(a.full?12:a.span)+'"></span>').join('')+'</span>';
    }

    function applyAreaToTile(a, tile, placement){
        if(!tile) return;
        placement = placement || computePreviewPlacements()[areas.indexOf(a)];
        if(!placement){
            placement = { col: a.col, span: a.span, row: a.row, rowSpan: a.rowSpan || 1, gridColumn: a.gridColumn || '', gridRow: a.gridRow || '' };
        }
        if(previewViewport === 'desktop' && a.gridColumn){
            tile.style.gridColumn = a.gridColumn;
            tile.style.removeProperty('--col-start');
            tile.style.removeProperty('--span');
        } else if(placement.gridColumn){
            tile.style.gridColumn = placement.gridColumn;
            tile.style.removeProperty('--col-start');
            tile.style.removeProperty('--span');
        } else {
            tile.style.setProperty('--col-start', placement.col);
            tile.style.setProperty('--span', placement.span);
            tile.style.gridColumn = '';
        }
        tile.style.gridRow = placement.gridRow || (placement.rowSpan > 1 ? (placement.row + ' / span ' + placement.rowSpan) : String(placement.row));
        const meta = tile.querySelector('.cm-tile-meta');
        if(meta) meta.textContent = tileMeta(a, placement);
    }

    function showResizeHandles(a){
        if(isBootstrap() && a.gridColumn) return false;
        if(a.gridColumn && previewViewport === 'desktop') return false;
        if(a.full && previewViewport === 'desktop') return false;
        return true;
    }

    function prepareViewportResize(a){
        const layout = effectiveLayout(a);
        if(previewViewport === 'tablet'){
            if(a.tabletSpan === null || a.tabletSpan === '' || a.tabletSpan === undefined){
                a.tabletSpan = layout.span;
            }
            if(a.tabletCol === null || a.tabletCol === '' || a.tabletCol === undefined){
                a.tabletCol = layout.col;
            }
        }
        if(previewViewport === 'mobile'){
            if(a.mobileCol === null || a.mobileCol === '' || a.mobileCol === undefined){
                a.mobileCol = layout.col;
            }
            if(a.mobileStack !== false && (a.mobileSpan === null || a.mobileSpan === '' || a.mobileSpan === undefined)){
                a.mobileStack = false;
                a.mobileSpan = layout.span;
            } else if(a.mobileSpan === null || a.mobileSpan === '' || a.mobileSpan === undefined){
                a.mobileSpan = layout.span;
            }
        }
        return effectiveLayout(a);
    }

    function prepareViewportMove(a){
        const layout = effectiveLayout(a);
        if(previewViewport === 'tablet'){
            if(a.tabletSpan === null || a.tabletSpan === '' || a.tabletSpan === undefined){
                a.tabletSpan = layout.span;
            }
            if(a.tabletCol === null || a.tabletCol === '' || a.tabletCol === undefined){
                a.tabletCol = layout.col;
            }
            if(a.tabletRow == null || a.tabletRow === ''){
                a.tabletRow = a.row;
            }
        }
        if(previewViewport === 'mobile'){
            if(a.mobileStack === false){
                if(a.mobileSpan === null || a.mobileSpan === '' || a.mobileSpan === undefined){
                    a.mobileSpan = layout.span;
                }
                if(a.mobileCol === null || a.mobileCol === '' || a.mobileCol === undefined){
                    a.mobileCol = layout.col;
                }
            }
            if(a.mobileRow == null || a.mobileRow === ''){
                a.mobileRow = a.row;
            }
        }
        return effectiveLayout(a);
    }

    function renderPresets(){
        $('cmPresets').innerHTML = PRESETS.map(p =>
            '<div class="cm-preset" draggable="true" data-preset="'+p.id+'" title="'+esc(p.hint || p.label)+'">'+presetPreviewHtml(p.areas)+'<span>'+esc(p.label)+'</span></div>'
        ).join('');
    }

    function buildColLines(){
        const cols = previewViewport === 'desktop' ? colCount() : previewColCount();
        $('cmColLines').style.gridTemplateColumns = previewGridColumnsCss();
        $('cmColLines').innerHTML = Array(cols).fill('<span></span>').join('');
    }

    function tileMeta(a, placement){
        placement = placement || (computePreviewPlacements()[areas.indexOf(a)] || effectiveLayout(a));
        const p = typeof placement.row === 'number' ? placement : null;
        const col = p ? p.col : placement.col;
        const span = p ? p.span : placement.span;
        const row = p ? p.row : a.row;
        let meta = 'col '+col+' · span '+span+' · row '+row;
        const rowSpan = p ? p.rowSpan : (a.rowSpan || 1);
        if(rowSpan > 1) meta += ' · row-span '+rowSpan;
        if(a.gridColumn && previewViewport === 'desktop') meta += ' · '+a.gridColumn;
        if(a.minHeight) meta += ' · min-h '+a.minHeight;
        if(previewViewport !== 'desktop'){
            const r = areaResponsiveSpans(a);
            meta += ' · '+previewViewport+' span '+r[previewViewport];
        }
        return meta;
    }

    function trackValueMeta(mode){
        switch(mode){
            case 'fr': return { placeholder: '1', title: 'Fraction of free space (1 = 1fr, 2 = 2fr)' };
            case 'px': return { placeholder: '200', title: 'Width in pixels (200 = 200px)' };
            case 'rem': return { placeholder: '1', title: 'Width in rem (1 = 1rem)' };
            case 'percent': return { placeholder: '50', title: 'Width as percent (50 = 50%)' };
            default: return { placeholder: '', title: '' };
        }
    }

    function renderTracks(){
        const modes = [['fr','fr'],['px','px'],['rem','rem'],['percent','%'],['auto','auto'],['clamp','clamp()'],['minmax','minmax()']];
        $('cmTracks').innerHTML = tracks.map((t,i)=>{
            const meta = trackValueMeta(t.mode);
            const fields = t.mode==='clamp'
                ? '<input class="form-control form-control-sm" data-track="'+i+'" data-field="min" value="'+esc(t.min||'200px')+'" placeholder="min" title="Minimum size">'
                +'<input class="form-control form-control-sm" data-track="'+i+'" data-field="preferred" value="'+esc(t.preferred||t.value||'1fr')+'" placeholder="pref" title="Preferred size">'
                +'<input class="form-control form-control-sm" data-track="'+i+'" data-field="max" value="'+esc(t.max||'100%')+'" placeholder="max" title="Maximum size">'
                : (t.mode==='minmax'
                    ? '<input class="form-control form-control-sm" data-track="'+i+'" data-field="min" value="'+esc(t.min||'0px')+'" placeholder="min" title="Minimum size">'
                    +'<input class="form-control form-control-sm" data-track="'+i+'" data-field="max" value="'+esc(t.max||t.value||'1fr')+'" placeholder="max" title="Maximum size">'
                    : (t.mode==='auto'
                        ? '<span class="small text-muted">auto</span>'
                        : '<input class="form-control form-control-sm" data-track="'+i+'" data-field="value" value="'+esc(t.value||'1')+'" placeholder="'+esc(meta.placeholder)+'" title="'+esc(meta.title)+'" aria-label="<?= h(t('Column track size')) ?> '+(i+1)+'">'));
            const cls = t.mode==='clamp' ? 'cm-track-fields' : (t.mode==='minmax' ? 'cm-track-fields' : 'cm-track-fields cm-track-fields-1');
            return '<div class="cm-track-row"><select class="form-select form-select-sm" data-track="'+i+'" data-field="mode" aria-label="<?= h(t('Column track type')) ?> '+(i+1)+'">'+modes.map(m=>'<option value="'+m[0]+'"'+(t.mode===m[0]?' selected':'')+'>'+m[1]+'</option>').join('')+'</select><div class="'+cls+'">'+fields+'</div><button type="button" class="btn btn-sm btn-outline-danger" data-remove-track="'+i+'" aria-label="<?= h(t('Remove track')) ?>">×</button></div>';
        }).join('');
        syncGridSettings();
    }

    function renderTiles(){
        if (isBootstrap()) normalizeBootstrapLayout();
        const gap = isBootstrap() ? bootstrapGutterGap() : ($('cmGap').value || '1rem');
        $('cmTiles').style.setProperty('--cm-gap', gap);
        const sideMargin = (!isBootstrap() && ($('cmSideMargin')?.value || '').trim()) || '';
        if ($('cmCanvas')) {
            $('cmCanvas').style.marginLeft = sideMargin;
            $('cmCanvas').style.marginRight = sideMargin;
        }
        $('cmTiles').style.gridTemplateColumns = previewGridColumnsCss();
        $('cmCanvas').style.setProperty('--cm-row-h', ROW_H+'px');
        $('cmCanvas').dataset.viewport = previewViewport;
        buildColLines();
        updateCanvasHeight();
        const previewPlacements = computePreviewPlacements();
        $('cmTiles').innerHTML = areas.map((a,i)=>{
            const p = previewPlacements[i];
            const handles = showResizeHandles(a) ?
                '<div class="cm-tile-resize-left" data-resize="'+i+'" data-edge="left" title="<?= h(t('Resize from left')) ?>"></div>'
                +'<div class="cm-tile-resize-right" data-resize="'+i+'" data-edge="right" title="<?= h(t('Resize from right')) ?>"></div>' : '';
            const colStyle = (previewViewport === 'desktop' && a.gridColumn)
                ? ('grid-column:'+a.gridColumn+';')
                : (p.gridColumn ? ('grid-column:'+p.gridColumn+';') : ('--col-start:'+p.col+';--span:'+p.span+';'));
            const rowStyle = p.gridRow || (p.rowSpan > 1 ? (p.row + ' / span ' + p.rowSpan) : String(p.row));
            const style = colStyle + 'grid-row:' + rowStyle + ';';
            return '<div class="cm-tile'+(i===selected?' active':'')+'" data-i="'+i+'" style="'+style+'">'
                +handles
                +'<div class="cm-tile-name">'+esc(a.name)+'</div>'
                +'<div class="cm-tile-meta">'+esc(tileMeta(a, p))+'</div></div>';
        }).join('');
        syncGridSettings();
        syncHidden();
        renderCode();
        syncSelectionPanel();
    }

    function syncHidden(){
        $('cmAreas').value = areas.map(a=>a.name).join('\n');
        $('cmPlacement').value = areas.map(a=>{
            const span = a.full ? colCount() : a.span;
            return a.name+'::'+(a.full?1:a.col)+'|'+span+'|'+a.row+'|'+(a.rowSpan||1)+'|'+(a.gridColumn||'')+'|'+(a.gridRow||'')+'|'+(a.minHeight||'');
        }).join('\n');
        $('cmResponsive').value = areas.map(a=>{
            const r = areaResponsiveSpans(a);
            return a.name+'::'+r.desktop+'|'+r.tablet+'|'+r.mobile;
        }).join('\n');
        if ($('cmAreaResponsive')) {
            $('cmAreaResponsive').value = areas.map(a=>{
                const ts = (a.tabletSpan === null || a.tabletSpan === '') ? '' : a.tabletSpan;
                const ms = (a.mobileSpan === null || a.mobileSpan === '') ? '' : a.mobileSpan;
                const tc = (a.tabletCol === null || a.tabletCol === '') ? '' : a.tabletCol;
                const mc = (a.mobileCol === null || a.mobileCol === '') ? '' : a.mobileCol;
                return a.name+'::'+ts+'|'+(ms)+'|'+(a.mobileStack !== false ? 1 : 0)+'|'+tc+'|'+mc+'|'+a.tabletRow+'|'+a.mobileRow;
            }).join('\n');
        }
        syncViewportSettings();
        $('cmFullRows').value = areas.filter(a=>a.full).map(a=>a.name).join('\n');
        $('cmNested').value = areas.map(a=>a.name).join('\n');
        $('cmPadding').value = areas.filter(a=>a.padding).map(a=>a.name+'::'+a.padding).join('\n');
    }

    function syncSelectionPanel(){
        const panel = $('cmSelection');
        if(selected < 0 || !areas[selected]){ panel.classList.add('d-none'); return; }
        panel.classList.remove('d-none');
        const a = areas[selected];
        $('cmSelName').value = a.name;
        $('cmSelCol').value = a.full ? 1 : a.col;
        $('cmSelSpan').value = a.full ? colCount() : a.span;
        $('cmSelRow').value = previewViewport === 'tablet' ? a.tabletRow : (previewViewport === 'mobile' ? a.mobileRow : a.row);
        $('cmSelRowSpan').value = a.rowSpan || 1;
        $('cmSelGridCol').value = a.gridColumn || '';
        $('cmSelGridRow').value = a.gridRow || '';
        $('cmSelMinH').value = a.minHeight || '';
        $('cmSelFull').checked = !!a.full;
        $('cmSelTabletSpan').value = (a.tabletSpan === null || a.tabletSpan === '') ? '' : a.tabletSpan;
        $('cmSelMobileSpan').value = (a.mobileSpan === null || a.mobileSpan === '') ? '' : a.mobileSpan;
        $('cmSelMobileStack').checked = a.mobileStack !== false;
        const cols = colCount();
        $('cmSelCol').max = cols;
        $('cmSelSpan').max = cols;
        $('cmSelCol').disabled = !!a.full;
        $('cmSelSpan').disabled = !!a.full;
        $('cmSelTabletSpan').max = cols;
        $('cmSelMobileSpan').max = cols;
    }

    function indent(text,n){const p=' '.repeat(n);return p+text.replace(/\n/g,'\n'+p);}
    function areaBlock(name){return "<?php\n$area = new ContainerArea($container, '"+name.replace(/'/g,"\\'")+"');\n$area->display($c);\n?>";}

    function buildResponsiveCss(cls){
        const cols = colCount();
        const tabletCols = ($('cmTabletColumns')?.value || '').trim();
        const mobileCols = ($('cmMobileColumns')?.value || '').trim();
        const tabletRules = [];
        const mobileRules = [];
        areas.forEach((a, i) => {
            if (a.full || a.gridColumn) return;
            const sel = '.'+cls+'__grid > *:nth-child('+(i+1)+')';
            const r = areaResponsiveSpans(a);
            const tabletCol = areaTabletCol(a);
            const mobileCol = areaMobileCol(a);
            const rowSpan = Math.max(1, a.rowSpan || 1);
            const tabletSpan = (a.tabletSpan != null && a.tabletSpan !== '') ? parseInt(a.tabletSpan, 10) : r.tablet;
            const mobileSpan = (a.mobileSpan != null && a.mobileSpan !== '') ? parseInt(a.mobileSpan, 10) : r.mobile;
            if (tabletSpan !== r.desktop || tabletCol !== a.col || a.tabletRow !== a.row || rowSpan > 1) {
                let rule = tabletSpan >= cols
                    ? sel+' { grid-column: 1 / -1;'
                    : sel+' { grid-column: '+tabletCol+' / span '+tabletSpan+';';
                if (a.tabletRow !== a.row || rowSpan > 1) {
                    rule += ' grid-row: '+viewportRowCss(a.tabletRow, rowSpan)+';';
                }
                tabletRules.push(rule + ' }');
            }
            const stack = isMobileStacked(a);
            const needsMobile = mobileSpan !== tabletSpan || mobileSpan !== r.desktop || stack
                || mobileCol !== tabletCol || a.mobileRow !== a.tabletRow || a.mobileRow !== a.row || rowSpan > 1;
            if (needsMobile) {
                if (stack && (a.mobileSpan === null || a.mobileSpan === '' || a.mobileSpan === undefined)) {
                    mobileRules.push(sel+' { grid-column: 1 / -1; grid-row: '+viewportRowCss(a.mobileRow, rowSpan)+'; }');
                } else if (mobileSpan >= cols) {
                    mobileRules.push(sel+' { grid-column: 1 / -1; grid-row: '+viewportRowCss(a.mobileRow, rowSpan)+'; }');
                } else {
                    let rule = sel+' { grid-column: '+mobileCol+' / span '+mobileSpan+';';
                    if (a.mobileRow !== a.row || rowSpan > 1) {
                        rule += ' grid-row: '+viewportRowCss(a.mobileRow, rowSpan)+';';
                    }
                    mobileRules.push(rule + ' }');
                }
            }
        });
        let media = '';
        if (tabletRules.length || tabletCols) {
            media += '@media (max-width: 991px) {\n';
            if (tabletCols) media += '  .'+cls+'__grid { grid-template-columns: '+tabletCols+'; }\n';
            media += tabletRules.map(r => '  '+r).join('\n');
            if (tabletRules.length) media += '\n';
            media += '}\n';
        }
        if (mobileRules.length || mobileCols) {
            media += '@media (max-width: 767px) {\n';
            if (mobileCols) media += '  .'+cls+'__grid { grid-template-columns: '+mobileCols+'; }\n';
            media += mobileRules.map(r => '  '+r).join('\n');
            if (mobileRules.length) media += '\n';
            media += '}\n';
        }
        return media;
    }

    let lastGeneratedCode = '';
    function editCodeOn(){ return !!$('cmEditCode')?.checked; }
    function setGeneratedCode(text){
        lastGeneratedCode = text;
        const pre = $('cmCode');
        if (pre) pre.textContent = text;
        // While hand-editing, never clobber what the user typed.
        if (!editCodeOn()) {
            const ta = $('cmCodeEdit');
            if (ta) ta.value = text;
        }
    }
    function setupEditCode(){
        const toggle = $('cmEditCode');
        const pre = $('cmCode');
        const ta = $('cmCodeEdit');
        const controls = $('cmEditCodeControls');
        const flag = $('cmUseCustomCode');
        const reset = $('cmResetCode');
        if (!toggle || !ta || !pre || !flag) return;
        function apply(){
            const on = toggle.checked;
            flag.value = on ? '1' : '0';
            ta.disabled = !on;
            ta.classList.toggle('d-none', !on);
            pre.classList.toggle('d-none', on);
            if (controls) controls.classList.toggle('d-none', !on);
            if (on && (ta.value === '' || ta.value === undefined)) ta.value = lastGeneratedCode;
        }
        toggle.addEventListener('change', apply);
        if (reset) reset.addEventListener('click', ()=>{ ta.value = lastGeneratedCode; });
        apply();
    }

    function renderCode(){
        if (isBootstrap()) {
            renderBootstrapCode();
            return;
        }
        const handle = slug($('cmHandle').value || $('cmName').value);
        const cls = 'cm-'+handle.replace(/_/g,'-');
        const gap = $('cmGap').value || '1rem';
        const inner = $('cmInner').value;
        const items = areas.map(a=>'<div class="'+cls+'__item">\n'+indent(areaBlock(a.name),4)+'\n</div>').join('\n');
        const grid = '<div class="'+cls+'__grid">\n'+indent(items,4)+'\n</div>';
        const wrapped = inner ? '<div class="'+inner+'">\n'+indent(grid,4)+'\n</div>' : grid;
        const body = '<div class="'+cls+'">\n'+indent(wrapped,4)+'\n</div>';
        const sideMargin = ($('cmSideMargin')?.value || '').trim();
        const rules = [];
        if (sideMargin) rules.push('.'+cls+' { margin-left: '+sideMargin+'; margin-right: '+sideMargin+'; }');
        rules.push('.'+cls+'__grid { display: grid; grid-template-columns: '+buildGridColumnsCss()+'; grid-auto-rows: '+buildGridRowsCss()+'; gap: '+gap+'; }');
        areas.forEach((a,i)=>{
            const sel = '.'+cls+'__grid > *:nth-child('+(i+1)+')';
            rules.push(sel+' { '+areaCssParts(a).join('; ')+'; }');
        });
        rules.push('.ccm-edit-mode .'+cls+' .ccm-area { min-height: 48px; outline: 1px dashed rgba(0,0,0,.25); outline-offset: -1px; }');
        const responsiveCss = buildResponsiveCss(cls);
        const css = rules.join('\n') + (responsiveCss ? '\n'+responsiveCss : '');
        const headerPhp = "\n$cmContainerClass = '"+cls+"';\n"
            +"$cmContainerCss = "+JSON.stringify(css)+";\n"
            +"if (!isset($GLOBALS['cm_container_styles_added'])) {\n"
            +"    $GLOBALS['cm_container_styles_added'] = [];\n"
            +"}\n"
            +"if (!in_array($cmContainerClass, $GLOBALS['cm_container_styles_added'], true)) {\n"
            +"    \\Concrete\\Core\\View\\View::getInstance()->addHeaderItem('<style>' . $cmContainerCss . '</style>');\n"
            +"    $GLOBALS['cm_container_styles_added'][] = $cmContainerClass;\n"
            +"}\n";
        setGeneratedCode("<?php\ndefined('C5_EXECUTE') or die('Access Denied.');\n\nuse Concrete\\Core\\Area\\ContainerArea;\n"+headerPhp+"?>\n"+body+'\n');
    }

    function addPresetAt(presetId, clientX, clientY){
        const preset = PRESETS.find(p=>p.id===presetId);
        if(!preset) return;
        const start = areas.length ? pointerToCell(clientX, clientY, colCount()) : { col: 1, row: 1 };
        const placed = layoutPresetAreas(preset.areas, start.col, start.row);
        areas = areas.concat(placed);
        selected = areas.length - placed.length;
        renderTiles();
    }

    function loadEdit(ed){
        if(!ed) return;
        if (ed.layout_mode === 'bootstrap5') {
            layoutMode = 'bootstrap5';
        }
        $('cmName').value = ed.name || '';
        $('cmHandle').value = ed.handle || '';
        handleTouched = true;
        if(ed.theme_id && $('cmTheme')) $('cmTheme').value = String(ed.theme_id);
        if(ed.settings){
            if(ed.settings.gap) $('cmGap').value = ed.settings.gap;
            if(ed.settings.inner !== undefined) $('cmInner').value = ed.settings.inner;
            if(Array.isArray(ed.settings.grid_tracks) && ed.settings.grid_tracks.length){
                tracks = ed.settings.grid_tracks;
            } else if(ed.settings.grid_columns){
                const n = (String(ed.settings.grid_columns).match(/minmax|fr|px|rem|%/g)||[]).length;
                if(n >= 1 && n <= 24) tracks = Array.from({length:n}, ()=>({mode:'fr', value:'1', min:'200px', preferred:'1fr', max:'100%'}));
            }
            if(ed.settings.row_size && ed.settings.row_size.mode){
                rowSize = ed.settings.row_size;
                $('cmRowMode').value = rowSize.mode;
                $('cmRowValue').value = rowSize.value || '64px|auto';
            } else if(ed.settings.grid_rows){
                $('cmRowValue').value = String(ed.settings.grid_rows).replace(/^minmax\(|\)$/g,'').replace(/,\s*/,'|');
            }
            if(ed.settings.tablet_columns) $('cmTabletColumns').value = ed.settings.tablet_columns;
            if(ed.settings.mobile_columns) $('cmMobileColumns').value = ed.settings.mobile_columns;
            if (ed.settings.side_margin) $('cmSideMargin').value = ed.settings.side_margin;
            if (ed.settings.theme_has_bootstrap !== undefined) $('cmThemeHasBootstrap').checked = !!ed.settings.theme_has_bootstrap;
            if (ed.settings.bootstrap_gutter) $('cmBootstrapGutter').value = ed.settings.bootstrap_gutter;
        }
        applyLayoutModeUi();
        if(ed.areas && ed.areas.length){
            areas = ed.areas.map(a=>({
                name: a.name,
                col: parseInt(a.col,10) || 1,
                row: parseInt(a.row,10) || 1,
                rowSpan: parseInt(a.row_span,10) || 1,
                span: parseInt(a.desktop,10) || 12,
                full: !!a.full,
                padding: a.padding || '',
                gridColumn: a.grid_column || '',
                gridRow: a.grid_row || '',
                minHeight: a.min_height || '',
                tabletSpan: (a.tablet_span != null && a.tablet_span !== '') ? parseInt(a.tablet_span, 10) : null,
                mobileSpan: (a.mobile_span != null && a.mobile_span !== '') ? parseInt(a.mobile_span, 10) : null,
                mobileStack: a.mobile_stack !== false,
                tabletCol: (a.tablet_col != null && a.tablet_col !== '') ? parseInt(a.tablet_col, 10) : null,
                mobileCol: (a.mobile_col != null && a.mobile_col !== '') ? parseInt(a.mobile_col, 10) : null,
                tabletRow: (a.tablet_row != null && a.tablet_row !== '') ? parseInt(a.tablet_row, 10) : (parseInt(a.row,10) || 1),
                mobileRow: (a.mobile_row != null && a.mobile_row !== '') ? parseInt(a.mobile_row, 10) : (parseInt(a.row,10) || 1)
            }));
            selected = 0;
        }
        if (isBootstrap()) normalizeBootstrapLayout();
        renderTracks();
        renderTiles();
    }

    function updateSaveBtn(){
        const btn = $('cmSaveBtn');
        if(btn) btn.disabled = !$('cmTheme').value;
    }
    updateSaveBtn();

    // Palette drag
    $('cmPresets').addEventListener('dragstart', e=>{
        const el = e.target.closest('[data-preset]');
        if(!el) return;
        e.dataTransfer.setData('text/cm-preset', el.dataset.preset);
        e.dataTransfer.effectAllowed = 'copy';
    });

    const canvas = $('cmCanvas');
    ['dragenter','dragover'].forEach(ev=>canvas.addEventListener(ev,e=>{e.preventDefault();canvas.classList.add('cm-drag-over');}));
    canvas.addEventListener('dragleave', e=>{ if(!canvas.contains(e.relatedTarget)) canvas.classList.remove('cm-drag-over'); });
    canvas.addEventListener('drop', e=>{
        e.preventDefault();
        canvas.classList.remove('cm-drag-over');
        const preset = e.dataTransfer.getData('text/cm-preset');
        if(preset) addPresetAt(preset, e.clientX, e.clientY);
    });

    $('cmClear')?.addEventListener('click', ()=>{
        if(!EDIT) return;
        const hasContent = areas.length > 0 || ($('cmName')?.value || '').trim() !== '';
        if(hasContent && !confirm('<?= h(t('Clear all areas, container name, and handle? Overwrite will be unchecked.')) ?>')) return;
        areas = [];
        selected = -1;
        $('cmName').value = '';
        const handleInput = $('cmHandle');
        if (handleInput) {
            handleInput.value = '';
            handleInput.readOnly = false;
            handleTouched = false;
        }
        if ($('cmOverwrite')) $('cmOverwrite').checked = false;
        renderTiles();
    });

    // Tile interactions
    $('cmTiles').addEventListener('pointerdown', e=>{
        const resize = e.target.closest('[data-resize]');
        if(resize){
            e.preventDefault();
            const i = parseInt(resize.dataset.resize,10);
            const a = areas[i];
            if(!showResizeHandles(a)) return;
            selected = i;
            const { colW, cols } = gridMetrics();
            const layout = prepareViewportResize(a);
            drag = {
                type:'resize',
                viewport: previewViewport,
                edge: resize.dataset.edge || 'right',
                i,
                startX:e.clientX,
                startCol: layout.col,
                startSpan: layout.span,
                startRight: layout.col + layout.span - 1,
                colW,
                cols,
                moved:false
            };
            resize.setPointerCapture(e.pointerId);
            syncSelectionPanel();
            renderTiles();
            return;
        }
        const tile = e.target.closest('.cm-tile');
        if(!tile || e.target.closest('[data-resize]')) return;
        e.preventDefault();
        selected = parseInt(tile.dataset.i,10);
        const a = areas[selected];
        if(previewViewport === 'desktop' && a.full && !isBootstrap()) {
            syncSelectionPanel();
            renderTiles();
            return;
        }
        const layout = prepareViewportMove(a);
        drag = {
            type:'move',
            viewport: previewViewport,
            i:selected,
            startX:e.clientX,
            startY:e.clientY,
            moved:false,
            span: layout.span
        };
        tile.setPointerCapture(e.pointerId);
        syncSelectionPanel();
        renderTiles();
    });

    document.addEventListener('pointermove', e=>{
        if(!drag) return;
        if(drag.type==='resize'){
            e.preventDefault();
            drag.moved = true;
            const delta = Math.round((e.clientX - drag.startX) / drag.colW);
            const a = areas[drag.i];
            const tile = document.querySelector('.cm-tile[data-i="'+drag.i+'"]');
            const vp = drag.viewport;
            if(vp === 'desktop'){
                a.full = false;
                let nextCol = drag.startCol;
                let nextSpan = drag.startSpan;
                if(drag.edge === 'left'){
                    nextCol = clamp(drag.startCol + delta, 1, drag.startRight);
                    nextSpan = drag.startRight - nextCol + 1;
                } else {
                    nextSpan = clamp(drag.startSpan + delta, 1, drag.cols + 1 - drag.startCol);
                    nextCol = drag.startCol;
                }
                const row = a.row || 1;
                if (!isBootstrap() || tryBootstrapGeometry(drag.i, nextCol, nextSpan, row, 1, false, 'desktop')) {
                    a.col = nextCol;
                    a.span = nextSpan;
                }
            } else if(vp === 'tablet'){
                let nextCol, nextSpan;
                if(drag.edge === 'left'){
                    nextCol = clamp(drag.startCol + delta, 1, drag.startRight);
                    nextSpan = drag.startRight - nextCol + 1;
                } else {
                    nextCol = drag.startCol;
                    nextSpan = clamp(drag.startSpan + delta, 1, drag.cols + 1 - drag.startCol);
                }
                const row = a.tabletRow || a.row || 1;
                if (!isBootstrap() || tryBootstrapGeometry(drag.i, nextCol, nextSpan, row, 1, false, 'tablet')) {
                    a.tabletCol = nextCol;
                    a.tabletSpan = nextSpan;
                }
            } else {
                let nextCol, nextSpan;
                if(drag.edge === 'left'){
                    nextCol = clamp(drag.startCol + delta, 1, drag.startRight);
                    nextSpan = drag.startRight - nextCol + 1;
                } else {
                    nextCol = drag.startCol;
                    nextSpan = clamp(drag.startSpan + delta, 1, drag.cols);
                }
                const row = a.mobileRow || a.row || 1;
                const full = nextSpan >= drag.cols;
                if (!isBootstrap() || tryBootstrapGeometry(drag.i, nextCol, nextSpan, row, 1, full, 'mobile')) {
                    a.mobileCol = nextCol;
                    a.mobileSpan = nextSpan;
                    a.mobileStack = full;
                }
            }
            applyAreaToTile(a, tile);
            syncHidden();
            renderCode();
            syncSelectionPanel();
        } else if(drag.type==='move'){
            if(Math.hypot(e.clientX-drag.startX, e.clientY-drag.startY) < 8) return;
            e.preventDefault();
            drag.moved = true;
            const { col, row } = pointerToCell(e.clientX, e.clientY, drag.span);
            const a = areas[drag.i];
            const layout = effectiveLayout(a);
            const vp = drag.viewport;
            if(vp === 'desktop'){
                if(!layout.full && !a.gridColumn && (!isBootstrap() || tryBootstrapGeometry(drag.i, col, drag.span, row, 1, false, 'desktop'))) {
                    a.col = col;
                }
                if (!isBootstrap() || tryBootstrapGeometry(drag.i, a.full ? 1 : a.col, a.full ? colCount() : (a.span || drag.span), row, 1, a.full, 'desktop')) {
                    a.row = row;
                }
            } else if(vp === 'tablet'){
                if(!layout.full && (!isBootstrap() || tryBootstrapGeometry(drag.i, col, drag.span, row, 1, false, 'tablet'))) {
                    a.tabletCol = col;
                }
                if (!isBootstrap() || tryBootstrapGeometry(drag.i, layout.full ? 1 : (a.tabletCol || col), layout.span, row, 1, layout.full, 'tablet')) {
                    a.tabletRow = row;
                }
            } else {
                if(!layout.full && (!isBootstrap() || tryBootstrapGeometry(drag.i, col, drag.span, row, 1, layout.full, 'mobile'))) {
                    a.mobileCol = col;
                }
                if (!isBootstrap() || tryBootstrapGeometry(drag.i, layout.full ? 1 : (a.mobileCol || col), layout.span, row, 1, layout.full, 'mobile')) {
                    a.mobileRow = row;
                }
            }
            const tile = document.querySelector('.cm-tile[data-i="'+drag.i+'"]');
            if(tile) tile.classList.add('cm-dragging');
            applyAreaToTile(a, tile);
            updateCanvasHeight();
            syncHidden();
            renderCode();
            syncSelectionPanel();
        }
    });

    document.addEventListener('pointerup', ()=>{
        if(!drag) return;
        document.querySelectorAll('.cm-tile.cm-dragging').forEach(t=>t.classList.remove('cm-dragging'));
        if(drag.moved && isBootstrap()) normalizeBootstrapLayout();
        if(drag.moved) renderTiles();
        drag = null;
    });

    // Selection panel
    $('cmSelName').addEventListener('input', e=>{ if(areas[selected]){ areas[selected].name=e.target.value; renderTiles(); }});
    $('cmSelCol').addEventListener('input', e=>{
        if(selected < 0 || !areas[selected] || areas[selected].full) return;
        const col = clamp(parseInt(e.target.value,10)||1,1,colCount());
        const a = areas[selected];
        if (previewViewport === 'tablet') {
            const row = a.tabletRow || a.row || 1;
            const span = a.tabletSpan != null && a.tabletSpan !== '' ? a.tabletSpan : a.span;
            if (!isBootstrap() || tryBootstrapGeometry(selected, col, span, row, 1, false, 'tablet')) {
                a.tabletCol = col;
                renderTiles();
            } else e.target.value = a.tabletCol ?? a.col;
            return;
        }
        if (previewViewport === 'mobile') {
            const row = a.mobileRow || a.row || 1;
            const span = a.mobileSpan != null && a.mobileSpan !== '' ? a.mobileSpan : a.span;
            if (!isBootstrap() || tryBootstrapGeometry(selected, col, span, row, 1, false, 'mobile')) {
                a.mobileCol = col;
                renderTiles();
            } else e.target.value = a.mobileCol ?? 1;
            return;
        }
        if (tryBootstrapGeometry(selected, col, a.span, a.row, 1, false, 'desktop')) {
            a.col = col;
            renderTiles();
        } else {
            e.target.value = a.col;
        }
    });
    $('cmSelSpan').addEventListener('input', e=>{
        if(selected < 0 || !areas[selected] || areas[selected].full) return;
        const span = clamp(parseInt(e.target.value,10)||1,1,colCount());
        const a = areas[selected];
        if (previewViewport === 'tablet') {
            const row = a.tabletRow || a.row || 1;
            const col = a.tabletCol ?? a.col;
            if (!isBootstrap() || tryBootstrapGeometry(selected, col, span, row, 1, false, 'tablet')) {
                a.tabletSpan = span;
                renderTiles();
            } else e.target.value = a.tabletSpan ?? a.span;
            return;
        }
        if (previewViewport === 'mobile') {
            const row = a.mobileRow || a.row || 1;
            const col = a.mobileCol ?? 1;
            if (!isBootstrap() || tryBootstrapGeometry(selected, col, span, row, 1, span >= colCount(), 'mobile')) {
                a.mobileSpan = span;
                a.mobileStack = span >= colCount();
                renderTiles();
            } else e.target.value = a.mobileSpan ?? a.span;
            return;
        }
        if (tryBootstrapGeometry(selected, a.col, span, a.row, 1, false, 'desktop')) {
            a.span = span;
            renderTiles();
        } else {
            e.target.value = a.span;
        }
    });
    $('cmSelRow').addEventListener('input', e=>{
        if(selected < 0 || !areas[selected]) return;
        const v = Math.max(1,parseInt(e.target.value,10)||1);
        const a = areas[selected];
        if (previewViewport === 'tablet') {
            const col = a.tabletCol ?? a.col;
            const span = a.tabletSpan != null && a.tabletSpan !== '' ? a.tabletSpan : a.span;
            if (!isBootstrap() || tryBootstrapGeometry(selected, col, span, v, 1, !!a.full, 'tablet')) {
                a.tabletRow = v;
                renderTiles();
            } else e.target.value = a.tabletRow;
            return;
        }
        if (previewViewport === 'mobile') {
            const col = a.mobileCol ?? 1;
            const span = a.mobileSpan != null && a.mobileSpan !== '' ? a.mobileSpan : a.span;
            const full = isMobileStacked(a) || span >= colCount();
            if (!isBootstrap() || tryBootstrapGeometry(selected, col, span, v, 1, full, 'mobile')) {
                a.mobileRow = v;
                renderTiles();
            } else e.target.value = a.mobileRow;
            return;
        }
        if (tryBootstrapGeometry(selected, a.col, a.span, v, 1, a.full, 'desktop')) {
            a.row = v;
            renderTiles();
        } else {
            e.target.value = a.row;
        }
    });
    $('cmSelRowSpan').addEventListener('input', e=>{
        if (isBootstrap()) return;
        if(areas[selected]){ areas[selected].rowSpan=Math.max(1,parseInt(e.target.value,10)||1); renderTiles(); }
    });
    $('cmSelGridCol').addEventListener('input', e=>{ if(areas[selected]){ areas[selected].gridColumn=e.target.value.trim(); renderTiles(); }});
    $('cmSelGridRow').addEventListener('input', e=>{ if(areas[selected]){ areas[selected].gridRow=e.target.value.trim(); renderTiles(); }});
    $('cmSelMinH').addEventListener('input', e=>{ if(areas[selected]){ areas[selected].minHeight=e.target.value.trim(); renderTiles(); }});
    $('cmSelFull').addEventListener('change', e=>{
        if(selected < 0 || !areas[selected]) return;
        const full = e.target.checked;
        if (!full || tryBootstrapGeometry(selected, 1, colCount(), areas[selected].row, 1, true, previewViewport)) {
            areas[selected].full = full;
            if(full){ areas[selected].col = 1; areas[selected].span = colCount(); }
            renderTiles();
        } else {
            e.target.checked = areas[selected].full;
        }
    });
    $('cmSelTabletSpan').addEventListener('input', e=>{ if(areas[selected]){ const v=e.target.value.trim(); areas[selected].tabletSpan=v===''?null:clamp(parseInt(v,10)||1,1,colCount()); renderTiles(); }});
    $('cmSelMobileSpan').addEventListener('input', e=>{ if(areas[selected]){ const v=e.target.value.trim(); areas[selected].mobileSpan=v===''?null:clamp(parseInt(v,10)||1,1,colCount()); renderTiles(); }});
    $('cmSelMobileStack').addEventListener('change', e=>{ if(areas[selected]){ areas[selected].mobileStack=e.target.checked; renderTiles(); }});
    $('cmSelDelete').addEventListener('click', ()=>{
        if(selected < 0) return;
        areas.splice(selected,1);
        selected = areas.length ? Math.min(selected, areas.length-1) : -1;
        renderTiles();
    });

    ['cmName','cmHandle','cmGap','cmInner','cmSideMargin','cmRowMode','cmRowValue','cmTabletColumns','cmMobileColumns','cmBootstrapGutter'].forEach(id=>{
        $(id).addEventListener('input', ()=>{
            if(id==='cmName' && !handleTouched) $('cmHandle').value = slug($('cmName').value);
            if(id==='cmHandle') handleTouched = true;
            renderTiles();
        });
        if($(id)) $(id).addEventListener('change', ()=>renderTiles());
    });

    $('cmAddTrack').addEventListener('click', ()=>{ tracks.push({mode:'fr', value:'1', min:'200px', preferred:'1fr', max:'100%'}); renderTracks(); renderTiles(); });
    $('cmEqual12').addEventListener('click', ()=>{ tracks = Array.from({length:12}, ()=>({mode:'fr', value:'1', min:'200px', preferred:'1fr', max:'100%'})); renderTracks(); renderTiles(); });
    $('cmTracks').addEventListener('input', e=>{
        const t = e.target;
        const i = parseInt(t.dataset.track,10);
        if(isNaN(i) || !tracks[i]) return;
        if(t.dataset.field==='mode'){ tracks[i].mode = t.value; renderTracks(); }
        else tracks[i][t.dataset.field] = t.value;
        renderTiles();
    });
    $('cmTracks').addEventListener('click', e=>{
        const btn = e.target.closest('[data-remove-track]');
        if(!btn || tracks.length <= 1) return;
        tracks.splice(parseInt(btn.dataset.removeTrack,10), 1);
        renderTracks();
        renderTiles();
    });

    $('cmForm').addEventListener('submit', e=>{
        if(!areas.length){
            e.preventDefault();
            alert('<?= h(t('Add at least one area before saving.')) ?>');
        }
    });

    document.querySelectorAll('.cm-viewport-bar [data-viewport]').forEach(btn=>{
        btn.addEventListener('click', ()=>{
            previewViewport = btn.dataset.viewport || 'desktop';
            document.querySelectorAll('.cm-viewport-bar [data-viewport]').forEach(b=>b.classList.toggle('active', b === btn));
            renderTiles();
        });
    });

    document.querySelectorAll('[data-layout-mode]').forEach(btn=>{
        btn.addEventListener('click', ()=> setLayoutMode(btn.dataset.layoutMode || 'css_grid'));
    });
    $('cmThemeHasBootstrap')?.addEventListener('change', ()=>{ syncBootstrapSettings(); renderTiles(); });

    setupEditCode();

    buildColLines();
    renderPresets();
    renderTracks();
    loadEdit(EDIT);
    applyLayoutModeUi();
    if(!EDIT) renderTiles();

    const importScroll = $('cmImportScroll');
    if (importScroll) {
        importScroll.addEventListener('click', ()=>{
            const section = $('cmImportSection');
            if (section) {
                section.scrollIntoView({ behavior: 'smooth', block: 'start' });
                section.classList.add('cm-import-highlight');
                window.setTimeout(()=>section.classList.remove('cm-import-highlight'), 1200);
            }
        });
    }
})();
</script>
