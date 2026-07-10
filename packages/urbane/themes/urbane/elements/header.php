<?php defined('C5_EXECUTE') or die("Access Denied."); ?>
<!DOCTYPE html>
<html lang="<?php echo Localization::activeLanguage() ?>">
<head>
    <?=$view->getThemeStyles()?>
    <?php
    View::element('header_required', [
        'pageTitle' => isset($pageTitle) ? $pageTitle : '',
        'pageDescription' => isset($pageDescription) ? $pageDescription : '',
        'pageMetaKeywords' => isset($pageMetaKeywords) ? $pageMetaKeywords : '',
    ]);
    ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Urbane theme by tallacman :: concretecms.org -->
</head>
<body>

<div class="theme-urbane <?php echo $c->getPageWrapperClass() ?>">
	<header>
		<div class="container sitename pt-4 pb-4 pt-md-5 mt-md-3">
			<div class="row justify-content-center justify-content-md-end">
				<div class="col-auto">
					<?php
					$a = new GlobalArea('Header Site Title');
					$a->display();
					?>
				</div>
			</div>
		</div>
		<div id="slicknav"></div>
		<div class="nav-wrapper">
			<div class="container">
				<div class="row justify-content-center justify-content-md-start">
					<nav class="col-auto header-nav slicknav-hide">
						<?php
						$a = new GlobalArea('Header Navigation');
						$a->display();
						?>
					</nav>
				</div>
			</div>
		</div>
	</header>

<div class="container pt-4 pt-sm-4 pt-md-5">
	<div class="row">
		<div class="col-12 page-title-area">
			<h1><?php echo $c->getCollectionName() ?></h1>
		</div>
	</div>
</div>
