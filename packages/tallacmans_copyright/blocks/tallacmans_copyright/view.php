<?php

declare(strict_types=1);

defined('C5_EXECUTE') or die('Access Denied.');

$thisYear = (string) date('Y');
$blockScopeId = 'tallacmans-copyright-' . (int) $bID;
$startYear = trim((string) ($copyrightStart ?? ''));
$holder = trim((string) ($copyrightHolder ?? ''));

$wrapperStyle = [
    'padding-top:' . (int) ($blockPaddingTop ?? 0) . 'px',
    'padding-bottom:' . (int) ($blockPaddingBottom ?? 0) . 'px',
];
$textStyle = [
    'font-size:' . (int) ($fontSize ?? 14) . 'px',
    'font-weight:' . (int) ($fontWeight ?? 400),
];

if (!empty($fontFamily)) {
    $textStyle[] = 'font-family:' . $fontFamily;
}

if (!empty($textColor)) {
    $textStyle[] = 'color:' . $textColor;
}

$wrapperStyleAttr = ' style="' . h(implode(';', $wrapperStyle)) . '"';
$textStyleAttr = ' style="' . h(implode(';', $textStyle)) . '"';
?>

<style>
#<?= h($blockScopeId) ?> .tallacmans-copyright {
    display: flex;
    flex-wrap: wrap;
    align-items: baseline;
    gap: 0.35em;
    line-height: 1.6;
}
</style>

<div class="tallacmans-copyright-wrapper" id="<?= h($blockScopeId) ?>"<?= $wrapperStyleAttr ?>>
    <div class="tallacmans-copyright ccm-tallacmans-copyright"<?= $textStyleAttr ?>>
        <span class="ccm-tallacmans-copyright-symbol">&copy;</span>
        <span class="ccm-tallacmans-copyright-years">
            <?php
            if ($startYear !== '' && $startYear !== $thisYear) {
                echo h($startYear) . ' &ndash; ' . h($thisYear);
            } else {
                echo h($thisYear);
            }
            ?>
        </span>
        <?php if ($holder !== '') { ?>
            <span class="ccm-tallacmans-copyright-holder"><?= h($holder) ?></span>
        <?php } ?>
        <span class="ccm-tallacmans-copyright-rights">&ndash; <?= t('All rights reserved.') ?></span>
    </div>
</div>
