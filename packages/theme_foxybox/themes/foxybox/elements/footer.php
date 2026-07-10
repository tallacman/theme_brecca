<?php defined('C5_EXECUTE') or die("Access Denied."); ?>

<div class="container background justify-center">
    <div class="col-4">
        <?php
        $a = new Area('Page Background');
        $a->display($c);
        ?>
         <?php
        // Only show the global background area when the current page does not
        // have a per-page `tallacmans_picturesque` block installed.
        $showGlobalBackground = true;
        $page = \Concrete\Core\Page\Page::getCurrentPage();
        if ($page) {
            $pageBgBlocks = $page->getBlocks('Page Background');
            foreach ($pageBgBlocks as $pb) {
                if (method_exists($pb, 'getBlockTypeHandle') && $pb->getBlockTypeHandle() === 'tallacmans_picturesque') {
                    $showGlobalBackground = false;
                    break;
                }
            }
        }

        if ($showGlobalBackground) {
            $a = new GlobalArea('GlobalPage Background');
            $a->display($c);
        }
        ?>
    </div>
</div>
    <footer class="container gutters-all">
        <div class="col-6">
            <?php
        	$a = new GlobalArea('Footer');
        	$a->display();
            ?>
        </div>
        <div class="col-6 text-right">
            <?php
            $a = new GlobalArea('Footer Right');
            $a->display();
            ?>
        </div>
    </footer>
</div> <!--  end page wrapper     -->


<?php          View::element('footer_required'); ?>

    <script src="<?php        echo $this->getThemePath();?>/js/slicknav.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
    	$('#menu').slicknav();
    });
</script>


</body>
</html>
