<?php
namespace Concrete\Package\JackieOTheme\Theme\JackieO;

use Concrete\Core\Page\Theme\Theme;
class PageTheme extends Theme {

	// protected $pThemeGridFrameworkHandle = 'Juiced';
	protected $pThemeGridFrameworkHandle = 'Juiced';


	public function registerAssets() {
		$this->requireAsset('jackie-o-theme');
	        $this->requireAsset('javascript', 'picturefill');
	}


    public function getThemeResponsiveImageMap() {
        return array(
            'xlarge' => '1200px',
            'large' => 	'992px',
            'medium' => '768px',
            'small' => 	'0'
        );
    }


}
