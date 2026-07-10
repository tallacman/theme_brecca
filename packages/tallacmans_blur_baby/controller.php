<?php

declare(strict_types=1);

namespace Concrete\Package\TallacmansBlurBaby;

use Concrete\Core\Block\BlockType\BlockType;
use Concrete\Core\Package\Package;
use Concrete\Core\Support\Facade\Database;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends Package
{
    protected $pkgHandle = 'tallacmans_blur_baby';

    protected $appVersionRequired = '9.0.0';

    protected $pkgVersion = '2.1.1';

    public function getPackageName()
    {
        return t("Tallacman's Blur Baby");
    }

    public function getPackageDescription()
    {
        return t('Feature an image with a sharp foreground and blurred background.');
    }

    public function install()
    {
        $pkg = parent::install();

        if (!BlockType::getByHandle('tallacmans_blur_baby')) {
            BlockType::installBlockType('tallacmans_blur_baby', $pkg);
        }

        return $pkg;
    }

    public function upgrade()
    {
        $pkg = parent::upgrade();

        $blockType = BlockType::getByHandle('tallacmans_blur_baby');
        if ($blockType) {
            $blockType->refresh();
        }

        return $pkg;
    }

    public function uninstall()
    {
        $pkg = parent::uninstall();

        $db = Database::connection();
        $db->executeQuery('drop table if exists btTallacmansBlurBaby');

        return $pkg;
    }
}
