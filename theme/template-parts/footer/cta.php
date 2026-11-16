<?php
/**
 * Footer call-to-action and configured Gravity Form.
 *
 * @package St._Anthony_of_Padua
 */

$footer_cta_heading = $args['footer_cta_heading'] ?? __( 'Get Started Today', 'saop' );
$footer_cta_blurb   = $args['footer_cta_blurb'] ?? '';
$footer_cta_form    = $args['footer_cta_form'] ?? null;
$footer_cta_form_id = 0;

if ( ! $footer_cta_blurb ) {
	$footer_cta_blurb = __( 'Blurb about filling out form below', 'saop' );
}

if ( is_array( $footer_cta_form ) ) {
	$footer_cta_form_id = absint(
		$footer_cta_form['id'] ?? $footer_cta_form['ID'] ?? $footer_cta_form['form_id'] ?? 0
	);

	if ( ! $footer_cta_form_id ) {
		$footer_cta_first_form = reset( $footer_cta_form );

		if ( is_numeric( $footer_cta_first_form ) ) {
			$footer_cta_form_id = absint( $footer_cta_first_form );
		} elseif ( is_array( $footer_cta_first_form ) ) {
			$footer_cta_form_id = absint(
				$footer_cta_first_form['id'] ?? $footer_cta_first_form['ID'] ?? $footer_cta_first_form['form_id'] ?? 0
			);
		}
	}
} elseif ( is_object( $footer_cta_form ) ) {
	$footer_cta_form_id = absint(
		$footer_cta_form->id ?? $footer_cta_form->ID ?? $footer_cta_form->form_id ?? 0
	);
} elseif ( is_numeric( $footer_cta_form ) ) {
	$footer_cta_form_id = absint( $footer_cta_form );
}
?>

<section aria-labelledby="footer-cta-heading">
	<div class="flex items-center gap-3">
		<h2 id="footer-cta-heading" class="shrink-0 text-xl font-bold text-secondary-3 uppercase">
			<?php echo esc_html( $footer_cta_heading ); ?>
		</h2>
		<span class="h-0.5 flex-1 bg-secondary-3" aria-hidden="true"></span>
	</div>

	<div class="mt-2 text-sm text-black [&_p]:m-0">
		<?php echo wp_kses_post( wpautop( $footer_cta_blurb ) ); ?>
	</div>

	<?php if ( $footer_cta_form_id && function_exists( 'gravity_form' ) ) : ?>
		<div class="saop-footer-cta-form mt-4">
			<?php
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Trusted Gravity Forms output.
			echo gravity_form(
				$footer_cta_form_id,
				false,
				false,
				false,
				null,
				true,
				0,
				false
			);
			?>
		</div>
	<?php endif; ?>
</section>
