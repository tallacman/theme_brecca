<?php

namespace Concrete\Package\ThemeFoxybox;

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Block\BlockType\BlockType;
use Concrete\Core\Entity\Block\BlockType\BlockType as BlockTypeEntity;
use Concrete\Core\Package\Package;
use Concrete\Core\Page\Template as PageTemplate;
use Concrete\Core\Page\Theme\Theme as PageTheme;
use Concrete\Package\ThemeFoxybox\Src\Juiced;
use Doctrine\ORM\EntityManagerInterface;

class Controller extends Package
{
    protected $pkgHandle = 'theme_foxybox';
    protected $appVersionRequired = '9.0.0';
    protected $pkgVersion = '3.1.0';
    protected $pkgAutoloaderRegistries = [
        'src' => '\Concrete\Package\ThemeFoxybox\Src',
    ];

    public function getPackageName()
    {
        return t('FoxyBox Theme by tallacman');
    }

    public function getPackageDescription()
    {
        return t('A responsive theme for Concrete CMS 9.');
    }

    public function on_start()
    {
        $manager = $this->app->make('manager/grid_framework');
        $manager->extend('Juiced', static function () {
            return new Juiced();
        });
    }

    public function install()
    {
        $pkg = parent::install();

        $this->installPageTemplates($pkg);
        $this->installCustomBlocks($pkg);
        PageTheme::add('foxybox', $pkg);
    }

    public function upgrade()
    {
        parent::upgrade();

        // Blocks newly bundled with an existing package are not discovered by
        // Package::upgrade(), so install this one when upgrading from FoxyBox 2.x.
        $this->installCustomBlocks($this->getPackageEntity());
    }

    public function uninstall()
    {
        parent::uninstall();
    }

    private function installPageTemplates($pkg): void
    {
        $templates = [
            'foxy' => t('Foxy'),
            'narrow' => t('Narrow'),
            'thinner' => t('Thinner'),
        ];

        foreach ($templates as $handle => $name) {
            if (!PageTemplate::getByHandle($handle)) {
                PageTemplate::add($handle, $name, 'thumbnail.png', $pkg);
            }
        }
    }

    private function installCustomBlocks($pkg): void
    {
        $repository = $this->app->make(EntityManagerInterface::class)->getRepository(BlockTypeEntity::class);

        $blocksPath = $this->getPackagePath() . '/blocks';
        if (!is_dir($blocksPath)) {
            return;
        }

        $entries = scandir($blocksPath);
        foreach ($entries as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }

            $blockDir = $blocksPath . '/' . $entry;
            if (!is_dir($blockDir)) {
                continue;
            }

            $handle = $entry;
            if ($handle === 'tallacmans_background_image') {
                continue;
            }
            $blockType = $repository->findOneBy(['btHandle' => $handle]);
            if ($blockType === null) {
                BlockType::installBlockType($handle, $pkg);
            }
        }
    }
}
