<?php
/**
 * Template part for a post in the Latest News carousel.
 *
 * @package St._Anthony_of_Padua
 */

$news_post = $args['post'] ?? null;

if ( ! $news_post instanceof WP_Post ) {
	return;
}

$post_categories = get_the_category( $news_post->ID );
$post_category   = $post_categories[0] ?? null;
?>

<article class="h-58 px-1.25 md:h-67.5 lg:h-91">
	<a class="group relative block h-50.5 translate-y-3.75 overflow-hidden rounded-2lg bg-secondary-3 shadow-md transition-[height,transform] duration-500 md:h-67.5 md:translate-y-0 lg:h-91"
		href="<?php echo esc_url( get_permalink( $news_post ) ); ?>">
		<?php if ( has_post_thumbnail( $news_post ) ) : ?>
			<?php
			echo get_the_post_thumbnail(
				$news_post,
				'large',
				array(
					'class' => 'h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-105',
				)
			);
			?>
		<?php endif; ?>

		<div class="absolute inset-0 bg-linear-to-t from-black/85 via-black/15 to-transparent" aria-hidden="true"></div>

		<?php if ( $post_category instanceof WP_Term ) : ?>
			<span class="absolute top-4 left-4 rounded-2lg bg-primary-2 px-4 py-1.5 font-heading text-sm leading-none font-bold text-white uppercase">
				<?php echo esc_html( $post_category->name ); ?>
			</span>
		<?php endif; ?>

		<div class="absolute inset-x-0 bottom-0 px-5 pt-16 pb-5 md:px-7 lg:px-9 lg:pb-6">
			<h2 class="text-xl leading-normal font-medium text-white" data-news-card-title>
				<?php echo esc_html( get_the_title( $news_post ) ); ?>
			</h2>

			<span class="mt-1 block text-sm font-bold text-secondary-2 underline underline-offset-2 md:hidden">
				<?php esc_html_e( 'Read More', 'saop' ); ?>
			</span>
		</div>
	</a>
</article>
