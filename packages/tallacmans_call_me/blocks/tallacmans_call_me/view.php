<?php

declare(strict_types=1);

defined('C5_EXECUTE') or die('Access Denied.');

if (empty($contactHref) || empty($displayText)) {
    return;
}

$blockScopeId = 'tallacmans-call-me-' . (int) $bID;
$linkClasses = ['tcm-call-me-link'];

if (!empty($isButton)) {
    $linkClasses[] = 'tcm-call-me-button';
}

$linkClassAttr = h(implode(' ', $linkClasses));
?>

<div class="tallacmans-call-me" id="<?= h($blockScopeId) ?>">
    <?php if (!empty($isButton)) { ?>
        <a class="<?= $linkClassAttr ?>" href="<?= h($contactHref) ?>">
            <?= h($displayText) ?>
        </a>
    <?php } else { ?>
        <a class="<?= $linkClassAttr ?>" href="<?= h($contactHref) ?>">
            <<?= h($headingTag ?? 'span') ?> class="tcm-call-me-text">
                <?= h($displayText) ?>
            </<?= h($headingTag ?? 'span') ?>>
        </a>
    <?php } ?>
</div>
