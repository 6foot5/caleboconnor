<?php
/*
Plugin Name: Say Hey Register Custom Post Types and Taxonomies
Plugin URI:
Description: Registers all post typesand taxonomies required for Say Hey theme
Version: 1.0
Author: Rick Barley
Author URI:
License: GPLv2 or later
*/

function sh_tax() {

     $galleryTaxLabels = array(
        'name' => _x( 'Gallery Category', 'sayhey' ),
        'singular_name' => _x( 'Gallery Category', 'sayhey' ),
        'search_items' => _x( 'Search Gallery Categories', 'sayhey' ),
        'popular_items' => _x( 'Popular Gallery Categories', 'sayhey' ),
        'all_items' => _x( 'All Gallery Categories', 'sayhey' ),
        'parent_item' => _x( 'Parent Gallery Category', 'sayhey' ),
        'parent_item_colon' => _x( 'Parent Gallery Category:', 'sayhey' ),
        'edit_item' => _x( 'Edit Gallery Category', 'sayhey' ),
        'update_item' => _x( 'Update Gallery Category', 'sayhey' ),
        'add_new_item' => _x( 'Add New Gallery Category', 'sayhey' ),
        'new_item_name' => _x( 'New Gallery Category', 'sayhey' ),
        'add_or_remove_items' => _x( 'Add or remove Gallery Categories', 'sayhey' ),
        'choose_from_most_used' => _x( 'Choose from most used Gallery Categories', 'sayhey' ),
        'menu_name' => _x( 'Gallery Categories', 'sayhey' )
    );

    $galleryTaxArgs = array(
        'labels' => $galleryTaxLabels,
        'public' => true,
        'show_in_nav_menus' => true,
		    'show_ui' => true,
        'show_in_rest' => true,
		    'show_admin_column' => true,
        'show_tagcloud' => false,
        'show_admin_column' => true,
        'hierarchical' => true,
        'capabilities' => array(
        	'manage_terms' => 'manage_gallery',
        	'edit_terms' => 'edit_gallery',
        	'delete_terms' => 'delete_gallery',
        	'assign_terms' => 'assign_gallery'
        ),
        'rewrite' => array(
        	'slug' => 'artwork/gallery' ,
          'hierarchical' => true,
        	'with_front' => false
        ),
        'query_var' => true
    );

    // Need to explicitly define the post_tag capabilities so that they can
    // be assigned to custom roles (e.g. using the Members plugin)
    // register_taxonomy is used to create or alter a taxonomy
    // https://codex.wordpress.org/Function_Reference/register_taxonomy

    $postTagTaxArgs = array(
        'capabilities' => array(
        	'manage_terms' => 'manage_post_tag',
        	'edit_terms' => 'edit_post_tag',
        	'delete_terms' => 'delete_post_tag',
        	'assign_terms' => 'assign_post_tag'
        ),
        'rewrite' => array(
        	'slug' => 'tag' ,
          'hierarchical' => false,
        	'with_front' => false
        ),
        'query_var' => true
    );

    register_taxonomy( 'gallery', 'artwork', $galleryTaxArgs );
    register_taxonomy( 'post_tag', 'artwork', $postTagTaxArgs );

}

function sh_cpt() {

  // Artwork post type

    $artworkLabels = array(
        'name' => _x( 'Artwork', 'sayhey' ),
        'singular_name' => _x( 'Work', 'sayhey' ),
        'all_items' => _x( 'All Artwork', 'sayhey' ),
        'add_new' => _x( 'Add New Work', 'sayhey' ),
        'add_new_item' => _x( 'Add New Work', 'sayhey' ),
        'edit_item' => _x( 'Edit Work', 'sayhey' ),
        'new_item' => _x( 'New Work', 'sayhey' ),
        'view_item' => _x( 'View Work', 'sayhey' ),
        'search_items' => _x( 'Search Artwork', 'sayhey' ),
        'not_found' => _x( 'No artwork found', 'sayhey' ),
        'not_found_in_trash' => _x( 'No artwork found in Trash', 'sayhey' ),
        'menu_name' => _x( 'Artwork', 'sayhey' )
    );

    $artworkArgs = array(
        'labels' => $artworkLabels,
        'hierarchical' => false,
        'supports' => array('title', 'thumbnail', 'excerpt'),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => array( 'slug' => 'artwork', 'with_front' => false),
        'capability_type' => 'artwork',
        'map_meta_cap' => true,
        'taxonomies' => array('gallery', 'post_tag'),
        'menu_icon' => 'dashicons-art',
        'menu_position' => 12
    );

  // Story post type

    $storyLabels = array(
        'name' => _x( 'Stories', 'sayhey' ),
        'singular_name' => _x( 'Story', 'sayhey' ),
        'all_items' => _x( 'All Stories', 'sayhey' ),
        'add_new' => _x( 'Add New Story', 'sayhey' ),
        'add_new_item' => _x( 'Add New Story', 'sayhey' ),
        'edit_item' => _x( 'Edit Story', 'sayhey' ),
        'new_item' => _x( 'New Story', 'sayhey' ),
        'view_item' => _x( 'View Story', 'sayhey' ),
        'search_items' => _x( 'Search Stories', 'sayhey' ),
        'not_found' => _x( 'No Stories found', 'sayhey' ),
        'not_found_in_trash' => _x( 'No Stories found in Trash', 'sayhey' ),
        'menu_name' => _x( 'Stories', 'sayhey' )
    );

    $storyArgs = array(
        'labels' => $storyLabels,
        'description' => 'Learn about the stories behind Caleb\'s artwork and the threads that tie pieces together.',
        'hierarchical' => false,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => array( 'slug' => 'stories'),
        'capability_type' => 'story',
        'map_meta_cap' => true,
        'taxonomies' => array('categories', 'post_tag'),
        'menu_icon' => 'dashicons-book',
        'menu_position' => 14

    );

  // Process post type

    $processLabels = array(
        'name' => _x( 'Processes', 'sayhey' ),
        'singular_name' => _x( 'Process', 'sayhey' ),
        'all_items' => _x( 'Caleb\'s Process', 'sayhey' ),
        'add_new' => _x( 'Add New Process', 'sayhey' ),
        'add_new_item' => _x( 'Add New Process', 'sayhey' ),
        'edit_item' => _x( 'Edit Process', 'sayhey' ),
        'new_item' => _x( 'New Process', 'sayhey' ),
        'view_item' => _x( 'View Process', 'sayhey' ),
        'search_items' => _x( 'Search Processes', 'sayhey' ),
        'not_found' => _x( 'No Processes found', 'sayhey' ),
        'not_found_in_trash' => _x( 'No Processes found in Trash', 'sayhey' ),
        'menu_name' => _x( 'Process', 'sayhey' ),
    );

    $processArgs = array(
        'labels' => $processLabels,
        'description' => 'Take a look inside Caleb\'s process and learn how his works are made.',
        'hierarchical' => false,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => array( 'slug' => 'process'),
        'capability_type' => 'process',
        'map_meta_cap' => true,
        'taxonomies' => array('categories', 'post_tag'),
        'menu_icon' => 'dashicons-lightbulb',
        'menu_position' => 16
    );

    // 360-degree spin post type

    $spinLabels = array(
        'name' => _x( 'Spins', 'sayhey' ),
        'singular_name' => _x( '360 Spin', 'sayhey' ),
        'all_items' => _x( 'All 360 Spins', 'sayhey' ),
        'add_new' => _x( 'Add New 360 Spin', 'sayhey' ),
        'add_new_item' => _x( 'Add New 360 Spin', 'sayhey' ),
        'edit_item' => _x( 'Edit 360 Spin', 'sayhey' ),
        'new_item' => _x( 'New 360 Spin', 'sayhey' ),
        'view_item' => _x( 'View 360 Spins', 'sayhey' ),
        'search_items' => _x( 'Search 360 Spins', 'sayhey' ),
        'not_found' => _x( 'No 360 Spins found', 'sayhey' ),
        'not_found_in_trash' => _x( 'No 360 Spins found in Trash', 'sayhey' ),
        'menu_name' => _x( '360 Spins', 'sayhey' ),
    );

    $spinArgs = array(
        'labels' => $spinLabels,
        'hierarchical' => false,
        'supports' => array('title', 'editor', 'thumbnail'),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => false,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => array( 'slug' => 'spins'),
        'capability_type' => 'spin',
        'map_meta_cap' => true,
        'taxonomies' => array('categories'),
        'menu_icon' => 'dashicons-image-rotate',
        'menu_position' => 18
    );

	register_post_type('artwork', $artworkArgs);
	register_post_type('story', $storyArgs);
  register_post_type('process', $processArgs);
  register_post_type('spin', $spinArgs);

}

add_action('init', 'sh_tax');
add_action('init', 'sh_cpt');
