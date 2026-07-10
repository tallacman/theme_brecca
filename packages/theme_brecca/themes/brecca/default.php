<?php      defined('C5_EXECUTE') or die("Access Denied."); ?>
<?php      $this->inc('elements/header.php');?>


<?php
  if ($c->isEditMode()) { ?>
    <main class="container justify-center">
<?php } else { ?>
    <main class="container justify-center brecca">
<?php } ?>

<div class="col-12">
    <div class="container">
        <div class="col-6">
            <?php
            // In edit mode always show the global area so it can be managed.
            // Otherwise, skip rendering (and loading) the global background
            // image whenever a local per-page background image is set.
            $showGlobalBackground = true;
            if (!$c->isEditMode()) {
                foreach ($c->getBlocks('Background Image per Page') as $localBlock) {
                    if ($localBlock->getBlockTypeHandle() === 'tallacmans_background_image') {
                        $localController = $localBlock->getController();
                        if ((int) $localController->fID > 0) {
                            $showGlobalBackground = false;
                            break;
                        }
                    }
                }
            }

            if ($showGlobalBackground) {
                $a = new GlobalArea('Background Image Global');
                $a->display($c);
            }
            ?>
        </div>

         <div class="col-6">
            <?php
            $a = new Area('Background Image per Page');
            $a->display($c);
            ?>
        </div>


    </div>
</div>


    <div class="col-10 col-sm-9 col-md-7 col-lg-7 white-outer-back gutters-a">
        <div class="container">

            <div class="inner white-translucent-background gutters-all-big col-12">
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
