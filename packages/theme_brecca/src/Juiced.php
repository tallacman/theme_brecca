<?php
namespace Concrete\Package\ThemeBrecca\Src;
use Concrete\Core\Page\Theme\GridFramework\GridFramework;

class Juiced extends GridFramework
{
    public function supportsNesting(){
        return 'false';
    }

  public function getPageThemeGridFrameworkName()
    {
        return t('Juiced');
    }

    public function getPageThemeGridFrameworkContainerStartHTML()
    {
        return '<div class="container full-width">';
    }

    public function getPageThemeGridFrameworkContainerEndHTML()
    {
        return '</div>';
    }

        public function getPageThemeGridFrameworkRowStartHTML()
    {
        return '<div class="container gutters">';
    }

    public function getPageThemeGridFrameworkRowEndHTML()
    {
        return '</div>';
    }



    public function getPageThemeGridFrameworkColumnClasses()
    {
        $columns = array(
          'col-md-1',
          'col-md-2',
          'col-md-3',
          'col-md-4',
          'col-md-5',
          'col-md-6',
          'col-md-7',
          'col-md-8',
          'col-md-9',
          'col-md-10',
          'col-md-11',
          'col-md-12',
        );

        return $columns;
    }

    public function getPageThemeGridFrameworkColumnOffsetClasses()
    {
        $offsets = array(
          'col-md-push-1',
          'col-md-push-2',
          'col-md-push-3',
          'col-md-push-4',
          'col-md-push-5',
          'col-md-push-6',
          'col-md-push-7',
          'col-md-push-8',
          'col-md-push-9',
          'col-md-push-10',
          'col-md-push-11',
          'col-md-push-12',
        );

        return $offsets;
    }
    public function getPageThemeGridFrameworkColumnAdditionalClasses()
    {
        return '';
    }

    public function getPageThemeGridFrameworkColumnOffsetAdditionalClasses()
    {
        return '';
    }

    public function getPageThemeGridFrameworkHideOnExtraSmallDeviceClass()
    {
        return 'xs-display-none';
    }

    public function getPageThemeGridFrameworkHideOnSmallDeviceClass()
    {
        return 'sm-display-none';
    }

    public function getPageThemeGridFrameworkHideOnMediumDeviceClass()
    {
        return 'md-display-none';
    }

    public function getPageThemeGridFrameworkHideOnLargeDeviceClass()
    {
        return 'lg-display-none';
    }


}
