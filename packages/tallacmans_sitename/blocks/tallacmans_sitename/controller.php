<?php

namespace Concrete\Package\TallacmansSitename\Block\TallacmansSitename;

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Block\BlockController;
use Concrete\Core\File\File;
use Concrete\Package\TallacmansGoogleFonts\FontPickerHelper;

class Controller extends BlockController
{
    protected $btExportFileColumns = ['logoIcon'];
    protected $btTable = 'btTallacmansSitename';
    protected $btInterfaceWidth = 780;
    protected $btInterfaceHeight = 740;
    protected $btIgnorePageThemeGridFrameworkContainer = true;
    protected $btDefaultSet = 'basic';

    public function getBlockTypeDescription()
    {
        return t('Display a linked site name, logo, or both.');
    }

    public function getBlockTypeName()
    {
        return t('Tallacmans Site Name');
    }

    public function view()
    {
        $file = $this->logoIcon ? File::getByID($this->logoIcon) : null;

        if ($file && !$file->isError()) {
            $this->set('logoIcon', $file);
        } else {
            $this->set('logoIcon', false);
        }

        $this->set('fontFamilyCss', $this->resolveFontFamilyCss((string) $this->fontFamily));
    }

    public function add()
    {
        $this->addEdit();
    }

    public function edit()
    {
        $this->addEdit();
    }

    protected function addEdit()
    {
        $this->set('identifier_getString', uniqid('tallacmans_sitename_', true));
        $this->set('alignmentOptions', $this->getAlignmentOptions());
        $this->set('fontFamilyOptions', $this->getFontFamilyOptions());
        $this->set('fontFamilyCssMap', $this->getLegacyFontFamilyMap());
        $this->setPreviewLogoUrl();
        $this->requireAsset('core/file-manager');
    }

    protected function setPreviewLogoUrl(): void
    {
        $previewLogoUrl = '';

        if (!empty($this->logoIcon)) {
            $file = File::getByID((int) $this->logoIcon);
            if ($file && !$file->isError()) {
                $previewLogoUrl = (string) $file->getURL();
            }
        }

        $this->set('previewLogoUrl', $previewLogoUrl);
    }

    public function save($data)
    {
        $data['displaySitename'] = trim((string) ($data['displaySitename'] ?? ''));
        $data['logoIcon'] = (int) ($data['logoIcon'] ?? 0);
        $data['alignment'] = array_key_exists($data['alignment'] ?? '', $this->getAlignmentOptions())
            ? $data['alignment']
            : 'image-left';
        $data['textColor'] = $this->normalizeColor((string) ($data['textColor'] ?? ''));
        $data['fontFamily'] = array_key_exists($data['fontFamily'] ?? '', $this->getFontFamilyOptions())
            ? $data['fontFamily']
            : '';
        $fontSize = (int) ($data['fontSize'] ?? 25);
        $data['fontSize'] = $fontSize >= 8 && $fontSize <= 200 ? $fontSize : 25;

        parent::save($data);
    }

    public function validate($data)
    {
        $errors = $this->app->make('error');

        if (empty($data['logoIcon']) && trim((string) ($data['displaySitename'] ?? '')) === '') {
            $errors->add(t('Enter a site name, choose a logo, or use both.'));
        }

        if (!array_key_exists($data['alignment'] ?? '', $this->getAlignmentOptions())) {
            $errors->add(t('Choose a valid logo alignment.'));
        }

        $textColor = trim((string) ($data['textColor'] ?? ''));
        if ($textColor !== '' && $this->normalizeColor($textColor) === '') {
            $errors->add(t('Choose a valid text color.'));
        }

        if (!array_key_exists($data['fontFamily'] ?? '', $this->getFontFamilyOptions())) {
            $errors->add(t('Choose a valid font family.'));
        }

        $fontSize = (int) ($data['fontSize'] ?? 0);
        if ($fontSize !== 0 && ($fontSize < 8 || $fontSize > 200)) {
            $errors->add(t('Font size must be between 8 and 200 pixels, or 0 to use the theme default.'));
        }

        return $errors;
    }

    private function getAlignmentOptions(): array
    {
        return [
            'image-left' => t('Logo Left'),
            'image-right' => t('Logo Right'),
            'image-top' => t('Logo Above'),
            'image-bottom' => t('Logo Below'),
        ];
    }

    private function getFontFamilyOptions(): array
    {
        $options = [
            '' => t('Theme Default'),
            'system' => t('System UI'),
            'arial' => t('Arial'),
            'georgia' => t('Georgia'),
            'trebuchet' => t('Trebuchet MS'),
            'verdana' => t('Verdana'),
            'courier' => t('Courier New'),
        ];

        return FontPickerHelper::mergeFontFamilyOptions($options, $this->app);
    }

    private function resolveFontFamilyCss(string $fontFamily): string
    {
        if ($fontFamily === '') {
            return '';
        }

        $legacyFamilies = $this->getLegacyFontFamilyMap();

        if (isset($legacyFamilies[$fontFamily])) {
            return $legacyFamilies[$fontFamily];
        }

        if (array_key_exists($fontFamily, $this->getFontFamilyOptions())) {
            return $fontFamily;
        }

        return '';
    }

    private function getLegacyFontFamilyMap(): array
    {
        return [
            'system' => 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif',
            'arial' => 'Arial, Helvetica, sans-serif',
            'georgia' => 'Georgia, "Times New Roman", serif',
            'trebuchet' => '"Trebuchet MS", Arial, sans-serif',
            'verdana' => 'Verdana, Geneva, sans-serif',
            'courier' => '"Courier New", Courier, monospace',
        ];
    }

    private function normalizeColor(string $color): string
    {
        $color = trim($color);

        return preg_match('/^#[0-9a-f]{6}$/i', $color) ? strtolower($color) : '';
    }
}
