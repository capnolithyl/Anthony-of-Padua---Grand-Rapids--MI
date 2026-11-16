<?php
/**
 * Template part for displaying the footer content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package St._Anthony_of_Padua
 */

$menu_locations = get_nav_menu_locations();
$footer_menu_id = $menu_locations['menu-2'] ?? 0;
$footer_buttons = $footer_menu_id && saop_has_acf()
	? get_field( 'buttons', 'nav_menu_' . $footer_menu_id )
	: array();
$footer_buttons = is_array( $footer_buttons ) ? $footer_buttons : array();

$footer_args = array(
	'address'                => saop_get_theme_setting( 'address', '' ),
	'phone'                  => saop_get_theme_setting( 'phone_number', '' ),
	'fax'                    => saop_get_theme_setting( 'fax', '' ),
	'email'                  => saop_get_theme_setting( 'email', '' ),
	'footer_buttons'         => $footer_buttons,
	'footer_cta_heading'     => saop_get_theme_setting( 'cta_heading', __( 'Get Started Today', 'saop' ) ),
	'footer_cta_blurb'       => saop_get_theme_setting( 'cta_paragraph', '' ),
	'footer_cta_form'        => saop_get_theme_setting( 'cta_form', null ),
	'footer_connect_heading' => saop_get_theme_setting( 'connect_heading', __( 'Connect With Us', 'saop' ) ),
	'office_hours_heading'   => saop_get_theme_setting( 'office_hours_heading', __( 'School Office Hours', 'saop' ) ),
	'office_hours'           => saop_get_theme_setting( 'office_hours', array() ),
	'social_links'           => saop_get_social_links(),
);

?>

<footer id="colophon" class="w-full">
	<div class="flex w-full flex-col-reverse md:flex-row">
		<?php get_template_part( 'template-parts/footer/left', null, $footer_args ); ?>
		<?php get_template_part( 'template-parts/footer/right', null, $footer_args ); ?>
	</div>
</footer><!-- #colophon -->
