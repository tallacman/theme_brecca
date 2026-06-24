<?php      defined('C5_EXECUTE') or die("Access Denied."); ?>
<?php      $this->inc('elements/header.php');?>

<?php
  if ($c->isEditMode()) { ?>
<main class="container justify-center">
<?php } else { ?>
<main class="container justify-center brecca">
<?php
} ?>    <div class="col-10 col-sm-9 col-md-7 col-lg-7 white-outer-back gutters-a">
        <div class="container">
            <div class="inner white-translucent-background gutters-all-big col-12">
                <?php    View::element('system_errors', array('format' => 'block', 'error' => isset($error) ? $error : null, 'success' => isset($success) ? $success : null, 'message' => isset($message) ? $message : null));
                print $innerContent; ?>
            <?php
            $a = new Area('Main');
            $a->enableGridContainer();
            $a->display($c);
            ?>
            </div>


            <div class="col-12">
            <?php
            $a = new GlobalArea('Social Links');
            $a->display();
            ?>
            </div>
        </div>
    </div>
</main>

<?php      $this->inc('elements/footer.php');?>
