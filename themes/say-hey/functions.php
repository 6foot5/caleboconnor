<?php
/**
 * Say Hey functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Say_Hey
 */

// View posts query SQL...
/*
 function my_posts_request_filter( $input ) {
 	print_r( $input );
 	return $input;
 }
 add_filter( 'posts_request', 'my_posts_request_filter' );
*/

 if ( ! function_exists( 'say_hey_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function say_hey_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Say Hey, use a find and replace
		 * to change 'say-hey' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'say-hey', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'say-hey' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'say_hey_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'say_hey_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function say_hey_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'say_hey_content_width', 640 );
}
add_action( 'after_setup_theme', 'say_hey_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function say_hey_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'say-hey' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'say-hey' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'say_hey_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function say_hey_scripts() {

	wp_enqueue_style( 'say-hey-style', get_stylesheet_uri() );

	wp_enqueue_script( 'say-hey-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'say-hey-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

  // See https://wordpress.stackexchange.com/questions/189310/how-to-remove-default-jquery-and-add-js-in-footer
  // Goal is to prevent WP from loading default JQ version for *non-admin* pages (e.g. to use fancy box)
  	if ( !is_admin() ) {

  	   wp_deregister_script('jquery');
  	   wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js', false, null);
  	   wp_enqueue_script('jquery');

  	   wp_enqueue_style( 'fancybox-style', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css', array(), '3.3.5' );

  	   wp_enqueue_script( 'fancybox-js', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js', array(), '3.3.5', false );
     }

}

add_action( 'wp_enqueue_scripts', 'say_hey_scripts' );
/*
function say_hey_subcategory_hierarchy() {

    $category = get_queried_object();

    $temp = $category;

    do {
        $parent_id = $temp->category_parent;
        $temp = get_category( $parent_id );
    } while ( $temp->category_parent );

    $templates = array();

    if ( $parent_id == 0 ) {
        // Use default values from get_category_template()
        $templates[] = "category-{$category->slug}.php";
        $templates[] = "category-{$category->term_id}.php";
        $templates[] = 'category.php';
    } else {
        // Create replacement $templates array
        $parent = get_category( $parent_id );

        // Current first
        $templates[] = "category-{$category->slug}.php";
        $templates[] = "category-{$category->term_id}.php";

        // Parent second
        $templates[] = "category-{$parent->slug}.php";
        $templates[] = "category-{$parent->term_id}.php";
        $templates[] = 'category.php';
    }
    return locate_template( $templates );
}

add_filter( 'category_template', 'say_hey_subcategory_hierarchy' );
*/

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

function artworkCaptioner($workID = 0, $relatedCaption = '', $args = NULL) {

// Get 360-degree spin for specified artwork ID

  if ($args['get_spin']) {

    $relatedSpin = get_field('related_spin', $workID);

    if ($relatedSpin) {

      //$theSpin = $relatedSpin->get_field('related_spin');

      if ($relatedSpin) {
        $relatedCaption .= ' | ' . '<a data-fancybox data-type=\'iframe\' href=\'' . get_permalink($relatedSpin->ID) . '\'>View 360-degree Spin</a>';
      }
    }

  }

  // Get related stories for specified artwork ID

  if ($args['get_stories']) {

    $relatedStories = get_posts(array(
                  'post_type' => 'story',
                  'meta_query' => array(
                    array(
                      'key' => 'related_artwork', // name of custom field
                      'value' => '"' . $workID . '"', // matches exactly "123", not just 123. This prevents a match for "1234"
                      'compare' => 'LIKE'
                    )
                  )
                ));

    if ($relatedStories) {

      $relatedCaption .= '<br />Related Stories: ';

      foreach($relatedStories as $story) {
        $relatedCaption .= '<a href=\'' . get_permalink($story->ID) . '\'>+' . get_the_title($story->ID) . '+</a> ';
      }
    }

  }

  // Get related processes for specified artwork ID

  if ($args['get_processes']) {

    $relatedProcess = get_posts(array(
                  'post_type' => 'process',
                  'meta_query' => array(
                    array(
                      'key' => 'related_artwork', // name of custom field
                      'value' => '"' . $workID . '"', // matches exactly "123", not just 123. This prevents a match for "1234"
                      'compare' => 'LIKE'
                    )
                  )
                ));

    if ($relatedProcess) {

      $relatedCaption .= '<br />Related Processes: ';

      foreach($relatedProcess as $process) {
        $relatedCaption .= '<a href=\'' . get_permalink($process->ID) . '\'>+' . get_the_title($process->ID) . '+</a> ';
      }
    }

  }

  return $relatedCaption;
}
