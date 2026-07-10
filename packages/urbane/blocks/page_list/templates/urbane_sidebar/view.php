<?php
defined('C5_EXECUTE') or die("Access Denied.");
$c = Page::getCurrentPage();
if (is_object($c) && $c->isEditMode() && $controller->isBlockEmpty())
{
?>
    <div class="ccm-edit-mode-disabled-item"><?php echo t('Empty Page List Block.') ?></div>
    <?php
}
else
{
?>
<!--   lots of code stripped out - February 14, 2020  - tallacman  -->

    <div class="ccm-block-page-list-wrapper urbane-sidebar">

        <?php if (isset($pageListTitle) && $pageListTitle)
    {
?>
            <div class="ccm-block-page-list-header">
                <h5><?php echo h($pageListTitle) ?></h5>
            </div>
            <?php
    } ?>



        <div class="ccm-block-page-list-pages">

            <?php
    $includeEntryText = false;
    if ((isset($includeName) && $includeName) || (isset($includeDescription) && $includeDescription) || (isset($useButtonForLink) && $useButtonForLink))
    {
        $includeEntryText = true;
    }

    foreach ($pages as $page)
    {

        // Prepare data for each page being listed...
        $title = $page->getCollectionName();
        if ($page->getCollectionPointerExternalLink() != '')
        {
            $url = $page->getCollectionPointerExternalLink();
            if ($page->openCollectionPointerExternalLinkInNewWindow())
            {
                $target = '_blank';
            }
        }
        else
        {
            $url = $page->getCollectionLink();
            $target = $page->getAttribute('nav_target');
        }

?>
      <p>
        <a href="<?php echo h($url) ?>"

           target="<?php echo h($target) ?>"><?php echo h($title) ?>

        </a>
      </p>

      <?php
    } ?>
        </div><!-- end .ccm-block-page-list-pages -->

        <?php if (count($pages) == 0)
    { ?>
            <div class="ccm-block-page-list-no-pages"><?php echo h($noResultsMessage) ?></div>
        <?php
    } ?>

    </div><!-- end .ccm-block-page-list-wrapper -->


    <?php if ($showPagination)
    { ?>
        <?php echo $pagination; ?>
    <?php
    } ?>

    <?php
} ?>
