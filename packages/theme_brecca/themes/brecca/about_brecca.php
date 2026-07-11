<?php      defined('C5_EXECUTE') or die("Access Denied."); ?>
<?php
use Concrete\Core\User\User;

$u = new User();
?>
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

            <?php if ($u->isRegistered()) { ?>
                <div class="inner white-translucent-background gutters-all-big col-12">

                <h1>About Brecca Theme</h1>

                    <p>Brecca was designed to be an elegant theme for events, weddings and special occasions. Currently</p>
                    <h2>Navigation</h2>

                    <p> In the navigation area in the header you should put the autonav block. That block has a custom template. It's called Brecca Nav and that template was made for this theme. It uses flex box to place the menu items in line across the top of the page. It supports sub pages. All page names are centered the navigation bar. The number of top pages should be limited for design purposes.</p>
                    <h2>Background Image</h2>
                    <p>This theme automatically installs a custom block called Tallacmans Background Image. Use that to set a background image on your page. If you install that blocking the global area, you will have an image on every page. However, you can override that on any page you desire by putting that block in the per page background image area. </p> <p> One caveat when using background damage some mobile devices don't render the background image on scrolling down. This is not a feature of the block of the theme. This is just a limitation of what mobile browsers can do.</p>
                    <h2>Site Name</h2>
                    <p>The site name appears on the top of every page. This font-face can be changed. The theme comes with the number of preloaded fonts. </p> <p>Using JavaScript, the name will never break the two pieces. Long names will be resized to fit in the area, but try not to get excessive.</p>
                </div>
            <?php } ?>

            <div class="inner white-translucent-background gutters-all-big col-12">
            <?php
            $a = new Area('Main');
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
