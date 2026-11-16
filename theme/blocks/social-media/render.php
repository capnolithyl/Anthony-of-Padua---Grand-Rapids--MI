<?php

/**
 * Social media block template.
 *
 * @package St._Anthony_of_Padua
 */

$heading             = get_field('heading');
$text                = get_field('text');
$instagram_shortcode = get_field('instagram_shortcode');
$social_links        = saop_get_social_links();
$social_items        = array();

foreach ($social_links as $social_link) {
    $icon       = $social_link['icon'] ?? '';
    $link_value = $social_link['link'] ?? '';
    $url        = '';
    $label      = '';
    $target     = '_blank';

    if (is_array($link_value)) {
        $url    = $link_value['url'] ?? $link_value['link'] ?? '';
        $label  = $link_value['title'] ?? '';
        $target = $link_value['target'] ?? '_blank';
    } elseif (is_string($link_value)) {
        $url = $link_value;
    }

    if (! $icon || ! $url) {
        continue;
    }

    if (! $label) {
        $icon_name = strtolower(wp_strip_all_tags($icon));

        if (str_contains($icon_name, 'instagram')) {
            $label = __('Instagram', 'saop');
        } elseif (str_contains($icon_name, 'youtube')) {
            $label = __('YouTube', 'saop');
        } elseif (str_contains($icon_name, 'facebook')) {
            $label = __('Facebook', 'saop');
        } else {
            $label = __('Social media', 'saop');
        }
    }

    $social_items[] = array(
        'icon'   => $icon,
        'url'    => $url,
        'label'  => $label,
        'target' => $target,
    );
}

if (! $heading && ! $text && ! $instagram_shortcode && ! $social_items) {
    return;
}

$wrapper_attributes = get_block_wrapper_attributes(
    array(
        'class' => 'saop-section saop-social-media not-prose alignfull relative my-20 py-4 lg:my-30 lg:py-6 overflow-hidden',
    )
);
?>

<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            ?> data-saop-section data-saop-social-media>
    <div
        class="relative mx-0 mt-7 rounded-2lg border-3 border-accent-2 pb-8 md:mx-7 md:mt-10 md:pb-10 lg:mx-25 lg:min-h-167.75 lg:pb-12">
        <div class="relative z-10 flex flex-col gap-6 md:flex-row md:items-center md:gap-7 md:pt-4 lg:gap-16">
            <?php if ($heading) : ?>
                <div
                    class="-mt-7 -ml-0.75 flex h-14 w-58.75 shrink-0 items-center overflow-hidden rounded-r-[30px] bg-primary-2 px-5 shadow-md md:mt-0 md:-ml-8 md:h-17.5 md:w-72 md:px-3.5 lg:-ml-25 lg:h-20 lg:w-84 lg:px-7.5">
                    <h2 class="font-heading text-3xl leading-tight text-primary-1 md:text-4-5xl lg:text-5xl">
                        <?php echo esc_html($heading); ?>
                    </h2>
                </div>
            <?php endif; ?>

            <?php if ($text) : ?>
                <div
                    class="px-6 text-lg leading-normal text-primary-1 md:flex-1 md:px-0 lg:max-w-243.5 lg:text-xl [&_p]:m-0">
                    <?php echo wp_kses_post(wpautop($text)); ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($social_items) : ?>
            <ul class="mt-8 flex items-center gap-10 pl-6 lg:mt-9 lg:pl-5"
                aria-label="<?php esc_attr_e('Social media', 'saop'); ?>">
                <?php foreach ($social_items as $social_item) : ?>
                    <li>
                        <a class="flex size-8.75 items-center justify-center text-[35px] leading-none text-primary-2 transition-colors hover:text-primary-1 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-2"
                            href="<?php echo esc_url($social_item['url']); ?>"
                            target="<?php echo esc_attr($social_item['target']); ?>"
                            rel="<?php echo esc_attr('_blank' === $social_item['target'] ? 'noopener noreferrer' : ''); ?>">
                            <span class="sr-only"><?php echo esc_html($social_item['label']); ?></span>
                            <?php echo wp_kses_post($social_item['icon']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <?php if ($instagram_shortcode) : ?>
            <div class="mt-10 lg:-mx-25 lg:mt-12.5" data-instagram-feed>
                <?php
                echo do_shortcode((string) $instagram_shortcode); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Trusted plugin shortcode output.
                ?>
            </div>
        <?php endif; ?>
    </div>
</section>