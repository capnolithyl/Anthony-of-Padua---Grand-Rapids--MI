<?php

/**
 * Template part for displaying the header content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Catholic_Charities
 */

?>

<header id="masthead" data-page="front-page"
    class="fixed top-(--wp-admin-bar-height) right-0 left-0 z-100 border-b border-b-transparent py-4 transition-all">
    <div class="limit-width-wide flex flex-row items-center justify-between gap-8">
        <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
            <img class="logo-default h-20" src="<?php echo get_field("header_logo", "option")["url"]; ?>"
                alt="<?php echo get_field("header_logo", "option")["alt"]; ?>">
            <img class="logo-alt h-20" src="<?php echo get_field("header_logo_alt", "option")["url"]; ?>"
                alt="<?php echo get_field("header_logo_alt", "option")["alt"]; ?>">
        </a>


        <nav id="site-navigation" class="flex flex-1 items-center justify-end xl:justify-center"
            aria-label="<?php esc_attr_e('Main Navigation', 'ccwilm'); ?>">


            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'menu-1',
                    'menu_id'        => 'primary-menu',
                    'menu_class'     => 'flex flex-row gap-8',
                    'depth'          => 1,
                    'items_wrap'     => '<ul id="%1$s" class="%2$s" aria-label="submenu">%3$s</ul>',
                )
            ); ?>


        </nav><!-- #site-navigation -->
        <?php $menu_id = wp_get_nav_menu_object(get_nav_menu_locations()['menu-1'])->term_id;
        if (have_rows("additional_links", "nav_menu_$menu_id")) : ?>
        <div class="hidden shrink-0 items-center gap-4 xl:flex">

            <?php while (have_rows("additional_links", "nav_menu_$menu_id")) : the_row();
                    $link = get_sub_field("link");
                    if ($link) : ?>
            <a href="<?php echo esc_url($link['url']); ?>" class="btn <?php echo "btn-" . get_sub_field("style"); ?>">
                <?php echo esc_html($link['title']); ?>
            </a>
            <?php endif;
                endwhile; ?>

            <button class="cursor-pointer text-white transition-colors hover:text-gray-200">
                <i class="fas fa-search text-3xl transition-colors"></i>
            </button>
        </div>
        <?php endif; ?>
    </div>
</header><!-- #masthead -->
