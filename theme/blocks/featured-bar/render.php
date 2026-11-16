<?php
/**
 * Featured bar block template.
 *
 * @package St._Anthony_of_Padua
 */

$enabled = (bool) get_field( 'enabled' );
$items   = get_field( 'items' );

if ( ! $enabled || empty( $items ) ) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'saop-section saop-featured-bar not-prose alignfull',
	)
);
?>

<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> data-saop-section>
	<div class="saop-featured-bar__inner">
		<?php foreach ( $items as $item ) : ?>
			<?php if ( empty( $item['link']['url'] ) || empty( $item['link']['title'] ) ) : ?>
				<?php continue; ?>
			<?php endif; ?>

			<a class="saop-featured-bar__item" href="<?php echo esc_url( $item['link']['url'] ); ?>" target="<?php echo esc_attr( $item['link']['target'] ?: '_self' ); ?>">
				<span><?php echo esc_html( $item['link']['title'] ); ?></span>
			</a>
		<?php endforeach; ?>
	</div>
</section>
