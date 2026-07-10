<?php
namespace Concrete\Package\ThemeBrecca\Theme\Brecca;

use Concrete\Core\Page\Theme\Documentation\DocumentationPageInterface;
use Concrete\Core\Page\Theme\Documentation\DocumentationProviderInterface;
use Concrete\Core\Page\Theme\Documentation\ThemeDocumentationPage;
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

    public function getDocumentationProvider(): ?DocumentationProviderInterface
    {
        return new BreccaDocumentationProvider($this);
    }

}

class BreccaDocumentationProvider implements DocumentationProviderInterface
{
    /**
     * @var PageTheme
     */
    protected $theme;

    public function __construct(PageTheme $theme)
    {
        $this->theme = $theme;
    }

    public function clearSupportingElements(): void
    {
    }

    public function installSupportingElements(): void
    {
    }

    public function finishInstallation(): void
    {
    }

    /**
     * @return DocumentationPageInterface[]
     */
    public function getPages(): array
    {
        return [
            new ThemeDocumentationPage($this->theme, 'Overview', 'overview.xml'),
        ];
    }
}
