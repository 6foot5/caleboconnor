<?php
/**
 * This template is to display a gallery index page, with links to each category of art
 *
 * @package Say_Hey
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php
			echo '**' . $cat_id->term_id . '**';
			print_r($cat_id);

			$terms = get_terms( array(
				'taxonomy' => 'gallery',
				'hide_empty' => 1,
				'childless' => true,
				'orderby' => 'parent'
				)
			);

      foreach ( $terms as $childTerm ) {

				echo '<p>';
				//print_r($childTerm);


					$image = get_field('term_image', $childTerm);

					$label = $childTerm->name;

					if ($childTerm->parent) {
						$childOf = get_term($childTerm->parent);
						$label = $childOf->name . ' - ' . $label;
					}

          printf( '<a href="%1$s">%2$s</a><br />',
              esc_url( get_term_link( $childTerm->term_id ) ),
							$label
          );
					?>

					<?php  echo wp_get_attachment_image($image['id'], 'medium');

					echo '</p>';

      }
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
