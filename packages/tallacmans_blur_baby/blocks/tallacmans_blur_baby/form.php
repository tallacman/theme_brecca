<?php

declare(strict_types=1);

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\File\File;

$app = (isset($app) && $app) ? $app : \Concrete\Core\Support\Facade\Application::getFacadeApplication();
$assetLibrary = $app->make('helper/concrete/asset_library');
$imageField = $view->field('image');

$imageFile = $imageFile ?? null;
if (!$imageFile && !empty($image) && (int) $image > 0) {
    $imageFile = File::getByID((int) $image);
    if (!$imageFile || $imageFile->isError()) {
        $imageFile = null;
    }
}

$previewBlur = is_numeric($blur ?? null) ? (float) $blur : 15.0;
$previewScale = is_numeric($scale ?? null) ? (float) $scale : 1.4;
$previewHeight = is_numeric($height ?? null) ? (float) $height : 90.0;
$previewWidth = is_numeric($imageWidth ?? null) ? (float) $imageWidth : 50.0;
if ($previewWidth > 100) {
    $previewWidth = min(100, max(5, round($previewWidth / 14)));
}
$previewPlacement = in_array((string) ($placement ?? 'center'), ['flex-start', 'center', 'flex-end'], true)
    ? (string) $placement
    : 'center';
$previewPlacementClass = [
    'flex-start' => 'ttbb-placement-top',
    'center' => 'ttbb-placement-center',
    'flex-end' => 'ttbb-placement-bottom',
][$previewPlacement] ?? 'ttbb-placement-center';
$previewStyle = sprintf(
    '--ttbb-blur: %spx; --ttbb-scale: %s; --ttbb-bg-height: %s%%; --ttbb-image-width: %svw;',
    $previewBlur,
    $previewScale,
    $previewHeight,
    $previewWidth
);
?>

<div class="ttbb-block-editor">
    <div class="ttbb-preview-panel mb-3 border rounded bg-light">
        <div class="ttbb-preview-panel-header px-3 py-2 border-bottom bg-white rounded-top">
            <strong class="small text-uppercase text-muted"><?= t('Preview') ?></strong>
        </div>
        <div class="ttbb-preview-panel-body p-2">
            <div class="tallacmans-blur-baby ttbb-editor-preview <?= h($previewPlacementClass) ?>" id="ttbb-live-preview" style="<?= h($previewStyle) ?>">
                <div class="ttbb-wrapper">
                    <div class="ttbb-background" aria-hidden="true">
                        <img
                            class="ttbb-preview-bg"
                            src="<?= h($previewImageUrl ?? '') ?>"
                            alt=""
                            <?= empty($previewImageUrl) ? ' style="display:none;"' : '' ?>
                        >
                    </div>
                    <div class="ttbb-image">
                        <img
                            class="ttbb-preview-fg"
                            src="<?= h($previewImageUrl ?? '') ?>"
                            alt=""
                            <?= empty($previewImageUrl) ? ' style="display:none;"' : '' ?>
                        >
                        <span class="ttbb-preview-placeholder small text-muted"<?= !empty($previewImageUrl) ? ' style="display:none;"' : '' ?>>
                            <?= t('Choose an image to preview the blur effect.') ?>
                        </span>
                    </div>
                </div>
            </div>
            <p class="help-block small text-muted mb-0 mt-2">
                <?= t('Preview updates as you edit settings below.') ?>
            </p>
        </div>
    </div>

    <fieldset class="mb-3">
            <legend class="fs-6"><?= t('Image') ?></legend>

            <div class="form-group mb-2">
                <?= $form->label('image', t('Featured Image'), ['class' => 'form-label']) ?>
                <?= $assetLibrary->image(
                    'ccm-b-tallacmans_blur_baby-image-' . ($identifier_getString ?? ''),
                    $imageField,
                    t('Choose Image'),
                    $imageFile
                ) ?>
            </div>

            <div class="form-group mb-0">
                <?= $form->label($view->field('placement'), t('Background Placement'), ['class' => 'form-label']) ?>
                <?= $form->select($view->field('placement'), $placement_options, $placement ?? 'center', [
                    'class' => 'form-select form-select-sm ttbb-placement-select',
                ]) ?>
                <p class="help-block small text-muted mb-0">
                    <?= t('Vertical alignment of the blurred background within the block.') ?>
                </p>
            </div>
        </fieldset>

        <fieldset class="mb-3">
            <legend class="fs-6"><?= t('Foreground Image') ?></legend>

            <div class="form-group mb-0">
                <?= $form->label($view->field('imageWidth'), t('Image Width (vw)'), ['class' => 'form-label']) ?>
                <div class="input-group input-group-sm">
                    <?= $form->number($view->field('imageWidth'), $imageWidth ?? 50, [
                        'min' => 5,
                        'max' => 100,
                        'step' => 1,
                        'class' => 'form-control ttbb-image-width-input',
                        'required' => 'required',
                    ]) ?>
                    <span class="input-group-text"><?= t('vw') ?></span>
                </div>
                <p class="help-block small text-muted mb-0">
                    <?= t('Viewport width — the image scales down on smaller screens (5–100 vw).') ?>
                </p>
            </div>
        </fieldset>

        <fieldset class="mb-0">
            <legend class="fs-6"><?= t('Background Blur') ?></legend>

            <div class="row g-2">
                <div class="col-sm-4">
                    <div class="form-group mb-2">
                        <?= $form->label($view->field('blur'), t('Blur (px)'), ['class' => 'form-label']) ?>
                        <?= $form->number($view->field('blur'), $blur ?? 15, [
                            'min' => 0,
                            'max' => 99,
                            'step' => 1,
                            'class' => 'form-control form-control-sm ttbb-blur-input',
                            'required' => 'required',
                        ]) ?>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group mb-2">
                        <?= $form->label($view->field('scale'), t('Scale'), ['class' => 'form-label']) ?>
                        <?= $form->number($view->field('scale'), $scale ?? 1.4, [
                            'min' => 1,
                            'max' => 5,
                            'step' => 0.01,
                            'class' => 'form-control form-control-sm ttbb-scale-input',
                            'required' => 'required',
                        ]) ?>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group mb-0">
                        <?= $form->label($view->field('height'), t('Background Height (%)'), ['class' => 'form-label']) ?>
                        <?= $form->number($view->field('height'), $height ?? 90, [
                            'min' => 10,
                            'max' => 200,
                            'step' => 1,
                            'class' => 'form-control form-control-sm ttbb-height-input',
                            'required' => 'required',
                        ]) ?>
                    </div>
                </div>
            </div>

            <p class="help-block small text-muted mb-0 mt-2">
                <?= t('Background height is a percentage of the foreground image height. Scale enlarges the blurred photo.') ?>
            </p>
        </fieldset>
</div>

<style>
.ttbb-preview-panel {
    position: sticky;
    top: 0;
    z-index: 5;
    background-color: var(--bs-light, #f8f9fa);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.ttbb-preview-panel-header {
    background-color: var(--bs-body-bg, #fff);
}

.ttbb-preview-panel-body {
    overflow: hidden;
}

.ttbb-editor-preview.tallacmans-blur-baby {
    display: block;
    width: 100%;
}

.ttbb-editor-preview .ttbb-wrapper {
    display: flex;
    justify-content: center;
    position: relative;
    margin-top: 0;
    margin-bottom: 0;
    min-height: 160px;
}

.ttbb-editor-preview .ttbb-background {
    position: absolute;
    left: 0;
    width: 100%;
    overflow: hidden;
    height: var(--ttbb-bg-height, 90%);
    pointer-events: none;
}

.ttbb-editor-preview.ttbb-placement-top .ttbb-background {
    top: 0;
}

.ttbb-editor-preview.ttbb-placement-center .ttbb-background {
    top: 50%;
    transform: translateY(-50%);
}

.ttbb-editor-preview.ttbb-placement-bottom .ttbb-background {
    bottom: 0;
}

.ttbb-editor-preview .ttbb-background img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: blur(var(--ttbb-blur, 15px));
    transform: scale(var(--ttbb-scale, 1.4));
}

.ttbb-editor-preview .ttbb-image {
    position: relative;
    z-index: 1;
    width: min(var(--ttbb-image-width, 50vw), calc(100% - 1.5rem));
    max-width: 100%;
    margin-inline: 0.75rem;
    box-shadow: 2px 5px 14px -4px rgba(0, 0, 0, 0.5);
}

.ttbb-editor-preview .ttbb-image img {
    display: block;
    width: 100%;
    height: auto;
}

.ttbb-editor-preview .ttbb-preview-placeholder {
    display: block;
    padding: 2rem 1rem;
    text-align: center;
    background: rgba(255, 255, 255, 0.85);
}

.ttbb-block-editor legend {
    margin-bottom: 0.5rem;
}
</style>

<script>
(function () {
    var editor = document.querySelector('.ttbb-block-editor');
    if (!editor) {
        return;
    }

    var imageFieldName = <?= json_encode($imageField) ?>;
    var initialPreviewUrl = <?= json_encode($previewImageUrl ?? '') ?>;
    var previewRoot = editor.querySelector('#ttbb-live-preview');
    var previewBg = editor.querySelector('.ttbb-preview-bg');
    var previewFg = editor.querySelector('.ttbb-preview-fg');
    var previewPlaceholder = editor.querySelector('.ttbb-preview-placeholder');
    var placementSelect = editor.querySelector('.ttbb-placement-select');
    var imageWidthInput = editor.querySelector('.ttbb-image-width-input');
    var blurInput = editor.querySelector('.ttbb-blur-input');
    var scaleInput = editor.querySelector('.ttbb-scale-input');
    var heightInput = editor.querySelector('.ttbb-height-input');
    var imageInput = editor.querySelector('[name="' + imageFieldName + '"]');
    var lastLoadedFileId = null;

    function getNumberValue(input, fallback) {
        if (!input) {
            return fallback;
        }

        var value = parseFloat(input.value);

        return isNaN(value) ? fallback : value;
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
        var hasUrl = !!url;

        if (previewBg) {
            if (hasUrl) {
                previewBg.src = url;
                previewBg.style.display = '';
            } else {
                previewBg.removeAttribute('src');
                previewBg.style.display = 'none';
            }
        }

        if (previewFg) {
            if (hasUrl) {
                previewFg.src = url;
                previewFg.style.display = '';
            } else {
                previewFg.removeAttribute('src');
                previewFg.style.display = 'none';
            }
        }

        if (previewPlaceholder) {
            previewPlaceholder.style.display = hasUrl ? 'none' : '';
        }
    }

    var placementClassMap = {
        'flex-start': 'ttbb-placement-top',
        'center': 'ttbb-placement-center',
        'flex-end': 'ttbb-placement-bottom'
    };

    function updatePreviewStyles() {
        if (!previewRoot) {
            return;
        }

        previewRoot.style.setProperty('--ttbb-blur', getNumberValue(blurInput, 15) + 'px');
        previewRoot.style.setProperty('--ttbb-scale', String(getNumberValue(scaleInput, 1.4)));
        previewRoot.style.setProperty('--ttbb-bg-height', getNumberValue(heightInput, 90) + '%');
        previewRoot.style.setProperty('--ttbb-image-width', getNumberValue(imageWidthInput, 50) + 'vw');

        var placement = placementSelect ? placementSelect.value || 'center' : 'center';
        previewRoot.classList.remove('ttbb-placement-top', 'ttbb-placement-center', 'ttbb-placement-bottom');
        previewRoot.classList.add(placementClassMap[placement] || 'ttbb-placement-center');
    }

    function loadPreviewFromFileId(fileId) {
        fileId = parseInt(fileId, 10);

        if (!fileId) {
            lastLoadedFileId = null;
            setPreviewImageUrl('');
            updatePreviewStyles();

            return;
        }

        if (fileId === lastLoadedFileId && previewFg && previewFg.src) {
            updatePreviewStyles();

            return;
        }

        if (!window.jQuery || !jQuery.concrete || typeof jQuery.concrete.filemanager !== 'object') {
            updatePreviewStyles();

            return;
        }

        jQuery.concrete.filemanager.getFileDetails([fileId], function (response) {
            if (!response || !response.files || !response.files.length) {
                setPreviewImageUrl('');
                updatePreviewStyles();

                return;
            }

            lastLoadedFileId = fileId;
            setPreviewImageUrl(getFilePreviewUrl(response.files[0]));
            updatePreviewStyles();
        });
    }

    function updatePreview() {
        updatePreviewStyles();
    }

    [
        placementSelect,
        imageWidthInput,
        blurInput,
        scaleInput,
        heightInput,
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

    document.addEventListener('click', function (event) {
        var target = event.target;
        if (!target || !target.closest) {
            return;
        }

        if (target.closest('[data-concrete-file-input="' + imageFieldName + '"] .ccm-file-selector-clear-button')) {
            lastLoadedFileId = null;
            setPreviewImageUrl('');
            updatePreviewStyles();
        }
    });

    if (initialPreviewUrl) {
        setPreviewImageUrl(initialPreviewUrl);
    } else if (imageInput && imageInput.value) {
        loadPreviewFromFileId(imageInput.value);
    }

    updatePreview();
})();
</script>
