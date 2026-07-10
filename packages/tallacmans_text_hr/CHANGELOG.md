### version 2.0.9 - July 2, 2026

- updated marketplace marketing copy (`MARKETING.md`) to match Concrete CMS marketplace listing format
- synced `CHANGELOG.TXT` with current release history

### version 2.0.8 - July 2, 2026

- fixed double line style rendering as a single line when text sits on the rule (line is now split left/right of the text box, like Image HR)

### version 2.0.7 - July 1, 2026

- choosing double line style now sets line thickness to 3px automatically (minimum needed for double lines to appear)
- updated double-line help note in the block editor

### version 2.0.6 - July 1, 2026

- verified all PHP files pass the Concrete CMS `c5:phpcs` linter

### version 2.0.5 - July 1, 2026

- added optional centered drop shadow with blur, opacity, and color controls; shadow clears on hover

### version 2.0.4 - July 1, 2026

- added guidance that double line styles need at least 3px thickness to be visible

### version 2.0.3 - July 1, 2026

- replaced line thickness and text border preset dropdowns with px number inputs
- migrated existing block instances from legacy thickness and border presets on upgrade

### version 2.0.2 - July 1, 2026

- reorganized block editor into a side-by-side layout with settings on the left and a sticky **Preview** panel on the right
- tuned block dialog size to 780×640

### version 2.0.1 - July 1, 2026

- fixed package upgrade migration for Concrete CMS 9 database API

### version 2.0.0 - July 1, 2026

- rewrote package and block for Concrete CMS 9 coding standards
- modernized the block edit interface with taller layout and fieldsets
- added font family picker with custom CSS stack support
- replaced legacy text size/weight/leading presets with px font size, font weight, and letter spacing controls
- added configurable top and bottom margins
- added live preview in the edit dialog
- replaced legacy Spectrum color picker with native color inputs
- migrated existing block instances from legacy typography presets on upgrade

### version 1.1 - February 16, 2019

- changed db to doctrine, code cleanup

### version 1.0 - February 6, 2019

- first release
