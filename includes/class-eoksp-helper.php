<?php
namespace EOKSP;

if (!defined('ABSPATH')) {
	exit;
}

class Helper {
	public static function defaults() {
		return array(
			'enabled'          => '1',
			'priority'         => 10,
			'start_date'       => '',
			'end_date'         => '',
			'device'           => 'all',
			'audience'         => 'all',
			'visibility_mode'  => 'all',
			'target_rules'     => '',
			'position'         => 'center',
			'offset_x'         => '',
			'offset_y'         => '',
			'width'            => '720',
			'max_width'        => '90',
			'height_mode'      => 'auto',
			'height'           => '',
			'title_bar'        => '1',
			'title_text'       => '',
			'overlay'          => '1',
			'overlay_color'    => 'rgba(15, 23, 42, 0.62)',
			'overlay_close'    => '1',
			'background_color' => '#ffffff',
			'radius'           => '18',
			'shadow'           => 'lg',
			'open_delay'       => '0',
			'auto_close'       => '',
			'hide_today'       => '1',
			'cookie_days'      => '1',
			'show_close'       => '1',
			'slider_autoplay'  => '0',
			'slider_speed'     => '3500',
			'slider_loop'      => '1',
			'show_arrows'      => '1',
			'show_dots'        => '1',
			'kboard_style'     => 'news',
			'zindex'           => '999999',
		);
	}

	public static function sanitize_settings($input) {
		$defaults = self::defaults();
		$raw = is_array($input) ? $input : array();
		$settings = wp_parse_args($raw, $defaults);

		$checkbox_fields = array(
			'enabled',
			'title_bar',
			'overlay',
			'overlay_close',
			'hide_today',
			'show_close',
			'slider_autoplay',
			'slider_loop',
			'show_arrows',
			'show_dots',
		);

		foreach ($checkbox_fields as $field) {
			$settings[$field] = !empty($raw[$field]) ? '1' : '0';
		}

		$settings['priority']         = intval($settings['priority']);
		$settings['start_date']       = self::sanitize_date($settings['start_date']);
		$settings['end_date']         = self::sanitize_date($settings['end_date']);
		$settings['device']           = self::sanitize_choice($settings['device'], array('all', 'desktop', 'mobile'), 'all');
		$settings['audience']         = self::sanitize_choice($settings['audience'], array('all', 'guest', 'member'), 'all');
		$settings['visibility_mode']  = self::sanitize_choice($settings['visibility_mode'], array('all', 'home', 'include', 'exclude'), 'all');
		$settings['target_rules']     = self::sanitize_multiline_text($settings['target_rules']);
		$settings['position']         = self::sanitize_choice($settings['position'], array('center', 'top-left', 'top-center', 'top-right', 'middle-left', 'middle-right', 'bottom-left', 'bottom-center', 'bottom-right'), 'center');
		$settings['offset_x']         = self::sanitize_dimension($settings['offset_x']);
		$settings['offset_y']         = self::sanitize_dimension($settings['offset_y']);
		$settings['width']            = self::sanitize_positive_number($settings['width'], '720');
		$settings['max_width']        = self::sanitize_positive_number($settings['max_width'], '90');
		$settings['height_mode']      = self::sanitize_choice($settings['height_mode'], array('auto', 'fixed'), 'auto');
		$settings['height']           = self::sanitize_positive_number($settings['height'], '');
		$settings['title_text']       = sanitize_text_field($settings['title_text']);
		$settings['overlay_color']    = self::sanitize_color_value($settings['overlay_color'], 'rgba(15, 23, 42, 0.62)');
		$settings['background_color'] = self::sanitize_color_value($settings['background_color'], '#ffffff');
		$settings['radius']           = self::sanitize_positive_number($settings['radius'], '18');
		$settings['shadow']           = self::sanitize_choice($settings['shadow'], array('none', 'sm', 'md', 'lg'), 'lg');
		$settings['open_delay']       = self::sanitize_positive_number($settings['open_delay'], '0');
		$settings['auto_close']       = self::sanitize_positive_number($settings['auto_close'], '');
		$settings['cookie_days']      = self::sanitize_positive_number($settings['cookie_days'], '1');
		$settings['slider_speed']     = self::sanitize_positive_number($settings['slider_speed'], '3500');
		$settings['kboard_style']     = self::sanitize_choice($settings['kboard_style'], array('basic', 'news', 'premium'), 'news');
		$settings['zindex']           = self::sanitize_positive_number($settings['zindex'], '999999');

		return $settings;
	}

	public static function sanitize_slides($slides) {
		$output = array();
		if (!is_array($slides)) {
			return $output;
		}

		foreach ($slides as $slide) {
			if (!is_array($slide)) {
				continue;
			}

			$type = self::sanitize_choice($slide['type'] ?? '', array('image', 'video', 'html', 'kboard'), 'image');

			$item = array(
				'title'        => sanitize_text_field($slide['title'] ?? ''),
				'type'         => $type,
				'image_id'     => absint($slide['image_id'] ?? 0),
				'image_url'    => esc_url_raw($slide['image_url'] ?? ''),
				'image_alt'    => sanitize_text_field($slide['image_alt'] ?? ''),
				'link_url'     => esc_url_raw($slide['link_url'] ?? ''),
				'link_target'  => !empty($slide['link_target']) ? '1' : '0',
				'video_url'    => esc_url_raw($slide['video_url'] ?? ''),
				'html'         => wp_kses_post($slide['html'] ?? ''),
				'kboard_url'   => esc_url_raw($slide['kboard_url'] ?? ''),
				'button_label' => sanitize_text_field($slide['button_label'] ?? ''),
			);

			if (
				($type === 'image' && !$item['image_id'] && !$item['image_url'])
				|| ($type === 'video' && !$item['video_url'])
				|| ($type === 'html' && trim(wp_strip_all_tags($item['html'])) === '')
				|| ($type === 'kboard' && !$item['kboard_url'])
			) {
				continue;
			}

			$output[] = $item;
		}

		return $output;
	}

	public static function get_popup_settings($post_id) {
		$saved = get_post_meta($post_id, '_eoksp_settings', true);
		return wp_parse_args(is_array($saved) ? $saved : array(), self::defaults());
	}

	public static function get_popup_slides($post_id) {
		$slides = get_post_meta($post_id, '_eoksp_slides', true);
		return is_array($slides) ? $slides : array();
	}

	public static function is_popup_active($post_id, $settings = null) {
		if (!$settings) {
			$settings = self::get_popup_settings($post_id);
		}
		if (empty($settings['enabled'])) {
			return false;
		}

		$today = current_time('Y-m-d');
		if (!empty($settings['start_date']) && $settings['start_date'] > $today) {
			return false;
		}
		if (!empty($settings['end_date']) && $settings['end_date'] < $today) {
			return false;
		}

		return true;
	}

	public static function matches_display_context($settings) {
		if (is_admin()) {
			return true;
		}

		$audience = $settings['audience'] ?? 'all';
		if ($audience === 'guest' && is_user_logged_in()) {
			return false;
		}
		if ($audience === 'member' && !is_user_logged_in()) {
			return false;
		}

		$visibility_mode = $settings['visibility_mode'] ?? 'all';

		if ($visibility_mode === 'all') {
			return true;
		}

		if ($visibility_mode === 'home') {
			return is_front_page() || is_home();
		}

		$rules = self::parse_target_rules($settings['target_rules'] ?? '');
		if (empty($rules)) {
			return $visibility_mode === 'exclude';
		}

		$matched = self::current_request_matches_rules($rules);

		if ($visibility_mode === 'include') {
			return $matched;
		}

		if ($visibility_mode === 'exclude') {
			return !$matched;
		}

		return true;
	}

	public static function parse_target_rules($rules) {
		if (!is_string($rules) || $rules === '') {
			return array();
		}

		$rules = str_replace(array("\r\n", "\r"), "\n", $rules);
		$tokens = preg_split('/[\n,]+/', $rules);
		$tokens = array_map('trim', (array) $tokens);
		$tokens = array_filter($tokens, static function ($token) {
			return $token !== '';
		});

		return array_values(array_unique($tokens));
	}

	public static function current_request_matches_rules($rules) {
		foreach ($rules as $rule) {
			if (self::current_request_matches_rule($rule)) {
				return true;
			}
		}

		return false;
	}

	public static function current_request_matches_rule($rule) {
		$rule = trim((string) $rule);
		if ($rule === '') {
			return false;
		}

		$queried_id = get_queried_object_id();
		if (ctype_digit($rule) && $queried_id && intval($rule) === intval($queried_id)) {
			return true;
		}

		$current_path = self::get_current_request_path();
		$normalized_current_path = trim($current_path, '/');
		$rule_path = trim($rule, '/');

		if ($rule === '/' && $current_path === '/') {
			return true;
		}

		if (strpos($rule, '/') === 0) {
			if (substr($rule, -1) === '*') {
				$prefix = trim(substr($rule, 0, -1), '/');
				return $prefix === '' ? true : strpos($normalized_current_path, $prefix) === 0;
			}
			return $normalized_current_path === $rule_path;
		}

		if (is_singular()) {
			$post = get_post($queried_id);
			if ($post && !empty($post->post_name) && sanitize_title($rule) === $post->post_name) {
				return true;
			}
		}

		return $rule_path !== '' && strpos($normalized_current_path, sanitize_title($rule_path)) !== false;
	}

	public static function get_current_request_path() {
		global $wp;

		if (isset($wp->request)) {
			$request = trim((string) $wp->request, '/');
			return $request === '' ? '/' : '/' . $request;
		}

		$request_uri = isset($_SERVER['REQUEST_URI']) ? wp_unslash($_SERVER['REQUEST_URI']) : '/';
		$path = wp_parse_url($request_uri, PHP_URL_PATH);
		$path = is_string($path) ? trim($path, '/') : '';

		return $path === '' ? '/' : '/' . $path;
	}

	public static function sanitize_date($value) {
		$value = sanitize_text_field($value);
		if (!$value) {
			return '';
		}
		return preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) ? $value : '';
	}

	public static function sanitize_positive_number($value, $fallback = '') {
		if ($value === '' || $value === null) {
			return $fallback;
		}
		return (string) max(0, floatval($value));
	}

	public static function sanitize_dimension($value) {
		$value = trim((string) $value);
		if ($value === '') {
			return '';
		}
		if (preg_match('/^-?\d+(px|%)?$/', $value)) {
			return $value;
		}
		return '';
	}

	public static function sanitize_choice($value, $choices, $fallback) {
		return in_array($value, $choices, true) ? $value : $fallback;
	}

	public static function sanitize_color_value($value, $fallback) {
		$value = trim((string) $value);
		if ($value === '') {
			return $fallback;
		}
		if (preg_match('/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/', $value)) {
			return $value;
		}
		if (preg_match('/^rgba?\(([^)]+)\)$/', $value)) {
			return $value;
		}
		return $fallback;
	}

	public static function sanitize_multiline_text($value) {
		$value = is_string($value) ? $value : '';
		$value = str_replace(array("\r\n", "\r"), "\n", $value);
		$lines = array_map('sanitize_text_field', explode("\n", $value));
		$lines = array_filter($lines, static function ($line) {
			return trim($line) !== '';
		});

		return implode("\n", $lines);
	}

	public static function get_shadow_css($shadow) {
		switch ($shadow) {
			case 'none':
				return 'none';
			case 'sm':
				return '0 10px 25px rgba(15, 23, 42, .12)';
			case 'md':
				return '0 18px 45px rgba(15, 23, 42, .18)';
			case 'lg':
			default:
				return '0 28px 70px rgba(15, 23, 42, .24)';
		}
	}

	public static function get_attachment_image_url($image_id, $size = 'large') {
		if (!$image_id) {
			return '';
		}
		$url = wp_get_attachment_image_url($image_id, $size);
		return $url ? $url : '';
	}

	public static function excerpt_chars($text, $limit = 40) {
		$text = trim(wp_strip_all_tags((string) $text));
		if ($text === '') {
			return '';
		}
		if (function_exists('mb_strimwidth')) {
			return mb_strimwidth($text, 0, $limit * 2, '...', 'UTF-8');
		}
		return wp_html_excerpt($text, $limit, '...');
	}

	public static function render_video_embed($url) {
		if (!$url) {
			return '';
		}

		$type = wp_check_filetype($url);
		if (!empty($type['ext']) && in_array($type['ext'], array('mp4', 'webm', 'ogg', 'ogv', 'mov', 'm4v'), true)) {
			return sprintf(
				'<video class="eoksp-video-tag" controls playsinline preload="metadata"><source src="%s"></video>',
				esc_url($url)
			);
		}

		$embed = wp_oembed_get($url, array('width' => 960));
		if ($embed) {
			return sprintf('<div class="eoksp-embed-wrap">%s</div>', $embed);
		}

		$youtube_id = '';
		$vimeo_id = '';
		if (preg_match('~(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/|shorts/))([A-Za-z0-9_-]{6,})~', $url, $matches)) {
			$youtube_id = $matches[1];
		}
		if (preg_match('~vimeo\.com/(?:video/)?([0-9]+)~', $url, $matches)) {
			$vimeo_id = $matches[1];
		}

		if ($youtube_id) {
			return '<div class="eoksp-embed-wrap"><iframe src="https://www.youtube.com/embed/' . esc_attr($youtube_id) . '?rel=0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy"></iframe></div>';
		}
		if ($vimeo_id) {
			return '<div class="eoksp-embed-wrap"><iframe src="https://player.vimeo.com/video/' . esc_attr($vimeo_id) . '" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen loading="lazy"></iframe></div>';
		}

		return '<p class="eoksp-empty">영상 URL을 불러오지 못했습니다.</p>';
	}

	public static function resolve_kboard_data($url) {
		$url = esc_url_raw($url);
		$data = array(
			'success' => false,
			'url'     => esc_url($url),
			'title'   => 'KBoard 게시글',
			'excerpt' => '연결된 게시글로 이동합니다.',
			'image'   => '',
		);

		if (!$url) {
			return $data;
		}

		$uid = self::extract_kboard_uid($url);

		if ($uid) {
			$content_data = self::resolve_kboard_data_from_class($uid);
			if (!$content_data) {
				$content_data = self::resolve_kboard_data_from_database($uid);
			}

			if ($content_data) {
				$data = array_merge($data, $content_data);
				$data['success'] = true;
				$data['url'] = esc_url($url);
				return $data;
			}
		}

		$fallback_title = self::fallback_title_from_url($url);
		if ($fallback_title) {
			$data['title'] = $fallback_title;
		}

		return $data;
	}

	private static function extract_kboard_uid($url) {
		$parts = wp_parse_url($url);
		if (!is_array($parts)) {
			return 0;
		}

		$query = array();
		if (!empty($parts['query'])) {
			parse_str($parts['query'], $query);
		}

		foreach (array('uid', 'document_uid', 'kboard_content_uid', 'content_uid') as $key) {
			if (!empty($query[$key])) {
				return absint($query[$key]);
			}
		}

		if (!empty($parts['path']) && preg_match('~/(\d+)(?:/)?$~', $parts['path'], $matches)) {
			return absint($matches[1]);
		}

		return 0;
	}

	private static function resolve_kboard_data_from_class($uid) {
		if (!$uid || !class_exists('KBContent')) {
			return false;
		}

		try {
			$content = new \KBContent();
			if (method_exists($content, 'initWithUID')) {
				$content->initWithUID($uid);
			}

			if (empty($content->uid)) {
				return false;
			}

			return array(
				'title'   => sanitize_text_field($content->title ?? ''),
				'excerpt' => self::excerpt_chars($content->content ?? '', 60),
				'image'   => self::find_kboard_image_from_content($content->content ?? '', $content->attach ?? null),
			);
		} catch (\Throwable $e) {
			return false;
		}
	}

	private static function resolve_kboard_data_from_database($uid) {
		global $wpdb;

		if (!$uid || empty($wpdb)) {
			return false;
		}

		$table = $wpdb->prefix . 'kboard_board_content';
		$table_exists = $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $table));
		if ($table_exists !== $table) {
			return false;
		}

		$row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table} WHERE uid = %d LIMIT 1", $uid), ARRAY_A);
		if (!$row || empty($row['uid'])) {
			return false;
		}

		$title = !empty($row['title']) ? $row['title'] : 'KBoard 게시글';
		$content = !empty($row['content']) ? $row['content'] : '';
		$attach = isset($row['attach']) ? maybe_unserialize($row['attach']) : null;

		return array(
			'title'   => sanitize_text_field($title),
			'excerpt' => self::excerpt_chars($content, 60),
			'image'   => self::find_kboard_image_from_content($content, $attach),
		);
	}

	private static function find_kboard_image_from_content($content_html, $attach = null) {
		$from_attach = self::find_kboard_image_from_attach($attach);
		if ($from_attach) {
			return $from_attach;
		}

		if (is_string($content_html) && preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', $content_html, $matches)) {
			return esc_url_raw($matches[1]);
		}

		return '';
	}

	private static function find_kboard_image_from_attach($attach) {
		if (empty($attach)) {
			return '';
		}

		if (is_string($attach)) {
			$attach = maybe_unserialize($attach);
		}

		if (is_object($attach)) {
			$attach = get_object_vars($attach);
		}

		if (!is_array($attach)) {
			return '';
		}

		foreach ($attach as $value) {
			if (is_object($value)) {
				$value = get_object_vars($value);
			}
			if (is_array($value)) {
				$image = self::find_kboard_image_from_attach($value);
				if ($image) {
					return $image;
				}
				continue;
			}

			$file = (string) $value;
			$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
			if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'), true)) {
				return self::normalize_file_url($file);
			}
		}

		return '';
	}

	private static function fallback_title_from_url($url) {
		$parts = wp_parse_url($url);
		if (!is_array($parts)) {
			return '';
		}

		$path = isset($parts['path']) ? trim($parts['path'], '/') : '';
		if ($path === '') {
			return '';
		}

		$segments = array_values(array_filter(explode('/', $path)));
		$last = end($segments);
		if (!$last || ctype_digit($last)) {
			return '';
		}

		return sanitize_text_field(urldecode(str_replace(array('-', '_'), ' ', $last)));
	}

	private static function normalize_file_url($file) {
		$file = (string) $file;
		if ($file === '') {
			return '';
		}

		if (preg_match('#^https?://#i', $file)) {
			return esc_url_raw($file);
		}

		return esc_url_raw(home_url('/' . ltrim($file, '/')));
	}
}
