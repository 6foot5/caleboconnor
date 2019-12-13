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
    'callback' => 'sayheyArtworkResults',
    'args' => array(
      'id' => array(
        'validate_callback' => function($param, $request, $key) {
            return is_numeric($param);
        }
      )
    )
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

  $args = array(
    'posts_per_page' => -1,       // Get all the artwork
    'post_type' => array('artwork'),
    'order' => 'ASC',
    'orderby' => 'title'  // default sort is by title
  );

  if ($wpData['per_page']) {
    $args['posts_per_page'] = $wpData['per_page'];
  }

  if ($wpData['id']) {
    $args['p'] = $wpData['id'];
  }

  if ($wpData['ids']) {
    $args['post__in'] = $wpData['ids'];
  }

  if ($wpData['tax_query']) {
    $args['tax_query'] = $wpData['tax_query'];
  }

  /*
  EXAMPLE tax_query argument
  -----------------------------
    $argsREST['tax_query'] = array(
    array(
      'taxonomy' => 'post_tag',
      'field' => 'slug',
      'terms' => 'fishing'
    )
  );
  */

  //print_r($args); echo '<br />';

  $mainQuery = new WP_Query($args);

  $results = array(); // initialize the array of results
  $resultsLocalized = array(); // initialize the array of IDs and CSS selectors to localize

  // This defines an array of all image size keywords registered by theme;
  // will be used by each artwork to retrieve image URLs
  $allImageSizes = get_intermediate_image_sizes();
  array_push($allImageSizes, 'full');

  // Kick off the main query, spin through all published artwork posts
  while($mainQuery->have_posts()) {

    $mainQuery->the_post();

    $workSelectors = ''; // CSS selectors to reflect various artwork properties
/*
--------------------------------------------------------------------------------
  Get the detail images, if any
--------------------------------------------------------------------------------
*/
    $detailImagesFound = get_field('detail_images');
    $detailImageInfo = array();

    /*
      Selector taxonomy (for targeting/filtering client-side in JavaScript):
      ----------------------------------------------------------------------
        'year-[value]'
        'medium-[value]'
        'tag-[slug]'
        'category-[slug]'
        'has-spin'
        'has-story'
        'has-process'
    */

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
        'ID' => $thisTag->term_id,
        'name' => $thisTag->name,
        'slug' => $thisTag->slug,
        'taxonomy' => $thisTag->taxonomy,
        'permalink' => get_term_link($thisTag->term_id)
      ));

      $workSelectors .= ' tag-' . $thisTag->slug;
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
        'ID' => $thisCat->term_id,
        'name' => $thisCat->name,
        'slug' => $thisCat->slug,
        'taxonomy' => $thisCat->taxonomy,
        'permalink' => get_term_link($thisCat->term_id)
      ));

      $workSelectors .= ' category-' . $thisCat->slug;
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

    if ($relatedStories) {
      $workSelectors .= ' has-story';
    }

    $workStoryInfo = array();

    foreach($relatedStories as $story) {
      array_push($workStoryInfo, array(
        'ID' => $story->ID,
        'title' => get_the_title($story->ID),
        'permalink' => get_permalink($story->ID),
        'excerpt' => $story->post_excerpt
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

    if ($relatedProcesses) {
      $workSelectors .= ' has-process';
    }

    $workProcessInfo = array();

    foreach($relatedProcesses as $process) {
      array_push($workProcessInfo, array(
        'ID' => $process->ID,
        'title' => get_the_title($process->ID),
        'permalink' => get_permalink($process->ID),
        'excerpt' => $process->post_excerpt
      ));
    }

/*
--------------------------------------------------------------------------------
  Fill results array with all artwork info
--------------------------------------------------------------------------------
*/
    $thisSpinID = get_field('related_spin')->ID;
    // Once spins are a thing, fill out value as an array with permalink, etc.

    $thisYear = get_field('artwork_year');
    $thisMedium = get_field('artwork_medium');

    if ($thisYear) {
      $workSelectors .= ' year-' . $thisYear;
    }

    if ($thisSpinID) {
      $workSelectors .= ' has-spin';
    }

    if ($thisMedium) {
      $workSelectors .= ' medium-' . $thisMedium['value'];
    }

    $workSelectors .= ' artwork-' . get_the_id();

    array_push($results, array(
      'ID' => get_the_id(),
      'permalink' => get_the_permalink(),
      'title' => get_the_title(),
      'description' => get_field('artwork_description'),
      'featuredImage' => get_post_thumbnail_id(),
      'medium' => $thisMedium,
      'size' => get_field('artwork_size'),
      'year' => $thisYear,
      'location' => get_field('artwork_location'),
      'imageSrc' => $imagePermalinks,
      'detailImages' => $detailImageInfo,
      'tags' => $workTagInfo,
      'categories' => $workCatInfo,
      'spinID' => $thisSpinID,
      'stories' => $workStoryInfo,
      'processes' => $workProcessInfo,
      'selectors' => $workSelectors
    ));

    array_push($resultsLocalized, array(
      'artworkID' => get_the_id(),
      'selectors' => $workSelectors
    ));

  }

  if ($wpData['localize_results']) {
    wp_localize_script('sayhey-artwork-filter-js', 'sayHeyArtworkFilter', $resultsLocalized);
  }

  return $results;
}
