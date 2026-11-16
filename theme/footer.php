<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the `#content` element and all content thereafter.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package St._Anthony_of_Padua
 */

?>

	</div><!-- #content -->

	<?php if ( ! function_exists( 'saop_is_landing_page' ) || ! saop_is_landing_page() ) : ?>
		<?php get_template_part( 'template-parts/layout/footer', 'content' ); ?>
	<?php endif; ?>

	<?php if ( is_front_page() ) : ?>
		<?php get_template_part( 'template-parts/homepage/alert' ); ?>
	<?php endif; ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
