<?php
/**
 * Single ministry page.
 *
 * @package St._Anthony_of_Padua
 */

get_header();
?>

<main id="primary" class="bg-white">
	<?php while ( have_posts() ) : ?>
		<?php
		the_post();
		$saop_ministry_header_image = saop_get_ministry_header_image( get_the_ID() );
		?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="relative h-60 overflow-hidden bg-primary-1 md:h-[clamp(300px,30vw,455px)]">
				<?php if ( $saop_ministry_header_image['id'] ) : ?>
					<?php
					echo wp_get_attachment_image(
						$saop_ministry_header_image['id'],
						'full',
						false,
						array(
							'alt'           => '',
							'class'         => 'absolute inset-0 h-full w-full object-cover',
							'decoding'      => 'async',
							'fetchpriority' => 'high',
							'loading'       => 'eager',
						)
					);
					?>
				<?php elseif ( $saop_ministry_header_image['url'] ) : ?>
					<img class="absolute inset-0 h-full w-full object-cover"
						src="<?php echo esc_url( $saop_ministry_header_image['url'] ); ?>" alt="" loading="eager" fetchpriority="high">
				<?php endif; ?>

				<div class="absolute inset-0 bg-linear-to-t from-black/65 via-black/10 to-transparent" aria-hidden="true"></div>

				<div class="relative mx-auto flex h-full w-[calc(100%-3rem)] max-w-wide items-end pb-8 md:pb-10">
					<?php the_title( '<h1 class="font-heading text-4xl leading-normal font-bold text-white text-shadow-md md:text-4-5xl">', '</h1>' ); ?>
				</div>
			</header>

			<div class="mx-auto w-[calc(100%-3rem)] max-w-wide py-12 md:py-16 lg:py-20">
				<div <?php saop_content_class( 'entry-content' ); ?>>
					<?php
					the_content();

					wp_link_pages(
						array(
							'before' => '<nav class="mt-8 flex flex-wrap items-center gap-2" aria-label="' . esc_attr__( 'Ministry pages', 'saop' ) . '"><span class="font-bold">' . esc_html__( 'Pages:', 'saop' ) . '</span>',
							'after'  => '</nav>',
						)
					);
					?>
				</div>
			</div>
		</article>
	<?php endwhile; ?>
</main>

<?php
get_footer();
