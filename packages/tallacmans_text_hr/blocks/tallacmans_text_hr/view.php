<?php

declare(strict_types=1);

defined('C5_EXECUTE') or die('Access Denied.');

$blockScopeId = 'tallacmans-text-hr-' . (int) $bID;
$displayText = trim((string) ($textDisplay ?? ''));
$position = (string) ($postitionText ?? '1');
$lineBorder = (int) ($heightLine ?? 1) . 'px ' . ($hrStyleCss ?? 'solid') . ' ' . ($colorLine ?? '#333333');

$containerStyle = [
    'display:flex',
    'width:100%',
    'align-items:center',
    'margin:' . (int) ($blockMarginTop ?? 33) . 'px 0 ' . (int) ($blockMarginBottom ?? 33) . 'px',
];

$segmentStyle = [
    'flex:1 1 auto',
    'height:0',
    'align-self:center',
    'border-bottom:' . $lineBorder,
];

$labelStyle = [
    'flex:0 0 auto',
    'position:relative',
    'z-index:2',
    'background-color:' . ($colorBackg ?? '#ffffff'),
    'border:' . (int) ($textBorder ?? 0) . 'px solid ' . ($colorLine ?? '#333333'),
    'border-radius:' . ($textBorderShapeCss ?? '0'),
    'padding:5px 15px',
];

if (!empty($dropShadowValue)) {
    $labelStyle[] = 'box-shadow:' . $dropShadowValue;
    $labelStyle[] = 'transition:box-shadow 0.25s ease';
}

$textStyle = [
    'padding:0',
    'margin:0',
    'color:' . ($textColor ?? '#333333'),
    'font-size:' . (int) ($fontSize ?? 16) . 'px',
    'letter-spacing:' . ($letterSpacing ?? '0') . 'px',
    'font-weight:' . (int) ($fontWeight ?? 400),
];

if (!empty($fontFamily)) {
    $textStyle[] = 'font-family:' . $fontFamily;
}

if ($displayText === '') {
    $containerStyle[] = 'height:0';
    $containerStyle[] = 'border-bottom:' . $lineBorder;
}

if ($displayText !== '' && $position === '3') {
    $containerStyle[] = 'justify-content:flex-end';
}

$containerStyleAttr = ' style="' . h(implode(';', $containerStyle)) . '"';
$segmentStyleAttr = ' style="' . h(implode(';', $segmentStyle)) . '"';
$labelStyleAttr = ' style="' . h(implode(';', $labelStyle)) . '"';
$textStyleAttr = ' style="' . h(implode(';', $textStyle)) . '"';
$showLineBefore = $displayText !== '' && $position !== '2';
$showLineAfter = $displayText !== '' && $position !== '3';
?>

<div class="tallacmans-text-hr-wrapper" id="<?= h($blockScopeId) ?>">
    <?php if (!empty($hasDropShadow)) { ?>
        <style>
            #<?= h($blockScopeId) ?> .tallacmans-text-hr-label:hover {
                box-shadow: none !important;
            }
        </style>
    <?php } ?>
    <div class="tallacmans-text-hr ccm-tallacmans-text-hr"<?= $containerStyleAttr ?>>
        <?php if ($showLineBefore) { ?>
            <span class="tallacmans-text-hr-line"<?= $segmentStyleAttr ?> aria-hidden="true"></span>
        <?php } ?>
        <?php if ($displayText !== '') { ?>
            <div class="tallacmans-text-hr-label"<?= $labelStyleAttr ?>>
                <p class="tallacmans-text-hr-text"<?= $textStyleAttr ?>><?= h($displayText) ?></p>
            </div>
        <?php } ?>
        <?php if ($showLineAfter) { ?>
            <span class="tallacmans-text-hr-line"<?= $segmentStyleAttr ?> aria-hidden="true"></span>
        <?php } ?>
    </div>
</div>
