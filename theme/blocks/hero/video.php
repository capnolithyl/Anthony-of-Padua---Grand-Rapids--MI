<?php

/**
 * Video media for the hero block.
 *
 * @package St._Anthony_of_Padua
 */

$video_file        = get_field('video_file');
$video_poster      = get_field('video_poster');
$video_heading     = get_field('video_heading');
$video_subheadings = get_field('video_subheading');
$video_buttons     = get_field('video_buttons');

$video_url       = '';
$video_mime_type = '';
$poster_id       = 0;
$poster_url      = '';

if (is_array($video_file)) {
	$video_url       = (string) ($video_file['url'] ?? '');
	$video_mime_type = (string) ($video_file['mime_type'] ?? $video_mime_type);
} elseif (is_numeric($video_file)) {
	$video_url       = (string) wp_get_attachment_url((int) $video_file);
	$video_mime_type = (string) get_post_mime_type((int) $video_file);
} elseif (is_string($video_file)) {
	$video_url = $video_file;
}

if (empty($video_mime_type) && ! empty($video_url)) {
	$video_file_type = wp_check_filetype($video_url);
	$video_mime_type = ! empty($video_file_type['type'])
		? (string) $video_file_type['type']
		: 'video/mp4';
}

if (is_array($video_poster)) {
	$poster_id  = (int) ($video_poster['ID'] ?? $video_poster['id'] ?? 0);
	$poster_url = (string) ($video_poster['url'] ?? '');
} elseif (is_numeric($video_poster)) {
	$poster_id  = (int) $video_poster;
	$poster_url = (string) wp_get_attachment_image_url($poster_id, 'full');
} elseif (is_string($video_poster)) {
	$poster_url = $video_poster;
}

if (is_string($video_subheadings)) {
	$video_subheadings = '' !== trim($video_subheadings)
		? array(array('subheading' => $video_subheadings))
		: array();
} elseif (is_array($video_subheadings) && isset($video_subheadings['subheading'])) {
	$video_subheadings = array($video_subheadings);
} elseif (! is_array($video_subheadings)) {
	$video_subheadings = array();
}

$video_buttons = is_array($video_buttons) ? $video_buttons : array();
?>

<article class="relative" data-hero-slide>
	<div class="relative h-screen w-screen overflow-hidden bg-black">
		<?php if (! empty($video_url)) : ?>
			<video class="h-screen w-screen object-cover" autoplay muted loop playsinline preload="metadata" aria-hidden="true"
				<?php if (! empty($poster_url)) : ?>
				poster="<?php echo esc_url($poster_url); ?>"
				<?php endif; ?>>
				<source src="<?php echo esc_url($video_url); ?>" type="<?php echo esc_attr($video_mime_type); ?>">
			</video>
		<?php elseif ($poster_id) : ?>
			<?php
			echo wp_get_attachment_image(
				$poster_id,
				'full',
				false,
				array(
					'class'         => 'h-screen w-screen object-cover',
					'loading'       => 'eager',
					'fetchpriority' => 'high',
				)
			);
			?>
		<?php elseif (! empty($poster_url)) : ?>
			<img class="h-screen w-screen object-cover" src="<?php echo esc_url($poster_url); ?>" alt="">
		<?php else : ?>
			<p class="m-0 flex h-screen items-center justify-center p-6 text-center text-white">
				<?php esc_html_e('Please upload a video file and poster image to this hero block.', 'saop'); ?>
			</p>
		<?php endif; ?>

		<?php if (! empty($video_url) || ! empty($poster_url)) : ?>
			<?php get_template_part('blocks/hero/overlay'); ?>
		<?php endif; ?>
	</div>

	<?php
	get_template_part(
		'blocks/hero/info',
		null,
		array(
			'heading'     => $video_heading,
			'subheadings' => $video_subheadings,
			'buttons'     => $video_buttons,
		)
	);
	?>
</article>
