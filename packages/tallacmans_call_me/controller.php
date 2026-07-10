<?php

declare(strict_types=1);

namespace Concrete\Package\TallacmansCallMe;

use Concrete\Core\Block\BlockType\BlockType;
use Concrete\Core\Package\Package;
use Concrete\Core\Support\Facade\Database;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends Package
{
    protected $pkgHandle = 'tallacmans_call_me';

    protected $appVersionRequired = '9.0.0';

    protected $pkgVersion = '2.0.0';

    public function getPackageName()
    {
        return t("Tallacman's Call Me");
    }

    public function getPackageDescription()
    {
        return t('Add a click-to-call or click-to-text link with optional button or heading display.');
    }

    public function install()
    {
        $pkg = parent::install();

        if (!BlockType::getByHandle('tallacmans_call_me')) {
            BlockType::installBlockType('tallacmans_call_me', $pkg);
        }

        return $pkg;
    }

    public function upgrade()
    {
        $pkg = parent::upgrade();

        $blockType = BlockType::getByHandle('tallacmans_call_me');
        if ($blockType) {
            $blockType->refresh();
        }

        return $pkg;
    }

    public function uninstall()
    {
        $pkg = parent::uninstall();

        $db = Database::connection();
        $db->executeQuery('drop table if exists btTallacmansCallMe');

        return $pkg;
    }
}
