<?php

/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#404-not-found
 *
 * @package St._Anthony_of_Padua
 */

get_header();
?>

<main id="primary" class="not-prose overflow-hidden bg-secondary-1">
    <section class="relative isolate px-6 pt-48 pb-20 md:px-10 md:pt-56 md:pb-24 lg:px-16 lg:pt-64 lg:pb-32"
        aria-labelledby="error-404-title">
        <div class="absolute top-32 -right-32 -z-10 size-80 rounded-full bg-secondary-2/60 md:size-112"
            aria-hidden="true"></div>
        <div class="absolute -bottom-44 -left-40 -z-10 size-96 rounded-full border-32 border-white/70 md:size-128"
            aria-hidden="true"></div>

        <div
            class="mx-auto grid max-w-mid items-center gap-12 lg:grid-cols-[minmax(0,0.9fr)_minmax(0,1.1fr)] lg:gap-20">
            <div class="relative mx-auto flex aspect-square w-full max-w-105 items-center justify-center"
                aria-hidden="true">
                <div class="absolute inset-5 rounded-full border-3 border-primary-2 bg-white shadow-md md:inset-0">
                </div>
                <div class="absolute top-3 right-3 size-20 rounded-full bg-accent-2 md:size-28"></div>
                <div class="absolute bottom-4 left-0 h-16 w-36 rounded-full bg-secondary-2 md:h-20 md:w-44"></div>
                <span
                    class="relative font-heading text-[clamp(7rem,25vw,13rem)] leading-none font-bold tracking-[-0.08em] text-primary-1">
                    404
                </span>
            </div>

            <div class="mx-auto w-full max-w-2xl text-center lg:mx-0 lg:text-left">
                <p class="m-0 font-accent text-3xl leading-tight text-accent-2 md:text-4xl">
                    <?php esc_html_e('Let us help you find your way.', 'saop'); ?>
                </p>

                <h1 id="error-404-title"
                    class="mt-4 font-heading text-4xl leading-tight font-bold text-primary-1 md:text-5xl lg:text-6xl">
                    <?php esc_html_e('Page not found', 'saop'); ?>
                </h1>

                <p id="error-404-description"
                    class="mx-auto mt-5 max-w-xl text-lg leading-relaxed text-secondary-6 lg:mx-0">
                    <?php esc_html_e('The page you are looking for may have moved, or the address may be incorrect. Try searching the site or return to the homepage.', 'saop'); ?>
                </p>

                <form class="mt-8 flex flex-col gap-3 sm:flex-row" role="search" method="get"
                    action="<?php echo esc_url(home_url('/')); ?>" aria-describedby="error-404-description">
                    <label class="sr-only" for="error-404-search">
                        <?php esc_html_e('Search this site', 'saop'); ?>
                    </label>
                    <div class="relative min-w-0 flex-1">
                        <svg class="pointer-events-none absolute top-1/2 left-4 size-5 -translate-y-1/2 text-secondary-3"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <circle cx="11" cy="11" r="7"></circle>
                            <path d="m20 20-4-4"></path>
                        </svg>
                        <input id="error-404-search"
                            class="h-12 w-full rounded-2lg border-2 border-secondary-3 bg-white py-3 pr-4 pl-12 text-base text-primary-1 shadow-md outline-none placeholder:text-secondary-5 focus:border-primary-2 focus:ring-2 focus:ring-primary-2/30"
                            type="search" name="s" value="<?php echo esc_attr(get_search_query()); ?>"
                            placeholder="<?php esc_attr_e('What are you looking for?', 'saop'); ?>">
                    </div>
                    <button class="saop-button h-12 shrink-0 px-6" type="submit">
                        <?php esc_html_e('Search', 'saop'); ?>
                    </button>
                </form>

                <a class="saop-button saop-button--outline mt-5 inline-flex items-center justify-center gap-2"
                    href="<?php echo esc_url(home_url('/')); ?>">
                    <svg class="size-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2"
                        aria-hidden="true">
                        <path d="m12.5 4.5-5.5 5.5 5.5 5.5"></path>
                    </svg>
                    <?php esc_html_e('Return home', 'saop'); ?>
                </a>
            </div>
        </div>
    </section>
</main><!-- #primary -->

<?php
get_footer();