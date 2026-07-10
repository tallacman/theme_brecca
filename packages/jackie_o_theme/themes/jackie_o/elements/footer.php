<?php        defined('C5_EXECUTE') or die("Access Denied."); ?>
<!--   nessessary code for theme and concrete5    -->

<!--  these replicate all of the areas from the Elemental theme     -->
	<footer class="container full-width page-footer">
		<div class="col-12">
			<div class="container gutters">
				<div class="col-6 col-xs-4 col-sm-3">
					<?php
					$a = new GlobalArea('Footer Site Title');
					$a->display();
					?>
				</div>
				<div class="col-6 col-xs-4 col-sm-3">
					<?php
					$a = new GlobalArea('Footer Social');
					$a->display();
					?>
				</div>
				<div class="col-6 col-xs-4 col-sm-3">
					<?php
					$a = new GlobalArea('Footer Contact');
					$a->display();
					?>
				</div>
				<div class="col-6 col-xs-4 col-sm-3">
					<?php
					$a = new GlobalArea('Footer Navigation');
					$a->display();
					?>
				</div>
			</div>
		</div>
	</footer>


	<footer class="container full-width site-footer justify-center">
		<div class="col-12">
			<div class="container gutters">
				<div class="col-12 col-xs-6">
					<?php
					$a = new GlobalArea('Footer Legal');
					$a->display();
					?>
				</div>
				<div class="col-12 col-xs-6">
					<?php
					$a = new GlobalArea('LoginOut');
					$a->display();
					?>
				</div>
				<div class="col-12">
				<span id="ccm-account-menu-container"></span>

				</div>
			</div>
		</div>
	</footer>

</div> <!--  end ccm-page wrapper   -->


<?php        View::element('footer_required'); ?>

</body>
</html>
