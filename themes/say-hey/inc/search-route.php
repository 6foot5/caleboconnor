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
    Those filter the search query add join/where/groupby criteria for postmeta
    In this case, the $mainQuery will be filtered by the sayhey-search mu-plugin
  */

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

  } // end while loop over main query results

  /*
    The main search query has been processed.
    Below we're adding one more check for artwork that migt have been
    tagged (post_tag taxonomy) or categorized (gallery taxonomy) with a
    term matching the search criteria.

    [note: the right join criteria in the sayhey-search mu-plugin could
      include these same results in the main search query. That will
      be considered as a future enhancement]
  */

  $argsREST['tax_query'] = array(
    'relation' => 'OR',
    array(
      'taxonomy' => 'post_tag',
      'field' => 'name',
      'terms' => sanitize_text_field($wpData['term'])
    ),
    array(
      'taxonomy' => 'gallery',
      'field' => 'name',
      'terms' => sanitize_text_field($wpData['term'])
    )
  );

  $request = new WP_REST_Request( 'GET', '/sayhey/v1/artwork' );
  $request->set_query_params( $argsREST );
  $response = rest_do_request( $request );
  $server = rest_get_server();
  $data = $server->response_to_data( $response, false );

  foreach ($data as $work) {
    array_push($results['artworkInfo'], array(
      'postType' => get_post_type(),
      'title' => $work['title'],
      'permalink' => $work['permalink'],
      'thumbnail' => $work['imageSrc']['admin-preview'],
      'medium' => $work['medium'],
      'size' => $work['size'],
      'date' => $work['year']
    ));
  }

  return $results;

}
