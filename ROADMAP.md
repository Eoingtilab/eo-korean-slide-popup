# Roadmap

EO Korean Slide Popup is currently an early-stage public project. The first goal is to make the plugin easy to test, easy to understand, and safe enough for real Korean WordPress site operations.

## Phase 1: Repository cleanup

- Keep the plugin version and WordPress `readme.txt` stable tag aligned.
- Add missing documentation for installation, usage, troubleshooting, and screenshots.
- Review all files for WordPress coding standards.
- Confirm there are no hard-coded private URLs, API keys, or customer-specific settings.

## Phase 2: Admin UX cleanup

- Split the admin screen into Basic Settings and Advanced Settings.
- Reduce option overload for non-technical site operators.
- Add Korean help text for common popup scenarios.
- Improve the slide editor layout for mobile-width admin screens.

## Phase 3: Front-end stability

- Test popup positioning across common Korean WordPress themes.
- Improve mobile image sizing and close-button behavior.
- Improve accessibility attributes and keyboard behavior.
- Make overlay, scroll lock, and z-index behavior more predictable.

## Phase 4: KBoard compatibility

- Test KBoard URL card parsing with common KBoard skins.
- Improve representative image extraction.
- Add safe fallback UI when KBoard is not installed.
- Document KBoard-specific usage examples.

## Phase 5: Stable release

- Add screenshots.
- Add sample popup presets.
- Add import/export support if needed.
- Prepare WordPress.org-compatible documentation if the plugin is submitted later.
