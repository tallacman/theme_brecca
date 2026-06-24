<?php      defined('C5_EXECUTE') or die("Access Denied."); ?>

<?php     $a = new GlobalArea('Background Image');
	 if(($a->getTotalBlocksInArea($c) > 0) || ($c->isEditMode())) {
	 	 echo '<div class="background">';
		 $a->display($c);
		 echo '</div>';
	 } ?>

    </div> <!--  close .ccm-page     -->

<!--  theme javascripts     -->


<script src="<?php      echo $this->getThemePath();?>/js/unorphanize.jquery.js" type="text/javascript"></script>
<script src="<?php      echo $this->getThemePath();?>/js/jquery.fittext.js" type="text/javascript"></script>


    <script type="text/javascript">
        $("h2").unorphanize(1);
    </script>

    <script type="text/javascript">
        $("#fittext").fitText(1.1);
    </script>

<script src="<?php     echo $this->getThemePath()?>/js/slicknav.js"> </script>

<script>
$('#menu').slicknav({
label: 'MENU',
duration: 200,
easingOpen: 'swing',
easingClose: 'swing',
closedSymbol: '&#x2193;',
openedSymbol: '&#x2191;',
prependTo: '#slicknav',
appendTo: '',
parentTag: 'a',
closeOnClick: false,
allowParentLinks: true,
nestedParentLinks: true,
showChildren: false,
removeIds: true,
removeClasses: false,
removeStyles: false,
brand: ''
});
</script>
<?php      View::element('footer_required'); ?>

</body>
</html>
