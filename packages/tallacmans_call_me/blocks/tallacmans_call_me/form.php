<?php

declare(strict_types=1);

defined('C5_EXECUTE') or die('Access Denied.');
?>

<div class="tcm-block-editor">
    <fieldset class="mb-3">
        <legend class="fs-6"><?= t('Contact Link') ?></legend>

        <div class="form-group mb-2">
            <?= $form->label('callOrText', t('Call or Text'), ['class' => 'form-label']) ?>
            <?= $form->select($view->field('callOrText'), $callOrText_options, $callOrText ?? '1', ['class' => 'form-select form-select-sm']) ?>
            <p class="help-block small text-muted mb-0">
                <?= t('Opens the visitor\'s phone app to dial or compose a text message.') ?>
            </p>
        </div>

        <div class="form-group mb-2">
            <?= $form->label('phoneNumber', t('Phone Number'), ['class' => 'form-label']) ?>
            <?= $form->tel($view->field('phoneNumber'), $phoneNumber ?? '', [
                'maxlength' => 45,
                'placeholder' => t('For example, +1 555 123 4567'),
                'class' => 'form-control form-control-sm',
                'required' => 'required',
            ]) ?>
            <p class="help-block small text-muted mb-0">
                <?= t('Include country code when possible. Non-numeric characters are stripped from the link automatically.') ?>
            </p>
        </div>
    </fieldset>

    <fieldset class="mb-0">
        <legend class="fs-6"><?= t('Display') ?></legend>

        <div class="form-group mb-2">
            <?= $form->label('displayText', t('Display Text'), ['class' => 'form-label']) ?>
            <?= $form->text($view->field('displayText'), $displayText ?? '', [
                'maxlength' => 255,
                'placeholder' => t('Call us today'),
                'class' => 'form-control form-control-sm',
                'required' => 'required',
            ]) ?>
        </div>

        <div class="form-group mb-0">
            <?= $form->label('displayAs', t('Display As'), ['class' => 'form-label']) ?>
            <?= $form->select($view->field('displayAs'), $displayAs_options, $displayAs ?? '1', ['class' => 'form-select form-select-sm']) ?>
            <p class="help-block small text-muted mb-0">
                <?= t('Choose a button or a text element (heading, span, or paragraph).') ?>
            </p>
        </div>
    </fieldset>
</div>
