<?php          defined('C5_EXECUTE') or die("Access Denied.");
 ?>

<!doctype html>
<html lang="<?php   echo Localization::activeLanguage()?>">
<head>
  <?php
  View::element('header_required', [
      'pageTitle' => isset($pageTitle) ? $pageTitle : '',
      'pageDescription' => isset($pageDescription) ? $pageDescription : '',
      'pageMetaKeywords' => isset($pageMetaKeywords) ? $pageMetaKeywords : ''
  ]);
  ?>
  <meta name="viewport" content="width=device-width, initial-scale=1">
<?php   echo $html->css($view->getStylesheet('main.less'))?>

</head>

<body>
<div class="<?php         echo $c->getPageWrapperClass()?>">
    <header class="container full-width gutters items-center">
        <div class="col-12">
            <div class="container gutters justify-center items-center">
                <div class="col-auto sitename">
                    <?php
                  	$a = new GlobalArea('Header Site Title');
                  	$a->display();
                    ?>
            		</div>
            		<nav class="col-auto">
                    <?php
                    $a = new GlobalArea('Header Navigation');
                    $a->display();
                    ?>
            		</nav>
              </div>
          </div>
        <div class="slicknav-menu"></div>

	</header>
