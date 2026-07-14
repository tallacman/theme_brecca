<?php

declare(strict_types=1);

namespace Concrete\Package\TallacmansCopyright\Block\TallacmansCopyright;

use Concrete\Core\Block\BlockController;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends BlockController
{
    protected const FONT_FAMILY_CUSTOM = '__custom__';

    protected $btTable = 'btTallacmansCopyright';

    protected $btInterfaceWidth = 520;

    protected $btInterfaceHeight = 860;

    protected $btDefaultSet = 'basic';

    protected $pkg = 'tallacmans_copyright';

    public $copyrightStart;

    public $copyrightHolder;

    public $fontFamily;

    public $fontFamilyCustom;

    public $fontSize;

    public $fontWeight;

    public $textColor;

    public $blockPaddingTop;

    public $blockPaddingBottom;

    public function getBlockTypeDescription()
    {
        return t('A customizable copyright notice for your site.');
    }

    public function getBlockTypeName()
    {
        return t('Tallacmans Copyright');
    }

    public function getSearchableContent()
    {
        return trim((string) ($this->copyrightStart ?? '') . ' ' . (string) ($this->copyrightHolder ?? ''));
    }

    public function view()
    {
        $this->set('copyrightStart', trim((string) ($this->copyrightStart ?? '')));
        $this->set('copyrightHolder', trim((string) ($this->copyrightHolder ?? '')));
        $this->set('fontFamily', $this->resolveFontFamily($this->fontFamily ?? '', $this->fontFamilyCustom ?? ''));
        $this->set('fontSize', $this->sanitizeFontSize($this->fontSize ?? 14));
        $this->set('fontWeight', $this->sanitizeFontWeight($this->fontWeight ?? '400'));
        $this->set('textColor', $this->sanitizeColor($this->textColor ?? ''));
        $this->set('blockPaddingTop', $this->sanitizeSpacing($this->blockPaddingTop ?? 0));
        $this->set('blockPaddingBottom', $this->sanitizeSpacing($this->blockPaddingBottom ?? 0));
    }

    public function add()
    {
        $this->addEdit();
        $this->set('copyrightStart', '');
        $this->set('copyrightHolder', '');
        $this->set('fontFamily', '');
        $this->set('fontFamilyCustom', '');
        $this->set('fontSize', 14);
        $this->set('fontWeight', '400');
        $this->set('textColor', '');
        $this->set('blockPaddingTop', 0);
        $this->set('blockPaddingBottom', 0);
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
        $args['copyrightStart'] = $this->sanitizeCopyrightStart($args['copyrightStart'] ?? '');
        $args['copyrightHolder'] = trim((string) ($args['copyrightHolder'] ?? ''));
        $args = $this->sanitizeFontFamilyFields($args);
        $args['fontSize'] = $this->sanitizeFontSize($args['fontSize'] ?? 14);
        $args['fontWeight'] = $this->sanitizeFontWeight($args['fontWeight'] ?? '400');
        $args['textColor'] = $this->sanitizeColor($args['textColor'] ?? '');
        $args['blockPaddingTop'] = $this->sanitizeSpacing($args['blockPaddingTop'] ?? 0);
        $args['blockPaddingBottom'] = $this->sanitizeSpacing($args['blockPaddingBottom'] ?? 0);

        return parent::save($args);
    }

    public function validate($data)
    {
        $e = $this->app->make('error');
        $thisYear = (int) date('Y');
        $copyrightStart = trim((string) ($data['copyrightStart'] ?? ''));

        if ($copyrightStart !== '') {
            if (!ctype_digit($copyrightStart)) {
                $e->add(t('The %s field has to be an integer.', t('Year copyright started')));
            } else {
                $startYear = (int) $copyrightStart;
                if ($startYear < 1900) {
                    $e->add(t('Year must be at least 1900.'));
                }
                if ($startYear > $thisYear) {
                    $e->add(t('Start year can\'t be in the future.'));
                }
            }
        }

        if (strlen(trim((string) ($data['copyrightHolder'] ?? ''))) >= 70) {
            $e->add(t('Copyright holder text must be shorter than 70 characters.'));
        }

        if (!empty($data['textColor']) && !$this->isValidColor($data['textColor'])) {
            $e->add(t('Text color must be a valid hex color (for example, #333333).'));
        }

        if (($data['fontFamily'] ?? '') === self::FONT_FAMILY_CUSTOM) {
            $customFont = trim((string) ($data['fontFamilyCustom'] ?? ''));
            if ($customFont === '') {
                $e->add(t('Please enter a custom font family or choose a preset.'));
            } elseif (!$this->isValidFontFamilyCustom($customFont)) {
                $e->add(t('Custom font family contains invalid characters.'));
            }
        }

        foreach (['blockPaddingTop', 'blockPaddingBottom'] as $field) {
            if (isset($data[$field]) && $data[$field] !== '' && (!is_numeric($data[$field]) || (int) $data[$field] < 0)) {
                $e->add(t('Spacing values must be zero or a positive number.'));
                break;
            }
        }

        if (isset($data['fontSize']) && $data['fontSize'] !== '' && (!is_numeric($data['fontSize']) || (int) $data['fontSize'] < 1)) {
            $e->add(t('Text size must be at least 1 pixel.'));
        }

        return $e;
    }

    protected function addEdit()
    {
        $this->set('identifier_getString', uniqid('tallacmans_copyright_', true));
        $this->set('fontFamily_options', $this->getFontFamilyOptions());
        $this->set('fontWeight_options', $this->getFontWeightOptions());
        $this->prepareFontFamilyFieldsForForm();
    }

    protected function getFontFamilyOptions()
    {
        $options = [
            '' => t('Inherit from theme'),
            '"Droid Sans", "Nunito Sans", Roboto, sans-serif' => t('Droid Sans / Nunito Sans'),
            'Roboto, sans-serif' => t('Roboto'),
            '"Helvetica Neue", Helvetica, Arial, sans-serif' => t('Helvetica / Arial'),
            'Georgia, "Times New Roman", Times, serif' => t('Georgia / Times'),
            '"Courier New", Courier, monospace' => t('Courier Monospace'),
            'system-ui, -apple-system, "Segoe UI", sans-serif' => t('System UI'),
            self::FONT_FAMILY_CUSTOM => t('Custom...'),
        ];

        return $options;
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

    protected function prepareFontFamilyFieldsForForm()
    {
        $fontFamily = (string) ($this->fontFamily ?? '');
        $fontFamilyCustom = (string) ($this->fontFamilyCustom ?? '');
        $options = $this->getFontFamilyOptions();

        if ($fontFamily === self::FONT_FAMILY_CUSTOM || ($fontFamily !== '' && !array_key_exists($fontFamily, $options))) {
            if ($fontFamily !== self::FONT_FAMILY_CUSTOM && $fontFamily !== '') {
                $fontFamilyCustom = $fontFamily;
            }
            $fontFamily = self::FONT_FAMILY_CUSTOM;
        }

        $this->set('fontFamily', $fontFamily);
        $this->set('fontFamilyCustom', $fontFamilyCustom);
    }

    protected function sanitizeFontFamilyFields(array $args)
    {
        $preset = (string) ($args['fontFamily'] ?? '');

        if ($preset === self::FONT_FAMILY_CUSTOM) {
            $args['fontFamily'] = self::FONT_FAMILY_CUSTOM;
            $args['fontFamilyCustom'] = $this->sanitizeFontFamilyCustom($args['fontFamilyCustom'] ?? '');
        } else {
            $args['fontFamily'] = $this->sanitizeFontFamilyPreset($preset);
            $args['fontFamilyCustom'] = '';
        }

        return $args;
    }

    protected function resolveFontFamily($fontFamily, $fontFamilyCustom)
    {
        if ($fontFamily === self::FONT_FAMILY_CUSTOM) {
            return $this->sanitizeFontFamilyCustom($fontFamilyCustom);
        }

        return $this->sanitizeFontFamilyPreset($fontFamily);
    }

    protected function sanitizeFontFamilyPreset($fontFamily)
    {
        $fontFamily = (string) $fontFamily;
        $options = $this->getFontFamilyOptions();
        unset($options[self::FONT_FAMILY_CUSTOM]);

        return array_key_exists($fontFamily, $options) ? $fontFamily : '';
    }

    protected function sanitizeFontFamilyCustom($fontFamilyCustom)
    {
        $fontFamilyCustom = trim(strip_tags((string) $fontFamilyCustom));

        if ($fontFamilyCustom === '') {
            return '';
        }

        if (!$this->isValidFontFamilyCustom($fontFamilyCustom)) {
            return '';
        }

        return substr($fontFamilyCustom, 0, 255);
    }

    protected function isValidFontFamilyCustom($fontFamilyCustom)
    {
        return (bool) preg_match('/^[\p{L}\p{N}\s,\'".\-()\/]+$/u', (string) $fontFamilyCustom);
    }

    protected function sanitizeCopyrightStart($copyrightStart)
    {
        $copyrightStart = trim((string) $copyrightStart);

        if ($copyrightStart === '') {
            return '';
        }

        if (!ctype_digit($copyrightStart)) {
            return '';
        }

        return (string) (int) $copyrightStart;
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

        return $fontSize > 0 ? $fontSize : 14;
    }

    protected function sanitizeSpacing($spacing)
    {
        if ($spacing === '' || $spacing === null) {
            return 0;
        }

        return max(0, (int) $spacing);
    }
}
