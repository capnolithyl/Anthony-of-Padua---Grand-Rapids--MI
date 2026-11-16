<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package St._Anthony_of_Padua
 */

/**
 * Filters the default archive titles.
 */
function saop_get_the_archive_title() {
	if ( is_category() ) {
		$title = __( 'Category Archives: ', 'saop' ) . '<span>' . single_term_title( '', false ) . '</span>';
	} elseif ( is_tag() ) {
		$title = __( 'Tag Archives: ', 'saop' ) . '<span>' . single_term_title( '', false ) . '</span>';
	} elseif ( is_author() ) {
		$title = __( 'Author Archives: ', 'saop' ) . '<span>' . get_the_author_meta( 'display_name' ) . '</span>';
	} elseif ( is_year() ) {
		$title = __( 'Yearly Archives: ', 'saop' ) . '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'saop' ) ) . '</span>';
	} elseif ( is_month() ) {
		$title = __( 'Monthly Archives: ', 'saop' ) . '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'saop' ) ) . '</span>';
	} elseif ( is_day() ) {
		$title = __( 'Daily Archives: ', 'saop' ) . '<span>' . get_the_date() . '</span>';
	} elseif ( is_post_type_archive() ) {
		$cpt   = get_post_type_object( get_queried_object()->name );
		$title = sprintf(
			/* translators: %s: Post type singular name */
			esc_html__( '%s Archives', 'saop' ),
			$cpt->labels->singular_name
		);
	} elseif ( is_tax() ) {
		$tax   = get_taxonomy( get_queried_object()->taxonomy );
		$title = sprintf(
			/* translators: %s: Taxonomy singular name */
			esc_html__( '%s Archives', 'saop' ),
			$tax->labels->singular_name
		);
	} else {
		$title = __( 'Archives:', 'saop' );
	}
	return $title;
}
add_filter( 'get_the_archive_title', 'saop_get_the_archive_title' );

/**
 * Determines whether the post thumbnail can be displayed.
 */
function saop_can_show_post_thumbnail() {
	return apply_filters( 'saop_can_show_post_thumbnail', ! post_password_required() && ! is_attachment() && has_post_thumbnail() );
}

/**
 * Create the continue reading link
 *
 * @param string $more_string The string shown within the more link.
 */
function saop_continue_reading_link( $more_string ) {

	if ( ! is_admin() ) {
		$continue_reading = sprintf(
			/* translators: %s: Name of current post. */
			wp_kses( __( 'Continue reading %s', 'saop' ), array( 'span' => array( 'class' => array() ) ) ),
			the_title( '<span class="sr-only">"', '"</span>', false )
		);

		$more_string = '<a href="' . esc_url( get_permalink() ) . '">' . $continue_reading . '</a>';
	}

	return $more_string;
}

// Filter the excerpt more link.
add_filter( 'excerpt_more', 'saop_continue_reading_link' );

// Filter the content more link.
add_filter( 'the_content_more_link', 'saop_continue_reading_link' );
