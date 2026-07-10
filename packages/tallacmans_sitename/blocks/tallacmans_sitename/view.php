<?php
defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Support\Facade\Url;

$siteName = trim((string) ($displaySitename ?? ''));
$hasLogo = !empty($logoIcon);
$alignmentClass = $siteName !== '' && $hasLogo ? ' align-' . $alignment : '';
$homeUrl = (string) Url::to('/');
$logoAlt = $siteName !== '' ? $siteName : ($hasLogo ? $logoIcon->getTitle() : '');
$textStyles = [];
if (!empty($textColor)) {
    $textStyles[] = 'color: ' . $textColor;
}
if (!empty($fontFamilyCss)) {
    $textStyles[] = 'font-family: ' . $fontFamilyCss;
}
if (!empty($fontSize)) {
    $textStyles[] = 'font-size: ' . (int) $fontSize . 'px';
}
$textStyleAttribute = $textStyles ? ' style="' . h(implode('; ', $textStyles)) . '"' : '';
?>
<div class="tallacmans-sitename-wrapper">
    <div class="tallacmans-sitename<?= h($alignmentClass) ?>">
        <?php if ($siteName !== '') { ?>
            <div class="tallacmans-sitename-text">
                <a href="<?= h($homeUrl) ?>"<?= $textStyleAttribute ?>><?= h($siteName) ?></a>
            </div>
        <?php } ?>

        <?php if ($hasLogo) { ?>
            <div class="tallacmans-sitename-icon">
                <a href="<?= h($homeUrl) ?>">
                    <img src="<?= h($logoIcon->getURL()) ?>" alt="<?= h($logoAlt) ?>">
                </a>
            </div>
        <?php } ?>
    </div>
</div>
