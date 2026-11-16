<?php

function saop_filter_block_categories_when_post_provided($block_categories, $editor_context)
{
    if (! empty($editor_context->post)) {
        array_push(
            $block_categories,
            array(
                'slug'  => 'saop',
                'title' => __('SAOP', 'saop'),
                'icon'  => null,
            )
        );
    }
    return $block_categories;
}
add_filter('block_categories_all', 'saop_filter_block_categories_when_post_provided', 10, 2);