<?php

/**
 * Template Name: Contact Page
 * Template Post Type: page
 *
 * @package St._Anthony_of_Padua
 */

get_header();

while (have_posts()) :
	the_post();

	$saop_heading             = saop_has_acf() ? trim((string) get_field('heading')) : '';
	$saop_form_heading        = saop_has_acf() ? trim((string) get_field('form_heading')) : '';
	$saop_background_logo     = saop_has_acf() ? get_field('background_logo') : null;
	$saop_contact_form        = saop_has_acf() ? get_field('contact_form') : null;
	$saop_google_maps_iframe  = saop_has_acf() ? (string) get_field('google_maps_iframe') : '';
	$saop_background_logo_id  = 0;
	$saop_contact_form_id     = 0;
	$saop_google_maps_src     = '';
	$saop_address             = saop_get_theme_setting('address', '');
	$saop_address_text        = str_ireplace(array('<br>', '<br/>', '<br />', '</p>'), ' ', (string) $saop_address);
	$saop_address_label       = trim((string) preg_replace('/\s+/', ' ', wp_strip_all_tags($saop_address_text)));
	$saop_phone               = trim((string) saop_get_theme_setting('phone_number', ''));
	$saop_fax                 = trim((string) saop_get_theme_setting('fax', ''));
	$saop_email               = sanitize_email((string) saop_get_theme_setting('email', ''));
	$saop_phone_href          = preg_replace('/[^0-9+]/', '', $saop_phone);
	$saop_social_links        = saop_get_social_links();
	$saop_contact_social_items = array();

	if ('' === $saop_form_heading) {
		$saop_form_heading = get_the_title();
	}

	if (is_array($saop_background_logo)) {
		$saop_background_logo_id = absint(
			$saop_background_logo['ID'] ?? $saop_background_logo['id'] ?? 0
		);
	} elseif (is_numeric($saop_background_logo)) {
		$saop_background_logo_id = absint($saop_background_logo);
	}

	if (is_array($saop_contact_form)) {
		$saop_contact_form_id = absint(
			$saop_contact_form['id'] ?? $saop_contact_form['ID'] ?? $saop_contact_form['form_id'] ?? 0
		);

		if (! $saop_contact_form_id) {
			$saop_first_contact_form = reset($saop_contact_form);

			if (is_numeric($saop_first_contact_form)) {
				$saop_contact_form_id = absint($saop_first_contact_form);
			} elseif (is_array($saop_first_contact_form)) {
				$saop_contact_form_id = absint(
					$saop_first_contact_form['id'] ?? $saop_first_contact_form['ID'] ?? $saop_first_contact_form['form_id'] ?? 0
				);
			}
		}
	} elseif (is_object($saop_contact_form)) {
		$saop_contact_form_id = absint(
			$saop_contact_form->id ?? $saop_contact_form->ID ?? $saop_contact_form->form_id ?? 0
		);
	} elseif (is_numeric($saop_contact_form)) {
		$saop_contact_form_id = absint($saop_contact_form);
	}

	if (
		$saop_google_maps_iframe
		&& preg_match('~<iframe\b[^>]*\bsrc=(["\'])(.*?)\1~i', $saop_google_maps_iframe, $saop_map_matches)
	) {
		$saop_candidate_map_src = esc_url_raw(html_entity_decode($saop_map_matches[2], ENT_QUOTES, 'UTF-8'));
		$saop_candidate_host    = strtolower((string) wp_parse_url($saop_candidate_map_src, PHP_URL_HOST));
		$saop_is_google_host    = 'google.com' === $saop_candidate_host
			|| str_ends_with($saop_candidate_host, '.google.com');

		if ($saop_is_google_host) {
			$saop_google_maps_src = $saop_candidate_map_src;
		}
	}

	foreach ($saop_social_links as $saop_social_link) {
		$saop_social_icon   = $saop_social_link['icon'] ?? '';
		$saop_social_value  = $saop_social_link['link'] ?? '';
		$saop_social_url    = '';
		$saop_social_label  = '';
		$saop_social_target = '_blank';

		if (is_array($saop_social_value)) {
			$saop_social_url    = $saop_social_value['url'] ?? $saop_social_value['link'] ?? '';
			$saop_social_label  = $saop_social_value['title'] ?? '';
			$saop_social_target = $saop_social_value['target'] ?? '_blank';
		} elseif (is_string($saop_social_value)) {
			$saop_social_url = $saop_social_value;
		}

		if (! $saop_social_icon || ! $saop_social_url) {
			continue;
		}

		if (! $saop_social_label) {
			$saop_icon_name = strtolower(wp_strip_all_tags((string) $saop_social_icon));

			if (str_contains($saop_icon_name, 'instagram')) {
				$saop_social_label = __('Instagram', 'saop');
			} elseif (str_contains($saop_icon_name, 'youtube')) {
				$saop_social_label = __('YouTube', 'saop');
			} elseif (str_contains($saop_icon_name, 'facebook')) {
				$saop_social_label = __('Facebook', 'saop');
			} else {
				$saop_social_label = __('Social media', 'saop');
			}
		}

		$saop_contact_social_items[] = array(
			'icon'   => $saop_social_icon,
			'url'    => $saop_social_url,
			'label'  => $saop_social_label,
			'target' => $saop_social_target,
		);
	}

	$saop_has_contact_card = $saop_phone || $saop_fax || $saop_email || $saop_contact_social_items;
?>

<main id="primary" class="saop-contact-page not-prose w-full overflow-hidden bg-white text-primary-1">
    <section class="relative w-full pb-[100px] md:pb-[120px] lg:pb-30"
        aria-labelledby="saop-contact-heading">
        <div class="relative w-full">
            <header
                class="<?php echo esc_attr($saop_has_contact_card ? 'pt-[270px] md:pt-[90px] lg:pt-20' : 'pt-12 md:pt-16 lg:pt-20'); ?> px-[29px] md:px-10 md:pr-[300px] lg:pr-80 lg:pl-[clamp(3rem,10vw,9.5rem)]">
                <?php if ($saop_heading) : ?>
                <p
                    class="font-accent text-[28px] leading-normal text-accent-2 uppercase md:text-[40px] lg:text-3xl">
                    <?php echo esc_html($saop_heading); ?>
                </p>
                <?php endif; ?>

                <h1 id="saop-contact-heading"
                    class="mt-2 ml-[94px] font-heading text-[32px] leading-tight font-normal text-primary-1 md:mt-3 md:text-[40px] lg:mt-5 lg:ml-10 lg:text-5xl">
                    <?php echo esc_html($saop_form_heading); ?>
                </h1>
            </header>

            <?php if ($saop_contact_form_id && function_exists('gravity_form')) : ?>
            <div
                class="relative mx-auto mt-5 w-[calc(100%-2.375rem)] max-w-[55.375rem] pb-[82px] md:mt-16 md:mr-0 md:ml-11 md:w-[calc(100%-6.1875rem)] md:max-w-none md:pb-[115px] lg:mx-auto lg:mt-10 lg:w-[calc(100%-3rem)] lg:max-w-[55.375rem] lg:pb-12">
                <div class="pointer-events-none absolute top-1/2 right-[54px] bottom-0 -left-[41px] rounded-2lg border-3 border-accent-2 md:right-[95px] md:-left-[26px] lg:top-1/3 lg:right-20 lg:-left-12"
                    aria-hidden="true"></div>

                <?php if ($saop_background_logo_id) : ?>
                <?php
							echo wp_get_attachment_image(
								$saop_background_logo_id,
								'large',
								false,
								array(
									'alt'         => '',
									'aria-hidden' => 'true',
									'class'       => 'pointer-events-none absolute top-10 left-0 z-0 h-auto w-[396px] -translate-x-[46.5%] object-contain opacity-15 md:top-[62px] md:-translate-x-[51%] lg:top-1/2 lg:w-96 lg:-translate-x-3/4 lg:-translate-y-1/2',
									'loading'     => 'lazy',
									'decoding'    => 'async',
								)
							);
							?>
                <?php endif; ?>

                <div
                    class="saop-contact-form relative z-10 min-h-[538px] rounded-2lg bg-secondary-1 px-[15px] py-[15px] shadow-md md:min-h-[502px] md:px-7 md:py-5 lg:min-h-0 lg:px-8 lg:py-7">
                    <?php
							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Trusted Gravity Forms output.
							echo gravity_form(
								$saop_contact_form_id,
								false,
								false,
								false,
								null,
								true,
								0,
								false
							);
							?>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($saop_has_contact_card) : ?>
            <aside
                class="absolute -top-2 right-0 min-h-[201px] w-[219px] rounded-bl-[20px] bg-secondary-1 px-5 pt-[35px] pb-5 md:-top-[83px] md:min-h-[279px] md:w-[258px] md:px-[37px] md:pt-[110px] lg:top-0 lg:min-h-0 lg:w-80 lg:px-9 lg:pt-8 lg:pb-8"
                aria-label="<?php esc_attr_e('Contact information', 'saop'); ?>">
                <ul class="space-y-[15px] text-[15px] leading-tight text-primary-1">
                    <?php if ($saop_phone) : ?>
                    <li class="flex items-center gap-6">
                        <i class="fas fa-phone w-5 shrink-0 text-center" aria-hidden="true"></i>
                        <a class="wrap-break-word transition-colors hover:text-primary-2 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-2"
                            href="<?php echo esc_url('tel:' . $saop_phone_href); ?>">
                            <?php echo esc_html($saop_phone); ?>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if ($saop_fax) : ?>
                    <li class="flex items-center gap-6">
                        <i class="fas fa-print w-5 shrink-0 text-center" aria-hidden="true"></i>
                        <span class="wrap-break-word"><?php echo esc_html($saop_fax); ?></span>
                    </li>
                    <?php endif; ?>

                    <?php if ($saop_email) : ?>
                    <li class="flex items-center gap-6">
                        <i class="fas fa-envelope w-5 shrink-0 text-center" aria-hidden="true"></i>
                        <a class="wrap-break-word transition-colors hover:text-primary-2 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-2"
                            href="<?php echo esc_url('mailto:' . $saop_email); ?>">
                            <?php echo esc_html(antispambot($saop_email)); ?>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>

                <?php if ($saop_contact_social_items) : ?>
                <ul class="mt-[19px] flex items-center gap-10 text-white lg:mt-7 lg:gap-7"
                    aria-label="<?php esc_attr_e('Social media', 'saop'); ?>">
                    <?php foreach ($saop_contact_social_items as $saop_social_item) : ?>
                    <li>
                        <a class="flex size-[34px] items-center justify-center rounded-full bg-primary-2 text-lg leading-none transition-colors hover:bg-primary-1 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-2"
                            href="<?php echo esc_url($saop_social_item['url']); ?>"
                            target="<?php echo esc_attr($saop_social_item['target']); ?>"
                            rel="<?php echo esc_attr('_blank' === $saop_social_item['target'] ? 'noopener noreferrer' : ''); ?>">
                            <span class="sr-only"><?php echo esc_html($saop_social_item['label']); ?></span>
                            <?php echo wp_kses_post($saop_social_item['icon']); ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </aside>
            <?php endif; ?>
        </div>

        <?php if ($saop_google_maps_src) : ?>
        <section
            class="relative mx-auto mt-[113px] w-[calc(100%-0.8125rem)] max-w-wide px-[15px] pb-[23px] md:mt-[105px] md:mr-0 md:ml-2.5 md:w-[calc(100%-1.9375rem)] md:max-w-none md:px-[17px] md:pb-[38px] lg:mx-auto lg:mt-28 lg:w-[calc(100%-3rem)] lg:max-w-wide lg:px-12 lg:pb-12"
            aria-labelledby="saop-contact-map-heading">
            <h2 id="saop-contact-map-heading" class="sr-only">
                <?php esc_html_e('Location', 'saop'); ?>
            </h2>

            <div class="pointer-events-none absolute top-[171px] right-0 bottom-0 left-0 rounded-2lg border-3 border-accent-2 md:top-[161px] lg:top-24"
                aria-hidden="true"></div>

            <?php if ($saop_address_label) : ?>
            <div
                class="relative z-10 -mb-6 ml-[27px] flex min-h-[92px] w-[calc(100%-3rem)] max-w-[296px] items-center gap-3 rounded-t-2lg bg-secondary-1 px-3 py-3 text-sm text-black md:-mb-5 md:ml-[30px] md:min-h-[75px] md:max-w-[484px] md:gap-4 md:px-3 lg:mb-0 lg:ml-12 lg:inline-flex lg:min-h-18 lg:w-auto lg:max-w-[calc(100%-2rem)] lg:px-7">
                <span
                    class="flex size-11 shrink-0 items-center justify-center rounded-full bg-primary-2 text-lg text-white"
                    aria-hidden="true">
                    <i class="fas fa-map-marker-alt"></i>
                </span>
                <address class="min-w-0 leading-snug not-italic">
                    <?php echo esc_html($saop_address_label); ?>
                </address>
            </div>
            <?php endif; ?>

            <div
                class="relative z-10 h-[324px] overflow-hidden rounded-2lg border-2 border-primary-2 bg-secondary-1 shadow-md md:h-[353px] lg:h-110">
                <iframe class="block h-full w-full border-0" src="<?php echo esc_url($saop_google_maps_src); ?>"
                    title="<?php esc_attr_e('St. Anthony of Padua location map', 'saop'); ?>" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
            </div>
        </section>
        <?php endif; ?>
    </section>
</main>
<?php
endwhile;

get_footer();
