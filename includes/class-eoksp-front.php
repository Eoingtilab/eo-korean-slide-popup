<?php
namespace EOKSP;

if (!defined('ABSPATH')) {
	exit;
}

class Front {
	public function __construct() {
		add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
		add_action('wp_footer', array($this, 'render_popups'), 30);
	}

	public function enqueue_assets() {
		if (is_admin()) {
			return;
		}

		wp_enqueue_style('eoksp-front', EOKSP_URL . 'assets/front.css', array(), EOKSP_VERSION);
		wp_enqueue_script('eoksp-front', EOKSP_URL . 'assets/front.js', array(), EOKSP_VERSION, true);
	}

	private function is_preview_request() {
		if (empty($_GET['eoksp_preview']) || empty($_GET['eoksp_nonce'])) {
			return false;
		}

		$post_id = absint(wp_unslash($_GET['eoksp_preview']));
		$nonce = sanitize_text_field(wp_unslash($_GET['eoksp_nonce']));

		return $post_id > 0 && current_user_can('manage_options') && wp_verify_nonce($nonce, 'eoksp_preview_' . $post_id);
	}

	private function get_preview_post_id() {
		return !empty($_GET['eoksp_preview']) ? absint(wp_unslash($_GET['eoksp_preview'])) : 0;
	}

	public function render_popups() {
		if (is_admin()) {
			return;
		}

		$preview_mode = $this->is_preview_request();
		$posts = array();

		if ($preview_mode) {
			$preview_post_id = $this->get_preview_post_id();
			$preview_post = get_post($preview_post_id);
			if ($preview_post && $preview_post->post_type === 'eoksp_popup') {
				$posts = array($preview_post);
			}
		} else {
			$posts = get_posts(array(
				'post_type'        => 'eoksp_popup',
				'post_status'      => 'publish',
				'posts_per_page'   => -1,
				'orderby'          => 'menu_order title',
				'order'            => 'ASC',
				'suppress_filters' => false,
			));
		}

		if (!$posts) {
			return;
		}

		$popups = array();
		foreach ($posts as $post) {
			$settings = Helper::get_popup_settings($post->ID);
			$slides = Helper::get_popup_slides($post->ID);

			if (empty($slides)) {
				continue;
			}

			if (!$preview_mode) {
				if (!Helper::is_popup_active($post->ID, $settings) || !Helper::matches_display_context($settings)) {
					continue;
				}
			} else {
				$settings['enabled'] = '1';
				$settings['open_delay'] = '0';
				$settings['auto_close'] = '';
				$settings['overlay'] = '1';
				$settings['position'] = 'center';
			}

			$popups[] = array(
				'post'         => $post,
				'settings'     => $settings,
				'slides'       => $slides,
				'preview_mode' => $preview_mode,
			);
		}

		if (!$popups) {
			return;
		}

		usort($popups, function ($a, $b) {
			return intval($a['settings']['priority']) <=> intval($b['settings']['priority']);
		});

		echo '<div class="eoksp-popup-stack" aria-hidden="true">';
		foreach ($popups as $item) {
			echo $this->get_popup_markup($item['post'], $item['settings'], $item['slides'], array('preview_mode' => !empty($item['preview_mode']))); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		echo '</div>';
	}

	public function get_popup_markup($post, $settings, $slides, $args = array()) {
		$args = wp_parse_args($args, array(
			'preview_mode' => false,
			'queue_item'   => true,
		));

		$device = $settings['device'];
		$classes = array('eoksp-popup', 'eoksp-position-' . $settings['position']);

		if ($device !== 'all') {
			$classes[] = 'eoksp-device-' . $device;
		}
		if (!empty($args['queue_item'])) {
			$classes[] = 'eoksp-queue-item';
		}
		if (!empty($args['preview_mode'])) {
			$classes[] = 'eoksp-preview-mode';
		}
		if (empty($settings['title_bar'])) {
			$classes[] = 'eoksp-no-title-bar';
		}

		$style = array(
			'--eoksp-width:' . esc_attr($settings['width']) . 'px',
			'--eoksp-max-width:' . esc_attr($settings['max_width']) . 'vw',
			'--eoksp-radius:' . esc_attr($settings['radius']) . 'px',
			'--eoksp-bg:' . esc_attr($settings['background_color']),
			'--eoksp-shadow:' . esc_attr(Helper::get_shadow_css($settings['shadow'])),
			'--eoksp-overlay:' . esc_attr($settings['overlay_color']),
			'--eoksp-z:' . esc_attr($settings['zindex']),
		);

		if ($settings['height_mode'] === 'fixed' && $settings['height'] !== '') {
			$style[] = '--eoksp-height:' . esc_attr($settings['height']) . 'px';
		}
		if ($settings['offset_x'] !== '') {
			$style[] = '--eoksp-offset-x:' . esc_attr($settings['offset_x']);
		}
		if ($settings['offset_y'] !== '') {
			$style[] = '--eoksp-offset-y:' . esc_attr($settings['offset_y']);
		}

		$show_title_head = !empty($settings['title_bar']) || !empty($settings['show_close']) || (!empty($settings['hide_today']) && empty($args['preview_mode']));
		$show_navigation = count($slides) > 1;
		ob_start();
		?>
		<section
			class="<?php echo esc_attr(implode(' ', $classes)); ?>"
			data-popup-id="<?php echo esc_attr($post->ID); ?>"
			data-open-delay="<?php echo esc_attr($settings['open_delay']); ?>"
			data-auto-close="<?php echo esc_attr($settings['auto_close']); ?>"
			data-overlay-close="<?php echo esc_attr($settings['overlay_close']); ?>"
			data-hide-today="<?php echo esc_attr($settings['hide_today']); ?>"
			data-cookie-days="<?php echo esc_attr($settings['cookie_days']); ?>"
			data-slider-autoplay="<?php echo esc_attr($settings['slider_autoplay']); ?>"
			data-slider-speed="<?php echo esc_attr($settings['slider_speed']); ?>"
			data-slider-loop="<?php echo esc_attr($settings['slider_loop']); ?>"
			data-preview="<?php echo !empty($args['preview_mode']) ? '1' : '0'; ?>"
			style="<?php echo esc_attr(implode(';', $style)); ?>"
			hidden
		>
			<?php if (!empty($settings['overlay'])) : ?>
				<div class="eoksp-overlay" data-overlay></div>
			<?php endif; ?>

			<div class="eoksp-dialog" role="dialog" aria-modal="true" aria-label="<?php echo esc_attr(get_the_title($post)); ?>">
				<?php if (!empty($args['preview_mode'])) : ?>
					<div class="eoksp-preview-badge">관리자 미리보기</div>
				<?php endif; ?>

				<?php if ($show_title_head) : ?>
					<div class="eoksp-dialog-head">
						<?php if (!empty($settings['title_bar'])) : ?>
							<div class="eoksp-dialog-title"><?php echo esc_html($settings['title_text'] ?: get_the_title($post)); ?></div>
						<?php endif; ?>
						<div class="eoksp-head-actions">
							<?php if (!empty($settings['hide_today']) && empty($args['preview_mode'])) : ?>
								<button type="button" class="eoksp-meta-chip eoksp-hide-today" data-hide-today>오늘 하루 보지 않기</button>
							<?php endif; ?>
							<?php if (!empty($settings['show_close'])) : ?>
								<button type="button" class="eoksp-meta-chip eoksp-text-close" aria-label="팝업 닫기" data-close>닫기</button>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>

				<div class="eoksp-body <?php echo $settings['height_mode'] === 'fixed' ? 'is-fixed-height' : 'is-auto-height'; ?>">
					<div class="eoksp-slides" data-slider>
						<div class="eoksp-slides-track" data-slides-track>
							<?php foreach ($slides as $index => $slide) : ?>
								<article class="eoksp-slide" data-slide-index="<?php echo esc_attr($index); ?>">
									<?php echo $this->render_slide_content($slide, $settings); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</article>
							<?php endforeach; ?>
						</div>
					</div>

					<?php if ($show_navigation && !empty($settings['show_arrows'])) : ?>
						<button type="button" class="eoksp-nav eoksp-prev" data-prev aria-label="이전 슬라이드">‹</button>
						<button type="button" class="eoksp-nav eoksp-next" data-next aria-label="다음 슬라이드">›</button>
					<?php endif; ?>
				</div>

				<?php if ($show_navigation && !empty($settings['show_dots'])) : ?>
					<div class="eoksp-dots" data-dots>
						<?php foreach ($slides as $index => $slide) : ?>
							<button type="button" class="eoksp-dot <?php echo $index === 0 ? 'is-active' : ''; ?>" data-dot="<?php echo esc_attr($index); ?>" aria-label="<?php echo esc_attr(($index + 1) . '번 슬라이드'); ?>"></button>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</section>
		<?php
		return ob_get_clean();
	}

	private function render_slide_content($slide, $settings) {
		$type = $slide['type'] ?? 'image';
		ob_start();

		if ($type === 'image') {
			$image_url = Helper::get_attachment_image_url($slide['image_id'] ?? 0, 'large');
			if (!$image_url && !empty($slide['image_url'])) {
				$image_url = $slide['image_url'];
			}

			$image_html = $image_url ? '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($slide['image_alt'] ?? '') . '" class="eoksp-image">' : '<p class="eoksp-empty">이미지를 불러오지 못했습니다.</p>';
			if (!empty($slide['link_url'])) {
				echo '<a href="' . esc_url($slide['link_url']) . '" class="eoksp-image-link"' . (!empty($slide['link_target']) ? ' target="_blank" rel="noopener noreferrer"' : '') . '>' . $image_html . '</a>';
			} else {
				echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		if ($type === 'video') {
			echo Helper::render_video_embed($slide['video_url'] ?? ''); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		if ($type === 'html') {
			echo '<div class="eoksp-html">' . wp_kses_post($slide['html'] ?? '') . '</div>';
		}

		if ($type === 'kboard') {
			$data = Helper::resolve_kboard_data($slide['kboard_url'] ?? '');
			$button_label = !empty($slide['button_label']) ? $slide['button_label'] : '자세히 보기';
			$style_class = 'eoksp-kboard-style-' . ($settings['kboard_style'] ?? 'news');
			$resolved_class = !empty($data['success']) ? 'is-resolved' : 'is-fallback';
			$title = !empty($data['title']) && $data['title'] !== 'KBoard 게시글' && !empty($data['success']) ? $data['title'] : ($slide['title'] ?: $data['title']);
			$excerpt = !empty($data['excerpt']) ? $data['excerpt'] : '연결된 게시글로 이동합니다.';

			echo '<a href="' . esc_url($data['url']) . '" class="eoksp-kboard-card ' . esc_attr($style_class . ' ' . $resolved_class) . '">';
			if (!empty($data['image'])) {
				echo '<div class="eoksp-kboard-thumb"><img src="' . esc_url($data['image']) . '" alt="' . esc_attr($title) . '"></div>';
			}
			echo '<div class="eoksp-kboard-text">';
			echo '<strong class="eoksp-kboard-title">' . esc_html($title ?: 'KBoard 게시글') . '</strong>';
			echo '<p class="eoksp-kboard-excerpt">' . esc_html($excerpt) . '</p>';
			echo '<span class="eoksp-kboard-button">' . esc_html($button_label) . '</span>';
			echo '</div>';
			echo '</a>';
		}

		return ob_get_clean();
	}
}
