=== EO Korean Slide Popup ===
Contributors: eoingti
Tags: popup, slider, modal, kboard, youtube, vimeo
Requires at least: 5.8
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.2.5
License: GPLv2 or later

가볍고 테마 충돌이 적은 한국형 슬라이드 팝업 플러그인입니다.

== Description ==
- 이미지, 유튜브/비메오/MP4 영상, 워드프레스 에디터 기반 HTML, KBoard URL 카드 슬라이드 지원
- 홈 화면만 노출, 전체 페이지 노출, 특정 페이지 포함/제외 조건 지원
- 팝업별 개별 사이즈, 위치, 타이틀, 라운드, 쉐도우, 자동닫기, 오버레이, 오늘 하루 숨김 지원
- 여러 팝업 활성화 시 우선순위 순차 노출
- KBoard가 설치된 사이트에서는 게시물 URL만으로 제목/본문/대표 이미지 카드 팝업 가능
- KBoard 게시글 정보를 불러오지 못해도 링크와 대체 텍스트를 유지
- 이미지/영상 팝업은 불필요한 흰 카드 배경 없이 콘텐츠 자체에 라운드와 쉐도우를 적용
- 순수 CSS/JS 기반으로 테마 충돌 최소화

== Installation ==
1. WordPress 관리자 > 플러그인 > 새로 추가 > 플러그인 업로드
2. 설치용 ZIP 파일 업로드
3. 플러그인 활성화
4. 슬라이드 팝업 > 새 팝업 추가

GitHub 기본 Download ZIP은 `eo-korean-slide-popup-main` 폴더로 설치될 수 있습니다. 기존 플러그인을 업데이트하려면 최상위 폴더명이 `eo-korean-slide-popup`인 설치용 ZIP을 사용하세요.

== Changelog ==
= 1.2.5 =
- 지원하지 않는 영상 URL이 현재 워드프레스 페이지로 임베드되는 문제 방지
- 영상 임베드는 YouTube, Vimeo, 직접 영상 파일 URL로 제한
- 이미지/영상형 팝업에서 이미지와 영상 자체에 라운드/쉐도우가 적용되도록 정리
- 관리자 설정 화면 여백과 카드 UI 개선
- 테마 버튼 CSS와 충돌하지 않도록 도트/화살표 스타일 보강

= 1.2.4 =
- 이미지/영상형 팝업에서 불필요한 흰 배경 박스가 생기던 구조 개선
- 이미지와 영상 콘텐츠 자체에 라운드/쉐도우 적용
- 도트와 화살표 스타일을 테마 CSS와 충돌하지 않도록 강화
- 상단 타이틀/닫기 버튼을 미디어형 팝업에서는 은은한 플로팅 칩 형태로 정리
- 관리자 설정 UI 여백과 카드 디자인 개선

= 1.2.3 =
- WordPress 설치용 ZIP 폴더명 안내 추가
- GitHub 기본 ZIP 설치 시 중복 플러그인으로 설치될 수 있는 문제 안내
- 설치용 ZIP 패키지 구조 정리

= 1.2.2 =
- 홈 화면만 노출 옵션 추가
- 체크박스 저장 문제 수정
- KBoard 카드 fallback 보강
- 도트/화살표/라운드/쉐도우 스타일 개선

= 1.2.1 =
- 초기 GitHub 공개 버전
