<?php
/**
 * Plugin Name: EO Korean Slide Popup
 * Plugin URI: https://eoingti.com/
 * Description: 가볍고 테마 충돌이 적은 한국형 슬라이드 팝업 플러그인. 이미지, 영상, HTML, KBoard URL 카드를 지원합니다.
 * Version: 1.2.2
 * Author: EOINGTI Lab
 * Author URI: https://eoingti.com/
 * Text Domain: eo-korean-slide-popup
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */

if (!defined('ABSPATH')) {
	exit;
}

define('EOKSP_VERSION', '1.2.2');
define('EOKSP_FILE', __FILE__);
define('EOKSP_PATH', plugin_dir_path(__FILE__));
define('EOKSP_URL', plugin_dir_url(__FILE__));

require_once EOKSP_PATH . 'includes/class-eoksp-plugin.php';

function eoksp_init_plugin() {
	return EOKSP\Plugin::instance();
}

eoksp_init_plugin();
