<?php
defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Area\ContainerArea;

$areas = array (
  0 => 'Title',
  1 => 'Card One',
  2 => 'Card Two',
  3 => 'Card Three',
);
?>
<section class="cm-card-grid">
    <div class="container">
        <?php foreach ($areas as $areaName) { ?>
            <div class="cm-card-grid__area cm-card-grid__area--<?= h(strtolower(str_replace(' ', '-', $areaName))) ?>">
                <?php
                $area = new ContainerArea($container, $areaName);
                if (method_exists($area, 'setAreaGridMaximumColumns')) {
                    $area->setAreaGridMaximumColumns(12);
                }
                if (method_exists($area, 'enableGridContainer')) {
                    $area->enableGridContainer();
                }
                $area->display($c);
                ?>
            </div>
        <?php } ?>
    </div>
</section>
<style>
.ccm-edit-mode .cm-card-grid__area {
    min-height: 72px;
    outline: 1px dashed rgba(0, 0, 0, .25);
    outline-offset: -1px;
    padding: .5rem;
}
.ccm-edit-mode .cm-card-grid__area .ccm-area {
    min-height: 48px;
}
</style>