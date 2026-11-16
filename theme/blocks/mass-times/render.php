<?php

/**
 * Mass Times block template.
 *
 * @package St._Anthony_of_Padua
 */

$heading    = trim((string) get_field('heading'));
$subheading = trim((string) get_field('subheading'));
$image      = get_field('image');
$mass_times = get_field('mass_times');
$links      = get_field('links');
$image_id   = is_array($image) ? absint($image['ID'] ?? $image['id'] ?? 0) : absint($image);
$mass_times = is_array($mass_times) ? $mass_times : array();
$links      = is_array($links) ? $links : array();

$format_mass_time = static function ($value) {
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

$schedule_cards = array();

foreach ($mass_times as $mass_time) {
	if (! is_array($mass_time)) {
		continue;
	}

	$label     = trim((string) ($mass_time['label'] ?? ''));
	$notes     = (string) ($mass_time['notes'] ?? '');
	$days      = isset($mass_time['days']) && is_array($mass_time['days']) ? $mass_time['days'] : array();
	$day_rows  = array();
	$has_notes = '' !== trim(wp_strip_all_tags($notes));

	foreach ($days as $day) {
		if (! is_array($day)) {
			continue;
		}

		$day_label     = trim((string) ($day['day'] ?? ''));
		$times         = isset($day['times']) && is_array($day['times']) ? $day['times'] : array();
		$display_times = array();

		foreach ($times as $time) {
			if (! is_array($time)) {
				continue;
			}

			$start_time = $format_mass_time($time['time'] ?? '');

			if ('' === $start_time) {
				continue;
			}

			$end_time  = $format_mass_time($time['end_time'] ?? '');
			$time_note = trim((string) ($time['notes'] ?? ''));
			$time_text = $start_time;

			if ('' !== $end_time) {
				$time_text .= ' - ' . $end_time;
			}

			if ('' !== $time_note) {
				$time_text .= ' ' . $time_note;
			}

			$display_times[] = $time_text;
		}

		if ('' === $day_label || empty($display_times)) {
			continue;
		}

		$day_rows[] = array(
			'day'   => rtrim($day_label, " \t\n\r\0\x0B:"),
			'times' => $display_times,
		);
	}

	if ('' === $label || (empty($day_rows) && ! $has_notes)) {
		continue;
	}

	$schedule_cards[] = array(
		'label' => $label,
		'days'  => $day_rows,
		'notes' => $has_notes ? $notes : '',
	);
}

$button_links = array();

foreach ($links as $link_row) {
	$button_link = is_array($link_row) && isset($link_row['link']) && is_array($link_row['link'])
		? $link_row['link']
		: array();

	if (empty($button_link['url']) || empty($button_link['title'])) {
		continue;
	}

	$button_links[] = $button_link;
}

if ('' === $heading || '' === $subheading || empty($schedule_cards)) {
	return;
}

$heading_id   = wp_unique_id('saop-mass-times-heading-');
$column_size  = (int) ceil(count($schedule_cards) / 2);
$card_columns = array(
	array_slice($schedule_cards, 0, $column_size),
	array_slice($schedule_cards, $column_size),
);

if ($image_id) {
	$grid_classes   = 'md:grid-cols-[minmax(0,325px)_minmax(0,1fr)] md:gap-x-6 lg:grid-cols-[minmax(0,493px)_minmax(0,1fr)] lg:gap-x-16';
	$heading_layout = 'order-2 md:col-span-2 md:col-start-1 md:row-start-1 lg:col-span-1 lg:col-start-2 lg:row-start-1 lg:pt-5';
	$cards_layout   = 'order-3 md:col-start-2 md:row-start-2 md:row-span-2 md:max-w-[351px] md:justify-self-start lg:max-w-none';
	$links_layout   = 'order-4 md:col-start-1 md:row-start-3 lg:col-start-2 lg:row-start-3 lg:w-[calc(50%-1rem)] lg:justify-self-end';
} else {
	$grid_classes   = 'max-w-[950px]';
	$heading_layout = 'order-1';
	$cards_layout   = 'order-2';
	$links_layout   = 'order-3';
}

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'saop-section saop-mass-times not-prose  relative overflow-hidden bg-white px-4 pb-8 md:px-6  md:pb-8 lg:px-8 lg:pb-10',
	)
);
?>

<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?> aria-labelledby="<?php echo esc_attr($heading_id); ?>" data-saop-section>
    <div
        class="relative z-10 mx-auto grid max-w-wide grid-cols-1 gap-y-10 md:gap-y-10 <?php echo esc_attr($grid_classes); ?>">
        <?php if ($image_id) : ?>
        <div
            class="relative order-1 mx-auto w-full max-w-[325px] pb-6 pl-5 md:col-start-1 md:row-start-2 md:max-w-none lg:row-start-1 lg:row-span-3 lg:max-w-[493px] lg:pb-10 lg:pl-8">
            <div class="pointer-events-none absolute bottom-0 left-0 h-[74%] w-[72%] rounded-2lg border-4 border-accent-2"
                aria-hidden="true"></div>
            <div
                class="relative z-10 aspect-[325/297] w-full overflow-hidden rounded-2lg shadow-md lg:aspect-[493/451]">
                <?php
					echo wp_get_attachment_image(
						$image_id,
						'large',
						false,
						array(
							'class' => 'h-full w-full object-cover',
						)
					);
					?>
            </div>
        </div>
        <?php endif; ?>

        <header class="<?php echo esc_attr($heading_layout); ?>">
            <p class="m-0 font-accent text-[28px] leading-none text-accent-2 md:text-[38px] lg:text-5xl">
                <?php echo esc_html($subheading); ?>
            </p>
            <h2 id="<?php echo esc_attr($heading_id); ?>"
                class="mt-3 mb-0 ml-6 font-heading text-[28px] leading-tight font-normal text-primary-1 md:ml-20 md:text-[38px] lg:mt-5 lg:ml-10 lg:text-5xl">
                <?php echo esc_html($heading); ?>
            </h2>
        </header>

        <div
            class="flex flex-col gap-6 lg:grid lg:grid-cols-2 lg:items-start lg:gap-x-8 <?php echo esc_attr($cards_layout); ?>">
            <?php foreach ($card_columns as $card_column) : ?>
            <div class="contents lg:flex lg:flex-col lg:gap-8">
                <?php foreach ($card_column as $card) : ?>
                <?php $card_heading_id = wp_unique_id('saop-mass-times-card-'); ?>
                <section class="rounded-2lg border-3 border-accent-2 bg-white p-4 shadow-md"
                    aria-labelledby="<?php echo esc_attr($card_heading_id); ?>">
                    <h3 id="<?php echo esc_attr($card_heading_id); ?>"
                        class="m-0 font-heading text-2xl leading-none font-normal text-primary-2">
                        <?php echo esc_html($card['label']); ?>
                    </h3>

                    <?php if (! empty($card['days'])) : ?>
                    <dl class="mt-4 space-y-3 text-sm leading-normal text-black">
                        <?php foreach ($card['days'] as $day_row) : ?>
                        <div class="grid grid-cols-[max-content_minmax(0,1fr)] gap-x-4">
                            <dt><?php echo esc_html($day_row['day'] . ':'); ?></dt>
                            <dd class="min-w-0">
                                <?php echo esc_html(implode(', ', $day_row['times'])); ?>
                            </dd>
                        </div>
                        <?php endforeach; ?>
                    </dl>
                    <?php endif; ?>

                    <?php if ($card['notes']) : ?>
                    <div class="mt-4 text-sm leading-[1.484] text-black [&_a]:underline [&_p]:m-0 [&_p+p]:mt-3">
                        <?php echo wp_kses_post(wpautop($card['notes'])); ?>
                    </div>
                    <?php endif; ?>
                </section>
                <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if ($button_links) : ?>
        <div
            class="flex flex-wrap items-center justify-center gap-6 md:gap-8 lg:gap-6 <?php echo esc_attr($links_layout); ?>">
            <?php foreach ($button_links as $button_link) : ?>
            <?php echo wp_kses_post(saop_render_button($button_link, 'outline', 'min-h-12 min-w-30 px-5 text-center')); ?>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>