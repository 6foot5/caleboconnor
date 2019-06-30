<?php

function register_acf_block_types() {

    // register a testimonial block.
    acf_register_block_type(array(
        'name'              => 'press',
        'title'             => __('Press Coverage'),
        'description'       => __('A block to capture press coverage details.'),
        'render_template'   => 'template-parts/blocks/press/press.php',
        'category'          => 'common',
        'icon'              => 'admin-site-alt3',
        'keywords'          => array( 'press', 'news' ),
    ));
}

// Check if function exists and hook into setup.
if( function_exists('acf_register_block_type') ) {
    add_action('acf/init', 'register_acf_block_types');
}
