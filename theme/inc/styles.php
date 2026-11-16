<?php
/**
 * Theme stylesheet assets.
 *
 * @package St._Anthony_of_Padua
 */

/**
 * Enqueue styles.
 */
function saop_enqueue_styles() {
	$stylesheet_path    = get_stylesheet_directory() . '/style.css';
	$stylesheet_version = is_readable( $stylesheet_path ) ? filemtime( $stylesheet_path ) : false;

	wp_enqueue_style(
		'saop-style',
		get_stylesheet_uri(),
		array(),
		false !== $stylesheet_version ? (string) $stylesheet_version : SAOP_VERSION
	);
}

add_action( 'wp_enqueue_scripts', 'saop_enqueue_styles' );
