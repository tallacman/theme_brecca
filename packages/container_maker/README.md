# Container Maker for Concrete CMS

Visual drag-and-drop designer for **native CSS Grid** and **Bootstrap 5** container templates.

## Dashboard location

**Dashboard → Pages & Themes → Container Maker**

## What it does

1. Drag a **layout preset** (50/50, sidebar, three columns, etc.) onto the canvas
2. Drag the **right edge** of an area to change its column span (1–12)
3. Drag areas vertically to **reorder** them
4. Watch the **generated PHP** update live
5. Save to your active theme’s `elements/containers/` folder

## Layout presets

- Full width
- 50 / 50
- Three columns
- 2/3 + 1/3 and 1/3 + 2/3
- Four columns
- Sidebar left / right
- Hero + content

## Layout modes (v1.6)

- **CSS Grid** — explicit `grid-column` / `grid-row` with custom column tracks
- **Bootstrap 5** — `.row` / `.col-*` markup; optional scoped Bootstrap grid CSS for themes without Bootstrap

## Features (v1.1)

- **Save to any installed theme** — not limited to the site active theme
- **Stack multiple presets** — drop presets onto the canvas to build complex layouts
- **Free grid placement** — drag areas anywhere; column, span, and row snap automatically
- **Live PHP preview** with explicit `grid-column` / `grid-row` CSS

## Workflow

1. Choose **Save to theme** from the dropdown
2. Drag presets onto the canvas (drop position sets starting column/row)
3. Drag areas to reposition; drag right edge to resize span
4. Stack more presets anywhere on the grid
5. Save

## Install

1. Copy the `container_maker` folder into your Concrete CMS `packages/` directory
2. **Dashboard → Extend Concrete → Install Container Maker**
3. Open **Dashboard → Pages & Themes → Container Maker**

## Output

Each container is a PHP file using:

- `display: grid` with `grid-template-columns: repeat(12, minmax(0, 1fr))`
- Per-area `grid-column` / `grid-row` rules injected into the page **header** via `View::addHeaderItem()`
- Optional inner wrapper class (`container`, `container-fluid`, or none)
- Embedded designer state so containers can be re-opened for editing

Styles are added once per container class per page, even if the same container appears multiple times.

## Upgrade from 0.9.x

Replace the package folder and refresh the dashboard page. Existing container files continue to work; only containers with embedded `CM_DESIGNER_STATE` can be edited in the designer.
