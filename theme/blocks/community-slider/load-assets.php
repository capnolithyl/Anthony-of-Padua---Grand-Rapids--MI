<?php

/**
 * Enqueue the community slider assets only where the block can render.
 */
function saop_enqueue_community_slider_assets()
{
	saop_enqueue_slick_assets();

	wp_enqueue_script(
		'saop-community-slider',
		get_theme_file_uri('/blocks/community-slider/community-slider.js'),
		array('jquery', 'saop-slick'),
		filemtime(get_theme_file_path('/blocks/community-slider/community-slider.js')),
		true
	);
}

/**
 * Load slider assets in the editor and on posts containing the block.
 */
function saop_maybe_enqueue_community_slider_assets()
{
	if (
		is_admin() ||
		has_block('acf/community-slider') ||
		has_block('acf/saop-community-slider')
	) {
		saop_enqueue_community_slider_assets();
	}
}
add_action('enqueue_block_assets', 'saop_maybe_enqueue_community_slider_assets');
