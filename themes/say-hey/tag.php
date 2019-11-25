<?php

get_header();

$this_term = get_queried_object();

?>

<div id="primary" class="content-area content-area--padded-sides content-area--bg-color">
  <main id="main" class="site-main contents-aligncenter">

    <div class="aligncenter">
      <h1 class="heading"><?php echo 'Tag | ' . $this_term->name; ?></h1>
      <hr class="heading__line" />
    </div>

<?php

$argsREST['tax_query'] = array(
  array(
    'taxonomy' => 'post_tag',
    'field' => 'slug',
    'terms' => $this_term->slug
  )
);

$request = new WP_REST_Request( 'GET', '/sayhey/v1/artwork' );
$request->set_query_params( $argsREST );
$response = rest_do_request( $request );
$server = rest_get_server();
$data = $server->response_to_data( $response, false );

echo '<div class="gallery-thumbs">';

$captionArgs = array('get_spin' => false, 'get_stories' => true, 'get_processes' => true);
galleryThumbsOutput($data, $captionArgs, true, 'all-thumbs');

echo '</div>';

$behindTheArt = new WP_Query(array(
  'posts_per_page' => -1,
  'post_type' => array('story','process'),
  'orderby' => 'title',
  'order' => 'asc',
  'tax_query' => array(
        array(
        'taxonomy' => 'post_tag',
        'field' => 'slug',
        'terms' => $this_term->slug
      )
    )
  )
);

// wp_reset_postdata();
$behindArray = $behindTheArt->posts;

    if ($behindArray) {

     echo '<section class="post-card-container">';

        while ($behindTheArt->have_posts()) {
          $behindTheArt->the_post();
          cptCardsOutput(get_the_ID(), get_the_permalink(), get_the_title(), get_the_excerpt(), get_post_type());
        }

      echo '</section>';

    }

    ?>


    <?php
    get_footer();
