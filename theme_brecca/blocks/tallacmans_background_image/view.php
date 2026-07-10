<?php
defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\File\File;
use Concrete\Core\Page\Page;

$isEditMode = Page::getCurrentPage()->isEditMode();
$file = File::getByID((int) ($fID ?? 0));
if (!$file || $file->isError()) {
    return;
}

$image = json_encode($file->getURL(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES);
$overlay = trim((string) ($bgOverlayColor ?? ''));
$validOverlay = preg_match('/^(?:#[0-9a-f]{3,8}|rgba?\([0-9.%\s,]+\)|hsla?\([0-9.%\s,]+\))$/i', $overlay) === 1;
$backgroundImage = $overlay !== ''
    && $validOverlay
    ? 'linear-gradient(' . $overlay . ', ' . $overlay . '), url(' . $image . ')'
    : 'url(' . $image . ')';
?>
<?php if (!$isEditMode) { ?>
    <style>
        :root {
            --tallacmans-background-image: <?= $backgroundImage ?>;
        }

        /* Place the selected image above the page background but behind content */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: var(--tallacmans-background-image);
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            z-index: 0;
            pointer-events: none;
            will-change: transform;
        }

        /* Ensure the main page content sits above the background layer */
        .ccm-page {
            position: relative;
            z-index: 1;
        }
    </style>
<?php } ?>

<?php if ($isEditMode) { ?>
    <div class="tallacmans-background-image alert alert-light border">
        <?= t('Tallacmans Background Image') ?>
        <?php if ($overlay !== '' && $validOverlay) { ?>
            <span class="badge text-bg-secondary"><?= t('Overlay enabled') ?></span>
        <?php } ?>
    </div>
<?php } else { ?>
    <div class="tallacmans-background-image" aria-hidden="true"></div>
<?php } ?>
