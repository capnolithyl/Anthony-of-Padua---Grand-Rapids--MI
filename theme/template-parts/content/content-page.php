<?php

/**
 * Template part for displaying pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package St._Anthony_of_Padua
 */

$show_page_header = ! isset($args['show_page_header']) || $args['show_page_header'];
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php if ($show_page_header && ! is_front_page()) : ?>
    <?php get_template_part('template-parts/layout/page', 'header'); ?>
    <?php endif; ?>

    <div <?php is_front_page() ? saop_content_class('entry-content') : saop_content_class('entry-content mt-19'); ?>>
        <?php the_content(); ?>
    </div>
</article><!-- #post-<?php the_ID(); ?> -->