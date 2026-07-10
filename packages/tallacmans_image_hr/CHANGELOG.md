### version 2.0.7 - July 2, 2026

- updated marketplace marketing copy (`MARKETING.md`) to match Concrete CMS marketplace listing format
- synced `CHANGELOG.TXT` with current release history

### version 2.0.6 - July 2, 2026

- choosing double line style now sets line thickness to 3px automatically (minimum needed for double lines to appear)
- updated double-line help note in the block editor

### version 2.0.5 - July 1, 2026

- verified all PHP files pass the Concrete CMS `c5:phpcs` linter

### version 2.0.4 - July 1, 2026

- added optional centered drop shadow with blur, opacity, and color controls; shadow clears on hover

### version 2.0.3 - July 1, 2026

- fixed image border and padding not persisting or rendering after save (legacy preset lookup was remapping px values 1–6)

### version 2.0.2 - July 1, 2026

- fixed live preview image updates when choosing a new file in the block editor
- added guidance that double line styles need at least 3px thickness to be visible

### version 2.0.1 - July 1, 2026

- fixed package upgrade migration for legacy empty padding values before schema conversion

### version 2.0.0 - July 1, 2026

- rewrote package and block for Concrete CMS 9 coding standards
- modernized the block edit interface with a side-by-side layout and sticky live preview
- replaced preset dropdowns with px number inputs for image size, padding, border, and line thickness
- added configurable top and bottom margins
- fixed thumbnail generation by passing integer dimensions to the image helper
- replaced legacy Spectrum color picker with native color inputs
- migrated existing block instances from legacy preset values on upgrade

### version 1.0.3 - February 27, 2019

- colors populate properly: moved to addedit

### version 1.0 - February 6, 2019

- removed page call from block controller color matched icon to #ff05c4

### version 0.8.9 - February 5, 2019

- second viable rebuild
