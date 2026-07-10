### version 1.1.0 - July 8, 2026

- replaced flat-color/placeholder providers (Placehold.co, DiceBear, UI Avatars) with real-photo providers: Lorem Picsum, LoremFlickr, Unsplash, Pexels, Pixabay
- Unsplash/Pexels/Pixabay do a live search and pick a random result per file, so batch replacements don't all get the same photo
- added an API key field for the providers that require one

### version 1.0.0 - July 8, 2026

- initial release
- dashboard tool at System & Settings > Image Replacer
- scans a chosen folder for JPEG/PNG files and lists filename, dimensions, size, and backup status
- replaces selected images with same-dimension placeholders from Placehold.co, Lorem Picsum, LoremFlickr, DiceBear, or UI Avatars
- automatic .bak backup before first overwrite, with a restore action per file
