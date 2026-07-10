<?php defined('C5_EXECUTE') or die('Access Denied.');

/**
 * @var \Concrete\Core\Page\View\PageView $view
 * @var \Concrete\Core\Form\Service\Form $form
 * @var \Concrete\Core\Validation\CSRF\Token $token
 * @var string $folderInput
 * @var string[] $providers
 * @var array $images
 * @var string|null $scanError
 */

$relFolder = trim($folderInput, '/');
?>

<div class="ccm-dashboard-content-form">

    <p><?= t('Point this at a folder of JPEG/PNG files (path is relative to your site root, e.g. <code>application/themes/my_theme/images</code>), scan it, then swap the images you pick for real photos at the exact same dimensions. The original is always saved as a <code>.bak</code> file next to it before it\'s overwritten, so you can restore it at any time.') ?></p>

    <form method="get" action="<?= $view->action('view') ?>" class="mb-4">
        <div class="form-group">
            <label for="folder" class="form-label"><?= t('Folder to scan') ?></label>
            <div class="input-group">
                <?= $form->text('folder', $folderInput, ['placeholder' => 'application/themes/my_theme/images']) ?>
                <button type="submit" class="btn btn-secondary"><?= t('Scan Folder') ?></button>
            </div>
        </div>
    </form>

    <?php if ($scanError): ?>
        <div class="alert alert-danger"><?= h($scanError) ?></div>
    <?php endif; ?>

    <?php if ($folderInput !== '' && !$scanError && empty($images)): ?>
        <div class="alert alert-info"><?= t('No JPEG or PNG files were found in that folder.') ?></div>
    <?php endif; ?>

    <?php if (!empty($images)): ?>
        <form method="post" action="<?= $view->action('process') ?>">
            <?= $token->output('image_replacer_process') ?>
            <input type="hidden" name="folder" value="<?= h($folderInput) ?>">

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="width:2em;"><input type="checkbox" id="ir-select-all"></th>
                        <th><?= t('Preview') ?></th>
                        <th><?= t('Filename') ?></th>
                        <th><?= t('Dimensions') ?></th>
                        <th><?= t('Size') ?></th>
                        <th><?= t('Backup?') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($images as $image): ?>
                        <tr>
                            <td><input type="checkbox" name="files[]" class="ir-file-checkbox" value="<?= h($image['filename']) ?>"></td>
                            <td>
                                <img src="<?= rtrim(DIR_REL, '/') ?>/<?= h($relFolder) ?>/<?= h(rawurlencode($image['filename'])) ?>"
                                     alt="" style="max-width:80px;max-height:60px;" onerror="this.style.display='none'">
                            </td>
                            <td><?= h($image['filename']) ?></td>
                            <td><?= (int) $image['width'] ?> &times; <?= (int) $image['height'] ?></td>
                            <td><?= h(round($image['size'] / 1024, 1)) ?> KB</td>
                            <td><?= $image['hasBackup'] ? t('Yes') : t('No') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <fieldset class="mb-3">
                <legend><?= t('Replacement source') ?></legend>

                <div class="form-group mb-2">
                    <label for="provider" class="form-label"><?= t('Provider') ?></label>
                    <?= $form->select('provider', $providers, 'picsum') ?>
                </div>

                <div class="row">
                    <div class="col-md-4 form-group mb-2">
                        <label for="category" class="form-label"><?= t('Search keyword') ?></label>
                        <?= $form->text('category', '', ['placeholder' => 'nature']) ?>
                        <small class="text-muted d-block"><?= t('Used by LoremFlickr, Unsplash, Pexels, and Pixabay.') ?></small>
                    </div>
                    <div class="col-md-4 form-group mb-2">
                        <label for="seed" class="form-label"><?= t('Seed') ?></label>
                        <?= $form->text('seed', '', ['placeholder' => 'header-1']) ?>
                        <small class="text-muted d-block"><?= t('Lorem Picsum only: keeps the same photo across replacements.') ?></small>
                    </div>
                    <div class="col-md-4 form-group mb-2">
                        <label for="api_key" class="form-label"><?= t('API key') ?></label>
                        <?= $form->text('api_key', '', ['placeholder' => 'your key']) ?>
                        <small class="text-muted d-block"><?= t('Required for Unsplash, Pexels, and Pixabay. Get a free key from the provider\'s developer site.') ?></small>
                    </div>
                </div>
            </fieldset>

            <div class="ccm-dashboard-form-actions-wrapper">
                <div class="ccm-dashboard-form-actions">
                    <button type="submit" name="do" value="replace" class="btn btn-primary"><?= t('Replace Selected') ?></button>
                    <button type="submit" name="do" value="restore" class="btn btn-secondary"><?= t('Restore Selected from Backup') ?></button>
                </div>
            </div>
        </form>

        <script>
        (function () {
            var selectAll = document.getElementById('ir-select-all');
            if (!selectAll) { return; }
            selectAll.addEventListener('change', function () {
                var boxes = document.querySelectorAll('.ir-file-checkbox');
                for (var i = 0; i < boxes.length; i++) {
                    boxes[i].checked = selectAll.checked;
                }
            });
        })();
        </script>
    <?php endif; ?>

</div>
