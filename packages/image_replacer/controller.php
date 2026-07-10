<?php namespace Concrete\Package\ImageReplacer;

use Concrete\Core\Package\Package;
use Concrete\Core\Page\Single;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends Package
{
    protected $pkgHandle = 'image_replacer';
    protected $appVersionRequired = '9.0.0';
    protected $pkgVersion = '1.1.0';

    public function getPackageName(): string
    {
        return t('Image Replacer');
    }

    public function getPackageDescription(): string
    {
        return t('Dashboard tool that swaps every JPEG/PNG in a chosen folder for a same-dimension real photo pulled from Lorem Picsum, LoremFlickr, Unsplash, Pexels, or Pixabay, with one-click backup and restore.');
    }

    public function install()
    {
        $pkg = parent::install();

        Single::add('/dashboard/system/image_replacer', $pkg);

        return $pkg;
    }
}
