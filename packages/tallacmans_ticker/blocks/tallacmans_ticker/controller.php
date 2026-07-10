<?php

declare(strict_types=1);

namespace Concrete\Package\TallacmansTicker\Block\TallacmansTicker;

use Concrete\Core\Block\BlockController;
use Concrete\Core\Page\Page;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends BlockController
{
    protected $btTable = 'btTallacmansTicker';

    protected $btInterfaceWidth = 600;

    protected $btInterfaceHeight = 920;

    protected $btIgnorePageThemeGridFrameworkContainer = true;

    protected $btDefaultSet = 'multimedia';

    protected $btCacheBlockRecord = true;

    protected $btCacheBlockOutput = true;

    protected $btCacheBlockOutputOnPost = true;

    protected $btCacheBlockOutputForRegisteredUsers = true;

    protected $pkg = 'tallacmans_ticker';

    public $items;

    public $colorBack;

    public $colorText;

    public $speed;

    public $fontSize;

    public $sizeUnit;

    public $fontStyle;

    public $scrollDirection;

    public $pauseOnHover;

    public $textShadowColor;

    public $textShadowBlur;

    public $textShadowOffsetX;

    public $textShadowOffsetY;

    public $itemSpacing;

    /** @var string|null Legacy yes/no column from v2.1.0 */
    public $textShadow;

    public function getBlockTypeDescription()
    {
        return t('Scrolling text ticker for announcements and highlights.');
    }

    public function getBlockTypeName()
    {
        return t("Tallacman's Ticker");
    }

    public function getSearchableContent()
    {
        return $this->sanitizeItemsStored($this->items ?? '');
    }

    public function view()
    {
        $items = $this->parseItems($this->items ?? '');
        $fontSize = $this->sanitizeFontSize($this->fontSize ?? 16);
        $sizeUnit = $this->sanitizeSizeUnit($this->sizeUnit ?? 'px');

        $this->set('items', $items);
        $this->set('colorBack', $this->sanitizeColor($this->colorBack ?? '', 'rgb(0,0,0)'));
        $this->set('colorText', $this->sanitizeColor($this->colorText ?? '', 'rgb(255,255,255)'));
        $this->set('speed', $this->sanitizeSpeed($this->speed ?? 30));
        $this->set('fontSizeCss', $fontSize . $sizeUnit);
        $this->set('fontWeight', $this->getFontWeightCss($this->fontStyle ?? 'inherit'));
        $this->set('scrollDirection', $this->sanitizeScrollDirection($this->scrollDirection ?? 'ltr'));
        $this->set('pauseOnHover', $this->sanitizeYesNo($this->pauseOnHover ?? '1') === '1');
        $this->set('textShadowCss', $this->buildTextShadowCss(
            $this->textShadowBlur ?? null,
            $this->textShadowOffsetX ?? null,
            $this->textShadowOffsetY ?? null,
            $this->textShadowColor ?? null,
            $this->textShadow ?? null
        ));
        $itemSpacing = trim((string) ($this->itemSpacing ?? ''));
        $this->set('itemPaddingCss', $this->getItemPaddingCss($itemSpacing === '' ? 128 : $itemSpacing));

        $page = Page::getCurrentPage();
        $this->set('isEditMode', $page && !$page->isError() && $page->isEditMode());
    }

    public function add()
    {
        $this->addEdit();
        $this->setDefaults();
    }

    public function edit()
    {
        if ((float) ($this->speed ?? 0) <= 0) {
            $this->set('speed', 30);
        }

        if (trim((string) ($this->sizeUnit ?? '')) === '') {
            $this->set('sizeUnit', 'px');
        }

        if (trim((string) ($this->fontStyle ?? '')) === '') {
            $this->set('fontStyle', 'inherit');
        }

        if (trim((string) ($this->scrollDirection ?? '')) === '') {
            $this->set('scrollDirection', 'ltr');
        }

        if (trim((string) ($this->pauseOnHover ?? '')) === '') {
            $this->set('pauseOnHover', '1');
        }

        $this->migrateShadowFields();

        if (trim((string) ($this->itemSpacing ?? '')) === '') {
            $this->set('itemSpacing', 128);
        }

        $this->addEdit();
    }

    public function composer()
    {
        $this->edit();
    }

    public function save($args)
    {
        $args['items'] = $this->sanitizeItemsStored($args['items'] ?? '');
        $args['colorBack'] = $this->sanitizeColor($args['colorBack'] ?? '', 'rgb(0,0,0)');
        $args['colorText'] = $this->sanitizeColor($args['colorText'] ?? '', 'rgb(255,255,255)');
        $args['speed'] = (string) $this->sanitizeSpeed($args['speed'] ?? 30);
        $args['fontSize'] = (string) $this->sanitizeFontSize($args['fontSize'] ?? 16);
        $args['sizeUnit'] = $this->sanitizeSizeUnit($args['sizeUnit'] ?? 'px');
        $args['fontStyle'] = $this->sanitizeFontStyle($args['fontStyle'] ?? 'inherit');
        $args['scrollDirection'] = $this->sanitizeScrollDirection($args['scrollDirection'] ?? 'ltr');
        $args['pauseOnHover'] = $this->sanitizeYesNo($args['pauseOnHover'] ?? '1');
        $args['textShadowColor'] = $this->sanitizeColor($args['textShadowColor'] ?? '', 'rgba(0,0,0,0.55)');
        $args['textShadowBlur'] = (string) $this->sanitizeShadowBlur($args['textShadowBlur'] ?? 10);
        $args['textShadowOffsetX'] = (string) $this->sanitizeShadowOffset($args['textShadowOffsetX'] ?? 0);
        $args['textShadowOffsetY'] = (string) $this->sanitizeShadowOffset($args['textShadowOffsetY'] ?? 2);
        $args['itemSpacing'] = (string) $this->sanitizeItemSpacing($args['itemSpacing'] ?? 64);

        return parent::save($args);
    }

    public function validate($args)
    {
        $e = $this->app->make('error');

        if ($this->parseItems($args['items'] ?? '') === []) {
            $e->add(t('Add at least one ticker item (one per line).'));
        }

        $speed = $args['speed'] ?? '';
        if ($speed === '' || $speed === null) {
            $e->add(t('Speed cannot be blank. Please enter a number.'));
        } elseif (!is_numeric($speed)) {
            $e->add(t('Speed must be a number.'));
        } elseif ((float) $speed <= 0) {
            $e->add(t('Speed must be a positive number.'));
        } elseif ((float) $speed > 3600) {
            $e->add(t("Speed must be smaller than 3600 seconds. That's only once per hour."));
        }

        $fontSize = $args['fontSize'] ?? '';
        if ($fontSize === '' || $fontSize === null) {
            $e->add(t('Font size is required.'));
        } elseif (!is_numeric($fontSize)) {
            $e->add(t('Font size must be a number.'));
        } elseif ((float) $fontSize <= 0) {
            $e->add(t('Font size must be a positive number.'));
        } elseif (mb_strlen((string) $fontSize) > 12) {
            $e->add(t('Font size must be less than 12 digits.'));
        }

        foreach (['colorBack', 'colorText', 'textShadowColor'] as $field) {
            $value = trim((string) ($args[$field] ?? ''));
            if ($value !== '' && !$this->isValidColor($value)) {
                $e->add(t('Color values must be a valid hex or rgb/rgba color.'));
                break;
            }
        }

        if ($this->sanitizeShadowBlur($args['textShadowBlur'] ?? 0) > 0) {
            $shadowColor = trim((string) ($args['textShadowColor'] ?? ''));
            if ($shadowColor === '' || !$this->isValidColor($shadowColor)) {
                $e->add(t('Choose a valid text shadow color, or set blur to 0 to disable the shadow.'));
            }
        }

        if (!array_key_exists($this->sanitizeSizeUnit($args['sizeUnit'] ?? ''), $this->getSizeUnitOptions())) {
            $e->add(t('Please choose a valid font unit.'));
        }

        if (!array_key_exists($this->sanitizeFontStyle($args['fontStyle'] ?? ''), $this->getFontStyleOptions())) {
            $e->add(t('Please choose a valid font style.'));
        }

        if (!array_key_exists($this->sanitizeScrollDirection($args['scrollDirection'] ?? ''), $this->getScrollDirectionOptions())) {
            $e->add(t('Please choose a valid scroll direction.'));
        }

        $itemSpacing = $args['itemSpacing'] ?? '';
        if ($itemSpacing !== '' && $itemSpacing !== null && !is_numeric($itemSpacing)) {
            $e->add(t('Space between items must be a number.'));
        }

        return $e;
    }

    protected function addEdit()
    {
        $this->set('fontStyle_options', $this->getFontStyleOptions());
        $this->set('sizeUnit_options', $this->getSizeUnitOptions());
        $this->set('scrollDirection_options', $this->getScrollDirectionOptions());
        $this->set('yesNo_options', $this->getYesNoOptions());
    }

    protected function setDefaults()
    {
        $this->set('colorBack', 'rgb(0,0,0)');
        $this->set('colorText', 'rgb(255,255,255)');
        $this->set('speed', 30);
        $this->set('fontSize', 16);
        $this->set('items', '');
        $this->set('sizeUnit', 'px');
        $this->set('fontStyle', 'inherit');
        $this->set('scrollDirection', 'ltr');
        $this->set('pauseOnHover', '1');
        $this->set('textShadowColor', 'rgba(0,0,0,0.55)');
        $this->set('textShadowBlur', 10);
        $this->set('textShadowOffsetX', 0);
        $this->set('textShadowOffsetY', 2);
        $this->set('itemSpacing', 64);
    }

    protected function migrateShadowFields()
    {
        if (trim((string) ($this->textShadowBlur ?? '')) !== '') {
            return;
        }

        if (($this->textShadow ?? '') === '0') {
            $this->set('textShadowBlur', 0);
            $this->set('textShadowColor', 'rgba(0,0,0,0.55)');
            $this->set('textShadowOffsetX', 0);
            $this->set('textShadowOffsetY', 2);

            return;
        }

        if (trim((string) ($this->textShadowColor ?? '')) === '') {
            $this->set('textShadowColor', 'rgba(0,0,0,0.55)');
        }

        $this->set('textShadowBlur', 10);
        $this->set('textShadowOffsetX', (float) ($this->textShadowOffsetX ?? 0));
        $this->set('textShadowOffsetY', (float) ($this->textShadowOffsetY ?? 2));
    }

    protected function getItemPaddingCss($itemSpacing): string
    {
        $spacing = $this->sanitizeItemSpacing($itemSpacing ?? 64);
        $padding = $spacing / 2;

        return $padding . 'px';
    }

    protected function sanitizeItemSpacing($itemSpacing): float
    {
        if (!is_numeric($itemSpacing)) {
            return 64.0;
        }

        $itemSpacing = (float) $itemSpacing;

        if ($itemSpacing < 0) {
            return 0.0;
        }

        if ($itemSpacing > 240) {
            return 240.0;
        }

        return $itemSpacing;
    }

    protected function buildTextShadowCss($blur, $offsetX, $offsetY, $color, $legacyEnabled = null): string
    {
        if ($blur === null || $blur === '') {
            if (($legacyEnabled ?? '') === '0') {
                return '';
            }

            $blur = 10;
        }

        $blur = $this->sanitizeShadowBlur($blur);
        if ($blur <= 0) {
            return '';
        }

        $offsetX = $this->sanitizeShadowOffset($offsetX ?? 0);
        $offsetY = $this->sanitizeShadowOffset($offsetY ?? 2);
        $color = $this->sanitizeColor((string) ($color ?? ''), 'rgba(0,0,0,0.55)');

        return sprintf('%spx %spx %spx %s', $offsetX, $offsetY, $blur, $color);
    }

    protected function sanitizeShadowBlur($blur): float
    {
        if (!is_numeric($blur)) {
            return 0.0;
        }

        $blur = (float) $blur;

        if ($blur < 0) {
            return 0.0;
        }

        if ($blur > 100) {
            return 100.0;
        }

        return $blur;
    }

    protected function sanitizeShadowOffset($offset): float
    {
        if (!is_numeric($offset)) {
            return 0.0;
        }

        $offset = (float) $offset;

        if ($offset < -50) {
            return -50.0;
        }

        if ($offset > 50) {
            return 50.0;
        }

        return $offset;
    }

    protected function getFontStyleOptions()
    {
        return [
            'inherit' => t('Normal'),
            'bold' => t('Bold'),
        ];
    }

    protected function getSizeUnitOptions()
    {
        return [
            'px' => t('Pixels'),
            'em' => t('Em'),
            'rem' => t('Relative Em (rem)'),
        ];
    }

    protected function getScrollDirectionOptions()
    {
        return [
            'ltr' => t('Left (default)'),
            'rtl' => t('Right'),
        ];
    }

    protected function getYesNoOptions()
    {
        return [
            '1' => t('Yes'),
            '0' => t('No'),
        ];
    }

    protected function parseItems(string $items): array
    {
        $items = str_replace(["\r\n", "\r"], "\n", $items);
        $lines = explode("\n", $items);
        $parsed = [];

        foreach ($lines as $line) {
            $line = trim(strip_tags($line));
            if ($line !== '') {
                $parsed[] = $line;
            }
        }

        return $parsed;
    }

    protected function sanitizeItemsStored($items)
    {
        return implode("\n", $this->parseItems((string) $items));
    }

    protected function sanitizeYesNo($value): string
    {
        $value = (string) $value;

        return array_key_exists($value, $this->getYesNoOptions()) ? $value : '0';
    }

    protected function sanitizeScrollDirection($direction): string
    {
        $direction = (string) $direction;

        return array_key_exists($direction, $this->getScrollDirectionOptions()) ? $direction : 'ltr';
    }

    protected function sanitizeSpeed($speed)
    {
        if (!is_numeric($speed)) {
            return 30.0;
        }

        $speed = (float) $speed;

        if ($speed <= 0) {
            return 30.0;
        }

        if ($speed > 3600) {
            return 3600.0;
        }

        return $speed;
    }

    protected function sanitizeFontSize($fontSize)
    {
        if (!is_numeric($fontSize)) {
            return 16.0;
        }

        $fontSize = (float) $fontSize;

        if ($fontSize <= 0) {
            return 16.0;
        }

        if ($fontSize > 999) {
            return 999.0;
        }

        return $fontSize;
    }

    protected function sanitizeSizeUnit($sizeUnit)
    {
        $sizeUnit = (string) $sizeUnit;

        return array_key_exists($sizeUnit, $this->getSizeUnitOptions()) ? $sizeUnit : 'px';
    }

    protected function sanitizeFontStyle($fontStyle)
    {
        $fontStyle = (string) $fontStyle;

        return array_key_exists($fontStyle, $this->getFontStyleOptions()) ? $fontStyle : 'inherit';
    }

    protected function getFontWeightCss(string $fontStyle): string
    {
        return $this->sanitizeFontStyle($fontStyle) === 'bold' ? 'bold' : 'inherit';
    }

    protected function sanitizeColor($color, $fallback = '')
    {
        $color = trim((string) $color);

        if ($color === '') {
            return $fallback;
        }

        if ($this->isValidColor($color)) {
            return $color;
        }

        return $fallback;
    }

    protected function isValidColor($color)
    {
        $color = trim((string) $color);

        if ($color === 'transparent') {
            return true;
        }

        if (preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6}|[0-9a-fA-F]{8})$/', $color)) {
            return true;
        }

        if (preg_match('/^rgba?\(\s*\d+\s*,\s*\d+\s*,\s*\d+(?:\s*,\s*(?:0?\.\d+|1(?:\.0)?|\d+%))?\s*\)$/i', $color)) {
            return true;
        }

        return false;
    }
}
