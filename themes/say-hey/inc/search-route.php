<?php

add_action('rest_api_init', 'sayheyRegisterSearch');

function sayheyRegisterSearch() {
  register_rest_route('sayhey/v1', 'search', array(
    'methods' => WP_REST_SERVER::READABLE,
    'callback' => 'sayheySearchResults'
  ));
}

function sayheySearchResults($wpData) {

  $mainQuery = new WP_Query(array(
    'posts_per_page' => -1,
    'post_type' => array('page', 'artwork', 'story', 'process'),
    's' => sanitize_text_field($wpData['term'])
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

  }

  return $results;

}
