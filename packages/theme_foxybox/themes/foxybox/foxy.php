<?php          defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('elements/header.php');
//$this->inc('elements/header_theme.php'); ?>

<!--  used by foxy.php as a page type     -->
	<main class="container full-width gutters justify-center">
		<div class="col-10">
			<div class="container align-items-center">
				<div class="col-12 col-md-6 area">
                    <?php
                    $a = new Area('Main');
					$a->setCustomTemplate('content', 'foxy_content.php');
					$a->setCustomTemplate('form', 'foxy_express_form.php');


                    $a->display($c);
                    ?>
				</div>

                <!--  AREA TWO     -->

				<div class="col-12 col-md-6 area">
                    <?php
                    $a = new Area('Sidebar');
                    $a->display($c);
                    ?>
				</div>
			</div>
		</div>

        <!--  AREA THREE     -->

        <div class="col-10">
            <div class="container align-items-center">
                <div class="col-12 col-md-6 area col-order-2 col-md-order-1">
                    <?php
                    $a = new Area('Area Three');
					$a->setCustomTemplate('content', 'foxy_content.php');
					$a->setCustomTemplate('form', 'foxy_express_form.php');
					$a->display($c);
                    ?>
                </div>

                <!--  AREA FOUR     -->

                <div class="col-12 col-md-6 area col-order-1 col-md-order-2 ">
                    <?php
                    $a = new Area('Area Four');
					$a->setCustomTemplate('content', 'foxy_content.php');
					$a->setCustomTemplate('form', 'foxy_express_form.php');
					$a->display($c);
                    ?>
                </div>
            </div>
        </div>

        <!--  AREA FIVE     -->

        <div class="col-10">
			<div class="container align-items-center">
				<div class="col-12 col-md-6 area">
                    <?php
                    $a = new Area('Area Five');
					$a->setCustomTemplate('content', 'foxy_content.php');
					$a->setCustomTemplate('form', 'foxy_express_form.php');
					$a->display($c);
                    ?>
				</div>

                <!--  AREA SIX     -->

				<div class="col-12 col-md-6 area">
                    <?php
                    $a = new Area('Area Six');
					$a->setCustomTemplate('content', 'foxy_content.php');
					$a->setCustomTemplate('form', 'foxy_express_form.php');
					$a->display($c);
                    ?>
				</div>
			</div>
		</div>

        <!--  AREA SEVEN     -->

        <div class="col-10">
			<div class="container align-items-center">
				<div class="col-12 col-md-6 area col-md-order-2 col-md-order-1">
                    <?php
                    $a = new Area('Area Seven');
					$a->setCustomTemplate('content', 'foxy_content.php');
					$a->setCustomTemplate('form', 'foxy_express_form.php');
					$a->display($c);
                    ?>
				</div>

                <!--  AREA EIGHT     -->

				<div class="col-12 col-md-6 area col-md-order-1 col-md-order-2">
                    <?php
                    $a = new Area('Area Eight');
					$a->setCustomTemplate('content', 'foxy_content.php');
					$a->setCustomTemplate('form', 'foxy_express_form.php');
					$a->display($c);
                    ?>
				</div>
			</div>
		</div>
		<div class="col-10">
			<div class="container align-items-center">
				<div class="col-12 col-md-6 area col-md-order-2 col-md-order-1">
					<?php
					$a = new Area('Area Nine');
					$a->setCustomTemplate('content', 'foxy_content.php');
					$a->setCustomTemplate('form', 'foxy_express_form.php');
					$a->display($c);
					?>
				</div>

				<!--  AREA TEN     -->

				<div class="col-12 col-md-6 area col-md-order-1 col-md-order-2">
					<?php
					$a = new Area('Area Ten');
					$a->setCustomTemplate('content', 'foxy_content.php');
					$a->setCustomTemplate('form', 'foxy_express_form.php');
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
