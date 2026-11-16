<?php

/**
 * Featured links block template.
 *
 * @package St._Anthony_of_Padua
 */

$section_heading = get_field('section_heading');
$inner_heading   = get_field('inner_heading');
$text            = get_field('text');
$background      = get_field('background_image');
$instance_id     = wp_unique_id('saop-featured-links-');

if (function_exists('saop_enqueue_featured_links_assets')) {
	saop_enqueue_featured_links_assets();
}

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'saop-section saop-featured-links not-prose alignfull mt-30 overflow-x-clip',
	)
);

$background_style = '';

if (is_array($background) && ! empty($background['url'])) {
	$background_style = sprintf(
		'background-image: url("%s");',
		esc_url($background['url'])
	);
}
?>

<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?> data-saop-section data-featured-links>
	<?php if ($section_heading) : ?>
		<h2
			class="mt-0 mb-6 text-center font-heading text-[28px] font-normal text-primary-1 md:text-[38px] lg:mb-7 lg:text-5xl">
			<?php echo esc_html($section_heading); ?>
		</h2>
	<?php endif; ?>

	<div class="relative bg-cover bg-center bg-no-repeat shadow-featured-links md:min-h-131.75 lg:flex lg:min-h-0 lg:flex-row lg:justify-end"
		<?php if ($background_style) : ?> style="<?php echo esc_attr($background_style); ?>" <?php endif; ?>>
		<?php if (have_rows('links')) : ?>
			<div class="relative z-10 ml-26 md:ml-auto md:w-[43%] lg:ml-0 lg:w-auto">
				<ul>
					<?php
					$link_index = 0;

					while (have_rows('links')) :
						the_row();

						$link = get_sub_field('link');

						if (
							! is_array($link) ||
							empty($link['url']) ||
							empty($link['title'])
						) {
							continue;
						}

						$icon       = get_sub_field('icon');
						$excerpt    = get_sub_field('excerpt');
						$target     = ! empty($link['target']) ? $link['target'] : '_self';
						$rel        = '_blank' === $target ? 'noopener noreferrer' : '';
						$toggle_id  = $instance_id . '-toggle-' . $link_index;
						$panel_id   = $instance_id . '-panel-' . $link_index;
						$link_index++;
					?>
						<li class="group relative h-24.75 border-primary-2 not-last:border-b-2 md:h-32.75 lg:h-auto lg:py-8 lg:pr-30 lg:pl-7 lg:transition-all lg:duration-500"
							data-featured-link-item data-featured-link-expanded="false">
							<button
								class="relative flex h-full w-full cursor-pointer items-center gap-7.5 bg-transparent pl-7 text-left focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-primary-1 lg:hidden"
								type="button" id="<?php echo esc_attr($toggle_id); ?>" aria-expanded="false"
								aria-controls="<?php echo esc_attr($panel_id); ?>" data-featured-link-toggle>
								<span
									class="flex size-12.5 shrink-0 items-center justify-center rounded-full bg-primary-2 text-2xl text-white md:size-16.5 md:text-4-5xl"
									aria-hidden="true">
									<?php echo wp_kses_post($icon); ?>
								</span>
								<span class="font-heading text-xl font-bold text-primary-1 uppercase md:text-2xl">
									<?php echo esc_html($link['title']); ?>
								</span>
							</button>

							<div class="pointer-events-none absolute inset-y-0 right-0 z-10 flex w-[calc(100vw-3px)] translate-x-full items-center gap-3 overflow-hidden rounded-l-full bg-primary-2 px-2.5 opacity-0 shadow-md transition-[transform,opacity] duration-300 ease-out motion-reduce:transition-none md:w-[83.333vw] md:gap-6 md:px-7 lg:hidden"
								id="<?php echo esc_attr($panel_id); ?>" role="region" aria-hidden="true"
								aria-labelledby="<?php echo esc_attr($toggle_id); ?>" data-featured-link-panel>
								<span
									class="flex size-12.5 shrink-0 items-center justify-center rounded-full bg-white text-2xl text-primary-2 md:size-16.5 md:text-4-5xl"
									aria-hidden="true">
									<?php echo wp_kses_post($icon); ?>
								</span>

								<span class="min-w-0 flex-1 self-stretch py-2.5 md:py-5">
									<a class="inline-flex items-center gap-2.5 font-heading text-xl leading-normal font-bold text-primary-1 uppercase focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-1 md:text-2xl"
										href="<?php echo esc_url($link['url']); ?>" target="<?php echo esc_attr($target); ?>"
										rel="<?php echo esc_attr($rel); ?>" tabindex="-1" data-featured-link-destination>
										<?php echo esc_html($link['title']); ?>
										<img class="size-6 shrink-0"
											src="<?php echo esc_url(get_theme_file_uri('/assets/img/arrow-up-right.svg')); ?>"
											alt="" aria-hidden="true" />
									</a>

									<?php if ($excerpt) : ?>
										<span
											class="mt-0.5 line-clamp-3 block text-sm leading-none text-primary-1 md:mt-1 md:text-[15px] md:leading-relaxed">
											<?php echo wp_kses_post($excerpt); ?>
										</span>
									<?php endif; ?>
								</span>
							</div>

							<a class="hidden flex-row items-center gap-7.5 py-5 lg:flex"
								href="<?php echo esc_url($link['url']); ?>" target="<?php echo esc_attr($target); ?>"
								rel="<?php echo esc_attr($rel); ?>">
								<span class="rounded-full bg-primary-2 p-3 text-2xl text-white" aria-hidden="true">
									<?php echo wp_kses_post($icon); ?>
								</span>

								<h3 class="font-heading font-bold text-black uppercase text-xl md:text-2xl">
									<?php echo esc_html($link['title']); ?>
								</h3>

								<span
									class="absolute inset-y-0 right-0 flex w-0 items-center gap-6 overflow-hidden rounded-l-full py-6 opacity-0 shadow-md transition-all duration-500 group-hover:w-[50vw] group-hover:bg-primary-2 group-hover:px-7 group-hover:opacity-100">
									<span class="shrink-0 rounded-full bg-white p-3 text-2xl text-primary-2" aria-hidden="true">
										<?php echo wp_kses_post($icon); ?>
									</span>

									<span class="min-w-0">
										<span
											class="mb-2.5 flex flex-row items-center gap-2.5 font-heading font-bold text-black uppercase">
											<?php echo esc_html($link['title']); ?>
											<img class="size-6 shrink-0"
												src="<?php echo esc_url(get_theme_file_uri('/assets/img/arrow-up-right.svg')); ?>"
												alt="" aria-hidden="true" />
										</span>

										<?php if ($excerpt) : ?>
											<span class="line-clamp-3 block pl-6 text-sm text-primary-1">
												<?php echo wp_kses_post($excerpt); ?>
											</span>
										<?php endif; ?>
									</span>
								</span>
							</a>
						</li>
					<?php endwhile; ?>
				</ul>
			</div>
		<?php endif; ?>

		<?php if ($inner_heading || $text) : ?>
			<div
				class="relative z-10 flex flex-col gap-2.5 px-6 pt-11 pb-2 md:absolute md:bottom-5 md:left-6 md:w-87 md:p-0 lg:bottom-5 lg:left-9 lg:w-auto lg:max-w-lg lg:gap-5">
				<?php if ($inner_heading) : ?>
					<h3 class="font-heading text-[28px] text-primary-1 lg:text-5xl">
						<?php echo esc_html($inner_heading); ?>
					</h3>
				<?php endif; ?>

				<?php if ($text) : ?>
					<div class="pl-4 text-lg leading-normal text-primary-1 lg:pl-8 lg:text-xl [&_p]:m-0">
						<?php echo wp_kses_post(wpautop($text)); ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>