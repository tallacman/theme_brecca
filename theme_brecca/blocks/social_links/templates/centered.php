<?php   defined('C5_EXECUTE') or die("Access Denied."); ?>

<div id="ccm-block-social-links<?php     echo $bID?>" class="ccm-block-social-links">
    <div class="container gutters-sm-all justify-center">
        <?php     foreach($links as $link) {
            $service = $link->getServiceObject();
            ?>
            <div class="col-1"><a href="<?php     echo $link->getURL()?>"><?php     echo $service->getServiceIconHTML()?></a></div>
        <?php     } ?>
    </div>
</div>
