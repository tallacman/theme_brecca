### version 2.2.7 - July 6, 2026

Fixed the **ShowArrow** upgrade migration to remap legacy values in a safe order (`1→0`, then `2→1`) instead of using a temporary sentinel, and added cleanup for rows left at the old sentinel after a partial migration.

### version 2.2.5 - July 3, 2026

Modernized **Tallacman's Lead Feature** for Concrete CMS 9 and refined the hero block for marketplace release: rewrote the package and block with current coding standards, input sanitization, validation, composer support, block caching, and scoped front-end CSS; restored **rich text editors** for lead and secondary copy; fixed **ShowArrow** storage and upgrade migration (Yes/No with legacy value support); improved the **scroll chevron** with an inline SVG icon, in-hero positioning, smooth scroll-to-content, and a gentle float animation; added a **default text drop shadow** for readability on photos; tuned **entrance animations** so they play across the full hero image with text finishing **centered vertically and horizontally** in the frame; and fixed a **load-time height jump** by reserving hero dimensions up front with inline layout styles and correcting the animation layer containing block.

### version 1.1 - February 11, 2025

- declared variables for PHP 8+

### version 0.9.5 - December 20, 2019

- changes for the better

### version 0.9.3 - December 13, 2019

- numerous changes and rewriting

### version 0.9.1 - December 4, 2019

- register fontawesome, refined searchable content concatenation, removed extra die statement

### version 0.9.0 - December 2, 2019

- first release
