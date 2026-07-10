<?php defined('C5_EXECUTE') or die("Access Denied."); ?>
<div class="ccm-block-testimonial-wrapper">
    <div class="ccm-block-testimonial container">
        <?php if ($image): ?>
            <div class="ccm-block-testimonial-image col-12 col-sm-4"><?php echo $image?></div>
        <?php endif; ?>

        <div class="ccm-block-testimonial-text">

            <div class="ccm-block-testimonial-name">
                <?php echo h($name)?>
            </div>

        <?php if ($position && $company && $companyURL): ?>
            <div class="ccm-block-testimonial-position">
                <?= sprintf('%s, <a href="%s">%s</a>', h($position), h($companyURL), h($company)) ?>
            </div>
        <?php endif; ?>

        <?php if ($position && !$company && $companyURL): ?>
            <div class="ccm-block-testimonial-position">
                <?= sprintf('<a href="%s">%s</a>', h($companyURL), h($position)) ?>
            </div>
        <?php endif; ?>

        <?php if ($position && $company && !$companyURL): ?>
            <div class="ccm-block-testimonial-position">
                <?= sprintf('%s, %s', h($position), h($company)) ?>
            </div>
        <?php endif; ?>

        <?php if ($position && !$company && !$companyURL): ?>
            <div class="ccm-block-testimonial-position">
                <?php echo h($position)?>
            </div>
        <?php endif; ?>

        <?php if ($paragraph): ?>
            <div class="ccm-block-testimonial-paragraph"><?php echo h($paragraph)?></div>
        <?php endif; ?>

        </div>

    </div>
</div>
