<?php
defined('C5_EXECUTE') or die("Access Denied.");
/*   modified for fondation 6.5 and bootstrap 4 by tallacman    */


if (!$previousLinkURL && !$nextLinkURL && !$parentLabel) {
    return false;
}
?>

<div class="ccm-block-next-previous-wrapper simple-edge-to-edge">
  <div class="row justify-content-between">

    <div class="col-auto next-prev prev py-1 py-md-4">
          <p class="ccm-block-next-previous-previous-link"><p class="small">previous</p>
            <i class="fa fa-caret-left" aria-hidden="true">&nbsp;</i>  <?php echo $previousLinkURL ? '<a href="' . $previousLinkURL . '">' . $previousLinkText . '</a>' : '' ?>
          </p>
    </div>

    <div class="col-auto next-prev next py-1 py-md-4">
            <p class="ccm-block-next-previous-next-link"><p class="small">next</p>
                <?php echo $nextLinkURL ? '<a href="' . $nextLinkURL . '">' . $nextLinkText . '</a>' : '' ?>&nbsp;&nbsp;<i class="fa fa-caret-right" aria-hidden="true"></i>
            </p>
    </div>

  </div>
</div>
