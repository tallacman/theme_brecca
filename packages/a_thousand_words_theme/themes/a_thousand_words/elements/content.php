<?php

declare(strict_types=1);
defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Area\Area;
use Concrete\Core\Area\GlobalArea;
use Concrete\Core\View\View;

$layout = $layout ?? 'center';
$mainAreaName = $mainAreaName ?? 'Main';
$showSidebar = $layout !== 'blank';
$sidebarFirst = $layout === 'left';
$mainWidth = $showSidebar ? 'col-12 col-lg-7' : 'col-12';
$sidebarWidth = 'col-12 col-lg-4';

$sidebar = new Area('Sidebar');
$lead = new Area('Lead');
$main = new Area($mainAreaName);

$hasSidebarBlocks = $sidebar->getTotalBlocksInArea($c) > 0;
$hasSidebar = $showSidebar;

$atwAreaClass = static function (string $baseClass, bool $isEmpty, bool $forceStyled = false): string {
    if ($forceStyled || !$isEmpty) {
        return $baseClass;
    }

    return $baseClass . ' atw-area-empty';
};

$atwCaptureArea = static function (callable $render): array {
    ob_start();
    $render();
    $content = (string) ob_get_clean();

    return [
        'content' => $content,
        'isEmpty' => trim($content) === '',
    ];
};

$mainArea = $atwCaptureArea(static function () use ($c, $main, $errorCode, $errorMessage, $showInnerContent, $innerContent, $error, $success, $message): void {
    if (!empty($errorCode)) {
        echo '<h1 class="error">', h($errorCode), '</h1>';
        echo '<p>', h($errorMessage ?? ''), '</p>';
    }
    if (!empty($showInnerContent)) {
        View::element('system_errors', [
            'format' => 'block',
            'error' => $error ?? null,
            'success' => $success ?? null,
            'message' => $message ?? null,
        ]);
        echo $innerContent ?? '';
    } else {
        $main->enableGridContainer();
        $main->display($c);
    }
});

$leadArea = $atwCaptureArea(static function () use ($c, $lead): void {
    $lead->display($c);
});

$showLead = $layout !== 'blank' && (!$leadArea['isEmpty'] || $c->isEditMode());
?>
<main class="atw-main container py-5">
    <div class="row g-4 justify-content-center">
        <?php if ($hasSidebar && $sidebarFirst) { ?>
            <aside class="<?= $sidebarWidth ?> atw-sidebar">
                <div class="<?= $atwAreaClass('atw-card', !$hasSidebarBlocks) ?> atw-area-sidebar"><?= $sidebar->display($c) ?></div>
            </aside>
        <?php } ?>

        <div class="<?= $mainWidth ?><?= $layout === 'right' ? ' order-lg-first' : '' ?>">
            <?php
            if ($showLead) { ?>
                <section class="<?= $atwAreaClass('atw-lead mb-4', $leadArea['isEmpty']) ?> atw-area-lead">
                    <?= $leadArea['content'] ?>
                </section>
            <?php } ?>

            <section class="<?= $atwAreaClass('atw-card atw-content', $mainArea['isEmpty'], !empty($errorCode)) ?> atw-area-main">
                <?= $mainArea['content'] ?>
            </section>
        </div>

        <?php if ($hasSidebar && !$sidebarFirst) { ?>
            <aside class="<?= $sidebarWidth ?> atw-sidebar">
                <div class="<?= $atwAreaClass('atw-card', !$hasSidebarBlocks) ?> atw-area-sidebar"><?= $sidebar->display($c) ?></div>
            </aside>
        <?php } ?>
    </div>
</main>
<?php
$copyright = new GlobalArea('Copyright');
?>
<div class="atw-copyright">
    <div class="container">
        <?= $copyright->display() ?>
    </div>
</div>
