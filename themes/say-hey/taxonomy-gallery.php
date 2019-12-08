<?php
/**
 * The template for displaying the GALLERY taxonomy (i.e. display categories)
 * This is the curated section of the site, showing predefined categories
 *
 * @package SayHey
 */

get_header();

$this_cat = get_queried_object();
$this_cat_ancestors = get_ancestors($this_cat->term_id, 'gallery');

$sectionHeading = $this_cat->name;

foreach ($this_cat_ancestors as $ancestorID) {
		$ancestorInfo = get_term($ancestorID, 'gallery');
		$sectionHeading = $ancestorInfo->name . ' | ' . $sectionHeading;
}

$children = get_term_children($this_cat->term_id, 'gallery');

?>

	<div id="primary" class="content-area content-area--padded-sides content-area--bg-color">
		<main id="main" class="site-main contents-aligncenter">

			<div class="aligncenter">
				<h1 class="heading"><?php echo $sectionHeading; ?></h1>
				<hr class="heading__line" />
			</div>

    <?php

      if ( !empty($children) ) {    // If this category has subcategories....

        $terms = get_terms( array(
          'taxonomy' => 'gallery',
          'parent' => $this_cat->term_id,
          'hide_empty' => 1
          )
        );

        foreach ($terms as $childTerm) {

          echo '<div class="gallery-index-item">';

          $image = get_field('term_image', $childTerm);


          $galleryThumbURL = $image['sizes']['gallery-category'];

          $label = $childTerm->name;

					$imgTag = '<img width="100%" src="' . $galleryThumbURL . '" alt="' . $label . '" />';

          printf( '<a href="%1$s" title="View Gallery Category - %2$s">%3$s</a>',
              esc_url( get_term_link( $childTerm->term_id ) ),
							$label,
							$imgTag
          );

          ?>

          <a href="<?php echo esc_url( get_term_link( $childTerm->term_id ) ) ?>"
						 title="View Gallery Category - <?php echo $label; ?>">
            <div class="gallery-index-item__shadow-overlay">
              <div class="gallery-index-item__text-content">
                <?php	echo $label; ?>
              </div>
            </div>
          </a>

          <?php

          echo '</div>';

        }

      }
			// If there are no subcategories, display all individual works within
			elseif ( empty($children) ) {

				$argsREST = array(
					'tax_query' => array(
								array(
								'taxonomy' => 'gallery',
								'field' => 'slug',
								'terms' => $this_cat->slug
							)
						)
					);

					$request = new WP_REST_Request( 'GET', '/sayhey/v1/artwork' );
					$request->set_query_params( $argsREST );
					$response = rest_do_request( $request );
					$server = rest_get_server();
					$data = $server->response_to_data( $response, false );

					$captionArgs = array('get_spin' => false, 'get_stories' => true, 'get_processes' => true);
					galleryThumbsOutput($data, $captionArgs, true, '');

      }

      ?>

    </main><!-- #main -->
	</div><!-- #primary -->

<?php

get_footer();
