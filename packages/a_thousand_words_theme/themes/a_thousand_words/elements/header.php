<?php

declare(strict_types=1);
defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Area\Area;
use Concrete\Core\Area\GlobalArea;
use Concrete\Core\File\File;
use Concrete\Core\Localization\Localization;
use Concrete\Core\Page\Page;
use Concrete\Core\Support\Facade\Url;
use Concrete\Core\View\View;

$siteName = app('site')->getSite()->getSiteName();

$pageHasBackgroundImage = static function (Page $page): bool {
    $pageBackgroundArea = new Area('Background Image');

    if ($pageBackgroundArea->getTotalBlocksInArea($page) < 1) {
        return false;
    }

    foreach ($pageBackgroundArea->getAreaBlocksArray($page) as $block) {
        if ($block->getBlockTypeHandle() !== 'tallacmans_background_image') {
            continue;
        }

        $controller = $block->getController();
        $file = File::getByID((int) ($controller->fID ?? 0));
        if ($file && !$file->isError()) {
            return true;
        }
    }

    return false;
};
?>
<!doctype html>
<html lang="<?= Localization::activeLanguage() ?>">
<head>
    <?= $view->getThemeStyles() ?>
    <?php View::element('header_required') ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= $view->getThemePath() ?>/css/a-thousand-words.css?v=2.4.4">
</head>
<body>
    <div class="theme-a-thousand-words <?= $c->getPageWrapperClass() ?>">
        <header class="atw-header">
            <div class="container">
                <div class="atw-brand">
                    <?php
                    $siteTitleArea = new GlobalArea('Header Site Title');
                    if ($siteTitleArea->getTotalBlocksInArea() > 0 || $c->isEditMode()) {
                        $siteTitleArea->display();
                    } else {
                        ?>
                        <a href="<?= h((string) Url::to('/')) ?>"><?= h($siteName) ?></a>
                        <?php
                    }
                    ?>
                </div>
                <div class="atw-header-nav">
                    <?php
                    $headerNavigation = new GlobalArea('Header Navigation');
                    $headerNavigation->setCustomTemplate('autonav', 'responsive_header_navigation');
                    $headerNavigation->display();
                    ?>
                </div>
            </div>
        </header>
        <div class="atw-background-areas<?= $c->isEditMode() ? ' atw-background-areas-edit' : '' ?>">
            <div class="container">
                <?php
                $pageBackgroundArea = new Area('Background Image');
                $pageBackgroundArea->display($c);

                if ($c->isEditMode() || !$pageHasBackgroundImage($c)) {
                    $globalBackgroundArea = new GlobalArea('Global Background Image');
                    $globalBackgroundArea->display();
                }
                ?>
            </div>
        </div>
