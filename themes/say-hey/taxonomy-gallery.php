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
 * @package Say_Hey
 */

get_header();

//locate_template( 'archive-artwork.php', true );

$cat_id = get_queried_object();

echo '<h1>' . $cat_id->term_id . '</h1>';

$children = get_term_children($cat_id->term_id, 'gallery');

//print_r($children);
echo '*** ' . empty($children) . ' *** GALLERY TAX';

?>

    <?php

      if ( !empty($children) ) {    // If this category has subcategories....

        $terms = get_terms( array(
          'taxonomy' => 'gallery',
          'parent' => $cat_id->term_id,
          'hide_empty' => 1
          )
        );

        //echo '<h1>GOT CHILDREN!</h1>';
        //print_r($terms);
        //echo '<br /><br />';

        $queried_object = get_queried_object();

        //print_r($queried_object);
        //echo '<br /><br />';

        foreach ($terms as $childTerm) {

          $image = get_field('term_image', $childTerm);

          //print_r($image);
          //echo '<br /><br />';


//print_r($childTerm);
          ?>

          <a href="<?php echo get_category_link($childTerm->term_id);?>">Go to <?php echo $childTerm->name;?>!</a>
          <?php  echo wp_get_attachment_image($image['id'], 'medium'); ?>

          <?php
        }

      } elseif (empty($children)) {                // It there are no subcategories

          //echo '<h1>FREE AND CLEAR!</h1>';

          $queried_object = get_queried_object();

          print_r($queried_object);

          $relatedWorks = new WP_Query(array(
            'posts_per_page' => -1,
            'post_type' => 'artwork',
            'orderby' => 'title',
            'order' => 'asc',
            'tax_query' => array(
                  array(
                  'taxonomy' => 'gallery',
                  'field' => 'slug',
                  'terms' => $queried_object->slug
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

              echo '<hr>';
              //echo '<h2>' . get_the_title() . '</h2>';
              //echo '<ul>';
              ?>

              <p class="imglist" style="max-width: 1000px;">

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

                <a data-fancybox="gallery" href="<?php the_post_thumbnail_url('large'); ?>" data-caption="<a data-fancybox data-type='iframe' href='<?php the_permalink(); ?>'><?php the_title(); ?></a> <?php echo $relatedCaption ?>"> <img alt="<?php the_title(); ?>" src="<?php the_post_thumbnail_url('medium'); ?>"></a>

              <?php

            }
            echo '</p>';

          }

      		?>

          <script type="text/javascript">
               <!--
               	$.fancybox.defaults.loop = true;
               	$.fancybox.defaults.protect = true;
               	$.fancybox.defaults.buttons = ['thumbs', 'fullScreen', 'close'];

                $('[data-fancybox="gallery"]').fancybox({
            				thumbs : {
          			    	autoStart : true,
          			    	axis      : 'x'
          				}
          			})

      			//alert('Sucka!');

               //-->
          </script>

        <?php
      }

      ?>



<?php
get_sidebar();
get_footer();
