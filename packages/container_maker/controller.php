<?php
namespace Concrete\Package\ContainerMaker;

use Concrete\Core\Package\Package;
use Concrete\Core\Page\Single as SinglePage;

class Controller extends Package
{
    protected $pkgHandle = 'container_maker';
    protected $appVersionRequired = '9.0.0';
    protected $pkgVersion = '1.6.7';
    protected $pkgAutoloaderRegistries = [
        'src/Service' => '\\Concrete\\Package\\ContainerMaker\\Service',
    ];

    public function getPackageName()
    {
        return t('Container Maker');
    }

    public function getPackageDescription()
    {
        return t('Visual CSS Grid and Bootstrap 5 container designer for Concrete CMS. Drag layout presets onto a canvas, resize areas, preview generated PHP, and save to your active theme.');
    }

    public function install()
    {
        $pkg = parent::install();
        $this->installSinglePage($pkg, '/dashboard/pages/container_maker', t('Container Maker'));
        return $pkg;
    }

    protected function installSinglePage($pkg, $path, $name)
    {
        $page = SinglePage::add($path, $pkg);
        if (is_object($page) && !$page->isError()) {
            $page->update(['cName' => $name]);
        }
    }
}
