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
  		<div class="col-10">
        <?php
         $a = new Area('Main');
         $a->display($c);
         ?>
  		</div>
  	</div>
	</div>

</main>
<?php $this->inc('elements/page_nav.php');
$this->inc('elements/footer.php');
?>
