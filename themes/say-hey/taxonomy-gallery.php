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

$children = get_term_children($this_cat->term_id, 'gallery');

/*
echo '<h1>' . $this_cat->term_id . '</h1>';

print_r($children);
echo '*** ' . empty($children) . ' *** GALLERY TAX';
*/

?>

<?php //pageBanner();	?>

	<div id="primary" class="content-area content-area--padded-sides">
		<main id="main" class="site-main contents-aligncenter">

			<div class="aligncenter">
				<h1>Gallery - <?php echo $this_cat->name; ?></h1>
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

          $relatedWorks = new WP_Query(array(
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
            )
          );

          if ($relatedWorks->have_posts()) {
            ?>
            <style>
              @media all and (min-width: 800px) {
                .fancybox-thumbs {
                  top: auto;
                  width: auto;
                  bottom: 0;
                  left: 0;
                  right : 0;
                  height: 95px;
                  padding: 10px 10px 5px 10px;
                  box-sizing: border-box;
                  background: rgba(0, 0, 0, 0.7);
                }

                .fancybox-show-thumbs .fancybox-inner {
                  right: 0;
                  bottom: 95px;
                }
              }
            </style>

            <?php

              //echo '<hr>';
              //echo '<h2>' . get_the_title() . '</h2>';
              //echo '<ul>';
              ?>

              <?php

              while ($relatedWorks->have_posts()) {

                $relatedWorks->the_post();

                $workID = get_the_ID();
                $relatedCaption = '';
                $captionArgs = array(
                    'get_spin' => false,
                    'get_stories' => true,
                    'get_processes' => true
                );

                $relatedSpin = get_field('related_spin');

                if ($relatedSpin) {
                  $relatedCaption .= ' | ' . '<a data-fancybox data-type=\'iframe\' href=\'' . get_permalink($relatedSpin->ID) . '\'>View 360-degree Spin</a>';
                }

                $relatedCaption = artworkCaptioner($workID, $relatedCaption, $captionArgs);


                ?>

								<div class="gallery-thumb">
									<div class="gallery-thumb__image">

	                  <a
											href = "<?php the_post_thumbnail_url('large'); ?>"
											data-fancybox = "gallery"
											data-caption = "<a data-fancybox data-type='iframe' href='<?php the_permalink(); ?>'><?php the_title(); ?></a> <?php echo $relatedCaption ?>">
												<img
													alt="<?php the_title(); ?>"
													src="<?php the_post_thumbnail_url('gallery-thumb'); ?>">

		                  <div class="gallery-thumb__shadow-overlay">
		                  </div>

										</a>
	                </div>
									<div class="gallery-thumb__caption">
										<?php the_title(); ?>
									</div>
								</div>



              <?php

            }

          }

      		?>

          <script type="text/javascript">
               <!--
               	$.fancybox.defaults.loop = true;
               	$.fancybox.defaults.protect = true;
               	$.fancybox.defaults.buttons = ['thumbs', 'fullScreen', 'close'];

                $('[data-fancybox="gallery"]').fancybox({
            				thumbs : {
          			    	autoStart : false,
          			    	axis      : 'x'
          				}
          			})

      			//alert('Sucka!');

               //-->
          </script>

        <?php
      }

      ?>

    </main><!-- #main -->
	</div><!-- #primary -->




<?php

get_footer();
