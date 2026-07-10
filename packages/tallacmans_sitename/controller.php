<?php

namespace Concrete\Package\TallacmansSitename;

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Block\BlockType\BlockType;
use Concrete\Core\Package\Package;

class Controller extends Package
{
    protected $pkgHandle = 'tallacmans_sitename';
    protected $appVersionRequired = '9.3.0';
    protected $pkgVersion = '2.4.0';

    public function getPackageName()
    {
        return t('Tallacmans Site Name');
    }

    public function getPackageDescription()
    {
        return t('Display a linked site name, logo, or both. Adjust the color, size and font.');
    }

    public function install()
    {
        $pkg = parent::install();
        BlockType::installBlockTypeFromPackage('tallacmans_sitename', $pkg);
    }
}
