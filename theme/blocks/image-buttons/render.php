<?php

/**
 * Image buttons block template.
 *
 * @package St._Anthony_of_Padua
 */

$cards = get_field('buttons');

if (!have_rows("buttons") || empty($cards)) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'saop-section saop-image-buttons not-prose alignwide mb-30 mt-30 pb-2',
	)
);

?>

<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?> data-saop-section>
	<div
		class="relative mb-15 flex flex-col gap-9 after:absolute after:top-15 after:-right-10 after:-left-10 after:-z-10 after:h-full after:rounded-2lg after:border-4 after:border-accent-2 after:content-[''] md:grid md:grid-cols-3">
		<?php while (have_rows("buttons")) : the_row(); ?>
			<?php
			$link_url    = get_sub_field('link')['url'] ?? '';
			$link_target = ! empty(get_sub_field('link')['target']) ? get_sub_field('link')['target'] : '_self';
			$heading 	 = get_sub_field('heading') ?? '';
			$subheading  = get_sub_field('subheading') ?? '';

			$is_editor = !empty($is_preview);
			$has_link  = !empty($link_url) && !$is_editor;
			?>

			<article class="relative">
				<?php if ($has_link) : ?>
					<a class="group" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
					<?php endif; ?>

					<div
						class="relative after:absolute after:inset-0 after:rounded-2lg after:bg-black/30 after:shadow-md after:content-['']">
						<?php if (! empty(get_sub_field('image')['ID'])) : ?>
							<?php echo wp_get_attachment_image(get_sub_field('image')['ID'], 'large', false, array('class' => 'md:aspect-2/3 lg:aspect-4/3 object-cover rounded-2lg shadow-md max-h-30 md:max-h-none')); ?>
						<?php endif; ?>

						<div
							class="absolute top-1/2 z-1 flex w-full -translate-y-1/2 flex-col items-center px-2 text-center text-white xl:translate-y-0">
							<?php if (! empty($heading)) : ?>
								<div
									class="mb-3 text-xl font-bold uppercase transition-transform md:mb-4 md:text-2xl md:group-hover:-translate-y-4">
									<?php echo esc_html($heading); ?>
								</div>
							<?php endif; ?>


							<?php if (! empty($subheading)) : ?>

								<div
									class="mb-3 h-0.5 w-40 rounded-full bg-secondary-2 transition-all md:mb-4 md:group-hover:w-48">

								</div>

								<p
									class="line-clamp-2 text-lg font-semibold transition-transform md:line-clamp-none md:text-xl md:group-hover:translate-y-4">
									<?php echo esc_html($subheading); ?>
								</p>
							<?php endif; ?>
						</div>
					</div>


					<?php if ($has_link) : ?>
					</a>
				<?php endif; ?>
			</article>
		<?php endwhile; ?>
	</div>
</section>