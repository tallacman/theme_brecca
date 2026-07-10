<?php

declare(strict_types=1);

defined('C5_EXECUTE') or die('Access Denied.');

if (empty($link_URL)) {
    return;
}

$blockScopeId = 'tbb-button-' . (int) $bID;
$styleMode = (string) ($styleMode ?? 'theme');
$wrapperStyle = [
    'padding-top:' . (int) ($spacingTop ?? 0) . 'px',
    'padding-right:' . (int) ($spacingRight ?? 0) . 'px',
    'padding-bottom:' . (int) ($spacingBottom ?? 8) . 'px',
    'padding-left:' . (int) ($spacingLeft ?? 0) . 'px',
];
$wrapperStyleAttr = ' style="' . h(implode(';', $wrapperStyle)) . '"';
$openInTarget = (string) ($openInTarget ?? '_self');
$buttonClasses = ['tbb-button'];
$buttonBorderColor = !empty($buttonBorderColor) ? (string) $buttonBorderColor : 'transparent';
$buttonHoverBorderColor = !empty($buttonHoverBorderColor) ? (string) $buttonHoverBorderColor : 'transparent';
$buttonBorderWidth = (int) ($buttonBorderWidth ?? 0);
$buttonHoverBorderWidth = (int) ($buttonHoverBorderWidth ?? 0);

if ($styleMode === 'custom') {
    $buttonClasses[] = 'tbb-button-custom';
} else {
    $buttonClasses[] = 'btn';
    $buttonClasses[] = 'btn-primary';
}
?>

<div class="tbb-button-wrapper" id="<?= h($blockScopeId) ?>"<?= $wrapperStyleAttr ?>>
    <?php if ($styleMode === 'custom') { ?>
        <style>
            #<?= h($blockScopeId) ?> .tbb-button-custom {
                background-color: <?= h($buttonBackgroundColor) ?>;
                color: <?= h($buttonTextColor) ?>;
                border-color: <?= h($buttonBorderColor) ?>;
                border-style: solid;
                border-width: <?= $buttonBorderWidth ?>px;
                <?php if (!empty($fontFamily)) { ?>
                font-family: <?= h($fontFamily) ?>;
                <?php } ?>
                font-weight: <?= h($fontWeight) ?>;
                font-size: <?= (int) ($fontSize ?? 16) ?>px;
            }

            #<?= h($blockScopeId) ?> .tbb-button-custom:hover,
            #<?= h($blockScopeId) ?> .tbb-button-custom:focus {
                background-color: <?= h($buttonHoverBackgroundColor) ?>;
                color: <?= h($buttonHoverTextColor) ?>;
                border-color: <?= h($buttonHoverBorderColor) ?>;
                border-width: <?= $buttonHoverBorderWidth ?>px;
            }
        </style>
    <?php } ?>
    <a href="<?= h($link_URL) ?>"
       class="<?= h(implode(' ', $buttonClasses)) ?>"
       target="<?= h($openInTarget) ?>"<?= $openInTarget === '_blank' ? ' rel="noopener noreferrer"' : '' ?>>
        <?= h($link_Title) ?>
    </a>
</div>
