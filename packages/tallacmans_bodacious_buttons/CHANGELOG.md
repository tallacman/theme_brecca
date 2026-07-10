### version 2.3.1 - July 1, 2026

- packaged as a **standalone repository** for installation on any Concrete CMS 9 site (clone or ZIP into `packages/tallacmans_bodacious_buttons/`)
- added README with install and upgrade instructions
- removed unused legacy `auto.js` asset

### version 2.3.0 - July 1, 2026

- reorganized block editor into a side-by-side layout with settings on the left and a sticky **Preview** panel on the right
- moved custom styling controls beneath the button settings (no separate tab); they appear only when **Style this button myself** is selected
- fixed live preview updates in the block dialog with scoped editor JavaScript and inline theme-style fallback colors
- condensed spacing controls with full padding labels in a compact row
- tuned block dialog size to 780×640

### version 2.2.0 - July 1, 2026

- added **border color** and **border width** controls for normal and hover states in custom styling mode
- live preview now reflects border colors and widths, including on hover
- bumped block dialog height to 1060px

### version 2.1.0 - July 1, 2026

- added choice between **Use theme styling** and **Style this button myself**
- custom styling options (shown only in custom mode): background color, text color, hover background, hover text color, font family, weight, and size
- font family options are loaded from the active theme's CMS webfonts
- added live button preview in the block editor
- bumped block dialog height to 980px for the new styling controls

### version 2.0.1 - July 1, 2026

- removed inline/block display mode option (spacing controls remain)
- fixed undefined `getOpenInOptions()` method call on save
- removed None link type option
- external URLs now auto-prefix `https://` when missing

### version 2.0.0 - July 1, 2026

**Package modernization**

- rewrote package and block controllers for Concrete CMS 9 coding standards
- raised minimum Concrete CMS version to 9.0.0
- added package `upgrade()` and `uninstall()` handlers with block type refresh
- modernized block edit interface with fieldsets, help text, and a taller dialog (520×720)
- fixed missing `identifier_getString` for smart link form controls
- updated add, edit, and composer templates to current block conventions
- all PHP files pass the Concrete CMS `c5:phpcs` linter

**New link options**

- added **File Download** link type using the Concrete asset library file picker
- added **Force file to download** checkbox for file links
- retained **Page** and **External URL** link types with improved validation

**New layout options**

- added adjustable spacing controls: top, right, bottom, and left (pixel padding)
- updated `view.css` for inline and block wrapper styles

**Database**

- added columns: `link_File`, `forceDownload`, `spacingTop`, `spacingRight`, `spacingBottom`, `spacingLeft`

### 0.9.4 and earlier

- original bodacious buttons release
