<?php

/*
--------------------------------------------------------------------------------
  REST route registration
--------------------------------------------------------------------------------
*/
add_action('rest_api_init', 'sayheyRegisterArtwork');

function sayheyRegisterArtwork() {
  register_rest_route('sayhey/v1', 'artwork', array(
    'methods' => WP_REST_SERVER::READABLE,
    'callback' => 'sayheyArtworkResults'
  ));
}
/*
--------------------------------------------------------------------------------
*/


/*
--------------------------------------------------------------------------------
  Function to pull data that populates the REST route
--------------------------------------------------------------------------------
*/
function sayheyArtworkResults($wpData) {

  $mainQuery = new WP_Query(array(
    'posts_per_page' => -1,       // Get all the artwork
    'post_type' => array('artwork'),
    'order' => 'ASC',
    'orderby' => 'title'  // default sort is by title
  ));

  $results = array(); // initialize the array of results

  // This defines an array of all image sizes; will be used by each artwork
  // to retrieve image URLs
  $allImageSizes = get_intermediate_image_sizes();
  array_push($allImageSizes, 'full');

  // Kick off the main query, spin over all published artwork posts
  while($mainQuery->have_posts()) {

    $mainQuery->the_post();
/*
--------------------------------------------------------------------------------
  Get the detail images, if any
--------------------------------------------------------------------------------
*/
    $detailImagesFound = get_field('detail_images');
    $detailImageInfo = array();

    if( is_array($detailImagesFound) ) {

      foreach($detailImagesFound as $image) {

        $detailPermalinks = array();

        foreach($allImageSizes as $thisSize) {
          $detailPermalinks[$thisSize] = wp_get_attachment_image_src($image->ID, $thisSize)[0];
        }

        //$detailPermalinks['full'] = wp_get_attachment_image_src($image->ID, 'full')[0];

        array_push($detailImageInfo, array(
          'imageID' => $image->ID,
          'imageSizes' => $detailPermalinks
        ));

        // empty out the array for next detail image's permalinks
        unset($detailPermalinks);
      }
    }


/*
--------------------------------------------------------------------------------
  Get the associated tags, if any
--------------------------------------------------------------------------------
*/
    $workTags = wp_get_post_tags(get_the_id());
    $workTagInfo = array();

    foreach($workTags as $thisTag) {
      array_push($workTagInfo, array(
        'tagID' => $thisTag->term_id,
        'tagName' => $thisTag->name,
        'taxonomy' => $thisTag->taxonomy,
        'permalink' => get_term_link($thisTag->term_id)
      ));
    }

/*
--------------------------------------------------------------------------------
  Get the associated categories, if any
--------------------------------------------------------------------------------
*/
    $workCats = wp_get_object_terms(get_the_id(), 'gallery');
    $workCatInfo = array();

    foreach($workCats as $thisCat) {
      array_push($workCatInfo, array(
        'catID' => $thisCat->term_id,
        'catName' => $thisCat->name,
        'taxonomy' => $thisCat->taxonomy,
        'permalink' => get_term_link($thisCat->term_id)
      ));
    }

/*
--------------------------------------------------------------------------------
  Get the featured image permalinks, all sizes
--------------------------------------------------------------------------------
*/
    $imagePermalinks = array();

    foreach($allImageSizes as $thisSize) {
      $imagePermalinks[$thisSize] = get_the_post_thumbnail_url(get_the_ID(), $thisSize);
    }
    //$imagePermalinks['full'] = get_the_post_thumbnail_url(get_the_ID(), 'full');

/*
--------------------------------------------------------------------------------
  Get related stories, if any
--------------------------------------------------------------------------------
*/
    $relatedStories = get_posts(array(
      'post_type' => 'story',
      'meta_query' => array(
        array(
          'key' => 'related_artwork',
          'value' => '"' . get_the_id() . '"',
          'compare' => 'LIKE'
        )
      )
    ));

    $workStoryInfo = array();

    foreach($relatedStories as $story) {
      array_push($workStoryInfo, array(
        'storyID' => $story->ID,
        'storyTitle' => get_the_title($story->ID),
        'permalink' => get_permalink($story->ID)
      ));
    }

/*
--------------------------------------------------------------------------------
  Get related processes, if any
--------------------------------------------------------------------------------
*/
    $relatedProcesses = get_posts(array(
      'post_type' => 'process',
      'meta_query' => array(
        array(
          'key' => 'related_artwork',
          'value' => '"' . get_the_id() . '"',
          'compare' => 'LIKE'
        )
      )
    ));

    $workProcessInfo = array();

    foreach($relatedProcesses as $process) {
      array_push($workProcessInfo, array(
        'processID' => $process->ID,
        'processTitle' => get_the_title($process->ID),
        'permalink' => get_permalink($process->ID)
      ));
    }

/*
--------------------------------------------------------------------------------
  Fill results array with all artwork info
--------------------------------------------------------------------------------
*/
    array_push($results, array(
      'ID' => get_the_id(),
      'permalink' => get_the_permalink(),
      'title' => get_the_title(),
      'description' => get_field('artwork_description'),
      'featuredImage' => get_post_thumbnail_id(),
      'medium' => get_field('artwork_medium'),
      'size' => get_field('artwork_size'),
      'date' => get_the_date('Y'),
      'imageSrc' => $imagePermalinks,
      'detailImages' => $detailImageInfo,
      'tags' => $workTagInfo,
      'categories' => $workCatInfo,
      'spinID' => get_field('related_spin')->ID,
      'stories' => $workStoryInfo,
      'processes' => $workProcessInfo
    ));

  }

  return $results;
}
