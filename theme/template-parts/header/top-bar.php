<?php
/**
 * Quick links top bar and site search dialog.
 *
 * @package St._Anthony_of_Padua
 */

$saop_header_search_query = get_search_query( false );
?>

<?php if ( has_nav_menu( 'menu-3' ) ) : ?>
	<div class="flex items-center rounded-l-full bg-secondary-2 pl-9 shadow-md transition-all" data-top-bar>
		<nav aria-label="<?php esc_attr_e( 'Quick Links', 'saop' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'menu-3',
					'menu_class'     => 'text-primary-1',
					'depth'          => 1,
					'container'      => false,
				)
			);
			?>
		</nav>

		<button class="mr-3 ml-5 flex size-12 shrink-0 cursor-pointer items-center justify-center text-primary-1 transition-colors hover:text-secondary-3 focus-visible:rounded-full focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-1"
			type="button"
			aria-label="<?php esc_attr_e( 'Open site search', 'saop' ); ?>"
			aria-controls="site-search-dialog"
			aria-expanded="false"
			aria-haspopup="dialog"
			data-site-search-open>
			<i class="fas fa-search text-[26px] leading-none" aria-hidden="true"></i>
		</button>
	</div>
<?php endif; ?>


<dialog id="site-search-dialog"
	class="m-auto max-h-[calc(100dvh-2rem)] w-[calc(100%-2rem)] max-w-3xl overflow-visible rounded-3xl border-0 bg-secondary-1 p-0 text-primary-1 shadow-md backdrop:bg-primary-1/80"
	aria-labelledby="site-search-dialog-title"
	data-site-search-dialog>
	<div class="relative px-6 py-10 md:px-12 md:py-12">
		<button class="absolute top-4 right-4 flex size-10 cursor-pointer items-center justify-center rounded-full border-2 border-primary-1 text-3xl leading-none text-primary-1 transition-colors hover:border-primary-2 hover:text-primary-2 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-2"
			type="button"
			aria-label="<?php esc_attr_e( 'Close site search', 'saop' ); ?>"
			data-site-search-close>
			<span aria-hidden="true">&times;</span>
		</button>

		<p class="font-heading text-sm leading-normal font-bold tracking-wider text-primary-2 uppercase">
			<?php esc_html_e( 'Find What You Need', 'saop' ); ?>
		</p>
		<h2 id="site-search-dialog-title" class="mt-2 pr-12 font-heading text-3xl leading-tight font-bold text-primary-1 md:text-4xl">
			<?php esc_html_e( 'Search Our Site', 'saop' ); ?>
		</h2>

		<form class="mt-7 flex flex-col gap-3 sm:flex-row" role="search" method="get"
			action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label class="sr-only" for="site-search-dialog-query">
				<?php esc_html_e( 'Search this site', 'saop' ); ?>
			</label>
			<input id="site-search-dialog-query"
				class="min-h-12 w-full rounded-2lg border-2 border-secondary-4 bg-white px-4 py-3 text-base text-primary-1 shadow-sm outline-none placeholder:text-secondary-5 focus:border-primary-2 focus:ring-2 focus:ring-primary-2"
				type="search"
				name="s"
				value="<?php echo esc_attr( $saop_header_search_query ); ?>"
				placeholder="<?php esc_attr_e( 'What are you looking for?', 'saop' ); ?>"
				data-site-search-input>
			<button class="saop-button min-h-12 shrink-0 px-7" type="submit">
				<?php esc_html_e( 'Search', 'saop' ); ?>
			</button>
		</form>
	</div>
</dialog>
