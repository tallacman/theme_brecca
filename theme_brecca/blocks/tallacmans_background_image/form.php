<?php
defined('C5_EXECUTE') or die('Access Denied.');

$assetLibrary = app('helper/concrete/asset_library');
$color = app('helper/form/color');
$file = !empty($fID) ? \Concrete\Core\File\File::getByID((int) $fID) : null;
$bgOverlayColor = (string) ($bgOverlayColor ?? '');
if (!$file || $file->isError()) {
    $file = null;
}
?>
<div class="form-group">
    <?= $form->label('fID', t('Background Image')) ?>
    <?= $assetLibrary->image('ccm-background-image', 'fID', t('Choose Image'), $file) ?>
    <div class="form-text"><?= t('The image fills the browser window and stays centered.') ?></div>
</div>

<div class="form-group">
    <?= $form->label('bgOverlayColor', t('Optional Color Overlay')) ?>
    <?php $color->output('bgOverlayColor', $bgOverlayColor, [
        'showAlpha' => true,
        'preferredFormat' => 'rgb',
    ]); ?>
    <div class="form-text"><?= t('Choose a transparent color to tint or darken the image. Leave blank for no overlay.') ?></div>
</div>
