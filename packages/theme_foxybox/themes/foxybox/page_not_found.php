<?php          defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('elements/header.php');
//$this->inc('elements/header_theme.php'); ?>



	<main class="container full-width gutters justify-center">
		<div class="col-10 col-sm-8 col-md-5">
			<div class="container align-items-center">
				<div class="col-12 area lead">
					<h1 class="fourohfour"> 404 </h1>
				</div>
			</div>
			<div class="container align-items-center gutters">
				<div class="col-12 area">
                    <?php
                    $a = new Area('Main');
                    $a->display($c);
                    ?>
				</div>
			</div>
		</div>
	</main>


<?php
//$this->inc('elements/footer_theme.php');
$this->inc('elements/footer.php');
?>
