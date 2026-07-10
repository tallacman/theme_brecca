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
      <div class="col-12 col-sm-12 col-md-11 offset-md-1 col-lg-10 offset-lg-2">
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
