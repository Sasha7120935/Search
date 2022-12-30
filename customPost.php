<?php

function car_register()
{
    $labels = array(
        'name' => _x('Car', 'wl-test-theme'),
        'singular_name' => _x('Car Item', 'wl-test-theme'),
        'add_new' => _x('Add New', 'wl-test-theme'),
        'add_new_item' => __('Add New Car Item', 'wl-test-theme'),
        'edit_item' => __('Edit Car Item', 'wl-test-theme'),
        'new_item' => __('New Car Item', 'wl-test-theme'),
        'view_item' => __('View Car Item', 'wl-test-theme'),
        'search_items' => __('Search Car Items', 'wl-test-theme'),
        'not_found' => __('Nothing found', 'wl-test-theme'),
        'not_found_in_trash' => __('Nothing found in Trash', 'wl-test-theme'),
        'parent_item_colon' => ''
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'menu_position' => 8,
        'hierarchical' => true,
        'description' => __('Description.'),
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'car'),
        'capability_type' => 'page',
        'has_archive' => true,
        "show_in_rest" => true,
        'supports' => array('title', 'editor', 'author', 'thumbnail', 'comments', 'revisions', 'page-attributes', 'custom-fields')
    );
    register_post_type('car', $args);
}
add_action('init', 'car_register');