### version 3.0.1 - June 26, 2026

- Fixed theme customization: upgraded `styles.xml` to version 2.0 (SCSS skin customizer) so color and typography options appear in the customizer again

### version 3.0 - June 26, 2026

- Converted Urbane theme to a Concrete CMS Bedrock theme (Bootstrap 5, SCSS, `BedrockThemeTrait`)
- Replaced LESS build pipeline with SCSS presets compiled to `css/skins/default.css`
- Updated header/footer to use `$view->getThemeStyles()` and core Bedrock assets (removed Bootstrap 4 CDN)
- Removed custom Bootstrap 4 grid framework from the package
- Updated FAQ accordion block template for Bootstrap 5 data attributes
- Removed legacy LESS sources, vendored Bootstrap 4 grid files, duplicate SlickNav JS, and unused theme element files
- Requires Concrete CMS 9.0+

### version 1.2 - March 9, 2020

- fixed spacing on header and nav button with static values
- moved typography to local-resets to control font weight and size
- fixed feature hover size
- fixed spacing on page name display

### version 1.1 - March 6, 2020

- added cdn js bs4 bundle to footer
- added accordion faq block template

### version 1.0.3 & 4 - March 5, 2020

- fixed footer on blog pages

### version 1.0.2 - March 4, 2020

- padding on footer bottom
- hide horizontal overflow on body - something in the header...
