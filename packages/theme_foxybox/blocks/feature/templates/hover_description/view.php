<?php defined('C5_EXECUTE') or die("Access Denied."); ?>

<?php if ($linkURL) {
    ?>
    <a href="<?= h($linkURL) ?>">
<?php
} ?>

  <div class="ccm-tallacman-feature-hover-wrapper">
      <div class="ccm-tallacman-feature-hover">
          <div class="ccm-tallacman-feature-hover-icon"><i class="fa fa-<?= h($icon) ?>"></i></div>
      </div>
      <div class="ccm-tallacman-feature-hover-title"><?php echo h($title)?></div>
      <div class="ccm-tallacman-feature-hover-para">
        <?php echo h(strip_tags($paragraph))?>
      </div>
  </div>

<?php if ($linkURL) {
    ?>
    </a>
<?php
} ?>
