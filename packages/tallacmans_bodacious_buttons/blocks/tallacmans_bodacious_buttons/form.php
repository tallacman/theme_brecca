<?php

declare(strict_types=1);

use Concrete\Core\File\File;

defined('C5_EXECUTE') or die('Access Denied.');

$app = (isset($app) && $app) ? $app : \Concrete\Core\Support\Facade\Application::getFacadeApplication();
$assetLibrary = $app->make('helper/concrete/asset_library');
$linkContainerId = 'btTallacmansBodaciousButtons-link-container-' . $identifier_getString;
$selectedLinkType = in_array((string) ($link ?? ''), ['page', 'url', 'file'], true) ? (string) $link : 'page';
$isCustomStyle = ($styleMode ?? 'theme') === 'custom';

if (!empty($link_File) && (int) $link_File > 0) {
    $link_File_o = File::getByID((int) $link_File);
    if (!is_object($link_File_o)) {
        unset($link_File_o);
    }
}
?>

<div class="tbb-block-editor row g-3">
    <div class="col-lg-7 tbb-block-editor-controls">
        <fieldset class="mb-3">
            <legend class="fs-6"><?= t('Button Link') ?></legend>

            <div class="ft-smart-link" id="<?= h($linkContainerId) ?>">
                <div class="row g-2">
                    <div class="col-md-6">
                        <?= $form->label('link', t('Link Type'), ['class' => 'form-label']) ?>
                        <?= $form->select($view->field('link'), $link_Options, $link ?? 'page', ['class' => 'form-select form-select-sm ft-smart-link-type']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->label('openIn', t('Open in New Window?'), ['class' => 'form-label']) ?>
                        <?= $form->select($view->field('openIn'), $openIn_options, $openIn ?? '1', ['class' => 'form-select form-select-sm']) ?>
                    </div>
                </div>

                <div class="tbb-link-options mt-2">
                    <div class="mb-2">
                        <?= $form->label('link_Title', t('Button Text'), ['class' => 'form-label']) ?>
                        <?= $form->text($view->field('link_Title'), $link_Title ?? '', ['maxlength' => 35, 'placeholder' => t('Optional'), 'class' => 'form-control form-control-sm tbb-link-title-input']) ?>
                    </div>

                    <div class="tbb-link-type-field" data-link-type="page"<?= $selectedLinkType !== 'page' ? ' style="display:none;"' : '' ?>>
                        <?= $form->label('link_Page', t('Page'), ['class' => 'form-label']) ?>
                        <?= $app->make('helper/form/page_selector')->selectPage($view->field('link_Page'), $link_Page ?? 0) ?>
                    </div>

                    <div class="tbb-link-type-field" data-link-type="url"<?= $selectedLinkType !== 'url' ? ' style="display:none;"' : '' ?>>
                        <?= $form->label('link_URL', t('URL'), ['class' => 'form-label']) ?>
                        <?= $form->text($view->field('link_URL'), $link_URL ?? '', ['maxlength' => 125, 'placeholder' => t('example.com'), 'class' => 'form-control form-control-sm']) ?>
                    </div>

                    <div class="tbb-link-type-field" data-link-type="file"<?= $selectedLinkType !== 'file' ? ' style="display:none;"' : '' ?>>
                        <?= $form->label('link_File', t('File'), ['class' => 'form-label']) ?>
                        <?= $assetLibrary->file(
                            'ccm-b-tbb-link-file-' . $identifier_getString,
                            $view->field('link_File'),
                            t('Choose File'),
                            $link_File_o ?? null
                        ); ?>
                        <div class="form-check mt-1">
                            <?= $form->checkbox($view->field('forceDownload'), '1', !empty($forceDownload)) ?>
                            <?= $form->label('forceDownload', t('Force download'), ['class' => 'form-check-label']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>

        <fieldset class="mb-3">
            <legend class="fs-6"><?= t('Spacing') ?></legend>
            <p class="small text-muted mb-2"><?= t('Padding around the button (px).') ?></p>
            <div class="row g-2 flex-nowrap tbb-spacing-row">
                <div class="col-3 tbb-spacing-field">
                    <?= $form->label('spacingTop', t('Top'), ['class' => 'form-label small mb-1']) ?>
                    <div class="input-group input-group-sm">
                        <?= $form->number($view->field('spacingTop'), $spacingTop ?? 0, ['min' => 0, 'step' => 1, 'class' => 'tbb-spacing-top']) ?>
                        <span class="input-group-text">px</span>
                    </div>
                </div>
                <div class="col-3 tbb-spacing-field">
                    <?= $form->label('spacingRight', t('Right'), ['class' => 'form-label small mb-1']) ?>
                    <div class="input-group input-group-sm">
                        <?= $form->number($view->field('spacingRight'), $spacingRight ?? 0, ['min' => 0, 'step' => 1, 'class' => 'tbb-spacing-right']) ?>
                        <span class="input-group-text">px</span>
                    </div>
                </div>
                <div class="col-3 tbb-spacing-field">
                    <?= $form->label('spacingBottom', t('Bottom'), ['class' => 'form-label small mb-1']) ?>
                    <div class="input-group input-group-sm">
                        <?= $form->number($view->field('spacingBottom'), $spacingBottom ?? 8, ['min' => 0, 'step' => 1, 'class' => 'tbb-spacing-bottom']) ?>
                        <span class="input-group-text">px</span>
                    </div>
                </div>
                <div class="col-3 tbb-spacing-field">
                    <?= $form->label('spacingLeft', t('Left'), ['class' => 'form-label small mb-1']) ?>
                    <div class="input-group input-group-sm">
                        <?= $form->number($view->field('spacingLeft'), $spacingLeft ?? 0, ['min' => 0, 'step' => 1, 'class' => 'tbb-spacing-left']) ?>
                        <span class="input-group-text">px</span>
                    </div>
                </div>
            </div>
        </fieldset>

        <fieldset class="mb-3">
            <legend class="fs-6"><?= t('Styling Mode') ?></legend>
            <?= $form->label('styleMode', t('Styling'), ['class' => 'form-label']) ?>
            <?= $form->select($view->field('styleMode'), $styleMode_options, $styleMode ?? 'theme', ['class' => 'form-select form-select-sm tbb-style-mode-select']) ?>
        </fieldset>

        <div class="tbb-custom-style-fields"<?= $isCustomStyle ? '' : ' style="display:none;"' ?>>
            <fieldset class="mb-3">
                <legend class="fs-6"><?= t('Custom Styling') ?></legend>

                <div class="mb-3">
                    <div class="small fw-semibold mb-2"><?= t('Colors') ?></div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <?= $form->label('buttonBackgroundColor', t('Background'), ['class' => 'form-label small']) ?>
                            <div class="input-group input-group-sm tbb-color-group">
                                <?= $form->text($view->field('buttonBackgroundColor'), $buttonBackgroundColor ?? '#0d6efd', ['maxlength' => 7, 'class' => 'form-control tbb-color-input', 'data-color-field' => 'background']) ?>
                                <input type="color" class="form-control form-control-color tbb-color-picker" data-color-field="background" value="<?= h($buttonBackgroundColor ?: '#0d6efd') ?>" aria-label="<?= t('Background') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <?= $form->label('buttonTextColor', t('Text'), ['class' => 'form-label small']) ?>
                            <div class="input-group input-group-sm tbb-color-group">
                                <?= $form->text($view->field('buttonTextColor'), $buttonTextColor ?? '#ffffff', ['maxlength' => 7, 'class' => 'form-control tbb-color-input', 'data-color-field' => 'text']) ?>
                                <input type="color" class="form-control form-control-color tbb-color-picker" data-color-field="text" value="<?= h($buttonTextColor ?: '#ffffff') ?>" aria-label="<?= t('Text') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <?= $form->label('buttonHoverBackgroundColor', t('Hover Background'), ['class' => 'form-label small']) ?>
                            <div class="input-group input-group-sm tbb-color-group">
                                <?= $form->text($view->field('buttonHoverBackgroundColor'), $buttonHoverBackgroundColor ?? '#0b5ed7', ['maxlength' => 7, 'class' => 'form-control tbb-color-input', 'data-color-field' => 'hover-background']) ?>
                                <input type="color" class="form-control form-control-color tbb-color-picker" data-color-field="hover-background" value="<?= h($buttonHoverBackgroundColor ?: '#0b5ed7') ?>" aria-label="<?= t('Hover Background') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <?= $form->label('buttonHoverTextColor', t('Hover Text'), ['class' => 'form-label small']) ?>
                            <div class="input-group input-group-sm tbb-color-group">
                                <?= $form->text($view->field('buttonHoverTextColor'), $buttonHoverTextColor ?? '#ffffff', ['maxlength' => 7, 'class' => 'form-control tbb-color-input', 'data-color-field' => 'hover-text']) ?>
                                <input type="color" class="form-control form-control-color tbb-color-picker" data-color-field="hover-text" value="<?= h($buttonHoverTextColor ?: '#ffffff') ?>" aria-label="<?= t('Hover Text') ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="small fw-semibold mb-2"><?= t('Borders') ?></div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <?= $form->label('buttonBorderColor', t('Border Color'), ['class' => 'form-label small']) ?>
                            <div class="input-group input-group-sm tbb-color-group">
                                <?= $form->text($view->field('buttonBorderColor'), $buttonBorderColor ?? '#0d6efd', ['maxlength' => 7, 'class' => 'form-control tbb-color-input', 'data-color-field' => 'border']) ?>
                                <input type="color" class="form-control form-control-color tbb-color-picker" data-color-field="border" value="<?= h($buttonBorderColor ?: '#0d6efd') ?>" aria-label="<?= t('Border Color') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <?= $form->label('buttonBorderWidth', t('Border Width'), ['class' => 'form-label small']) ?>
                            <div class="input-group input-group-sm">
                                <?= $form->number($view->field('buttonBorderWidth'), $buttonBorderWidth ?? 0, ['min' => 0, 'max' => 20, 'step' => 1, 'class' => 'tbb-border-width-input']) ?>
                                <span class="input-group-text">px</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <?= $form->label('buttonHoverBorderColor', t('Hover Border Color'), ['class' => 'form-label small']) ?>
                            <div class="input-group input-group-sm tbb-color-group">
                                <?= $form->text($view->field('buttonHoverBorderColor'), $buttonHoverBorderColor ?? '#0b5ed7', ['maxlength' => 7, 'class' => 'form-control tbb-color-input', 'data-color-field' => 'hover-border']) ?>
                                <input type="color" class="form-control form-control-color tbb-color-picker" data-color-field="hover-border" value="<?= h($buttonHoverBorderColor ?: '#0b5ed7') ?>" aria-label="<?= t('Hover Border Color') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <?= $form->label('buttonHoverBorderWidth', t('Hover Border Width'), ['class' => 'form-label small']) ?>
                            <div class="input-group input-group-sm">
                                <?= $form->number($view->field('buttonHoverBorderWidth'), $buttonHoverBorderWidth ?? 0, ['min' => 0, 'max' => 20, 'step' => 1, 'class' => 'tbb-hover-border-width-input']) ?>
                                <span class="input-group-text">px</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="small fw-semibold mb-2"><?= t('Typography') ?></div>
                    <div class="mb-2">
                        <?= $form->label('fontFamily', t('Font Family'), ['class' => 'form-label small']) ?>
                        <?= $form->select($view->field('fontFamily'), $fontFamily_options, $fontFamily ?? '', ['class' => 'form-select form-select-sm tbb-font-family-select']) ?>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <?= $form->label('fontWeight', t('Font Weight'), ['class' => 'form-label small']) ?>
                            <?= $form->select($view->field('fontWeight'), $fontWeight_options, $fontWeight ?? '400', ['class' => 'form-select form-select-sm tbb-font-weight-select']) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->label('fontSize', t('Font Size'), ['class' => 'form-label small']) ?>
                            <div class="input-group input-group-sm">
                                <?= $form->number($view->field('fontSize'), $fontSize ?? 16, ['min' => 1, 'step' => 1, 'class' => 'tbb-font-size-input']) ?>
                                <span class="input-group-text">px</span>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>

    <div class="col-lg-5 tbb-block-editor-preview">
        <div class="tbb-preview-panel border rounded bg-light h-100">
            <div class="tbb-preview-panel-header px-3 py-2 border-bottom bg-white rounded-top">
                <strong class="small text-uppercase text-muted"><?= t('Preview') ?></strong>
            </div>
            <div class="tbb-preview-panel-body p-3">
                <div class="tbb-button-preview">
                    <div class="tbb-button-preview-wrapper text-center">
                        <span class="tbb-button-preview-button"><?= h($link_Title ?: t('Learn More')) ?></span>
                    </div>
                    <p class="help-block tbb-button-preview-hint mb-0 mt-3 small text-muted text-center">
                        <?= t('Hover the button to preview hover colors when using custom styling.') ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.tbb-block-editor-controls {
    padding-right: 0.25rem;
}

.tbb-spacing-row {
    margin-right: 0;
    margin-left: 0;
}

.tbb-spacing-field {
    min-width: 0;
}

.tbb-spacing-field .input-group {
    width: 100%;
}

.tbb-block-editor-preview {
    position: sticky;
    top: 0;
    align-self: flex-start;
}

.tbb-preview-panel {
    min-height: 220px;
}

.tbb-preview-panel-body {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 180px;
}

.tbb-button-preview {
    width: 100%;
}

.tbb-button-preview-wrapper {
    display: inline-block;
    max-width: 100%;
}

.tbb-button-preview-button {
    display: inline-block;
    margin: 0;
    padding: 0.375rem 0.75rem;
    border-radius: 0.25rem;
    border-style: solid;
    line-height: 1.5;
    cursor: default;
    text-decoration: none;
}

.tbb-block-editor fieldset {
    margin-bottom: 0.75rem;
}

.tbb-block-editor legend {
    margin-bottom: 0.5rem;
}
</style>

<script>
(function () {
    var editor = document.querySelector('.tbb-block-editor');
    if (!editor) {
        return;
    }

    var container = document.getElementById(<?= json_encode($linkContainerId) ?>);
    var linkTypeSelect = container ? container.querySelector('.ft-smart-link-type') : null;
    var typeFields = container ? container.querySelectorAll('.tbb-link-type-field') : [];

    function updateLinkFields() {
        var linkType = linkTypeSelect ? linkTypeSelect.value : 'page';

        typeFields.forEach(function (field) {
            field.style.display = field.getAttribute('data-link-type') === linkType ? '' : 'none';
        });
    }

    if (linkTypeSelect) {
        linkTypeSelect.addEventListener('change', updateLinkFields);
    }

    updateLinkFields();
})();

(function () {
    var editor = document.querySelector('.tbb-block-editor');
    if (!editor) {
        return;
    }

    var styleModeSelect = editor.querySelector('.tbb-style-mode-select');
    var customStyleFields = editor.querySelector('.tbb-custom-style-fields');
    var previewButton = editor.querySelector('.tbb-button-preview-button');
    var previewWrapper = editor.querySelector('.tbb-button-preview-wrapper');
    var previewHint = editor.querySelector('.tbb-button-preview-hint');
    var linkTitleInput = editor.querySelector('.tbb-link-title-input');
    var fontFamilySelect = editor.querySelector('.tbb-font-family-select');
    var fontWeightSelect = editor.querySelector('.tbb-font-weight-select');
    var fontSizeInput = editor.querySelector('.tbb-font-size-input');
    var borderWidthInput = editor.querySelector('.tbb-border-width-input');
    var hoverBorderWidthInput = editor.querySelector('.tbb-hover-border-width-input');
    var spacingInputs = editor.querySelectorAll('.tbb-spacing-top, .tbb-spacing-right, .tbb-spacing-bottom, .tbb-spacing-left');
    var defaultPreviewText = <?= json_encode(t('Learn More')) ?>;
    var themeHintText = <?= json_encode(t('Preview approximates your theme primary button on the live site.')) ?>;
    var customHintText = <?= json_encode(t('Hover the button to preview hover colors.')) ?>;
    var previewHoverActive = false;

    function getColorInputValue(fieldName) {
        var textInput = editor.querySelector('.tbb-color-input[data-color-field="' + fieldName + '"]');
        return textInput && /^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/.test(textInput.value) ? textInput.value : '';
    }

    function getSpacingValue(className, fallback) {
        var input = editor.querySelector('.' + className);
        var value = input ? parseInt(input.value, 10) : fallback;

        return isNaN(value) || value < 0 ? fallback : value;
    }

    function getBorderWidthValue(input, fallback) {
        if (!input) {
            return fallback;
        }

        var value = parseInt(input.value, 10);

        if (isNaN(value) || value < 0) {
            return fallback;
        }

        return Math.min(20, value);
    }

    function updateStyleModeFields() {
        var isCustom = styleModeSelect && styleModeSelect.value === 'custom';

        if (customStyleFields) {
            customStyleFields.style.display = isCustom ? '' : 'none';
        }

        updatePreview();
    }

    function applyCustomStyles(useHover) {
        if (!previewButton) {
            return;
        }

        var backgroundColor = getColorInputValue('background') || '#0d6efd';
        var textColor = getColorInputValue('text') || '#ffffff';
        var hoverBackgroundColor = getColorInputValue('hover-background') || '#0b5ed7';
        var hoverTextColor = getColorInputValue('hover-text') || '#ffffff';
        var borderColor = getColorInputValue('border') || 'transparent';
        var hoverBorderColor = getColorInputValue('hover-border') || 'transparent';
        var fontFamily = fontFamilySelect && fontFamilySelect.value ? fontFamilySelect.value : '';

        previewButton.style.backgroundColor = useHover ? hoverBackgroundColor : backgroundColor;
        previewButton.style.color = useHover ? hoverTextColor : textColor;
        previewButton.style.borderColor = useHover ? hoverBorderColor : borderColor;
        previewButton.style.borderWidth = (useHover
            ? getBorderWidthValue(hoverBorderWidthInput, 0)
            : getBorderWidthValue(borderWidthInput, 0)) + 'px';
        previewButton.style.fontFamily = fontFamily || '';
        previewButton.style.fontWeight = fontWeightSelect ? fontWeightSelect.value : '400';
        previewButton.style.fontSize = (fontSizeInput ? fontSizeInput.value : '16') + 'px';
    }

    function applyThemeStyles() {
        if (!previewButton) {
            return;
        }

        previewButton.style.backgroundColor = '#0d6efd';
        previewButton.style.color = '#ffffff';
        previewButton.style.borderColor = '#0d6efd';
        previewButton.style.borderWidth = '1px';
        previewButton.style.fontFamily = '';
        previewButton.style.fontWeight = '400';
        previewButton.style.fontSize = '16px';
    }

    function updatePreview() {
        if (!previewButton) {
            return;
        }

        var previewText = linkTitleInput && linkTitleInput.value.trim() !== ''
            ? linkTitleInput.value.trim()
            : defaultPreviewText;

        previewButton.textContent = previewText;

        if (previewWrapper) {
            previewWrapper.style.paddingTop = getSpacingValue('tbb-spacing-top', 0) + 'px';
            previewWrapper.style.paddingRight = getSpacingValue('tbb-spacing-right', 0) + 'px';
            previewWrapper.style.paddingBottom = getSpacingValue('tbb-spacing-bottom', 8) + 'px';
            previewWrapper.style.paddingLeft = getSpacingValue('tbb-spacing-left', 0) + 'px';
        }

        if (styleModeSelect && styleModeSelect.value === 'custom') {
            applyCustomStyles(previewHoverActive);
            if (previewHint) {
                previewHint.textContent = customHintText;
            }
        } else {
            previewHoverActive = false;
            applyThemeStyles();
            if (previewHint) {
                previewHint.textContent = themeHintText;
            }
        }
    }

    if (styleModeSelect) {
        styleModeSelect.addEventListener('change', updateStyleModeFields);
    }

    if (previewButton) {
        previewButton.addEventListener('mouseenter', function () {
            if (!styleModeSelect || styleModeSelect.value !== 'custom') {
                return;
            }

            previewHoverActive = true;
            applyCustomStyles(true);
        });

        previewButton.addEventListener('mouseleave', function () {
            if (!styleModeSelect || styleModeSelect.value !== 'custom') {
                return;
            }

            previewHoverActive = false;
            applyCustomStyles(false);
        });
    }

    editor.querySelectorAll('.tbb-color-group').forEach(function (group) {
        var textInput = group.querySelector('.tbb-color-input');
        var pickerInput = group.querySelector('.tbb-color-picker');
        if (!textInput || !pickerInput) {
            return;
        }

        pickerInput.addEventListener('input', function () {
            textInput.value = pickerInput.value;
            updatePreview();
        });

        textInput.addEventListener('input', function () {
            if (/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/.test(textInput.value)) {
                pickerInput.value = textInput.value.length === 4
                    ? '#' + textInput.value[1] + textInput.value[1] + textInput.value[2] + textInput.value[2] + textInput.value[3] + textInput.value[3]
                    : textInput.value;
            }
            updatePreview();
        });
    });

    [linkTitleInput, fontFamilySelect, fontWeightSelect, fontSizeInput, borderWidthInput, hoverBorderWidthInput].forEach(function (element) {
        if (!element) {
            return;
        }

        element.addEventListener('input', updatePreview);
        element.addEventListener('change', updatePreview);
    });

    spacingInputs.forEach(function (element) {
        element.addEventListener('input', updatePreview);
        element.addEventListener('change', updatePreview);
    });

    updateStyleModeFields();
})();
</script>
