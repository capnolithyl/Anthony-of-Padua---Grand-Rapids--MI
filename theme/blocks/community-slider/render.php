<?php

/**
 * Community slider block template.
 *
 * @package St._Anthony_of_Padua
 */

$heading    = get_field('heading');
$text       = get_field('text');
$categories = array_filter(
	array_map('get_term', (array) get_field('categories')),
	static function ($term) {
		return $term instanceof WP_Term;
	}
);

if (empty($categories)) {
	return;
}

$instance_id       = wp_unique_id('saop-community-');
$has_multiple_tabs = count($categories) > 1;
$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'saop-section saop-community not-prose alignfull relative my-20 py-4 lg:my-30 lg:py-6',
	)
);
?>

<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?> data-saop-section data-saop-community>
	<div
		class="relative mx-0 mt-7 rounded-2lg border-3 border-accent-2 pb-8 md:mx-7 md:mt-10 md:pb-10 lg:mx-25 lg:pb-10">
		<div class="relative z-10 flex flex-col gap-6 md:flex-row md:items-center md:gap-7 md:pt-4 lg:gap-11">
			<?php if ($heading) : ?>
				<div
					class="-mt-7 -ml-0.75 flex h-14 w-58.75 shrink-0 items-center overflow-hidden rounded-r-[30px] bg-primary-2 px-5 shadow-md md:mt-0 md:-ml-8 md:h-17.5 md:w-72 md:px-3.5 lg:-ml-25 lg:h-20 lg:w-96.75 lg:px-8">
					<h2 class="font-heading text-3xl leading-tight text-primary-1 md:text-4-5xl lg:text-5xl">
						<?php echo esc_html($heading); ?>
					</h2>
				</div>
			<?php endif; ?>

			<?php if ($text) : ?>
				<div class="px-6 text-lg leading-normal text-black md:flex-1 md:px-0 lg:max-w-232 lg:text-xl [&_p]:m-0">
					<?php echo wp_kses_post(wpautop($text)); ?>
				</div>
			<?php endif; ?>
		</div>

		<?php if ($has_multiple_tabs) : ?>
			<div class="mx-2.5 mt-10 flex flex-nowrap items-end justify-evenly gap-5 overflow-x-auto overflow-y-hidden border-b border-primary-1 px-1 md:mx-11 md:mt-16 md:justify-between md:gap-0 md:px-6 lg:mx-16 lg:mt-14 lg:justify-evenly lg:gap-10 lg:px-1"
				role="tablist" aria-label="<?php esc_attr_e('Community categories', 'saop'); ?>">
				<?php foreach ($categories as $index => $term) : ?>
					<?php
					$is_active = 0 === $index;
					$tab_id    = $instance_id . '-tab-' . $term->term_id;
					$panel_id  = $instance_id . '-panel-' . $term->term_id;
					?>
					<button
						class="relative shrink-0 cursor-pointer bg-transparent px-2 pb-3 font-heading text-xl leading-none font-bold text-primary-1 uppercase transition-colors after:absolute after:inset-x-0 after:-bottom-px after:h-1 after:rounded-full after:bg-transparent after:content-[''] hover:text-primary-2 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-2 aria-selected:text-primary-2 aria-selected:after:bg-primary-2 md:px-6 md:pb-5 md:leading-normal"
						type="button" id="<?php echo esc_attr($tab_id); ?>" role="tab" data-community-tab
						aria-controls="<?php echo esc_attr($panel_id); ?>"
						aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
						tabindex="<?php echo $is_active ? '0' : '-1'; ?>">
						<?php echo esc_html($term->name); ?>
					</button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<div class="mt-10 md:mt-7 lg:mt-10">
			<?php foreach ($categories as $index => $term) : ?>
				<?php
				$is_active = 0 === $index;
				$tab_id    = $instance_id . '-tab-' . $term->term_id;
				$panel_id  = $instance_id . '-panel-' . $term->term_id;
				$posts     = get_posts(
					array(
						'post_type'           => 'post',
						'post_status'         => 'publish',
						'posts_per_page'      => 5,
						'ignore_sticky_posts' => true,
						'category__in'        => array($term->term_id),
					)
				);
				?>

				<div id="<?php echo esc_attr($panel_id); ?>" data-community-panel <?php if ($has_multiple_tabs) : ?>
					role="tabpanel" aria-labelledby="<?php echo esc_attr($tab_id); ?>" <?php endif; ?>
					<?php echo $is_active ? '' : ' hidden'; ?>>
					<?php if ($posts) : ?>
						<?php // A literal `>` in this Tailwind selector is corrupted by WordPress wptexturize.
						?>
						<div class="h-58 md:-mx-8 md:h-73 lg:-mx-25 [&_.slick-center_a]:h-58 md:[&_.slick-center_a]:h-73"
							data-community-track>
							<?php foreach ($posts as $community_post) : ?>
								<article class="h-58 px-1.5 md:h-73">
									<div class="flex h-full items-center">
										<a class="group relative block h-50.5 w-full overflow-hidden rounded-2lg bg-secondary-3 shadow-md transition-[height] duration-500 md:h-73"
											href="<?php echo esc_url(get_permalink($community_post)); ?>">
											<?php if (has_post_thumbnail($community_post)) : ?>
												<?php
												echo get_the_post_thumbnail(
													$community_post,
													'large',
													array(
														'class' => 'h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-105',
													)
												);
												?>
											<?php endif; ?>

											<div class="absolute inset-0 bg-linear-to-t from-black/80 via-black/20 to-transparent"
												aria-hidden="true"></div>
											<div class="absolute inset-x-0 bottom-0 px-3.5 pt-12 pb-2.5 md:px-6 md:pb-4">
												<h3 class="font-heading text-xl leading-tight text-white md:text-2xl">
													<?php echo esc_html(get_the_title($community_post)); ?>
												</h3>
												<span
													class="mt-1 inline-block text-sm font-bold text-secondary-2 uppercase underline decoration-1 underline-offset-2">
													<?php esc_html_e('Read more', 'saop'); ?>
												</span>
											</div>
										</a>
									</div>
								</article>
							<?php endforeach; ?>
						</div>

						<div class="mt-8 flex min-h-1 items-center justify-center md:mt-11 lg:mt-12 [&_.slick-active_button]:w-34 [&_.slick-active_button]:bg-primary-2"
							data-community-dots></div>
					<?php else : ?>
						<p class="px-6 text-center text-lg text-primary-1">
							<?php esc_html_e('No community posts are available yet.', 'saop'); ?>
						</p>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
