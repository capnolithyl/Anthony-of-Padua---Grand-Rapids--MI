<?php
/**
 * Template part for a compact Discover post.
 *
 * @package St._Anthony_of_Padua
 */

$news_post = $args['post'] ?? null;

if ( ! $news_post instanceof WP_Post ) {
	return;
}

$post_category = $args['category'] ?? null;

if ( ! $post_category instanceof WP_Term ) {
	$post_categories = get_the_category( $news_post->ID );
	$post_category   = $post_categories[0] ?? null;
}
?>

<article>
	<a class="group grid min-h-38.75 grid-cols-[46.5%_minmax(0,1fr)] gap-3.5 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-2 md:min-h-34 md:grid-cols-[145px_minmax(0,1fr)] md:gap-4"
		href="<?php echo esc_url( get_permalink( $news_post ) ); ?>">
		<div class="h-38.75 overflow-hidden rounded-2lg bg-secondary-3 shadow-md md:h-34">
			<?php if ( has_post_thumbnail( $news_post ) ) : ?>
				<?php
				echo get_the_post_thumbnail(
					$news_post,
					'medium',
					array(
						'class' => 'h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-105',
					)
				);
				?>
			<?php endif; ?>
		</div>

		<div class="flex min-w-0 flex-col">
			<?php if ( $post_category instanceof WP_Term ) : ?>
				<p class="font-heading text-sm leading-normal font-bold text-primary-1 uppercase">
					<?php echo esc_html( $post_category->name ); ?>
				</p>
			<?php endif; ?>

			<h3 class="mt-2 font-heading text-lg leading-normal font-normal text-black transition-colors group-hover:text-primary-2">
				<?php echo esc_html( get_the_title( $news_post ) ); ?>
			</h3>

			<time class="mt-auto block pt-2 text-sm leading-normal text-secondary-5"
				datetime="<?php echo esc_attr( get_the_date( DATE_W3C, $news_post ) ); ?>">
				<?php echo esc_html( get_the_date( '', $news_post ) ); ?>
			</time>
		</div>
	</a>
</article>
