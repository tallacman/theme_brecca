<?php        defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('elements/header.php'); ?>


<!--  basic page structure   -->
<main class="container full-width">
    <div class="col-12">
        <div class="container full-width edgetoedge">
            <div class="col-12">
                <?php
                $a = new Area('Edge to Edge');
                $a->display($c);
                ?>
            </div>
        </div>


        <div class="container gutters justify-center">
            <div class="col-12 lead">
                <div class="container full-width justify-center">
                    <div class="col-12 col-xs-10 col-sm-9">
                        <?php
                        $a = new Area('Lead');
                        $a->display($c);
                        ?>
                    </div>
                </div>
            </div>
<!--  this area is centered on the page     -->
            <div class="col-12 col-xs-12 col-sm-12 content">
                <?php
                $a = new Area('Main');
                $a->display($c);
                ?>
            </div>
        </div>
    </div>
</main>



<?php       $this->inc('elements/footer.php'); ?>
