<?php

/**
 * Alternative image buttons block template.
 *
 * @package St._Anthony_of_Padua
 */

$heading = get_field('heading');
$text    = get_field('text');
$rows    = get_field('image_buttons');

if (! $heading || ! $text || ! is_array($rows)) {
	return;
}

$image_buttons = array();

foreach (array_slice($rows, 0, 4) as $row) {
	$button_link = isset($row['link']) && is_array($row['link']) ? $row['link'] : array();
	$image       = $row['image'] ?? null;

	if (is_array($image)) {
		$image_id = absint($image['ID'] ?? $image['id'] ?? 0);
	} else {
		$image_id = absint($image);
	}

	if (! $image_id || empty($button_link['url']) || empty($button_link['title'])) {
		continue;
	}

	$image_buttons[] = array(
		'image_id' => $image_id,
		'link'     => $button_link,
	);
}

if (empty($image_buttons)) {
	return;
}

$button_count = count($image_buttons);
$heading_id   = wp_unique_id('saop-image-buttons-alt-heading-');
$is_editor    = ! empty($is_preview);
$grid_layout  = 1 === $button_count
	? 'md:max-w-86.75 md:grid-cols-1 lg:max-w-136.25'
	: 'md:max-w-175.75 md:grid-cols-2 lg:max-w-276.25';
$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'saop-section saop-image-buttons-alt not-prose alignfull my-20 px-4 md:my-24 md:px-10 lg:my-30 lg:px-0',
	)
);
?>

<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?> aria-labelledby="<?php echo esc_attr($heading_id); ?>" data-saop-section>
    <div class="relative mx-auto max-w-328.25">
        <div class="pointer-events-none absolute inset-x-0 top-0 bottom-15 rounded-2lg border-3 border-accent-2 md:bottom-12.25 lg:bottom-31.25"
            aria-hidden="true"></div>

        <div class="relative z-10 flex flex-col items-center pt-6">
            <h2 id="<?php echo esc_attr($heading_id); ?>"
                class="m-0 px-6 text-center font-heading text-4xl leading-tight font-normal text-primary-1 md:text-[2.375rem] lg:text-5xl">
                <?php echo esc_html($heading); ?>
            </h2>

            <div
                class="mt-5 max-w-269.25 px-6 text-center text-lg leading-tight text-black md:max-w-148 md:px-0 md:leading-[1.375] lg:max-w-269.25 lg:px-6 lg:text-xl lg:leading-tight [&_p]:m-0">
                <?php echo wp_kses_post(wpautop($text)); ?>
            </div>

            <ul
                class="mt-9 grid w-full max-w-276.25 grid-cols-1 gap-4 px-4 md:gap-x-2.5 md:gap-y-3.25 md:px-0 lg:gap-4 <?php echo esc_attr($grid_layout); ?>">
                <?php foreach ($image_buttons as $index => $image_button) : ?>
                <?php
					$button_link = $image_button['link'];
					$target      = ! empty($button_link['target']) ? $button_link['target'] : '_self';
					$rel         = '_blank' === $target ? 'noopener noreferrer' : '';
					$center_last = 3 === $button_count && 2 === $index
						? 'md:col-span-2 md:mx-auto md:max-w-86.75 lg:max-w-136.25'
						: '';
					?>

                <li class="w-full <?php echo esc_attr($center_last); ?>">
                    <?php if (! $is_editor) : ?>
                    <a class="group relative block h-51.25 w-full overflow-hidden rounded-2lg bg-secondary-3 shadow-md focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary-2 md:h-43.75 lg:h-51.25"
                        href="<?php echo esc_url($button_link['url']); ?>" target="<?php echo esc_attr($target); ?>"
                        <?php if ($rel) : ?> rel="<?php echo esc_attr($rel); ?>" <?php endif; ?>>
                        <?php else : ?>
                        <div
                            class="group relative block h-51.25 w-full overflow-hidden rounded-2lg bg-secondary-3 shadow-md md:h-43.75 lg:h-51.25">
                            <?php endif; ?>

                            <?php
								echo wp_get_attachment_image(
									$image_button['image_id'],
									'large',
									false,
									array(
										'alt'   => '',
										'class' => 'h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-105',
									)
								);
								?>

                            <span
                                class="pointer-events-none absolute inset-0 bg-linear-to-t from-black/75 via-black/15 to-transparent"
                                aria-hidden="true"></span>
                            <span
                                class="absolute inset-x-0 bottom-0 px-5 pt-12 pb-4 font-heading text-2xl leading-tight font-bold text-white uppercase md:px-9">
                                <?php echo esc_html($button_link['title']); ?>
                            </span>

                            <?php if (! $is_editor) : ?>
                    </a>
                    <?php else : ?>
        </div>
        <?php endif; ?>
        </li>
        <?php endforeach; ?>
        </ul>
    </div>
    </div>
</section>