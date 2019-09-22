<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package SayHey
 */

get_header();
?>


	<?php //pageBanner();	?>


	<div id="primary" class="content-area content-area--padded-sides content-area--bg-color">
		<main id="main" class="site-main">

<?php

  $mainQuery = new WP_Query(array(
    'posts_per_page' => -1,
    'post_type' => array('page', 'artwork', 'story', 'process'),
    's' => sanitize_text_field('baltimore')
  ));

	global $wpdb;
	echo $wpdb->last_query;

the_search_query();

  $results = array(
    'artworkInfo' => array(),
    'storyInfo' => array(),
    'processInfo' => array(),
    'generalInfo' => array()
  );

  while($mainQuery->have_posts()) {

    $mainQuery->the_post();

    if (get_post_type() == 'page') {
      array_push($results['generalInfo'], array(
        'postType' => get_post_type(),
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
        'thumbnail' => get_the_post_thumbnail_url()
      ));
    }

    if (get_post_type() == 'artwork') {
      array_push($results['artworkInfo'], array(
        'postType' => get_post_type(),
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
        'thumbnail' => get_the_post_thumbnail_url(get_the_id(),'admin-preview'),
        'medium' => get_field('artwork_medium'),
        'size' => get_field('artwork_size'),
        'date' => get_the_date('Y')
      ));
    }

    if (get_post_type() == 'story') {
      array_push($results['storyInfo'], array(
        'postType' => get_post_type(),
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
        'thumbnail' => get_the_post_thumbnail_url()
      ));
    }

    if (get_post_type() == 'process') {
      array_push($results['processInfo'], array(
        'postType' => get_post_type(),
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
        'thumbnail' => get_the_post_thumbnail_url()
      ));
    }

  }

  print_r($results);


			//get_template_part( 'template-parts/content', 'page' );


		?>


		</main><!-- #main -->
	</div><!-- #primary -->

<?php
//get_sidebar();
get_footer();
