<?php
defined('C5_EXECUTE') or die('Access Denied.');

$site = app('site')->getSite();
$siteName = $site ? $site->getSiteName() : '';
$homeUrl = (string) URL::to('/');
?>

<!doctype html>
<html lang="<?=h(Localization::activeLanguage())?>">
<head>
  <?php
  View::element('header_required', [
      'pageTitle' => isset($pageTitle) ? $pageTitle : '',
      'pageDescription' => isset($pageDescription) ? $pageDescription : '',
      'pageMetaKeywords' => isset($pageMetaKeywords) ? $pageMetaKeywords : ''
  ]);
  ?>
  <meta name="viewport" content="width=device-width, initial-scale=1">
<?php       echo $html->css($view->getStylesheet('main.less'))?>

<!--
<link rel="stylesheet" href="<?php  //  echo $this->getThemePath()?>/css/styles.css">
 --> 
</head>

<body>
    <div class="<?php       echo $c->getPageWrapperClass()?>">
        <div id="slicknav" data-brand="<?=h($siteName)?>" data-home-url="<?=h($homeUrl)?>">
<!--  slicknav gets attached here     -->
        </div>

        <header class="container full-width items-center">
            <div class="col-12">
                <div class="container gutters items-center">
                    <div class="col-auto sitename">
                        <?php
                        $a = new GlobalArea('Header Site Title');
                        $a->display();
                        ?>
                    </div>

                    <nav class="col-auto grow-1">
                        <?php
                        $a = new GlobalArea('Header Navigation');
                        $a->setCustomTemplate('autonav', 'slicknav.php');
                        $a->display();
                        ?>
                    </nav>
                </div>
            </div>
        </header>
