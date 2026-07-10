<?php

declare(strict_types=1);

namespace Concrete\Package\TallacmansBodaciousButtons\Block\TallacmansBodaciousButtons;

use Concrete\Core\Block\BlockController;
use Concrete\Core\File\File;
use Concrete\Core\Page\Page;
use Concrete\Core\Page\Theme\Theme;
use Concrete\Core\Permission\Checker as Permissions;
use Concrete\Core\StyleCustomizer\Skin\SkinInterface;
use Concrete\Package\TallacmansGoogleFonts\FontPickerHelper;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends BlockController
{
    protected $btTable = 'btTallacmansBodaciousButtons';

    protected $btInterfaceWidth = 920;

    protected $btInterfaceHeight = 720;

    protected $btCacheBlockRecord = true;

    protected $btCacheBlockOutput = true;

    protected $btCacheBlockOutputOnPost = true;

    protected $btCacheBlockOutputForRegisteredUsers = true;

    protected $pkg = 'tallacmans_bodacious_buttons';

    protected $btDefaultSet = 'basic';

    protected $btExportFileColumns = ['link_File'];

    protected $btExportPageColumns = ['link_Page'];

    public $link;

    public $link_Title;

    public $link_Page;

    public $link_URL;

    public $link_File;

    public $forceDownload;

    public $openIn;

    public $spacingTop;

    public $spacingRight;

    public $spacingBottom;

    public $spacingLeft;

    public $styleMode;

    public $buttonBackgroundColor;

    public $buttonTextColor;

    public $buttonHoverBackgroundColor;

    public $buttonHoverTextColor;

    public $fontFamily;

    public $fontWeight;

    public $fontSize;

    public $buttonBorderColor;

    public $buttonBorderWidth;

    public $buttonHoverBorderColor;

    public $buttonHoverBorderWidth;

    public function getBlockTypeDescription()
    {
        return t('Bodacious buttons for beautiful websites.');
    }

    public function getBlockTypeName()
    {
        return t("Tallacman's Bodacious Buttons");
    }

    public function view()
    {
        $linkUrl = null;
        $linkTitle = trim((string) ($this->link_Title ?? ''));

        switch ((string) ($this->link ?? '')) {
            case 'page':
                $page = Page::getByID((int) ($this->link_Page ?? 0));
                if ($page && !$page->isError() && !$page->isInTrash()) {
                    $linkUrl = (string) $page->getCollectionLink();
                    if ($linkTitle === '') {
                        $linkTitle = (string) $page->getCollectionName();
                    }
                }
                break;
            case 'url':
                $linkUrl = $this->normalizeExternalUrl($this->link_URL ?? '');
                if ($linkTitle === '') {
                    $linkTitle = $linkUrl;
                }
                break;
            case 'file':
                $file = File::getByID((int) ($this->link_File ?? 0));
                if ($file && !$file->isError()) {
                    $permissions = new Permissions($file);
                    if ($permissions->canViewFile()) {
                        $linkUrl = !empty($this->forceDownload)
                            ? (string) $file->getForceDownloadURL()
                            : (string) $file->getDownloadURL();
                        if ($linkTitle === '') {
                            $linkTitle = (string) $file->getTitle();
                        }
                    }
                }
                break;
        }

        $styleMode = $this->sanitizeStyleMode($this->styleMode ?? 'theme');

        $this->set('link_URL', $linkUrl);
        $this->set('link_Title', $linkTitle);
        $this->set('openInTarget', $this->getOpenInTarget($this->openIn ?? '1'));
        $this->set('spacingTop', $this->sanitizeSpacing($this->spacingTop ?? 0));
        $this->set('spacingRight', $this->sanitizeSpacing($this->spacingRight ?? 0));
        $this->set('spacingBottom', $this->sanitizeSpacing($this->spacingBottom ?? 8));
        $this->set('spacingLeft', $this->sanitizeSpacing($this->spacingLeft ?? 0));
        $this->set('styleMode', $styleMode);
        $this->set('buttonBackgroundColor', $this->sanitizeColor($this->buttonBackgroundColor ?? ''));
        $this->set('buttonTextColor', $this->sanitizeColor($this->buttonTextColor ?? ''));
        $this->set('buttonHoverBackgroundColor', $this->sanitizeColor($this->buttonHoverBackgroundColor ?? ''));
        $this->set('buttonHoverTextColor', $this->sanitizeColor($this->buttonHoverTextColor ?? ''));
        $this->set('fontFamily', $this->sanitizeFontFamily($this->fontFamily ?? ''));
        $this->set('fontWeight', $this->sanitizeFontWeight($this->fontWeight ?? '400'));
        $this->set('fontSize', $this->sanitizeFontSize($this->fontSize ?? 16));
        $this->set('buttonBorderColor', $this->sanitizeColor($this->buttonBorderColor ?? ''));
        $this->set('buttonBorderWidth', $this->sanitizeBorderWidth($this->buttonBorderWidth ?? 0));
        $this->set('buttonHoverBorderColor', $this->sanitizeColor($this->buttonHoverBorderColor ?? ''));
        $this->set('buttonHoverBorderWidth', $this->sanitizeBorderWidth($this->buttonHoverBorderWidth ?? 0));
    }

    public function add()
    {
        $this->addEdit();
        $this->set('link', 'page');
        $this->set('link_Title', '');
        $this->set('link_Page', 0);
        $this->set('link_URL', '');
        $this->set('link_File', 0);
        $this->set('forceDownload', 1);
        $this->set('openIn', '1');
        $this->set('spacingTop', 0);
        $this->set('spacingRight', 0);
        $this->set('spacingBottom', 8);
        $this->set('spacingLeft', 0);
        $this->set('styleMode', 'theme');
        $this->set('buttonBackgroundColor', '#0d6efd');
        $this->set('buttonTextColor', '#ffffff');
        $this->set('buttonHoverBackgroundColor', '#0b5ed7');
        $this->set('buttonHoverTextColor', '#ffffff');
        $this->set('fontFamily', '');
        $this->set('fontWeight', '400');
        $this->set('fontSize', 16);
        $this->set('buttonBorderColor', '#0d6efd');
        $this->set('buttonBorderWidth', 0);
        $this->set('buttonHoverBorderColor', '#0b5ed7');
        $this->set('buttonHoverBorderWidth', 0);
    }

    public function edit()
    {
        $this->addEdit();
    }

    public function composer()
    {
        $this->edit();
    }

    public function save($args)
    {
        $args['link'] = $this->sanitizeLinkType($args['link'] ?? 'page');
        $linkType = (string) $args['link'];

        switch ($linkType) {
            case 'page':
                $args['link_URL'] = '';
                $args['link_File'] = 0;
                $args['forceDownload'] = 0;
                break;
            case 'url':
                $args['link_Page'] = 0;
                $args['link_File'] = 0;
                $args['forceDownload'] = 0;
                $args['link_URL'] = $this->normalizeExternalUrl($args['link_URL'] ?? '');
                break;
            case 'file':
                $args['link_Page'] = 0;
                $args['link_URL'] = '';
                $args['link_File'] = (int) ($args['link_File'] ?? 0);
                $args['forceDownload'] = empty($args['forceDownload']) ? 0 : 1;
                break;
        }

        $args['link_Title'] = trim((string) ($args['link_Title'] ?? ''));
        $args['openIn'] = array_key_exists((string) ($args['openIn'] ?? ''), $this->getOpenInFormOptions()) ? (string) $args['openIn'] : '1';
        $args['spacingTop'] = $this->sanitizeSpacing($args['spacingTop'] ?? 0);
        $args['spacingRight'] = $this->sanitizeSpacing($args['spacingRight'] ?? 0);
        $args['spacingBottom'] = $this->sanitizeSpacing($args['spacingBottom'] ?? 8);
        $args['spacingLeft'] = $this->sanitizeSpacing($args['spacingLeft'] ?? 0);
        $args['styleMode'] = $this->sanitizeStyleMode($args['styleMode'] ?? 'theme');
        $args['buttonBackgroundColor'] = $this->sanitizeColor($args['buttonBackgroundColor'] ?? '');
        $args['buttonTextColor'] = $this->sanitizeColor($args['buttonTextColor'] ?? '');
        $args['buttonHoverBackgroundColor'] = $this->sanitizeColor($args['buttonHoverBackgroundColor'] ?? '');
        $args['buttonHoverTextColor'] = $this->sanitizeColor($args['buttonHoverTextColor'] ?? '');
        $args['fontFamily'] = $this->sanitizeFontFamily($args['fontFamily'] ?? '');
        $args['fontWeight'] = $this->sanitizeFontWeight($args['fontWeight'] ?? '400');
        $args['fontSize'] = $this->sanitizeFontSize($args['fontSize'] ?? 16);
        $args['buttonBorderColor'] = $this->sanitizeColor($args['buttonBorderColor'] ?? '');
        $args['buttonBorderWidth'] = $this->sanitizeBorderWidth($args['buttonBorderWidth'] ?? 0);
        $args['buttonHoverBorderColor'] = $this->sanitizeColor($args['buttonHoverBorderColor'] ?? '');
        $args['buttonHoverBorderWidth'] = $this->sanitizeBorderWidth($args['buttonHoverBorderWidth'] ?? 0);

        return parent::save($args);
    }

    public function validate($data)
    {
        $e = $this->app->make('error');

        if (strlen(trim((string) ($data['link_Title'] ?? ''))) >= 36) {
            $e->add(t('Your link title needs to be less than 35 characters.'));
        }

        $linkType = $this->sanitizeLinkType($data['link'] ?? '');

        switch ($linkType) {
            case 'page':
                $pageId = (int) ($data['link_Page'] ?? 0);
                $page = Page::getByID($pageId);
                if ($pageId < 1 || !$page || $page->isError()) {
                    $e->add(t('Please choose a page to link to.'));
                }
                break;
            case 'url':
                $url = $this->normalizeExternalUrl($data['link_URL'] ?? '');
                if ($url === '') {
                    $e->add(t('Please enter a URL.'));
                } elseif (strlen($url) >= 126) {
                    $e->add(t('Your link URL needs to be less than 125 characters.'));
                } elseif (!filter_var($url, FILTER_VALIDATE_URL)) {
                    $e->add(t('Please enter a valid URL.'));
                }
                break;
            case 'file':
                $fileId = (int) ($data['link_File'] ?? 0);
                $file = File::getByID($fileId);
                if ($fileId < 1 || !$file || $file->isError()) {
                    $e->add(t('Please choose a file to link to.'));
                }
                break;
        }

        foreach (['spacingTop', 'spacingRight', 'spacingBottom', 'spacingLeft'] as $field) {
            if (isset($data[$field]) && $data[$field] !== '' && (!is_numeric($data[$field]) || (int) $data[$field] < 0)) {
                $e->add(t('Spacing values must be zero or a positive number.'));
                break;
            }
        }

        if ($this->sanitizeStyleMode($data['styleMode'] ?? 'theme') === 'custom') {
            foreach ([
                'buttonBackgroundColor' => t('Button background color'),
                'buttonTextColor' => t('Button text color'),
                'buttonHoverBackgroundColor' => t('Button hover background color'),
                'buttonHoverTextColor' => t('Button hover text color'),
            ] as $field => $label) {
                $color = trim((string) ($data[$field] ?? ''));
                if ($color === '') {
                    $e->add(t('The %s field is required when styling the button yourself.', $label));
                } elseif (!$this->isValidColor($color)) {
                    $e->add(t('%s must be a valid hex color (for example, #333333).', $label));
                }
            }

            if (isset($data['fontSize']) && $data['fontSize'] !== '' && (!is_numeric($data['fontSize']) || (int) $data['fontSize'] < 1)) {
                $e->add(t('Font size must be at least 1 pixel.'));
            }

            foreach ([
                'buttonBorderColor' => t('Button border color'),
                'buttonHoverBorderColor' => t('Button hover border color'),
            ] as $field => $label) {
                $color = trim((string) ($data[$field] ?? ''));
                if ($color !== '' && !$this->isValidColor($color)) {
                    $e->add(t('%s must be a valid hex color (for example, #333333).', $label));
                }
            }

            foreach (['buttonBorderWidth', 'buttonHoverBorderWidth'] as $field) {
                if (isset($data[$field]) && $data[$field] !== '' && (!is_numeric($data[$field]) || (int) $data[$field] < 0)) {
                    $e->add(t('Border width values must be zero or a positive number.'));
                    break;
                }
            }
        }

        return $e;
    }

    protected function addEdit()
    {
        $this->set('identifier_getString', uniqid('tallacmans_bodacious_buttons_', true));
        $this->set('link_Options', $this->getLinkTypeOptions());
        $this->set('openIn_options', $this->getOpenInFormOptions());
        $this->set('styleMode_options', $this->getStyleModeOptions());
        $this->set('fontFamily_options', $this->getFontFamilyOptions());
        $this->set('fontWeight_options', $this->getFontWeightOptions());
    }

    protected function getLinkTypeOptions()
    {
        return [
            'page' => t('Page'),
            'url' => t('External URL'),
            'file' => t('File Download'),
        ];
    }

    protected function getStyleModeOptions()
    {
        return [
            'theme' => t('Use theme styling'),
            'custom' => t('Style this button myself'),
        ];
    }

    protected function getFontFamilyOptions()
    {
        $options = [
            '' => t('Inherit from theme'),
        ];

        $theme = Theme::getSiteTheme();
        if ($theme && $theme->getThemeCustomizer()) {
            $site = $this->app->make('site')->getSite();
            $skinIdentifier = (string) ($site->getThemeSkinIdentifier() ?: SkinInterface::SKIN_DEFAULT);
            $file = $theme->getThemeCustomizer()->getConfigurationFile();

            if ($file && is_readable($file)) {
                $xml = simplexml_load_file($file);
                if ($xml && $xml->webfonts) {
                    foreach ($xml->webfonts->preset as $presetNode) {
                        if ((string) $presetNode['identifier'] !== $skinIdentifier) {
                            continue;
                        }

                        foreach ($presetNode->font as $fontNode) {
                            $name = trim((string) $fontNode['name']);
                            if ($name === '') {
                                continue;
                            }

                            $cssValue = '"' . str_replace('"', '', $name) . '", sans-serif';
                            $options[$cssValue] = $name;
                        }

                        break;
                    }
                }
            }
        }

        if (count($options) === 1) {
            $options['"Helvetica Neue", Helvetica, Arial, sans-serif'] = t('Helvetica / Arial');
            $options['Georgia, "Times New Roman", Times, serif'] = t('Georgia / Times');
            $options['system-ui, -apple-system, "Segoe UI", sans-serif'] = t('System UI');
        }

        return FontPickerHelper::mergeFontFamilyOptions($options, $this->app);
    }

    protected function getFontWeightOptions()
    {
        return [
            '100' => t('Thin (100)'),
            '200' => t('Extra Light (200)'),
            '300' => t('Light (300)'),
            '400' => t('Normal (400)'),
            '500' => t('Medium (500)'),
            '600' => t('Semi Bold (600)'),
            '700' => t('Bold (700)'),
            '800' => t('Extra Bold (800)'),
            '900' => t('Black (900)'),
        ];
    }

    protected function sanitizeLinkType($linkType)
    {
        $linkType = (string) $linkType;

        return array_key_exists($linkType, $this->getLinkTypeOptions()) ? $linkType : 'page';
    }

    protected function sanitizeStyleMode($styleMode)
    {
        $styleMode = (string) $styleMode;

        return array_key_exists($styleMode, $this->getStyleModeOptions()) ? $styleMode : 'theme';
    }

    protected function sanitizeFontFamily($fontFamily)
    {
        $fontFamily = (string) $fontFamily;
        $options = $this->getFontFamilyOptions();

        return array_key_exists($fontFamily, $options) ? $fontFamily : '';
    }

    protected function normalizeExternalUrl($url)
    {
        $url = trim((string) $url);

        if ($url === '') {
            return '';
        }

        if (!preg_match('~^https?://~i', $url)) {
            $url = 'https://' . $url;
        }

        return substr($url, 0, 125);
    }

    protected function getOpenInFormOptions()
    {
        return [
            '1' => t('No. Open in this window.'),
            '2' => t('Yes. Open in new window.'),
        ];
    }

    protected function getOpenInTarget($openIn)
    {
        $targets = [
            '1' => '_self',
            '2' => '_blank',
        ];

        return $targets[(string) $openIn] ?? '_self';
    }

    protected function sanitizeColor($color)
    {
        $color = trim((string) $color);

        if ($color === '') {
            return '';
        }

        if ($color[0] !== '#') {
            $color = '#' . $color;
        }

        return $this->isValidColor($color) ? strtolower($color) : '';
    }

    protected function isValidColor($color)
    {
        return (bool) preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', (string) $color);
    }

    protected function sanitizeFontWeight($fontWeight)
    {
        $fontWeight = (string) $fontWeight;

        return array_key_exists($fontWeight, $this->getFontWeightOptions()) ? $fontWeight : '400';
    }

    protected function sanitizeFontSize($fontSize)
    {
        $fontSize = (int) $fontSize;

        return $fontSize > 0 ? $fontSize : 16;
    }

    protected function sanitizeBorderWidth($borderWidth)
    {
        if ($borderWidth === '' || $borderWidth === null) {
            return 0;
        }

        return max(0, min(20, (int) $borderWidth));
    }

    protected function sanitizeSpacing($spacing)
    {
        if ($spacing === '' || $spacing === null) {
            return 0;
        }

        return max(0, (int) $spacing);
    }
}
