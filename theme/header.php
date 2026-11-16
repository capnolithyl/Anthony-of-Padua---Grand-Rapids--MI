<?php

/**
 * The header for our theme
 *
 * This is the template that displays the `head` element and everything up
 * until the `#content` element.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package St._Anthony_of_Padua
 */

$saop_has_site_header = ! function_exists('saop_is_landing_page') || ! saop_is_landing_page();
$saop_content_class   = $saop_has_site_header ? 'site-content--offset-header' : '';
?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<?php wp_body_open(); ?>

	<div id="page">
		<a href="#content" class="sr-only"><?php esc_html_e('Skip to content', 'saop'); ?></a>

		<?php if ($saop_has_site_header) : ?>
			<?php get_template_part('template-parts/layout/header', 'content'); ?>
		<?php endif; ?>

		<div id="content" class="<?php echo esc_attr($saop_content_class); ?>">