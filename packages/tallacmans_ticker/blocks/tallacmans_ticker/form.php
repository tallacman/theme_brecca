<?php

declare(strict_types=1);

defined('C5_EXECUTE') or die('Access Denied.');

$app = (isset($app) && $app) ? $app : \Concrete\Core\Support\Facade\Application::getFacadeApplication();
$color = $app->make('helper/form/color');

$previewLines = preg_split('/\R/', (string) ($items ?? ''), -1, PREG_SPLIT_NO_EMPTY);
$previewLines = array_values(array_filter(array_map('trim', $previewLines)));
if ($previewLines === []) {
    $previewLines = [t('Example announcement'), t('Another ticker item')];
}

$previewItemHtml = static function (array $lines): string {
    $html = '';
    foreach ($lines as $line) {
        $html .= '<div class="ttk-item">' . h($line) . '</div>';
    }

    return $html;
};

$previewItemsMarkup = $previewItemHtml($previewLines);
$previewSpeed = is_numeric($speed ?? null) ? (float) $speed : 30.0;
$previewDirectionRtl = ($scrollDirection ?? 'ltr') === 'rtl';
$previewPauseHover = ($pauseOnHover ?? '1') === '1';
?>

<div class="ttk-block-editor">
    <div class="ttk-preview-panel mb-3 border rounded bg-light">
        <div class="ttk-preview-panel-header px-3 py-2 border-bottom bg-white rounded-top">
            <strong class="small text-uppercase text-muted"><?= t('Preview') ?></strong>
        </div>
        <div class="ttk-preview-panel-body p-2">
            <div
                class="tallacmans-ticker ttk-editor-preview<?= $previewDirectionRtl ? ' ttk-direction-rtl' : '' ?>"
                id="ttk-live-preview"
            >
                <div
                    class="ttk-wrap<?= $previewPauseHover ? ' ttk-pause-hover' : '' ?>"
                    style="background-color: <?= h($colorBack ?? 'rgb(0,0,0)') ?>"
                >
                    <div class="ttk-track" style="animation-duration: <?= h((string) $previewSpeed) ?>s;">
                        <div class="ttk-group">
                            <?= $previewItemsMarkup ?>
                        </div>
                        <div class="ttk-group ttk-group-duplicate" aria-hidden="true">
                            <?= $previewItemsMarkup ?>
                        </div>
                    </div>
                </div>
            </div>
            <p class="help-block small text-muted mb-0 mt-2">
                <?= t('Preview updates as you edit. Hover to test pause-on-hover.') ?>
            </p>
        </div>
    </div>

    <fieldset class="mb-3">
        <legend class="fs-6"><?= t('Ticker Items') ?></legend>

        <div class="form-group mb-2">
            <?= $form->label('items', t('Items (one per line)'), ['class' => 'form-label']) ?>
            <?= $form->textarea($view->field('items'), $items ?? '', [
                'rows' => 8,
                'class' => 'form-control form-control-sm ttk-items-input',
                'placeholder' => t("Breaking news\nFree shipping today\nNew hours: 9–5"),
            ]) ?>
            <p class="help-block small text-muted mb-0">
                <?= t('Enter one item per line. Each line scrolls as a separate segment with spacing between items.') ?>
            </p>
        </div>

        <div class="form-group mb-0">
            <?= $form->label($view->field('itemSpacing'), t('Space Between Items'), ['class' => 'form-label']) ?>
            <div class="input-group input-group-sm">
                <?= $form->number($view->field('itemSpacing'), $itemSpacing ?? 64, [
                    'min' => 0,
                    'max' => 240,
                    'step' => 1,
                    'class' => 'form-control ttk-item-spacing-input',
                ]) ?>
                <span class="input-group-text"><?= t('px') ?></span>
            </div>
            <p class="help-block small text-muted mb-0">
                <?= t('Total gap between one phrase and the next (0–240 px).') ?>
            </p>
        </div>
    </fieldset>

    <fieldset class="mb-3">
        <legend class="fs-6"><?= t('Typography & Speed') ?></legend>

        <div class="row g-2">
            <div class="col-sm-4">
                <div class="form-group mb-2">
                    <?= $form->label($view->field('sizeUnit'), t('Font Units'), ['class' => 'form-label']) ?>
                    <?= $form->select($view->field('sizeUnit'), $sizeUnit_options, $sizeUnit ?? 'px', ['class' => 'form-select form-select-sm ttk-size-unit-select']) ?>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group mb-2">
                    <?= $form->label($view->field('fontSize'), t('Font Size'), ['class' => 'form-label']) ?>
                    <?= $form->number($view->field('fontSize'), $fontSize ?? 16, [
                        'min' => 1,
                        'max' => 999,
                        'step' => 'any',
                        'class' => 'form-control form-control-sm ttk-font-size-input',
                        'required' => 'required',
                    ]) ?>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group mb-2">
                    <?= $form->label($view->field('speed'), t('Scroll Speed (seconds)'), ['class' => 'form-label']) ?>
                    <?= $form->number($view->field('speed'), $speed ?? 30, [
                        'min' => 0.1,
                        'max' => 3600,
                        'step' => 'any',
                        'class' => 'form-control form-control-sm ttk-speed-input',
                        'required' => 'required',
                    ]) ?>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group mb-0">
                    <?= $form->label($view->field('fontStyle'), t('Font Style'), ['class' => 'form-label']) ?>
                    <?= $form->select($view->field('fontStyle'), $fontStyle_options, $fontStyle ?? 'inherit', ['class' => 'form-select form-select-sm ttk-font-style-select']) ?>
                </div>
            </div>
        </div>

        <p class="help-block small text-muted mb-0 mt-2">
            <?= t('Font face is inherited from your theme. Speed is the time for one complete scroll cycle in seconds.') ?>
        </p>
    </fieldset>

    <fieldset class="mb-3">
        <legend class="fs-6"><?= t('Text Drop Shadow') ?></legend>

        <div class="row g-2">
            <div class="col-sm-6">
                <div class="form-group mb-2">
                    <?= $form->label('textShadowColor', t('Shadow Color'), ['class' => 'form-label']) ?>
                    <?php $color->output('textShadowColor', $textShadowColor ?? 'rgba(0,0,0,0.55)', [
                        'showAlpha' => 'true',
                        'showPalette' => 'true',
                        'preferredFormat' => 'rgb',
                    ]); ?>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group mb-2">
                    <?= $form->label($view->field('textShadowBlur'), t('Shadow Blur (px)'), ['class' => 'form-label']) ?>
                    <?= $form->number($view->field('textShadowBlur'), $textShadowBlur ?? 10, [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                        'class' => 'form-control form-control-sm ttk-shadow-blur-input',
                    ]) ?>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group mb-2">
                    <?= $form->label($view->field('textShadowOffsetX'), t('Horizontal Offset (px)'), ['class' => 'form-label']) ?>
                    <?= $form->number($view->field('textShadowOffsetX'), $textShadowOffsetX ?? 0, [
                        'min' => -50,
                        'max' => 50,
                        'step' => 1,
                        'class' => 'form-control form-control-sm ttk-shadow-offset-x-input',
                    ]) ?>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group mb-0">
                    <?= $form->label($view->field('textShadowOffsetY'), t('Vertical Offset (px)'), ['class' => 'form-label']) ?>
                    <?= $form->number($view->field('textShadowOffsetY'), $textShadowOffsetY ?? 2, [
                        'min' => -50,
                        'max' => 50,
                        'step' => 1,
                        'class' => 'form-control form-control-sm ttk-shadow-offset-y-input',
                    ]) ?>
                </div>
            </div>
        </div>

        <p class="help-block small text-muted mb-0 mt-2">
            <?= t('Set blur to 0 to turn the shadow off. Defaults: 10px blur, 2px down, semi-transparent black.') ?>
        </p>
    </fieldset>

    <fieldset class="mb-3">
        <legend class="fs-6"><?= t('Scroll Behavior') ?></legend>

        <div class="row g-2">
            <div class="col-sm-6">
                <div class="form-group mb-2">
                    <?= $form->label($view->field('scrollDirection'), t('Scroll Direction'), ['class' => 'form-label']) ?>
                    <?= $form->select($view->field('scrollDirection'), $scrollDirection_options, $scrollDirection ?? 'ltr', ['class' => 'form-select form-select-sm ttk-direction-select']) ?>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group mb-0">
                    <?= $form->label($view->field('pauseOnHover'), t('Pause on Hover'), ['class' => 'form-label']) ?>
                    <?= $form->select($view->field('pauseOnHover'), $yesNo_options, $pauseOnHover ?? '1', ['class' => 'form-select form-select-sm ttk-pause-hover-select']) ?>
                </div>
            </div>
        </div>

        <p class="help-block small text-muted mb-0 mt-2">
            <?= t('Pause on hover lets visitors read an item before it scrolls away.') ?>
        </p>
    </fieldset>

    <fieldset class="mb-0">
        <legend class="fs-6"><?= t('Colors') ?></legend>

        <div class="row g-2">
            <div class="col-sm-6">
                <div class="form-group mb-0">
                    <?= $form->label('colorBack', t('Background Color'), ['class' => 'form-label']) ?>
                    <?php $color->output('colorBack', $colorBack ?? 'rgb(0,0,0)', [
                        'showAlpha' => 'false',
                        'showPalette' => 'true',
                        'preferredFormat' => 'rgb',
                    ]); ?>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group mb-0">
                    <?= $form->label('colorText', t('Text Color'), ['class' => 'form-label']) ?>
                    <?php $color->output('colorText', $colorText ?? 'rgb(255,255,255)', [
                        'showAlpha' => 'false',
                        'showPalette' => 'true',
                        'preferredFormat' => 'rgb',
                    ]); ?>
                </div>
            </div>
        </div>
    </fieldset>
</div>

<style>
.ttk-preview-panel {
    position: sticky;
    top: 0;
    z-index: 5;
    background-color: var(--bs-light, #f8f9fa);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.ttk-preview-panel-header {
    background-color: var(--bs-body-bg, #fff);
}

.ttk-preview-panel-body {
    overflow: hidden;
}

.ttk-editor-preview.tallacmans-ticker {
    display: block;
    width: 100%;
}

.ttk-editor-preview .ttk-wrap {
    width: 100%;
    overflow: hidden;
}

.ttk-editor-preview .ttk-wrap * {
    box-sizing: border-box;
}

.ttk-editor-preview .ttk-track {
    display: flex;
    width: max-content;
    flex-wrap: nowrap;
    will-change: transform;
    animation-iteration-count: infinite;
    animation-timing-function: linear;
    animation-name: ttk-preview-scroll;
    -webkit-font-smoothing: antialiased;
    line-height: 1.7;
}

.ttk-editor-preview .ttk-group {
    display: flex;
    flex-shrink: 0;
    align-items: center;
    white-space: nowrap;
}

.ttk-editor-preview .ttk-item {
    display: inline-block;
    flex-shrink: 0;
    white-space: nowrap;
}

.ttk-editor-preview .ttk-pause-hover:hover .ttk-track {
    animation-play-state: paused;
}

.ttk-editor-preview.ttk-direction-rtl .ttk-track {
    animation-name: ttk-preview-scroll-rtl;
}

@keyframes ttk-preview-scroll {
    0% {
        transform: translate3d(0, 0, 0);
    }

    100% {
        transform: translate3d(-50%, 0, 0);
    }
}

@keyframes ttk-preview-scroll-rtl {
    0% {
        transform: translate3d(-50%, 0, 0);
    }

    100% {
        transform: translate3d(0, 0, 0);
    }
}

.ttk-block-editor legend {
    margin-bottom: 0.5rem;
}
</style>

<script>
(function () {
    var editor = document.querySelector('.ttk-block-editor');
    if (!editor) {
        return;
    }

    var defaultPreviewItems = <?= json_encode([t('Example announcement'), t('Another ticker item')]) ?>;
    var colorFieldNames = ['colorBack', 'colorText', 'textShadowColor'];
    var previewRoot = editor.querySelector('#ttk-live-preview');
    var previewWrap = editor.querySelector('#ttk-live-preview .ttk-wrap');
    var previewTrack = editor.querySelector('#ttk-live-preview .ttk-track');
    var itemsInput = editor.querySelector('.ttk-items-input');
    var sizeUnitSelect = editor.querySelector('.ttk-size-unit-select');
    var fontSizeInput = editor.querySelector('.ttk-font-size-input');
    var speedInput = editor.querySelector('.ttk-speed-input');
    var fontStyleSelect = editor.querySelector('.ttk-font-style-select');
    var shadowBlurInput = editor.querySelector('.ttk-shadow-blur-input');
    var shadowOffsetXInput = editor.querySelector('.ttk-shadow-offset-x-input');
    var shadowOffsetYInput = editor.querySelector('.ttk-shadow-offset-y-input');
    var directionSelect = editor.querySelector('.ttk-direction-select');
    var pauseHoverSelect = editor.querySelector('.ttk-pause-hover-select');
    var itemSpacingInput = editor.querySelector('.ttk-item-spacing-input');

    function getNumberValue(input, fallback) {
        if (!input) {
            return fallback;
        }

        var value = parseFloat(input.value);

        return isNaN(value) ? fallback : value;
    }

    function getColorFieldValue(fieldName, fallback) {
        var input = editor.querySelector('input[name="' + fieldName + '"]');
        if (!input) {
            return fallback;
        }

        if (window.jQuery && typeof jQuery(input).spectrum === 'function') {
            try {
                var spectrumColor = jQuery(input).spectrum('get');
                if (spectrumColor) {
                    if (typeof spectrumColor.toRgbString === 'function') {
                        return spectrumColor.toRgbString();
                    }

                    if (typeof spectrumColor.toString === 'function') {
                        return spectrumColor.toString();
                    }
                }
            } catch (error) {
                // Fall back to the input value below.
            }
        }

        return input.value || fallback;
    }

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text;

        return div.innerHTML;
    }

    function getItemLines() {
        var raw = itemsInput ? itemsInput.value : '';
        var lines = raw.replace(/\r\n/g, '\n').replace(/\r/g, '\n').split('\n');
        var parsed = [];

        lines.forEach(function (line) {
            line = line.trim();
            if (line !== '') {
                parsed.push(line);
            }
        });

        return parsed.length ? parsed : defaultPreviewItems.slice();
    }

    function buildItemsMarkup(lines) {
        return lines.map(function (line) {
            return '<div class="ttk-item">' + escapeHtml(line) + '</div>';
        }).join('');
    }

    function getItemPaddingPx() {
        var spacing = getNumberValue(itemSpacingInput, 64);
        if (spacing < 0) {
            spacing = 0;
        }
        if (spacing > 240) {
            spacing = 240;
        }

        return spacing / 2;
    }

    function getTextShadowStyle() {
        var blur = getNumberValue(shadowBlurInput, 10);
        if (blur <= 0) {
            return '';
        }

        var offsetX = getNumberValue(shadowOffsetXInput, 0);
        var offsetY = getNumberValue(shadowOffsetYInput, 2);
        var color = getColorFieldValue('textShadowColor', 'rgba(0,0,0,0.55)');

        return offsetX + 'px ' + offsetY + 'px ' + blur + 'px ' + color;
    }

    function applyItemStyles() {
        if (!previewTrack) {
            return;
        }

        var textColor = getColorFieldValue('colorText', 'rgb(255,255,255)');
        var fontSize = getNumberValue(fontSizeInput, 16);
        var sizeUnit = sizeUnitSelect ? sizeUnitSelect.value || 'px' : 'px';
        var fontWeight = fontStyleSelect && fontStyleSelect.value === 'bold' ? 'bold' : 'inherit';
        var textShadow = getTextShadowStyle();
        var itemPadding = getItemPaddingPx();
        var style = 'color:' + textColor + ';font-size:' + fontSize + sizeUnit + ';font-weight:' + fontWeight + ';padding-left:' + itemPadding + 'px;padding-right:' + itemPadding + 'px;';

        if (textShadow !== '') {
            style += 'text-shadow:' + textShadow + ';';
        }

        previewTrack.querySelectorAll('.ttk-item').forEach(function (item) {
            item.setAttribute('style', style);
        });
    }

    function rebuildPreviewTrack() {
        if (!previewTrack) {
            return;
        }

        var itemMarkup = buildItemsMarkup(getItemLines());
        previewTrack.innerHTML =
            '<div class="ttk-group">' + itemMarkup + '</div>' +
            '<div class="ttk-group ttk-group-duplicate" aria-hidden="true">' + itemMarkup + '</div>';

        applyItemStyles();
    }

    function updatePreview() {
        if (!previewRoot || !previewWrap || !previewTrack) {
            return;
        }

        previewWrap.style.backgroundColor = getColorFieldValue('colorBack', 'rgb(0,0,0)');

        var speed = getNumberValue(speedInput, 30);
        if (speed <= 0) {
            speed = 30;
        }
        previewTrack.style.animationDuration = speed + 's';

        previewRoot.classList.toggle('ttk-direction-rtl', directionSelect && directionSelect.value === 'rtl');
        previewWrap.classList.toggle('ttk-pause-hover', !pauseHoverSelect || pauseHoverSelect.value === '1');

        rebuildPreviewTrack();
    }

    function bindColorFields() {
        colorFieldNames.forEach(function (fieldName) {
            var input = editor.querySelector('input[name="' + fieldName + '"]');
            if (!input || input.dataset.ttkPreviewBound === '1') {
                return;
            }

            input.dataset.ttkPreviewBound = '1';
            input.addEventListener('input', updatePreview);
            input.addEventListener('change', updatePreview);

            if (window.jQuery) {
                jQuery(input)
                    .off('.ttkPreview')
                    .on('change.ttkPreview move.spectrum.ttkPreview drag.spectrum.ttkPreview hide.spectrum.ttkPreview', updatePreview);
            }
        });
    }

    editor.addEventListener('input', updatePreview);
    editor.addEventListener('change', updatePreview);

    bindColorFields();
    window.setTimeout(bindColorFields, 250);
    window.setTimeout(bindColorFields, 1000);

    updatePreview();
})();
</script>
