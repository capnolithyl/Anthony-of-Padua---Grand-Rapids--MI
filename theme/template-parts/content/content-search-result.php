<?php
/**
 * Template part for displaying a search result.
 *
 * @package St._Anthony_of_Padua
 */

$saop_search_post_type_object = get_post_type_object( get_post_type() );
$saop_search_post_type_label  = $saop_search_post_type_object instanceof WP_Post_Type
	? $saop_search_post_type_object->labels->singular_name
	: __( 'Content', 'saop' );
$saop_search_has_thumbnail    = has_post_thumbnail();

if ( post_password_required() || has_excerpt() ) {
	$saop_search_result_excerpt = get_the_excerpt();
} else {
	$saop_search_result_excerpt = wp_trim_words(
		wp_strip_all_tags( strip_shortcodes( get_post_field( 'post_content', get_the_ID() ) ) ),
		34,
		'&hellip;'
	);
}

$saop_search_result_classes = 'group overflow-hidden rounded-2lg border border-secondary-4 bg-white shadow-md transition-shadow hover:shadow-lg focus-within:ring-2 focus-within:ring-primary-2 focus-within:ring-offset-2';

if ( $saop_search_has_thumbnail ) {
	$saop_search_result_classes .= ' grid md:grid-cols-[14rem_minmax(0,1fr)]';
}

/* translators: %s: Search result title. */
$saop_search_read_more_label = sprintf( __( 'Read more about %s', 'saop' ), get_the_title() );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $saop_search_result_classes ); ?>>
	<?php if ( $saop_search_has_thumbnail ) : ?>
		<a class="block h-52 overflow-hidden bg-secondary-3 md:h-full md:min-h-56" href="<?php the_permalink(); ?>"
			aria-hidden="true" tabindex="-1">
			<?php
			the_post_thumbnail(
				'medium_large',
				array(
					'alt'     => '',
					'class'   => 'h-full w-full object-cover transition-transform duration-500 ease-out motion-safe:group-hover:scale-105',
					'loading' => 'lazy',
				)
			);
			?>
		</a>
	<?php endif; ?>

	<div class="flex flex-col px-6 py-6 md:px-8 md:py-7">
		<div class="flex flex-wrap items-center gap-x-3 gap-y-1">
			<p class="font-heading text-sm leading-normal font-bold tracking-wide text-primary-2 uppercase">
				<?php echo esc_html( $saop_search_post_type_label ); ?>
			</p>

			<?php if ( 'post' === get_post_type() ) : ?>
				<span class="h-1 w-1 rounded-full bg-secondary-4" aria-hidden="true"></span>
				<time class="text-sm text-secondary-5" datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
					<?php echo esc_html( get_the_date() ); ?>
				</time>
			<?php endif; ?>
		</div>

		<h2 class="mt-2 font-heading text-2xl leading-tight font-bold text-primary-1 md:text-3xl">
			<a class="transition-colors group-hover:text-primary-2 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-2"
				href="<?php the_permalink(); ?>" rel="bookmark">
				<?php the_title(); ?>
			</a>
		</h2>

		<?php if ( $saop_search_result_excerpt ) : ?>
			<p class="mt-3 text-base leading-relaxed text-secondary-6">
				<?php echo wp_kses_post( $saop_search_result_excerpt ); ?>
			</p>
		<?php endif; ?>

		<a class="mt-5 inline-flex w-fit items-center gap-2 text-sm font-bold text-primary-2 underline decoration-2 underline-offset-4 transition-colors hover:text-secondary-3 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-2"
			href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( $saop_search_read_more_label ); ?>">
			<?php esc_html_e( 'Read More', 'saop' ); ?>
			<span aria-hidden="true">&rarr;</span>
		</a>
	</div>
</article>
