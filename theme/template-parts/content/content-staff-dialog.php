<?php
/**
 * Staff biography dialog.
 *
 * @package St._Anthony_of_Padua
 */

$staff_id    = ! empty( $args['staff_id'] ) ? absint( $args['staff_id'] ) : get_the_ID();
$staff       = get_post( $staff_id );
$name        = get_the_title( $staff_id );
$position    = saop_get_staff_field( 'staff_position', $staff_id );
$email       = sanitize_email( saop_get_staff_field( 'staff_email', $staff_id ) );
$phone       = saop_get_staff_field( 'staff_phone', $staff_id );
$phone_uri   = saop_get_staff_phone_href( $phone );
$dialog_id   = ! empty( $args['dialog_id'] )
	? sanitize_html_class( $args['dialog_id'] )
	: 'staff-bio-' . $staff_id;
$title_id    = $dialog_id . '-title';
$close_label = sprintf(
	/* translators: %s: Staff member name. */
	__( 'Close biography for %s', 'saop' ),
	$name
);

$content = $staff instanceof WP_Post
	? apply_filters( 'the_content', $staff->post_content )
	: '';
?>

<dialog id="<?php echo esc_attr( $dialog_id ); ?>"
	class="m-auto max-h-[calc(100dvh-2rem)] w-[calc(100%-2rem)] max-w-321.25 overflow-y-auto rounded-3xl bg-secondary-1 p-0 text-black shadow-md backdrop:bg-primary-1/70 lg:h-183.5"
	aria-labelledby="<?php echo esc_attr( $title_id ); ?>"
	data-staff-dialog
	data-staff-slug="<?php echo esc_attr( $staff instanceof WP_Post ? $staff->post_name : '' ); ?>">
	<div class="relative min-h-full px-6 py-8 lg:grid lg:grid-cols-[393px_minmax(0,1fr)] lg:gap-x-9.5 lg:px-9.5 lg:py-7">
		<button class="absolute top-5 right-5 z-10 flex size-9 items-center justify-center rounded-full border-3 border-secondary-6 text-[30px] leading-none text-secondary-6 transition-colors hover:border-primary-2 hover:text-primary-2 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-2"
			type="button"
			aria-label="<?php echo esc_attr( $close_label ); ?>"
			data-staff-dialog-close>
			<span aria-hidden="true">&times;</span>
		</button>

		<div class="flex min-w-0 flex-col pt-0 lg:pt-0">
			<h2 id="<?php echo esc_attr( $title_id ); ?>"
				class="pr-11 text-center font-heading text-2xl leading-normal font-bold text-primary-1 lg:min-h-10.5 lg:pr-0 lg:text-[28px]">
				<?php echo esc_html( $name ); ?>
			</h2>

			<div class="relative mt-5 aspect-393/486 w-full overflow-hidden rounded-3xl bg-secondary-3 shadow-md lg:mt-6.75 lg:aspect-auto lg:h-121.5">
				<?php if ( has_post_thumbnail( $staff_id ) ) : ?>
					<?php
					echo get_the_post_thumbnail(
						$staff_id,
						'large',
						array(
							'class'   => 'absolute inset-0 h-full w-full object-cover',
							'loading' => 'lazy',
						)
					); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Core-generated attachment markup.
					?>
				<?php endif; ?>
			</div>

			<?php if ( $position ) : ?>
				<p class="mt-4 text-center font-heading text-2xl leading-normal text-black lg:text-[28px]">
					<?php echo esc_html( $position ); ?>
				</p>
			<?php endif; ?>

			<?php if ( ( $phone && $phone_uri ) || $email ) : ?>
				<div class="mt-6 flex flex-wrap items-center justify-between gap-x-8 gap-y-3 text-lg leading-normal text-primary-2 lg:text-xl">
					<?php if ( $phone && $phone_uri ) : ?>
						<a class="uppercase hover:underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-2"
							href="<?php echo esc_url( $phone_uri ); ?>">
							<?php echo esc_html( $phone ); ?>
						</a>
					<?php endif; ?>

					<?php if ( $email ) : ?>
						<a class="hover:underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-2"
							href="<?php echo esc_url( 'mailto:' . $email ); ?>">
							<?php esc_html_e( 'Email Me', 'saop' ); ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( trim( wp_strip_all_tags( $content ) ) ) : ?>
			<div <?php saop_content_class( 'mt-10 text-base leading-[1.3] text-black prose-headings:mb-2 prose-headings:mt-0 prose-headings:font-heading prose-headings:text-xl prose-headings:font-bold prose-headings:leading-normal prose-p:mb-6 prose-p:mt-0 lg:mt-0 lg:pt-17.25 lg:prose-p:pl-4' ); ?>>
				<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Filtered WordPress post content. ?>
			</div>
		<?php endif; ?>
	</div>
</dialog>
