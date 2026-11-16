<?php

/**
 * Theme scripts.
 *
 * @package St._Anthony_of_Padua
 */

/**
 * Enqueue front-end scripts.
 */
function saop_scripts()
{
	wp_enqueue_script('saop-script', get_template_directory_uri() . '/js/script.min.js', array(), SAOP_VERSION, true);

	$saop_nav_dependencies = array('jquery');

	if (wp_script_is('megamenu', 'registered')) {
		$saop_nav_dependencies[] = 'megamenu';
	}

	wp_enqueue_script('saop-nav-script', get_template_directory_uri() . '/js/nav.min.js', $saop_nav_dependencies, SAOP_VERSION, true);

	wp_register_script(
		'saop-staff-directory',
		get_template_directory_uri() . '/js/staff.min.js',
		array(),
		SAOP_VERSION,
		true
	);

	if ( is_home() || is_category() ) {
		saop_enqueue_slick_assets();

		wp_enqueue_script(
			'saop-news-archive',
			get_template_directory_uri() . '/js/news.min.js',
			array('jquery', 'saop-slick'),
			SAOP_VERSION,
			true
		);
	}

	if (is_post_type_archive('staff')) {
		saop_enqueue_slick_assets();

		wp_enqueue_script('saop-staff-directory');
	}

	if (is_post_type_archive('ministry')) {
		saop_enqueue_slick_assets();

		wp_enqueue_script(
			'saop-ministry-directory',
			get_template_directory_uri() . '/js/ministry.min.js',
			array('jquery', 'saop-slick'),
			SAOP_VERSION,
			true
		);
	}

	wp_enqueue_script(
		'saop-smooth-scroll',
		get_template_directory_uri() . '/js/smoothscroll.js',
		array(),
		SAOP_VERSION,
		true
	);
}
add_action('wp_enqueue_scripts', 'saop_scripts');

/**
 * Enqueue the block editor script.
 */
function saop_enqueue_block_editor_script()
{
	$current_screen = function_exists('get_current_screen') ? get_current_screen() : null;

	if (
		$current_screen &&
		$current_screen->is_block_editor() &&
		'widgets' !== $current_screen->id
	) {
		wp_enqueue_script(
			'saop-editor',
			get_template_directory_uri() . '/js/block-editor.min.js',
			array(
				'wp-blocks',
				'wp-edit-post',
			),
			SAOP_VERSION,
			true
		);
		wp_add_inline_script('saop-editor', "tailwindTypographyClasses = '" . esc_attr(SAOP_TYPOGRAPHY_CLASSES) . "'.split(' ');", 'before');
	}
}
add_action('enqueue_block_assets', 'saop_enqueue_block_editor_script');
