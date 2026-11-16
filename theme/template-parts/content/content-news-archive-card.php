<?php
/**
 * Template part for a post in a category archive grid.
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

<article>
	<a class="group relative block aspect-4/3 overflow-hidden rounded-2lg bg-secondary-3 shadow-md focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary-2"
		href="<?php echo esc_url( get_permalink( $news_post ) ); ?>">
		<?php if ( has_post_thumbnail( $news_post ) ) : ?>
			<?php
			echo get_the_post_thumbnail(
				$news_post,
				'large',
				array(
					'alt'   => '',
					'class' => 'h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-105',
				)
			);
			?>
		<?php endif; ?>

		<div class="absolute inset-0 bg-linear-to-t from-black/90 via-black/20 to-transparent" aria-hidden="true"></div>

		<?php if ( $post_category instanceof WP_Term ) : ?>
			<span class="absolute top-4 left-4 rounded-2lg bg-primary-2 px-4 py-1.5 font-heading text-sm leading-none font-bold text-white uppercase">
				<?php echo esc_html( $post_category->name ); ?>
			</span>
		<?php endif; ?>

		<div class="absolute inset-x-0 bottom-0 px-5 pt-16 pb-5 md:px-6">
			<h2 class="font-heading text-xl leading-normal font-medium text-white md:text-2xl">
				<?php echo esc_html( get_the_title( $news_post ) ); ?>
			</h2>

			<time class="mt-2 block text-sm leading-normal text-white/80"
				datetime="<?php echo esc_attr( get_the_date( DATE_W3C, $news_post ) ); ?>">
				<?php echo esc_html( get_the_date( '', $news_post ) ); ?>
			</time>
		</div>
	</a>
</article>
