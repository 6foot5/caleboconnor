<?php

add_action('rest_api_init', 'sayheyRegisterSearch');

function sayheyRegisterSearch() {
  register_rest_route('sayhey/v1', 'search', array(
    'methods' => WP_REST_SERVER::READABLE,
    'callback' => 'sayheySearchResults'
  ));
}

function sayheySearchResults($wpData) {

  /*
    IMPORTANT - Any query containing the "s" term (i.e. a search query) will
    be subject to the filters defined in the sayhey-search mu-plugin.
    This filters the search query to add join/where/groupby criteria for postmeta
    In this case, the $mainQuery will be filtered by the sayhey-search mu-plugin
  */

  $mainQuery = new WP_Query(array(
    'posts_per_page' => -1,
    'post_type' => array('page', 'artwork', 'story', 'process'),
    's' => sanitize_text_field($wpData['term']),
    'sayhey_search_route' => true
  ));

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

  } // end while loop over main query results

  wp_reset_postdata();

  /*
    The main search query has been processed.
    Below we're adding one more check for content that migt have been
    tagged (post_tag taxonomy) or categorized (gallery taxonomy) with a
    term matching the search criteria.

    [note: the right join criteria in the sayhey-search mu-plugin could
      include these same results in the main search query. That will
      be considered as a future enhancement]

      Check this stackoverflow:
      https://wordpress.stackexchange.com/questions/173377/how-to-use-filter-hook-posts-join-for-querying-taxonomy-terms-in-posts-where
  */

  $matchingTermIds = get_terms([
    'taxonomy' => array('post_tag','gallery'),
    'name__like' => sanitize_text_field($wpData['term']),
    'fields' => 'ids'
  ]);

  //print_r($matchingTermIds);

  $taxQuery = new WP_Query(array(
    'posts_per_page' => -1,
    'post_type' => array('page', 'artwork', 'story', 'process'),
    'tax_query' => array(
      'relation' => 'OR',
      array(
        'taxonomy' => 'post_tag',
        'field' => 'id',
        'terms' => $matchingTermIds
      ),
      array(
        'taxonomy' => 'gallery',
        'field' => 'id',
        'terms' => $matchingTermIds
      )
    )
  ));

  while($taxQuery->have_posts()) {

    $taxQuery->the_post();

    // Block of code to see if taxQuery results are already in the result set

    $newVal = get_the_permalink();

    $pageFound = current(array_filter($results['generalInfo'], function($item) use($newVal) {
      return isset($item['permalink']) && $newVal == $item['permalink'];
    }));

    $storyFound = current(array_filter($results['storyInfo'], function($item) use($newVal) {
      return isset($item['permalink']) && $newVal == $item['permalink'];
    }));

    $processFound = current(array_filter($results['processInfo'], function($item) use($newVal) {
      return isset($item['permalink']) && $newVal == $item['permalink'];
    }));

    $artworkFound = current(array_filter($results['artworkInfo'], function($item) use($newVal) {
      return isset($item['permalink']) && $newVal == $item['permalink'];
    }));

    // End check for duplicates

    if (get_post_type() == 'page' && !$pageFound) {
      array_push($results['generalInfo'], array(
        'postType' => get_post_type(),
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
        'thumbnail' => get_the_post_thumbnail_url()
      ));
    }

    if (get_post_type() == 'artwork' && !$artworkFound) {
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

    if (get_post_type() == 'story' && !$storyFound) {
      array_push($results['storyInfo'], array(
        'postType' => get_post_type(),
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
        'thumbnail' => get_the_post_thumbnail_url()
      ));
    }

    if (get_post_type() == 'process' && !$processFound) {
      array_push($results['processInfo'], array(
        'postType' => get_post_type(),
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
        'thumbnail' => get_the_post_thumbnail_url()
      ));
    }

  }

  return $results;

}
