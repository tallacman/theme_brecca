# Changelog

## 1.6.9

- CSS Grid designer now prevents block overlap in all responsive tiers (desktop/tablet/mobile), including tablet preview collisions.

## 1.6.8

- Preview viewport controls now show full Bootstrap 5 breakpoints: **xs, sm, md, lg, xl, xxl**.
- Breakpoint buttons map to the existing responsive editing tiers so current container data stays compatible.
- Canvas preview widths now align with Bootstrap 5 breakpoint ranges.

## 1.6.7

- Reworked import so **Installed / Upload / Paste** all load directly into the designer again.
- Removed tile resize handles and shifted sizing to the explicit area settings panel.
- Added a quick **Area names** editor and per-area **Area ID** support.
- Added optional **Container ID** and wired generated header CSS scoping to container id when present.
- Simplified workspace layout and reduced per-preview ad-hoc spacing controls.
- CSS Grid output now uses theme-driven gap variable (`--cm-theme-gap`) instead of dashboard gap input.

## 1.6.6

- **Save to active theme only** — the theme picker is gone; the designer always builds against (and imports from) the active site theme, so you can't accidentally save to the wrong theme.
- **Import** — the container list now only shows files from the active theme. Removed the **Upload file** and **Paste code** tabs to keep the flow simple.
- **Bootstrap detection** — the active theme is scanned for Bootstrap; when found, **Theme already includes Bootstrap 5** is checked automatically (still overridable).
- **Edit code (advanced)** — a new toggle on the generated-code panel lets you hand-edit the raw PHP before saving. Manual edits are written to the theme file verbatim, with a clear "you know what you're doing" warning.

## 1.6.5

- **Bootstrap 5** — **Gap** is hidden and excluded from save (use **Row gutter** in the Bootstrap panel instead).
- **CSS Grid** — new **Side margin** setting (Column tracks panel) adds left/right margin on the front end so the layout does not touch the viewport edges.

## 1.6.4

- **Bootstrap front-end** — fixed scoped grid CSS that forced every column to `width: 100%` (overriding `col-*` widths). Added `offset-*` classes for column positioning, single-row layout with row breaks, and tablet/mobile order fixes when rows are reordered.
- **CSS Grid front-end** — tablet/mobile rules now use explicit `tablet_span` / `mobile_span` and column positions; invalid `clamp()` row heights fall back to safe `minmax()`.

## 1.6.3

- **Bootstrap tablet/mobile** — resize handles and drag-to-move work again on narrow previews, snapping to a 12-column grid with overlap prevention per viewport.
- **Left resize handle** — fixed on all viewports (updates both column and span from the left edge).
- **Clear canvas** — also clears the container handle (unlocks the field), and unchecks **Overwrite**.

## 1.6.2

- **Bootstrap 5** — overlapping areas are fully disabled: layout auto-reflows, row span and grid overrides are hidden, and drag-to-move is desktop-only so tiles cannot stack on top of each other.

## 1.6.1

- **Clear canvas** — only shown when editing an existing/imported container; clears areas and the container **Name** in the save toolbar (and **Handle** when editable).
- **Bootstrap overlap** — Bootstrap 5 mode blocks overlapping areas on the canvas (CSS Grid still allows overlap).

## 1.6.0

- **Layout mode tabs** — choose **CSS Grid** or **Bootstrap 5** at the top of the designer. The same canvas, presets, and viewport preview work in both modes.
- **Bootstrap 5 containers** — areas on the same row are output as Bootstrap `.row` sections with `.col-*`, `.col-md-*`, and `.col-lg-*` classes. Tablet/mobile spans map to Bootstrap breakpoints.
- **Non-Bootstrap themes** — uncheck **Theme already includes Bootstrap 5** to inject scoped Bootstrap grid CSS in the page header, namespaced to the container block class (`.cm-{handle}`) so grid styles do not require theme-wide Bootstrap.
- **Bootstrap gutter** — pick `g-0` through `g-5` for row spacing.
- **Import** — existing Bootstrap row/column containers are detected on import and opened in Bootstrap mode.

## 1.5.3

- Independent row order per viewport (desktop, tablet, mobile) when dragging areas in narrow preview modes.

## 1.5.2

- Tablet/mobile preview reflow fix to prevent overlapping stacked blocks.

## 1.5.0

- Import existing containers from theme files, uploads, or pasted PHP.
