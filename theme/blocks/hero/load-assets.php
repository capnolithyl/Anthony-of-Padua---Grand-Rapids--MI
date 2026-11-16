<?php

function saop_enqueue_hero_assets()
{
    saop_enqueue_slick_assets();

    wp_enqueue_script(
        'saop-hero',
        get_theme_file_uri('/blocks/hero/hero.js'),
        array('jquery', 'saop-slick'),
        filemtime(get_theme_file_path('/blocks/hero/hero.js')),
        true
    );
}
