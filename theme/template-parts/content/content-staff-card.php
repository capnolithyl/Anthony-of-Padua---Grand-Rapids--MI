<?php
/**
 * Staff directory card.
 *
 * @package St._Anthony_of_Padua
 */

$staff_id         = ! empty( $args['staff_id'] ) ? absint( $args['staff_id'] ) : get_the_ID();
$staff_section_id = ! empty( $args['staff_section_id'] ) ? sanitize_html_class( $args['staff_section_id'] ) : '';
$name             = get_the_title( $staff_id );
$position         = saop_get_staff_field( 'staff_position', $staff_id );
$email            = sanitize_email( saop_get_staff_field( 'staff_email', $staff_id ) );
$phone            = saop_get_staff_field( 'staff_phone', $staff_id );
$phone_uri        = saop_get_staff_phone_href( $phone );
$bio_url          = saop_get_staff_bio_url( $staff_id, $staff_section_id );
$dialog_id        = 'staff-bio-' . $staff_id;
?>

<article class="grid h-50.5 grid-cols-[38%_62%] overflow-hidden rounded-2lg bg-secondary-1 shadow-md lg:h-57.75">
	<div class="relative overflow-hidden bg-secondary-3">
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

	<div class="flex min-w-0 flex-col px-5 py-4">
		<h3 class="text-center font-heading text-lg leading-normal text-black lg:text-xl">
			<?php echo esc_html( $name ); ?>
		</h3>

		<?php if ( $position ) : ?>
			<p class="mt-7 text-center font-heading text-lg leading-normal text-black">
				<?php echo esc_html( $position ); ?>
			</p>
		<?php endif; ?>

		<div class="mt-auto space-y-2 text-[15px] leading-normal text-secondary-3">
			<?php if ( $email ) : ?>
				<a class="flex min-w-0 items-center gap-4 hover:underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-2"
					href="<?php echo esc_url( 'mailto:' . $email ); ?>">
					<img class="h-4.5 w-5.5 shrink-0" src="<?php echo esc_url( get_theme_file_uri( '/assets/img/staff-mail.svg' ) ); ?>" alt="" aria-hidden="true">
					<span class="min-w-0 wrap-break-word"><?php echo esc_html( $email ); ?></span>
				</a>
			<?php endif; ?>

			<div class="grid grid-cols-[minmax(0,1fr)_auto] items-center gap-3">
				<?php if ( $phone && $phone_uri ) : ?>
					<a class="flex min-w-0 items-center gap-4 hover:underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-2"
						href="<?php echo esc_url( $phone_uri ); ?>">
						<img class="size-4.5 shrink-0" src="<?php echo esc_url( get_theme_file_uri( '/assets/img/staff-phone.svg' ) ); ?>" alt="" aria-hidden="true">
						<span><?php echo esc_html( $phone ); ?></span>
					</a>
				<?php else : ?>
					<span></span>
				<?php endif; ?>

				<?php if ( $bio_url ) : ?>
					<a class="uppercase hover:underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-2"
						href="<?php echo esc_url( $bio_url ); ?>"
						aria-controls="<?php echo esc_attr( $dialog_id ); ?>"
						aria-haspopup="dialog"
						data-staff-bio-trigger="<?php echo esc_attr( $staff_id ); ?>">
						<?php esc_html_e( 'Bio', 'saop' ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</article>
