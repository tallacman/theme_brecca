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

            <?php
            $a = new Area('Lead');
            if (($a->getTotalBlocksInArea($c) > 0) || ($c->isEditMode())) {
                echo '<div class="col-12 col-xs-10 col-sm-9 lead">';
                $a->display($c);
                echo "</div>";
            } ?>

            <div class="col-12 col-xs-10 col-sm-6 content">
                <h1><?php       echo t('404')?></h1>
                <p><?php       echo t('The page you are looking for is not here.')?></p>
                <?php
                $a = new Area('Main');
                $a->display($c);
                ?>
            </div>

            <?php
            $a = new Area('Sidebar');
            if (($a->getTotalBlocksInArea($c) > 0) || ($c->isEditMode())) {
                echo '<div class="col-12 col-xs-10 col-sm-3 sidebar">';
                $a->display($c);
                echo "</div>";
            } ?>



        </div>
    </div>
</main>



<?php       $this->inc('elements/footer.php'); ?>
