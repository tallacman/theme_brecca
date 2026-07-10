<?php        defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('elements/header.php');
?>


<main>
	<?php
	   $a = new Area('Lead');
      if (($a->getTotalBlocksInArea($c) > 0) || ($c->isEditMode())) {
        echo '<div class="lead">';
				$a->enableGridContainer();

        $a->display($c);
        echo "</div>";
	}
	?>


	<div class="container">
		<div class="row">
  		<div class="col-12 col-md-7 order-md-2 offset-md-0">
        <?php
         $a = new Area('Main');
         $a->display($c);
         ?>
  		</div>
      <div class="col-12 col-md-3 order-md-1 offset-md-2 sidebar">
        <?php
         $a = new Area('Sidebar');
         $a->display($c);
         ?>
      </div>
  	</div>
	</div>

	<?php
	$a = new Area('Page Footer');
	$a->enableGridContainer();
	$a->display($c);
	?>
</main>


<?php $this->inc('elements/page_nav.php');
$this->inc('elements/footer.php');
?>
