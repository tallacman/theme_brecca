<?php      defined('C5_EXECUTE') or die("Access Denied."); ?>

<!doctype html>
<html lang="<?php     echo Localization::activeLanguage()?>">
<head>
  <?php
  View::element('header_required', [
      'pageTitle' => isset($pageTitle) ? $pageTitle : '',
      'pageDescription' => isset($pageDescription) ? $pageDescription : '',
      'pageMetaKeywords' => isset($pageMetaKeywords) ? $pageMetaKeywords : ''
  ]);
  ?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/modern-normalize/0.6.0/modern-normalize.min.css">
<?php     echo $html->css($view->getStylesheet('main.less'))?>


<!--
<link rel="stylesheet" href="<?php // echo $this->getThemePath()?>/css/styles.css" />
 -->
</head>

<body>
    <div class="<?php     echo $c->getPageWrapperClass()?>">
      <div id="slicknav">
<!--  slicknav gets attached here     -->

    </div>

      <?php
         $a = new GlobalArea('Header Navigation');
            if (($a->getTotalBlocksInArea($c) > 0) || ($c->isEditMode())) {
              echo '<header class="container top">';
                echo '<div class="col-12 margin-top">';
                  $a->setCustomTemplate('autonav', 'brecca_nav.php');
                  $a->display();
                echo "</div>";
              echo "</header>";

      } ?>


        <header class="container gutters-all justify-center">
            <div class="col-12 site-name">
                <h1 id="fittext"><a href="<?php echo DIR_REL?>/"><?php     echo Config::get('concrete.site'); ?></a></h1>
            </div>
        </header>
