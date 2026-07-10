### version 2.4.4 June 30, 2026

- stick copyright footer to the bottom of the viewport on short pages using a flex column layout
- give the lead area the same card drop shadow as main and sidebar content areas
- align search box height with the search button using Bootstrap-matched padding and flex alignment
- restore default dark text color on form fields and search inputs (removed temporary white-on-green styling)
- change default page/body background color to white so form fields no longer inherit the theme green; body background now follows the customizer `--bs-body-bg` variable
- hide empty lead area on the front end so it takes zero height; lead area still appears in edit mode for adding blocks
- bring theme element PHP files up to Concrete CMS PHP-CS-Fixer compliance (`declare(strict_types=1)` and file header formatting)
- always render sidebar columns for sidebar page templates, even when empty
- always render lead area markup for non-blank layouts (superseded in 2.4.4 for empty lead on the front end)
- repair orphaned page template IDs so templates appear in the Design panel again
- create missing page-type default pages for Center, Intro, Blank, and other theme templates
- keep empty areas visible in edit mode with transparent backgrounds instead of white cards
- register theme page templates on package upgrade and startup so Center, Intro, and Blank appear in the page Design panel

### version 2.4.0 June 28, 2026

- moved Tallacmans Background Image block into its own standalone package

### version 2.3.2 June 27, 2026

- fixed editing header site title and navigation blocks in edit mode
- switched mobile navigation to Concrete's built-in Responsive Header Navigation Auto-Nav template
- removed the custom responsive navigation block and theme Auto-Nav template
- fixed mobile menu to display full-width below the header
- wired header site title to the standard Header Site Title global area

### version 2.3.1 June 27, 2026

- made empty content areas transparent instead of showing a white card
- fixed blog and other pages where blocks exist but render no visible output

### version 2.3.0 June 27, 2026

- fixed header dropdown navigation on desktop
- removed drop shadow on hover for all content card areas
- made the header bar sticky
- added a theme customizer control for header bar background color and transparency
- added a hamburger menu for header navigation on small screens
- hide the global background image area when a page has its own background image set
- bring theme PHP up to Concrete CMS PHP-CS-Fixer / PSR-12 compliance

### January 30, 2017

- added global area for global background, translucent background for lead area in edit mode, link hover color set to dark purple instead of light orange.

###version 0.9.2 January 31, 2017

- made image areas for background smaller
-  made header nav show if in edit mode.

### version 1.1 January 19, 2020

- made juiced grid active

### version 1.2 February 4, 2020

- changed header sitename color on hover to #eee

### version 1.3 November 9, 2020

- added intro page type
