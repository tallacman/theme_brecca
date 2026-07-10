<?php

namespace Concrete\Package\Urbane\Theme\Urbane;

use Concrete\Core\Page\Theme\BedrockThemeTrait;
use Concrete\Core\Page\Theme\Color\Color;
use Concrete\Core\Page\Theme\Color\ColorCollection;
use Concrete\Core\Page\Theme\Theme;

class PageTheme extends Theme
{
    use BedrockThemeTrait {
        getColorCollection as getBedrockColorCollection;
        registerAssets as registerBedrockAssets;
    }

    public function getThemeName()
    {
        return t('Urbane');
    }

    public function getThemeDescription()
    {
        return t('Nuanced and opinionated; elegant, noteworthy.');
    }

    public function registerAssets()
    {
        $this->registerBedrockAssets();
    }

    public function getThemeBlockClasses()
    {
        return [
            'page_list' => [
                'recent-blog-entry',
                'blog-entry-list',
                'page-list-with-buttons',
                'block-sidebar-wrapped',
            ],
            'next_previous' => ['block-sidebar-wrapped'],
            'share_this_page' => ['block-sidebar-wrapped'],
            'content' => [
                'block-sidebar-wrapped',
                'block-sidebar-padded',
            ],
            'date_navigation' => ['block-sidebar-padded'],
            'topic_list' => ['block-sidebar-wrapped'],
            'testimonial' => ['testimonial-bio'],
            'image' => [
                'image-left-tilt',
                'image-right-tilt',
                'image-circle',
            ],
        ];
    }

    public function getThemeDefaultBlockTemplates()
    {
        return [
            'calendar' => 'bootstrap_calendar.php',
        ];
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

    public function getThemeEditorClasses()
    {
        return [
            [
                'title' => t('Title Thin'),
                'element' => ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div'],
                'attributes' => ['class' => 'title-thin'],
            ],
            [
                'title' => t('Title Caps Bold'),
                'element' => ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div'],
                'attributes' => ['class' => 'title-caps-bold'],
            ],
            [
                'title' => t('Title Caps'),
                'element' => ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div'],
                'attributes' => ['class' => 'title-caps'],
            ],
            [
                'title' => t('Image Caption'),
                'element' => ['figcaption'],
                'attributes' => ['class' => 'image-caption'],
            ],
            [
                'title' => t('Lead'),
                'element' => ['p'],
                'attributes' => ['class' => 'lead'],
            ],
        ];
    }

    public function getThemeAreaLayoutPresets()
    {
        return [
            [
                'handle' => 'left_sidebar',
                'name' => 'Left Sidebar',
                'container' => '<div class="row"></div>',
                'columns' => [
                    '<div class="col-md-4"></div>',
                    '<div class="col-md-8"></div>',
                ],
            ],
            [
                'handle' => 'right_sidebar',
                'name' => 'Right Sidebar',
                'container' => '<div class="row"></div>',
                'columns' => [
                    '<div class="col-md-8"></div>',
                    '<div class="col-md-4"></div>',
                ],
            ],
        ];
    }

    public function getColorCollection(): ?ColorCollection
    {
        $collection = $this->getBedrockColorCollection();
        $collection->add(new Color('accent', t('Accent')));
        return $collection;
    }
}
