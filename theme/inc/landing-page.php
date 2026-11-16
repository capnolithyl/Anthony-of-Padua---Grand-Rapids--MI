<?php

/**
 * Landing page template integrations.
 *
 * @package St._Anthony_of_Padua
 */

if (! defined('SAOP_LANDING_PAGE_TEMPLATE')) {
	define('SAOP_LANDING_PAGE_TEMPLATE', 'page-templates/landing-page.php');
}

if (! function_exists('saop_is_landing_page')) :
	/**
	 * Determine whether the current page uses the landing page template.
	 *
	 * @return bool
	 */
	function saop_is_landing_page()
	{
		return is_page_template(SAOP_LANDING_PAGE_TEMPLATE);
	}
endif;

if (! function_exists('saop_register_landing_page_fields')) :
	/**
	 * Register the two fixed landing page sections with ACF Pro.
	 */
	function saop_register_landing_page_fields()
	{
		if (! function_exists('acf_add_local_field_group')) {
			return;
		}

		$section_fields = static function ($section_key) {
			return array(
				array(
					'key'           => 'field_saop_landing_' . $section_key . '_logo',
					'label'         => __('Logo', 'saop'),
					'name'          => 'logo',
					'type'          => 'image',
					'instructions'  => __('Select the logo and add meaningful alternative text in the Media Library.', 'saop'),
					'required'      => 1,
					'return_format' => 'id',
					'preview_size'  => 'medium',
					'library'       => 'all',
				),
				array(
					'key'      => 'field_saop_landing_' . $section_key . '_heading',
					'label'    => __('Heading', 'saop'),
					'name'     => 'heading',
					'type'     => 'text',
					'required' => 1,
				),
				array(
					'key'       => 'field_saop_landing_' . $section_key . '_subheading',
					'label'     => __('Subheading', 'saop'),
					'name'      => 'subheading',
					'type'      => 'textarea',
					'required'  => 1,
					'rows'      => 3,
					'new_lines' => '',
				),
				array(
					'key'           => 'field_saop_landing_' . $section_key . '_link',
					'label'         => __('Button Link', 'saop'),
					'name'          => 'link',
					'type'          => 'link',
					'required'      => 1,
					'return_format' => 'array',
				),
				array(
					'key'           => 'field_saop_landing_' . $section_key . '_background_image',
					'label'         => __('Background Image', 'saop'),
					'name'          => 'background_image',
					'type'          => 'image',
					'required'      => 1,
					'return_format' => 'id',
					'preview_size'  => 'medium',
					'library'       => 'all',
				),
				array(
					'key'           => 'field_saop_landing_' . $section_key . '_overlay_opacity',
					'label'         => __('Overlay Opacity', 'saop'),
					'name'          => 'overlay_opacity',
					'type'          => 'range',
					'instructions'  => __('Adjust the darkness of the color overlay over the background image.', 'saop'),
					'required'      => 1,
					'default_value' => 70,
					'min'           => 0,
					'max'           => 100,
					'step'          => 5,
					'append'        => '%',
				),
			);
		};

		acf_add_local_field_group(
			array(
				'key'            => 'group_saop_landing_page',
				'title'          => __('Landing Page Sections', 'saop'),
				'fields'         => array(
					array(
						'key'        => 'field_saop_landing_section_one',
						'label'      => __('School', 'saop'),
						'name'       => 'landing_section_one',
						'type'       => 'group',
						'required'   => 1,
						'layout'     => 'block',
						'sub_fields' => $section_fields('one'),
					),
					array(
						'key'        => 'field_saop_landing_section_two',
						'label'      => __('Church', 'saop'),
						'name'       => 'landing_section_two',
						'type'       => 'group',
						'required'   => 1,
						'layout'     => 'block',
						'sub_fields' => $section_fields('two'),
					),
				),
				'location'       => array(
					array(
						array(
							'param'    => 'page_template',
							'operator' => '==',
							'value'    => SAOP_LANDING_PAGE_TEMPLATE,
						),
					),
				),
				'position'       => 'acf_after_title',
				'hide_on_screen' => array('the_content', 'featured_image'),
			)
		);
	}
endif;
add_action('acf/init', 'saop_register_landing_page_fields');