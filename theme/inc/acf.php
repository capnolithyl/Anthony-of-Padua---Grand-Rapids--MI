<?php

/**
 * ACF integrations for theme settings and custom blocks.
 *
 * @package St._Anthony_of_Padua
 */

if (! function_exists('saop_has_acf')) :
	/**
	 * Determine whether ACF is active.
	 */
	function saop_has_acf()
	{
		return function_exists('get_field');
	}
endif;

if (! function_exists('saop_force_acf_block_version_3')) :
	/**
	 * Force ACF blocks to use version 3.
	 *
	 * @param int    $version Current block version.
	 * @param string $block Block name.
	 * @return int
	 */
	function saop_force_acf_block_version_3($version, $block)
	{
		return 3;
	}

endif;

add_filter('acf/blocks/default_block_version', 'saop_force_acf_block_version_3', 10, 2);

if (!function_exists('saop_force_acf_blocks_api_version_3')) :
	function saop_force_acf_blocks_api_version_3($args, $block_type)
	{
		if (0 === strpos($block_type, 'acf/')) {
			$args['api_version'] = 3;
		}

		return $args;
	}
endif;

add_filter('register_block_type_args', 'saop_force_acf_blocks_api_version_3', 20, 2);

if (! function_exists('saop_get_theme_setting')) :
	/**
	 * Retrieve a theme setting from the ACF options page.
	 *
	 * @param string $field_name Field name.
	 * @param mixed  $default Default value.
	 * @return mixed
	 */
	function saop_get_theme_setting($field_name, $default = null)
	{
		if (! saop_has_acf()) {
			return $default;
		}

		$value = get_field($field_name, 'option');

		if (null === $value || '' === $value || false === $value) {
			return $default;
		}

		return $value;
	}
endif;

if (! function_exists('saop_get_social_links')) :
	/**
	 * Return the configured social links.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	function saop_get_social_links()
	{
		$links = saop_get_theme_setting('social_media', array());

		return is_array($links) ? $links : array();
	}
endif;

if (! function_exists('saop_get_theme_image')) :
	/**
	 * Retrieve an image field from theme settings.
	 *
	 * @param string $field_name Field name.
	 * @return array<string, mixed>|null
	 */
	function saop_get_theme_image($field_name)
	{
		$image = saop_get_theme_setting($field_name, null);

		return is_array($image) ? $image : null;
	}
endif;

if (! function_exists('saop_render_theme_logo_link')) :
	/**
	 * Render a linked logo from theme settings with a custom logo fallback.
	 *
	 * @param string $field_name Theme setting field name.
	 * @param string $class_name Extra image class names.
	 * @return string
	 */
	function saop_render_theme_logo_link($field_name = 'logo', $class_name = '')
	{
		$logo = saop_get_theme_image($field_name);

		if (! empty($logo['ID'])) {
			return sprintf(
				'<a class="saop-branding__link" href="%1$s" rel="home" aria-label="%2$s">%3$s</a>',
				esc_url(home_url('/')),
				esc_attr(get_bloginfo('name')),
				wp_get_attachment_image(
					$logo['ID'],
					'full',
					false,
					array(
						'class'   => trim('saop-site-logo ' . $class_name),
						'loading' => 'eager',
					)
				)
			);
		}

		if (has_custom_logo()) {
			return get_custom_logo();
		}

		return sprintf(
			'<a class="saop-branding__title" href="%1$s" rel="home">%2$s</a>',
			esc_url(home_url('/')),
			esc_html(get_bloginfo('name'))
		);
	}
endif;

if (! function_exists('saop_render_button')) :
	/**
	 * Render a styled link button from an ACF link field.
	 *
	 * @param array<string, mixed>|false|null $link Link data.
	 * @param string                          $variant Visual variant.
	 * @param string                          $class_name Extra classes.
	 * @return string
	 */
	function saop_render_button(array|false|null $link, string $variant = 'primary', string $class_name = '')
	{
		if (! is_array($link) || empty($link['url']) || empty($link['title'])) {
			return '';
		}

		$classes = array(
			'saop-button',
			'saop-button--' . $variant,
		);

		if (! empty($class_name)) {
			$classes[] = $class_name;
		}

		$target = ! empty($link['target']) ? $link['target'] : '_self';

		return sprintf(
			'<a class="%1$s" href="%2$s" target="%3$s" rel="%4$s">%5$s</a>',
			esc_attr(implode(' ', $classes)),
			esc_url($link['url']),
			esc_attr($target),
			esc_attr('_blank' === $target ? 'noopener noreferrer' : ''),
			esc_html($link['title'])
		);
	}
endif;

if (! function_exists('saop_render_social_links')) :
	/**
	 * Render social links from a repeater field.
	 *
	 * @param array<int, array<string, mixed>>|null $links Social link rows.
	 * @param string                                $class_name Extra wrapper classes.
	 * @return string
	 */
	function saop_render_social_links($links = null, $class_name = '')
	{
		if (null === $links) {
			$links = saop_get_social_links();
		}

		if (empty($links) || ! is_array($links)) {
			return '';
		}

		$items = array();

		foreach ($links as $link) {
			if (empty($link['icon']) || empty($link['url'])) {
				continue;
			}

			$label   = ucfirst(str_replace('-', ' ', (string) $link['icon']));
			$items[] = sprintf(
				'<li><a href="%1$s" target="_blank" rel="noopener noreferrer"><span class="screen-reader-text">%2$s</span>%3$s</a></li>',
				esc_url($link['url']),
				esc_html($label),
				$icon
			);
		}

		if (empty($items)) {
			return '';
		}

		$classes = trim('saop-social-links ' . $class_name);

		return sprintf('<ul class="%1$s">%2$s</ul>', esc_attr($classes), implode('', $items));
	}
endif;
