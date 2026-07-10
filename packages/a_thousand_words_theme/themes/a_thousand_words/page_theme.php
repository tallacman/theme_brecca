<?php

declare(strict_types=1);

namespace Concrete\Package\AThousandWordsTheme\Theme\AThousandWords;

use Concrete\Core\Page\Theme\BedrockThemeTrait;
use Concrete\Core\Page\Theme\Theme;

defined('C5_EXECUTE') or die('Access Denied.');

class PageTheme extends Theme
{
    use BedrockThemeTrait;

    public function getThemeName()
    {
        return t('A Thousand Words');
    }

    public function getThemeDescription()
    {
        return t('A visual, card-based theme built on Concrete CMS Bedrock.');
    }

    public function getThemeResponsiveImageMap()
    {
        return [
            'xl' => '1200px',
            'lg' => '992px',
            'md' => '768px',
            'sm' => '576px',
            'xs' => '0',
        ];
    }
}
