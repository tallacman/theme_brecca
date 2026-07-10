<?php

declare(strict_types=1);

namespace Concrete\Package\TallacmansLeadFeature;

use Concrete\Core\Block\BlockType\BlockType;
use Concrete\Core\Package\Package;
use Concrete\Core\Support\Facade\Database;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends Package
{
    protected $pkgHandle = 'tallacmans_lead_feature';

    protected $appVersionRequired = '9.0.0';

    protected $pkgVersion = '2.2.7';

    public function getPackageName()
    {
        return t("Tallacman's Lead Feature");
    }

    public function getPackageDescription()
    {
        return t('Full-width hero section with background image, overlay, animated lead text, and optional scroll arrow.');
    }

    public function install()
    {
        $pkg = parent::install();

        if (!BlockType::getByHandle('tallacmans_lead_feature')) {
            BlockType::installBlockType('tallacmans_lead_feature', $pkg);
        }

        return $pkg;
    }

    public function upgrade()
    {
        $pkg = parent::upgrade();

        $blockType = BlockType::getByHandle('tallacmans_lead_feature');
        if ($blockType) {
            $blockType->refresh();
        }

        $this->migrateShowArrowValues();

        return $pkg;
    }

    protected function migrateShowArrowValues(): void
    {
        $config = $this->getConfig();
        if ((bool) $config->get('legacy_show_arrow_migrated')) {
            return;
        }

        $db = Database::connection();
        // Legacy: 1 = No, 2 = Yes. New: 0 = No, 1 = Yes. Order matters: 1→0 before 2→1.
        $db->executeQuery("UPDATE btTallacmansLeadFeature SET ShowArrow = '0' WHERE ShowArrow = '1'");
        $db->executeQuery("UPDATE btTallacmansLeadFeature SET ShowArrow = '1' WHERE ShowArrow = '2'");
        $db->executeQuery("UPDATE btTallacmansLeadFeature SET ShowArrow = '1' WHERE ShowArrow = '9'");

        $config->save('legacy_show_arrow_migrated', true);
    }

    public function uninstall()
    {
        $pkg = parent::uninstall();

        $db = Database::connection();
        $db->executeQuery('drop table if exists btTallacmansLeadFeature');

        return $pkg;
    }
}
