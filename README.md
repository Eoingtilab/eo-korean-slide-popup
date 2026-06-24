# EO Korean Slide Popup

A lightweight WordPress slide popup plugin designed for Korean websites.

EO Korean Slide Popup helps site operators create practical announcement popups without relying on heavy, outdated, or theme-breaking popup plugins. It supports image slides, video slides, WordPress-editor-based HTML slides, and KBoard post-card popups for Korean WordPress sites.

> Project status: early-stage public release. The plugin is usable as a development preview, but the codebase is still being cleaned up before a stable production release.

## Important installation note

Do not upload GitHub's default **Code > Download ZIP** file directly to WordPress if you are replacing an existing installation.

GitHub usually creates a folder named:

```text
eo-korean-slide-popup-main
```

WordPress treats that as a different plugin folder from:

```text
eo-korean-slide-popup
```

For WordPress upload, use a ZIP whose top-level folder is exactly:

```text
eo-korean-slide-popup
```

If you already installed both folders, deactivate both, delete the duplicate `eo-korean-slide-popup-main` plugin, then install the correctly packaged ZIP.

## Main features

- Image slide with optional link
- YouTube, Vimeo, and MP4 video slide
- HTML slide with WordPress visual/text editor support
- KBoard URL card slide
- Start date and end date
- Priority-based popup queue
- Desktop-only, mobile-only, or all-device display
- Guest-only, member-only, or all-user display
- All pages, home only, include rules, and exclude rules
- Center, top, bottom, left, and right popup positioning
- Custom width, max-width, radius, background, shadow, and overlay
- Fade-in and fade-out popup animation
- One-day hide button with cookie duration setting
- Slider arrows, dots, loop, and autoplay options

## KBoard support

When KBoard is installed, the plugin can use a KBoard post URL to create a card-style popup from the post content.

Current KBoard card behavior:

- Try to resolve title, excerpt, and image from a KBoard URL
- Try the KBoard class first
- Fall back to the KBoard database table when possible
- Keep the original link usable even when the card data cannot be resolved
- Use safe fallback text instead of breaking the popup layout

## Requirements

- WordPress 5.8 or higher
- PHP 7.4 or higher
- KBoard is optional and only required for KBoard URL card popups

## Basic usage

1. Open **Slide Popup > Add New**.
2. Enter the popup title.
3. Add slide content.
4. Choose content type: image, video, HTML, or KBoard URL card.
5. Set display options such as date range, device, audience, page visibility, and position.
6. Publish the popup.

For a notice popup, use an HTML slide. The HTML slide field supports the WordPress editor, so site managers can write headings, paragraphs, lists, links, and media without typing every tag manually.

For a KBoard announcement popup, use a KBoard post URL and select the KBoard card style. If the plugin cannot resolve the KBoard title or content, the card still keeps the link and uses fallback text.

## HTML popup sample

A website open announcement sample is included at:

```text
examples/website-open-popup.html
```

Recommended popup settings for the sample:

- Slide type: HTML / Editor
- Page visibility: Home only
- Position: Center
- Width: 640 to 720px
- Overlay: On
- Hide today button: On

## Recent development changes

### 1.2.3 development preview

- Added install-package warning for GitHub ZIP folder naming.
- Rebuilt the WordPress installable ZIP with the correct root folder.
- Rechecked PHP syntax for all plugin PHP files.

### 1.2.2 development preview

- Added **Home only** page visibility mode.
- Fixed checkbox saving so unchecked options remain unchecked after saving.
- Improved popup radius and shadow application on the front-end dialog.
- Added fade-in and fade-out open/close animation.
- Reduced dot size and opacity.
- Simplified arrow styling by removing hover background color and using opacity only.
- Improved KBoard card fallback behavior.
- Reorganized the admin settings UI into clearer sections.

## Security and privacy

- The plugin stores popup settings and slide content in WordPress post meta.
- It does not intentionally send popup data to an external service.
- KBoard URL cards are resolved locally where possible.
- Site operators should avoid placing sensitive personal information in public popup content.

## License

GPLv2 or later.

## Korean summary

한국형 홈페이지 운영에 맞춘 워드프레스 슬라이드 팝업 플러그인입니다. 이미지, 영상, 워드프레스 에디터 HTML, KBoard 게시글 카드 팝업을 지원합니다. 현재는 개발 프리뷰 단계이며, 실사용 테스트와 관리자 UI 개선을 진행 중입니다.
