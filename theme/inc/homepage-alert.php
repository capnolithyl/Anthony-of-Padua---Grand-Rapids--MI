<?php

/**
 * Homepage alert data and assets.
 *
 * @package St._Anthony_of_Padua
 */

if (! function_exists('saop_normalize_homepage_alert_image')) :
	/**
	 * Normalize any ACF image return format for the alert template.
	 *
	 * @param mixed $image Raw ACF image value.
	 * @return array{id: int, url: string, alt: string}
	 */
	function saop_normalize_homepage_alert_image($image)
	{
		$normalized = array(
			'id'  => 0,
			'url' => '',
			'alt' => '',
		);

		if (is_array($image)) {
			$normalized['id']  = absint($image['ID'] ?? $image['id'] ?? 0);
			$normalized['url'] = ! empty($image['url']) ? esc_url_raw($image['url']) : '';
			$normalized['alt'] = ! empty($image['alt']) ? sanitize_text_field($image['alt']) : '';
		} elseif (is_numeric($image)) {
			$normalized['id'] = absint($image);
		} elseif (is_string($image)) {
			$normalized['url'] = esc_url_raw($image);
		}

		return $normalized;
	}
endif;

if (! function_exists('saop_get_homepage_alert')) :
	/**
	 * Return the dashboard-managed homepage alert fields.
	 *
	 * @return array<string, mixed>
	 */
	function saop_get_homepage_alert()
	{
		return array(
			'heading'    => get_field('heading', 'option'),
			'subheading' => get_field('subheading', 'option'),
			'image'      => get_field('image', 'option'),
			'text'       => get_field('text', 'option'),
			'links'      => get_field('links', 'option'),
			'enabled'    => get_field('enabled', 'option'),
			'start_date' => get_field('start_date', 'option', false),
			'end_date'   => get_field('end_date', 'option', false),
		);
	}
endif;

if (! function_exists('saop_is_homepage_alert_active')) :
	/**
	 * Determine whether the alert is enabled for the current site date.
	 *
	 * @param array<string, mixed> $alert Homepage alert fields.
	 */
	function saop_is_homepage_alert_active($alert)
	{
		$today      = current_time('Ymd');
		$start_date = (string) ($alert['start_date'] ?? '');
		$end_date   = (string) ($alert['end_date'] ?? '');

		return ! empty($alert['enabled'])
			&& ('' === $start_date || $today >= $start_date)
			&& ('' === $end_date || $today <= $end_date);
	}
endif;

if (! function_exists('saop_enqueue_homepage_alert_script')) :
	/**
	 * Load the alert behavior only while an active alert is rendered.
	 */
	function saop_enqueue_homepage_alert_script()
	{
		if (! is_front_page() || ! saop_is_homepage_alert_active(saop_get_homepage_alert())) {
			return;
		}

		wp_enqueue_script(
			'saop-homepage-alert',
			get_template_directory_uri() . '/js/homepage-alert.min.js',
			array(),
			SAOP_VERSION,
			true
		);
	}
endif;
add_action('wp_enqueue_scripts', 'saop_enqueue_homepage_alert_script');
