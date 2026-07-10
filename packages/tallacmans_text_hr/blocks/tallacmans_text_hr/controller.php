<?php

declare(strict_types=1);

namespace Concrete\Package\TallacmansTextHr\Block\TallacmansTextHr;

use Concrete\Core\Block\BlockController;
use Concrete\Package\TallacmansGoogleFonts\FontPickerHelper;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends BlockController
{
    protected const FONT_FAMILY_CUSTOM = '__custom__';

    protected $btTable = 'btTallacmansTextHr';

    protected $btInterfaceWidth = 780;

    protected $btInterfaceHeight = 640;

    protected $btIgnorePageThemeGridFrameworkContainer = true;

    protected $btDefaultSet = 'basic';

    protected $pkg = 'tallacmans_text_hr';

    public $textDisplay;

    public $postitionText;

    public $textColor;

    public $colorLine;

    public $colorBackg;

    public $textBorder;

    public $textBorderShape;

    public $hrStyle;

    public $heightLine;

    public $fontFamily;

    public $fontFamilyCustom;

    public $fontSize;

    public $fontWeight;

    public $letterSpacing;

    public $blockMarginTop;

    public $blockMarginBottom;

    public $textSize;

    public $textLeading;

    public $textWeight;

    public $dropShadowBlur;

    public $dropShadowColor;

    public $dropShadowOpacity;

    public function getBlockTypeDescription()
    {
        return t('A decorative horizontal rule with centered text and customizable typography.');
    }

    public function getBlockTypeName()
    {
        return t("Tallacman's Text HR");
    }

    public function getSearchableContent()
    {
        return trim((string) ($this->textDisplay ?? ''));
    }

    public function view()
    {
        $this->set('textDisplay', trim((string) ($this->textDisplay ?? '')));
        $this->set('postitionText', $this->sanitizePosition($this->postitionText ?? '1'));
        $this->set('textColor', $this->sanitizeColor($this->textColor ?? '', '#333333'));
        $this->set('colorLine', $this->sanitizeColor($this->colorLine ?? '', '#333333'));
        $this->set('colorBackg', $this->sanitizeColor($this->colorBackg ?? '', '#ffffff'));
        $this->set('textBorder', $this->resolveTextBorderWidth($this->textBorder ?? 0));
        $this->set('textBorderShape', $this->sanitizeTextBorderShape($this->textBorderShape ?? '1'));
        $this->set('hrStyle', $this->sanitizeHrStyle($this->hrStyle ?? '1'));
        $this->set('heightLine', $this->resolveLineThicknessForStyle($this->heightLine ?? 1, $this->hrStyle ?? '1'));
        $this->set('fontFamily', $this->resolveFontFamily($this->fontFamily ?? '', $this->fontFamilyCustom ?? ''));
        $this->set('fontSize', $this->resolveFontSize($this->fontSize ?? 0, $this->textSize ?? ''));
        $this->set('fontWeight', $this->resolveFontWeight($this->fontWeight ?? '', $this->textWeight ?? ''));
        $this->set('letterSpacing', $this->resolveLetterSpacing($this->letterSpacing ?? '', $this->textLeading ?? ''));
        $this->set('blockMarginTop', $this->sanitizeSpacing($this->blockMarginTop ?? 33));
        $this->set('blockMarginBottom', $this->sanitizeSpacing($this->blockMarginBottom ?? 33));
        $this->setDropShadowViewVars();

        $position = $this->sanitizePosition($this->postitionText ?? '1');
        $textBorderShape = $this->sanitizeTextBorderShape($this->textBorderShape ?? '1');
        $hrStyle = $this->sanitizeHrStyle($this->hrStyle ?? '1');

        $this->set('positionCss', $this->getPositionCssMap()[$position]);
        $this->set('textBorderShapeCss', $this->getTextBorderShapeCssMap()[$textBorderShape]);
        $this->set('hrStyleCss', $this->getHrStyleCssMap()[$hrStyle]);
    }

    public function add()
    {
        $this->addEdit();
        $this->setDefaults();
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
        $args['textDisplay'] = trim(strip_tags((string) ($args['textDisplay'] ?? '')));
        $args['postitionText'] = $this->sanitizePosition($args['postitionText'] ?? '1');
        $args['textColor'] = $this->sanitizeColor($args['textColor'] ?? '', '#333333');
        $args['colorLine'] = $this->sanitizeColor($args['colorLine'] ?? '', '#333333');
        $args['colorBackg'] = $this->sanitizeColor($args['colorBackg'] ?? '', '#ffffff');
        $args['textBorder'] = $this->sanitizeTextBorderWidth($args['textBorder'] ?? 0);
        $args['textBorderShape'] = $this->sanitizeTextBorderShape($args['textBorderShape'] ?? '1');
        $args['hrStyle'] = $this->sanitizeHrStyle($args['hrStyle'] ?? '1');
        $args['heightLine'] = $this->resolveLineThicknessForStyle($args['heightLine'] ?? 1, $args['hrStyle']);
        $args = $this->sanitizeFontFamilyFields($args);
        $args['fontSize'] = $this->sanitizeFontSize($args['fontSize'] ?? 16);
        $args['fontWeight'] = $this->sanitizeFontWeight($args['fontWeight'] ?? '400');
        $args['letterSpacing'] = $this->sanitizeLetterSpacing($args['letterSpacing'] ?? '0');
        $args['blockMarginTop'] = $this->sanitizeSpacing($args['blockMarginTop'] ?? 33);
        $args['blockMarginBottom'] = $this->sanitizeSpacing($args['blockMarginBottom'] ?? 33);
        $args = $this->sanitizeDropShadowFields($args);

        return parent::save($args);
    }

    public function validate($data)
    {
        $e = $this->app->make('error');

        if (strlen(trim((string) ($data['textDisplay'] ?? ''))) > 255) {
            $e->add(t('Display text must be 255 characters or fewer.'));
        }

        foreach (['textColor', 'colorLine', 'colorBackg', 'dropShadowColor'] as $field) {
            if (!empty($data[$field]) && !$this->isValidColor($data[$field])) {
                $e->add(t('%s must be a valid hex color (for example, #333333).', t('Color values')));
                break;
            }
        }

        if (($data['fontFamily'] ?? '') === self::FONT_FAMILY_CUSTOM) {
            $customFont = trim((string) ($data['fontFamilyCustom'] ?? ''));
            if ($customFont === '') {
                $e->add(t('Please enter a custom font family or choose a preset.'));
            } elseif (!$this->isValidFontFamilyCustom($customFont)) {
                $e->add(t('Custom font family contains invalid characters.'));
            }
        }

        if (isset($data['fontSize']) && $data['fontSize'] !== '' && (!is_numeric($data['fontSize']) || (int) $data['fontSize'] < 1)) {
            $e->add(t('Font size must be at least 1 pixel.'));
        }

        if (isset($data['letterSpacing']) && $data['letterSpacing'] !== '' && !is_numeric($data['letterSpacing'])) {
            $e->add(t('Letter spacing must be a number.'));
        }

        foreach (['blockMarginTop', 'blockMarginBottom', 'heightLine', 'textBorder', 'dropShadowBlur', 'dropShadowOpacity'] as $field) {
            if (isset($data[$field]) && $data[$field] !== '' && (!is_numeric($data[$field]) || (int) $data[$field] < 0)) {
                $e->add(t('Spacing and size values must be zero or a positive number.'));
                break;
            }
        }

        return $e;
    }

    protected function addEdit()
    {
        $this->set('identifier_getString', uniqid('tallacmans_text_hr_', true));
        $this->set('postitionText_options', $this->getPositionOptions());
        $this->set('textBorderShape_options', $this->getTextBorderShapeOptions());
        $this->set('hrStyle_options', $this->getHrStyleOptions());
        $this->set('fontFamily_options', $this->getFontFamilyOptions());
        $this->set('fontWeight_options', $this->getFontWeightOptions());
        $this->prepareFontFamilyFieldsForForm();
        $this->prepareLineFieldsForForm();
    }

    protected function setDefaults()
    {
        $this->set('textDisplay', '');
        $this->set('postitionText', '1');
        $this->set('textColor', '#333333');
        $this->set('colorLine', '#333333');
        $this->set('colorBackg', '#ffffff');
        $this->set('textBorder', 0);
        $this->set('textBorderShape', '1');
        $this->set('hrStyle', '1');
        $this->set('heightLine', 1);
        $this->set('fontFamily', '');
        $this->set('fontFamilyCustom', '');
        $this->set('fontSize', 16);
        $this->set('fontWeight', '400');
        $this->set('letterSpacing', '0');
        $this->set('blockMarginTop', 33);
        $this->set('blockMarginBottom', 33);
        $this->set('dropShadowBlur', 0);
        $this->set('dropShadowColor', '#333333');
        $this->set('dropShadowOpacity', 35);
    }

    protected function getPositionOptions()
    {
        return [
            '1' => t('Center'),
            '2' => t('Left'),
            '3' => t('Right'),
        ];
    }

    protected function getPositionCssMap()
    {
        return [
            '1' => 'center',
            '2' => 'flex-start',
            '3' => 'flex-end',
        ];
    }

    protected function getLegacyTextBorderPresetMap()
    {
        return [
            '1' => 0,
            '2' => 1,
            '3' => 2,
            '4' => 3,
            '5' => 4,
            '6' => 5,
        ];
    }

    protected function getLegacyHeightLinePresetMap()
    {
        return [
            '1' => 1,
            '2' => 2,
            '3' => 3,
            '4' => 4,
            '5' => 5,
        ];
    }

    protected function prepareLineFieldsForForm()
    {
        $this->set('heightLine', $this->resolveLineThicknessForStyle($this->heightLine ?? 1, $this->hrStyle ?? '1'));
        $this->set('textBorder', $this->resolveTextBorderWidth($this->textBorder ?? 0));
        $this->set('dropShadowBlur', $this->sanitizeDropShadowBlur($this->dropShadowBlur ?? 0));
        $this->set('dropShadowColor', $this->sanitizeColor($this->dropShadowColor ?? '', '#333333'));
        $this->set('dropShadowOpacity', $this->sanitizeDropShadowOpacity($this->dropShadowOpacity ?? 35));
    }

    protected function getTextBorderShapeOptions()
    {
        return [
            '1' => t('Square'),
            '2' => t('Rounded'),
            '3' => t('Pill'),
        ];
    }

    protected function getTextBorderShapeCssMap()
    {
        return [
            '1' => '0',
            '2' => '3px',
            '3' => '100px',
        ];
    }

    protected function getHrStyleOptions()
    {
        return [
            '1' => t('Solid'),
            '2' => t('Dashed'),
            '3' => t('Dotted'),
            '4' => t('Double'),
        ];
    }

    protected function getHrStyleCssMap()
    {
        return [
            '1' => 'solid',
            '2' => 'dashed',
            '3' => 'dotted',
            '4' => 'double',
        ];
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

    protected function getLegacyFontSizeMap()
    {
        return [
            '1' => 16,
            '2' => 14,
            '3' => 19,
            '4' => 35,
            '5' => 48,
        ];
    }

    protected function getLegacyFontWeightMap()
    {
        return [
            '1' => '400',
            '2' => '300',
            '3' => '600',
            '4' => '700',
        ];
    }

    protected function getLegacyLetterSpacingMap()
    {
        return [
            '1' => '0',
            '2' => '-0.5',
            '3' => '1',
            '4' => '3',
            '5' => '5',
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

    protected function sanitizePosition($position)
    {
        $position = (string) $position;

        return array_key_exists($position, $this->getPositionOptions()) ? $position : '1';
    }

    protected function resolveLineThickness($heightLine)
    {
        $legacyMap = $this->getLegacyHeightLinePresetMap();
        $value = (string) $heightLine;

        if (array_key_exists($value, $legacyMap)) {
            return $legacyMap[$value];
        }

        return $this->sanitizeLineThickness($heightLine);
    }

    protected function getDoubleLineMinimumThickness(): int
    {
        return 3;
    }

    protected function isDoubleHrStyle($hrStyle): bool
    {
        return $this->sanitizeHrStyle($hrStyle) === '4';
    }

    protected function resolveLineThicknessForStyle($heightLine, $hrStyle)
    {
        $thickness = $this->resolveLineThickness($heightLine);

        if ($this->isDoubleHrStyle($hrStyle)) {
            return max($this->getDoubleLineMinimumThickness(), $thickness);
        }

        return $thickness;
    }

    protected function resolveTextBorderWidth($textBorder)
    {
        $legacyMap = $this->getLegacyTextBorderPresetMap();
        $value = (string) $textBorder;

        if (array_key_exists($value, $legacyMap)) {
            return $legacyMap[$value];
        }

        return $this->sanitizeTextBorderWidth($textBorder);
    }

    protected function sanitizeLineThickness($heightLine)
    {
        if ($heightLine === '' || $heightLine === null) {
            return 1;
        }

        return max(0, min(20, (int) $heightLine));
    }

    protected function sanitizeTextBorderWidth($textBorder)
    {
        if ($textBorder === '' || $textBorder === null) {
            return 0;
        }

        return max(0, min(20, (int) $textBorder));
    }

    protected function sanitizeTextBorderShape($textBorderShape)
    {
        $textBorderShape = (string) $textBorderShape;

        return array_key_exists($textBorderShape, $this->getTextBorderShapeOptions()) ? $textBorderShape : '1';
    }

    protected function sanitizeHrStyle($hrStyle)
    {
        $hrStyle = (string) $hrStyle;

        return array_key_exists($hrStyle, $this->getHrStyleOptions()) ? $hrStyle : '1';
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

    protected function resolveFontSize($fontSize, $legacyTextSize)
    {
        $fontSize = (int) $fontSize;
        if ($fontSize > 0) {
            return $fontSize;
        }

        $legacyTextSize = (string) $legacyTextSize;
        $map = $this->getLegacyFontSizeMap();

        return $map[$legacyTextSize] ?? 16;
    }

    protected function resolveFontWeight($fontWeight, $legacyTextWeight)
    {
        $fontWeight = (string) $fontWeight;
        if ($fontWeight !== '' && array_key_exists($fontWeight, $this->getFontWeightOptions())) {
            return $fontWeight;
        }

        $legacyTextWeight = (string) $legacyTextWeight;
        $map = $this->getLegacyFontWeightMap();

        return $map[$legacyTextWeight] ?? '400';
    }

    protected function resolveLetterSpacing($letterSpacing, $legacyTextLeading)
    {
        if ($letterSpacing !== '' && $letterSpacing !== null) {
            return $this->sanitizeLetterSpacing($letterSpacing);
        }

        $legacyTextLeading = (string) $legacyTextLeading;
        $map = $this->getLegacyLetterSpacingMap();

        return $map[$legacyTextLeading] ?? '0';
    }

    protected function sanitizeColor($color, $fallback = '')
    {
        $color = trim((string) $color);

        if ($color === '') {
            return $fallback;
        }

        if ($color[0] !== '#') {
            $color = '#' . $color;
        }

        return $this->isValidColor($color) ? strtolower($color) : $fallback;
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

    protected function sanitizeLetterSpacing($letterSpacing)
    {
        if ($letterSpacing === '' || $letterSpacing === null) {
            return '0';
        }

        if (!is_numeric($letterSpacing)) {
            return '0';
        }

        return (string) (float) $letterSpacing;
    }

    protected function sanitizeSpacing($spacing)
    {
        if ($spacing === '' || $spacing === null) {
            return 0;
        }

        return max(0, (int) $spacing);
    }

    protected function setDropShadowViewVars()
    {
        $dropShadowValue = $this->buildDropShadowValue(
            $this->dropShadowBlur ?? 0,
            $this->dropShadowColor ?? '#333333',
            $this->dropShadowOpacity ?? 35
        );

        $this->set('dropShadowValue', $dropShadowValue);
        $this->set('hasDropShadow', $dropShadowValue !== '');
    }

    protected function sanitizeDropShadowFields(array $args)
    {
        $args['dropShadowBlur'] = $this->sanitizeDropShadowBlur($args['dropShadowBlur'] ?? 0);
        $args['dropShadowColor'] = $this->sanitizeColor($args['dropShadowColor'] ?? '', '#333333');
        $args['dropShadowOpacity'] = $this->sanitizeDropShadowOpacity($args['dropShadowOpacity'] ?? 35);

        return $args;
    }

    protected function buildDropShadowValue($blur, $color, $opacity)
    {
        $blur = $this->sanitizeDropShadowBlur($blur);
        if ($blur <= 0) {
            return '';
        }

        $color = $this->sanitizeColor($color, '#333333');
        $opacityValue = $this->sanitizeDropShadowOpacity($opacity) / 100;
        [$r, $g, $b] = $this->hexColorToRgb($color);

        return sprintf(
            '0 0 %dpx rgba(%d,%d,%d,%s)',
            $blur,
            $r,
            $g,
            $b,
            rtrim(rtrim(sprintf('%.2f', $opacityValue), '0'), '.')
        );
    }

    protected function hexColorToRgb($hex)
    {
        $hex = ltrim((string) $hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];
    }

    protected function sanitizeDropShadowBlur($blur)
    {
        if ($blur === '' || $blur === null) {
            return 0;
        }

        return max(0, min(50, (int) $blur));
    }

    protected function sanitizeDropShadowOpacity($opacity)
    {
        if ($opacity === '' || $opacity === null) {
            return 35;
        }

        return max(0, min(100, (int) $opacity));
    }
}
