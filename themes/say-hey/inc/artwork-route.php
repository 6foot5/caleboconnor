<?php

add_action('rest_api_init', 'sayheyRegisterArtwork');

function sayheyRegisterArtwork() {
  register_rest_route('sayhey/v1', 'works', array(
    'methods' => WP_REST_SERVER::READABLE,
    'callback' => 'sayheyArtworkResults'
  ));
}

function sayheyArtworkResults($data) {

  $mainQuery = new WP_Query(array(
    'posts_per_page'   => -1,
    'post_type' => array('artwork')
  ));

  $results = array(
    'workInfo' => array()
  );

  while($mainQuery->have_posts()) {

    $mainQuery->the_post();

    $detailImages = get_field('detail_images');
    $detailImageIDs = array();

    foreach($detailImages as $image) {
      array_push($detailImageIDs, $image->ID);
    }

    $workTags = wp_get_post_tags(get_the_id());
    $workTagInfo = array();

    foreach($workTags as $thisTag) {
      array_push($workTagInfo, array(
        'tagID' => $thisTag->term_id,
        'tagName' => $thisTag->name
      ));
    }

    $workCats = wp_get_object_terms(get_the_id(), 'gallery');

    $workCatInfo = array();

    foreach($workCats as $thisCat) {
      array_push($workCatInfo, array(
        'tagID' => $thisCat->term_id,
        'tagName' => $thisCat->name
      ));
    }

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
        'storyTitle' => get_the_title($story->ID)
      ));
    }

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
        'processTitle' => get_the_title($process->ID)
      ));
    }

    array_push($results['workInfo'], array(
      'title' => get_the_title(),
      'description' => get_the_content(),
      'featuredImage' => get_post_thumbnail_id(),
      'medium' => get_field('artwork_medium'),
      'size' => get_field('artwork_size'),
      'date' => get_the_date('Y'),
      'detailImages' => $detailImageIDs,
      'tags' => $workTagInfo,
      'categories' => $workCatInfo,
      'spinID' => get_field('related_spin')->ID,
      'stories' => $workStoryInfo,
      'processes' => $workProcessInfo
    ));



  }

  return $results;

}
