<?php

declare(strict_types=1);

use Concrete\Core\File\Type\Type as FileType;

defined('C5_EXECUTE') or die('Access Denied.');

$imageField = $view->field('image');
$fileInputUniqid = 'tih-file-' . $identifier_getString;
$fID = (!empty($image) && (int) $image > 0) ? (int) $image : 0;
$imageFilters = json_encode([['field' => 'type', 'type' => FileType::T_IMAGE]]);
?>

<div class="tih-block-editor row g-3">
    <div class="col-lg-7 tih-block-editor-controls">
        <fieldset class="mb-3">
            <legend class="fs-6"><?= t('Image') ?></legend>

            <div class="form-group mb-2">
                <?= $form->label('image', t('Choose Image'), ['class' => 'form-label']) ?>
                <div class="tih-image-picker" data-concrete-file-input="<?= h($fileInputUniqid) ?>">
                    <concrete-file-input
                        :filters='<?= $imageFilters ?>'
                        :file-id="<?= $fID ?>"
                        choose-text="<?= h(t('Choose Image')) ?>"
                        input-name="<?= h($imageField) ?>"
                        @selectedfile="handleImageFileSelect"
                    ></concrete-file-input>
                </div>
            </div>

            <div class="row g-2">
                <div class="col-md-6">
                    <?= $form->label('sizeImage', t('Image Size'), ['class' => 'form-label small']) ?>
                    <div class="input-group input-group-sm">
                        <?= $form->number($view->field('sizeImage'), $sizeImage ?? 110, ['min' => 1, 'max' => 400, 'step' => 1, 'class' => 'tih-size-image-input']) ?>
                        <span class="input-group-text">px</span>
                    </div>
                    <p class="help-block small text-muted mb-0">
                        <?= t('Diameter of the circular image.') ?>
                    </p>
                </div>
                <div class="col-md-6">
                    <?= $form->label('postitionImage', t('Image Position'), ['class' => 'form-label small']) ?>
                    <?= $form->select($view->field('postitionImage'), $postitionImage_options, $postitionImage ?? '1', ['class' => 'form-select form-select-sm tih-position-select']) ?>
                </div>
            </div>
        </fieldset>

        <fieldset class="mb-3">
            <legend class="fs-6"><?= t('Image Frame') ?></legend>

            <div class="row g-2">
                <div class="col-md-6">
                    <?= $form->label('paddingImage', t('Image Padding'), ['class' => 'form-label small']) ?>
                    <div class="input-group input-group-sm">
                        <?= $form->number($view->field('paddingImage'), $paddingImage ?? 0, ['min' => 0, 'max' => 50, 'step' => 1, 'class' => 'tih-padding-image-input']) ?>
                        <span class="input-group-text">px</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <?= $form->label('borderImage', t('Image Border'), ['class' => 'form-label small']) ?>
                    <div class="input-group input-group-sm">
                        <?= $form->number($view->field('borderImage'), $borderImage ?? 0, ['min' => 0, 'max' => 20, 'step' => 1, 'class' => 'tih-border-image-input']) ?>
                        <span class="input-group-text">px</span>
                    </div>
                </div>
            </div>

            <div class="row g-2 mt-2">
                <div class="col-md-6">
                    <?= $form->label('borderColor', t('Border Color'), ['class' => 'form-label small']) ?>
                    <div class="input-group input-group-sm">
                        <?= $form->text($view->field('borderColor'), $borderColor ?? '#333333', ['maxlength' => 7, 'placeholder' => '#333333', 'class' => 'form-control tih-border-color-input']) ?>
                        <input type="color" class="form-control form-control-color tih-border-color-picker" value="<?= h($borderColor ?: '#333333') ?>" aria-label="<?= t('Border Color') ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <?= $form->label('colorBackg', t('Background Color'), ['class' => 'form-label small']) ?>
                    <div class="input-group input-group-sm">
                        <?= $form->text($view->field('colorBackg'), $colorBackg ?? '#ffffff', ['maxlength' => 7, 'placeholder' => '#ffffff', 'class' => 'form-control tih-color-backg-input']) ?>
                        <input type="color" class="form-control form-control-color tih-color-backg-picker" value="<?= h($colorBackg ?: '#ffffff') ?>" aria-label="<?= t('Background Color') ?>">
                    </div>
                </div>
            </div>
            <p class="help-block small text-muted mb-0 mt-2">
                <?= t('Match the background color to your page so the line breaks cleanly behind the image.') ?>
            </p>
        </fieldset>

        <fieldset class="mb-3">
            <legend class="fs-6"><?= t('Drop Shadow') ?></legend>

            <div class="row g-2">
                <div class="col-md-6">
                    <?= $form->label('dropShadowBlur', t('Shadow Blur'), ['class' => 'form-label small']) ?>
                    <div class="input-group input-group-sm">
                        <?= $form->number($view->field('dropShadowBlur'), $dropShadowBlur ?? 0, ['min' => 0, 'max' => 50, 'step' => 1, 'class' => 'tih-drop-shadow-blur-input']) ?>
                        <span class="input-group-text">px</span>
                    </div>
                    <p class="help-block small text-muted mb-0">
                        <?= t('Set to 0 to disable the shadow.') ?>
                    </p>
                </div>
                <div class="col-md-6">
                    <?= $form->label('dropShadowOpacity', t('Shadow Opacity'), ['class' => 'form-label small']) ?>
                    <div class="input-group input-group-sm">
                        <?= $form->number($view->field('dropShadowOpacity'), $dropShadowOpacity ?? 35, ['min' => 0, 'max' => 100, 'step' => 1, 'class' => 'tih-drop-shadow-opacity-input']) ?>
                        <span class="input-group-text">%</span>
                    </div>
                </div>
            </div>

            <div class="form-group mt-2 mb-0">
                <?= $form->label('dropShadowColor', t('Shadow Color'), ['class' => 'form-label small']) ?>
                <div class="input-group input-group-sm">
                    <?= $form->text($view->field('dropShadowColor'), $dropShadowColor ?? '#333333', ['maxlength' => 7, 'placeholder' => '#333333', 'class' => 'form-control tih-drop-shadow-color-input']) ?>
                    <input type="color" class="form-control form-control-color tih-drop-shadow-color-picker" value="<?= h($dropShadowColor ?: '#333333') ?>" aria-label="<?= t('Shadow Color') ?>">
                </div>
                <p class="help-block small text-muted mb-0 mt-2">
                    <?= t('The shadow clears when visitors hover over the image.') ?>
                </p>
            </div>
        </fieldset>

        <fieldset class="mb-0">
            <legend class="fs-6"><?= t('Line Style') ?></legend>

            <div class="row g-2">
                <div class="col-md-6">
                    <?= $form->label('hrStyle', t('Line Style'), ['class' => 'form-label small']) ?>
                    <?= $form->select($view->field('hrStyle'), $hrStyle_options, $hrStyle ?? '1', ['class' => 'form-select form-select-sm tih-hr-style-select']) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->label('heightLine', t('Line Thickness'), ['class' => 'form-label small']) ?>
                    <div class="input-group input-group-sm">
                        <?= $form->number($view->field('heightLine'), $heightLine ?? 1, ['min' => 0, 'max' => 20, 'step' => 1, 'class' => 'tih-height-line-input']) ?>
                        <span class="input-group-text">px</span>
                    </div>
                    <p class="help-block small text-muted mb-0 tih-double-line-note" style="display:none;">
                        <?= t('Double lines need 3px thickness to appear. Thickness is set to 3px automatically when you choose this style.') ?>
                    </p>
                </div>
            </div>

            <div class="form-group mt-2 mb-2">
                <?= $form->label('colorLine', t('Line Color'), ['class' => 'form-label small']) ?>
                <div class="input-group input-group-sm">
                    <?= $form->text($view->field('colorLine'), $colorLine ?? '#333333', ['maxlength' => 7, 'placeholder' => '#333333', 'class' => 'form-control tih-color-line-input']) ?>
                    <input type="color" class="form-control form-control-color tih-color-line-picker" value="<?= h($colorLine ?: '#333333') ?>" aria-label="<?= t('Line Color') ?>">
                </div>
            </div>

            <div class="row g-2">
                <div class="col-md-6">
                    <?= $form->label('blockMarginTop', t('Margin Top'), ['class' => 'form-label small']) ?>
                    <div class="input-group input-group-sm">
                        <?= $form->number($view->field('blockMarginTop'), $blockMarginTop ?? 80, ['min' => 0, 'step' => 1, 'class' => 'tih-margin-top-input']) ?>
                        <span class="input-group-text">px</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <?= $form->label('blockMarginBottom', t('Margin Bottom'), ['class' => 'form-label small']) ?>
                    <div class="input-group input-group-sm">
                        <?= $form->number($view->field('blockMarginBottom'), $blockMarginBottom ?? 80, ['min' => 0, 'step' => 1, 'class' => 'tih-margin-bottom-input']) ?>
                        <span class="input-group-text">px</span>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>

    <div class="col-lg-5 tih-block-editor-preview">
        <div class="tih-preview-panel border rounded bg-light h-100">
            <div class="tih-preview-panel-header px-3 py-2 border-bottom bg-white rounded-top">
                <strong class="small text-uppercase text-muted"><?= t('Preview') ?></strong>
            </div>
            <div class="tih-preview-panel-body p-3">
                <div class="tih-image-hr-preview">
                    <div class="tih-image-hr-preview-line">
                        <div class="tih-image-hr-preview-image-wrap">
                            <img
                                class="tih-image-hr-preview-image"
                                src="<?= h($previewImageUrl ?? '') ?>"
                                alt=""
                                <?= empty($previewImageUrl) ? ' style="display:none;"' : '' ?>
                            >
                            <span class="tih-image-hr-preview-placeholder small text-muted"<?= !empty($previewImageUrl) ? ' style="display:none;"' : '' ?>>
                                <?= t('Choose an image') ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.tih-block-editor-controls {
    padding-right: 0.25rem;
}

.tih-block-editor-preview {
    position: sticky;
    top: 0;
    align-self: flex-start;
}

.tih-preview-panel {
    min-height: 220px;
}

.tih-preview-panel-body {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 220px;
}

.tih-image-hr-preview {
    width: 100%;
}

.tih-image-hr-preview-line {
    display: flex;
    width: 100%;
    height: 0;
    align-items: center;
    justify-content: center;
}

.tih-image-hr-preview-image-wrap {
    position: relative;
    z-index: 2;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 100%;
    overflow: hidden;
    line-height: 0;
    transition: box-shadow 0.25s ease;
}

.tih-image-hr-preview-image-wrap:hover {
    box-shadow: none !important;
}

.tih-image-hr-preview-image {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 100%;
}

.tih-image-hr-preview-placeholder {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 80px;
    min-height: 80px;
    padding: 1rem;
    text-align: center;
    border-radius: 100%;
    background: #fff;
}

.tih-block-editor fieldset {
    margin-bottom: 0.75rem;
}

.tih-block-editor legend {
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
                handleImageFileSelect: function (file) {
                    document.dispatchEvent(new CustomEvent('tih-image-hr-file-selected', {
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
    var editor = document.querySelector('.tih-block-editor');
    if (!editor) {
        return;
    }

    var positionMap = { '1': 'center', '2': 'flex-start', '3': 'flex-end' };
    var hrStyleMap = { '1': 'solid', '2': 'dashed', '3': 'dotted', '4': 'double' };
    var imageFieldName = <?= json_encode($imageField) ?>;
    var initialPreviewUrl = <?= json_encode($previewImageUrl ?? '') ?>;

    var previewLine = editor.querySelector('.tih-image-hr-preview-line');
    var previewImageWrap = editor.querySelector('.tih-image-hr-preview-image-wrap');
    var previewImage = editor.querySelector('.tih-image-hr-preview-image');
    var previewPlaceholder = editor.querySelector('.tih-image-hr-preview-placeholder');
    var positionSelect = editor.querySelector('.tih-position-select');
    var sizeImageInput = editor.querySelector('.tih-size-image-input');
    var paddingImageInput = editor.querySelector('.tih-padding-image-input');
    var borderImageInput = editor.querySelector('.tih-border-image-input');
    var borderColorInput = editor.querySelector('.tih-border-color-input');
    var colorBackgInput = editor.querySelector('.tih-color-backg-input');
    var hrStyleSelect = editor.querySelector('.tih-hr-style-select');
    var heightLineInput = editor.querySelector('.tih-height-line-input');
    var colorLineInput = editor.querySelector('.tih-color-line-input');
    var marginTopInput = editor.querySelector('.tih-margin-top-input');
    var marginBottomInput = editor.querySelector('.tih-margin-bottom-input');
    var dropShadowBlurInput = editor.querySelector('.tih-drop-shadow-blur-input');
    var dropShadowOpacityInput = editor.querySelector('.tih-drop-shadow-opacity-input');
    var dropShadowColorInput = editor.querySelector('.tih-drop-shadow-color-input');
    var doubleLineNote = editor.querySelector('.tih-double-line-note');
    var imageInput = editor.querySelector('[name="' + imageFieldName + '"]');
    var lastLoadedFileId = null;

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

    function defaultMarginForSize(size) {
        return Math.round(size / 2) + 25;
    }

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

    function setPreviewImageUrl(url) {
        if (!previewImage) {
            return;
        }

        if (url) {
            previewImage.src = url;
            previewImage.style.display = '';
            if (previewPlaceholder) {
                previewPlaceholder.style.display = 'none';
            }
        } else {
            previewImage.removeAttribute('src');
            previewImage.style.display = 'none';
            if (previewPlaceholder) {
                previewPlaceholder.style.display = '';
            }
        }
    }

    function loadPreviewFromFileId(fileId) {
        fileId = parseInt(fileId, 10);

        if (!fileId) {
            lastLoadedFileId = null;
            setPreviewImageUrl('');
            updatePreview();
            return;
        }

        if (fileId === lastLoadedFileId && previewImage && previewImage.src) {
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
                setPreviewImageUrl('');
                updatePreview();
                return;
            }

            lastLoadedFileId = fileId;
            setPreviewImageUrl(getFilePreviewUrl(response.files[0]));
            updatePreview();
        });
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

    function updatePreview() {
        if (!previewLine || !previewImageWrap) {
            return;
        }

        var sizeImage = getSpacingValue(sizeImageInput, 110);
        var paddingImage = getSpacingValue(paddingImageInput, 0);
        var borderImage = getSpacingValue(borderImageInput, 0);
        var borderColor = borderColorInput && borderColorInput.value ? borderColorInput.value : '#333333';
        var backgColor = colorBackgInput && colorBackgInput.value ? colorBackgInput.value : '#ffffff';
        var lineColor = colorLineInput && colorLineInput.value ? colorLineInput.value : '#333333';
        var position = positionSelect ? positionMap[positionSelect.value] || 'center' : 'center';
        var hrStyle = hrStyleSelect ? hrStyleMap[hrStyleSelect.value] || 'solid' : 'solid';
        var heightLine = getSpacingValue(heightLineInput, 1);
        if (hrStyleSelect && hrStyleSelect.value === '4') {
            heightLine = Math.max(3, heightLine);
        }
        var marginTop = marginTopInput && marginTopInput.value !== '' ? getSpacingValue(marginTopInput, defaultMarginForSize(sizeImage)) : defaultMarginForSize(sizeImage);
        var marginBottom = marginBottomInput && marginBottomInput.value !== '' ? getSpacingValue(marginBottomInput, defaultMarginForSize(sizeImage)) : defaultMarginForSize(sizeImage);

        previewLine.style.justifyContent = position;
        previewLine.style.borderBottom = heightLine + 'px ' + hrStyle + ' ' + lineColor;
        previewLine.style.margin = marginTop + 'px 0 ' + marginBottom + 'px';

        previewImageWrap.style.width = sizeImage + 'px';
        previewImageWrap.style.height = sizeImage + 'px';
        previewImageWrap.style.padding = paddingImage + 'px';
        previewImageWrap.style.backgroundColor = backgColor;
        previewImageWrap.style.border = borderImage + 'px solid ' + borderColor;
        previewImageWrap.style.boxSizing = 'border-box';
        previewImageWrap.style.boxShadow = getDropShadowStyle();

        if (previewPlaceholder) {
            previewPlaceholder.style.backgroundColor = backgColor;
            previewPlaceholder.style.border = borderImage + 'px solid ' + borderColor;
            previewPlaceholder.style.width = sizeImage + 'px';
            previewPlaceholder.style.height = sizeImage + 'px';
            previewPlaceholder.style.boxSizing = 'border-box';
        }

        applyDoubleLineStyleRules(false);
    }

    if (hrStyleSelect) {
        hrStyleSelect.addEventListener('change', function () {
            applyDoubleLineStyleRules(hrStyleSelect.value === '4');
        });
    }

    editor.querySelectorAll('.input-group').forEach(function (group) {
        var textInput = group.querySelector('.tih-border-color-input, .tih-color-backg-input, .tih-color-line-input, .tih-drop-shadow-color-input');
        var pickerInput = group.querySelector('.tih-border-color-picker, .tih-color-backg-picker, .tih-color-line-picker, .tih-drop-shadow-color-picker');
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
        sizeImageInput,
        paddingImageInput,
        borderImageInput,
        borderColorInput,
        colorBackgInput,
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

    if (imageInput) {
        imageInput.addEventListener('change', function () {
            loadPreviewFromFileId(imageInput.value);
        });
        imageInput.addEventListener('input', function () {
            loadPreviewFromFileId(imageInput.value);
        });
    }

    document.addEventListener('tih-image-hr-file-selected', function (event) {
        var file = event.detail ? event.detail.file : null;

        if (!file) {
            lastLoadedFileId = null;
            setPreviewImageUrl('');
            updatePreview();
            return;
        }

        if (file.fID) {
            lastLoadedFileId = parseInt(file.fID, 10);
        }

        setPreviewImageUrl(getFilePreviewUrl(file));
        updatePreview();
    });

    var fileInputContainer = editor.querySelector('.tih-image-picker');
    if (fileInputContainer && typeof MutationObserver !== 'undefined') {
        var fileObserver = new MutationObserver(function () {
            var input = editor.querySelector('input[name="' + imageFieldName + '"]');
            if (input && input !== imageInput) {
                imageInput = input;
                imageInput.addEventListener('change', function () {
                    loadPreviewFromFileId(imageInput.value);
                });
                imageInput.addEventListener('input', function () {
                    loadPreviewFromFileId(imageInput.value);
                });
            }

            if (input && input.value) {
                loadPreviewFromFileId(input.value);
            }
        });
        fileObserver.observe(fileInputContainer, { childList: true, subtree: true, attributes: true });
    }

    if (initialPreviewUrl) {
        setPreviewImageUrl(initialPreviewUrl);
    } else if (imageInput && imageInput.value) {
        loadPreviewFromFileId(imageInput.value);
    }

    applyDoubleLineStyleRules(false);
    updatePreview();
})();
</script>
