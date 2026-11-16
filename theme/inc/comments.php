<?php
/**
 * Disable comments and trackbacks throughout the site.
 *
 * Existing comments remain in the database but are not displayed or available
 * for interaction while this theme is active.
 *
 * @package St._Anthony_of_Padua
 */

/**
 * Remove comment and trackback support from every registered post type.
 */
function saop_disable_comments_post_type_support() {
	foreach ( get_post_types() as $post_type ) {
		remove_post_type_support( $post_type, 'comments' );
		remove_post_type_support( $post_type, 'trackbacks' );
	}
}
add_action( 'init', 'saop_disable_comments_post_type_support', 100 );

/**
 * Close comments and pingbacks, including on existing content.
 *
 * @return false
 */
function saop_disable_comments_status() {
	return false;
}
add_filter( 'comments_open', 'saop_disable_comments_status', 20 );
add_filter( 'pings_open', 'saop_disable_comments_status', 20 );
add_filter( 'feed_links_show_comments_feed', 'saop_disable_comments_status', 20 );
add_filter( 'feed_links_extra_show_post_comments_feed', 'saop_disable_comments_status', 20 );

/**
 * Hide existing comments from front-end queries.
 *
 * @return array An empty list of comments.
 */
function saop_hide_existing_comments() {
	return array();
}
add_filter( 'comments_array', 'saop_hide_existing_comments', 10 );

/**
 * Remove comment-related admin menu items.
 */
function saop_remove_comments_admin_menus() {
	remove_menu_page( 'edit-comments.php' );
	remove_submenu_page( 'options-general.php', 'options-discussion.php' );
}
add_action( 'admin_menu', 'saop_remove_comments_admin_menus', 100 );

/**
 * Remove the recent comments dashboard widget.
 */
function saop_remove_comments_dashboard_widget() {
	remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
}
add_action( 'wp_dashboard_setup', 'saop_remove_comments_dashboard_widget' );

/**
 * Remove the comments shortcut from the admin toolbar.
 *
 * @param WP_Admin_Bar $wp_admin_bar WordPress admin toolbar instance.
 */
function saop_remove_comments_admin_bar_item( $wp_admin_bar ) {
	$wp_admin_bar->remove_node( 'comments' );
}
add_action( 'admin_bar_menu', 'saop_remove_comments_admin_bar_item', 100 );
