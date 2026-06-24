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
- Include or exclude specific pages by path or slug
- Center, top, bottom, left, and right popup positioning
- Custom width, max-width, radius, background, shadow, and overlay
- Open delay and auto-close
- One-day hide button with cookie duration setting
- Slider arrows, dots, loop, and autoplay options

### KBoard support

When KBoard is installed, the plugin can use a KBoard post URL to create a card-style popup from the post content.

Current KBoard card goals:

- Resolve title, excerpt, and representative image from a KBoard URL
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
5. Set display options such as date range, device, audience, and position.
6. Publish the popup.

For a simple event popup, use an image slide with a link URL.

For a notice popup, use an HTML slide. The HTML slide field now supports the WordPress editor, so site managers can write headings, paragraphs, lists, links, and media without typing every tag manually.

For a KBoard announcement popup, use a KBoard post URL and select the KBoard card style.

## HTML popup sample

A website open announcement sample is included at:

```text
examples/website-open-popup.html
```

Recommended popup settings for the sample:

- Slide type: HTML
- Position: Center
- Width: 640 to 720px
- Overlay: On
- Title bar: Optional
- Hide today button: On

The sample uses the `.eoksp-open-popup` CSS class, which is styled by `assets/front.css`.

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

- Standardize plugin version and stable tag
- Improve admin UI wording and option grouping
- Separate basic settings and advanced settings
- Add screenshots and usage examples
- Test the WordPress editor experience for HTML slides
- Test with common Korean WordPress themes
- Test with KBoard installations

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

The project is intentionally kept lightweight. Heavy slider libraries and complex page-builder dependencies are avoided unless they become necessary.

## Security and privacy notes

- The plugin does not require an external API.
- The plugin does not intentionally send visitor data to external services.
- Video embeds may load third-party resources depending on the video URL used by the site operator.
- KBoard URL cards are intended to resolve content from the same WordPress site.
- HTML slide content is sanitized through WordPress post-content sanitization before rendering.

## Contributing

Contributions are welcome after the first cleanup phase.

Useful contribution areas:

- WordPress coding standards cleanup
- Accessibility improvements
- Mobile UI testing
- KBoard compatibility testing
- Korean documentation improvements
- Bug reports with theme/plugin conflict details

When reporting a bug, please include:

- WordPress version
- PHP version
- Theme name
- KBoard version, if relevant
- Browser/device
- Steps to reproduce the issue

## License

This project is licensed under the GNU General Public License v2.0.

## Korean summary

EO Korean Slide Popup은 한국형 워드프레스 홈페이지 운영에 맞춘 가벼운 슬라이드 팝업 플러그인입니다. 이미지 팝업, 영상 팝업, 워드프레스 에디터 기반 HTML 공지 팝업, KBoard 게시글 카드 팝업을 지원하는 것을 목표로 합니다.

현재는 초기 공개 버전이며, 안정 배포 전까지 코드 정리, 관리자 화면 개선, 문서화, KBoard 연동 테스트를 진행할 예정입니다.
