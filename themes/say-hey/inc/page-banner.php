<?php

function pageBanner($args = NULL) {

  if( !$args['photo'] ) {

    if(get_field('page_banner_background_image')) {
      $bgImg = get_field('page_banner_background_image');
      $args['photo'] = $bgImg['sizes']['page-banner'];
    }

    else {

      // This field is part of the ACF site settings page in WP admin
      $bannerImagesFound = get_field('random_banner_images', 'options');
      $bannerPermalinks = array();

      if ( $bannerImagesFound ) {
        $numBanners = 0;
        foreach($bannerImagesFound as $image) {
          $thisImg = wp_get_attachment_image_src($image->ID, 'page-banner');
          array_push($bannerPermalinks, $thisImg[0]);
          $numBanners += 1;
        }
        $args['photo'] = $bannerPermalinks[mt_rand(0,$numBanners-1)];
      }
      else {
        $args['photo'] = get_theme_file_uri('/img/banner.jpg');
      }

    }
  }

  ?>

  <div class="site-banner">
    <div class="site-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>);"></div>
  </div>

  <?php
}
