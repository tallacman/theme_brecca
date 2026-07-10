<?php        defined('C5_EXECUTE') or die("Access Denied."); ?>

<section class="page-nav">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<?php
				$a = new GlobalArea('Page Navigation');
				$a->setCustomTemplate('next_previous', 'simple_edge_to_edge');
				$a->display();
				?>
			</div>
		</div>
	</div>
</section>
