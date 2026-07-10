<?php
namespace Concrete\Package\JackieOTheme;

use Concrete\Core\Asset\Asset;
use Concrete\Core\Asset\AssetList;
use Concrete\Core\Package\Package;
use Concrete\Core\Page\Template as PageTemplate;
use Concrete\Core\Page\Theme\Theme;
use Concrete\Package\JackieOTheme\Src\Juiced;

defined('C5_EXECUTE') or die("Access Denied.");

class Controller extends Package
{

    protected $pkgHandle = 'jackie_o_theme';
    protected $appVersionRequired = '9.0';
    protected $pkgVersion = '1.8';
    protected $pkgAutoloaderRegistries = [
        'src' => '\Concrete\Package\JackieOTheme\Src',
    ];

    public function getPackageName()
    {
        return t('Jackie O by tallacman');
    }

    public function getPackageDescription()
    {
        return t('Ready to wear.');
    }

    public function on_start()
    {
        $manager = $this->app->make('manager/grid_framework');
        $manager->extend('Juiced', static function () {
            return new Juiced();
        });

        $assets = AssetList::getInstance();
        $assets->register(
            'css',
            'jackie-o-normalize',
            'themes/jackie_o/css/vendor/modern-normalize.min.css',
            ['version' => '0.6.0'],
            $this
        );
        $assets->register(
            'javascript',
            'jackie-o-slicknav',
            'themes/jackie_o/js/slicknav.js',
            ['position' => Asset::ASSET_POSITION_FOOTER, 'version' => '1.0.10'],
            $this
        );
        $assets->register(
            'javascript',
            'jackie-o-main',
            'themes/jackie_o/js/main.js',
            ['position' => Asset::ASSET_POSITION_FOOTER, 'version' => $this->pkgVersion],
            $this
        );
        $assets->registerGroup('jackie-o-theme', [
            ['css', 'jackie-o-normalize'],
            ['javascript', 'jquery'],
            ['javascript', 'jackie-o-slicknav'],
            ['javascript', 'jackie-o-main'],
        ]);
    }

    public function install()
    {
        $pkg = parent::install();

        if (!PageTemplate::getByHandle('thinner')) {
            PageTemplate::add('thinner', t('Thinner'), 'thumbnail.png', $pkg);
        }

        Theme::add('jackie_o', $pkg);
    }
}
