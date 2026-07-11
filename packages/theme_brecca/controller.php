<?php
namespace Concrete\Package\ThemeBrecca;

use Concrete\Core\Package\Package;
use Concrete\Core\Block\BlockType\BlockType;
use Concrete\Core\Page\Template as PageTemplate;
use Concrete\Core\Page\Theme\Theme as PageTheme;
use Concrete\Package\ThemeBrecca\Src\Juiced;

defined('C5_EXECUTE') or die("Access Denied.");

class Controller extends Package
{

    protected $pkgHandle = 'theme_brecca';
    protected $appVersionRequired = '9.0';
    protected $pkgVersion = '2.0.3';
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

        if (!PageTemplate::getByHandle('about_brecca')) {
            PageTemplate::add('about_brecca', t('About Brecca'), FILENAME_PAGE_TEMPLATE_DEFAULT_ICON, $pkg);
        }

        return $pkg;
    }

    public function upgrade()
    {
        parent::upgrade();

        if (!PageTemplate::getByHandle('about_brecca')) {
            PageTemplate::add('about_brecca', t('About Brecca'), FILENAME_PAGE_TEMPLATE_DEFAULT_ICON, $this);
        }
    }

    public function uninstall()
    {
        if ($bt = BlockType::getByHandle('tallacmans_background_image')) {
            $bt->delete();
        }

        if ($pt = PageTemplate::getByHandle('about_brecca')) {
            $pt->delete();
        }

        parent::uninstall();
    }
}
