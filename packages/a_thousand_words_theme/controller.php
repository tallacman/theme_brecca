<?php

declare(strict_types=1);

namespace Concrete\Package\AThousandWordsTheme;

use Concrete\Core\Package\Package;
use Concrete\Core\Page\Template as PageTemplate;
use Concrete\Core\Page\Theme\Theme as PageTheme;
use Concrete\Core\Page\Type\Type as PageType;
use Concrete\Core\Support\Facade\Database;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends Package
{
    protected $pkgHandle = 'a_thousand_words_theme';

    protected $appVersionRequired = '9.0.0';

    protected $pkgVersion = '2.4.4';

    public function getPackageName()
    {
        return t('A Thousand Words');
    }

    public function getPackageDescription()
    {
        return t('A Thousand Words is a visual, centered-card theme with background-image areas and several layout variants.');
    }

    public function install()
    {
        $pkg = parent::install();
        $this->installPageTemplatesAndTheme($pkg);

        return $pkg;
    }

    public function upgrade()
    {
        parent::upgrade();
        $this->installPageTemplatesAndTheme($this);
    }

    public function on_start()
    {
        $this->installPageTemplatesAndTheme($this);
    }

    private function installPageTemplatesAndTheme($pkg): void
    {
        $this->pageTemplates($pkg);

        if (!PageTheme::getByHandle('a_thousand_words')) {
            PageTheme::add('a_thousand_words', $pkg);
        }

        $this->repairPageTemplateReferences();
        $this->ensurePageTypeDefaultPages();
    }

    private function repairPageTemplateReferences(): void
    {
        $db = Database::connection();
        $fallbackTemplate = PageTemplate::getByHandle('left_sidebar') ?: PageTemplate::getByHandle('full');
        if (!$fallbackTemplate) {
            return;
        }
        $fallbackTemplateID = $fallbackTemplate->getPageTemplateID();

        $orphanedVersions = $db->fetchAllAssociative(
            'SELECT cv.cID, cv.cvID FROM CollectionVersions cv
             LEFT JOIN PageTemplates pt ON cv.pTemplateID = pt.pTemplateID
             WHERE cv.pTemplateID > 0 AND pt.pTemplateID IS NULL'
        );
        foreach ($orphanedVersions as $row) {
            $db->update(
                'CollectionVersions',
                ['pTemplateID' => $fallbackTemplateID],
                ['cID' => (int) $row['cID'], 'cvID' => (int) $row['cvID']]
            );
        }

        $orphanedDefaults = $db->fetchAllAssociative(
            'SELECT d.ptID, d.pTemplateID FROM PageTypePageTemplateDefaultPages d
             LEFT JOIN PageTemplates pt ON d.pTemplateID = pt.pTemplateID
             WHERE pt.pTemplateID IS NULL'
        );
        foreach ($orphanedDefaults as $row) {
            $db->delete('PageTypePageTemplateDefaultPages', [
                'ptID' => (int) $row['ptID'],
                'pTemplateID' => (int) $row['pTemplateID'],
            ]);
        }
    }

    private function ensurePageTypeDefaultPages(): void
    {
        $pageType = PageType::getByHandle('page');
        if (!$pageType) {
            return;
        }

        foreach (['full', 'left_sidebar', 'right_sidebar', 'center', 'intro', 'blank'] as $handle) {
            $template = PageTemplate::getByHandle($handle);
            if ($template) {
                $pageType->getPageTypePageTemplateDefaultPageObject($template);
            }
        }
    }

    private function pageTemplates($pkg)
    {
        if (!PageTemplate::getByHandle('left_sidebar')) {
            PageTemplate::add('left_sidebar', t('Left Sidebar'), 'left_sidebar.png', $pkg);
        }

        if (!PageTemplate::getByHandle('right_sidebar')) {
            PageTemplate::add('right_sidebar', t('Right Sidebar'), 'right_sidebar.png', $pkg);
        }

        if (!PageTemplate::getByHandle('center')) {
            PageTemplate::add('center', t('Center'), 'full.png', $pkg);
        }

        if (!PageTemplate::getByHandle('intro')) {
            PageTemplate::add('intro', t('Intro'), 'full.png', $pkg);
        }

        if (!PageTemplate::getByHandle('blank')) {
            PageTemplate::add('blank', t('Blank'), 'full.png', $pkg);
        }
    }
}
