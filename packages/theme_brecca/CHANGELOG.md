### version 2.0.5 - July 11, 2026

- restored built-in Image Slider block behavior by removing Brecca's `providesAsset('css', 'blocks/image-slider')` override so core slider assets load normally

### version 2.0.4 - July 11, 2026

- ensured `tallacmans_background_image` installs on both package install and package upgrade/update
- fixed package controller block-install helper to accept Concrete's runtime package object during install/upgrade
- block continues to be removed when the theme package is uninstalled

### version 2.0.3 - July 11, 2026

- custom page content added to the "About Brecca" page now always displays, wrapped in the theme's white translucent box styling, regardless of login status
- documentation text on the "About Brecca" page remains logged-in only

### version 2.0.2 - July 10, 2026

- added an "About Brecca" page template, auto-registered on package install/upgrade
- per-page background image now takes priority over the global background image, and the global image is skipped entirely (not just hidden) when a page has its own image set
- "About Brecca" page content is restricted to logged-in users
- noted a known limitation: some mobile browsers do not render the fixed background image correctly while scrolling

### version 2.0.1 - July 10, 2026

- fixed package controller parse error (missing closing class brace) that prevented install
- normalized package layout so `controller.php` is at package root
- clarified install steps to avoid broken package handle from wrong folder naming/nesting

### version 2.0.0 - June 23, 2026

- updated required Concrete CMS version to 9.0
- bumped package version to 2.0.0
- auto-install `tallacmans_background_image` block with theme install
- auto-uninstall `tallacmans_background_image` block when theme is removed
- corrected `Tallacmans Background Image` block namespace for package compatibility
- kept Juiced grid framework integration

### version 1.5.2 - March 18, 2021

- fixed feature display (tooltip be gone)
- added feature block default to accomplish the above
- set default nav background color to be a dark gray

### version 1.5.1 - February 5, 2020

- nav ul ul display fixed - needs mobile nav
- fixed nav colors

### version 1.5 - February 4, 2020

- nav disappears if empty
- breadcrumbs styled

### version 1.4 - January 27, 2020

- added framework and button styles

### version 1.3 - April 24, 2019

- remove account menu
- resolved issue with animate preventing the moving of blocks in the main area - fixed most coding practices to current standards
- removed useless page templates raised version to 8.2 minimum
