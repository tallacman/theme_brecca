<?php        defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('elements/header.php');
?>


<main>
	<div class="container">
		<div class="row">
			<div class="col-12 col-md-2 blog-date-published">
				<?php
				 $a = new GlobalArea('Published Date');
				 $a->display();
				 ?>
			</div>
			<div class="col-12 col-md-6 order-md-1">
				<?php
				 $a = new Area('Main');
				 $a->display($c);
				 ?>
			</div>
			<div class="col-12 col-md-3 offset-md-1 order-md-2 blog-meta">
				<?php
				 $a = new GlobalArea('Blog Post Sidebar');
				 $a->setCustomTemplate('topics', 'urbane_sidebar');
				 $a->setCustomTemplate('tags', 'urbane_sidebar');
				 $a->setCustomTemplate('page_list', 'urbane_sidebar');
				 $a->display();
				 ?>
			</div>
		</div>
	</div>
</main>


<?php $this->inc('elements/blog_nav.php');
$this->inc('elements/footer.php');
?>
