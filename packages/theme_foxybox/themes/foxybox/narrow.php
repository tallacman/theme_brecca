<?php          defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('elements/header.php');
//$this->inc('elements/header_theme.php'); ?>



	<main class="container full-width gutters justify-center">
		<div class="col-10 col-sm-8 col-md-5">
			<div class="container align-items-center">
				<div class="col-12 area lead">
                    <?php
                    $a = new Area('Lead');
					$a->setCustomTemplate('content', 'foxy_content.php');
                    $a->display($c);
                    ?>
				</div>
			</div>
			<div class="container align-items-center gutters-all">
				<div class="col-12 area">
					<?php
					$a = new Area('Main');
					$a->display($c);
					?>
				</div>
			</div>

			<div class="container align-items-center gutters">
				<div class="col-12 col-md-6 area">
					<?php
					$a = new Area('Column One');
					$a->display($c);
					?>
				</div>
				<div class="col-12 col-md-6 area">
					<?php
					$a = new Area('Column Two');
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
