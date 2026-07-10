<?php
defined('C5_EXECUTE') or die('Access Denied.');

/* CM_DESIGNER_STATE eyJuYW1lIjoieHpjeHoiLCJoYW5kbGUiOiJ4emN4eiIsImxheW91dF9tb2RlIjoiZmxleCIsInNldHRpbmdzIjp7ImdhcCI6IjU1cHgiLCJwYWRkaW5nIjoiMnJlbSAwIiwiaW5uZXIiOiIiLCJtb2JpbGUiOiJjb2x1bW4iLCJkaXJlY3Rpb24iOiJyb3ciLCJ3cmFwIjoid3JhcCIsImFsaWduIjoic3RyZXRjaCIsImp1c3RpZnkiOiJmbGV4LXN0YXJ0IiwidXNlX2ZsZXgiOnRydWV9LCJhcmVhcyI6W3sibmFtZSI6Ik1haW4iLCJkZXNrdG9wIjo0LCJ0YWJsZXQiOjEyLCJtb2JpbGUiOjEyLCJmdWxsIjpmYWxzZSwibmVzdGVkIjp0cnVlLCJwYWRkaW5nIjoiIn0seyJuYW1lIjoiQXJlYSAyIiwiZGVza3RvcCI6NiwidGFibGV0IjoxMiwibW9iaWxlIjoxMiwiZnVsbCI6ZmFsc2UsIm5lc3RlZCI6dHJ1ZSwicGFkZGluZyI6IiJ9XX0= CM_DESIGNER_STATE_END */

use Concrete\Core\Area\ContainerArea;

$areas = array (
  0 => 'Main',
  1 => 'Area 2',
);
$areaWidths = array (
  'Main' => '0',
  'Area 2' => '0',
);
$areaFullRows = array (
);
$responsiveColumns = array (
  'Main' => 
  array (
    'desktop' => 4,
    'tablet' => 12,
    'mobile' => 12,
  ),
  'Area 2' => 
  array (
    'desktop' => 6,
    'tablet' => 12,
    'mobile' => 12,
  ),
);
$nestedAreas = array (
  0 => 'Main',
  1 => 'Area 2',
);
$areaPadding = array (
);
?>
<section class="cm-flex-xzcxz cm-visual-container" style="--cm-container-display: flex; --cm-flex-direction: row; --cm-flex-wrap: wrap; --cm-flex-gap: 55px; --cm-flex-align: stretch; --cm-flex-justify: flex-start; --cm-flex-padding: 2rem 0; --cm-flex-mobile-direction: column; --cm-flex-child-basis: 100%; --cm-flex-child-grow: 0; --cm-flex-child-shrink: 0;">
    <div class="">
        <div class="cm-visual-container__items">
            <?php foreach ($areas as $areaName) { ?>
                <div class="cm-visual-container__item cm-visual-container__item--<?= h(strtolower(preg_replace('/[^a-z0-9]+/i', '-', $areaName))) ?><?= in_array($areaName, $areaFullRows, true) ? ' cm-visual-container__item--full-row' : '' ?><?= in_array($areaName, $nestedAreas, true) ? ' cm-visual-container__item--nested' : '' ?>" style="--cm-flex-child-basis: <?= h(in_array($areaName, $areaFullRows, true) ? '100%' : ($areaWidths[$areaName] ?? '100%')) ?>; --cm-col-desktop: <?= h((string)($responsiveColumns[$areaName]['desktop'] ?? 12)) ?>; --cm-col-tablet: <?= h((string)($responsiveColumns[$areaName]['tablet'] ?? 12)) ?>; --cm-col-mobile: <?= h((string)($responsiveColumns[$areaName]['mobile'] ?? 12)) ?>; --cm-area-padding: <?= h($areaPadding[$areaName] ?? '0') ?>;">
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
    </div>
</section>
<style>
.cm-flex-xzcxz { padding: var(--cm-flex-padding, 0); }
.cm-flex-xzcxz .cm-visual-container__items {
    display: var(--cm-container-display, flex);
    grid-template-columns: repeat(12, minmax(0, 1fr));
    flex-direction: var(--cm-flex-direction, row);
    flex-wrap: var(--cm-flex-wrap, wrap);
    gap: var(--cm-flex-gap, 1rem);
    align-items: var(--cm-flex-align, stretch);
    justify-content: var(--cm-flex-justify, flex-start);
}
.cm-flex-xzcxz .cm-visual-container__item {
    grid-column: span var(--cm-col-desktop, 12);
    flex: var(--cm-flex-child-grow, 1) var(--cm-flex-child-shrink, 1) var(--cm-flex-child-basis, 0);
    min-width: 0;
    position: relative;
    padding: var(--cm-area-padding, 0);
}
.ccm-edit-mode .cm-flex-xzcxz .cm-visual-container__item,
.cm-flex-xzcxz.ccm-edit-mode .cm-visual-container__item {
    min-height: 72px;
    outline: 1px dashed rgba(0, 0, 0, .25);
    outline-offset: -1px;
    padding: .5rem;
}
.ccm-edit-mode .cm-flex-xzcxz .ccm-area,
.cm-flex-xzcxz.ccm-edit-mode .ccm-area {
    min-height: 48px;
}
.cm-flex-xzcxz .cm-visual-container__item--full-row {
    flex: 0 0 100%;
    max-width: 100%;
}
@media (max-width: 991px) {
    .cm-flex-xzcxz .cm-visual-container__item { grid-column: span var(--cm-col-tablet, 12); }
}
@media (max-width: 767px) {
    .cm-flex-xzcxz .cm-visual-container__items {
        flex-direction: var(--cm-flex-mobile-direction, column);
    }
    .cm-flex-xzcxz .cm-visual-container__item { grid-column: span var(--cm-col-mobile, 12); }
}
.ccm-edit-mode .cm-flex-xzcxz .cm-visual-container__item--nested::before {
    content: 'Nested-friendly area';
    display: block;
    font-size: 11px;
    opacity: .65;
    margin-bottom: .25rem;
}
</style>