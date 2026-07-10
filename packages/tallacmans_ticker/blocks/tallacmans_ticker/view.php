<?php

declare(strict_types=1);

defined('C5_EXECUTE') or die('Access Denied.');

$blockScopeId = 'tallacmans-ticker-' . (int) $bID;

$rootClasses = ['tallacmans-ticker'];
if (($scrollDirection ?? 'ltr') === 'rtl') {
    $rootClasses[] = 'ttk-direction-rtl';
}

$wrapClasses = ['ttk-wrap'];
if (!empty($pauseOnHover)) {
    $wrapClasses[] = 'ttk-pause-hover';
}

$itemStyle = sprintf(
    'color: %s; font-size: %s; font-weight: %s; padding-left: %s; padding-right: %s;',
    h($colorText ?? 'rgb(255,255,255)'),
    h($fontSizeCss ?? '16px'),
    h($fontWeight ?? 'inherit'),
    h($itemPaddingCss ?? '32px'),
    h($itemPaddingCss ?? '32px')
);

if (!empty($textShadowCss)) {
    $itemStyle .= ' text-shadow: ' . h($textShadowCss) . ';';
}
?>

<div class="<?= h(implode(' ', $rootClasses)) ?>" id="<?= h($blockScopeId) ?>">
    <?php if (!empty($items)) { ?>
        <div class="<?= h(implode(' ', $wrapClasses)) ?>" style="background-color: <?= h($colorBack ?? 'rgb(0,0,0)') ?>">
            <div class="ttk-track" style="animation-duration: <?= h((string) ($speed ?? 30)) ?>s;">
                <?php for ($groupIndex = 0; $groupIndex < 2; $groupIndex++) { ?>
                    <div
                        class="ttk-group<?= $groupIndex > 0 ? ' ttk-group-duplicate' : '' ?>"
                        <?= $groupIndex > 0 ? 'aria-hidden="true"' : '' ?>
                    >
                        <?php foreach ($items as $item) { ?>
                            <div class="ttk-item" style="<?= $itemStyle ?>">
                                <?= h($item) ?>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } elseif (!empty($isEditMode)) { ?>
        <div class="ttk-empty">
            <?= t('Your ticker contains no items to display.') ?>
        </div>
    <?php } ?>
</div>
