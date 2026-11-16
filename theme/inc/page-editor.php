<?php

/**
 * Page editor integrations.
 *
 * @package St._Anthony_of_Padua
 */

/**
 * Remove the content editor from pages whose templates do not render it.
 */
function saop_remove_editor_from_fixed_page_templates()
{
	$post_id = filter_input(INPUT_GET, 'post', FILTER_VALIDATE_INT);

	if (! is_admin() || ! $post_id || 'page' !== get_post_type($post_id)) {
		return;
	}

	$contentless_templates = array(
		'page-templates/mass-times.php',
		'page-templates/landing-page.php',
		'page-templates/contact.php',
	);

	if (in_array(get_page_template_slug($post_id), $contentless_templates, true)) {
		remove_post_type_support('page', 'editor');
	}
}
add_action('admin_init', 'saop_remove_editor_from_fixed_page_templates');
