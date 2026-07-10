<?php

declare(strict_types=1);

defined('C5_EXECUTE') or die('Access Denied.');

$blockScopeId = 'tallacmans-icon-hr-' . (int) $bID;

$lineStyle = [
    'display:flex',
    'width:100%',
    'height:0',
    'border-bottom:' . (int) ($heightLine ?? 1) . 'px ' . ($hrStyleCss ?? 'solid') . ' ' . ($colorLine ?? '#333333'),
    'margin:' . (int) ($blockMarginTop ?? 33) . 'px 0 ' . (int) ($blockMarginBottom ?? 33) . 'px',
    'align-items:center',
    'justify-content:' . ($positionCss ?? 'center'),
];

$iconWrapStyle = [
    'align-self:center',
    'position:relative',
    'z-index:2',
    'display:inline-flex',
    'align-items:center',
    'justify-content:center',
    'padding:' . (int) ($iconPadding ?? 8) . 'px',
    'line-height:1',
];

if (!empty($dropShadowValue)) {
    $iconWrapStyle[] = 'box-shadow:' . $dropShadowValue;
    $iconWrapStyle[] = 'transition:box-shadow 0.25s ease';
}

$iconStyle = [
    'font-size:' . (int) ($sizeIcon ?? 46) . 'px',
    'color:' . ($colorIcon ?? '#ff05c4'),
];

$lineStyleAttr = ' style="' . h(implode(';', $lineStyle)) . '"';
$iconWrapStyleAttr = ' style="' . h(implode(';', $iconWrapStyle)) . '"';
$iconStyleAttr = ' style="' . h(implode(';', $iconStyle)) . '"';
?>

<div class="tallacmans-icon-hr-wrapper" id="<?= h($blockScopeId) ?>">
    <?php if (!empty($hasDropShadow)) { ?>
        <style>
            #<?= h($blockScopeId) ?> .tallacmans-icon-hr-icon:hover {
                box-shadow: none !important;
            }
        </style>
    <?php } ?>
    <div class="tallacmans-icon-hr ccm-tallacmans-icon-hr"<?= $lineStyleAttr ?>>
        <?php if (!empty($iconTag)) { ?>
            <div class="tallacmans-icon-hr-icon"<?= $iconWrapStyleAttr ?>>
                <span class="tallacmans-icon-hr-icon-inner"<?= $iconStyleAttr ?>><?= $iconTag ?></span>
            </div>
        <?php } ?>
    </div>
</div>
