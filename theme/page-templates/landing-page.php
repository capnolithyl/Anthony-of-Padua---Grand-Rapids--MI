<?php

/**
 * Template Name: Landing Page
 * Template Post Type: page
 *
 * @package St._Anthony_of_Padua
 */

get_header();

while (have_posts()) :
	the_post();

	$saop_landing_sections = array(
		saop_has_acf() ? get_field('landing_section_one') : array(),
		saop_has_acf() ? get_field('landing_section_two') : array(),
	);
?>

<main id="primary" class="not-prose grid min-h-[calc(100svh-var(--admin-bar-height))] bg-primary-1 lg:grid-cols-2">
    <h1 class="sr-only"><?php the_title(); ?></h1>

    <?php foreach ($saop_landing_sections as $saop_landing_index => $saop_landing_section) : ?>
    <?php
			$saop_landing_section = is_array($saop_landing_section) ? $saop_landing_section : array();
			$saop_landing_heading = trim((string) ($saop_landing_section['heading'] ?? ''));
			$saop_landing_text    = trim((string) ($saop_landing_section['subheading'] ?? ''));
			$saop_landing_link    = isset($saop_landing_section['link']) && is_array($saop_landing_section['link'])
				? $saop_landing_section['link']
				: array();
			$saop_landing_logo    = $saop_landing_section['logo'] ?? 0;
			$saop_landing_image   = $saop_landing_section['background_image'] ?? 0;
			$saop_landing_opacity = isset($saop_landing_section['overlay_opacity'])
				? absint($saop_landing_section['overlay_opacity'])
				: 70;
			$saop_landing_opacity = min(100, max(0, $saop_landing_opacity));

			$saop_landing_logo_id    = is_array($saop_landing_logo)
				? absint($saop_landing_logo['ID'] ?? $saop_landing_logo['id'] ?? 0)
				: absint($saop_landing_logo);
			$saop_landing_image_id   = is_array($saop_landing_image)
				? absint($saop_landing_image['ID'] ?? $saop_landing_image['id'] ?? 0)
				: absint($saop_landing_image);
			$saop_landing_heading_id = wp_unique_id('saop-landing-heading-');
			?>

    <section
        class="group relative isolate flex min-h-[calc(50svh-var(--admin-bar-height)/2)] overflow-hidden bg-primary-1 lg:min-h-[calc(100svh-var(--admin-bar-height))]"
        <?php if ($saop_landing_heading) : ?> aria-labelledby="<?php echo esc_attr($saop_landing_heading_id); ?>"
        <?php endif; ?>>
        <?php if ($saop_landing_image_id) : ?>
        <?php
					echo wp_get_attachment_image(
						$saop_landing_image_id,
						'full',
						false,
						array(
							'alt'           => '',
							'aria-hidden'   => 'true',
							'class'         => 'absolute inset-0 h-full w-full object-cover transition-transform duration-700 ease-out motion-safe:group-hover:scale-105',
							'decoding'      => 'async',
							'fetchpriority' => 0 === $saop_landing_index ? 'high' : 'auto',
							'loading'       => 'eager',
						)
					);
					?>
        <?php endif; ?>

        <div class="absolute inset-0 bg-primary-1"
            style="<?php echo esc_attr(sprintf('opacity: %d%%;', $saop_landing_opacity)); ?>" aria-hidden="true"></div>

        <div
            class="relative z-10 flex h-full w-full flex-col items-center justify-center px-6 py-12 text-center md:px-10 md:py-16 lg:px-12">
            <?php if ($saop_landing_logo_id) : ?>
            <?php
						echo wp_get_attachment_image(
							$saop_landing_logo_id,
							'full',
							false,
							array(
								'class'    => 'mb-7 h-auto max-h-28 w-auto max-w-full object-contain md:max-h-36',
								'decoding' => 'async',
								'loading'  => 'eager',
							)
						);
						?>
            <?php endif; ?>

            <?php if ($saop_landing_heading) : ?>
            <h2 id="<?php echo esc_attr($saop_landing_heading_id); ?>"
                class="m-0 max-w-2xl font-heading text-4xl leading-tight font-bold text-white text-shadow-md md:text-5xl">
                <?php echo esc_html($saop_landing_heading); ?>
            </h2>
            <?php endif; ?>

            <?php if ($saop_landing_text) : ?>
            <p class="mt-4 max-w-xl text-lg leading-relaxed whitespace-pre-line text-white md:text-xl">
                <?php echo esc_html($saop_landing_text); ?>
            </p>
            <?php endif; ?>

            <?php if (! empty($saop_landing_link['url']) && ! empty($saop_landing_link['title'])) : ?>
            <?php echo wp_kses_post(saop_render_button($saop_landing_link, 'primary', 'mt-8 inline-flex items-center justify-center text-center')); ?>
            <?php endif; ?>
        </div>
    </section>
    <?php endforeach; ?>
</main>

<?php
endwhile;

get_footer();