<?php

declare(strict_types=1);

namespace Concrete\Package\TallacmansCopyright;

use Concrete\Core\Block\BlockType\BlockType;
use Concrete\Core\Package\Package;
use Concrete\Core\Support\Facade\Database;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends Package
{
    protected $pkgHandle = 'tallacmans_copyright';

    protected $appVersionRequired = '9.0.0';

    protected $pkgVersion = '2.1.1';

    public function getPackageName()
    {
        return t('Tallacmans Copyright');
    }

    public function getPackageDescription()
    {
        return t('Display a styled legal copyright notice with customizable typography.');
    }

    public function install()
    {
        $pkg = parent::install();

        if (!BlockType::getByHandle('tallacmans_copyright')) {
            BlockType::installBlockType('tallacmans_copyright', $pkg);
        }

        return $pkg;
    }

    public function upgrade()
    {
        $pkg = parent::upgrade();

        $blockType = BlockType::getByHandle('tallacmans_copyright');
        if ($blockType) {
            $blockType->refresh();
        }

        return $pkg;
    }

    public function uninstall()
    {
        $pkg = parent::uninstall();

        $db = Database::connection();
        $db->executeQuery('drop table if exists btTallacmansCopyright');

        return $pkg;
    }
}
