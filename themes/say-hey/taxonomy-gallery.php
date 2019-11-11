<?php
/**
 * The template for displaying the ARTWORK category (i.e. display subcategories)
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package SayHey
 */

get_header();

//locate_template( 'archive-artwork.php', true );

$this_cat = get_queried_object();
$this_cat_ancestors = get_ancestors($this_cat->term_id, 'gallery');

$sectionHeading = $this_cat->name;

foreach ($this_cat_ancestors as $ancestorID) {
		$ancestorInfo = get_term($ancestorID, 'gallery');
		$sectionHeading = $ancestorInfo->name . ' | ' . $sectionHeading;
}

$children = get_term_children($this_cat->term_id, 'gallery');

/*
echo '<h1>' . $this_cat->term_id . '</h1>';

print_r($children);
echo '*** ' . empty($children) . ' *** GALLERY TAX';
*/

?>

<?php //pageBanner();	?>

	<div id="primary" class="content-area content-area--padded-sides content-area--bg-color">
		<main id="main" class="site-main contents-aligncenter">

			<div class="aligncenter">
				<h1 class="heading"><?php echo $sectionHeading; ?></h1>
				<hr class="heading__line" />
			</div>

    <?php

    //print_r($this_cat);
    //echo 'cat-term:' . $this_cat->term_id;


      if ( !empty($children) ) {    // If this category has subcategories....

        $terms = get_terms( array(
          'taxonomy' => 'gallery',
          'parent' => $this_cat->term_id,
          'hide_empty' => 1
          )
        );
/*
        echo '<h1>GOT CHILDREN!</h1>';
        print_r($terms);
        echo '<br /><br />';
*/
        //$queried_object = get_queried_object();

        //print_r($queried_object);
        //echo '<br /><br />';

        foreach ($terms as $childTerm) {

          echo '<div class="gallery-index-item">';

          $image = get_field('term_image', $childTerm);

          //print_r($childTerm);
          //echo '<br /><br />';

          $galleryThumbURL = $image['sizes']['gallery-category'];

          $label = $childTerm->name;

					$imgTag = '<img width="100%" src="' . $galleryThumbURL . '" alt="' . $label . '" />';

          printf( '<a href="%1$s">%2$s</a>',
              esc_url( get_term_link( $childTerm->term_id ) ),
							$imgTag
          );

          ?>

          <a href="<?php echo esc_url( get_term_link( $childTerm->term_id ) ) ?>">
            <div class="gallery-index-item__shadow-overlay">
              <div class="gallery-index-item__text-content">
                <?php	echo $label; ?>
              </div>
            </div>
          </a>

          <?php

          echo '</div>';

//print_r($childTerm);
          ?>

          <?php
        }

      } elseif (empty($children)) {                // It there are no subcategories

          //echo '<h1>FREE AND CLEAR!</h1>';

          //$queried_object = get_queried_object();

          //print_r($this_cat);

					$argsREST = array(
						'posts_per_page' => -1,
						'post_type' => 'artwork',
						'orderby' => 'title',
						'order' => 'asc',
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

						galleryThumbsOutput($data, NULL, true, '');

      }

      ?>

    </main><!-- #main -->
	</div><!-- #primary -->




<?php

get_footer();
