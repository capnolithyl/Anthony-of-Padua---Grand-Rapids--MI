<?php
/**
 * Ministry archive card.
 *
 * @package St._Anthony_of_Padua
 */

$ministry_id = isset( $args['ministry_id'] ) ? absint( $args['ministry_id'] ) : get_the_ID();
$ministry    = get_post( $ministry_id );

if ( ! $ministry instanceof WP_Post || 'ministry' !== $ministry->post_type ) {
	return;
}

$ministry_url   = get_permalink( $ministry );
$ministry_title = get_the_title( $ministry );
?>

<article <?php post_class( '', $ministry_id ); ?>>
	<a class="group relative block aspect-[372/229] overflow-hidden rounded-2lg bg-primary-1 shadow-md focus-visible:outline-3 focus-visible:outline-offset-3 focus-visible:outline-primary-2 lg:aspect-[455/293]"
		href="<?php echo esc_url( $ministry_url ); ?>">
		<?php if ( has_post_thumbnail( $ministry_id ) ) : ?>
			<?php
			echo get_the_post_thumbnail(
				$ministry_id,
				'large',
				array(
					'alt'      => '',
					'class'    => 'absolute inset-0 h-full w-full object-cover transition-transform duration-500 motion-safe:group-hover:scale-105',
					'decoding' => 'async',
					'loading'  => 'lazy',
				)
			);
			?>
		<?php endif; ?>

		<div class="absolute inset-0 bg-linear-to-t from-black/80 via-black/15 to-transparent" aria-hidden="true"></div>

		<div class="relative flex h-full flex-col justify-end px-5.5 pt-8 pb-4 text-white">
			<h3 class="font-heading text-xl leading-tight font-normal lg:text-2xl">
				<?php echo esc_html( $ministry_title ); ?>
			</h3>
			<span class="mt-1 font-heading text-[15px] leading-normal font-bold text-secondary-2 uppercase underline underline-offset-2">
				<?php esc_html_e( 'More', 'saop' ); ?>
			</span>
		</div>
	</a>
</article>
