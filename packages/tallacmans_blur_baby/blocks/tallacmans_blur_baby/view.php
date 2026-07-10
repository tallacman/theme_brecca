<?php

declare(strict_types=1);

defined('C5_EXECUTE') or die('Access Denied.');

if (empty($imageUrl)) {
    return;
}

$blockScopeId = 'tallacmans-blur-baby-' . (int) $bID;
$rootClasses = array_filter([
    'tallacmans-blur-baby',
    $placementClass ?? 'ttbb-placement-center',
]);
$wrapperStyle = sprintf(
    '--ttbb-blur: %s; --ttbb-scale: %s; --ttbb-bg-height: %s; --ttbb-image-width: %s;',
    h($blurCss ?? '15px'),
    h($scaleCss ?? '1.4'),
    h($backgroundHeightCss ?? '90%'),
    h($imageWidthCss ?? '50vw')
);
?>

<div class="<?= h(implode(' ', $rootClasses)) ?>" id="<?= h($blockScopeId) ?>" style="<?= $wrapperStyle ?>">
    <div class="ttbb-wrapper">
        <div class="ttbb-background" aria-hidden="true">
            <img src="<?= h($imageUrl) ?>" alt="" loading="lazy" decoding="async">
        </div>
        <div class="ttbb-image">
            <img src="<?= h($imageUrl) ?>" alt="<?= h($imageAlt ?? '') ?>" loading="lazy" decoding="async">
        </div>
    </div>
</div>
