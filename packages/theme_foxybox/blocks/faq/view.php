<?php defined('C5_EXECUTE') or die('Access Denied.');

$linkCount = 1;
$faqEntryCount = 1;
?>

<div class="ccm-faq-container container justify-space-between">
    <?php if ($rows !== []) { ?>
        <ul class="ccm-faq-block-links col-12 col-sm-4 no-bullets no-padding">
            <?php foreach ($rows as $row) { ?>
                <li><a href="#<?= h((string) $bID . $linkCount) ?>"><?= h($row['linkTitle']) ?></a></li>
                <?php
                ++$linkCount;
            } ?>
        </ul>
        <div class="ccm-faq-block-entries col-12 col-sm-7">
            <?php foreach ($rows as $row) { ?>
                <div class="faq-entry-content">
                    <a id="<?= h((string) $bID . $faqEntryCount) ?>"></a>
                    <h3><?= h($row['title']) ?></h3>
                    <?php echo $row['description']; ?>
                </div>
                <?php
                ++$faqEntryCount;
            } ?>
        </div>
    <?php
    } else {
    ?>
        <div class="ccm-faq-block-links">
            <p><?php echo t('No Faq Entries Entered.'); ?></p>
        </div>
    <?php
    }
    ?>
</div>
