<?php

/**
 * Custom post type to log RMA data
 */

// Register Custom Post Type RMA
function create_rma_cpt() {

    $labels = array(
        'name' => _x('RMAs', 'Post Type General Name', 'sb-wc-rma'),
        'singular_name' => _x('RMA', 'Post Type Singular Name', 'sb-wc-rma'),
        'menu_name' => _x('RMAs', 'Admin Menu text', 'sb-wc-rma'),
        'name_admin_bar' => _x('RMA', 'Add New on Toolbar', 'sb-wc-rma'),
        'archives' => __('RMA Archives', 'sb-wc-rma'),
        'attributes' => __('RMA Attributes', 'sb-wc-rma'),
        'parent_item_colon' => __('Parent RMA:', 'sb-wc-rma'),
        'all_items' => __('RMAs', 'sb-wc-rma'),
        'add_new_item' => __('Add New RMA', 'sb-wc-rma'),
        'add_new' => __('Add New', 'sb-wc-rma'),
        'new_item' => __('New RMA', 'sb-wc-rma'),
        'edit_item' => __('Edit RMA', 'sb-wc-rma'),
        'update_item' => __('Update RMA', 'sb-wc-rma'),
        'view_item' => __('View RMA', 'sb-wc-rma'),
        'view_items' => __('View RMAs', 'sb-wc-rma'),
        'search_items' => __('Search RMA', 'sb-wc-rma'),
        'not_found' => __('Not found', 'sb-wc-rma'),
        'not_found_in_trash' => __('Not found in Trash', 'sb-wc-rma'),
        'featured_image' => __('Featured Image', 'sb-wc-rma'),
        'set_featured_image' => __('Set featured image', 'sb-wc-rma'),
        'remove_featured_image' => __('Remove featured image', 'sb-wc-rma'),
        'use_featured_image' => __('Use as featured image', 'sb-wc-rma'),
        'insert_into_item' => __('Insert into RMA', 'sb-wc-rma'),
        'uploaded_to_this_item' => __('Uploaded to this RMA', 'sb-wc-rma'),
        'items_list' => __('RMAs list', 'sb-wc-rma'),
        'items_list_navigation' => __('RMAs list navigation', 'sb-wc-rma'),
        'filter_items_list' => __('Filter RMAs list', 'sb-wc-rma'),
    );
    $args = array(
        'label' => __('RMA', 'sb-wc-rma'),
        'description' => __('Custom post type for log RMA data', 'sb-wc-rma'),
        'labels' => $labels,
        'menu_icon' => '',
        'supports' => array('revisions'),
        'taxonomies' => array(),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => 'edit.php?post_type=product',
        'menu_position' => 50,
        'show_in_admin_bar' => false,
        'show_in_nav_menus' => false,
        'can_export' => true,
        'has_archive' => true,
        'hierarchical' => false,
        'exclude_from_search' => false,
        'show_in_rest' => false,
        'publicly_queryable' => false,
        'capability_type' => 'post',
        'register_meta_box_cb' => 'rma_data_metabox',
    );
    register_post_type('rma', $args);
}
add_action('init', 'create_rma_cpt', 0);
