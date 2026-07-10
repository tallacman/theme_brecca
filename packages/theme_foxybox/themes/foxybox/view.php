<?php          defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('elements/header.php');
//$this->inc('elements/header_theme.php'); ?>



	<main class="container full-width gutters justify-center">
		<div class="col-7">
			<div class="container align-items-center">
				<div class="col-12 area">
					<?php   View::element('system_errors', array('format' => 'block', 'error' => isset($error) ? $error : null, 'success' => isset($success) ? $success : null, 'message' => isset($message) ? $message : null));
					print $innerContent; ?>
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
