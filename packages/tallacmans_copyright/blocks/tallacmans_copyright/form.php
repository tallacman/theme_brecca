<?php

declare(strict_types=1);

defined('C5_EXECUTE') or die('Access Denied.');

$fontFamilyField = $view->field('fontFamily');
$fontFamilyCustomField = $view->field('fontFamilyCustom');
$showCustomFontField = ($fontFamily ?? '') === '__custom__';
?>

<fieldset>
    <legend><?= t('Copyright Content') ?></legend>

    <div class="form-group">
        <?= $form->label('copyrightStart', t('Year Copyright Started')) ?>
        <?= $form->number($view->field('copyrightStart'), $copyrightStart ?? '', ['min' => 1900, 'max' => (int) date('Y'), 'step' => 1, 'placeholder' => t('Optional')]) ?>
        <p class="help-block">
            <?= t('Enter the four-digit year your copyright started. Leave blank to show only the current year.') ?>
        </p>
    </div>

    <div class="form-group">
        <?= $form->label('copyrightHolder', t('Copyright Holder')) ?>
        <?= $form->text($view->field('copyrightHolder'), $copyrightHolder ?? '', ['maxlength' => 70, 'placeholder' => t('Company or person name')]) ?>
        <p class="help-block">
            <?= t('The name of the copyright holder displayed after the year.') ?>
        </p>
    </div>
</fieldset>

<fieldset>
    <legend><?= t('Spacing') ?></legend>

    <div class="form-group">
        <?= $form->label('blockPaddingTop', t('Block Padding Top')) ?>
        <div class="input-group">
            <?= $form->number($view->field('blockPaddingTop'), $blockPaddingTop ?? 0, ['min' => 0, 'step' => 1]) ?>
            <span class="input-group-text">px</span>
        </div>
    </div>

    <div class="form-group">
        <?= $form->label('blockPaddingBottom', t('Block Padding Bottom')) ?>
        <div class="input-group">
            <?= $form->number($view->field('blockPaddingBottom'), $blockPaddingBottom ?? 0, ['min' => 0, 'step' => 1]) ?>
            <span class="input-group-text">px</span>
        </div>
        <p class="help-block">
            <?= t('Adds space above or below the copyright notice.') ?>
        </p>
    </div>
</fieldset>

<fieldset>
    <legend><?= t('Typography') ?></legend>

    <div class="form-group">
        <?= $form->label('fontFamily', t('Font Family')) ?>
        <?= $form->select($fontFamilyField, $fontFamily_options, $fontFamily ?? '', ['class' => 'form-select tallacmans-copyright-font-family-select']) ?>
    </div>

    <div class="form-group tallacmans-copyright-custom-font-group"<?= $showCustomFontField ? '' : ' style="display:none;"' ?>>
        <?= $form->label('fontFamilyCustom', t('Custom Font Family')) ?>
        <?= $form->text($fontFamilyCustomField, $fontFamilyCustom ?? '', [
            'maxlength' => 255,
            'placeholder' => t('For example, "Playfair Display", Georgia, serif'),
            'class' => 'form-control tallacmans-copyright-font-family-custom',
        ]); ?>
        <p class="help-block">
            <?= t('Enter a CSS font-family value. Use quotes around multi-word font names.') ?>
        </p>
    </div>

    <div class="form-group">
        <?= $form->label('fontWeight', t('Font Weight')) ?>
        <?= $form->select($view->field('fontWeight'), $fontWeight_options, $fontWeight ?? '400', ['class' => 'form-select tallacmans-copyright-font-weight']) ?>
    </div>

    <div class="form-group">
        <?= $form->label('fontSize', t('Font Size')) ?>
        <div class="input-group">
            <?= $form->number($view->field('fontSize'), $fontSize ?? 14, ['min' => 1, 'step' => 1, 'class' => 'tallacmans-copyright-font-size']) ?>
            <span class="input-group-text">px</span>
        </div>
    </div>

    <div class="form-group">
        <?= $form->label('textColor', t('Text Color')) ?>
        <div class="input-group">
            <?= $form->text($view->field('textColor'), $textColor ?? '', ['maxlength' => 7, 'placeholder' => '#666666', 'class' => 'form-control tallacmans-copyright-color-input']) ?>
            <input type="color" class="form-control form-control-color tallacmans-copyright-color-picker" value="<?= h($textColor ?: '#666666') ?>" aria-label="<?= t('Text Color') ?>">
        </div>
        <p class="help-block">
            <?= t('Leave blank to inherit the theme text color.') ?>
        </p>
    </div>

    <div class="form-group">
        <?= $form->label('fontPreview', t('Preview')) ?>
        <div class="tallacmans-copyright-font-preview border rounded p-3 bg-light">
            <span class="tallacmans-copyright-font-preview-text">&copy; <?= (int) date('Y') ?> <?= t('Example Company') ?> &ndash; <?= t('All rights reserved.') ?></span>
        </div>
    </div>
</fieldset>

<script>
(function () {
    var customValue = <?= json_encode('__custom__') ?>;
    var fontFamilySelect = document.querySelector('.tallacmans-copyright-font-family-select');
    var customFontGroup = document.querySelector('.tallacmans-copyright-custom-font-group');
    var customFontInput = document.querySelector('.tallacmans-copyright-font-family-custom');
    var previewText = document.querySelector('.tallacmans-copyright-font-preview-text');
    var fontWeightSelect = document.querySelector('.tallacmans-copyright-font-weight');
    var fontSizeInput = document.querySelector('.tallacmans-copyright-font-size');
    var colorInput = document.querySelector('.tallacmans-copyright-color-input');

    function toggleCustomFontField() {
        if (!fontFamilySelect || !customFontGroup) {
            return;
        }

        var showCustom = fontFamilySelect.value === customValue;
        customFontGroup.style.display = showCustom ? '' : 'none';
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

    function updatePreview() {
        if (!previewText) {
            return;
        }

        var fontFamily = getSelectedFontFamily();
        previewText.style.fontFamily = fontFamily || 'inherit';
        previewText.style.fontWeight = fontWeightSelect ? fontWeightSelect.value : '400';
        previewText.style.fontSize = (fontSizeInput ? fontSizeInput.value : '14') + 'px';
        previewText.style.color = colorInput && colorInput.value ? colorInput.value : '';
    }

    if (fontFamilySelect) {
        fontFamilySelect.addEventListener('change', toggleCustomFontField);
    }

    [customFontInput, fontWeightSelect, fontSizeInput, colorInput].forEach(function (element) {
        if (!element) {
            return;
        }

        element.addEventListener('input', updatePreview);
        element.addEventListener('change', updatePreview);
    });

    document.querySelectorAll('.input-group').forEach(function (group) {
        var textInput = group.querySelector('.tallacmans-copyright-color-input');
        var pickerInput = group.querySelector('.tallacmans-copyright-color-picker');
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

    toggleCustomFontField();
})();
</script>
