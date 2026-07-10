<?php

declare(strict_types=1);

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\File\File;

$app = (isset($app) && $app) ? $app : \Concrete\Core\Support\Facade\Application::getFacadeApplication();
$assetLibrary = $app->make('helper/concrete/asset_library');
$color = $app->make('helper/form/color');
$editor = $app->make('editor');

$imageFile = null;
if (!empty($Image) && (int) $Image > 0) {
    $imageFile = File::getByID((int) $Image);
    if (!$imageFile || $imageFile->isError()) {
        $imageFile = null;
    }
}
?>

<div class="tlf-block-editor">
    <fieldset class="mb-3">
        <legend class="fs-6"><?= t('Background') ?></legend>

        <div class="form-group mb-2">
            <?= $form->label('Image', t('Image'), ['class' => 'form-label']) ?>
            <?= $assetLibrary->image(
                'ccm-b-tallacmans_lead_feature-Image-' . ($identifier_getString ?? ''),
                $view->field('Image'),
                t('Choose Image'),
                $imageFile
            ) ?>
            <p class="help-block small text-muted mb-0">
                <?= t('Optional. The image fills the block width and can scroll with the page or stay fixed.') ?>
            </p>
        </div>

        <div class="row g-2">
            <div class="col-md-6">
                <?= $form->label('Height', t('Height'), ['class' => 'form-label small']) ?>
                <?= $form->select($view->field('Height'), $Height_options, $Height ?? '50vh', ['class' => 'form-select form-select-sm']) ?>
            </div>
            <div class="col-md-6">
                <?= $form->label('Align', t('Align Image'), ['class' => 'form-label small']) ?>
                <?= $form->select($view->field('Align'), $Align_options, $Align ?? 'center center', ['class' => 'form-select form-select-sm']) ?>
            </div>
        </div>

        <div class="row g-2 mt-1">
            <div class="col-md-6">
                <?= $form->label('ScrollBehaviour', t('Scroll Image with Page?'), ['class' => 'form-label small']) ?>
                <?= $form->select($view->field('ScrollBehaviour'), $ScrollBehaviour_options, $ScrollBehaviour ?? 'scroll', ['class' => 'form-select form-select-sm']) ?>
            </div>
            <div class="col-md-6">
                <?= $form->label('Overlay', t('Overlay Color'), ['class' => 'form-label small']) ?>
                <?php $color->output('Overlay', $Overlay ?? 'rgba(0,0,0,0.1)', [
                    'showAlpha' => true,
                    'preferredFormat' => 'rgb',
                ]); ?>
            </div>
        </div>
    </fieldset>

    <fieldset class="mb-3">
        <legend class="fs-6"><?= t('Lead Text') ?></legend>

        <div class="form-group mb-2">
            <?= $form->label('LeadText', t('Lead Text'), ['class' => 'form-label']) ?>
            <?= $editor->outputBlockEditModeEditor($view->field('LeadText'), $LeadText ?? '') ?>
        </div>

        <div class="row g-2">
            <div class="col-md-4">
                <?= $form->label($view->field('LeadAnimation'), t('Animation'), ['class' => 'form-label small']) ?>
                <?= $form->select($view->field('LeadAnimation'), $LeadAnimation_options, $LeadAnimation ?? '', ['class' => 'form-select form-select-sm']) ?>
            </div>
            <div class="col-md-4">
                <?= $form->label($view->field('LeadSpeed'), t('Speed'), ['class' => 'form-label small']) ?>
                <?= $form->select($view->field('LeadSpeed'), $LeadSpeed_options, $LeadSpeed ?? '', ['class' => 'form-select form-select-sm']) ?>
            </div>
            <div class="col-md-4">
                <?= $form->label('LeadDelay', t('Delay'), ['class' => 'form-label small']) ?>
                <?= $form->select($view->field('LeadDelay'), $LeadDelay_options, $LeadDelay ?? '', ['class' => 'form-select form-select-sm']) ?>
            </div>
        </div>
    </fieldset>

    <fieldset class="mb-3">
        <legend class="fs-6"><?= t('Secondary Text') ?></legend>

        <div class="form-group mb-2">
            <?= $form->label('SecondaryText', t('Secondary Text'), ['class' => 'form-label']) ?>
            <?= $editor->outputBlockEditModeEditor($view->field('SecondaryText'), $SecondaryText ?? '') ?>
        </div>

        <div class="row g-2">
            <div class="col-md-4">
                <?= $form->label('SecondaryAnimation', t('Animation'), ['class' => 'form-label small']) ?>
                <?= $form->select($view->field('SecondaryAnimation'), $SecondaryAnimation_options, $SecondaryAnimation ?? '', ['class' => 'form-select form-select-sm']) ?>
            </div>
            <div class="col-md-4">
                <?= $form->label('SecondarySpeed', t('Speed'), ['class' => 'form-label small']) ?>
                <?= $form->select($view->field('SecondarySpeed'), $SecondarySpeed_options, $SecondarySpeed ?? '', ['class' => 'form-select form-select-sm']) ?>
            </div>
            <div class="col-md-4">
                <?= $form->label('SecondaryDelay', t('Delay'), ['class' => 'form-label small']) ?>
                <?= $form->select($view->field('SecondaryDelay'), $SecondaryDelay_options, $SecondaryDelay ?? '', ['class' => 'form-select form-select-sm']) ?>
            </div>
        </div>
    </fieldset>

    <fieldset class="mb-0">
        <legend class="fs-6"><?= t('Scroll Arrow') ?></legend>

        <div class="row g-2">
            <div class="col-md-6">
                <?= $form->label('ShowArrow', t('Show Arrow Down'), ['class' => 'form-label small']) ?>
                <?= $form->select($view->field('ShowArrow'), $ShowArrow_options, $ShowArrow ?? '0', ['class' => 'form-select form-select-sm']) ?>
                <p class="help-block small text-muted mb-0">
                    <?= t('Adds a bouncing chevron that links to the content below this block.') ?>
                </p>
            </div>
            <div class="col-md-6">
                <?= $form->label('ChevronColor', t('Chevron Color'), ['class' => 'form-label small']) ?>
                <?php $color->output('ChevronColor', $ChevronColor ?? '#ffffff', [
                    'showAlpha' => true,
                    'preferredFormat' => 'rgb',
                ]); ?>
            </div>
        </div>
    </fieldset>
</div>
