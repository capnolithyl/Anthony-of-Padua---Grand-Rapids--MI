<?php
/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package St._Anthony_of_Padua
 */

global $wp_query;

$saop_search_query        = get_search_query( false );
$saop_search_result_count = (int) $wp_query->found_posts;

$saop_search_result_count_text = sprintf(
	/* translators: %s: Number of search results. */
	_n( '%s result found', '%s results found', $saop_search_result_count, 'saop' ),
	number_format_i18n( $saop_search_result_count )
);

get_header();
?>

<main id="primary" class="not-prose">
	<header class="bg-linear-to-br from-primary-1 via-primary-1 to-secondary-3 pt-12 pb-14 md:pt-16 md:pb-18 lg:pt-20">
		<div class="mx-auto max-w-narrow px-6 md:px-12">
			<p class="font-heading text-sm leading-normal font-bold tracking-wider text-primary-2 uppercase">
				<?php esc_html_e( 'Site Search', 'saop' ); ?>
			</p>

			<h1 class="mt-3 font-heading text-4xl leading-tight font-bold text-white md:text-5xl">
				<?php esc_html_e( 'Search results for', 'saop' ); ?>
				<span class="block text-primary-2">&ldquo;<?php echo esc_html( $saop_search_query ); ?>&rdquo;</span>
			</h1>

			<p class="mt-4 text-base text-white/80">
				<?php echo esc_html( $saop_search_result_count_text ); ?>
			</p>

			<form class="mt-8 flex max-w-3xl flex-col gap-3 sm:flex-row" role="search" method="get"
				action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label class="sr-only" for="search-results-query">
					<?php esc_html_e( 'Search this site', 'saop' ); ?>
				</label>
				<input id="search-results-query"
					class="min-h-12 w-full rounded-2lg border-2 border-white bg-white px-4 py-3 text-base text-primary-1 shadow-md outline-none placeholder:text-secondary-5 focus:border-primary-2 focus:ring-2 focus:ring-primary-2 focus:ring-offset-2 focus:ring-offset-primary-1"
					type="search" name="s" value="<?php echo esc_attr( $saop_search_query ); ?>"
					placeholder="<?php esc_attr_e( 'What are you looking for?', 'saop' ); ?>">
				<button class="saop-button min-h-12 shrink-0 px-7" type="submit">
					<?php esc_html_e( 'Search', 'saop' ); ?>
				</button>
			</form>
		</div>
	</header>

	<div class="mx-auto max-w-narrow px-6 py-14 md:px-12 md:py-20">
		<?php if ( have_posts() ) : ?>
			<section aria-labelledby="search-results-heading">
				<h2 id="search-results-heading" class="sr-only">
					<?php esc_html_e( 'Search results', 'saop' ); ?>
				</h2>
				<div class="space-y-6">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content/content', 'search-result' );
				endwhile;
				?>
				</div>
			</section>

			<?php
			$saop_search_pagination_links = paginate_links(
				array(
					'current'   => max( 1, get_query_var( 'paged' ) ),
					'total'     => $wp_query->max_num_pages,
					'mid_size'  => 2,
					'prev_text' => __( 'Previous', 'saop' ),
					'next_text' => __( 'Next', 'saop' ),
					'type'      => 'array',
				)
			);
			?>

			<?php if ( $saop_search_pagination_links ) : ?>
				<nav class="mt-12 border-t border-secondary-4 pt-8" aria-label="<?php esc_attr_e( 'Search result pages', 'saop' ); ?>">
					<ul class="flex flex-wrap items-center justify-center gap-2">
						<?php foreach ( $saop_search_pagination_links as $saop_search_pagination_link ) : ?>
							<li class="[&_.current]:border-primary-1 [&_.current]:bg-primary-1 [&_.current]:text-white [&_.dots]:border-transparent [&_.dots]:shadow-none [&_.page-numbers]:inline-flex [&_.page-numbers]:min-h-11 [&_.page-numbers]:min-w-11 [&_.page-numbers]:items-center [&_.page-numbers]:justify-center [&_.page-numbers]:rounded-2lg [&_.page-numbers]:border [&_.page-numbers]:border-secondary-4 [&_.page-numbers]:bg-white [&_.page-numbers]:px-3 [&_.page-numbers]:font-semibold [&_.page-numbers]:text-primary-1 [&_.page-numbers]:shadow-sm [&_a.page-numbers]:transition-colors [&_a.page-numbers]:hover:border-primary-2 [&_a.page-numbers]:hover:bg-primary-2 [&_a.page-numbers]:hover:text-white [&_a.page-numbers]:focus-visible:outline-2 [&_a.page-numbers]:focus-visible:outline-offset-2 [&_a.page-numbers]:focus-visible:outline-primary-2">
								<?php echo wp_kses_post( $saop_search_pagination_link ); ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</nav>
			<?php endif; ?>
		<?php else : ?>
			<section class="rounded-3xl border border-secondary-4 bg-secondary-1 px-6 py-10 shadow-md md:px-10 md:py-12"
				aria-labelledby="no-search-results-heading">
				<h2 id="no-search-results-heading" class="font-heading text-3xl leading-tight font-bold text-primary-1 md:text-4xl">
					<?php esc_html_e( 'Nothing matched your search', 'saop' ); ?>
				</h2>
				<p class="mt-4 max-w-2xl text-lg leading-relaxed text-secondary-6">
					<?php esc_html_e( 'Try another phrase, check the spelling, or use fewer words to broaden your search.', 'saop' ); ?>
				</p>

				<form class="mt-7 flex max-w-3xl flex-col gap-3 sm:flex-row" role="search" method="get"
					action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<label class="sr-only" for="search-results-empty-query">
						<?php esc_html_e( 'Try another search', 'saop' ); ?>
					</label>
					<input id="search-results-empty-query"
						class="min-h-12 w-full rounded-2lg border-2 border-secondary-4 bg-white px-4 py-3 text-base text-primary-1 shadow-sm outline-none placeholder:text-secondary-5 focus:border-primary-2 focus:ring-2 focus:ring-primary-2"
						type="search" name="s" value="<?php echo esc_attr( $saop_search_query ); ?>"
						placeholder="<?php esc_attr_e( 'Try a different search', 'saop' ); ?>">
					<button class="saop-button min-h-12 shrink-0 px-7" type="submit">
						<?php esc_html_e( 'Search Again', 'saop' ); ?>
					</button>
				</form>
			</section>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
