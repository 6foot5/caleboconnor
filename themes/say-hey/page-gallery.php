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
			$terms = get_terms( array(
				'taxonomy' => 'gallery',
				'parent' => $cat_id->term_id,
				'hide_empty' => 1
				)
			);

      foreach ( $terms as $childTerm ) {

					$image = get_field('term_image', $childTerm);

          printf( '<a href="%1$s">%2$s</a><br />',
              esc_url( get_term_link( $childTerm->term_id ) ),
              esc_html( $childTerm->name )
          );
					?>

					<?php  echo wp_get_attachment_image($image['id'], 'medium'); 

      }
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
