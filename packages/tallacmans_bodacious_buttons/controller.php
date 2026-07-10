<?php

declare(strict_types=1);

namespace Concrete\Package\TallacmansBodaciousButtons;

use Concrete\Core\Block\BlockType\BlockType;
use Concrete\Core\Package\Package;
use Concrete\Core\Support\Facade\Database;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends Package
{
    protected $pkgHandle = 'tallacmans_bodacious_buttons';

    protected $appVersionRequired = '9.0.0';

    protected $pkgVersion = '2.3.1';

    public function getPackageName()
    {
        return t("Tallacman's Bodacious Buttons");
    }

    public function getPackageDescription()
    {
        return t('Styled CTA buttons for any Concrete CMS 9 site. Link to pages, URLs, or downloadable files.');
    }

    public function install()
    {
        $pkg = parent::install();

        if (!BlockType::getByHandle('tallacmans_bodacious_buttons')) {
            BlockType::installBlockType('tallacmans_bodacious_buttons', $pkg);
        }

        return $pkg;
    }

    public function upgrade()
    {
        $pkg = parent::upgrade();

        $blockType = BlockType::getByHandle('tallacmans_bodacious_buttons');
        if ($blockType) {
            $blockType->refresh();
        }

        return $pkg;
    }

    public function uninstall()
    {
        $pkg = parent::uninstall();

        $db = Database::connection();
        $db->executeQuery('drop table if exists btTallacmansBodaciousButtons');

        return $pkg;
    }
}
