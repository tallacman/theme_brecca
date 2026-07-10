<?php

declare(strict_types=1);

defined('C5_EXECUTE') or die('Access Denied.');

$app = \Concrete\Core\Support\Facade\Application::getFacadeApplication();
$blockScopeId = 'tallacmans-image-hr-' . (int) $bID;
$image_thumb = null;
$thumbSize = (int) ($sizeImage ?? 110);

if ($image && $thumbSize > 0) {
    $image_thumb = $app->make('helper/image')->getThumbnail($image, $thumbSize, $thumbSize, true);
}

$lineStyle = [
    'display:flex',
    'width:100%',
    'height:0',
    'border-bottom:' . (int) ($heightLine ?? 1) . 'px ' . ($hrStyleCss ?? 'solid') . ' ' . ($colorLine ?? '#333333'),
    'margin:' . (int) ($blockMarginTop ?? 80) . 'px 0 ' . (int) ($blockMarginBottom ?? 80) . 'px',
    'align-items:center',
    'justify-content:' . ($positionCss ?? 'center'),
];

$imageWrapStyle = [
    'align-self:center',
    'position:relative',
    'z-index:2',
    'width:' . $thumbSize . 'px',
    'height:' . $thumbSize . 'px',
    'box-sizing:border-box',
    'padding:' . (int) ($paddingImage ?? 0) . 'px',
    'border-radius:100%',
    'border:' . (int) ($borderImage ?? 0) . 'px solid ' . ($borderColor ?? '#333333'),
    'background-color:' . ($colorBackg ?? '#ffffff'),
    'overflow:hidden',
];

if (!empty($dropShadowValue)) {
    $imageWrapStyle[] = 'box-shadow:' . $dropShadowValue;
    $imageWrapStyle[] = 'transition:box-shadow 0.25s ease';
}

$imageStyle = [
    'display:block',
    'width:100%',
    'height:100%',
    'object-fit:cover',
    'border-radius:100%',
];

$lineStyleAttr = ' style="' . h(implode(';', $lineStyle)) . '"';
$imageWrapStyleAttr = ' style="' . h(implode(';', $imageWrapStyle)) . '"';
$imageStyleAttr = ' style="' . h(implode(';', $imageStyle)) . '"';
?>

<div class="tallacmans-image-hr-wrapper" id="<?= h($blockScopeId) ?>">
    <?php if (!empty($hasDropShadow)) { ?>
        <style>
            #<?= h($blockScopeId) ?> .tallacmans-image-hr-image:hover {
                box-shadow: none !important;
            }
        </style>
    <?php } ?>
    <div class="tallacmans-image-hr ccm-tallacmans-image-hr"<?= $lineStyleAttr ?>>
        <?php if ($image && $image_thumb) { ?>
            <div class="tallacmans-image-hr-image"<?= $imageWrapStyleAttr ?>>
                <img
                    src="<?= h($image_thumb->src) ?>"
                    alt="<?= h((string) $image->getTitle()) ?>"
                    class="tallacmans-image-hr-img"
                    <?= $imageStyleAttr ?>
                >
            </div>
        <?php } ?>
    </div>
</div>
