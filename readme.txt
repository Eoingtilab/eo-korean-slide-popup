=== EO Korean Slide Popup ===
Contributors: eoingti
Tags: popup, slider, modal, kboard, youtube, vimeo
Requires at least: 5.8
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.2.3
License: GPLv2 or later

가볍고 테마 충돌이 적은 한국형 슬라이드 팝업 플러그인입니다.

== Description ==
- 이미지, 유튜브/비메오/MP4 영상, 워드프레스 에디터 기반 HTML, KBoard URL 카드 슬라이드 지원
- 홈 화면만 노출, 전체 페이지 노출, 특정 페이지 포함/제외 조건 지원
- 팝업별 개별 사이즈, 위치, 타이틀, 라운드, 쉐도우, 자동닫기, 오버레이, 오늘 하루 숨김 지원
- 여러 팝업 활성화 시 우선순위 순차 노출
- KBoard가 설치된 사이트에서는 게시물 URL만으로 제목/본문/대표 이미지 카드 팝업 가능
- KBoard 게시글 정보를 불러오지 못해도 링크와 대체 텍스트를 유지
- 순수 CSS/JS 기반으로 테마 충돌 최소화

== Installation ==
1. GitHub 기본 Download ZIP은 폴더명이 `eo-korean-slide-popup-main`으로 내려올 수 있으므로, 워드프레스 업로드용 ZIP은 최상위 폴더명이 반드시 `eo-korean-slide-popup`인지 확인합니다.
2. 기존 버전을 교체할 때는 기존 `eo-korean-slide-popup` 폴더를 백업 또는 삭제한 뒤 업로드합니다.
3. 플러그인 업로드 후 활성화합니다.
4. 관리자 메뉴의 슬라이드 팝업에서 새 팝업을 추가합니다.
5. 슬라이드와 팝업 설정을 저장합니다.
6. 공개 페이지에서 자동 노출됩니다.

== Changelog ==
= 1.2.3 =
* 워드프레스 설치용 ZIP 폴더명 문제 안내 추가
* 기존 버전과 중복 설치되지 않도록 배포 패키지 기준 정리
* 최종 테스트 전 버전 캐시 갱신용 버전 업데이트

= 1.2.2 =
* 홈 화면만 노출 옵션 추가
* 체크박스 해제 후 저장 시 다시 체크되는 문제 수정
* 팝업 라운드/쉐도우 적용 개선
* 페이드인/페이드아웃 애니메이션 추가
* 도트 크기와 투명도 조정
* 화살표 hover 배경 제거 및 opacity 중심으로 개선
* KBoard 카드 불러오기 실패 시 대체 출력 개선
* 관리자 설정 UI 개선

= 1.2.1 =
* GitHub 공개용 초기 정리
* 메인 플러그인 버전과 Stable tag 통일
* HTML 슬라이드에 워드프레스 기본 에디터 입력 방식 추가
* examples/website-open-popup.html 샘플 추가

= 1.0.0 =
* 최초 배포
