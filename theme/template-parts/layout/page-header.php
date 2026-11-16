<?php
/**
 * Featured-image header for standard pages.
 *
 * @package St._Anthony_of_Padua
 */

?>

<header class="relative h-60 overflow-hidden bg-primary-1 md:h-[clamp(240px,30vw,455px)]">
	<?php if ( saop_can_show_post_thumbnail() ) : ?>
		<?php
		echo get_the_post_thumbnail(
			get_the_ID(),
			'full',
			array(
				'class'         => 'absolute inset-0 h-full w-full object-cover',
				'alt'           => '',
				'loading'       => 'eager',
				'fetchpriority' => 'high',
				'decoding'      => 'async',
			)
		);
		?>
	<?php endif; ?>

	<div class="absolute inset-0 shadow-[inset_0_-107px_29px_0_rgba(0,0,0,0.45)]" aria-hidden="true"></div>

	<div class="relative mx-auto flex h-full max-w-wide items-end px-6 pb-8 md:px-12 md:pb-10">
		<?php
		the_title(
			'<h1 class="font-heading text-4xl leading-normal font-bold text-white text-shadow-md md:text-4-5xl">',
			'</h1>'
		);
		?>
	</div>
</header>
