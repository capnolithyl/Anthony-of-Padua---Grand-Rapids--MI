<?php
/**
 * Posts page protections.
 *
 * @package St._Anthony_of_Padua
 */

/**
 * Prevent non-administrators from editing or deleting the configured posts page.
 *
 * The page ID is read from the current site's Reading settings so this remains
 * correct when the shared theme is used across a multisite network.
 *
 * @param string[] $caps    Primitive capabilities required of the user.
 * @param string   $cap     Capability being checked.
 * @param int      $user_id User ID being checked.
 * @param mixed[]  $args    Additional capability arguments; the first is the post ID.
 * @return string[]
 */
function saop_protect_posts_page_capabilities( $caps, $cap, $user_id, $args ) {
	$protected_caps = array( 'edit_post', 'edit_page', 'delete_post', 'delete_page' );

	if ( ! in_array( $cap, $protected_caps, true ) || empty( $args[0] ) ) {
		return $caps;
	}

	$posts_page_id = (int) get_option( 'page_for_posts' );
	$object_id     = (int) $args[0];

	if ( ! $posts_page_id || $posts_page_id !== $object_id ) {
		return $caps;
	}

	if ( ! user_can( $user_id, 'manage_options' ) ) {
		return array( 'do_not_allow' );
	}

	return $caps;
}
add_filter( 'map_meta_cap', 'saop_protect_posts_page_capabilities', 10, 4 );

/**
 * Get the configured posts page path relative to the current site.
 *
 * @return string
 */
function saop_get_posts_page_path() {
	$posts_page_id = (int) get_option( 'page_for_posts' );

	if ( ! $posts_page_id ) {
		return '';
	}

	return trim( (string) get_page_uri( $posts_page_id ), '/' );
}

/**
 * Get a category's complete hierarchical slug path.
 *
 * @param int|WP_Term $category Category ID or term object.
 * @return string
 */
function saop_get_category_path( $category ) {
	$category = get_term( $category, 'category' );

	if ( ! $category instanceof WP_Term ) {
		return '';
	}

	$category_path = get_category_parents( $category->term_id, false, '/', true );

	if ( is_wp_error( $category_path ) ) {
		return '';
	}

	return trim( $category_path, '/' );
}

/**
 * Build the canonical Posts-page URL for a category.
 *
 * @param int|WP_Term $category Category ID or term object.
 * @return string
 */
function saop_get_posts_category_url( $category ) {
	$posts_page_id = (int) get_option( 'page_for_posts' );
	$category_path = saop_get_category_path( $category );

	if ( ! $posts_page_id || ! $category_path ) {
		return '';
	}

	$posts_page_url = get_permalink( $posts_page_id );

	if ( ! $posts_page_url ) {
		return '';
	}

	return trailingslashit( $posts_page_url ) . user_trailingslashit( $category_path, 'category' );
}

/**
 * Point generated category links at their Discover view on the Posts page.
 *
 * @param string $category_link Existing category URL.
 * @param int    $category_id   Category term ID.
 * @return string
 */
function saop_filter_category_link( $category_link, $category_id ) {
	$posts_category_url = saop_get_posts_category_url( $category_id );

	return $posts_category_url ? $posts_category_url : $category_link;
}
add_filter( 'category_link', 'saop_filter_category_link', 10, 2 );

/**
 * Register the internal flag used to identify Posts-page category routes.
 *
 * @param string[] $query_vars Public query variables.
 * @return string[]
 */
function saop_register_posts_category_query_var( $query_vars ) {
	$query_vars[] = 'saop_posts_category';

	return $query_vars;
}
add_filter( 'query_vars', 'saop_register_posts_category_query_var' );

/**
 * Register category routes beneath the configured Posts page.
 */
function saop_register_posts_category_rewrite_rules() {
	$posts_page_path = saop_get_posts_page_path();

	if ( ! $posts_page_path ) {
		return;
	}

	$route_base = preg_quote( $posts_page_path, '#' );
	$query_base = 'index.php?category_name=$matches[1]&saop_posts_category=1';

	// Add the broad rule first because later "top" rules take precedence.
	add_rewrite_rule( '^' . $route_base . '/(.+?)/?$', $query_base, 'top' );
	add_rewrite_rule( '^' . $route_base . '/(.+?)/page/?([0-9]{1,})/?$', $query_base . '&paged=$matches[2]', 'top' );
	add_rewrite_rule( '^' . $route_base . '/(.+?)/(feed|rdf|rss|rss2|atom)/?$', $query_base . '&feed=$matches[2]', 'top' );
	add_rewrite_rule( '^' . $route_base . '/(.+?)/feed/?$', $query_base . '&feed=rss2', 'top' );
	add_rewrite_rule( '^' . $route_base . '/(.+?)/feed/(feed|rdf|rss|rss2|atom)/?$', $query_base . '&feed=$matches[2]', 'top' );
}
add_action( 'init', 'saop_register_posts_category_rewrite_rules' );

/**
 * Refresh category rewrite rules once per site and whenever its Posts page changes.
 */
function saop_maybe_flush_posts_category_rewrite_rules() {
	$posts_page_id  = (int) get_option( 'page_for_posts' );
	$rewrite_state  = '1|' . $posts_page_id . '|' . saop_get_posts_page_path();
	$stored_version = (string) get_option( 'saop_posts_category_rewrite_state', '' );

	if ( $rewrite_state === $stored_version ) {
		return;
	}

	flush_rewrite_rules( false );
	update_option( 'saop_posts_category_rewrite_state', $rewrite_state, false );
}
add_action( 'init', 'saop_maybe_flush_posts_category_rewrite_rules', 99 );

/**
 * Redirect legacy category archives to their canonical Posts-page URL.
 */
function saop_redirect_legacy_category_archives() {
	if ( ! is_category() || get_query_var( 'saop_posts_category' ) ) {
		return;
	}

	$category = get_queried_object();

	if ( ! $category instanceof WP_Term ) {
		return;
	}

	if ( is_feed() ) {
		$feed   = get_query_var( 'feed' ) ? get_query_var( 'feed' ) : get_default_feed();
		$target = get_category_feed_link( $category->term_id, $feed );
	} else {
		$target = saop_get_posts_category_url( $category );

		if ( $target && is_paged() ) {
			$target = trailingslashit( $target ) . user_trailingslashit( 'page/' . max( 1, (int) get_query_var( 'paged' ) ), 'paged' );
		}
	}

	if ( ! $target ) {
		return;
	}

	wp_safe_redirect( $target, 301, 'St. Anthony of Padua' );
	exit;
}
add_action( 'template_redirect', 'saop_redirect_legacy_category_archives', 5 );

/**
 * Render canonical category routes through the Posts-page template.
 *
 * @param string $template Located template path.
 * @return string
 */
function saop_use_posts_page_for_category_routes( $template ) {
	if ( ! is_category() || is_feed() || ! get_query_var( 'saop_posts_category' ) ) {
		return $template;
	}

	$posts_template = get_theme_file_path( '/home.php' );

	return file_exists( $posts_template ) ? $posts_template : $template;
}
add_filter( 'template_include', 'saop_use_posts_page_for_category_routes' );
