<?php

function pageBanner($args = NULL) {

  if(!$args['photo']) {
    if(get_field('page_banner_background_image')) {
      $bgImg = get_field('page_banner_background_image');
      $args['photo'] = $bgImg['sizes']['page-banner'];
    }
    /*
    elseif (is_singular('artwork')) {
      $args['photo'] = wp_get_attachment_image_src(674, 'page-banner')[0]; // HARDCODED ID of desired bg for viewing single artwork
    }
    */
    else {

      $queryArgs = array(
  	    'post_status' => 'inherit',
  			'posts_per_page' => -1,
  			'post_type' => 'attachment',
  		);

/*
  This query is grabbing a random image (attachment) from the media library
  It is looking for a specific "folder" (ID 44)
  This folder is essentially a fallback to produce banners for
  pages that have not explicitly defined a banner
*/

  		$queryArgs['tax_query'] = array(
  			array(
  		    'taxonomy' => 'nt_wmc_folder',
  		    'terms' => array( 44 ), // ID of the "Random Banners" media folder
  		    'field' => 'term_id',
  			)
  		);

  		$the_query = new WP_Query( $queryArgs );

  		if ( $the_query->have_posts() ) {
  			$theBanners = array();
  			$numBanners = 0;
  		    while ( $the_query->have_posts() ) {
  		    $the_query->the_post();
  					$thisImg = wp_get_attachment_image_src(get_the_ID(), 'page-banner');
  					array_push($theBanners, $thisImg[0]);
  					$numBanners += 1;
  		    }
  				$args['photo'] = $theBanners[mt_rand(0,$numBanners-1)];
  		}
      else {
        $args['photo'] = get_theme_file_uri('/img/banner.jpg');
      }

  		wp_reset_postdata();

    }
  }

  ?>

  <div class="site-banner">
    <div class="site-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>);"></div>
  </div>


  <?php
  //return $args['photo'];
}
