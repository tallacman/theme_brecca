<?php

declare(strict_types=1);

defined('C5_EXECUTE') or die('Access Denied.');

$blockScopeId = 'tallacmans-lead-feature-' . (int) $bID;
$height = (string) ($Height ?? '50vh');
$sizeStyles = 'height:' . $height . ';min-height:' . $height . ';max-height:' . $height;

$rootStyleAttr = ' style="' . h($sizeStyles) . '"';
$wrapperStyle = [
    $sizeStyles,
    'position:relative',
    'overflow:hidden',
];

if (!empty($Image)) {
    $wrapperStyle[] = 'background-image:url(' . (string) $Image->getURL() . ')';
    $wrapperStyle[] = 'background-attachment:' . (string) ($ScrollBehaviour ?? 'scroll');
    $wrapperStyle[] = 'background-position:' . (string) ($Align ?? 'center center');
    $wrapperStyle[] = 'background-size:cover';
}

$wrapperStyleAttr = ' style="' . h(implode(';', $wrapperStyle)) . '"';
$overlayStyleAttr = ' style="' . h(implode(';', [
    'position:absolute',
    'inset:0',
    'width:100%',
    'background-color:' . ($Overlay ?? 'rgba(0,0,0,0.1)'),
])) . '"';
$textLayerStyleAttr = ' style="' . h(implode(';', [
    'position:absolute',
    'inset:0',
    'width:100%',
    'height:100%',
    'overflow:hidden',
])) . '"';
$arrowLayerStyleAttr = $textLayerStyleAttr;
$chevronColor = (string) ($ChevronColor ?? '#ffffff');

$leadClasses = h(implode(' ', array_filter([
    'tlf-lead',
    'animated',
    (string) ($LeadAnimation ?? ''),
    (string) ($LeadSpeed ?? ''),
    (string) ($LeadDelay ?? ''),
])));

$secondaryClasses = h(implode(' ', array_filter([
    'tlf-secondary',
    'animated',
    (string) ($SecondaryAnimation ?? ''),
    (string) ($SecondarySpeed ?? ''),
    (string) ($SecondaryDelay ?? ''),
])));
?>

<div class="tallacmans-lead-feature" id="<?= h($blockScopeId) ?>"<?= $rootStyleAttr ?>>
    <div class="tlf-wrapper"<?= $wrapperStyleAttr ?>>
        <div class="tlf-overlay"<?= $overlayStyleAttr ?>></div>

        <?php if (!empty($LeadText) || !empty($SecondaryText)) { ?>
            <div class="tlf-text"<?= $textLayerStyleAttr ?>>
                <div class="tlf-inner-text">
                    <div class="tlf-text-stack">
                        <?php if (!empty($LeadText)) { ?>
                            <div class="<?= $leadClasses ?> tlf-text-anim">
                                <div class="tlf-lead">
                                    <?= $LeadText ?>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if (!empty($SecondaryText)) { ?>
                            <div class="<?= $secondaryClasses ?> tlf-text-anim">
                                <div class="tlf-secondary">
                                    <?= $SecondaryText ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php if (!empty($showArrow)) { ?>
            <div class="tlf-arrow"<?= $arrowLayerStyleAttr ?>>
                <a href="#<?= h((string) $bID) ?>" class="tlf-arrow-link" aria-label="<?= t('Scroll to content below') ?>" style="<?= h('color:' . $chevronColor) ?>">
                    <svg class="tlf-chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="48" height="48" aria-hidden="true" focusable="false">
                        <path fill="currentColor" d="M7.41 8.59 12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z"/>
                    </svg>
                </a>
            </div>
        <?php } ?>
    </div>

    <a id="<?= h((string) $bID) ?>" class="tlf-scroll-target" aria-hidden="true"></a>
</div>

<?php if (!empty($showArrow)) { ?>
<script>
(function () {
    var block = document.getElementById(<?= json_encode($blockScopeId) ?>);
    if (!block) {
        return;
    }

    var link = block.querySelector('.tlf-arrow-link');
    if (!link) {
        return;
    }

    link.addEventListener('click', function (event) {
        var href = link.getAttribute('href');
        if (!href || href.charAt(0) !== '#') {
            return;
        }

        var target = document.getElementById(href.slice(1));
        if (!target) {
            return;
        }

        event.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
})();
</script>
<?php } ?>
