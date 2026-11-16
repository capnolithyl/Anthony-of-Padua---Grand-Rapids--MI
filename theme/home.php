<?php
/**
 * The template for the posts page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package St._Anthony_of_Padua
 */

$saop_latest_posts = get_posts(
	array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 5,
		'ignore_sticky_posts' => true,
		'orderby'             => 'date',
		'order'               => 'DESC',
	)
);

$saop_news_categories = array_filter(
	get_categories(
		array(
			'hide_empty' => true,
			'orderby'    => 'term_id',
			'order'      => 'ASC',
		)
	),
	static function ( $term ) {
		return $term instanceof WP_Term;
	}
);

$saop_requested_category = is_category() ? get_queried_object() : null;

if ( ! $saop_requested_category instanceof WP_Term || 'category' !== $saop_requested_category->taxonomy ) {
	$saop_requested_category = null;
}

if ( $saop_requested_category instanceof WP_Term ) {
	$saop_has_requested_category = false;

	foreach ( $saop_news_categories as $saop_news_category ) {
		if ( $saop_news_category->term_id === $saop_requested_category->term_id ) {
			$saop_has_requested_category = true;
			break;
		}
	}

	if ( ! $saop_has_requested_category ) {
		$saop_news_categories[] = $saop_requested_category;

		usort(
			$saop_news_categories,
			static function ( $first_term, $second_term ) {
				return $first_term->term_id <=> $second_term->term_id;
			}
		);
	}
}

$saop_discover_tabs = array(
	array(
		'key'  => 'all',
		'name' => __( 'All', 'saop' ),
		'term' => null,
	),
);

foreach ( $saop_news_categories as $saop_news_category ) {
	$saop_discover_tabs[] = array(
		'key'  => $saop_news_category->slug,
		'name' => $saop_news_category->name,
		'term' => $saop_news_category,
	);
}

$saop_instance_id = wp_unique_id( 'saop-news-' );

get_header();
?>

<main id="primary" class="not-prose overflow-hidden" data-news-archive
	<?php echo $saop_requested_category instanceof WP_Term ? ' data-news-scroll-to-discover' : ''; ?>>
	<section class="bg-white pt-14 pb-28 lg:pt-14 lg:pb-30" aria-labelledby="latest-news-heading">
		<div class="mx-auto mb-10 flex max-w-wide items-end justify-between gap-6 px-6 md:px-8 lg:px-12">
			<h1 id="latest-news-heading" class="font-heading text-3xl leading-normal font-normal text-primary-1 md:text-4xl lg:text-5xl">
				<?php esc_html_e( 'Latest News', 'saop' ); ?>
			</h1>
			<a class="shrink-0 text-lg text-primary-2 underline underline-offset-2 lg:text-xl" href="#news-discover">
				<?php esc_html_e( 'View all', 'saop' ); ?>
			</a>
		</div>

		<?php if ( $saop_latest_posts ) : ?>
			<div class="h-58 md:h-67.5 lg:h-91 [&_.slick-center>a]:h-58 [&_.slick-center>a]:translate-y-0 md:[&_.slick-center>a]:h-67.5 lg:[&_.slick-center>a]:h-91" data-news-latest-track>
				<?php foreach ( $saop_latest_posts as $saop_latest_post ) : ?>
					<?php
					get_template_part(
						'template-parts/content/content-news-slider-card',
						null,
						array( 'post' => $saop_latest_post )
					);
					?>
				<?php endforeach; ?>
			</div>

			<div
				class="mt-12 flex min-h-1.5 items-center justify-center px-6 lg:mt-15 [&_.slick-active_button]:w-34 [&_.slick-active_button]:bg-accent-2 md:[&_.slick-active_button]:w-61.5 lg:[&_.slick-active_button]:w-92.5"
				data-news-latest-dots></div>
		<?php else : ?>
			<p class="mx-auto max-w-wide px-6 text-lg text-primary-1 md:px-8 lg:px-12">
				<?php esc_html_e( 'No news posts are available yet.', 'saop' ); ?>
			</p>
		<?php endif; ?>
	</section>

	<section id="news-discover" class="scroll-mt-32 bg-secondary-1 pt-6 pb-14 lg:pb-15" aria-labelledby="news-discover-heading">
		<div class="mx-auto max-w-wide px-4 md:px-7.5">
			<h2 id="news-discover-heading" class="ml-2 font-heading text-2xl leading-normal font-normal text-primary-1 md:ml-7.5 md:text-4-5xl lg:ml-0 lg:text-5xl">
				<?php esc_html_e( 'Discover', 'saop' ); ?>
			</h2>

			<div
				class="mt-7 flex flex-nowrap items-end overflow-x-auto overflow-y-hidden border-b-2 border-primary-1 md:mx-5 lg:mx-0 lg:mt-9"
				role="tablist" aria-label="<?php esc_attr_e( 'News categories', 'saop' ); ?>">
				<?php foreach ( $saop_discover_tabs as $saop_index => $saop_discover_tab ) : ?>
					<?php
					$saop_tab_term  = $saop_discover_tab['term'];
					$saop_is_active = $saop_requested_category instanceof WP_Term
						? $saop_tab_term instanceof WP_Term && $saop_tab_term->term_id === $saop_requested_category->term_id
						: 0 === $saop_index;
					$saop_tab_key   = sanitize_html_class( $saop_discover_tab['key'] );
					$saop_tab_id    = $saop_instance_id . '-tab-' . $saop_tab_key;
					$saop_panel_id  = $saop_instance_id . '-panel-' . $saop_tab_key;
					?>
					<button
						class="relative min-w-max flex-1 shrink-0 cursor-pointer bg-transparent px-3 pb-3 font-heading text-lg leading-normal font-bold text-primary-1 uppercase transition-colors after:absolute after:inset-x-3 after:-bottom-0.5 after:h-1.75 after:rounded-full after:bg-transparent after:content-[''] hover:text-accent-2 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-2 aria-selected:text-accent-2 aria-selected:after:bg-accent-2 md:px-6 md:text-xl md:after:inset-x-6"
						type="button" id="<?php echo esc_attr( $saop_tab_id ); ?>" role="tab" data-news-tab
						aria-controls="<?php echo esc_attr( $saop_panel_id ); ?>"
						aria-selected="<?php echo $saop_is_active ? 'true' : 'false'; ?>"
						tabindex="<?php echo $saop_is_active ? '0' : '-1'; ?>">
						<?php echo esc_html( $saop_discover_tab['name'] ); ?>
					</button>
				<?php endforeach; ?>
			</div>

			<div class="mt-10 lg:mt-12">
				<?php foreach ( $saop_discover_tabs as $saop_index => $saop_discover_tab ) : ?>
					<?php
					$saop_tab_term   = $saop_discover_tab['term'];
					$saop_is_active  = $saop_requested_category instanceof WP_Term
						? $saop_tab_term instanceof WP_Term && $saop_tab_term->term_id === $saop_requested_category->term_id
						: 0 === $saop_index;
					$saop_tab_key    = sanitize_html_class( $saop_discover_tab['key'] );
					$saop_tab_id     = $saop_instance_id . '-tab-' . $saop_tab_key;
					$saop_panel_id   = $saop_instance_id . '-panel-' . $saop_tab_key;
					$saop_term       = $saop_discover_tab['term'];
					$saop_query_args = array(
						'post_type'           => 'post',
						'post_status'         => 'publish',
						'posts_per_page'      => 5,
						'ignore_sticky_posts' => true,
						'orderby'             => 'date',
						'order'               => 'DESC',
					);

					if ( $saop_term instanceof WP_Term ) {
						$saop_query_args['category__in'] = array( $saop_term->term_id );
					}

					$saop_panel_posts   = get_posts( $saop_query_args );
					$saop_featured_post = $saop_panel_posts[0] ?? null;
					$saop_list_posts    = array_slice( $saop_panel_posts, 1, 4 );
					?>

					<section id="<?php echo esc_attr( $saop_panel_id ); ?>" role="tabpanel"
						aria-labelledby="<?php echo esc_attr( $saop_tab_id ); ?>" data-news-panel
						<?php echo $saop_is_active ? '' : ' hidden'; ?>>
						<?php if ( $saop_featured_post instanceof WP_Post ) : ?>
							<div class="grid gap-8.5 md:gap-15 lg:grid-cols-[minmax(0,5fr)_minmax(320px,3fr)] lg:gap-7.5">
								<?php
								get_template_part(
									'template-parts/content/content-news-featured',
									null,
									array(
										'post'     => $saop_featured_post,
										'category' => $saop_term,
									)
								);
								?>

								<div class="lg:border-l-2 lg:border-primary-1 lg:pt-4 lg:pl-6.5">
									<?php if ( $saop_list_posts ) : ?>
										<div class="mx-20 hidden border-t-2 border-primary-1 md:block lg:hidden" aria-hidden="true"></div>

										<div class="grid gap-8.5 md:mt-13.5 md:grid-cols-2 md:gap-x-4.75 md:gap-y-7.5 lg:mt-0 lg:grid-cols-1 lg:gap-6">
											<?php foreach ( $saop_list_posts as $saop_list_post ) : ?>
												<?php
												get_template_part(
													'template-parts/content/content-news-list-item',
													null,
													array(
														'post'     => $saop_list_post,
														'category' => $saop_term,
													)
												);
												?>
											<?php endforeach; ?>
										</div>
									<?php endif; ?>

									<?php if ( $saop_term instanceof WP_Term ) : ?>
										<?php $saop_category_url = get_category_link( $saop_term ); ?>
										<?php if ( ! is_wp_error( $saop_category_url ) ) : ?>
											<div class="mt-10 text-center lg:mt-7.5 lg:text-left">
												<a class="text-sm text-primary-2 underline underline-offset-2 lg:hidden" href="<?php echo esc_url( $saop_category_url ); ?>">
													<?php esc_html_e( 'Load More', 'saop' ); ?>
												</a>
												<a class="saop-button hidden lg:inline-flex" href="<?php echo esc_url( $saop_category_url ); ?>">
													<?php esc_html_e( 'View all', 'saop' ); ?>
												</a>
											</div>
										<?php endif; ?>
									<?php endif; ?>
								</div>
							</div>
						<?php else : ?>
							<p class="text-lg text-primary-1">
								<?php esc_html_e( 'No news posts are available in this category.', 'saop' ); ?>
							</p>
						<?php endif; ?>
					</section>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
