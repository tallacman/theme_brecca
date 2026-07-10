<?php
namespace Concrete\Package\ThemeFoxybox\Theme\Foxybox;

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Page\Theme\Theme;

class PageTheme extends Theme
{


	protected $pThemeGridFrameworkHandle = 'Juiced';

	public function registerAssets()
    {
		$this->providesAsset('javascript', 'slicknav.js');
		$this->providesAsset('css', 'testimonial.css');
		
		$this->requireAsset('css', 'font-awesome');
		$this->requireAsset('javascript', 'jquery');
		$this->requireAsset('javascript', 'picturefill');
		$this->requireAsset('javascript-conditional', 'html5-shiv');
		$this->requireAsset('javascript-conditional', 'respond');
	}

	public function getThemeBlockClasses()
	     {
	         return [
	             'content' => ['foxy-content'],
	         ];
	 }

    public function getThemeResponsiveImageMap()
    {
        return [
            'xlarge' => '1100px',
            'large' => '992px',
            'medium' => '768px',
            'small' => '0'
        ];
    }


}
