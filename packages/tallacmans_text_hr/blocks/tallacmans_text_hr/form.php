<?php

declare(strict_types=1);

defined('C5_EXECUTE') or die('Access Denied.');

$fontFamilyField = $view->field('fontFamily');
$fontFamilyCustomField = $view->field('fontFamilyCustom');
$showCustomFontField = ($fontFamily ?? '') === '__custom__';
?>

<div class="tth-block-editor row g-3">
    <div class="col-lg-7 tth-block-editor-controls">
        <fieldset class="mb-3">
            <legend class="fs-6"><?= t('Text Content') ?></legend>

            <div class="form-group mb-2">
                <?= $form->label('textDisplay', t('Text to Display'), ['class' => 'form-label']) ?>
                <?= $form->text($view->field('textDisplay'), $textDisplay ?? '', ['maxlength' => 255, 'placeholder' => t('Your text here'), 'class' => 'form-control form-control-sm tth-text-display-input']) ?>
                <p class="help-block small text-muted mb-0">
                    <?= t('Short text works best, especially on mobile.') ?>
                </p>
            </div>

            <div class="form-group mb-0">
                <?= $form->label('postitionText', t('Text Position'), ['class' => 'form-label']) ?>
                <?= $form->select($view->field('postitionText'), $postitionText_options, $postitionText ?? '1', ['class' => 'form-select form-select-sm tth-position-select']) ?>
            </div>
        </fieldset>

        <fieldset class="mb-3">
            <legend class="fs-6"><?= t('Line Style') ?></legend>

            <div class="row g-2">
                <div class="col-md-6">
                    <?= $form->label('hrStyle', t('Line Style'), ['class' => 'form-label small']) ?>
                    <?= $form->select($view->field('hrStyle'), $hrStyle_options, $hrStyle ?? '1', ['class' => 'form-select form-select-sm tth-hr-style-select']) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->label('heightLine', t('Line Thickness'), ['class' => 'form-label small']) ?>
                    <div class="input-group input-group-sm">
                        <?= $form->number($view->field('heightLine'), $heightLine ?? 1, ['min' => 0, 'max' => 20, 'step' => 1, 'class' => 'tth-height-line-input']) ?>
                        <span class="input-group-text">px</span>
                    </div>
                    <p class="help-block small text-muted mb-0 tth-double-line-note" style="display:none;">
                        <?= t('Double lines need 3px thickness to appear. Thickness is set to 3px automatically when you choose this style.') ?>
                    </p>
                </div>
            </div>

            <div class="form-group mt-2 mb-2">
                <?= $form->label('colorLine', t('Line Color'), ['class' => 'form-label small']) ?>
                <div class="input-group input-group-sm">
                    <?= $form->text($view->field('colorLine'), $colorLine ?? '#333333', ['maxlength' => 7, 'placeholder' => '#333333', 'class' => 'form-control tth-color-line-input']) ?>
                    <input type="color" class="form-control form-control-color tth-color-line-picker" value="<?= h($colorLine ?: '#333333') ?>" aria-label="<?= t('Line Color') ?>">
                </div>
            </div>

            <div class="row g-2">
                <div class="col-md-6">
                    <?= $form->label('blockMarginTop', t('Margin Top'), ['class' => 'form-label small']) ?>
                    <div class="input-group input-group-sm">
                        <?= $form->number($view->field('blockMarginTop'), $blockMarginTop ?? 33, ['min' => 0, 'step' => 1, 'class' => 'tth-margin-top-input']) ?>
                        <span class="input-group-text">px</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <?= $form->label('blockMarginBottom', t('Margin Bottom'), ['class' => 'form-label small']) ?>
                    <div class="input-group input-group-sm">
                        <?= $form->number($view->field('blockMarginBottom'), $blockMarginBottom ?? 33, ['min' => 0, 'step' => 1, 'class' => 'tth-margin-bottom-input']) ?>
                        <span class="input-group-text">px</span>
                    </div>
                </div>
            </div>
        </fieldset>

        <fieldset class="mb-3">
            <legend class="fs-6"><?= t('Text Box') ?></legend>

            <div class="form-group mb-2">
                <?= $form->label('colorBackg', t('Background Color'), ['class' => 'form-label small']) ?>
                <div class="input-group input-group-sm">
                    <?= $form->text($view->field('colorBackg'), $colorBackg ?? '#ffffff', ['maxlength' => 7, 'placeholder' => '#ffffff', 'class' => 'form-control tth-color-backg-input']) ?>
                    <input type="color" class="form-control form-control-color tth-color-backg-picker" value="<?= h($colorBackg ?: '#ffffff') ?>" aria-label="<?= t('Background Color') ?>">
                </div>
                <p class="help-block small text-muted mb-0">
                    <?= t('Match your page background so the line breaks behind the text.') ?>
                </p>
            </div>

            <div class="row g-2">
                <div class="col-md-6">
                    <?= $form->label('textBorder', t('Text Border'), ['class' => 'form-label small']) ?>
                    <div class="input-group input-group-sm">
                        <?= $form->number($view->field('textBorder'), $textBorder ?? 0, ['min' => 0, 'max' => 20, 'step' => 1, 'class' => 'tth-text-border-input']) ?>
                        <span class="input-group-text">px</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <?= $form->label('textBorderShape', t('Border Shape'), ['class' => 'form-label small']) ?>
                    <?= $form->select($view->field('textBorderShape'), $textBorderShape_options, $textBorderShape ?? '1', ['class' => 'form-select form-select-sm tth-text-border-shape-select']) ?>
                </div>
            </div>
        </fieldset>

        <fieldset class="mb-3">
            <legend class="fs-6"><?= t('Drop Shadow') ?></legend>

            <div class="row g-2">
                <div class="col-md-6">
                    <?= $form->label('dropShadowBlur', t('Shadow Blur'), ['class' => 'form-label small']) ?>
                    <div class="input-group input-group-sm">
                        <?= $form->number($view->field('dropShadowBlur'), $dropShadowBlur ?? 0, ['min' => 0, 'max' => 50, 'step' => 1, 'class' => 'tth-drop-shadow-blur-input']) ?>
                        <span class="input-group-text">px</span>
                    </div>
                    <p class="help-block small text-muted mb-0">
                        <?= t('Set to 0 to disable the shadow.') ?>
                    </p>
                </div>
                <div class="col-md-6">
                    <?= $form->label('dropShadowOpacity', t('Shadow Opacity'), ['class' => 'form-label small']) ?>
                    <div class="input-group input-group-sm">
                        <?= $form->number($view->field('dropShadowOpacity'), $dropShadowOpacity ?? 35, ['min' => 0, 'max' => 100, 'step' => 1, 'class' => 'tth-drop-shadow-opacity-input']) ?>
                        <span class="input-group-text">%</span>
                    </div>
                </div>
            </div>

            <div class="form-group mt-2 mb-0">
                <?= $form->label('dropShadowColor', t('Shadow Color'), ['class' => 'form-label small']) ?>
                <div class="input-group input-group-sm">
                    <?= $form->text($view->field('dropShadowColor'), $dropShadowColor ?? '#333333', ['maxlength' => 7, 'placeholder' => '#333333', 'class' => 'form-control tth-drop-shadow-color-input']) ?>
                    <input type="color" class="form-control form-control-color tth-drop-shadow-color-picker" value="<?= h($dropShadowColor ?: '#333333') ?>" aria-label="<?= t('Shadow Color') ?>">
                </div>
                <p class="help-block small text-muted mb-0 mt-2">
                    <?= t('The shadow clears when visitors hover over the text.') ?>
                </p>
            </div>
        </fieldset>

        <fieldset class="mb-0">
            <legend class="fs-6"><?= t('Typography') ?></legend>

            <div class="form-group mb-2">
                <?= $form->label('fontFamily', t('Font Family'), ['class' => 'form-label small']) ?>
                <?= $form->select($fontFamilyField, $fontFamily_options, $fontFamily ?? '', ['class' => 'form-select form-select-sm tth-font-family-select']) ?>
            </div>

            <div class="form-group tth-custom-font-group mb-2"<?= $showCustomFontField ? '' : ' style="display:none;"' ?>>
                <?= $form->label('fontFamilyCustom', t('Custom Font Family'), ['class' => 'form-label small']) ?>
                <?= $form->text($fontFamilyCustomField, $fontFamilyCustom ?? '', [
                    'maxlength' => 255,
                    'placeholder' => t('For example, "Playfair Display", Georgia, serif'),
                    'class' => 'form-control form-control-sm tth-font-family-custom',
                ]); ?>
            </div>

            <div class="row g-2">
                <div class="col-md-6">
                    <?= $form->label('fontWeight', t('Font Weight'), ['class' => 'form-label small']) ?>
                    <?= $form->select($view->field('fontWeight'), $fontWeight_options, $fontWeight ?? '400', ['class' => 'form-select form-select-sm tth-font-weight-select']) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->label('fontSize', t('Font Size'), ['class' => 'form-label small']) ?>
                    <div class="input-group input-group-sm">
                        <?= $form->number($view->field('fontSize'), $fontSize ?? 16, ['min' => 1, 'step' => 1, 'class' => 'tth-font-size-input']) ?>
                        <span class="input-group-text">px</span>
                    </div>
                </div>
            </div>

            <div class="form-group mt-2 mb-0">
                <?= $form->label('letterSpacing', t('Letter Spacing'), ['class' => 'form-label small']) ?>
                <div class="input-group input-group-sm">
                    <?= $form->number($view->field('letterSpacing'), $letterSpacing ?? '0', ['step' => 0.1, 'class' => 'tth-letter-spacing-input']) ?>
                    <span class="input-group-text">px</span>
                </div>
            </div>

            <div class="form-group mt-2 mb-0">
                <?= $form->label('textColor', t('Text Color'), ['class' => 'form-label small']) ?>
                <div class="input-group input-group-sm">
                    <?= $form->text($view->field('textColor'), $textColor ?? '#333333', ['maxlength' => 7, 'placeholder' => '#333333', 'class' => 'form-control tth-color-text-input']) ?>
                    <input type="color" class="form-control form-control-color tth-color-text-picker" value="<?= h($textColor ?: '#333333') ?>" aria-label="<?= t('Text Color') ?>">
                </div>
            </div>
        </fieldset>
    </div>

    <div class="col-lg-5 tth-block-editor-preview">
        <div class="tth-preview-panel border rounded bg-light h-100">
            <div class="tth-preview-panel-header px-3 py-2 border-bottom bg-white rounded-top">
                <strong class="small text-uppercase text-muted"><?= t('Preview') ?></strong>
            </div>
            <div class="tth-preview-panel-body p-3">
                <div class="tth-text-hr-preview">
                    <div class="tth-text-hr-preview-line">
                        <span class="tth-text-hr-preview-segment tth-text-hr-preview-segment-before" aria-hidden="true"></span>
                        <span class="tth-text-hr-preview-label">
                            <span class="tth-text-hr-preview-text"><?= h($textDisplay ?: t('Example Text')) ?></span>
                        </span>
                        <span class="tth-text-hr-preview-segment tth-text-hr-preview-segment-after" aria-hidden="true"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.tth-block-editor-controls {
    padding-right: 0.25rem;
}

.tth-block-editor-preview {
    position: sticky;
    top: 0;
    align-self: flex-start;
}

.tth-preview-panel {
    min-height: 220px;
}

.tth-preview-panel-body {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 180px;
}

.tth-text-hr-preview {
    width: 100%;
}

.tth-text-hr-preview-line {
    display: flex;
    width: 100%;
    align-items: center;
}

.tth-text-hr-preview-segment {
    flex: 1 1 auto;
    height: 0;
    align-self: center;
}

.tth-text-hr-preview-label {
    flex: 0 0 auto;
    position: relative;
    z-index: 2;
    transition: box-shadow 0.25s ease;
}

.tth-text-hr-preview-label:hover {
    box-shadow: none !important;
}

.tth-block-editor fieldset {
    margin-bottom: 0.75rem;
}

.tth-block-editor legend {
    margin-bottom: 0.5rem;
}
</style>

<script>
(function () {
    var editor = document.querySelector('.tth-block-editor');
    if (!editor) {
        return;
    }

    var customValue = <?= json_encode('__custom__') ?>;
    var positionMap = { '1': 'center', '2': 'flex-start', '3': 'flex-end' };
    var shapeMap = { '1': '0', '2': '3px', '3': '100px' };
    var hrStyleMap = { '1': 'solid', '2': 'dashed', '3': 'dotted', '4': 'double' };
    var defaultPreviewText = <?= json_encode(t('Example Text')) ?>;

    var fontFamilySelect = editor.querySelector('.tth-font-family-select');
    var customFontGroup = editor.querySelector('.tth-custom-font-group');
    var customFontInput = editor.querySelector('.tth-font-family-custom');
    var previewLine = editor.querySelector('.tth-text-hr-preview-line');
    var previewSegmentBefore = editor.querySelector('.tth-text-hr-preview-segment-before');
    var previewSegmentAfter = editor.querySelector('.tth-text-hr-preview-segment-after');
    var previewLabel = editor.querySelector('.tth-text-hr-preview-label');
    var previewText = editor.querySelector('.tth-text-hr-preview-text');
    var textDisplayInput = editor.querySelector('.tth-text-display-input');
    var positionSelect = editor.querySelector('.tth-position-select');
    var hrStyleSelect = editor.querySelector('.tth-hr-style-select');
    var heightLineInput = editor.querySelector('.tth-height-line-input');
    var colorLineInput = editor.querySelector('.tth-color-line-input');
    var colorBackgInput = editor.querySelector('.tth-color-backg-input');
    var textBorderInput = editor.querySelector('.tth-text-border-input');
    var textBorderShapeSelect = editor.querySelector('.tth-text-border-shape-select');
    var fontWeightSelect = editor.querySelector('.tth-font-weight-select');
    var fontSizeInput = editor.querySelector('.tth-font-size-input');
    var letterSpacingInput = editor.querySelector('.tth-letter-spacing-input');
    var colorTextInput = editor.querySelector('.tth-color-text-input');
    var marginTopInput = editor.querySelector('.tth-margin-top-input');
    var marginBottomInput = editor.querySelector('.tth-margin-bottom-input');
    var dropShadowBlurInput = editor.querySelector('.tth-drop-shadow-blur-input');
    var dropShadowOpacityInput = editor.querySelector('.tth-drop-shadow-opacity-input');
    var dropShadowColorInput = editor.querySelector('.tth-drop-shadow-color-input');
    var doubleLineNote = editor.querySelector('.tth-double-line-note');

    function hexToRgb(hex) {
        hex = (hex || '#333333').replace('#', '');

        if (hex.length === 3) {
            hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
        }

        return {
            r: parseInt(hex.substring(0, 2), 16),
            g: parseInt(hex.substring(2, 4), 16),
            b: parseInt(hex.substring(4, 6), 16),
        };
    }

    function getDropShadowStyle() {
        var blur = getSpacingValue(dropShadowBlurInput, 0);

        if (blur <= 0) {
            return 'none';
        }

        var color = dropShadowColorInput && dropShadowColorInput.value ? dropShadowColorInput.value : '#333333';
        var opacity = getSpacingValue(dropShadowOpacityInput, 35) / 100;
        var rgb = hexToRgb(color);

        return '0 0 ' + blur + 'px rgba(' + rgb.r + ',' + rgb.g + ',' + rgb.b + ',' + opacity + ')';
    }

    function getSpacingValue(input, fallback) {
        if (!input) {
            return fallback;
        }

        var value = parseInt(input.value, 10);

        return isNaN(value) || value < 0 ? fallback : value;
    }

    function toggleCustomFontField() {
        if (!fontFamilySelect || !customFontGroup) {
            return;
        }

        customFontGroup.style.display = fontFamilySelect.value === customValue ? '' : 'none';
        updatePreview();
    }

    function getSelectedFontFamily() {
        if (!fontFamilySelect) {
            return '';
        }

        if (fontFamilySelect.value === customValue) {
            return customFontInput ? customFontInput.value.trim() : '';
        }

        if (fontFamilySelect.value === '') {
            return '';
        }

        return fontFamilySelect.value;
    }

    function applyDoubleLineStyleRules(autoSetThickness) {
        if (!hrStyleSelect) {
            return;
        }

        var isDouble = hrStyleSelect.value === '4';
        var doubleLineMinThickness = 3;

        if (doubleLineNote) {
            doubleLineNote.style.display = isDouble ? '' : 'none';
        }

        if (!isDouble || !heightLineInput) {
            return;
        }

        if (autoSetThickness || parseInt(heightLineInput.value, 10) < doubleLineMinThickness) {
            heightLineInput.value = String(doubleLineMinThickness);
        }
    }

    function applyLineSegments(lineBorder, position) {
        var segmentStyle = {
            flex: '1 1 auto',
            height: '0',
            alignSelf: 'center',
            borderBottom: lineBorder,
            display: '',
        };

        if (previewSegmentBefore) {
            Object.assign(previewSegmentBefore.style, segmentStyle);
            previewSegmentBefore.style.display = position === '2' ? 'none' : '';
        }

        if (previewSegmentAfter) {
            Object.assign(previewSegmentAfter.style, segmentStyle);
            previewSegmentAfter.style.display = position === '3' ? 'none' : '';
        }
    }

    function updatePreview() {
        if (!previewLine || !previewLabel || !previewText) {
            return;
        }

        var lineColor = colorLineInput && colorLineInput.value ? colorLineInput.value : '#333333';
        var backgColor = colorBackgInput && colorBackgInput.value ? colorBackgInput.value : '#ffffff';
        var textColor = colorTextInput && colorTextInput.value ? colorTextInput.value : '#333333';
        var position = positionSelect ? positionSelect.value || '1' : '1';
        var hrStyle = hrStyleSelect ? hrStyleMap[hrStyleSelect.value] || 'solid' : 'solid';
        var heightLine = getSpacingValue(heightLineInput, 1);
        if (hrStyleSelect && hrStyleSelect.value === '4') {
            heightLine = Math.max(3, heightLine);
        }
        var textBorder = getSpacingValue(textBorderInput, 0);
        var borderShape = textBorderShapeSelect ? shapeMap[textBorderShapeSelect.value] || '0' : '0';
        var fontFamily = getSelectedFontFamily();
        var marginTop = getSpacingValue(marginTopInput, 24);
        var marginBottom = getSpacingValue(marginBottomInput, 24);
        var lineBorder = heightLine + 'px ' + hrStyle + ' ' + lineColor;

        previewLine.style.display = 'flex';
        previewLine.style.width = '100%';
        previewLine.style.alignItems = 'center';
        previewLine.style.margin = marginTop + 'px 0 ' + marginBottom + 'px';
        previewLine.style.height = '';
        previewLine.style.borderBottom = '';
        previewLine.style.justifyContent = position === '3' ? 'flex-end' : '';

        applyLineSegments(lineBorder, position);

        previewLabel.style.backgroundColor = backgColor;
        previewLabel.style.border = textBorder + 'px solid ' + lineColor;
        previewLabel.style.borderRadius = borderShape;
        previewLabel.style.padding = '5px 15px';
        previewLabel.style.display = 'inline-block';
        previewLabel.style.flex = '0 0 auto';
        previewLabel.style.position = 'relative';
        previewLabel.style.zIndex = '2';
        previewLabel.style.boxShadow = getDropShadowStyle();

        previewText.textContent = textDisplayInput && textDisplayInput.value.trim() !== ''
            ? textDisplayInput.value.trim()
            : defaultPreviewText;
        previewText.style.display = 'block';
        previewText.style.margin = '0';
        previewText.style.padding = '0';
        previewText.style.color = textColor;
        previewText.style.fontFamily = fontFamily || 'inherit';
        previewText.style.fontWeight = fontWeightSelect ? fontWeightSelect.value : '400';
        previewText.style.fontSize = (fontSizeInput ? fontSizeInput.value : '16') + 'px';
        previewText.style.letterSpacing = (letterSpacingInput ? letterSpacingInput.value : '0') + 'px';

        applyDoubleLineStyleRules(false);
    }

    if (hrStyleSelect) {
        hrStyleSelect.addEventListener('change', function () {
            applyDoubleLineStyleRules(hrStyleSelect.value === '4');
        });
    }

    if (fontFamilySelect) {
        fontFamilySelect.addEventListener('change', toggleCustomFontField);
    }

    editor.querySelectorAll('.input-group').forEach(function (group) {
        var textInput = group.querySelector('.tth-color-line-input, .tth-color-backg-input, .tth-color-text-input, .tth-drop-shadow-color-input');
        var pickerInput = group.querySelector('.tth-color-line-picker, .tth-color-backg-picker, .tth-color-text-picker, .tth-drop-shadow-color-picker');
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

    [
        textDisplayInput,
        customFontInput,
        positionSelect,
        hrStyleSelect,
        heightLineInput,
        colorLineInput,
        colorBackgInput,
        textBorderInput,
        textBorderShapeSelect,
        fontWeightSelect,
        fontSizeInput,
        letterSpacingInput,
        colorTextInput,
        marginTopInput,
        marginBottomInput,
        dropShadowBlurInput,
        dropShadowOpacityInput,
        dropShadowColorInput,
    ].forEach(function (element) {
        if (!element) {
            return;
        }

        element.addEventListener('input', updatePreview);
        element.addEventListener('change', updatePreview);
    });

    toggleCustomFontField();
    applyDoubleLineStyleRules(false);
    updatePreview();
})();
</script>
