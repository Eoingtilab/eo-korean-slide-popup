# EO Korean Slide Popup

A lightweight WordPress slide popup plugin designed for Korean websites.

EO Korean Slide Popup helps site operators create practical announcement popups without relying on heavy, outdated, or theme-breaking popup plugins. It supports image slides, video slides, WordPress-editor-based HTML slides, and KBoard post-card popups for Korean WordPress sites.

> Project status: early-stage public release. The plugin is usable as a development preview, but the codebase is still being cleaned up before a stable production release.

## Why this project exists

Many Korean WordPress sites still need popup notices for events, admissions, holidays, promotions, urgent announcements, and KBoard-based content. Existing popup plugins often feel too complex, too heavy, or poorly matched to Korean site operations.

This project aims to provide a simpler popup workflow for Korean websites:

- Create popups from the WordPress admin screen.
- Add multiple slides to one popup.
- Use image, video, HTML, or KBoard URL content.
- Write HTML slide content with the familiar WordPress editor instead of only raw code.
- Display popups on all pages, only the home page, included pages, or excluded pages.
- Control display period, audience, device, position, and priority.
- Keep the front-end lightweight and reduce theme conflicts.

## Main features

### Slide content types

- Image slide with optional link and button label
- YouTube, Vimeo, and MP4 video slide
- HTML slide with WordPress visual/text editor support
- KBoard URL card slide

### Korean website popup workflow

- Start date and end date
- Priority-based popup queue
- Desktop-only, mobile-only, or all-device display
- Guest-only, member-only, or all-user display
- All pages, home only, include rules, and exclude rules
- Center, top, bottom, left, and right popup positioning
- Custom width, max-width, radius, background, shadow, and overlay
- Fade-in and fade-out popup animation
- Open delay and auto-close
- One-day hide button with cookie duration setting
- Slider arrows, dots, loop, and autoplay options

### KBoard support

When KBoard is installed, the plugin can use a KBoard post URL to create a card-style popup from the post content.

Current KBoard card goals:

- Resolve title, excerpt, and representative image from a KBoard URL
- Try the KBoard class first, then fall back to the KBoard database table
- Keep the original link usable even when the card data cannot be resolved
- Support simple news-style and premium-style popup cards
- Help Korean site operators promote board posts without manually recreating each popup

## Requirements

- WordPress 5.8 or higher
- PHP 7.4 or higher
- Modern browser with JavaScript enabled
- KBoard is optional and only required for KBoard URL card popups

## Installation

### Development preview installation

1. Download or clone this repository.
2. Copy the plugin directory to your WordPress plugins directory:

```bash
wp-content/plugins/eo-korean-slide-popup
```

3. Activate **EO Korean Slide Popup** from the WordPress admin plugin screen.
4. Go to **Slide Popup** in the admin menu.
5. Create a new popup and add one or more slides.
6. Publish the popup and check it on the front-end.

### ZIP upload installation

1. Compress the plugin folder as `eo-korean-slide-popup.zip`.
2. In WordPress admin, go to **Plugins > Add New > Upload Plugin**.
3. Upload the ZIP file.
4. Activate the plugin.

## Basic usage

1. Open **Slide Popup > Add New**.
2. Enter the popup title.
3. Add slide content.
4. Choose content type: image, video, HTML, or KBoard URL card.
5. Set display options such as date range, device, audience, page visibility, and position.
6. Publish the popup.

For a simple event popup, use an image slide with a link URL.

For a notice popup, use an HTML slide. The HTML slide field supports the WordPress editor, so site managers can write headings, paragraphs, lists, links, and media without typing every tag manually.

For a KBoard announcement popup, use a KBoard post URL and select the KBoard card style. If the plugin cannot resolve the KBoard title or content, the card still keeps the link and uses safe fallback text.

## HTML popup sample

A website open announcement sample is included at:

```text
examples/website-open-popup.html
```

Recommended popup settings for the sample:

- Slide type: HTML
- Page visibility: Home only, if it is a main opening notice
- Position: Center
- Width: 640 to 720px
- Overlay: On
- Title bar: Optional
- Hide today button: On

The sample uses the `.eoksp-open-popup` CSS class, which is styled by `assets/front.css`.

## Recent development changes

### 1.2.2 development preview

- Added **Home only** page visibility mode.
- Fixed checkbox saving so unchecked options remain unchecked after saving.
- Improved popup radius and shadow application on the front-end dialog.
- Added fade-in and fade-out open/close animation.
- Reduced dot size and opacity.
- Simplified arrow styling by removing hover background color and using opacity only.
- Improved KBoard card fallback behavior.
- Reorganized the admin settings UI into clearer cards.

## Current repository structure

```text
eo-korean-slide-popup/
├── assets/
│   ├── admin.css
│   ├── admin.js
│   ├── front.css
│   └── front.js
├── examples/
│   ├── README.md
│   └── website-open-popup.html
├── includes/
│   ├── class-eoksp-admin.php
│   ├── class-eoksp-front.php
│   ├── class-eoksp-helper.php
│   └── class-eoksp-plugin.php
├── CHANGELOG.md
├── ROADMAP.md
├── eo-korean-slide-popup.php
└── readme.txt
```

## Roadmap

### Short-term cleanup

- Improve admin UI wording and option grouping
- Add screenshots and usage examples
- Test the WordPress editor experience for HTML slides
- Test with common Korean WordPress themes
- Test with KBoard installations
- Add safer migration handling for saved popup settings from earlier development versions

### Stable release goals

- Safer default popup settings
- Better mobile popup layout
- Cleaner accessibility behavior
- More predictable KBoard card parsing
- Import/export popup settings
- WordPress.org-compatible readme cleanup
- Korean and English documentation

### Possible future features

- Popup templates for event, notice, admission, holiday, and promotion use cases
- Simple analytics for views and closes
- Page builder compatibility checks
- KBoard category-based popup creation
- Shortcode or block-based manual popup embedding

## Development notes

This plugin currently uses:

- WordPress custom post type for popup management
- Post meta for popup settings and slide data
- Vanilla JavaScript for front-end popup behavior
- WordPress media uploader for image selection
- WordPress editor scripts for HTML slide editing
- CSS custom properties for front-end styling

## Security and privacy

- The plugin stores popup settings and slide content in WordPress post meta.
- It does not intentionally send popup data to an external service.
- KBoard URL cards are resolved locally where possible.
- Site operators should avoid placing sensitive personal information in public popup content.

## Contributing

This project is currently maintained as an early-stage open-source plugin. Issues, bug reports, UI suggestions, and KBoard compatibility notes are welcome.

## License

GPLv2 or later.

## Korean summary

한국형 홈페이지 운영에 맞춘 워드프레스 슬라이드 팝업 플러그인입니다. 이미지, 영상, 워드프레스 에디터 HTML, KBoard 게시글 카드 팝업을 지원합니다. 현재는 개발 프리뷰 단계이며, 실사용 테스트와 관리자 UI 개선을 진행 중입니다.
