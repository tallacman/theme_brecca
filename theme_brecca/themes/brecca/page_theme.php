<?php
namespace Concrete\Package\ThemeBrecca\Theme\Brecca;

use Concrete\Core\Page\Theme\Theme;
class PageTheme extends Theme {

	protected $pThemeGridFrameworkHandle = 'Juiced';

	public function registerAssets() {
        $this->providesAsset('css', 'blocks/image-slider');
        $this->providesAsset('css', 'blocks/social_links');

        $this->requireAsset('javascript', 'jquery');
        $this->requireAsset('javascript', 'picturefill');

		$this->requireAsset('javascript-conditional', 'html5-shiv');
        $this->requireAsset('javascript-conditional', 'respond');
	}

    public function getThemeResponsiveImageMap() {
        return array(
            'xlarge' => '1100px',
            'large' => '992px',
            'medium' => '768px',
            'small' => '0'
        );
    }

}
