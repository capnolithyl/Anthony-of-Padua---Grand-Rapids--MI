<?php
/**
 * Template part for a featured Discover post.
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

$post_excerpt_source = has_excerpt( $news_post )
	? get_the_excerpt( $news_post )
	: wp_strip_all_tags( strip_shortcodes( get_post_field( 'post_content', $news_post->ID ) ) );
$post_excerpt        = wp_trim_words( $post_excerpt_source, 75, '&hellip;' );
?>

<article class="grid min-h-38.75 grid-cols-[46.5%_minmax(0,1fr)] gap-3.5 md:min-h-79 md:grid-cols-[250px_minmax(0,1fr)] md:gap-4 lg:h-full lg:grid-cols-[minmax(240px,44%)_minmax(0,1fr)] lg:gap-6">
	<a class="block h-38.75 overflow-hidden rounded-2lg bg-secondary-3 shadow-md md:h-79 lg:h-full"
		href="<?php echo esc_url( get_permalink( $news_post ) ); ?>" aria-hidden="true" tabindex="-1">
		<?php if ( has_post_thumbnail( $news_post ) ) : ?>
			<?php
			echo get_the_post_thumbnail(
				$news_post,
				'large',
				array( 'class' => 'h-full w-full object-cover' )
			);
			?>
		<?php endif; ?>
	</a>

	<div class="flex min-w-0 flex-col">
		<?php if ( $post_category instanceof WP_Term ) : ?>
			<p class="font-heading text-sm leading-normal font-bold text-primary-1 uppercase">
				<?php echo esc_html( $post_category->name ); ?>
			</p>
		<?php endif; ?>

		<h3 class="mt-2 font-heading text-lg leading-normal font-normal text-black md:mt-4 md:text-2xl">
			<a class="transition-colors hover:text-primary-2 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-2"
				href="<?php echo esc_url( get_permalink( $news_post ) ); ?>">
				<?php echo esc_html( get_the_title( $news_post ) ); ?>
			</a>
		</h3>

		<time class="mt-auto block pt-2 text-sm leading-normal text-secondary-5 md:mt-2 md:pt-0"
			datetime="<?php echo esc_attr( get_the_date( DATE_W3C, $news_post ) ); ?>">
			<?php echo esc_html( get_the_date( '', $news_post ) ); ?>
		</time>

		<?php if ( $post_excerpt ) : ?>
			<p class="mt-2 hidden text-base leading-normal text-black md:block">
				<?php echo wp_kses_post( $post_excerpt ); ?>
			</p>
		<?php endif; ?>

		<a class="mt-4 hidden text-sm font-bold text-primary-2 underline underline-offset-2 md:inline-block"
			href="<?php echo esc_url( get_permalink( $news_post ) ); ?>">
			<?php esc_html_e( 'Read More', 'saop' ); ?>
		</a>
	</div>
</article>
