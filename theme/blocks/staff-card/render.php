<?php

/**
 * Staff card block template.
 *
 * @package St._Anthony_of_Padua
 */
$is_manual = (bool) get_field('is_manual');
$staff_id  = 0;
$photo_id  = 0;
$photo_url = '';
$bio_url   = '';
$biography = '';

if ($is_manual) {
	$name      = sanitize_text_field((string) get_field('staff_member_name'));
	$position  = sanitize_text_field((string) get_field('staff_member_position'));
	$email     = sanitize_email((string) get_field('staff_member_email_address'));
	$phone     = sanitize_text_field((string) get_field('staff_member_phone_number'));
	$biography = (string) get_field('staff_member_biography');
	$photo     = get_field('staff_member_photo');

	// Support each of ACF's image return formats: array, attachment ID, or URL.
	if (is_array($photo)) {
		$photo_id  = absint($photo['ID'] ?? $photo['id'] ?? 0);
		$photo_url = isset($photo['url']) && is_string($photo['url']) ? $photo['url'] : '';
	} elseif (is_numeric($photo)) {
		$photo_id = absint($photo);
	} elseif (is_string($photo)) {
		$photo_url = $photo;
	}

	$has_bio   = '' !== trim(wp_strip_all_tags($biography));
	$dialog_id = wp_unique_id('staff-bio-manual-');
} else {
	$staff_member = get_field('staff_member');
	$staff_id     = $staff_member instanceof WP_Post ? $staff_member->ID : absint($staff_member);

	if (! $staff_id || 'staff' !== get_post_type($staff_id)) {
		if (is_admin()) {
			$wrapper_attributes = get_block_wrapper_attributes(
				array(
					'class' => 'not-prose flex min-h-44 w-full max-w-63.25 items-center justify-center rounded-2lg border-2 border-dashed border-secondary-4 bg-secondary-1 p-6 text-center text-sm text-secondary-6',
				)
			);
			?>
			<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Core-generated block attributes.
					?>>
				<?php esc_html_e('Choose a staff member to display.', 'saop'); ?>
			</div>
			<?php
		}

		return;
	}

	$name      = get_the_title($staff_id);
	$position  = saop_get_staff_field('staff_position', $staff_id);
	$email     = sanitize_email(saop_get_staff_field('staff_email', $staff_id));
	$phone     = saop_get_staff_field('staff_phone', $staff_id);
	$photo_id  = get_post_thumbnail_id($staff_id);
	$bio_url   = saop_get_staff_bio_url($staff_id);
	$has_bio   = (bool) $bio_url;
	$dialog_id = wp_unique_id('staff-bio-' . $staff_id . '-');
}

$phone_uri = saop_get_staff_phone_href($phone);

wp_enqueue_script('saop-staff-directory');

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class'                => 'not-prose w-full max-w-63.25 overflow-hidden rounded-2lg bg-secondary-1 shadow-md mb-12 p-0',
		'data-staff-directory' => '',
	)
);
?>

<article <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Core-generated block attributes.
			?>>
	<div class="relative h-66.75 overflow-hidden bg-secondary-3">
		<?php if ($photo_id) : ?>
			<?php
			echo wp_get_attachment_image(
				$photo_id,
				'large',
				false,
				array(
					'class'   => 'absolute inset-0 h-full w-full object-cover',
					'loading' => 'lazy',
				)
			); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Core-generated attachment markup.
			?>
		<?php elseif ($photo_url) : ?>
			<img src="<?php echo esc_url($photo_url); ?>"
				alt="<?php echo esc_attr($name); ?>"
				class="absolute inset-0 h-full w-full object-cover" loading="lazy">
		<?php endif; ?>
	</div>

	<div class="flex h-43.5 min-w-0 flex-col px-3 py-3.75">
		<p class="m-0 text-center font-heading text-xl leading-normal text-black">
			<?php echo esc_html($name); ?>
		</p>

		<?php if ($position) : ?>
			<p class="mt-3.75 mb-0 text-center font-heading text-lg leading-normal text-black">
				<?php echo esc_html($position); ?>
			</p>
		<?php endif; ?>

		<div class="mt-auto space-y-2 text-[15px] leading-normal text-secondary-3">
			<?php if ($email) : ?>
				<a class="flex min-w-0 items-center gap-3 hover:underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-2"
					href="<?php echo esc_url('mailto:' . $email); ?>">
					<img class="h-4.5 w-5.5 shrink-0"
						src="<?php echo esc_url(get_theme_file_uri('/assets/img/staff-mail.svg')); ?>" alt=""
						aria-hidden="true">
					<span class="min-w-0 wrap-break-word"><?php echo esc_html($email); ?></span>
				</a>
			<?php endif; ?>

			<div class="grid grid-cols-[minmax(0,1fr)_auto] items-center gap-3">
				<?php if ($phone && $phone_uri) : ?>
					<a class="flex min-w-0 items-center gap-3 hover:underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-2"
						href="<?php echo esc_url($phone_uri); ?>">
						<img class="size-4.5 shrink-0"
							src="<?php echo esc_url(get_theme_file_uri('/assets/img/staff-phone.svg')); ?>" alt=""
							aria-hidden="true">
						<span><?php echo esc_html($phone); ?></span>
					</a>
				<?php else : ?>
					<span></span>
				<?php endif; ?>

				<?php if ($has_bio) : ?>
					<?php if ($is_manual) : ?>
						<button type="button"
							class="uppercase hover:underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-2"
							aria-controls="<?php echo esc_attr($dialog_id); ?>"
							aria-haspopup="dialog"
							data-staff-bio-trigger="<?php echo esc_attr($dialog_id); ?>">
							<?php esc_html_e('Bio', 'saop'); ?>
						</button>
					<?php else : ?>
						<a class="uppercase hover:underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-2"
							href="<?php echo esc_url($bio_url); ?>" aria-controls="<?php echo esc_attr($dialog_id); ?>"
							aria-haspopup="dialog" data-staff-bio-trigger="<?php echo esc_attr($staff_id); ?>">
							<?php esc_html_e('Bio', 'saop'); ?>
						</a>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<?php
	get_template_part(
		'template-parts/content/content',
		'staff-dialog',
		array(
			'staff_id'    => $staff_id,
			'dialog_id'   => $dialog_id,
			'manual_staff' => $is_manual
				? array(
					'name'       => $name,
					'position'   => $position,
					'email'      => $email,
					'phone'      => $phone,
					'biography'  => $biography,
					'photo_id'   => $photo_id,
					'photo_url'  => $photo_url,
				)
				: null,
		)
	);
	?>
</article>