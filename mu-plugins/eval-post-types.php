<?php

function sh_tax() {

//function sh_custom_taxonomies() {

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
		    'show_admin_column' => true,
        'show_tagcloud' => false,
        'show_admin_column' => true,
        'hierarchical' => true,
        'rewrite' => array(
        	'slug' => 'artwork/gallery' ,
        	'with_front' => false
        ),
        'query_var' => true
    );

    register_taxonomy( 'gallery', 'artwork', $galleryTaxArgs );

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
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
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
        'rewrite' => array( 'slug' => 'artwork', 'with_front' => false),
        'capability_type' => 'post',
        'taxonomies' => array('gallery'),
        'menu_icon' => 'dashicons-art',
        'menu_position' => 5
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
        'capability_type' => 'post',
        'taxonomies' => array('categories'),
        'menu_icon' => 'dashicons-book',
        'menu_position' => 7

    );



  // Process post type


    $processLabels = array(
        'name' => _x( 'Processes', 'sayhey' ),
        'singular_name' => _x( 'Process', 'sayhey' ),
        'all_items' => _x( 'All Processes', 'sayhey' ),
        'add_new' => _x( 'Add New Process', 'sayhey' ),
        'add_new_item' => _x( 'Add New Process', 'sayhey' ),
        'edit_item' => _x( 'Edit Process', 'sayhey' ),
        'new_item' => _x( 'New Process', 'sayhey' ),
        'view_item' => _x( 'View Process', 'sayhey' ),
        'search_items' => _x( 'Search Processes', 'sayhey' ),
        'not_found' => _x( 'No Processes found', 'sayhey' ),
        'not_found_in_trash' => _x( 'No Processes found in Trash', 'sayhey' ),
        'menu_name' => _x( 'Processes', 'sayhey' ),
    );

    $processArgs = array(
        'labels' => $processLabels,
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
        'capability_type' => 'post',
        'taxonomies' => array('categories'),
        'menu_icon' => 'dashicons-lightbulb',
        'menu_position' => 8
    );


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
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
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
        'capability_type' => 'post',
        'taxonomies' => array('categories'),
        'menu_icon' => 'dashicons-image-rotate',
        'menu_position' => 6
    );



	register_post_type('artwork', $artworkArgs);
	register_post_type('story', $storyArgs);
  register_post_type('process', $processArgs);
  register_post_type('spin', $spinArgs);

}

add_action('init', 'sh_tax');
add_action('init', 'sh_cpt');

//add_action('init', 'sh_custom_taxonomies' );


// picked up the below function from https://codeable.io/get-your-custom-taxonomy-urls-in-order/

/*
function generate_taxonomy_rewrite_rules( $wp_rewrite ) {

  $rules = array();
  $post_types = get_post_types( array( 'name' => 'artwork', 'public' => true, '_builtin' => false ), 'objects' );
  $taxonomies = get_taxonomies( array( 'name' => 'gallery', 'public' => true, '_builtin' => false ), 'objects' );

  foreach ( $post_types as $post_type ) {

    $post_type_name = $post_type->name; // 'artwork'
    $post_type_slug = $post_type->rewrite['slug']; // 'artwork'

    foreach ( $taxonomies as $taxonomy ) {

      if ( $taxonomy->object_type[0] == $post_type_name ) {

        $terms = get_categories( array( 'type' => $post_type_name, 'taxonomy' => $taxonomy->name, 'hide_empty' => 0 ) );

        foreach ( $terms as $term ) {
          $rules[$post_type_slug . '/' . $term->slug . '/?$'] = 'index.php?' . $term->taxonomy . '=' . $term->slug;
        }
      }
    }
  }

  $wp_rewrite->rules = $rules + $wp_rewrite->rules;

}

add_action('generate_rewrite_rules', 'generate_taxonomy_rewrite_rules');

*/
