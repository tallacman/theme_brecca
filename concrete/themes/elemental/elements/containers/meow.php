<?php
defined('C5_EXECUTE') or die('Access Denied.');

/* CM_DESIGNER_STATE eyJuYW1lIjoibWVvdyIsImhhbmRsZSI6Im1lb3ciLCJsYXlvdXRfbW9kZSI6ImJvb3RzdHJhcDUiLCJ0aGVtZV9pZCI6Miwic2V0dGluZ3MiOnsiZ2FwIjoiIiwiaW5uZXIiOiJjb250YWluZXIiLCJncmlkX2NvbHVtbnMiOiIxZnIgMWZyIDFmciAxZnIgMWZyIDFmciAxZnIgMWZyIDFmciAxZnIgMWZyIDFmciIsImdyaWRfcm93cyI6Im1pbm1heCg2NHB4LCBhdXRvKSIsImdyaWRfdHJhY2tzIjp7InRyYWNrcyI6W3sibW9kZSI6ImZyIiwidmFsdWUiOiIxIiwibWluIjoiMjAwcHgiLCJwcmVmZXJyZWQiOiIxZnIiLCJtYXgiOiIxMDAlIn0seyJtb2RlIjoiZnIiLCJ2YWx1ZSI6IjEiLCJtaW4iOiIyMDBweCIsInByZWZlcnJlZCI6IjFmciIsIm1heCI6IjEwMCUifSx7Im1vZGUiOiJmciIsInZhbHVlIjoiMSIsIm1pbiI6IjIwMHB4IiwicHJlZmVycmVkIjoiMWZyIiwibWF4IjoiMTAwJSJ9LHsibW9kZSI6ImZyIiwidmFsdWUiOiIxIiwibWluIjoiMjAwcHgiLCJwcmVmZXJyZWQiOiIxZnIiLCJtYXgiOiIxMDAlIn0seyJtb2RlIjoiZnIiLCJ2YWx1ZSI6IjEiLCJtaW4iOiIyMDBweCIsInByZWZlcnJlZCI6IjFmciIsIm1heCI6IjEwMCUifSx7Im1vZGUiOiJmciIsInZhbHVlIjoiMSIsIm1pbiI6IjIwMHB4IiwicHJlZmVycmVkIjoiMWZyIiwibWF4IjoiMTAwJSJ9LHsibW9kZSI6ImZyIiwidmFsdWUiOiIxIiwibWluIjoiMjAwcHgiLCJwcmVmZXJyZWQiOiIxZnIiLCJtYXgiOiIxMDAlIn0seyJtb2RlIjoiZnIiLCJ2YWx1ZSI6IjEiLCJtaW4iOiIyMDBweCIsInByZWZlcnJlZCI6IjFmciIsIm1heCI6IjEwMCUifSx7Im1vZGUiOiJmciIsInZhbHVlIjoiMSIsIm1pbiI6IjIwMHB4IiwicHJlZmVycmVkIjoiMWZyIiwibWF4IjoiMTAwJSJ9LHsibW9kZSI6ImZyIiwidmFsdWUiOiIxIiwibWluIjoiMjAwcHgiLCJwcmVmZXJyZWQiOiIxZnIiLCJtYXgiOiIxMDAlIn0seyJtb2RlIjoiZnIiLCJ2YWx1ZSI6IjEiLCJtaW4iOiIyMDBweCIsInByZWZlcnJlZCI6IjFmciIsIm1heCI6IjEwMCUifSx7Im1vZGUiOiJmciIsInZhbHVlIjoiMSIsIm1pbiI6IjIwMHB4IiwicHJlZmVycmVkIjoiMWZyIiwibWF4IjoiMTAwJSJ9XSwicm93U2l6ZSI6eyJtb2RlIjoibWlubWF4IiwidmFsdWUiOiI2NHB4fGF1dG8iLCJtaW4iOiIyMDBweCIsInByZWZlcnJlZCI6IjFmciIsIm1heCI6IjEwMCUifX0sInJvd19zaXplIjpbXSwidGFibGV0X2NvbHVtbnMiOiIiLCJtb2JpbGVfY29sdW1ucyI6IiIsInNpZGVfbWFyZ2luIjoiIiwidGhlbWVfaGFzX2Jvb3RzdHJhcCI6ZmFsc2UsImJvb3RzdHJhcF9ndXR0ZXIiOiJnLTUifSwiYXJlYXMiOlt7Im5hbWUiOiJsZWZ0IiwiY29sIjoxLCJyb3ciOjEsInJvd19zcGFuIjoxLCJncmlkX2NvbHVtbiI6IiIsImdyaWRfcm93IjoiIiwibWluX2hlaWdodCI6IiIsImRlc2t0b3AiOjQsInRhYmxldCI6NiwibW9iaWxlIjoxMiwidGFibGV0X3NwYW4iOjYsIm1vYmlsZV9zcGFuIjoxMiwibW9iaWxlX3N0YWNrIjp0cnVlLCJ0YWJsZXRfY29sIjoxLCJtb2JpbGVfY29sIjoxLCJ0YWJsZXRfcm93IjoxLCJtb2JpbGVfcm93IjoyLCJmdWxsIjpmYWxzZSwibmVzdGVkIjp0cnVlLCJwYWRkaW5nIjoiIn0seyJuYW1lIjoibWlkZGxlIiwiY29sIjo1LCJyb3ciOjEsInJvd19zcGFuIjoxLCJncmlkX2NvbHVtbiI6IiIsImdyaWRfcm93IjoiIiwibWluX2hlaWdodCI6IiIsImRlc2t0b3AiOjQsInRhYmxldCI6NCwibW9iaWxlIjoxMiwidGFibGV0X3NwYW4iOjQsIm1vYmlsZV9zcGFuIjoxMiwibW9iaWxlX3N0YWNrIjp0cnVlLCJ0YWJsZXRfY29sIjoxLCJtb2JpbGVfY29sIjoxLCJ0YWJsZXRfcm93IjoyLCJtb2JpbGVfcm93IjozLCJmdWxsIjpmYWxzZSwibmVzdGVkIjp0cnVlLCJwYWRkaW5nIjoiIn0seyJuYW1lIjoicmlnaHQiLCJjb2wiOjUsInJvdyI6Miwicm93X3NwYW4iOjEsImdyaWRfY29sdW1uIjoiIiwiZ3JpZF9yb3ciOiIiLCJtaW5faGVpZ2h0IjoiIiwiZGVza3RvcCI6OCwidGFibGV0Ijo2LCJtb2JpbGUiOjEyLCJ0YWJsZXRfc3BhbiI6NiwibW9iaWxlX3NwYW4iOjEyLCJtb2JpbGVfc3RhY2siOnRydWUsInRhYmxldF9jb2wiOjcsIm1vYmlsZV9jb2wiOjEsInRhYmxldF9yb3ciOjEsIm1vYmlsZV9yb3ciOjEsImZ1bGwiOmZhbHNlLCJuZXN0ZWQiOnRydWUsInBhZGRpbmciOiIifV19 CM_DESIGNER_STATE_END */

use Concrete\Core\Area\ContainerArea;

$cmContainerClass = 'cm-meow';
$cmContainerCss = '/* Container Maker — Bootstrap 5 grid scoped to .cm-meow */
.cm-meow .container, .cm-meow .container-fluid { width: 100%; padding-right: calc(3rem * .5); padding-left: calc(3rem * .5); margin-right: auto; margin-left: auto; }
.cm-meow .container { max-width: 1320px; }
.cm-meow .row { --bs-gutter-x: 3rem; --bs-gutter-y: 0; display: flex; flex-wrap: wrap; margin-top: calc(-1 * var(--bs-gutter-y)); margin-right: calc(-.5 * var(--bs-gutter-x)); margin-left: calc(-.5 * var(--bs-gutter-x)); }
.cm-meow .row > [class*="col-"], .cm-meow .row > [class*="offset-"] { flex-shrink: 0; max-width: 100%; padding-right: calc(var(--bs-gutter-x) * .5); padding-left: calc(var(--bs-gutter-x) * .5); margin-top: var(--bs-gutter-y); box-sizing: border-box; }
.cm-meow .cm-meow__row-break { flex-basis: 100%; width: 100%; height: 0; overflow: hidden; padding: 0; margin: 0; border: 0; }
.cm-meow .row > .col-1 { flex: 0 0 auto; width: 8.333333%; }
.cm-meow .row > .col-2 { flex: 0 0 auto; width: 16.666667%; }
.cm-meow .row > .col-3 { flex: 0 0 auto; width: 25%; }
.cm-meow .row > .col-4 { flex: 0 0 auto; width: 33.333333%; }
.cm-meow .row > .col-5 { flex: 0 0 auto; width: 41.666667%; }
.cm-meow .row > .col-6 { flex: 0 0 auto; width: 50%; }
.cm-meow .row > .col-7 { flex: 0 0 auto; width: 58.333333%; }
.cm-meow .row > .col-8 { flex: 0 0 auto; width: 66.666667%; }
.cm-meow .row > .col-9 { flex: 0 0 auto; width: 75%; }
.cm-meow .row > .col-10 { flex: 0 0 auto; width: 83.333333%; }
.cm-meow .row > .col-11 { flex: 0 0 auto; width: 91.666667%; }
.cm-meow .row > .col-12 { flex: 0 0 auto; width: 100%; }
.cm-meow .row > .col-12 { flex: 0 0 auto; width: 100%; }
.cm-meow .row > .offset-1 { margin-left: 8.333333%; }
.cm-meow .row > .offset-2 { margin-left: 16.666667%; }
.cm-meow .row > .offset-3 { margin-left: 25%; }
.cm-meow .row > .offset-4 { margin-left: 33.333333%; }
.cm-meow .row > .offset-5 { margin-left: 41.666667%; }
.cm-meow .row > .offset-6 { margin-left: 50%; }
.cm-meow .row > .offset-7 { margin-left: 58.333333%; }
.cm-meow .row > .offset-8 { margin-left: 66.666667%; }
.cm-meow .row > .offset-9 { margin-left: 75%; }
.cm-meow .row > .offset-10 { margin-left: 83.333333%; }
.cm-meow .row > .offset-11 { margin-left: 91.666667%; }
@media (min-width: 768px) {
    .cm-meow .row > .col-md-1 { flex: 0 0 auto; width: 8.333333%; }
    .cm-meow .row > .col-md-2 { flex: 0 0 auto; width: 16.666667%; }
    .cm-meow .row > .col-md-3 { flex: 0 0 auto; width: 25%; }
    .cm-meow .row > .col-md-4 { flex: 0 0 auto; width: 33.333333%; }
    .cm-meow .row > .col-md-5 { flex: 0 0 auto; width: 41.666667%; }
    .cm-meow .row > .col-md-6 { flex: 0 0 auto; width: 50%; }
    .cm-meow .row > .col-md-7 { flex: 0 0 auto; width: 58.333333%; }
    .cm-meow .row > .col-md-8 { flex: 0 0 auto; width: 66.666667%; }
    .cm-meow .row > .col-md-9 { flex: 0 0 auto; width: 75%; }
    .cm-meow .row > .col-md-10 { flex: 0 0 auto; width: 83.333333%; }
    .cm-meow .row > .col-md-11 { flex: 0 0 auto; width: 91.666667%; }
    .cm-meow .row > .col-md-12 { flex: 0 0 auto; width: 100%; }
    .cm-meow .row > .col-md-12 { flex: 0 0 auto; width: 100%; }
    .cm-meow .row > .offset-md-1 { margin-left: 8.333333%; }
    .cm-meow .row > .offset-md-2 { margin-left: 16.666667%; }
    .cm-meow .row > .offset-md-3 { margin-left: 25%; }
    .cm-meow .row > .offset-md-4 { margin-left: 33.333333%; }
    .cm-meow .row > .offset-md-5 { margin-left: 41.666667%; }
    .cm-meow .row > .offset-md-6 { margin-left: 50%; }
    .cm-meow .row > .offset-md-7 { margin-left: 58.333333%; }
    .cm-meow .row > .offset-md-8 { margin-left: 66.666667%; }
    .cm-meow .row > .offset-md-9 { margin-left: 75%; }
    .cm-meow .row > .offset-md-10 { margin-left: 83.333333%; }
    .cm-meow .row > .offset-md-11 { margin-left: 91.666667%; }
}
@media (min-width: 992px) {
    .cm-meow .row > .col-lg-1 { flex: 0 0 auto; width: 8.333333%; }
    .cm-meow .row > .col-lg-2 { flex: 0 0 auto; width: 16.666667%; }
    .cm-meow .row > .col-lg-3 { flex: 0 0 auto; width: 25%; }
    .cm-meow .row > .col-lg-4 { flex: 0 0 auto; width: 33.333333%; }
    .cm-meow .row > .col-lg-5 { flex: 0 0 auto; width: 41.666667%; }
    .cm-meow .row > .col-lg-6 { flex: 0 0 auto; width: 50%; }
    .cm-meow .row > .col-lg-7 { flex: 0 0 auto; width: 58.333333%; }
    .cm-meow .row > .col-lg-8 { flex: 0 0 auto; width: 66.666667%; }
    .cm-meow .row > .col-lg-9 { flex: 0 0 auto; width: 75%; }
    .cm-meow .row > .col-lg-10 { flex: 0 0 auto; width: 83.333333%; }
    .cm-meow .row > .col-lg-11 { flex: 0 0 auto; width: 91.666667%; }
    .cm-meow .row > .col-lg-12 { flex: 0 0 auto; width: 100%; }
    .cm-meow .row > .col-lg-12 { flex: 0 0 auto; width: 100%; }
    .cm-meow .row > .offset-lg-1 { margin-left: 8.333333%; }
    .cm-meow .row > .offset-lg-2 { margin-left: 16.666667%; }
    .cm-meow .row > .offset-lg-3 { margin-left: 25%; }
    .cm-meow .row > .offset-lg-4 { margin-left: 33.333333%; }
    .cm-meow .row > .offset-lg-5 { margin-left: 41.666667%; }
    .cm-meow .row > .offset-lg-6 { margin-left: 50%; }
    .cm-meow .row > .offset-lg-7 { margin-left: 58.333333%; }
    .cm-meow .row > .offset-lg-8 { margin-left: 66.666667%; }
    .cm-meow .row > .offset-lg-9 { margin-left: 75%; }
    .cm-meow .row > .offset-lg-10 { margin-left: 83.333333%; }
    .cm-meow .row > .offset-lg-11 { margin-left: 91.666667%; }
}
@media (max-width: 991px) {
    .cm-meow .row { display: flex; flex-wrap: wrap; }
    .cm-meow__row-break { display: none !important; height: 0; flex-basis: 0; }
    .cm-meow__item--1 { order: 101; }
    .cm-meow__item--2 { order: 201; }
    .cm-meow__item--3 { order: 107; }
}
@media (max-width: 767px) {
    .cm-meow .row { display: flex; flex-wrap: wrap; }
    .cm-meow__row-break { display: none !important; height: 0; flex-basis: 0; }
    .cm-meow__item--1 { order: 201; }
    .cm-meow__item--2 { order: 301; }
    .cm-meow__item--3 { order: 101; }
}
.ccm-edit-mode .cm-meow .ccm-area { min-height: 48px; outline: 1px dashed rgba(0, 0, 0, .25); outline-offset: -1px; }';
if (!isset($GLOBALS['cm_container_styles_added'])) {
    $GLOBALS['cm_container_styles_added'] = [];
}
if (!in_array($cmContainerClass, $GLOBALS['cm_container_styles_added'], true)) {
    \Concrete\Core\View\View::getInstance()->addHeaderItem('<style>' . $cmContainerCss . '</style>');
    $GLOBALS['cm_container_styles_added'][] = $cmContainerClass;
}
?>
<div class="cm-meow">
    <div class="container">
        <div class="row g-5">
            <div class="cm-meow__item cm-meow__item--1 col-12 col-md-6 col-lg-4">
                <?php
                $area = new ContainerArea($container, 'left');
                $area->display($c);
                ?>
            </div>
            <div class="cm-meow__item cm-meow__item--2 col-12 col-md-4 col-lg-4 offset-lg-4">
                <?php
                $area = new ContainerArea($container, 'middle');
                $area->display($c);
                ?>
            </div>
            <div class="cm-meow__row-break" aria-hidden="true"></div>
            <div class="cm-meow__item cm-meow__item--3 col-12 col-md-6 offset-md-6 col-lg-8 offset-lg-4">
                <?php
                $area = new ContainerArea($container, 'right');
                $area->display($c);
                ?>
            </div>
        </div>
    </div>
</div>
