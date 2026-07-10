<?php

defined('C5_EXECUTE') or die('Access Denied.');

/** @var \Concrete\Core\Block\View\BlockView $view */
/** @var \Concrete\Core\Express\Form\Renderer|null $renderer */
/** @var \Concrete\Core\Captcha\CaptchaInterface|null $captcha */
?>
<div class="ccm-block-express-form foxy-content">
    <?php if (isset($renderer)) { ?>
        <div class="ccm-form">
            <a id="form<?= h($bID) ?>"></a>

            <?php if (isset($success)) { ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php } ?>

            <?php if (isset($error) && is_object($error)) { ?>
                <div class="alert alert-danger"><?= $error->output() ?></div>
            <?php } ?>

            <form enctype="multipart/form-data" class="form-stacked" method="post"
                  action="<?= h($view->action('submit')) ?>#form<?= h($bID) ?>">
                <?php
                $renderer->setRequiredHtmlElement('<span class="text-muted small">' . t('Required') . '</span>');
                $renderer->render();

                if ($displayCaptcha && $captcha) {
                    ?>
                    <div class="form-group captcha">
                        <?php if ($captchaLabel = $captcha->label()) { ?>
                            <label class="control-label form-label"><?= $captchaLabel ?></label>
                        <?php } ?>
                        <div><?php $captcha->display(); ?></div>
                        <div><?php $captcha->showInput(); ?></div>
                    </div>
                <?php } ?>

                <div class="form-actions">
                    <button type="submit" name="Submit" class="btn btn-primary"><?= h(t($submitLabel)) ?></button>
                </div>
            </form>
        </div>
    <?php } else { ?>
        <p><?= t('This form is unavailable.') ?></p>
    <?php } ?>
</div>
