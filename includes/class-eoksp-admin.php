<?php
namespace EOKSP;

if (!defined('ABSPATH')) {
	exit;
}

class Admin {
	public function __construct() {
		add_action('init', array($this, 'register_post_type'));
		add_action('admin_menu', array($this, 'register_admin_menu'));
		add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
		add_action('save_post_eoksp_popup', array($this, 'save_popup'));
		add_action('admin_enqueue_scripts', array($this, 'enqueue_assets'));
		add_filter('manage_eoksp_popup_posts_columns', array($this, 'admin_columns'));
		add_action('manage_eoksp_popup_posts_custom_column', array($this, 'render_admin_columns'), 10, 2);
		add_filter('plugin_action_links_' . plugin_basename(EOKSP_FILE), array($this, 'plugin_action_links'));
		add_filter('parent_file', array($this, 'highlight_admin_menu'));
		add_filter('submenu_file', array($this, 'highlight_admin_submenu'));
	}

	public function register_post_type() {
		register_post_type('eoksp_popup', array(
			'labels' => array(
				'name' => '슬라이드 팝업',
				'singular_name' => '슬라이드 팝업',
				'menu_name' => '슬라이드 팝업',
				'add_new' => '새 팝업 추가',
				'add_new_item' => '새 팝업 추가',
				'edit_item' => '팝업 수정',
				'new_item' => '새 팝업',
				'view_item' => '팝업 보기',
				'search_items' => '팝업 검색',
				'not_found' => '팝업이 없습니다.',
			),
			'public' => false,
			'show_ui' => true,
			'show_in_menu' => false,
			'supports' => array('title'),
			'capability_type' => 'post',
			'map_meta_cap' => true,
		));
	}

	public function register_admin_menu() {
		add_menu_page('슬라이드 팝업', '슬라이드 팝업', 'manage_options', 'eoksp-popup-dashboard', array($this, 'render_dashboard_page'), 'dashicons-format-gallery', 58);
		add_submenu_page('eoksp-popup-dashboard', '관리 홈', '관리 홈', 'manage_options', 'eoksp-popup-dashboard', array($this, 'render_dashboard_page'));
		add_submenu_page('eoksp-popup-dashboard', '팝업 목록', '팝업 목록', 'manage_options', 'edit.php?post_type=eoksp_popup');
		add_submenu_page('eoksp-popup-dashboard', '새 팝업 추가', '새 팝업 추가', 'manage_options', 'post-new.php?post_type=eoksp_popup');
	}

	public function highlight_admin_menu($parent_file) {
		$screen = get_current_screen();
		if ($screen && $screen->post_type === 'eoksp_popup') {
			return 'eoksp-popup-dashboard';
		}
		return $parent_file;
	}

	public function highlight_admin_submenu($submenu_file) {
		$screen = get_current_screen();
		if ($screen && $screen->post_type === 'eoksp_popup') {
			return $screen->base === 'post' ? 'edit.php?post_type=eoksp_popup' : $submenu_file;
		}
		return $submenu_file;
	}

	public function plugin_action_links($links) {
		array_unshift(
			$links,
			'<a href="' . esc_url(admin_url('edit.php?post_type=eoksp_popup')) . '">설정</a>',
			'<a href="' . esc_url(admin_url('post-new.php?post_type=eoksp_popup')) . '">새 팝업 추가</a>'
		);
		return $links;
	}

	public function render_dashboard_page() {
		if (!current_user_can('manage_options')) {
			wp_die('권한이 없습니다.');
		}
		$count_all = wp_count_posts('eoksp_popup');
		$published = isset($count_all->publish) ? intval($count_all->publish) : 0;
		?>
		<div class="wrap eoksp-dashboard">
			<h1>슬라이드 팝업</h1>
			<p class="eoksp-dashboard-intro">한국형 홈페이지 운영에 맞춘 가벼운 슬라이드 팝업 플러그인입니다. HTML 타입은 워드프레스 기본 에디터로 쉽게 작성할 수 있습니다.</p>
			<div class="eoksp-dashboard-cards">
				<div class="eoksp-dashboard-card">
					<h2>현재 팝업</h2>
					<p class="eoksp-dashboard-number"><?php echo esc_html($published); ?></p>
					<p><a class="button button-primary" href="<?php echo esc_url(admin_url('edit.php?post_type=eoksp_popup')); ?>">팝업 목록 열기</a></p>
				</div>
				<div class="eoksp-dashboard-card">
					<h2>빠른 시작</h2>
					<ol><li>새 팝업 추가</li><li>슬라이드 타입 선택</li><li>노출 조건 설정</li><li>발행 후 확인</li></ol>
					<p><a class="button" href="<?php echo esc_url(admin_url('post-new.php?post_type=eoksp_popup')); ?>">새 팝업 만들기</a></p>
				</div>
				<div class="eoksp-dashboard-card">
					<h2>지원 콘텐츠</h2>
					<ul><li>이미지</li><li>영상 URL</li><li>워드프레스 에디터 HTML</li><li>KBoard URL 카드</li></ul>
				</div>
			</div>
		</div>
		<?php
	}

	public function add_meta_boxes() {
		add_meta_box('eoksp_popup_slides', '팝업 슬라이드 콘텐츠', array($this, 'render_slides_box'), 'eoksp_popup', 'normal', 'high');
		add_meta_box('eoksp_popup_settings', '팝업 설정', array($this, 'render_settings_box'), 'eoksp_popup', 'normal', 'default');
		add_meta_box('eoksp_popup_preview', '관리자 미리보기', array($this, 'render_preview_box'), 'eoksp_popup', 'side', 'high');
		add_meta_box('eoksp_popup_help', '운영 팁', array($this, 'render_help_box'), 'eoksp_popup', 'side', 'default');
	}

	public function enqueue_assets($hook) {
		global $post_type;
		$is_popup_editor = in_array($hook, array('post.php', 'post-new.php'), true) && $post_type === 'eoksp_popup';
		$is_dashboard = $hook === 'toplevel_page_eoksp-popup-dashboard';
		if (!$is_popup_editor && !$is_dashboard) {
			return;
		}
		wp_enqueue_style('eoksp-admin', EOKSP_URL . 'assets/admin.css', array(), EOKSP_VERSION);
		if ($is_popup_editor) {
			wp_enqueue_media();
			wp_enqueue_editor();
			wp_enqueue_script('eoksp-admin', EOKSP_URL . 'assets/admin.js', array('jquery'), EOKSP_VERSION, true);
			wp_localize_script('eoksp-admin', 'EOKSPAdmin', array(
				'chooseImage' => '이미지 선택',
				'useImage' => '이 이미지 사용',
				'confirmRemove' => '이 슬라이드를 삭제하시겠습니까?',
			));
		}
	}

	public function render_slides_box($post) {
		wp_nonce_field('eoksp_save_popup', 'eoksp_nonce');
		$slides = Helper::get_popup_slides($post->ID);
		?>
		<div class="eoksp-metabox eoksp-slides-wrap">
			<p class="eoksp-description">이미지, 영상, HTML, KBoard URL 카드 타입을 혼합해서 사용할 수 있습니다. HTML 타입은 워드프레스 기본 에디터로 입력됩니다.</p>
			<div id="eoksp-slides-list" class="eoksp-slides-list">
				<?php
				if ($slides) {
					foreach ($slides as $index => $slide) {
						$this->render_slide_item($index, $slide);
					}
				}
				?>
			</div>
			<button type="button" class="button button-primary" id="eoksp-add-slide">슬라이드 추가</button>
		</div>
		<script type="text/template" id="tmpl-eoksp-slide-item">
			<?php $this->render_slide_item('__INDEX__', array()); ?>
		</script>
		<?php
	}

	private function render_slide_item($index, $slide = array()) {
		$slide = wp_parse_args($slide, array(
			'title' => '',
			'type' => 'image',
			'image_id' => 0,
			'image_url' => '',
			'image_alt' => '',
			'link_url' => '',
			'link_target' => '0',
			'video_url' => '',
			'html' => '',
			'kboard_url' => '',
			'button_label' => '',
		));
		$image_preview = $slide['image_id'] ? wp_get_attachment_image_url($slide['image_id'], 'medium') : $slide['image_url'];
		$editor_id = 'eoksp_html_editor_' . preg_replace('/[^a-zA-Z0-9_]/', '_', (string) $index);
		?>
		<div class="eoksp-slide-item" data-slide-item>
			<div class="eoksp-slide-head"><strong>슬라이드</strong><button type="button" class="button-link-delete eoksp-remove-slide">삭제</button></div>
			<div class="eoksp-grid eoksp-grid-2">
				<p><label>관리용 이름</label><input type="text" name="eoksp_slides[<?php echo esc_attr($index); ?>][title]" value="<?php echo esc_attr($slide['title']); ?>" class="widefat"></p>
				<p><label>콘텐츠 타입</label><select name="eoksp_slides[<?php echo esc_attr($index); ?>][type]" class="widefat eoksp-slide-type"><option value="image" <?php selected($slide['type'], 'image'); ?>>이미지</option><option value="video" <?php selected($slide['type'], 'video'); ?>>영상</option><option value="html" <?php selected($slide['type'], 'html'); ?>>HTML / 에디터</option><option value="kboard" <?php selected($slide['type'], 'kboard'); ?>>KBoard URL 카드</option></select></p>
			</div>
			<div class="eoksp-type-panel eoksp-type-image" <?php echo $slide['type'] !== 'image' ? 'style="display:none;"' : ''; ?>>
				<div class="eoksp-media-box"><input type="hidden" name="eoksp_slides[<?php echo esc_attr($index); ?>][image_id]" value="<?php echo esc_attr($slide['image_id']); ?>" class="eoksp-image-id"><input type="hidden" name="eoksp_slides[<?php echo esc_attr($index); ?>][image_url]" value="<?php echo esc_attr($slide['image_url']); ?>" class="eoksp-image-url"><div class="eoksp-image-preview-wrap <?php echo $image_preview ? 'has-image' : ''; ?>"><img src="<?php echo esc_url($image_preview); ?>" alt="" class="eoksp-image-preview"></div><div class="eoksp-media-actions"><button type="button" class="button eoksp-select-image">이미지 선택</button><button type="button" class="button eoksp-remove-image">이미지 제거</button></div></div>
				<div class="eoksp-grid eoksp-grid-2"><p><label>대체 텍스트</label><input type="text" name="eoksp_slides[<?php echo esc_attr($index); ?>][image_alt]" value="<?php echo esc_attr($slide['image_alt']); ?>" class="widefat"></p><p><label>클릭 링크 URL</label><input type="url" name="eoksp_slides[<?php echo esc_attr($index); ?>][link_url]" value="<?php echo esc_attr($slide['link_url']); ?>" class="widefat"></p></div>
				<p><label><input type="checkbox" name="eoksp_slides[<?php echo esc_attr($index); ?>][link_target]" value="1" <?php checked($slide['link_target'], '1'); ?>> 새 창으로 열기</label></p>
			</div>
			<div class="eoksp-type-panel eoksp-type-video" <?php echo $slide['type'] !== 'video' ? 'style="display:none;"' : ''; ?>><p><label>영상 URL</label><input type="url" name="eoksp_slides[<?php echo esc_attr($index); ?>][video_url]" value="<?php echo esc_attr($slide['video_url']); ?>" class="widefat" placeholder="https://youtu.be/... 또는 mp4 URL"></p></div>
			<div class="eoksp-type-panel eoksp-type-html" <?php echo $slide['type'] !== 'html' ? 'style="display:none;"' : ''; ?>><div class="eoksp-html-editor-wrap"><label for="<?php echo esc_attr($editor_id); ?>">HTML 콘텐츠</label><textarea id="<?php echo esc_attr($editor_id); ?>" name="eoksp_slides[<?php echo esc_attr($index); ?>][html]" rows="10" class="widefat eoksp-html-editor" data-eoksp-editor="1"><?php echo esc_textarea($slide['html']); ?></textarea><p class="description">제목, 문단, 목록, 링크를 워드프레스 기본 에디터로 입력할 수 있습니다. 세밀한 수정은 텍스트/HTML 모드에서 조정하세요.</p></div></div>
			<div class="eoksp-type-panel eoksp-type-kboard" <?php echo $slide['type'] !== 'kboard' ? 'style="display:none;"' : ''; ?>><p><label>KBoard 게시물 URL</label><input type="url" name="eoksp_slides[<?php echo esc_attr($index); ?>][kboard_url]" value="<?php echo esc_attr($slide['kboard_url']); ?>" class="widefat"></p><p><label>버튼 문구</label><input type="text" name="eoksp_slides[<?php echo esc_attr($index); ?>][button_label]" value="<?php echo esc_attr($slide['button_label']); ?>" class="widefat" placeholder="자세히 보기"></p></div>
		</div>
		<?php
	}

	public function render_settings_box($post) {
		$settings = Helper::get_popup_settings($post->ID);
		?>
		<div class="eoksp-metabox">
			<div class="eoksp-section"><h3>기본 설정</h3><div class="eoksp-grid eoksp-grid-3"><p><label><input type="checkbox" name="eoksp_settings[enabled]" value="1" <?php checked($settings['enabled'], '1'); ?>> 팝업 활성화</label></p><p><label>우선순위</label><input type="number" name="eoksp_settings[priority]" value="<?php echo esc_attr($settings['priority']); ?>" class="small-text"></p><p><label>디바이스</label><select name="eoksp_settings[device]" class="widefat"><option value="all" <?php selected($settings['device'], 'all'); ?>>전체</option><option value="desktop" <?php selected($settings['device'], 'desktop'); ?>>PC만</option><option value="mobile" <?php selected($settings['device'], 'mobile'); ?>>모바일만</option></select></p></div></div>
			<div class="eoksp-section"><h3>노출 기간 / 대상</h3><div class="eoksp-grid eoksp-grid-2"><p><label>노출 시작일</label><input type="date" name="eoksp_settings[start_date]" value="<?php echo esc_attr($settings['start_date']); ?>"></p><p><label>노출 종료일</label><input type="date" name="eoksp_settings[end_date]" value="<?php echo esc_attr($settings['end_date']); ?>"></p><p><label>대상 사용자</label><select name="eoksp_settings[audience]" class="widefat"><option value="all" <?php selected($settings['audience'], 'all'); ?>>전체</option><option value="guest" <?php selected($settings['audience'], 'guest'); ?>>비회원</option><option value="member" <?php selected($settings['audience'], 'member'); ?>>회원</option></select></p><p><label>페이지 노출 범위</label><select name="eoksp_settings[visibility_mode]" class="widefat"><option value="all" <?php selected($settings['visibility_mode'], 'all'); ?>>전체 페이지</option><option value="include" <?php selected($settings['visibility_mode'], 'include'); ?>>특정 페이지에서만</option><option value="exclude" <?php selected($settings['visibility_mode'], 'exclude'); ?>>특정 페이지 제외</option></select></p></div><p><label>페이지 조건</label><textarea name="eoksp_settings[target_rules]" rows="4" class="widefat code" placeholder="예시&#10;/event/*&#10;15&#10;contact"><?php echo esc_textarea($settings['target_rules']); ?></textarea></p></div>
			<div class="eoksp-section"><h3>레이아웃</h3><div class="eoksp-grid eoksp-grid-3"><p><label>위치</label><select name="eoksp_settings[position]" class="widefat"><?php foreach (array('center'=>'정중앙','top-left'=>'좌상단','top-center'=>'상단 중앙','top-right'=>'우상단','bottom-left'=>'좌하단','bottom-center'=>'하단 중앙','bottom-right'=>'우하단') as $value => $label) : ?><option value="<?php echo esc_attr($value); ?>" <?php selected($settings['position'], $value); ?>><?php echo esc_html($label); ?></option><?php endforeach; ?></select></p><p><label>가로 너비(px)</label><input type="number" name="eoksp_settings[width]" value="<?php echo esc_attr($settings['width']); ?>" class="small-text"></p><p><label>최대 너비(vw)</label><input type="number" name="eoksp_settings[max_width]" value="<?php echo esc_attr($settings['max_width']); ?>" class="small-text"></p></div></div>
			<div class="eoksp-section"><h3>닫기 / 숨김 / 슬라이드</h3><div class="eoksp-grid eoksp-grid-3"><p><label><input type="checkbox" name="eoksp_settings[title_bar]" value="1" <?php checked($settings['title_bar'], '1'); ?>> 타이틀 바 사용</label></p><p><label><input type="checkbox" name="eoksp_settings[overlay]" value="1" <?php checked($settings['overlay'], '1'); ?>> 오버레이 사용</label></p><p><label><input type="checkbox" name="eoksp_settings[show_close]" value="1" <?php checked($settings['show_close'], '1'); ?>> 닫기 버튼 표시</label></p><p><label><input type="checkbox" name="eoksp_settings[hide_today]" value="1" <?php checked($settings['hide_today'], '1'); ?>> 오늘 하루 보지 않기</label></p><p><label><input type="checkbox" name="eoksp_settings[show_arrows]" value="1" <?php checked($settings['show_arrows'], '1'); ?>> 화살표 표시</label></p><p><label><input type="checkbox" name="eoksp_settings[show_dots]" value="1" <?php checked($settings['show_dots'], '1'); ?>> 도트 표시</label></p></div><p><label>타이틀 문구</label><input type="text" name="eoksp_settings[title_text]" value="<?php echo esc_attr($settings['title_text']); ?>" class="widefat"></p></div>
		</div>
		<?php
	}

	public function render_preview_box($post) {
		$preview_url = add_query_arg(array('eoksp_preview' => $post->ID, 'eoksp_nonce' => wp_create_nonce('eoksp_preview_' . $post->ID)), home_url('/'));
		echo '<p>저장 후 실제 프론트 스타일로 확인합니다.</p><p><a class="button button-primary button-large" href="' . esc_url($preview_url) . '" target="_blank" rel="noopener noreferrer">미리보기 열기</a></p>';
	}

	public function render_help_box() {
		echo '<div class="eoksp-help-box"><p><strong>운영 팁</strong></p><ul><li>HTML 타입은 워드프레스 기본 에디터로 작성할 수 있습니다.</li><li>웹사이트 오픈 공지는 examples/website-open-popup.html 샘플을 참고하세요.</li><li>KBoard 연동은 게시물 상세 URL을 입력하면 카드형 팝업으로 출력됩니다.</li></ul></div>';
	}

	public function save_popup($post_id) {
		if (!isset($_POST['eoksp_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['eoksp_nonce'])), 'eoksp_save_popup')) {
			return;
		}
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}
		if (!current_user_can('edit_post', $post_id)) {
			return;
		}
		$settings = isset($_POST['eoksp_settings']) ? wp_unslash($_POST['eoksp_settings']) : array();
		$slides = isset($_POST['eoksp_slides']) ? wp_unslash($_POST['eoksp_slides']) : array();
		update_post_meta($post_id, '_eoksp_settings', Helper::sanitize_settings($settings));
		update_post_meta($post_id, '_eoksp_slides', Helper::sanitize_slides($slides));
	}

	public function admin_columns($columns) {
		$new = array();
		foreach ($columns as $key => $label) {
			$new[$key] = $label;
			if ($key === 'title') {
				$new['eoksp_status'] = '상태';
				$new['eoksp_priority'] = '우선순위';
				$new['eoksp_period'] = '기간';
			}
		}
		return $new;
	}

	public function render_admin_columns($column, $post_id) {
		$settings = Helper::get_popup_settings($post_id);
		if ($column === 'eoksp_status') {
			echo Helper::is_popup_active($post_id, $settings) ? '<span style="color:#0a7d35;font-weight:700;">활성</span>' : '<span style="color:#777;">비활성</span>';
		}
		if ($column === 'eoksp_priority') {
			echo esc_html($settings['priority']);
		}
		if ($column === 'eoksp_period') {
			$start = $settings['start_date'] ?: '즉시';
			$end = $settings['end_date'] ?: '제한 없음';
			echo esc_html($start . ' ~ ' . $end);
		}
	}
}
