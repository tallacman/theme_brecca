<?php

declare(strict_types=1);

namespace Concrete\Package\TallacmansIconHr\Block\TallacmansIconHr;

use Concrete\Core\Block\BlockController;
use Concrete\Core\Html\Service\FontAwesomeIcon;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends BlockController
{
    protected $btTable = 'btTallacmansIconHr';

    protected $btInterfaceWidth = 780;

    protected $btInterfaceHeight = 640;

    protected $btIgnorePageThemeGridFrameworkContainer = true;

    protected $btDefaultSet = 'basic';

    protected $pkg = 'tallacmans_icon_hr';

    public $icon;

    public $colorIcon;

    public $colorBackg;

    public $colorLine;

    public $postitionIcon;

    public $sizeIcon;

    public $iconPadding;

    public $hrStyle;

    public $heightLine;

    public $blockMarginTop;

    public $blockMarginBottom;

    public $dropShadowBlur;

    public $dropShadowColor;

    public $dropShadowOpacity;

    public function getBlockTypeDescription()
    {
        return t('A decorative horizontal rule with a centered Font Awesome icon.');
    }

    public function getBlockTypeName()
    {
        return t("Tallacman's Icon HR");
    }

    public function getSearchableContent()
    {
        return trim((string) ($this->icon ?? ''));
    }

    public function registerViewAssets($outputContent = '')
    {
        $this->requireAsset('css', 'font-awesome');
    }

    public function view()
    {
        $icon = $this->sanitizeIcon($this->icon ?? '');
        $iconTag = '';

        if ($icon !== '') {
            $iconTag = (string) FontAwesomeIcon::getFromClassNames($icon);
        }

        $this->set('icon', $icon);
        $this->set('iconTag', $iconTag);
        $this->set('colorIcon', $this->sanitizeColor($this->colorIcon ?? '', '#ff05c4'));
        $this->set('colorLine', $this->sanitizeColor($this->colorLine ?? '', '#333333'));
        $this->set('postitionIcon', $this->sanitizePosition($this->postitionIcon ?? '1'));
        $this->set('sizeIcon', $this->sanitizeIconSize($this->sizeIcon ?? 46));
        $this->set('iconPadding', $this->sanitizeIconPadding($this->iconPadding ?? 8));
        $this->set('hrStyle', $this->sanitizeHrStyle($this->hrStyle ?? '1'));
        $this->set('heightLine', $this->sanitizeLineThickness($this->heightLine ?? 1));
        $this->set('blockMarginTop', $this->sanitizeSpacing($this->blockMarginTop ?? 33));
        $this->set('blockMarginBottom', $this->sanitizeSpacing($this->blockMarginBottom ?? 33));
        $this->setDropShadowViewVars();

        $position = $this->sanitizePosition($this->postitionIcon ?? '1');
        $hrStyle = $this->sanitizeHrStyle($this->hrStyle ?? '1');

        $this->set('positionCss', $this->getPositionCssMap()[$position]);
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
        $args['icon'] = $this->sanitizeIcon($args['icon'] ?? '');
        $args['colorIcon'] = $this->sanitizeColor($args['colorIcon'] ?? '', '#ff05c4');
        $args['colorLine'] = $this->sanitizeColor($args['colorLine'] ?? '', '#333333');
        $args['postitionIcon'] = $this->sanitizePosition($args['postitionIcon'] ?? '1');
        $args['sizeIcon'] = $this->sanitizeIconSize($args['sizeIcon'] ?? 46);
        $args['iconPadding'] = $this->sanitizeIconPadding($args['iconPadding'] ?? 8);
        $args['hrStyle'] = $this->sanitizeHrStyle($args['hrStyle'] ?? '1');
        $args['heightLine'] = $this->sanitizeLineThickness($args['heightLine'] ?? 1);
        $args['blockMarginTop'] = $this->sanitizeSpacing($args['blockMarginTop'] ?? 33);
        $args['blockMarginBottom'] = $this->sanitizeSpacing($args['blockMarginBottom'] ?? 33);
        $args = $this->sanitizeDropShadowFields($args);

        return parent::save($args);
    }

    public function validate($data)
    {
        $e = $this->app->make('error');

        if (trim((string) ($data['icon'] ?? '')) === '') {
            $e->add(t('Please choose an icon.'));
        }

        foreach (['colorIcon', 'colorLine', 'dropShadowColor'] as $field) {
            if (!empty($data[$field]) && !$this->isValidColor($data[$field])) {
                $e->add(t('%s must be a valid hex color (for example, #333333).', t('Color values')));
                break;
            }
        }

        foreach (['sizeIcon', 'iconPadding', 'heightLine', 'blockMarginTop', 'blockMarginBottom', 'dropShadowBlur', 'dropShadowOpacity'] as $field) {
            if (isset($data[$field]) && $data[$field] !== '' && (!is_numeric($data[$field]) || (int) $data[$field] < 0)) {
                $e->add(t('Size and spacing values must be zero or a positive number.'));
                break;
            }
        }

        if (isset($data['sizeIcon']) && $data['sizeIcon'] !== '' && (int) $data['sizeIcon'] < 12) {
            $e->add(t('Icon size must be at least 12 pixels.'));
        }

        return $e;
    }

    protected function addEdit()
    {
        $block = $this->getBlockObject();
        $this->set('bID', $block ? (int) $block->getBlockID() : 0);
        $this->set('postitionIcon_options', $this->getPositionOptions());
        $this->set('hrStyle_options', $this->getHrStyleOptions());
        $this->prepareIconFieldsForForm();
        $this->set('iconChoices', $this->getIconChoices());
        $this->requireAsset('css', 'font-awesome');
    }

    protected function getIconChoices(): array
    {
        static $choices = null;

        if ($choices !== null) {
            return $choices;
        }

        $path = __DIR__ . DIRECTORY_SEPARATOR . 'icons.json';
        if (!is_readable($path)) {
            $choices = ['fas fa-star' => 'star'];

            return $choices;
        }

        $data = json_decode((string) file_get_contents($path), true);
        if (!is_array($data)) {
            $choices = ['fas fa-star' => 'star'];

            return $choices;
        }

        $choices = [];
        foreach (['solid', 'brands'] as $group) {
            foreach ($data[$group] ?? [] as $icon) {
                if (!empty($icon['value']) && !empty($icon['label'])) {
                    $choices[(string) $icon['value']] = (string) $icon['label'];
                }
            }
        }

        asort($choices, SORT_NATURAL | SORT_FLAG_CASE);

        if ($choices === []) {
            $choices = ['fas fa-star' => 'star'];
        }

        return $choices;
    }

    protected function setDefaults()
    {
        $this->set('icon', 'fas fa-star');
        $this->set('colorIcon', '#ff05c4');
        $this->set('colorLine', '#333333');
        $this->set('postitionIcon', '1');
        $this->set('sizeIcon', 46);
        $this->set('iconPadding', 8);
        $this->set('hrStyle', '1');
        $this->set('heightLine', 1);
        $this->set('blockMarginTop', 33);
        $this->set('blockMarginBottom', 33);
        $this->set('dropShadowBlur', 0);
        $this->set('dropShadowColor', '#333333');
        $this->set('dropShadowOpacity', 35);
    }

    protected function prepareIconFieldsForForm()
    {
        $this->set('icon', $this->sanitizeIcon($this->icon ?? ''));
        $this->set('sizeIcon', $this->sanitizeIconSize($this->sizeIcon ?? 46));
        $this->set('iconPadding', $this->sanitizeIconPadding($this->iconPadding ?? 8));
        $this->set('heightLine', $this->sanitizeLineThickness($this->heightLine ?? 1));
        $this->set('colorIcon', $this->sanitizeColor($this->colorIcon ?? '', '#ff05c4'));
        $this->set('colorLine', $this->sanitizeColor($this->colorLine ?? '', '#333333'));
        $this->set('blockMarginTop', $this->sanitizeSpacing($this->blockMarginTop ?? 33));
        $this->set('blockMarginBottom', $this->sanitizeSpacing($this->blockMarginBottom ?? 33));
        $this->set('dropShadowBlur', $this->sanitizeDropShadowBlur($this->dropShadowBlur ?? 0));
        $this->set('dropShadowColor', $this->sanitizeColor($this->dropShadowColor ?? '', '#333333'));
        $this->set('dropShadowOpacity', $this->sanitizeDropShadowOpacity($this->dropShadowOpacity ?? 35));
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

    protected function sanitizePosition($position)
    {
        $position = (string) $position;

        return array_key_exists($position, $this->getPositionOptions()) ? $position : '1';
    }

    protected function sanitizeHrStyle($hrStyle)
    {
        $hrStyle = (string) $hrStyle;

        return array_key_exists($hrStyle, $this->getHrStyleOptions()) ? $hrStyle : '1';
    }

    protected function sanitizeIcon($icon)
    {
        $icon = trim(strip_tags((string) $icon));

        if ($icon === '') {
            return '';
        }

        if (preg_match('/^fa-[a-z0-9-]+$/i', $icon)) {
            $icon = 'fas ' . $icon;
        }

        return substr($icon, 0, 255);
    }

    protected function sanitizeIconSize($sizeIcon)
    {
        $sizeIcon = (int) $sizeIcon;

        return $sizeIcon >= 12 ? min(120, $sizeIcon) : 46;
    }

    protected function sanitizeIconPadding($iconPadding)
    {
        if ($iconPadding === '' || $iconPadding === null) {
            return 8;
        }

        return max(0, min(50, (int) $iconPadding));
    }

    protected function sanitizeLineThickness($heightLine)
    {
        if ($heightLine === '' || $heightLine === null) {
            return 1;
        }

        return max(0, min(20, (int) $heightLine));
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
