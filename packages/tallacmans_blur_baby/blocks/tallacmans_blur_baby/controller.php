<?php

declare(strict_types=1);

namespace Concrete\Package\TallacmansBlurBaby\Block\TallacmansBlurBaby;

use Concrete\Core\Block\BlockController;
use Concrete\Core\File\File;
use Concrete\Core\Page\Page;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends BlockController
{
    protected $btTable = 'btTallacmansBlurBaby';

    protected $btInterfaceWidth = 600;

    protected $btInterfaceHeight = 860;

    protected $btIgnorePageThemeGridFrameworkContainer = true;

    protected $btDefaultSet = 'basic';

    protected $btCacheBlockRecord = true;

    protected $btCacheBlockOutput = true;

    protected $btCacheBlockOutputOnPost = true;

    protected $btCacheBlockOutputForRegisteredUsers = true;

    protected $btExportFileColumns = ['image'];

    protected $pkg = 'tallacmans_blur_baby';

    public $image;

    public $placement;

    public $imageWidth;

    public $blur;

    public $scale;

    public $height;

    public function getBlockTypeDescription()
    {
        return t('Show a featured image with a blurred background copy behind it.');
    }

    public function getBlockTypeName()
    {
        return t("Tallacman's Blur Baby");
    }

    public function getSearchableContent()
    {
        $file = $this->resolveImageFile($this->image ?? 0);

        return $file ? trim((string) $file->getTitle()) : '';
    }

    public function view()
    {
        $file = $this->resolveImageFile($this->image ?? 0);
        if (!$file) {
            $page = Page::getCurrentPage();
            $this->set('isEditMode', $page && !$page->isError() && $page->isEditMode());

            return;
        }

        $this->set('imageUrl', (string) $file->getURL());
        $this->set('imageAlt', trim((string) $file->getTitle()));
        $this->set('blurCss', $this->sanitizeBlur($this->blur ?? 15) . 'px');
        $this->set('scaleCss', (string) $this->sanitizeScale($this->scale ?? 1.4));
        $this->set('backgroundHeightCss', $this->sanitizeBackgroundHeight($this->height ?? 90) . '%');
        $this->set('imageWidthCss', $this->sanitizeImageWidthVw($this->imageWidth ?? 50) . 'vw');
        $this->set('placementClass', $this->getPlacementClass($this->placement ?? 'center'));
    }

    public function add()
    {
        $this->addEdit();
        $this->setDefaults();
    }

    public function edit()
    {
        if (trim((string) ($this->placement ?? '')) === '') {
            $this->set('placement', 'center');
        }

        $this->migrateLegacyImageWidth();

        $this->addEdit();
    }

    public function composer()
    {
        $this->edit();
    }

    public function save($args)
    {
        $args['image'] = (int) ($args['image'] ?? 0);
        $args['placement'] = $this->sanitizePlacement($args['placement'] ?? 'center');
        $args['imageWidth'] = (string) $this->sanitizeImageWidthVw($args['imageWidth'] ?? 50);
        $args['blur'] = (string) (int) $this->sanitizeBlur($args['blur'] ?? 15);
        $args['scale'] = (string) $this->sanitizeScale($args['scale'] ?? 1.4);
        $args['height'] = (string) (int) $this->sanitizeBackgroundHeight($args['height'] ?? 90);

        return parent::save($args);
    }

    public function validate($args)
    {
        $e = $this->app->make('error');

        if (empty($args['image']) || (int) $args['image'] <= 0) {
            $e->add(t('You must choose an image.'));
        } else {
            $file = $this->resolveImageFile($args['image']);
            if (!$file) {
                $e->add(t('The selected image could not be found.'));
            }
        }

        $scale = $args['scale'] ?? '';
        if ($scale === '' || !is_numeric($scale)) {
            $e->add(t('Scale must be a number between 1 and 5.'));
        } elseif ((float) $scale < 1 || (float) $scale > 5) {
            $e->add(t('Scale needs to be between 1 and 5.'));
        }

        $height = $args['height'] ?? '';
        if ($height === '' || !is_numeric($height)) {
            $e->add(t('Background height must be a number between 10 and 200.'));
        } elseif ((float) $height < 10 || (float) $height > 200) {
            $e->add(t('Background height must be between 10% and 200% of the image height.'));
        }

        $imageWidth = $args['imageWidth'] ?? '';
        if ($imageWidth === '' || !is_numeric($imageWidth)) {
            $e->add(t('Image width must be a number between 5 and 100.'));
        } elseif ((float) $imageWidth < 5 || (float) $imageWidth > 100) {
            $e->add(t('Image width must be between 5 and 100 vw.'));
        }

        $blur = $args['blur'] ?? '';
        if ($blur === '' || !is_numeric($blur)) {
            $e->add(t('Blur must be a number between 0 and 99.'));
        } elseif ((float) $blur < 0 || (float) $blur > 99) {
            $e->add(t('Blur must be between 0 and 99 pixels.'));
        }

        if (!array_key_exists($this->sanitizePlacement($args['placement'] ?? ''), $this->getPlacementOptions())) {
            $e->add(t('Please choose a valid image placement.'));
        }

        return $e;
    }

    protected function addEdit()
    {
        $this->set('placement_options', $this->getPlacementOptions());
        $this->set('previewImageUrl', $this->resolvePreviewImageUrl($this->image ?? 0));
        $this->set('imageFile', $this->resolveImageFile($this->image ?? 0));
    }

    protected function setDefaults()
    {
        $this->set('image', 0);
        $this->set('blur', 15);
        $this->set('scale', 1.4);
        $this->set('height', 90);
        $this->set('imageWidth', 50);
        $this->set('placement', 'center');
    }

    protected function migrateLegacyImageWidth()
    {
        $imageWidth = (float) ($this->imageWidth ?? 0);
        if ($imageWidth <= 100) {
            return;
        }

        // Legacy values were stored in pixels (~700px ≈ 50vw on a 1400px screen).
        $this->set('imageWidth', (int) round(min(100, max(5, $imageWidth / 14))));
    }

    protected function getPlacementClass(string $placement): string
    {
        $map = [
            'flex-start' => 'ttbb-placement-top',
            'center' => 'ttbb-placement-center',
            'flex-end' => 'ttbb-placement-bottom',
        ];

        $placement = $this->sanitizePlacement($placement);

        return $map[$placement] ?? 'ttbb-placement-center';
    }

    protected function getPlacementOptions()
    {
        return [
            'flex-start' => t('Align Top'),
            'center' => t('Center'),
            'flex-end' => t('Align Bottom'),
        ];
    }

    protected function resolveImageFile($imageId)
    {
        $imageId = (int) $imageId;
        if ($imageId <= 0) {
            return null;
        }

        $file = File::getByID($imageId);
        if (!$file || $file->isError()) {
            return null;
        }

        return $file;
    }

    protected function resolvePreviewImageUrl($imageId): string
    {
        $file = $this->resolveImageFile($imageId);

        return $file ? (string) $file->getURL() : '';
    }

    protected function sanitizeBlur($blur): float
    {
        if (!is_numeric($blur)) {
            return 15.0;
        }

        $blur = (float) $blur;

        if ($blur < 0) {
            return 0.0;
        }

        if ($blur > 99) {
            return 99.0;
        }

        return $blur;
    }

    protected function sanitizeScale($scale): float
    {
        if (!is_numeric($scale)) {
            return 1.4;
        }

        $scale = (float) $scale;

        if ($scale < 1) {
            return 1.0;
        }

        if ($scale > 5) {
            return 5.0;
        }

        return $scale;
    }

    protected function sanitizeBackgroundHeight($height): float
    {
        if (!is_numeric($height)) {
            return 90.0;
        }

        $height = (float) $height;

        if ($height < 10) {
            return 10.0;
        }

        if ($height > 200) {
            return 200.0;
        }

        return $height;
    }

    protected function sanitizeImageWidthVw($imageWidth): float
    {
        if (!is_numeric($imageWidth)) {
            return 50.0;
        }

        $imageWidth = (float) $imageWidth;

        if ($imageWidth > 100) {
            $imageWidth = min(100, max(5, $imageWidth / 14));
        }

        if ($imageWidth < 5) {
            return 5.0;
        }

        if ($imageWidth > 100) {
            return 100.0;
        }

        return $imageWidth;
    }

    protected function sanitizePlacement($placement): string
    {
        $placement = (string) $placement;

        return array_key_exists($placement, $this->getPlacementOptions()) ? $placement : 'center';
    }
}
