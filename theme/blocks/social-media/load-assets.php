<?php
/**
 * Social media slider asset loading.
 *
 * @package St._Anthony_of_Padua
 */

/**
 * Enqueue the social media slider assets where the block can render.
 */
function saop_enqueue_social_media_assets() {
	saop_enqueue_slick_assets();

	wp_enqueue_script(
		'saop-social-media',
		get_theme_file_uri( '/blocks/social-media/social-media.js' ),
		array( 'jquery', 'saop-slick' ),
		filemtime( get_theme_file_path( '/blocks/social-media/social-media.js' ) ),
		true
	);
}

/**
 * Load slider assets in the editor and on posts containing the block.
 */
function saop_maybe_enqueue_social_media_assets() {
	if (
		is_admin() ||
		has_block( 'acf/social-media' ) ||
		has_block( 'acf/saop-social-media' )
	) {
		saop_enqueue_social_media_assets();
	}
}
add_action( 'enqueue_block_assets', 'saop_maybe_enqueue_social_media_assets' );
