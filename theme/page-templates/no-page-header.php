<?php

/**
 * Template Name: Page Without Header
 * Template Post Type: page
 *
 * @package St._Anthony_of_Padua
 */

get_header();
?>

<section id="primary">
    <main id="main">
        <?php
		/* Start the Loop */
		while (have_posts()) :
			the_post();

			get_template_part(
				'template-parts/content/content',
				'page',
				array(
					'show_page_header' => false,
				)
			);

		endwhile; // End of the loop.
		?>
    </main><!-- #main -->
</section><!-- #primary -->

<?php
get_footer();
