<?php

declare(strict_types=1);

namespace Concrete\Package\TallacmansTicker;

use Concrete\Core\Block\BlockType\BlockType;
use Concrete\Core\Package\Package;
use Concrete\Core\Support\Facade\Database;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends Package
{
    protected $pkgHandle = 'tallacmans_ticker';

    protected $appVersionRequired = '9.0.0';

    protected $pkgVersion = '2.1.6';

    public function getPackageName()
    {
        return t("Tallacman's Ticker");
    }

    public function getPackageDescription()
    {
        return t('Scroll text across your site with customizable speed, colors, and typography.');
    }

    public function install()
    {
        $pkg = parent::install();

        if (!BlockType::getByHandle('tallacmans_ticker')) {
            BlockType::installBlockType('tallacmans_ticker', $pkg);
        }

        return $pkg;
    }

    public function upgrade()
    {
        $pkg = parent::upgrade();

        $blockType = BlockType::getByHandle('tallacmans_ticker');
        if ($blockType) {
            $blockType->refresh();
        }

        return $pkg;
    }

    public function uninstall()
    {
        $pkg = parent::uninstall();

        $db = Database::connection();
        $db->executeQuery('drop table if exists btTallacmansTicker');

        return $pkg;
    }
}
