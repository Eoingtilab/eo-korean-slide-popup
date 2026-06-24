# Changelog

All notable changes to this project will be documented here.

## 1.2.3 - Development preview

- Added installation notes for WordPress ZIP uploads.
- Clarified that GitHub's default `Download ZIP` folder name may create a duplicate plugin folder.
- Rebuilt the installable ZIP package with the correct root folder name: `eo-korean-slide-popup`.
- Rechecked PHP syntax for all plugin PHP files.
- Kept the 1.2.2 functional fixes in the installable package.

## 1.2.2 - Development preview

- Added a **Home only** page visibility option.
- Fixed checkbox persistence by saving hidden `0` values before each checkbox.
- Improved popup settings UI with grouped setting cards and clearer field descriptions.
- Improved KBoard card resolution with class-based lookup, database fallback, and safer fallback text.
- Applied radius, background, and shadow settings to the actual popup dialog.
- Added fade-in and fade-out animation for popup open and close.
- Reduced bottom dot size and visual weight.
- Removed arrow hover background color and changed arrow emphasis to opacity only.
- Bumped plugin asset version to reduce stale CSS/JS cache during testing.

## 1.2.1 - Development preview

- Published the initial GitHub repository structure.
- Added the WordPress plugin bootstrap file.
- Added front-end and admin assets.
- Added core plugin classes for admin, front-end rendering, helpers, and plugin loading.
- Aligned `readme.txt` stable tag with the plugin version.
- Added public README documentation for early-stage open-source review.
- Added WordPress editor initialization for HTML slide content.
- Added a website open announcement HTML sample under `examples/`.
- Added front-end sample styles for `.eoksp-open-popup` content.

## 1.0.0

- Initial internal prototype.
