<?php

/**
 * Template Name: Mass Times
 * Template Post Type: page
 *
 * @package St._Anthony_of_Padua
 */

get_header();

$saop_format_mass_time = static function ($value) {
    $value = trim((string) $value);

    if ('' === $value) {
        return '';
    }

    $formats = array('H:i:s', 'H:i', 'G:i', 'g:i a', 'g:i A', 'h:i a', 'h:i A');

    foreach ($formats as $format) {
        $date   = DateTimeImmutable::createFromFormat('!' . $format, $value);
        $errors = DateTimeImmutable::getLastErrors();

        if (
            $date
            && (
                false === $errors
                || (0 === $errors['warning_count'] && 0 === $errors['error_count'])
            )
        ) {
            return strtolower($date->format('g:i a'));
        }
    }

    $timestamp = strtotime($value);

    return false === $timestamp ? $value : strtolower(gmdate('g:i a', $timestamp));
};

while (have_posts()) :
    the_post();

    $saop_background_image = saop_has_acf() ? get_field('background_image') : null;
    $saop_background_url   = '';

    if (is_array($saop_background_image)) {
        if (! empty($saop_background_image['ID'])) {
            $saop_background_url = wp_get_attachment_image_url((int) $saop_background_image['ID'], 'full');
        } elseif (! empty($saop_background_image['url'])) {
            $saop_background_url = $saop_background_image['url'];
        }
    } elseif (is_numeric($saop_background_image)) {
        $saop_background_url = wp_get_attachment_image_url((int) $saop_background_image, 'full');
    } elseif (is_string($saop_background_image)) {
        $saop_background_url = $saop_background_image;
    }

    $saop_background_style = $saop_background_url
        ? sprintf(
            'background-image: url("%s"); background-size: 120rem auto;',
            esc_url($saop_background_url)
        )
        : '';
?>
<main id="primary" class="not-prose">
    <?php get_template_part('template-parts/layout/page', 'header'); ?>

    <div class="relative overflow-hidden bg-white">
        <?php if ($saop_background_style) : ?>
        <div class="absolute inset-0 bg-top bg-repeat-y opacity-40"
            style="<?php echo esc_attr($saop_background_style); ?>" aria-hidden="true"></div>
        <?php endif; ?>

        <div class="relative mx-auto max-w-wide px-6 py-14 md:px-12 md:py-16">
            <?php if (saop_has_acf() && have_rows('sections', 'option')) : ?>
            <div class="grid items-start gap-x-15 gap-y-16 md:grid-cols-2 lg:grid-cols-3">
                <?php while (have_rows('sections', 'option')) : ?>
                <?php
                            the_row();

                            $saop_heading      = trim((string) get_sub_field('heading'));
                            $saop_section_note = (string) get_sub_field('note');

                            if ('' === $saop_heading) {
                                continue;
                            }

                            $saop_heading_id = 'mass-times-section-' . get_row_index();
                            ?>
                <section class="self-start rounded-2lg border-3 border-accent-2 bg-white p-5 shadow-md"
                    aria-labelledby="<?php echo esc_attr($saop_heading_id); ?>">
                    <h2 id="<?php echo esc_attr($saop_heading_id); ?>"
                        class="font-heading text-2xl leading-normal font-normal text-primary-2">
                        <?php echo esc_html($saop_heading); ?>
                    </h2>

                    <?php if (have_rows('times_days')) : ?>
                    <dl class="mt-3 space-y-3 text-sm leading-normal text-black">
                        <?php while (have_rows('times_days')) : ?>
                        <?php
                                            the_row();

                                            $saop_day           = trim((string) get_sub_field('day'));
                                            $saop_display_times = array();

                                            if (have_rows('times')) {
                                                while (have_rows('times')) {
                                                    the_row();

                                                    $saop_start_time = $saop_format_mass_time(get_sub_field('time'));

                                                    if ('' === $saop_start_time) {
                                                        continue;
                                                    }

                                                    $saop_end_time   = $saop_format_mass_time(get_sub_field('end_time'));
                                                    $saop_time_note  = trim((string) get_sub_field('note'));
                                                    $saop_time_label = $saop_start_time;

                                                    if ($saop_end_time) {
                                                        $saop_time_label .= ' - ' . $saop_end_time;
                                                    }

                                                    if ($saop_time_note) {
                                                        $saop_time_label .= ' ' . $saop_time_note;
                                                    }

                                                    $saop_display_times[] = $saop_time_label;
                                                }
                                            }

                                            if ('' === $saop_day || ! $saop_display_times) {
                                                continue;
                                            }
                                            ?>
                        <div class="grid gap-y-1 sm:grid-cols-[max-content_minmax(0,1fr)] sm:gap-x-4">
                            <dt><?php echo esc_html(rtrim($saop_day, " \t\n\r\0\x0B:") . ':'); ?></dt>
                            <dd class="min-w-0"><?php echo esc_html(implode(', ', $saop_display_times)); ?></dd>
                        </div>
                        <?php endwhile; ?>
                    </dl>
                    <?php endif; ?>

                    <?php if ('' !== trim(wp_strip_all_tags($saop_section_note))) : ?>
                    <div class="mt-3 text-sm leading-[1.484] text-black [&_a]:underline [&_p]:m-0 [&_p+p]:mt-3">
                        <?php echo wp_kses_post($saop_section_note); ?>
                    </div>
                    <?php endif; ?>
                </section>
                <?php endwhile; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</main>
<?php
endwhile;

get_footer();