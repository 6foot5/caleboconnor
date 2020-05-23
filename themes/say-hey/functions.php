<?php
/**
 * Say Hey functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package SayHey
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

    // add_image_size('medium-square', 500, 500, true);

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
 * Adds custom image sizes to media library insertion option
 * May not be super useful for basic post editing... we'll see
 */
add_filter( 'image_size_names_choose', 'my_custom_sizes' );

function my_custom_sizes( $sizes ) {
    return array_merge( $sizes, array(
      'medium_large' => __('Medium Large'),
      'medium-small' => __('Medium Small'),
    ) );
}

/*
 * Using ACF to define a custom site settings page.
 */
$settingsArgs = array(
  'page_title' => __('Website Options'),
  'menu_title' => __('Website Options'),
  'menu_slug' => 'sayhey-options',
  'capability' => 'edit_artworks',
  'update_button' => __('Update Options', 'acf'),
  'updated_message' => __("Website Options Updated", 'acf')
);

if( function_exists('acf_add_options_page') ) {
  acf_add_options_page( $settingsArgs );
}

/*
***** SET PAGE BANNER ********
*/
require get_theme_file_path('/inc/page-banner.php');

/*
***** Function to output gallery thumbnails ********
*/
require get_theme_file_path('/inc/gallery-thumbs-output.php');

/*
***** Function to generate captions for artwork in lightbox view ********
*/
require get_theme_file_path('/inc/artwork-captioner.php');

/*
***** Function to output CPT cards - story, process, etc ********
*/
require get_theme_file_path('/inc/cpt-cards-output.php');

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

  // Use microtime to bust cache in dev cycles
  // wp_register_style( 'custom_wp_admin_css', get_theme_file_uri('/styles/admin-style.css'), false, microtime() );

  wp_register_style( 'custom_wp_admin_css', get_theme_file_uri('/styles/admin-style.css'), false, '1.0.0' );
  wp_enqueue_style( 'custom_wp_admin_css' );
}
add_action('admin_enqueue_scripts', 'sayhey_custom_wp_admin_style');

/**
 * Enqueue scripts and styles.
 */
function sayhey_scripts() {

  wp_enqueue_style( 'sayhey-style', get_stylesheet_uri(), NULL, '1.0.0');

  // Use microtime to bust cache in dev cycles
  // wp_enqueue_style( 'sayhey-style', get_stylesheet_uri(), NULL, microtime());

  wp_enqueue_style( 'font-awesome-style', get_theme_file_uri('/styles/fa/css/all.css'), NULL, '5.13.0');
  wp_enqueue_style( 'sayhey-font-roboto', 'https://fonts.googleapis.com/css?family=Merriweather:300,400|Roboto:300,400,700');

	// if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
	// 	wp_enqueue_script( 'comment-reply' );
	// }

  // See https://wordpress.stackexchange.com/questions/189310/how-to-remove-default-jquery-and-add-js-in-footer
  // Goal is to prevent WP from loading default JQ version for *non-admin* pages (e.g. to use fancy box)

  	if ( !is_admin() ) {

  	   wp_deregister_script('jquery');
  	   wp_register_script('jquery', get_theme_file_uri('/js/vendor/jquery.3.3.1.min.js'), false, null);
  	   wp_enqueue_script('jquery');

       // wp_enqueue_script( 'font-awesome-kit', 'https://kit.fontawesome.com/78e7cb0f98.js', [], null );
       //wp_enqueue_script( 'fontawesome-js', 'https://kit.fontawesome.com/78e7cb0f98.js', array(), '5.8.2', false );

       // Use microtime to bust cache in dev cycles
       // wp_enqueue_script( 'sayhey-vendor-bundle-js', get_theme_file_uri('/js/dist/vendor.js'), array('jquery'), microtime(), false );

       wp_enqueue_script( 'sayhey-vendor-bundle-js', get_theme_file_uri('/js/dist/vendor.js'), array('jquery'), '1.0.0', false );

       //wp_enqueue_script('sayhey-search-js', get_theme_file_uri('/js/search.js'), NULL, microtime(), true);

       // Use microtime to bust cache in dev cycles
       // wp_enqueue_script( 'sayhey-custom-bundle-js', get_theme_file_uri('/js/dist/custom.js'), array('sayhey-vendor-bundle-js'), microtime(), true );

       wp_enqueue_script( 'sayhey-custom-bundle-js', get_theme_file_uri('/js/dist/custom.js'), array('sayhey-vendor-bundle-js'), '1.0.0', true );

       // if ( is_post_type_archive('artwork') ) {
       //   wp_enqueue_script('sayhey-artwork-filter-js', get_theme_file_uri('/js/artwork-filter.js'), NULL, microtime(), true);
       // }

      wp_localize_script('sayhey-custom-bundle-js', 'sayHeyData', array(
        'root_url' => get_site_url()
      ));

      wp_localize_script( 'sayhey-custom-bundle-js', 'sayheyScreenReaderText', array(
          'expand' => __('Expand child menu', 'sayhey'),
          'collapse' => __('Collapse child menu', 'sayhey')
      ));

     }

}

add_action( 'wp_enqueue_scripts', 'sayhey_scripts' );

/* Disable WordPress Admin Bar for all users but admins. */
show_admin_bar(false);

/*
Make admin interface more useful by showing featured image thumbnails in post listings
*/

  // GET FEATURED IMAGE
  function sayhey_get_featured_image($post_ID) {
      $post_thumbnail_id = get_post_thumbnail_id($post_ID);
      if ($post_thumbnail_id) {
          $post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'admin-preview');
          return $post_thumbnail_img[0];
      }
  }

  // ADD NEW COLUMN
  function sayhey_columns_head($defaults) {
      $defaults['featured_image'] = 'Featured Image';
      return $defaults;
  }

  // SHOW THE FEATURED IMAGE
  function sayhey_columns_content($column_name, $post_ID) {
      if ($column_name == 'featured_image') {
          $post_featured_image = sayhey_get_featured_image($post_ID);
          if ($post_featured_image) {
              echo '<img src="' . $post_featured_image . '" />';
          }
      }
  }

  add_filter('manage_artwork_posts_columns', 'sayhey_columns_head');
  add_action('manage_artwork_posts_custom_column', 'sayhey_columns_content', 10, 2);

  add_filter('manage_spin_posts_columns', 'sayhey_columns_head');
  add_action('manage_spin_posts_custom_column', 'sayhey_columns_content', 10, 2);

/*
END - Show featured image thumbnails in admin post listings
*/



/**
 * Display a custom taxonomy dropdown in admin
 * @author Mike Hemberger
 * @link http://thestizmedia.com/custom-post-type-filter-admin-custom-taxonomy/
 */

  function sayhey_filter_post_type_by_taxonomy() {
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
  add_action('restrict_manage_posts', 'sayhey_filter_post_type_by_taxonomy');

  /**
   * Filter posts by taxonomy in admin
   * @author  Mike Hemberger
   * @link http://thestizmedia.com/custom-post-type-filter-admin-custom-taxonomy/
   */

  function sayhey_convert_id_to_term_in_query($query) {
  	global $pagenow;
  	$post_type = 'artwork';
  	$taxonomy  = 'gallery';
  	$q_vars    = &$query->query_vars;
  	if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0 ) {
  		$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
  		$q_vars[$taxonomy] = $term->slug;
  	}
  }
  add_filter('parse_query', 'sayhey_convert_id_to_term_in_query');

  // Remove dashboard widgets
  function sayhey_remove_dashboard_meta() {
  	if ( ! current_user_can( 'manage_options' ) ) {
  		remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
  		remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
  		remove_meta_box( 'dashboard_primary', 'dashboard', 'normal' );
  		remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
  		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
  		remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
  		remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
  		remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
  		remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');
  	}
  }
  add_action( 'admin_init', 'sayhey_remove_dashboard_meta' );

  // Sets admin dash "screen options" column options
  function sayhey_set_dashboard_columns() {
      add_screen_option(
          'layout_columns',
          array(
              'max'     => 1,
              'default' => 1
          )
      );
  }
  add_action( 'admin_head-index.php', 'sayhey_set_dashboard_columns' );
  /**
   * Add a widget to the dashboard for training videos
   * This function is hooked into the 'wp_dashboard_setup' action below.
   */
  function sayhey_add_dashboard_widgets() {
  	wp_add_dashboard_widget(
  		'sayhey_dashboard_training_widget', // Widget slug.
  		'Say Hey Wordpress Theme - Training Videos', // Title.
  		'sayhey_dashboard_training_widget_render' // Display function.
  	);
  }
  add_action( 'wp_dashboard_setup', 'sayhey_add_dashboard_widgets' );

  /**
   * Create the function to output the contents of your Dashboard Widget.
   */
  function sayhey_dashboard_training_widget_render() {
    // echo 'Placeholder';
    require get_theme_file_path('/inc/dashboard-training.php');
  }


  /**
  * Collapse ACF Repeater by default
  * https://wpster.com/collapse-advanced-custom-fields-repeater-by-default/
  */
  add_action('acf/input/admin_head', 'sayhey_acf_repeater_collapse');
  function sayhey_acf_repeater_collapse() {
  ?>
  <style id="sayhey-acf-repeater-collapse">.acf-repeater .acf-table {display:none;}</style>
  <script type="text/javascript">
       jQuery(function($) {
         // $('.acf-repeater .acf-row [data-id^='row-']').addClass('-collapsed');
            $("[data-id^='row-']").addClass('-collapsed');
            $('#sayhey-acf-repeater-collapse').detach();
       });
  </script>
  <?php
  }
