<?php

declare(strict_types=1);

use Concrete\Core\File\Type\Type as FileType;

defined('C5_EXECUTE') or die('Access Denied.');

$logoField = $view->field('logoIcon');
$fileInputUniqid = 'tsn-file-' . $identifier_getString;
$logoFileId = (!empty($logoIcon) && (int) $logoIcon > 0) ? (int) $logoIcon : 0;
$logoFilters = json_encode([['field' => 'type', 'type' => FileType::T_IMAGE]]);
$defaultPreviewName = t('Your Site Name');
?>

<div class="tsn-block-editor row g-3">
    <div class="col-lg-7 tsn-block-editor-controls">
        <fieldset class="mb-3">
            <legend class="fs-6"><?= t('Content') ?></legend>

            <div class="form-group mb-2">
                <?= $form->label('displaySitename', t('Site Name'), ['class' => 'form-label']) ?>
                <?= $form->text($view->field('displaySitename'), $displaySitename ?? '', [
                    'maxlength' => 255,
                    'placeholder' => t('Your site name'),
                    'class' => 'form-control form-control-sm tsn-site-name-input',
                ]) ?>
                <p class="help-block small text-muted mb-0">
                    <?= t('Optional when a logo is selected.') ?>
                </p>
            </div>

            <div class="form-group mb-0">
                <?= $form->label('logoIcon', t('Logo'), ['class' => 'form-label']) ?>
                <div class="tsn-logo-picker" data-concrete-file-input="<?= h($fileInputUniqid) ?>">
                    <concrete-file-input
                        :filters='<?= $logoFilters ?>'
                        :file-id="<?= $logoFileId ?>"
                        choose-text="<?= h(t('Choose Logo')) ?>"
                        input-name="<?= h($logoField) ?>"
                        @selectedfile="handleLogoFileSelect"
                    ></concrete-file-input>
                </div>
                <p class="help-block small text-muted mb-0 mt-2">
                    <?= t('The default template limits the logo height to 50 pixels. Use the Unconstrained custom template to display its original size.') ?>
                </p>
            </div>
        </fieldset>

        <fieldset class="mb-3">
            <legend class="fs-6"><?= t('Layout') ?></legend>

            <div class="form-group mb-0">
                <?= $form->label('alignment', t('Logo Position'), ['class' => 'form-label']) ?>
                <?= $form->select($view->field('alignment'), $alignmentOptions, $alignment ?: 'image-left', [
                    'class' => 'form-select form-select-sm tsn-alignment-select',
                ]) ?>
            </div>
        </fieldset>

        <fieldset class="mb-0">
            <legend class="fs-6"><?= t('Site Name Typography') ?></legend>

            <div class="form-group mb-2">
                <?= $form->label('textColor', t('Text Color'), ['class' => 'form-label small']) ?>
                <div class="input-group input-group-sm">
                    <?= $form->text($view->field('textColor'), $textColor ?? '', [
                        'maxlength' => 7,
                        'placeholder' => '#333333',
                        'class' => 'form-control tsn-text-color-input',
                    ]) ?>
                    <input
                        type="color"
                        class="form-control form-control-color tsn-text-color-picker"
                        value="<?= h($textColor ?: '#333333') ?>"
                        aria-label="<?= t('Text Color') ?>"
                    >
                </div>
                <p class="help-block small text-muted mb-0">
                    <?= t('Leave empty to use the theme color.') ?>
                </p>
            </div>

            <div class="form-group mb-2">
                <?= $form->label('fontFamily', t('Font Family'), ['class' => 'form-label small']) ?>
                <?= $form->select($view->field('fontFamily'), $fontFamilyOptions, $fontFamily ?? '', [
                    'class' => 'form-select form-select-sm tsn-font-family-select',
                ]) ?>
            </div>

            <div class="form-group mb-0">
                <?= $form->label('fontSize', t('Font Size'), ['class' => 'form-label small']) ?>
                <div class="input-group input-group-sm">
                    <?= $form->number($view->field('fontSize'), (int) ($fontSize ?? 0), [
                        'min' => 0,
                        'max' => 200,
                        'step' => 1,
                        'inputmode' => 'numeric',
                        'class' => 'tsn-font-size-input',
                    ]) ?>
                    <span class="input-group-text"><?= t('px') ?></span>
                </div>
                <p class="help-block small text-muted mb-0">
                    <?= t('Enter 8–200 pixels, or 0 to use the theme size.') ?>
                </p>
            </div>
        </fieldset>
    </div>

    <div class="col-lg-5 tsn-block-editor-preview">
        <div class="tsn-preview-panel border rounded bg-light h-100">
            <div class="tsn-preview-panel-header px-3 py-2 border-bottom bg-white rounded-top">
                <strong class="small text-uppercase text-muted"><?= t('Preview') ?></strong>
            </div>
            <div class="tsn-preview-panel-body p-3">
                <div class="tsn-sitename-preview">
                    <div class="tallacmans-sitename-wrapper">
                        <div class="tallacmans-sitename tsn-sitename-preview-inner">
                            <div class="tallacmans-sitename-text tsn-sitename-preview-text-wrap">
                                <a href="#" class="tsn-sitename-preview-text" onclick="return false;">
                                    <?= h($displaySitename ?: $defaultPreviewName) ?>
                                </a>
                            </div>
                            <div class="tallacmans-sitename-icon tsn-sitename-preview-icon-wrap">
                                <a href="#" onclick="return false;">
                                    <img
                                        class="tsn-sitename-preview-logo"
                                        src="<?= h($previewLogoUrl ?? '') ?>"
                                        alt=""
                                        <?= empty($previewLogoUrl) ? ' style="display:none;"' : '' ?>
                                    >
                                </a>
                            </div>
                        </div>
                    </div>
                    <p class="tsn-sitename-preview-empty small text-muted text-center mb-0 mt-3" style="display:none;">
                        <?= t('Enter a site name or choose a logo to preview.') ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.tsn-block-editor-controls {
    padding-right: 0.25rem;
}

.tsn-block-editor-preview {
    position: sticky;
    top: 0;
    align-self: flex-start;
}

.tsn-preview-panel {
    min-height: 240px;
}

.tsn-preview-panel-body {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 200px;
}

.tsn-sitename-preview {
    width: 100%;
}

.tsn-sitename-preview .tallacmans-sitename-wrapper {
    display: flex;
    justify-content: center;
}

.tsn-sitename-preview .tallacmans-sitename {
    display: flex;
    align-items: center;
    justify-content: center;
}

.tsn-sitename-preview .tallacmans-sitename-text a {
    color: inherit;
    font-family: inherit;
    font-size: inherit;
    text-decoration: none;
}

.tsn-sitename-preview .align-image-left .tallacmans-sitename-icon {
    order: 1;
    margin-right: 12px;
}

.tsn-sitename-preview .align-image-left .tallacmans-sitename-text {
    order: 2;
}

.tsn-sitename-preview .align-image-right .tallacmans-sitename-icon {
    margin-left: 12px;
}

.tsn-sitename-preview .align-image-top,
.tsn-sitename-preview .align-image-bottom {
    flex-direction: column;
}

.tsn-sitename-preview .align-image-top .tallacmans-sitename-icon {
    order: 1;
}

.tsn-sitename-preview .align-image-top .tallacmans-sitename-text {
    order: 2;
}

.tsn-sitename-preview .tallacmans-sitename-icon img {
    max-height: 50px;
    width: auto;
    display: block;
}

.tsn-block-editor fieldset {
    margin-bottom: 0.75rem;
}

.tsn-block-editor legend {
    margin-bottom: 0.5rem;
}
</style>

<script>
$(function () {
    Concrete.Vue.activateContext('cms', function (Vue, config) {
        new Vue({
            el: 'div[data-concrete-file-input="<?= h($fileInputUniqid) ?>"]',
            components: config.components,
            methods: {
                handleLogoFileSelect: function (file) {
                    document.dispatchEvent(new CustomEvent('tsn-sitename-logo-selected', {
                        detail: { file: file || null }
                    }));
                }
            }
        });
    });
});
</script>

<script>
(function () {
    var editor = document.querySelector('.tsn-block-editor');
    if (!editor) {
        return;
    }

    var logoFieldName = <?= json_encode($logoField) ?>;
    var initialPreviewLogoUrl = <?= json_encode($previewLogoUrl ?? '') ?>;
    var defaultPreviewName = <?= json_encode($defaultPreviewName) ?>;
    var fontFamilyCssMap = <?= json_encode($fontFamilyCssMap ?? []) ?>;

    var siteNameInput = editor.querySelector('.tsn-site-name-input');
    var alignmentSelect = editor.querySelector('.tsn-alignment-select');
    var textColorInput = editor.querySelector('.tsn-text-color-input');
    var textColorPicker = editor.querySelector('.tsn-text-color-picker');
    var fontFamilySelect = editor.querySelector('.tsn-font-family-select');
    var fontSizeInput = editor.querySelector('.tsn-font-size-input');
    var previewInner = editor.querySelector('.tsn-sitename-preview-inner');
    var previewTextWrap = editor.querySelector('.tsn-sitename-preview-text-wrap');
    var previewText = editor.querySelector('.tsn-sitename-preview-text');
    var previewIconWrap = editor.querySelector('.tsn-sitename-preview-icon-wrap');
    var previewLogo = editor.querySelector('.tsn-sitename-preview-logo');
    var previewEmpty = editor.querySelector('.tsn-sitename-preview-empty');
    var logoInput = editor.querySelector('[name="' + logoFieldName + '"]');
    var lastLoadedFileId = null;

    function getFilePreviewUrl(file) {
        if (!file) {
            return '';
        }

        if (file.url) {
            return file.url;
        }

        if (file.urlInline) {
            return file.urlInline;
        }

        if (file.resultsThumbnailImg) {
            var match = String(file.resultsThumbnailImg).match(/src=["']([^"']+)["']/);
            if (match) {
                return match[1];
            }
        }

        return '';
    }

    function setPreviewLogoUrl(url) {
        if (!previewLogo || !previewIconWrap) {
            return;
        }

        if (url) {
            previewLogo.src = url;
            previewLogo.style.display = '';
            previewIconWrap.style.display = '';
        } else {
            previewLogo.removeAttribute('src');
            previewLogo.style.display = 'none';
            previewIconWrap.style.display = 'none';
        }
    }

    function loadPreviewFromFileId(fileId) {
        fileId = parseInt(fileId, 10);

        if (!fileId) {
            lastLoadedFileId = null;
            setPreviewLogoUrl('');
            updatePreview();
            return;
        }

        if (fileId === lastLoadedFileId && previewLogo && previewLogo.src) {
            updatePreview();
            return;
        }

        if (typeof ConcreteFileManager === 'undefined') {
            updatePreview();
            return;
        }

        ConcreteFileManager.getFileDetails(fileId, function (response) {
            if (!response || !response.files || !response.files[0]) {
                lastLoadedFileId = null;
                setPreviewLogoUrl('');
                updatePreview();
                return;
            }

            lastLoadedFileId = fileId;
            setPreviewLogoUrl(getFilePreviewUrl(response.files[0]));
            updatePreview();
        });
    }

    function getSelectedFontFamilyCss() {
        if (!fontFamilySelect) {
            return '';
        }

        var value = fontFamilySelect.value;

        if (value === '') {
            return '';
        }

        if (Object.prototype.hasOwnProperty.call(fontFamilyCssMap, value)) {
            return fontFamilyCssMap[value];
        }

        return value;
    }

    function getSiteNameValue() {
        return siteNameInput ? siteNameInput.value.trim() : '';
    }

    function hasPreviewLogo() {
        return !!(previewLogo && previewLogo.src && previewLogo.style.display !== 'none');
    }

    function updatePreview() {
        var siteName = getSiteNameValue();
        var hasLogo = hasPreviewLogo();
        var alignment = alignmentSelect ? alignmentSelect.value || 'image-left' : 'image-left';
        var textColor = textColorInput ? textColorInput.value.trim() : '';
        var fontFamilyCss = getSelectedFontFamilyCss();
        var fontSize = fontSizeInput ? parseInt(fontSizeInput.value, 10) : 0;

        if (previewInner) {
            previewInner.className = 'tallacmans-sitename tsn-sitename-preview-inner';
            if (siteName !== '' && hasLogo) {
                previewInner.classList.add('align-' + alignment);
            }
        }

        if (previewTextWrap && previewText) {
            if (siteName !== '') {
                previewTextWrap.style.display = '';
                previewText.textContent = siteName;
            } else if (!hasLogo) {
                previewTextWrap.style.display = '';
                previewText.textContent = defaultPreviewName;
            } else {
                previewTextWrap.style.display = 'none';
            }
        }

        if (previewText) {
            previewText.style.color = textColor || '';
            previewText.style.fontFamily = fontFamilyCss || 'inherit';
            previewText.style.fontSize = fontSize > 0 ? fontSize + 'px' : '';
        }

        if (previewEmpty) {
            previewEmpty.style.display = siteName === '' && !hasLogo ? '' : 'none';
        }
    }

    if (textColorPicker && textColorInput) {
        textColorPicker.addEventListener('input', function () {
            textColorInput.value = textColorPicker.value;
            updatePreview();
        });

        textColorInput.addEventListener('input', function () {
            if (/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/.test(textColorInput.value)) {
                textColorPicker.value = textColorInput.value.length === 4
                    ? '#' + textColorInput.value[1] + textColorInput.value[1]
                        + textColorInput.value[2] + textColorInput.value[2]
                        + textColorInput.value[3] + textColorInput.value[3]
                    : textColorInput.value;
            }
            updatePreview();
        });
    }

    [
        siteNameInput,
        alignmentSelect,
        fontFamilySelect,
        fontSizeInput,
    ].forEach(function (element) {
        if (!element) {
            return;
        }

        element.addEventListener('input', updatePreview);
        element.addEventListener('change', updatePreview);
    });

    document.addEventListener('tsn-sitename-logo-selected', function (event) {
        var file = event.detail ? event.detail.file : null;

        if (!file) {
            lastLoadedFileId = null;
            setPreviewLogoUrl('');
            updatePreview();
            return;
        }

        if (file.fID) {
            lastLoadedFileId = parseInt(file.fID, 10);
        }

        setPreviewLogoUrl(getFilePreviewUrl(file));
        updatePreview();
    });

    document.addEventListener('click', function (event) {
        var target = event.target;
        if (!target || !target.closest) {
            return;
        }

        if (target.closest('[data-concrete-file-input="<?= h($fileInputUniqid) ?>"] .ccm-file-selector-clear-button')) {
            lastLoadedFileId = null;
            setPreviewLogoUrl('');
            updatePreview();
        }
    });

    var logoPicker = editor.querySelector('.tsn-logo-picker');
    if (logoPicker && typeof MutationObserver !== 'undefined') {
        var fileObserver = new MutationObserver(function () {
            var input = editor.querySelector('[name="' + logoFieldName + '"]');
            if (input && input !== logoInput) {
                logoInput = input;
                logoInput.addEventListener('change', function () {
                    loadPreviewFromFileId(logoInput.value);
                });
                logoInput.addEventListener('input', function () {
                    loadPreviewFromFileId(logoInput.value);
                });
            }

            if (input && input.value) {
                loadPreviewFromFileId(input.value);
            }
        });
        fileObserver.observe(logoPicker, { childList: true, subtree: true, attributes: true });
    }

    if (initialPreviewLogoUrl) {
        setPreviewLogoUrl(initialPreviewLogoUrl);
    } else if (logoInput && logoInput.value) {
        loadPreviewFromFileId(logoInput.value);
    } else {
        setPreviewLogoUrl('');
    }

    updatePreview();
})();
</script>
