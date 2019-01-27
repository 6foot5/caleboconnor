<?php
/**
 * This template is to display a gallery index page, with links to each category of art
 *
 * @package Say_Hey
 */

get_header();
?>

<?php //pageBanner();	?>

	<div id="primary" class="content-area content-area--padded-sides">
		<main id="main" class="site-main contents-aligncenter">

			<div class="aligncenter">
				<h1>ARTWORK</h1>
			</div>

		<?php
			//echo '**' . $cat_id->term_id . '**';
			//print_r($cat_id);

			$terms = get_terms( array(
				'taxonomy' => 'gallery',
				'hide_empty' => 1,
				'childless' => true,
				'orderby' => 'parent'
				)
			);

			$itemCount = 0;

			//echo '<div class="row row--margins-large">';

      foreach ( $terms as $childTerm ) {
/*

				-->  Displaying gallery thumbs as inline-block divs allows them to naturally
				flow from one line to the next, obviating the need to row/column logic.

				if ($itemCount % 2 == 0) {

					//echo '<div class="row__medium-6">';
				}

*/
				echo '<div class="gallery-index-item">';
				//print_r($childTerm);


					$image = get_field('term_image', $childTerm);

					$label = $childTerm->name;

					if ($childTerm->parent) {
						$childOf = get_term($childTerm->parent);
						$label = $childOf->name . '<hr>' . $label;
					}

					$galleryThumbURL = $image['sizes']['gallery-category'];

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

					/*

									-->  Displaying gallery thumbs as inline-block divs allows them to naturally
									flow from one line to the next, obviating the need to row/column logic.


					if ($itemCount % 2 != 0) {
						//echo '</div>';
					}

					$itemCount += 1;
*/
			}  //for each item in gallery

			//echo '</div>';

		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php

get_footer();
