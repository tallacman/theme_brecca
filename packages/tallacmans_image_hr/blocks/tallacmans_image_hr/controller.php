<?php

declare(strict_types=1);

namespace Concrete\Package\TallacmansImageHr\Block\TallacmansImageHr;

use Concrete\Core\Block\BlockController;
use Concrete\Core\File\File;
use Concrete\Core\Support\Facade\Application;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends BlockController
{
    protected $btExportFileColumns = ['image'];

    protected $btTable = 'btTallacmansImageHr';

    protected $btInterfaceWidth = 780;

    protected $btInterfaceHeight = 640;

    protected $btIgnorePageThemeGridFrameworkContainer = true;

    protected $btDefaultSet = 'basic';

    protected $pkg = 'tallacmans_image_hr';

    public $image;

    public $sizeImage;

    public $postitionImage;

    public $paddingImage;

    public $borderImage;

    public $borderColor;

    public $colorBackg;

    public $hrStyle;

    public $heightLine;

    public $colorLine;

    public $blockMarginTop;

    public $blockMarginBottom;

    public $dropShadowBlur;

    public $dropShadowColor;

    public $dropShadowOpacity;

    public function getBlockTypeDescription()
    {
        return t('A decorative horizontal rule with a centered circular image.');
    }

    public function getBlockTypeName()
    {
        return t("Tallacman's Image HR");
    }

    public function getSearchableContent()
    {
        if ($this->image && ($file = File::getByID((int) $this->image)) && is_object($file)) {
            return trim((string) $file->getTitle());
        }

        return '';
    }

    public function view()
    {
        $file = null;
        if ($this->image && ($file = File::getByID((int) $this->image)) && is_object($file)) {
            $this->set('image', $file);
        } else {
            $this->set('image', false);
        }

        $sizeImage = $this->resolveImageSize($this->sizeImage ?? 110);
        $this->set('sizeImage', $sizeImage);
        $this->set('postitionImage', $this->sanitizePosition($this->postitionImage ?? '1'));
        $this->set('paddingImage', $this->resolvePadding($this->paddingImage ?? 0));
        $this->set('borderImage', $this->resolveBorderWidth($this->borderImage ?? 0));
        $this->set('borderColor', $this->sanitizeColor($this->borderColor ?? '', '#333333'));
        $this->set('colorBackg', $this->sanitizeColor($this->colorBackg ?? '', '#ffffff'));
        $this->set('colorLine', $this->sanitizeColor($this->colorLine ?? '', '#333333'));
        $this->set('hrStyle', $this->sanitizeHrStyle($this->hrStyle ?? '1'));
        $this->set('heightLine', $this->resolveLineThicknessForStyle($this->heightLine ?? 1, $this->hrStyle ?? '1'));
        $this->set('blockMarginTop', $this->sanitizeSpacing($this->blockMarginTop ?? $this->defaultMarginForSize($sizeImage)));
        $this->set('blockMarginBottom', $this->sanitizeSpacing($this->blockMarginBottom ?? $this->defaultMarginForSize($sizeImage)));
        $this->setDropShadowViewVars();

        $position = $this->sanitizePosition($this->postitionImage ?? '1');
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
        $args['image'] = (int) ($args['image'] ?? 0);
        $args['sizeImage'] = $this->sanitizeImageSize($args['sizeImage'] ?? 110);
        $args['postitionImage'] = $this->sanitizePosition($args['postitionImage'] ?? '1');
        $args['paddingImage'] = $this->sanitizePadding($args['paddingImage'] ?? 0);
        $args['borderImage'] = $this->sanitizeBorderWidth($args['borderImage'] ?? 0);
        $args['borderColor'] = $this->sanitizeColor($args['borderColor'] ?? '', '#333333');
        $args['colorBackg'] = $this->sanitizeColor($args['colorBackg'] ?? '', '#ffffff');
        $args['colorLine'] = $this->sanitizeColor($args['colorLine'] ?? '', '#333333');
        $args['hrStyle'] = $this->sanitizeHrStyle($args['hrStyle'] ?? '1');
        $args['heightLine'] = $this->resolveLineThicknessForStyle($args['heightLine'] ?? 1, $args['hrStyle']);
        $args['blockMarginTop'] = $this->sanitizeSpacing($args['blockMarginTop'] ?? $this->defaultMarginForSize($args['sizeImage']));
        $args['blockMarginBottom'] = $this->sanitizeSpacing($args['blockMarginBottom'] ?? $this->defaultMarginForSize($args['sizeImage']));
        $args = $this->sanitizeDropShadowFields($args);

        return parent::save($args);
    }

    public function validate($data)
    {
        $e = $this->app->make('error');

        if (empty($data['image'])) {
            $e->add(t('Please choose an image.'));
        }

        foreach (['borderColor', 'colorBackg', 'colorLine', 'dropShadowColor'] as $field) {
            if (!empty($data[$field]) && !$this->isValidColor($data[$field])) {
                $e->add(t('%s must be a valid hex color (for example, #333333).', t('Color values')));
                break;
            }
        }

        foreach (['sizeImage', 'paddingImage', 'borderImage', 'heightLine', 'blockMarginTop', 'blockMarginBottom', 'dropShadowBlur', 'dropShadowOpacity'] as $field) {
            if (isset($data[$field]) && $data[$field] !== '' && (!is_numeric($data[$field]) || (int) $data[$field] < 0)) {
                $e->add(t('Size and spacing values must be zero or a positive number.'));
                break;
            }
        }

        if (isset($data['sizeImage']) && $data['sizeImage'] !== '' && (int) $data['sizeImage'] < 1) {
            $e->add(t('Image size must be at least 1 pixel.'));
        }

        return $e;
    }

    protected function addEdit()
    {
        $this->set('identifier_getString', uniqid('tallacmans_image_hr_', true));
        $this->set('postitionImage_options', $this->getPositionOptions());
        $this->set('hrStyle_options', $this->getHrStyleOptions());
        $this->prepareImageFieldsForForm();
        $this->setPreviewImageUrl();
        $this->requireAsset('core/file-manager');
    }

    protected function setDefaults()
    {
        $this->set('image', 0);
        $this->set('sizeImage', 110);
        $this->set('postitionImage', '1');
        $this->set('paddingImage', 0);
        $this->set('borderImage', 0);
        $this->set('borderColor', '#333333');
        $this->set('colorBackg', '#ffffff');
        $this->set('colorLine', '#333333');
        $this->set('hrStyle', '1');
        $this->set('heightLine', 1);
        $this->set('blockMarginTop', 80);
        $this->set('blockMarginBottom', 80);
        $this->set('dropShadowBlur', 0);
        $this->set('dropShadowColor', '#333333');
        $this->set('dropShadowOpacity', 35);
        $this->set('previewImageUrl', '');
    }

    protected function prepareImageFieldsForForm()
    {
        $sizeImage = $this->resolveImageSize($this->sizeImage ?? 110);
        $this->set('sizeImage', $sizeImage);
        $this->set('paddingImage', $this->resolvePadding($this->paddingImage ?? 0));
        $this->set('borderImage', $this->resolveBorderWidth($this->borderImage ?? 0));
        $this->set('heightLine', $this->resolveLineThicknessForStyle($this->heightLine ?? 1, $this->hrStyle ?? '1'));
        $this->set('borderColor', $this->sanitizeColor($this->borderColor ?? '', '#333333'));
        $this->set('colorBackg', $this->sanitizeColor($this->colorBackg ?? '', '#ffffff'));
        $this->set('colorLine', $this->sanitizeColor($this->colorLine ?? '', '#333333'));
        $this->set('blockMarginTop', $this->sanitizeSpacing($this->blockMarginTop ?? $this->defaultMarginForSize($sizeImage)));
        $this->set('blockMarginBottom', $this->sanitizeSpacing($this->blockMarginBottom ?? $this->defaultMarginForSize($sizeImage)));
        $this->set('dropShadowBlur', $this->sanitizeDropShadowBlur($this->dropShadowBlur ?? 0));
        $this->set('dropShadowColor', $this->sanitizeColor($this->dropShadowColor ?? '', '#333333'));
        $this->set('dropShadowOpacity', $this->sanitizeDropShadowOpacity($this->dropShadowOpacity ?? 35));
    }

    protected function setPreviewImageUrl()
    {
        $previewImageUrl = '';

        if (!empty($this->image) && ($file = File::getByID((int) $this->image)) && is_object($file)) {
            $app = Application::getFacadeApplication();
            $thumbSize = $this->resolveImageSize($this->sizeImage ?? 110);
            $thumbnail = $app->make('helper/image')->getThumbnail($file, $thumbSize, $thumbSize, true);
            if ($thumbnail) {
                $previewImageUrl = (string) $thumbnail->src;
            }
        }

        $this->set('previewImageUrl', $previewImageUrl);
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

    protected function getLegacyImageSizeMap()
    {
        return [
            '1' => 45,
            '2' => 85,
            '3' => 110,
            '4' => 140,
            '5' => 175,
        ];
    }

    protected function getLegacyPaddingMap()
    {
        return [
            '' => 0,
            '1' => 0,
            '2' => 3,
            '3' => 5,
            '4' => 9,
            '5' => 16,
        ];
    }

    protected function getLegacyBorderMap()
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

    protected function getLegacyHeightLineMap()
    {
        return [
            '1' => 1,
            '2' => 2,
            '3' => 3,
            '4' => 4,
            '5' => 5,
        ];
    }

    protected function defaultMarginForSize($sizeImage)
    {
        return (int) round($this->sanitizeImageSize($sizeImage) / 2) + 25;
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

    protected function resolveImageSize($sizeImage)
    {
        $legacyMap = $this->getLegacyImageSizeMap();
        $value = (string) $sizeImage;

        if (array_key_exists($value, $legacyMap)) {
            return $legacyMap[$value];
        }

        return $this->sanitizeImageSize($sizeImage);
    }

    protected function resolvePadding($paddingImage)
    {
        return $this->sanitizePadding($paddingImage);
    }

    protected function resolveBorderWidth($borderImage)
    {
        return $this->sanitizeBorderWidth($borderImage);
    }

    protected function resolveLineThickness($heightLine)
    {
        $legacyMap = $this->getLegacyHeightLineMap();
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

    protected function sanitizeImageSize($sizeImage)
    {
        $sizeImage = (int) $sizeImage;

        return $sizeImage > 0 ? min(400, $sizeImage) : 110;
    }

    protected function sanitizePadding($paddingImage)
    {
        if ($paddingImage === '' || $paddingImage === null) {
            return 0;
        }

        return max(0, min(50, (int) $paddingImage));
    }

    protected function sanitizeBorderWidth($borderImage)
    {
        if ($borderImage === '' || $borderImage === null) {
            return 0;
        }

        return max(0, min(20, (int) $borderImage));
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
