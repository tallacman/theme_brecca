<?php
namespace Concrete\Package\Urbane;

use Concrete\Core\Package\Package;
use Concrete\Core\Page\Template as PageTemplate;
use Concrete\Core\Page\Theme\Theme as PageTheme;

defined('C5_EXECUTE') or die("Access Denied.");

class Controller extends Package
{
    protected $pkgHandle = 'urbane';
    protected $appVersionRequired = '9.0.0';
    protected $pkgVersion = '3.0.1';

    public function getPackageName()
    {
        return t("Urbane by tallacman");
    }

    public function getPackageDescription()
    {
        return t("Refined and elegant. Built on Concrete Bedrock.");
    }

    public function install()
    {
        $pkg = parent::install();

        if (!PageTemplate::getByHandle('left_sidebar')) {
            PageTemplate::add('left_sidebar', 'Left Sidebar', 'left_sidebar.png', $pkg);
        }
        if (!PageTemplate::getByHandle('right_sidebar')) {
            PageTemplate::add('right_sidebar', 'Right Sidebar', 'right_sidebar.png', $pkg);
        }
        if (!PageTemplate::getByHandle('blank')) {
            PageTemplate::add('blank', 'Blank', 'full.png', $pkg);
        }
        if (!PageTemplate::getByHandle('full')) {
            PageTemplate::add('full', 'Full', 'full.png', $pkg);
        }
        if (!PageTemplate::getByHandle('blog_entry')) {
            PageTemplate::add('blog_entry', 'Blog Entry', 'left_sidebar.png', $pkg);
        }

        PageTheme::add('urbane', $pkg);
    }
}
