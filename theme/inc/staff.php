<?php
/**
 * Staff directory content type and fields.
 *
 * @package St._Anthony_of_Padua
 */

/**
 * Register staff members and their groups.
 */
function saop_register_staff_content_types() {
	register_taxonomy(
		'staff_group',
		array( 'staff' ),
		array(
			'labels'            => array(
				'name'              => __( 'Staff Groups', 'saop' ),
				'singular_name'     => __( 'Staff Group', 'saop' ),
				'search_items'      => __( 'Search Staff Groups', 'saop' ),
				'all_items'         => __( 'All Staff Groups', 'saop' ),
				'parent_item'       => __( 'Parent Staff Group', 'saop' ),
				'parent_item_colon' => __( 'Parent Staff Group:', 'saop' ),
				'edit_item'         => __( 'Edit Staff Group', 'saop' ),
				'update_item'       => __( 'Update Staff Group', 'saop' ),
				'add_new_item'      => __( 'Add New Staff Group', 'saop' ),
				'new_item_name'     => __( 'New Staff Group Name', 'saop' ),
				'menu_name'         => __( 'Staff Groups', 'saop' ),
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array(
				'slug'       => 'staff-group',
				'with_front' => false,
			),
		)
	);

	register_post_type(
		'staff',
		array(
			'labels'             => array(
				'name'                  => __( 'Staff Members', 'saop' ),
				'singular_name'         => __( 'Staff Member', 'saop' ),
				'menu_name'             => __( 'Staff', 'saop' ),
				'name_admin_bar'        => __( 'Staff Member', 'saop' ),
				'add_new'               => __( 'Add New', 'saop' ),
				'add_new_item'          => __( 'Add New Staff Member', 'saop' ),
				'new_item'              => __( 'New Staff Member', 'saop' ),
				'edit_item'             => __( 'Edit Staff Member', 'saop' ),
				'view_item'             => __( 'View Staff Member', 'saop' ),
				'all_items'             => __( 'All Staff Members', 'saop' ),
				'search_items'          => __( 'Search Staff Members', 'saop' ),
				'not_found'             => __( 'No staff members found.', 'saop' ),
				'not_found_in_trash'    => __( 'No staff members found in Trash.', 'saop' ),
				'featured_image'        => __( 'Staff Photo', 'saop' ),
				'set_featured_image'    => __( 'Set staff photo', 'saop' ),
				'remove_featured_image' => __( 'Remove staff photo', 'saop' ),
				'use_featured_image'    => __( 'Use as staff photo', 'saop' ),
				'archives'              => __( 'Staff Directory', 'saop' ),
			),
			'public'             => true,
			'publicly_queryable' => true,
			'show_in_rest'       => true,
			'menu_icon'          => 'dashicons-businessperson',
			'menu_position'      => 20,
			'has_archive'        => 'staff',
			'rewrite'            => array(
				'slug'       => 'staff',
				'with_front' => false,
			),
			'supports'           => array(
				'title',
				'editor',
				'thumbnail',
				'revisions',
				'page-attributes',
			),
			'taxonomies'         => array( 'staff_group' ),
		)
	);

	$meta_fields = array(
		'staff_position' => 'sanitize_text_field',
		'staff_email'    => 'sanitize_email',
		'staff_phone'    => 'sanitize_text_field',
	);

	foreach ( $meta_fields as $meta_key => $sanitize_callback ) {
		register_post_meta(
			'staff',
			$meta_key,
			array(
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => $sanitize_callback,
				'auth_callback'     => static function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}
}
add_action( 'init', 'saop_register_staff_content_types' );

/**
 * Register the staff contact fields with ACF Pro.
 */
function saop_register_staff_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key'      => 'group_saop_staff_details',
			'title'    => __( 'Staff Details', 'saop' ),
			'fields'   => array(
				array(
					'key'      => 'field_saop_staff_position',
					'label'    => __( 'Position', 'saop' ),
					'name'     => 'staff_position',
					'type'     => 'text',
					'required' => 1,
					'wrapper'  => array( 'width' => 33 ),
				),
				array(
					'key'     => 'field_saop_staff_email',
					'label'   => __( 'Email Address', 'saop' ),
					'name'    => 'staff_email',
					'type'    => 'email',
					'wrapper' => array( 'width' => 33 ),
				),
				array(
					'key'     => 'field_saop_staff_phone',
					'label'   => __( 'Phone Number', 'saop' ),
					'name'    => 'staff_phone',
					'type'    => 'text',
					'wrapper' => array( 'width' => 34 ),
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'staff',
					),
				),
			),
			'position' => 'acf_after_title',
		)
	);
}
add_action( 'acf/init', 'saop_register_staff_fields' );

/**
 * Return staff groups in their creation order.
 *
 * @return WP_Term[]
 */
function saop_get_staff_groups() {
	$groups = get_terms(
		array(
			'taxonomy'   => 'staff_group',
			'hide_empty' => false,
			'orderby'    => 'term_id',
			'order'      => 'ASC',
		)
	);

	return is_wp_error( $groups ) ? array() : $groups;
}

/**
 * Retrieve one staff field with an ACF-independent fallback.
 *
 * @param string $field_name Field name.
 * @param int    $post_id Post ID.
 * @return string
 */
function saop_get_staff_field( $field_name, $post_id = 0 ) {
	$allowed_fields = array( 'staff_position', 'staff_email', 'staff_phone' );

	if ( ! in_array( $field_name, $allowed_fields, true ) ) {
		return '';
	}

	$post_id = $post_id ? absint( $post_id ) : get_the_ID();
	$value   = function_exists( 'get_field' )
		? get_field( $field_name, $post_id )
		: get_post_meta( $post_id, $field_name, true );

	return is_scalar( $value ) ? trim( (string) $value ) : '';
}

/**
 * Convert a display phone number into a telephone URI.
 *
 * @param string $phone Phone number.
 * @return string
 */
function saop_get_staff_phone_href( $phone ) {
	$number = preg_replace( '/[^0-9+]/', '', $phone );

	return $number ? 'tel:' . $number : '';
}

/**
 * Return the archive section containing a staff member.
 *
 * Staff members can belong to more than one group. Direct links use the first
 * assigned group in the same creation order used by the directory.
 *
 * @param int $post_id Staff member post ID.
 * @return string
 */
function saop_get_staff_section_id( $post_id ) {
	$member_groups = get_the_terms( $post_id, 'staff_group' );

	if ( ! $member_groups || is_wp_error( $member_groups ) ) {
		return 'staff-group-other';
	}

	$member_group_ids = wp_list_pluck( $member_groups, 'term_id' );

	foreach ( saop_get_staff_groups() as $staff_group ) {
		if ( in_array( $staff_group->term_id, $member_group_ids, true ) ) {
			return 'staff-group-' . sanitize_title( $staff_group->slug );
		}
	}

	return 'staff-group-' . sanitize_title( $member_groups[0]->slug );
}

/**
 * Build a progressive-enhancement URL for a staff biography.
 *
 * @param int    $post_id Staff member post ID.
 * @param string $section_id Optional directory section ID.
 * @return string
 */
function saop_get_staff_bio_url( $post_id, $section_id = '' ) {
	$staff_member = get_post( $post_id );
	$archive_url  = get_post_type_archive_link( 'staff' );

	if ( ! $staff_member instanceof WP_Post || 'staff' !== $staff_member->post_type || ! $archive_url ) {
		return '';
	}

	$section_id = $section_id
		? sanitize_html_class( $section_id )
		: saop_get_staff_section_id( $staff_member->ID );

	return add_query_arg( 'staff_bio', $staff_member->post_name, $archive_url ) . '#' . $section_id;
}

/**
 * Point generated staff permalinks to the archive biography lightbox.
 *
 * @param string  $post_link Existing post permalink.
 * @param WP_Post $post Staff member post.
 * @return string
 */
function saop_filter_staff_permalink( $post_link, $post ) {
	if ( 'staff' !== $post->post_type ) {
		return $post_link;
	}

	$bio_url = saop_get_staff_bio_url( $post->ID );

	return $bio_url ? $bio_url : $post_link;
}
add_filter( 'post_type_link', 'saop_filter_staff_permalink', 10, 2 );

/**
 * Register the biography request used by direct archive links.
 *
 * @param string[] $query_vars Public query variables.
 * @return string[]
 */
function saop_register_staff_query_vars( $query_vars ) {
	$query_vars[] = 'staff_bio';

	return $query_vars;
}
add_filter( 'query_vars', 'saop_register_staff_query_vars' );

/**
 * Redirect legacy staff single URLs to the archive lightbox.
 */
function saop_redirect_staff_singles() {
	if ( ! is_singular( 'staff' ) ) {
		return;
	}

	$bio_url = saop_get_staff_bio_url( get_queried_object_id() );

	if ( ! $bio_url ) {
		return;
	}

	wp_safe_redirect( $bio_url, 301, 'St. Anthony of Padua' );
	exit;
}
add_action( 'template_redirect', 'saop_redirect_staff_singles', 5 );

/**
 * Load every staff member on the main directory in display order.
 *
 * @param WP_Query $query Current query.
 */
function saop_configure_staff_archive_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_post_type_archive( 'staff' ) ) {
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
add_action( 'pre_get_posts', 'saop_configure_staff_archive_query' );

/**
 * Send legacy staff-group archives to the matching directory section.
 */
function saop_redirect_staff_group_archives() {
	if ( ! is_tax( 'staff_group' ) ) {
		return;
	}

	$staff_group = get_queried_object();
	$archive_url = get_post_type_archive_link( 'staff' );

	if ( ! $staff_group instanceof WP_Term || ! $archive_url ) {
		return;
	}

	wp_safe_redirect( $archive_url . '#staff-group-' . sanitize_title( $staff_group->slug ), 301 );
	exit;
}
add_action( 'template_redirect', 'saop_redirect_staff_group_archives' );

/**
 * Make the staff title field's purpose clear in the editor.
 *
 * @param string  $placeholder Existing placeholder.
 * @param WP_Post $post Current post.
 * @return string
 */
function saop_staff_title_placeholder( $placeholder, $post ) {
	return 'staff' === $post->post_type ? __( 'Staff member name', 'saop' ) : $placeholder;
}
add_filter( 'enter_title_here', 'saop_staff_title_placeholder', 10, 2 );

/**
 * Add position to the staff list table.
 *
 * @param array<string, string> $columns Existing columns.
 * @return array<string, string>
 */
function saop_staff_admin_columns( $columns ) {
	$updated_columns = array();

	foreach ( $columns as $key => $label ) {
		$updated_columns[ $key ] = $label;

		if ( 'title' === $key ) {
			$updated_columns['staff_position'] = __( 'Position', 'saop' );
		}
	}

	return $updated_columns;
}
add_filter( 'manage_staff_posts_columns', 'saop_staff_admin_columns' );

/**
 * Render custom staff list-table columns.
 *
 * @param string $column Column key.
 * @param int    $post_id Post ID.
 */
function saop_render_staff_admin_columns( $column, $post_id ) {
	if ( 'staff_position' === $column ) {
		echo esc_html( saop_get_staff_field( 'staff_position', $post_id ) );
	}
}
add_action( 'manage_staff_posts_custom_column', 'saop_render_staff_admin_columns', 10, 2 );

/**
 * Refresh staff rewrite rules once on each site after this feature is added.
 */
function saop_maybe_flush_staff_rewrite_rules() {
	$rewrite_version = '1';

	if ( get_option( 'saop_staff_rewrite_version' ) === $rewrite_version ) {
		return;
	}

	flush_rewrite_rules( false );
	update_option( 'saop_staff_rewrite_version', $rewrite_version );
}
add_action( 'init', 'saop_maybe_flush_staff_rewrite_rules', 99 );
