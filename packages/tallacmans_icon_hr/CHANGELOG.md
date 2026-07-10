### version 2.0.5 - July 1, 2026

- replaced the unreliable core Vue icon selector with a standalone visual icon picker (TomSelect + Font Awesome glyphs)
- icon list includes all solid and brand icons shipped with Concrete CMS; the picker shows icons visually instead of text labels

### version 2.0.4 - July 1, 2026

- fixed icon picker dropdown showing no options by matching the core Feature block mount pattern and reinitializing TomSelect with a body-level dropdown
- moved the icon picker above the side-by-side editor so dialog overflow no longer clips the icon list

### version 2.0.3 - July 1, 2026

- fixed icon picker not mounting in the block editor (selector ID no longer uses dotted uniqid values that break jQuery/Vue)
- icon selector now uses the core `data-vue` / `concreteVue` initialization pattern for dialog and inline edit modes

### version 2.0.2 - July 1, 2026

- fixed icon picker not appearing in the block editor (Vue icon selector now initializes correctly)
- removed background color from the icon; the icon sits directly on the line

### version 2.0.1 - July 1, 2026

- verified all PHP files pass the Concrete CMS `c5:phpcs` linter

### version 2.0.0 - July 1, 2026

- rewrote package and block for Concrete CMS 9 coding standards
- replaced legacy Font Awesome 4 dropdown with the core icon selector (Font Awesome 5+)
- modernized the block edit interface with a side-by-side layout and sticky live preview
- replaced preset dropdowns with px number inputs for icon size, padding, and line thickness
- added configurable top and bottom margins
- added optional centered drop shadow with blur, opacity, and color controls; shadow clears on hover
- replaced legacy Spectrum color picker with native color inputs
- migrated existing block instances from legacy preset values on upgrade

### version 1.0.4

- legacy Concrete CMS 8 release
