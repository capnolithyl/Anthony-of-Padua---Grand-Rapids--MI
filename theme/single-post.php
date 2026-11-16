<?php
/**
 * The template for displaying standard posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package St._Anthony_of_Padua
 */

get_header();
?>

<main id="primary" class="not-prose bg-white">
	<?php
	while ( have_posts() ) :
		the_post();

		$saop_post_categories = get_the_category();
		$saop_post_tags       = get_the_tags();
		$saop_posts_page_id   = (int) get_option( 'page_for_posts' );
		$saop_posts_page_url  = $saop_posts_page_id ? get_permalink( $saop_posts_page_id ) : false;
		$saop_previous_post   = get_previous_post();
		$saop_next_post       = get_next_post();

		if ( ! $saop_posts_page_url ) {
			$saop_posts_page_url = home_url( '/posts/' );
		}
		?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="relative min-h-80 overflow-hidden bg-primary-1 md:min-h-112 lg:min-h-128">
				<?php if ( has_post_thumbnail() ) : ?>
					<?php
					echo get_the_post_thumbnail(
						get_the_ID(),
						'full',
						array(
							'alt'           => '',
							'class'         => 'absolute inset-0 h-full w-full object-cover',
							'decoding'      => 'async',
							'fetchpriority' => 'high',
							'loading'       => 'eager',
						)
					);
					?>
				<?php endif; ?>

				<div class="absolute inset-0 bg-linear-to-t from-primary-1 via-primary-1/65 to-primary-1/15" aria-hidden="true"></div>

				<div class="relative mx-auto flex min-h-80 max-w-wide flex-col justify-end px-6 py-10 md:min-h-112 md:px-8 md:py-14 lg:min-h-128 lg:px-12 lg:py-16">
					<?php if ( $saop_post_categories ) : ?>
						<nav class="mb-5 flex flex-wrap gap-2" aria-label="<?php esc_attr_e( 'Categories', 'saop' ); ?>">
							<?php foreach ( $saop_post_categories as $saop_post_category ) : ?>
								<?php $saop_category_url = get_category_link( $saop_post_category ); ?>
								<?php if ( ! is_wp_error( $saop_category_url ) ) : ?>
									<a class="rounded-2lg bg-primary-2 px-4 py-1.5 font-heading text-sm leading-none font-bold text-white uppercase transition-colors hover:bg-white hover:text-primary-1 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"
										href="<?php echo esc_url( $saop_category_url ); ?>" rel="category tag">
										<?php echo esc_html( $saop_post_category->name ); ?>
									</a>
								<?php endif; ?>
							<?php endforeach; ?>
						</nav>
					<?php endif; ?>

					<?php the_title( '<h1 class="max-w-5xl font-heading text-4xl leading-tight font-normal text-white text-shadow-md md:text-4-5xl lg:text-6xl">', '</h1>' ); ?>

					<time class="mt-5 block text-base leading-normal text-white/85"
						datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
						<?php echo esc_html( get_the_date() ); ?>
					</time>
				</div>
			</header>

			<div class="py-12 md:py-16 lg:py-20">
				<div class="mx-auto max-w-narrow px-6 md:px-8 lg:px-12">
					<a class="inline-flex items-center font-heading text-base font-bold text-primary-2 uppercase underline decoration-2 underline-offset-4 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-1"
						href="<?php echo esc_url( $saop_posts_page_url ); ?>">
						<?php esc_html_e( 'Back to Posts', 'saop' ); ?>
					</a>

					<div <?php saop_content_class( 'post-content mt-10' ); ?>>
						<?php
						the_content();

						wp_link_pages(
							array(
								'before' => '<nav class="mt-8 flex flex-wrap items-center gap-2" aria-label="' . esc_attr__( 'Post pages', 'saop' ) . '"><span class="font-bold">' . esc_html__( 'Pages:', 'saop' ) . '</span>',
								'after'  => '</nav>',
							)
						);
						?>
					</div>

					<?php if ( $saop_post_categories || $saop_post_tags ) : ?>
						<footer class="mt-12 border-t-2 border-secondary-4 pt-8 text-base leading-normal text-primary-1">
							<?php if ( $saop_post_categories ) : ?>
								<div class="flex flex-wrap items-baseline gap-x-3 gap-y-2">
									<span class="font-heading font-bold"><?php esc_html_e( 'Categories:', 'saop' ); ?></span>
									<?php foreach ( $saop_post_categories as $saop_post_category ) : ?>
										<?php $saop_category_url = get_category_link( $saop_post_category ); ?>
										<?php if ( ! is_wp_error( $saop_category_url ) ) : ?>
											<a class="text-primary-2 underline underline-offset-2 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-1"
												href="<?php echo esc_url( $saop_category_url ); ?>" rel="category tag">
												<?php echo esc_html( $saop_post_category->name ); ?>
											</a>
										<?php endif; ?>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>

							<?php if ( $saop_post_tags ) : ?>
								<div class="mt-4 flex flex-wrap items-baseline gap-x-3 gap-y-2">
									<span class="font-heading font-bold"><?php esc_html_e( 'Tags:', 'saop' ); ?></span>
									<?php foreach ( $saop_post_tags as $saop_post_tag ) : ?>
										<a class="text-primary-2 underline underline-offset-2 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-1"
											href="<?php echo esc_url( get_tag_link( $saop_post_tag ) ); ?>" rel="tag">
											<?php echo esc_html( $saop_post_tag->name ); ?>
										</a>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>

							<?php edit_post_link( __( 'Edit post', 'saop' ), '<div class="mt-4">', '</div>' ); ?>
						</footer>
					<?php endif; ?>

					<?php if ( $saop_previous_post instanceof WP_Post || $saop_next_post instanceof WP_Post ) : ?>
						<nav class="mt-12 grid gap-4 border-t-2 border-secondary-4 pt-8 md:grid-cols-2" aria-label="<?php esc_attr_e( 'Post navigation', 'saop' ); ?>">
							<?php if ( $saop_previous_post instanceof WP_Post ) : ?>
								<a class="rounded-2lg border-2 border-primary-2 p-5 transition-colors hover:bg-secondary-1 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-1"
									href="<?php echo esc_url( get_permalink( $saop_previous_post ) ); ?>" rel="prev">
									<span class="block font-heading text-sm font-bold text-primary-2 uppercase">
										<?php esc_html_e( 'Previous post', 'saop' ); ?>
									</span>
									<span class="mt-2 block font-heading text-xl leading-normal text-primary-1">
										<?php echo esc_html( get_the_title( $saop_previous_post ) ); ?>
									</span>
								</a>
							<?php endif; ?>

							<?php if ( $saop_next_post instanceof WP_Post ) : ?>
								<a class="rounded-2lg border-2 border-primary-2 p-5 transition-colors hover:bg-secondary-1 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-1 md:col-start-2 md:text-right"
									href="<?php echo esc_url( get_permalink( $saop_next_post ) ); ?>" rel="next">
									<span class="block font-heading text-sm font-bold text-primary-2 uppercase">
										<?php esc_html_e( 'Next post', 'saop' ); ?>
									</span>
									<span class="mt-2 block font-heading text-xl leading-normal text-primary-1">
										<?php echo esc_html( get_the_title( $saop_next_post ) ); ?>
									</span>
								</a>
							<?php endif; ?>
						</nav>
					<?php endif; ?>
				</div>
			</div>
		</article>
	<?php endwhile; ?>
</main>

<?php
get_footer();
