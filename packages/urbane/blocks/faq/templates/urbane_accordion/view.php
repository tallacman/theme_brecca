<?php defined('C5_EXECUTE') or die('Access Denied.');
$linkCount = 1;
?>

<div id="accordion" class="ccm-faq-container">

    <?php if (count($rows) > 0)
    { ?>

      <?php foreach ($rows as $row)
    { ?>

<div class="faq-accordion-item"> <!--   open faq item  -->
  <h5 class="faq-accordion-header" data-bs-toggle="collapse" data-bs-target="#faq-<?php echo $bID . $linkCount; ?>">
    <?php echo $row['linkTitle']; ?>
  </h5>
  <div id="faq-<?php echo $bID . $linkCount; ?>" class="collapse" data-bs-parent="#accordion">
    <div class="faq-accordion-answer">
      <?php echo $row['description']; ?>
    </div>
  </div>
</div> <!--   closes faq item  -->
<?php ++$linkCount;
    }
  } else {
?>
    <div class="ccm-faq-block-links">
        <p><?php echo t('No Faq Entries Entered.'); ?></p>
    </div>
  <?php
  }
?>

</div> <!--   close accordion  -->
