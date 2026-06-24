<?php
namespace Concrete\Package\ThemeBrecca;

use Concrete\Core\Package\Package;
use Concrete\Core\Block\BlockType\BlockType;
use Concrete\Core\Page\Theme\Theme as PageTheme;
use Concrete\Package\ThemeBrecca\Src\Juiced;

defined('C5_EXECUTE') or die("Access Denied.");

class Controller extends Package
{

    protected $pkgHandle = 'theme_brecca';
    protected $appVersionRequired = '9.0';
    protected $pkgVersion = '2.0.0';
    protected $pkgAutoloaderRegistries = array(
        'src' => '\Concrete\Package\ThemeBrecca\Src'
    );

    public function getPackageName() {
        return t("Brecca by tallacman");
    }

    public function getPackageDescription() {
        return t("When your message is visual.");
    }

    public function on_start(){
    $manager = $this->app->make('manager/grid_framework');
    $manager->extend('Juiced', function($app) {
        return new Juiced();
    });
   }

    public function install()
    {
        $pkg = parent::install();
        PageTheme::add('brecca', $pkg);
        BlockType::installBlockTypeFromPackage('tallacmans_background_image', $pkg);

        return $pkg;
    }

    public function uninstall()
    {
        if ($bt = BlockType::getByHandle('tallacmans_background_image')) {
            $bt->delete();
        }

        parent::uninstall();
    }
