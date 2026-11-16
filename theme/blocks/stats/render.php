<?php

/**
 * Stats block template.
 *
 * @package St._Anthony_of_Padua
 */

$stats = get_field('stats');

if (empty($stats)) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'saop-section saop-stats not-prose alignfull my-50 h-76.5 bg-primary-1 py-0 text-white md:my-40 md:h-34.5 lg:my-30 lg:h-49.75',
	)
);
?>

<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?> data-saop-section>
	<div
		class="container mx-auto flex -translate-y-8.5 flex-col justify-between gap-7 pr-6 pl-7 md:h-full md:max-w-none md:translate-y-0 md:flex-row md:gap-0 md:px-7.5 lg:max-w-7xl lg:px-0 lg:pl-16">
		<?php foreach ($stats as $stat) : ?>
			<article
				class="flex h-26 flex-row items-start gap-5.5 nth-2:flex-row-reverse md:-translate-y-9 md:gap-4 md:nth-2:flex-row lg:gap-5.5 first:[&_div]:bg-accent-2 nth-2:[&_div]:bg-secondary-3 nth-3:[&_div]:bg-accent-3 nth-2:[&_p]:mt-8.75 nth-2:[&_p]:text-right md:nth-2:[&_p]:mt-11 md:nth-2:[&_p]:text-left">
				<div
					class="flex aspect-square h-26 w-26 shrink-0 items-center justify-center rounded-full border-4 border-white font-accent text-4-5xl uppercase lg:h-43.5 lg:w-43.5 lg:text-6xl">
					<?php echo esc_html($stat['value']); ?>
				</div>

				<p class="mt-10.5 max-w-none text-lg leading-[normal] uppercase md:mt-11 md:max-w-42 lg:mt-13">
					<?php echo esc_html($stat['label']); ?>
				</p>
			</article>
		<?php endforeach; ?>
	</div>
</section>
