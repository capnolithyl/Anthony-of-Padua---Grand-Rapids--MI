<?php

/**
 * Enqueue the featured links interaction where the block can render.
 */
function saop_enqueue_featured_links_assets()
{
	wp_enqueue_script(
		'saop-featured-links',
		get_theme_file_uri('/blocks/featured-links/featured-links.js'),
		array(),
		filemtime(get_theme_file_path('/blocks/featured-links/featured-links.js')),
		true
	);
}

/**
 * Load the interaction in the editor and on posts containing the block.
 */
function saop_maybe_enqueue_featured_links_assets()
{
	if (
		is_admin() ||
		has_block('acf/featured-links') ||
		has_block('acf/saop-featured-links')
	) {
		saop_enqueue_featured_links_assets();
	}
}
add_action('enqueue_block_assets', 'saop_maybe_enqueue_featured_links_assets');
