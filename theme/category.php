<?php
/**
 * The template for displaying standard post category archives.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#category
 *
 * @package St._Anthony_of_Padua
 */

$saop_category             = get_queried_object();
$saop_category_name        = $saop_category instanceof WP_Term ? $saop_category->name : single_cat_title( '', false );
$saop_category_description = category_description();
/* translators: %s: Category name. */
$saop_category_posts_label = sprintf( __( 'Posts in %s', 'saop' ), $saop_category_name );

get_header();
?>

<main id="primary" class="not-prose overflow-hidden bg-secondary-1">
	<header class="pt-12 md:pt-16 lg:pt-20">
		<h1 class="relative z-10 m-0 flex min-h-14 w-fit max-w-full items-center rounded-r-[30px] bg-primary-2 py-2 pr-8 pl-5 font-heading text-3xl leading-normal font-normal text-primary-1 shadow-md md:min-h-17.5 md:pr-10 md:pl-6 md:text-4-5xl lg:min-h-20 lg:pr-11.25 lg:pl-7.5 lg:text-5xl">
			<?php echo esc_html( $saop_category_name ); ?>
		</h1>

		<?php if ( $saop_category_description ) : ?>
			<div class="mx-auto mt-8 max-w-wide px-6 text-lg leading-normal text-primary-1 md:px-8 lg:px-12 lg:text-xl [&_p]:m-0">
				<?php echo wp_kses_post( $saop_category_description ); ?>
			</div>
		<?php endif; ?>
	</header>

	<section class="mx-auto max-w-wide px-6 pt-10 pb-16 md:px-8 md:pt-12 md:pb-20 lg:px-12 lg:pt-16 lg:pb-24"
		aria-label="<?php echo esc_attr( $saop_category_posts_label ); ?>">
		<?php if ( have_posts() ) : ?>
			<div class="grid gap-6 md:grid-cols-2 md:gap-8 lg:grid-cols-3">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part(
						'template-parts/content/content-news-archive-card',
						null,
						array( 'post' => get_post() )
					);
				endwhile;
				?>
			</div>

			<div class="mt-12 [&_.current]:bg-primary-2 [&_.current]:text-white [&_.dots]:border-transparent [&_.nav-links]:flex [&_.nav-links]:flex-wrap [&_.nav-links]:items-center [&_.nav-links]:justify-center [&_.nav-links]:gap-2 [&_.page-numbers]:inline-flex [&_.page-numbers]:min-h-11 [&_.page-numbers]:min-w-11 [&_.page-numbers]:items-center [&_.page-numbers]:justify-center [&_.page-numbers]:rounded-2lg [&_.page-numbers]:border-2 [&_.page-numbers]:border-primary-2 [&_.page-numbers]:px-3 [&_.page-numbers]:font-heading [&_.page-numbers]:text-base [&_.page-numbers]:font-bold [&_.page-numbers]:text-primary-1 [&_.page-numbers]:transition-colors [&_a.page-numbers:focus-visible]:outline-2 [&_a.page-numbers:focus-visible]:outline-offset-2 [&_a.page-numbers:focus-visible]:outline-primary-1 [&_a.page-numbers:hover]:bg-white">
				<?php saop_the_posts_navigation(); ?>
			</div>
		<?php else : ?>
			<p class="text-lg leading-normal text-primary-1">
				<?php esc_html_e( 'No news posts are available in this category yet.', 'saop' ); ?>
			</p>
		<?php endif; ?>
	</section>
</main>

<?php
get_footer();
