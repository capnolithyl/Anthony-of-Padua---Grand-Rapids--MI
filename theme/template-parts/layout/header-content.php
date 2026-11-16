<?php

/**
 * Template part for displaying the header content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package St._Anthony_of_Padua
 */

?>

<header id="masthead" class="fixed top-(--admin-bar-height) right-0 left-0 z-1000 bg-primary-1 transition-all"
	data-site-header>


	<div class="flex flex-row items-center justify-between">
		<?php get_template_part('template-parts/header/logo'); ?>
		<div class="flex flex-col items-end">
			<?php get_template_part('template-parts/header/top-bar'); ?>
			<?php get_template_part('template-parts/header/nav'); ?>


		</div>
	</div>
</header><!-- #masthead -->