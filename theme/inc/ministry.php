<?php
/**
 * Ministry archive and single-page helpers.
 *
 * The ministry post type, ministry group taxonomy, and ACF fields are managed
 * outside the theme. This file only provides presentation-layer helpers.
 *
 * @package St._Anthony_of_Padua
 */

/**
 * Return the taxonomy registered for ministry groups.
 *
 * @return string
 */
function saop_get_ministry_taxonomy() {
	$preferred_taxonomies = array( 'ministry_group', 'ministry-group' );

	foreach ( $preferred_taxonomies as $taxonomy ) {
		if ( taxonomy_exists( $taxonomy ) && is_object_in_taxonomy( 'ministry', $taxonomy ) ) {
			return $taxonomy;
		}
	}

	foreach ( get_object_taxonomies( 'ministry', 'names' ) as $taxonomy ) {
		if ( 'ministry-group' === str_replace( '_', '-', $taxonomy ) ) {
			return $taxonomy;
		}
	}

	return '';
}

/**
 * Return every ministry group in its creation order.
 *
 * @return WP_Term[]
 */
function saop_get_ministry_groups() {
	$taxonomy = saop_get_ministry_taxonomy();

	if ( ! $taxonomy ) {
		return array();
	}

	$groups = get_terms(
		array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => false,
			'orderby'    => 'term_id',
			'order'      => 'ASC',
		)
	);

	return is_wp_error( $groups ) ? array() : $groups;
}

/**
 * Normalize any ACF image return format for ministry templates.
 *
 * @param mixed $image Image field value.
 * @return array{id: int, url: string, alt: string}
 */
function saop_normalize_ministry_image( $image ) {
	$image_id  = 0;
	$image_url = '';
	$image_alt = '';

	if ( is_array( $image ) ) {
		$image_id  = absint( $image['ID'] ?? $image['id'] ?? 0 );
		$image_url = isset( $image['url'] ) ? (string) $image['url'] : '';
		$image_alt = isset( $image['alt'] ) ? (string) $image['alt'] : '';
	} elseif ( is_numeric( $image ) ) {
		$image_id = absint( $image );
	} elseif ( is_string( $image ) ) {
		$image_url = $image;
	}

	if ( $image_id ) {
		if ( ! $image_url ) {
			$image_url = (string) wp_get_attachment_image_url( $image_id, 'full' );
		}

		if ( ! $image_alt ) {
			$image_alt = (string) get_post_meta( $image_id, '_wp_attachment_image_alt', true );
		}
	} elseif ( $image_url ) {
		$image_id = attachment_url_to_postid( $image_url );
	}

	return array(
		'id'  => $image_id,
		'url' => esc_url_raw( $image_url ),
		'alt' => sanitize_text_field( $image_alt ),
	);
}

/**
 * Return the image configured on the Ministry Page Settings options page.
 *
 * Supports both ACF's default options storage and a custom post ID matching
 * the options-page slug.
 *
 * @return array{id: int, url: string, alt: string}
 */
function saop_get_ministry_archive_image() {
	if ( ! function_exists( 'get_field' ) ) {
		return saop_normalize_ministry_image( false );
	}

	foreach ( array( 'ministry-page-settings', 'ministry_page_settings', 'option', 'options' ) as $context ) {
		$image = get_field( 'featured_image', $context );

		if ( $image ) {
			return saop_normalize_ministry_image( $image );
		}
	}

	return saop_normalize_ministry_image( false );
}

/**
 * Return a ministry's single-page header image.
 *
 * @param int $post_id Ministry post ID.
 * @return array{id: int, url: string, alt: string}
 */
function saop_get_ministry_header_image( $post_id = 0 ) {
	$post_id = $post_id ? absint( $post_id ) : get_the_ID();
	$image   = function_exists( 'get_field' )
		? get_field( 'header_image', $post_id )
		: get_post_meta( $post_id, 'header_image', true );

	return saop_normalize_ministry_image( $image );
}

/**
 * Load all ministries on the archive in a stable display order.
 *
 * @param WP_Query $query Current query.
 */
function saop_configure_ministry_archive_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_post_type_archive( 'ministry' ) ) {
		return;
	}

	$query->set( 'posts_per_page', -1 );
	$query->set( 'no_found_rows', true );
	$query->set(
		'orderby',
		array(
			'menu_order' => 'ASC',
			'title'      => 'ASC',
		)
	);
	$query->set( 'order', 'ASC' );
}
add_action( 'pre_get_posts', 'saop_configure_ministry_archive_query' );
