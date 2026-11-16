<?php

/**
 * Welcome block template.
 *
 * @package St._Anthony_of_Padua
 */

$image            = get_field('image');
$eyebrow          = get_field('eyebrow');
$title            = get_field('title');
$text             = get_field('text');
$button_primary   = get_field('button_primary');
$button_secondary = get_field('button_secondary');
$reverse_layout   = (bool) get_field('reverse_layout');

$has_primary_button   = is_array($button_primary) && ! empty($button_primary['url']) && ! empty($button_primary['title']);
$has_secondary_button = is_array($button_secondary) && ! empty($button_secondary['url']) && ! empty($button_secondary['title']);

$media_desktop_column   = $reverse_layout ? 'lg:col-start-4' : 'lg:col-start-1';
$content_desktop_column = $reverse_layout ? 'lg:col-start-1' : 'lg:col-start-3';

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'saop-section saop-welcome not-prose alignwide relative overflow-hidden bg-secondary-1 px-0 py-7 md:py-10 lg:bg-white lg:py-28',
	)
);
?>

<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?> data-saop-section>
    <div class="absolute top-0 right-0 hidden h-24 w-1/4 rounded-bl-3xl bg-secondary-1 lg:block" aria-hidden="true">
    </div>

    <div
        class="relative z-10 mx-auto grid max-w-wide grid-cols-1 gap-y-10 px-6 md:grid-cols-2 md:gap-x-6 md:px-10 lg:grid-cols-5 lg:gap-x-12 lg:px-16 xl:px-24">
        <?php if (! empty($image['ID'])) : ?>
        <div
            class="<?php echo esc_attr($media_desktop_column); ?> relative order-1 mx-auto w-full max-w-sm pb-6 pl-4 md:order-0 md:col-start-1 md:row-span-2 md:row-start-2 lg:col-span-2 lg:row-span-3 lg:row-start-1 lg:max-w-lg lg:pb-10 lg:pl-8">
            <div class="absolute bottom-0 left-0 h-3/4 w-3/4 rounded-2lg border-4 border-accent-2" aria-hidden="true">
            </div>
            <?php
				echo wp_get_attachment_image(
					$image['ID'],
					'large',
					false,
					array(
						'class' => 'relative z-10 block h-auto w-full rounded-2lg object-cover shadow-md',
					)
				);
				?>
        </div>
        <?php endif; ?>

        <?php if ($eyebrow || $title) : ?>
        <div
            class="<?php echo esc_attr($content_desktop_column); ?> order-2 md:order-0 md:col-span-2 md:col-start-1 md:row-start-1 md:text-center lg:col-span-3 lg:row-start-1 lg:text-left">
            <?php if ($eyebrow) : ?>
            <p class="m-0 font-accent text-3xl leading-none text-accent-2 md:text-4xl xl:text-5xl">
                <?php echo esc_html($eyebrow); ?></p>
            <?php endif; ?>

            <?php if ($title) : ?>
            <h2
                class="mt-3 mb-0 pl-7 font-heading text-3xl leading-tight font-normal text-primary-1 md:pl-0 md:text-4xl lg:mt-5 xl:text-5xl">
                <?php echo esc_html($title); ?></h2>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php if ($text) : ?>
        <div
            class="<?php echo esc_attr($content_desktop_column); ?> order-3 space-y-5 text-lg leading-normal text-black md:order-0 md:col-start-2 md:row-start-2 lg:col-span-3 lg:row-start-2 xl:text-xl">
            <?php echo wp_kses_post($text); ?>
        </div>
        <?php endif; ?>

        <?php if ($has_primary_button || $has_secondary_button) : ?>
        <div
            class="<?php echo esc_attr($content_desktop_column); ?> order-4 flex flex-wrap items-center gap-8 md:order-0 md:col-start-2 md:row-start-3 lg:col-span-3 lg:row-start-3">
            <?php if ($has_primary_button) : ?>
            <?php echo wp_kses_post(saop_render_button($button_primary, 'outline', 'inline-flex w-42 items-center justify-center text-center')); ?>
            <?php endif; ?>

            <?php if ($has_secondary_button) : ?>
            <?php echo wp_kses_post(saop_render_button($button_secondary, 'outline', 'inline-flex w-30 items-center justify-center text-center')); ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
