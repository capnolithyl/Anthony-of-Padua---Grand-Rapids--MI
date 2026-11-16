<?php

/**
 * Hero block template.
 *
 * @package St._Anthony_of_Padua
 */

$media_type            = get_field('media_type') ? 'video' : 'slider';
$featured_link         = get_field('featured_link');
$featured_link_heading = get_field('featured_link_heading');
$featured_link_target  = is_array($featured_link) && ! empty($featured_link['target'])
	? $featured_link['target']
	: '_self';
$show_featured_link    = get_field('show_featured_link')
	&& is_array($featured_link)
	&& ! empty($featured_link['url'])
	&& ! empty($featured_link['title']);
$wrapper_attributes    = get_block_wrapper_attributes(
    array(
        'class' => 'saop-section saop-hero not-prose alignfull relative mb-45 after:content-[""] after:absolute after:right-0 after:w-90 after:bottom-0 after:h-35 after:bg-secondary-1 after:translate-y-full after:-z-10 after:rounded-bl-3xl',
    )
);
?>

<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            ?> data-saop-section data-saop-hero>

    <?php get_template_part("blocks/hero/$media_type"); ?>

    <?php if ($show_featured_link) : ?>
        <div
            class="absolute right-0 bottom-3 left-0 h-[116px] translate-y-full md:flex md:h-auto md:flex-row md:bg-secondary-2 md:transition-colors md:has-[.apply-cta:hover]:bg-secondary-3">

            <div
                class="absolute top-0 left-0 z-10 flex h-[69px] w-[calc(100%_-_21px)] items-center rounded-r-[50px] bg-accent-3 pl-5 font-sans text-xl leading-normal font-medium whitespace-nowrap text-secondary-1 uppercase shadow-md md:static md:-my-2 md:h-auto md:w-auto md:flex-1 md:justify-end md:rounded-r-full md:py-6 md:pr-11 md:pl-0 md:text-2xl md:text-white">
                <?php echo esc_html($featured_link_heading); ?>
            </div>

            <a class="apply-cta absolute right-0 bottom-0 left-0 flex h-[54px] items-center justify-end bg-secondary-2 pr-[15px] font-heading text-xl leading-normal font-bold whitespace-nowrap text-primary-1 uppercase transition-colors hover:bg-secondary-3 hover:text-white focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-primary-1 md:static md:h-auto md:justify-start md:bg-transparent md:px-10 md:py-4 md:font-sans md:text-2xl md:hover:bg-transparent"
                href="<?php echo esc_url($featured_link['url']); ?>"
                target="<?php echo esc_attr($featured_link_target); ?>"
                rel="<?php echo esc_attr('_blank' === $featured_link_target ? 'noopener noreferrer' : ''); ?>">
                <?php echo esc_html($featured_link['title']); ?>
            </a>
        </div>
    <?php endif; ?>
</section>
