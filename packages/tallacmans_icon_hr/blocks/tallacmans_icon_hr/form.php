<?php

declare(strict_types=1);

defined('C5_EXECUTE') or die('Access Denied.');

$bID = $bID ?? 0;
$iconSelectId = 'tic-icon-select-' . $bID;
$selectedIcon = $icon ?? 'fas fa-star';
$iconChoices = $iconChoices ?? ['fas fa-star' => 'star'];
if (!array_key_exists($selectedIcon, $iconChoices)) {
    $iconChoices = [$selectedIcon => $selectedIcon] + $iconChoices;
}
?>

<fieldset class="mb-3">
    <legend class="fs-6"><?= t('Icon') ?></legend>

    <div class="form-group mb-0 ccm-block-select-icon tic-icon-picker-shell">
        <?= $form->label($iconSelectId, t('Choose Icon'), ['class' => 'form-label']) ?>
        <div class="tic-icon-picker" id="tic-icon-picker-<?= (int) $bID ?>">
            <div class="tic-icon-picker-preview" aria-hidden="true">
                <i class="<?= h($selectedIcon) ?>"></i>
            </div>
            <div class="tic-icon-picker-field">
                <select
                    name="icon"
                    id="<?= h($iconSelectId) ?>"
                    class="form-select tic-icon-select"
                    title="<?= h(t('Choose Icon')) ?>"
                >
                    <?php foreach ($iconChoices as $value => $label) { ?>
                        <option value="<?= h($value) ?>" <?= $selectedIcon === $value ? 'selected' : '' ?>>
                            <?= h($label) ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <p class="help-block small text-muted mb-0 mt-2">
            <?= t('Click the field and type to search. Icons are shown visually in the list.') ?>
        </p>
    </div>
</fieldset>

<div class="tic-block-editor row g-3">
    <div class="col-lg-7 tic-block-editor-controls">
        <fieldset class="mb-3">
            <legend class="fs-6"><?= t('Icon Style') ?></legend>

            <div class="row g-2">
                <div class="col-md-6">
                    <?= $form->label('sizeIcon', t('Icon Size'), ['class' => 'form-label small']) ?>
                    <div class="input-group input-group-sm">
                        <?= $form->number($view->field('sizeIcon'), $sizeIcon ?? 46, ['min' => 12, 'max' => 120, 'step' => 1, 'class' => 'tic-size-icon-input']) ?>
                        <span class="input-group-text">px</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <?= $form->label('postitionIcon', t('Icon Position'), ['class' => 'form-label small']) ?>
                    <?= $form->select($view->field('postitionIcon'), $postitionIcon_options, $postitionIcon ?? '1', ['class' => 'form-select form-select-sm tic-position-select']) ?>
                </div>
            </div>

            <div class="row g-2 mt-2">
                <div class="col-md-6">
                    <?= $form->label('iconPadding', t('Icon Padding'), ['class' => 'form-label small']) ?>
                    <div class="input-group input-group-sm">
                        <?= $form->number($view->field('iconPadding'), $iconPadding ?? 8, ['min' => 0, 'max' => 50, 'step' => 1, 'class' => 'tic-icon-padding-input']) ?>
                        <span class="input-group-text">px</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <?= $form->label('colorIcon', t('Icon Color'), ['class' => 'form-label small']) ?>
                    <div class="input-group input-group-sm">
                        <?= $form->text($view->field('colorIcon'), $colorIcon ?? '#ff05c4', ['maxlength' => 7, 'placeholder' => '#ff05c4', 'class' => 'form-control tic-color-icon-input']) ?>
                        <input type="color" class="form-control form-control-color tic-color-icon-picker" value="<?= h($colorIcon ?: '#ff05c4') ?>" aria-label="<?= t('Icon Color') ?>">
                    </div>
                </div>
            </div>
        </fieldset>

        <fieldset class="mb-3">
            <legend class="fs-6"><?= t('Drop Shadow') ?></legend>

            <div class="row g-2">
                <div class="col-md-6">
                    <?= $form->label('dropShadowBlur', t('Shadow Blur'), ['class' => 'form-label small']) ?>
                    <div class="input-group input-group-sm">
                        <?= $form->number($view->field('dropShadowBlur'), $dropShadowBlur ?? 0, ['min' => 0, 'max' => 50, 'step' => 1, 'class' => 'tic-drop-shadow-blur-input']) ?>
                        <span class="input-group-text">px</span>
                    </div>
                    <p class="help-block small text-muted mb-0">
                        <?= t('Set to 0 to disable the shadow.') ?>
                    </p>
                </div>
                <div class="col-md-6">
                    <?= $form->label('dropShadowOpacity', t('Shadow Opacity'), ['class' => 'form-label small']) ?>
                    <div class="input-group input-group-sm">
                        <?= $form->number($view->field('dropShadowOpacity'), $dropShadowOpacity ?? 35, ['min' => 0, 'max' => 100, 'step' => 1, 'class' => 'tic-drop-shadow-opacity-input']) ?>
                        <span class="input-group-text">%</span>
                    </div>
                </div>
            </div>

            <div class="form-group mt-2 mb-0">
                <?= $form->label('dropShadowColor', t('Shadow Color'), ['class' => 'form-label small']) ?>
                <div class="input-group input-group-sm">
                    <?= $form->text($view->field('dropShadowColor'), $dropShadowColor ?? '#333333', ['maxlength' => 7, 'placeholder' => '#333333', 'class' => 'form-control tic-drop-shadow-color-input']) ?>
                    <input type="color" class="form-control form-control-color tic-drop-shadow-color-picker" value="<?= h($dropShadowColor ?: '#333333') ?>" aria-label="<?= t('Shadow Color') ?>">
                </div>
                <p class="help-block small text-muted mb-0 mt-2">
                    <?= t('The shadow clears when visitors hover over the icon.') ?>
                </p>
            </div>
        </fieldset>

        <fieldset class="mb-0">
            <legend class="fs-6"><?= t('Line Style') ?></legend>

            <div class="row g-2">
                <div class="col-md-6">
                    <?= $form->label('hrStyle', t('Line Style'), ['class' => 'form-label small']) ?>
                    <?= $form->select($view->field('hrStyle'), $hrStyle_options, $hrStyle ?? '1', ['class' => 'form-select form-select-sm tic-hr-style-select']) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->label('heightLine', t('Line Thickness'), ['class' => 'form-label small']) ?>
                    <div class="input-group input-group-sm">
                        <?= $form->number($view->field('heightLine'), $heightLine ?? 1, ['min' => 0, 'max' => 20, 'step' => 1, 'class' => 'tic-height-line-input']) ?>
                        <span class="input-group-text">px</span>
                    </div>
                    <p class="help-block small text-muted mb-0 tic-double-line-note" style="display:none;">
                        <?= t('Double lines need at least 3px thickness to be visible.') ?>
                    </p>
                </div>
            </div>

            <div class="form-group mt-2 mb-2">
                <?= $form->label('colorLine', t('Line Color'), ['class' => 'form-label small']) ?>
                <div class="input-group input-group-sm">
                    <?= $form->text($view->field('colorLine'), $colorLine ?? '#333333', ['maxlength' => 7, 'placeholder' => '#333333', 'class' => 'form-control tic-color-line-input']) ?>
                    <input type="color" class="form-control form-control-color tic-color-line-picker" value="<?= h($colorLine ?: '#333333') ?>" aria-label="<?= t('Line Color') ?>">
                </div>
            </div>

            <div class="row g-2">
                <div class="col-md-6">
                    <?= $form->label('blockMarginTop', t('Margin Top'), ['class' => 'form-label small']) ?>
                    <div class="input-group input-group-sm">
                        <?= $form->number($view->field('blockMarginTop'), $blockMarginTop ?? 33, ['min' => 0, 'step' => 1, 'class' => 'tic-margin-top-input']) ?>
                        <span class="input-group-text">px</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <?= $form->label('blockMarginBottom', t('Margin Bottom'), ['class' => 'form-label small']) ?>
                    <div class="input-group input-group-sm">
                        <?= $form->number($view->field('blockMarginBottom'), $blockMarginBottom ?? 33, ['min' => 0, 'step' => 1, 'class' => 'tic-margin-bottom-input']) ?>
                        <span class="input-group-text">px</span>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>

    <div class="col-lg-5 tic-block-editor-preview">
        <div class="tic-preview-panel border rounded bg-light h-100">
            <div class="tic-preview-panel-header px-3 py-2 border-bottom bg-white rounded-top">
                <strong class="small text-uppercase text-muted"><?= t('Preview') ?></strong>
            </div>
            <div class="tic-preview-panel-body p-3">
                <div class="tic-icon-hr-preview">
                    <div class="tic-icon-hr-preview-line">
                        <div class="tic-icon-hr-preview-icon-wrap">
                            <span class="tic-icon-hr-preview-icon"><i class="fas fa-star"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.tic-block-editor-controls {
    padding-right: 0.25rem;
}

.tic-block-editor-preview {
    position: sticky;
    top: 0;
    align-self: flex-start;
}

.tic-preview-panel {
    min-height: 220px;
}

.tic-preview-panel-body {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 180px;
}

.tic-icon-hr-preview {
    width: 100%;
}

.tic-icon-hr-preview-line {
    display: flex;
    width: 100%;
    height: 0;
    align-items: center;
    justify-content: center;
}

.tic-icon-hr-preview-icon-wrap {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    line-height: 1;
    transition: box-shadow 0.25s ease;
}

.tic-icon-hr-preview-icon-wrap:hover {
    box-shadow: none !important;
}

.tic-icon-hr-preview-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    line-height: 1;
}

.tic-block-editor fieldset {
    margin-bottom: 0.75rem;
}

.tic-block-editor legend {
    margin-bottom: 0.5rem;
}

.tic-icon-picker-shell,
.tic-icon-picker-shell .form-group,
.tic-block-editor-controls,
.tic-block-editor.row {
    overflow: visible;
}

.tic-icon-picker {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.tic-icon-picker-preview {
    width: 52px;
    height: 52px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--bs-border-color, #dee2e6);
    border-radius: 0.375rem;
    background: #fff;
    flex-shrink: 0;
    font-size: 26px;
    line-height: 1;
}

.tic-icon-picker-field {
    flex: 1;
    min-width: 0;
}

.tic-icon-picker-field .ts-control {
    min-height: 52px;
    align-items: center;
}

.tic-icon-picker-field .ts-dropdown,
div.ccm-block-select-icon .ts-dropdown {
    z-index: 12000;
}

.tic-icon-picker-field .tic-icon-option,
.tic-icon-picker-field .tic-icon-selected {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    min-height: 34px;
    font-size: 22px;
    line-height: 1;
}

.tic-icon-picker-field .tic-icon-option-label {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}

div.ccm-block-select-icon i {
    font-size: 22px;
}
</style>

<script type="text/javascript">
(function ($) {
    var iconSelectId = <?= json_encode($iconSelectId) ?>;
    var iconPickerId = <?= json_encode('tic-icon-picker-' . $bID) ?>;

    function getIconPicker() {
        return document.getElementById(iconPickerId);
    }

    function getIconSelect() {
        return document.getElementById(iconSelectId);
    }

    function updateIconPreview(iconClass) {
        var picker = getIconPicker();
        if (!picker) {
            return;
        }

        var previewIcon = picker.querySelector('.tic-icon-picker-preview i');
        if (previewIcon) {
            previewIcon.className = iconClass || 'fas fa-star';
        }

        document.dispatchEvent(new CustomEvent('tic-icon-hr-icon-changed', {
            detail: { icon: iconClass || 'fas fa-star' },
        }));
    }

    function renderIconOnly(data, escape) {
        return '<div class="tic-icon-option" title="' + escape(data.text) + '">' +
            '<i class="' + escape(data.value) + '" aria-hidden="true"></i>' +
            '<span class="tic-icon-option-label">' + escape(data.text) + '</span>' +
            '</div>';
    }

    function initIconPicker() {
        var select = getIconSelect();
        if (!select) {
            return false;
        }

        if (select.getAttribute('data-tic-icon-ready') === '1' && select.tomselect) {
            return true;
        }

        if (typeof TomSelect === 'undefined') {
            return false;
        }

        if (select.tomselect) {
            select.tomselect.destroy();
        }

        var tomSelect = new TomSelect(select, {
            maxOptions: null,
            dropdownParent: 'body',
            allowEmptyOption: false,
            render: {
                option: renderIconOnly,
                item: function (data, escape) {
                    return '<div class="tic-icon-selected" title="' + escape(data.text) + '">' +
                        '<i class="' + escape(data.value) + '" aria-hidden="true"></i>' +
                        '<span class="tic-icon-option-label">' + escape(data.text) + '</span>' +
                        '</div>';
                },
            },
        });

        tomSelect.on('change', function (value) {
            updateIconPreview(value);
        });

        select.setAttribute('data-tic-icon-ready', '1');
        updateIconPreview(tomSelect.getValue());

        return true;
    }

    function tryInitIconPicker(attempt) {
        if (initIconPicker()) {
            return;
        }

        if (attempt < 30) {
            window.setTimeout(function () {
                tryInitIconPicker(attempt + 1);
            }, 100);
            return;
        }

        var select = getIconSelect();
        if (select && select.getAttribute('data-tic-icon-ready') !== '1') {
            select.addEventListener('change', function () {
                updateIconPreview(select.value);
            });
            select.setAttribute('data-tic-icon-ready', '1');
            updateIconPreview(select.value);
        }
    }

    $(function () {
        tryInitIconPicker(0);

        if (typeof ConcreteEvent !== 'undefined') {
            ConcreteEvent.subscribe('EditModeInlineEditLoaded', function () {
                window.setTimeout(function () {
                    tryInitIconPicker(0);
                }, 50);
            });
        }
    });
})(jQuery);
</script>

<script>
(function () {
    var editor = document.querySelector('.tic-block-editor');
    if (!editor) {
        return;
    }

    var iconFieldName = 'icon';
    var iconSelectId = <?= json_encode($iconSelectId) ?>;
    var positionMap = { '1': 'center', '2': 'flex-start', '3': 'flex-end' };
    var hrStyleMap = { '1': 'solid', '2': 'dashed', '3': 'dotted', '4': 'double' };

    var previewLine = editor.querySelector('.tic-icon-hr-preview-line');
    var previewIconWrap = editor.querySelector('.tic-icon-hr-preview-icon-wrap');
    var previewIcon = editor.querySelector('.tic-icon-hr-preview-icon');
    var positionSelect = editor.querySelector('.tic-position-select');
    var sizeIconInput = editor.querySelector('.tic-size-icon-input');
    var iconPaddingInput = editor.querySelector('.tic-icon-padding-input');
    var colorIconInput = editor.querySelector('.tic-color-icon-input');
    var hrStyleSelect = editor.querySelector('.tic-hr-style-select');
    var heightLineInput = editor.querySelector('.tic-height-line-input');
    var colorLineInput = editor.querySelector('.tic-color-line-input');
    var marginTopInput = editor.querySelector('.tic-margin-top-input');
    var marginBottomInput = editor.querySelector('.tic-margin-bottom-input');
    var dropShadowBlurInput = editor.querySelector('.tic-drop-shadow-blur-input');
    var dropShadowOpacityInput = editor.querySelector('.tic-drop-shadow-opacity-input');
    var dropShadowColorInput = editor.querySelector('.tic-drop-shadow-color-input');
    var doubleLineNote = editor.querySelector('.tic-double-line-note');
    var iconInput = editor.querySelector('[name="' + iconFieldName + '"]');

    function getSpacingValue(input, fallback) {
        if (!input) {
            return fallback;
        }

        var value = parseInt(input.value, 10);

        return isNaN(value) || value < 0 ? fallback : value;
    }

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

    function normalizeIconClasses(iconValue) {
        iconValue = (iconValue || '').trim();

        if (iconValue === '') {
            return 'fas fa-star';
        }

        if (/^fa-[a-z0-9-]+$/i.test(iconValue)) {
            return 'fas ' + iconValue;
        }

        return iconValue;
    }

    function applyPreviewIconClasses(iconValue) {
        if (!previewIcon) {
            return;
        }

        var classes = normalizeIconClasses(iconValue).split(/\s+/).filter(Boolean);
        var iconElement = previewIcon.querySelector('i');

        if (!iconElement) {
            iconElement = document.createElement('i');
            previewIcon.innerHTML = '';
            previewIcon.appendChild(iconElement);
        }

        iconElement.className = classes.join(' ');
    }

    function bindIconInput(input) {
        if (!input || input.dataset.ticBound === '1') {
            return;
        }

        input.dataset.ticBound = '1';
        input.addEventListener('change', updatePreview);
        input.addEventListener('input', updatePreview);
    }

    function refreshIconInput() {
        var input = document.getElementById(iconSelectId);

        if (!input) {
            input = document.querySelector('[name="' + iconFieldName + '"]');
        }

        if (input && input !== iconInput) {
            iconInput = input;
            bindIconInput(iconInput);
        }

        return iconInput;
    }

    function toggleDoubleLineNote() {
        if (!doubleLineNote || !hrStyleSelect) {
            return;
        }

        doubleLineNote.style.display = hrStyleSelect.value === '4' ? '' : 'none';
    }

    function updatePreview() {
        if (!previewLine || !previewIconWrap || !previewIcon) {
            return;
        }

        var sizeIcon = getSpacingValue(sizeIconInput, 46);
        var iconPadding = getSpacingValue(iconPaddingInput, 8);
        var iconColor = colorIconInput && colorIconInput.value ? colorIconInput.value : '#ff05c4';
        var lineColor = colorLineInput && colorLineInput.value ? colorLineInput.value : '#333333';
        var position = positionSelect ? positionMap[positionSelect.value] || 'center' : 'center';
        var hrStyle = hrStyleSelect ? hrStyleMap[hrStyleSelect.value] || 'solid' : 'solid';
        var heightLine = getSpacingValue(heightLineInput, 1);
        var marginTop = getSpacingValue(marginTopInput, 33);
        var marginBottom = getSpacingValue(marginBottomInput, 33);
        var currentIconInput = refreshIconInput();

        previewLine.style.justifyContent = position;
        previewLine.style.borderBottom = heightLine + 'px ' + hrStyle + ' ' + lineColor;
        previewLine.style.margin = marginTop + 'px 0 ' + marginBottom + 'px';

        previewIconWrap.style.padding = iconPadding + 'px';
        previewIconWrap.style.boxShadow = getDropShadowStyle();

        previewIcon.style.fontSize = sizeIcon + 'px';
        previewIcon.style.color = iconColor;

        applyPreviewIconClasses(currentIconInput ? currentIconInput.value : normalizeIconClasses(<?= json_encode($selectedIcon) ?>));

        toggleDoubleLineNote();
    }

    editor.querySelectorAll('.input-group').forEach(function (group) {
        var textInput = group.querySelector('.tic-color-icon-input, .tic-color-line-input, .tic-drop-shadow-color-input');
        var pickerInput = group.querySelector('.tic-color-icon-picker, .tic-color-line-picker, .tic-drop-shadow-color-picker');
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
        positionSelect,
        sizeIconInput,
        iconPaddingInput,
        colorIconInput,
        hrStyleSelect,
        heightLineInput,
        colorLineInput,
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

    document.addEventListener('change', function (event) {
        if (!event.target || event.target.name !== iconFieldName) {
            return;
        }

        refreshIconInput();
        updatePreview();
    }, true);

    document.addEventListener('tic-icon-hr-icon-changed', function () {
        refreshIconInput();
        updatePreview();
    });

    window.setTimeout(function () {
        bindIconInput(refreshIconInput());
        updatePreview();
    }, 300);
})();
</script>
