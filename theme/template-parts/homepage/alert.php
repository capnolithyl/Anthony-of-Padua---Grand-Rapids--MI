<?php

/**
 * Homepage alert trigger and dialog.
 *
 * @package St._Anthony_of_Padua
 */

$alert = saop_get_homepage_alert();

if (! saop_is_homepage_alert_active($alert)) {
	return;
}

$dialog_id = 'homepage-alert-dialog';
$title_id  = $dialog_id . '-title';
$image     = saop_normalize_homepage_alert_image($alert['image']);
$image_id  = absint($image['id'] ?? 0);
$image_url = ! empty($image['url']) ? $image['url'] : '';
$image_alt = ! empty($image['alt']) ? $image['alt'] : '';
$has_image = $image_id || $image_url;
$cookie_value = substr(
	hash(
		'sha256',
		wp_json_encode(
			array(
				'heading'    => $alert['heading'],
				'subheading' => $alert['subheading'],
				'image'      => $alert['image'],
				'text'       => $alert['text'],
				'links'      => $alert['links'],
			)
		)
	),
	0,
	16
);
?>

<button
	class="homepage-alert-trigger fixed right-0 z-40 flex h-12 w-17.5 -translate-y-1/2 cursor-pointer items-center justify-center rounded-l-[30px] bg-accent-2 shadow-md transition-colors hover:bg-accent-3 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-1"
	type="button" aria-label="<?php esc_attr_e('Open important alert', 'saop'); ?>"
	aria-controls="<?php echo esc_attr($dialog_id); ?>" aria-expanded="false" aria-haspopup="dialog"
	data-homepage-alert-open>
	<span class="flex size-10 items-center justify-center" aria-hidden="true">
		<img class="size-[30.5px]"
			src="<?php echo esc_url(get_theme_file_uri('/assets/img/homepage-alert-icon.svg')); ?>" alt="">
	</span>
</button>

<dialog id="<?php echo esc_attr($dialog_id); ?>"
	class="homepage-alert-dialog m-auto overflow-y-auto rounded-3xl border-0 bg-secondary-1 p-0 text-primary-1 shadow-md <?php echo $has_image ? 'lg:h-183.5' : 'lg:h-auto'; ?>"
	aria-labelledby="<?php echo esc_attr($title_id); ?>" data-homepage-alert-dialog
	data-homepage-alert-cookie="saop_homepage_alert_dismissed"
	data-homepage-alert-cookie-value="<?php echo esc_attr($cookie_value); ?>">
	<div
		class="relative grid min-h-full gap-10 px-6 py-14 sm:px-9 lg:grid-cols-[minmax(0,544px)_minmax(0,1fr)] lg:gap-14 lg:px-10.5 lg:pt-17.5 lg:pb-13.75">
		<button
			class="absolute top-4 right-4 z-10 flex size-9 cursor-pointer items-center justify-center rounded-full border-3 border-primary-1 text-[30px] leading-none text-primary-1 transition-colors hover:border-primary-2 hover:text-primary-2 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-2"
			type="button" aria-label="<?php esc_attr_e('Close important alert', 'saop'); ?>"
			data-homepage-alert-close>
			<span aria-hidden="true">&times;</span>
		</button>

		<?php if ($has_image) : ?>
			<div class="homepage-alert-media relative aspect-544/559 min-w-0 lg:h-139.75 lg:aspect-auto">
				<div class="relative z-10 size-full overflow-hidden rounded-2lg bg-secondary-3 shadow-md">
					<?php if ($image_id) : ?>
						<?php
						echo wp_get_attachment_image(
							$image_id,
							'large',
							false,
							array(
								'class'   => 'size-full object-cover',
								'loading' => 'eager',
							)
						); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Core-generated attachment markup.
						?>
					<?php else : ?>
						<img class="size-full object-cover" src="<?php echo esc_url($image_url); ?>"
							alt="<?php echo esc_attr($image_alt); ?>" loading="eager">
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

		<div
			class="flex min-w-0 flex-col <?php echo $has_image ? 'lg:min-h-139.75 lg:pt-4' : 'lg:col-span-2 lg:mx-auto lg:w-full lg:max-w-3xl lg:py-4'; ?>">
			<?php if (! empty($alert['heading'])) : ?>
				<p class="pr-10 font-accent text-xl leading-normal text-accent-2 uppercase sm:text-2xl lg:text-[25px]">
					<?php echo esc_html($alert['heading']); ?>
				</p>
			<?php endif; ?>

			<h2 id="<?php echo esc_attr($title_id); ?>"
				class="mt-4 font-heading text-2xl leading-[1.2] font-bold text-primary-1 sm:text-3xl lg:mt-7 lg:ml-12 lg:max-w-139.5 lg:text-[30px] lg:leading-[1.484]">
				<?php echo esc_html($alert['subheading']); ?>
			</h2>

			<?php if (! empty($alert['text'])) : ?>
				<div
					class="mt-6 text-base leading-normal text-black [&_p]:mb-5 [&_p:last-child]:mb-0 sm:text-lg lg:mt-7.5 lg:text-xl">
					<?php echo wp_kses_post(wpautop($alert['text'])); ?>
				</div>
			<?php endif; ?>

			<div class="mt-8 flex flex-wrap justify-end gap-7.5 lg:mt-auto">
				<?php foreach ($alert['links'] as $alert_link) : ?>
					<?php echo saop_render_button($alert_link); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped by helper. 
					?>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</dialog>
