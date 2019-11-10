<?php
/**
 * Say Hey functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package SayHey
 */

// View posts query SQL...
/*
 function my_posts_request_filter( $input ) {
 	print_r( $input );
 	return $input;
 }
 add_filter( 'posts_request', 'my_posts_request_filter' );
*/

require get_theme_file_path('/inc/legacy-redirects.php');
require get_theme_file_path('/inc/artwork-route.php');
require get_theme_file_path('/inc/search-route.php');
require get_theme_file_path('/inc/acf-register-blocks.php');

function custom_title($title_parts) {

  $pagePath = parse_url( $_SERVER['REQUEST_URI'] );

  if (is_404() && $pagePath['path'] == '/artwork/gallery') {
    $title_parts['title'] = "Gallery of Work";
  }
  return $title_parts;
}
add_filter( 'document_title_parts', 'custom_title' );

 if ( ! function_exists( 'sayhey_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function sayhey_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Say Hey, use a find and replace
		 * to change 'sayhey' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'sayhey', get_template_directory() . '/languages' );

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
			'header' => esc_html__( 'Primary', 'sayhey' ),
		) );

    add_image_size('admin-preview', 55, 55, true); // (name, width, height, crop?)
    add_image_size('cpt-thumb', 400, 225, true, array('left','top')); // (name, width, height, crop? - near center of photo)
    add_image_size('gallery-category', 400, 400, true); // (name, width, height, crop?)
    add_image_size('page-banner', 1500, 350, true); // (name, width, height, crop?)
    // add_image_size('gallery-thumb', 200, 200, true); // REMOVED 6/23, redundant after changing native thumbnail size

    add_image_size('medium-small', 500, 500, false); // In case full view of art is wanted in a thumb (no crop)

    update_option('medium_large_size_w', 1500);
    update_option('medium_large_size_h', 1500);
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
		add_theme_support( 'custom-background', apply_filters( 'sayhey_custom_background_args', array(
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
add_action( 'after_setup_theme', 'sayhey_setup' );

/*
***** SET PAGE BANNER ********
*/
require get_theme_file_path('/inc/page-banner.php');

/*
***** Function to output gallery thumbnails ********
*/
require get_theme_file_path('/inc/gallery-thumbs-output.php');

/*
***** CUSTOMIZE LOGIN SCREEN ********
*/
require get_theme_file_path('/inc/customize-login.php');


/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function sayhey_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'sayhey_content_width', 640 );
}
add_action( 'after_setup_theme', 'sayhey_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function sayhey_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'sayhey' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'sayhey' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'sayhey_widgets_init' );


function sayhey_custom_wp_admin_style(){
    wp_register_style( 'custom_wp_admin_css', get_theme_file_uri('/styles/admin-style.css'), false, microtime() );
    wp_enqueue_style( 'custom_wp_admin_css' );
}
add_action('admin_enqueue_scripts', 'sayhey_custom_wp_admin_style');

/**
 * Enqueue scripts and styles.
 */
function sayhey_scripts() {

  //wp_enqueue_style('mcptc_main_styles', get_stylesheet_uri(), NULL, microtime());
  wp_enqueue_style( 'sayhey-style', get_stylesheet_uri(), NULL, microtime());
  wp_enqueue_style( 'sayhey-font-roboto', 'https://fonts.googleapis.com/css?family=Merriweather:300,400|Roboto:300,400,700|Oswald:300|Libre+Baskerville');
  wp_enqueue_style( 'sayhey-font-arimo', 'https://fonts.googleapis.com/css?family=Arimo:400,700|Cinzel:400,700|Crimson+Text:400,700');
  //wp_enqueue_style( 'sayhey-font-awesome', 'https://use.fontawesome.com/releases/v5.8.2/css/all.css');

/*
  wp_enqueue_script( 'sayhey-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );
*/

/*
  For use with borrowed 2017 theme JS
*/

  wp_enqueue_script( 'sayhey-navigation', get_template_directory_uri() . '/js/navigation.js', array('jquery'), '20151215', true );

  wp_localize_script( 'sayhey-navigation', 'sayheyScreenReaderText', array(
      'expand' => __('Expand child menu', 'sayhey'),
      'collapse' => __('Collapse child menu', 'sayhey')
  ));


/*
  if ( has_nav_menu( 'top' ) ) {
		wp_enqueue_script( 'sayhey-navigation', get_theme_file_uri( '/assets/js/navigation.js' ), array( 'jquery' ), '1.0', true );
		$sayhey_l10n['expand']   = __( 'Expand child menu', 'sayhey' );
		$sayhey_l10n['collapse'] = __( 'Collapse child menu', 'sayhey' );
		$sayhey_l10n['icon']     = sayhey_get_svg(
			array(
				'icon'     => 'angle-down',
				'fallback' => true,
			)
		);
	}
*/

	wp_enqueue_script( 'sayhey-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

  // See https://wordpress.stackexchange.com/questions/189310/how-to-remove-default-jquery-and-add-js-in-footer
  // Goal is to prevent WP from loading default JQ version for *non-admin* pages (e.g. to use fancy box)

  	if ( !is_admin() ) {

  	   wp_deregister_script('jquery');
  	   wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js', false, null);
  	   wp_enqueue_script('jquery');

       //wp_enqueue_style( 'fancybox-style', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css', array(), '3.3.5' );
       //wp_enqueue_script( 'fancybox-js', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js', array(), '3.3.5', false );

       wp_enqueue_style( 'fancybox-style', 'https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css', array(), '3.5.7' );
       wp_enqueue_script( 'fancybox-js', 'https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js', array(), '3.5.7', false );

       wp_enqueue_script( 'fontawesome-js', 'https://kit.fontawesome.com/78e7cb0f98.js', array(), '5.8.2', false );

       wp_enqueue_script('sayhey-search-js', get_theme_file_uri('/js/search.js'), NULL, microtime(), true);

       wp_enqueue_script('sayhey-artwork-filter-js', get_theme_file_uri('/js/artwork-filter.js'), NULL, microtime(), true);

       wp_localize_script('sayhey-search-js', 'sayHeyData', array(
        'root_url' => get_site_url()
      ));
     }

}

add_action( 'wp_enqueue_scripts', 'sayhey_scripts' );

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
/*
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}
*/


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
        $relatedCaption .= '<a href=\'' . get_permalink($story->ID) . '\'>' . get_the_title($story->ID) . '</a> ';
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
        $relatedCaption .= '<a href=\'' . get_permalink($process->ID) . '\'>' . get_the_title($process->ID) . '</a> ';
      }
    }

  }

  return $relatedCaption;
}

/* Disable WordPress Admin Bar for all users but admins. */

show_admin_bar(false);

/*
Make admin interface more useful by showing featured image thumbnails in post listings
*/

  // GET FEATURED IMAGE
  function ST4_get_featured_image($post_ID) {
      $post_thumbnail_id = get_post_thumbnail_id($post_ID);
      if ($post_thumbnail_id) {
          $post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'admin-preview');
          return $post_thumbnail_img[0];
      }
  }

  // ADD NEW COLUMN
  function ST4_columns_head($defaults) {
      $defaults['featured_image'] = 'Featured Image';
      return $defaults;
  }

  // SHOW THE FEATURED IMAGE
  function ST4_columns_content($column_name, $post_ID) {
      if ($column_name == 'featured_image') {
          $post_featured_image = ST4_get_featured_image($post_ID);
          if ($post_featured_image) {
              echo '<img src="' . $post_featured_image . '" />';
          }
      }
  }

  add_filter('manage_artwork_posts_columns', 'ST4_columns_head');
  add_action('manage_artwork_posts_custom_column', 'ST4_columns_content', 10, 2);

  add_filter('manage_spin_posts_columns', 'ST4_columns_head');
  add_action('manage_spin_posts_custom_column', 'ST4_columns_content', 10, 2);

/*
END - Show featured image thumbnails in admin post listings
*/



/**
 * Display a custom taxonomy dropdown in admin
 * @author Mike Hemberger
 * @link http://thestizmedia.com/custom-post-type-filter-admin-custom-taxonomy/
 */

  add_action('restrict_manage_posts', 'tsm_filter_post_type_by_taxonomy');

  function tsm_filter_post_type_by_taxonomy() {
  	global $typenow;
  	$post_type = 'artwork';
  	$taxonomy  = 'gallery';
  	if ($typenow == $post_type) {
  		$selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
  		$info_taxonomy = get_taxonomy($taxonomy);

  		wp_dropdown_categories(array(
  			'show_option_all' => __("{$info_taxonomy->labels->all_items}"),
  			'taxonomy'        => $taxonomy,
  			'name'            => $taxonomy,
  			'orderby'         => 'name',
  			'selected'        => $selected,
  			'show_count'      => false,
  			'hide_empty'      => false,
  		));
  	};
  }

  /**
   * Filter posts by taxonomy in admin
   * @author  Mike Hemberger
   * @link http://thestizmedia.com/custom-post-type-filter-admin-custom-taxonomy/
   */
  add_filter('parse_query', 'tsm_convert_id_to_term_in_query');

  function tsm_convert_id_to_term_in_query($query) {
  	global $pagenow;
  	$post_type = 'artwork';
  	$taxonomy  = 'gallery';
  	$q_vars    = &$query->query_vars;
  	if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0 ) {
  		$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
  		$q_vars[$taxonomy] = $term->slug;
  	}
  }


  /*
  * separate media categories from post categories
  * use a custom category called ‘category_media’ for the categories in the media library
  */
  add_filter('wpmediacategory_taxonomy', 'define_media_category');

  function define_media_category() {
    return 'category_media';
  }

  function sayhey_theme_archive_title( $title ) {
      if ( is_category() ) {
          $title = single_cat_title( '', false );
      } elseif ( is_tag() ) {
          $title = single_tag_title( '', false );
      } elseif ( is_author() ) {
          $title = '<span class="vcard">' . get_the_author() . '</span>';
      } elseif ( is_post_type_archive() ) {
          $title = post_type_archive_title( 'All ', false );
      } elseif ( is_tax() ) {
          $title = single_term_title( '', false );
      }
      return $title;
  }
  add_filter( 'get_the_archive_title', 'sayhey_theme_archive_title' );

  /**
   * SVG icons functions and filters from 2017.
   */
  //require get_parent_theme_file_path( '/inc/icon-functions.php' );
