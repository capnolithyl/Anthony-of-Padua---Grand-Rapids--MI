<?php

function saop_enqueue_slick_assets()
{
    wp_enqueue_style(
        'saop-slick',
        get_theme_file_uri('/js/vendor/slick-slider/slick.css'),
        array(),
        filemtime(get_theme_file_path('/js/vendor/slick-slider/slick.css'))
    );

    wp_enqueue_style(
        'saop-slick-theme',
        get_theme_file_uri('/js/vendor/slick-slider/slick-theme.css'),
        array('saop-slick'),
        filemtime(get_theme_file_path('/js/vendor/slick-slider/slick-theme.css'))
    );

    wp_enqueue_script(
        'saop-slick',
        get_theme_file_uri('/js/vendor/slick-slider/slick.min.js'),
        array('jquery'),
        filemtime(get_theme_file_path('/js/vendor/slick-slider/slick.min.js')),
        true
    );
}
