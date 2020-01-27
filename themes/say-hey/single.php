<?php
/**
 * The template for displaying all single posts (Process and Stories)
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package SayHey
 */

get_header();
?>

<?php //pageBanner();	?>

	<div id="primary" class="content-area content-area--padded-sides content-area--bg-color">
		<main id="main" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();
			$post = get_queried_object();
			$postType = get_post_type_object(get_post_type($post));
			//print_r($postType);
			if ($postType) {
					echo '<a href="' . get_post_type_archive_link($postType->name) . '" class="heading__post-type">ALL ' . strtoupper(esc_html($postType->labels->menu_name)) . '</a><br />';
			}
			?>

			<div class="reading">

			<header class="page-header">
				<?php
				the_title('<h1 class="heading">','</h1>');
				?>
				<hr class="heading__line heading__line--align-left heading__line--full-width" />
			</header><!-- .page-header -->

			<?php
			the_content( sprintf(
				wp_kses(

					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'sayhey' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			) );

			// get_template_part( 'template-parts/content', get_post_type() );

			//	the_post_navigation();

// get related artwork

				$relatedArtwork = get_field('related_artwork');

				$relatedIDs = array();
				if ($relatedArtwork) {
					foreach($relatedArtwork as $artwork) {
						$relatedIDs[] = $artwork->ID;
					}
				}


				$argsREST['ids'] = $relatedIDs;

				$request = new WP_REST_Request( 'GET', '/sayhey/v1/artwork' );
				$request->set_query_params( $argsREST );
				$response = rest_do_request( $request );
				$server = rest_get_server();
				$data = $server->response_to_data( $response, false );

				if ($relatedArtwork) {
					?>

					<h2 class="heading heading--small">Related Artwork</h2>
					<hr class="heading__line heading__line--align-left heading__line--full-width" />

					<div class="gallery-thumbs">

					<?php
					$captionArgs = array('get_spin' => false, 'get_stories' => false, 'get_processes' => false);
					galleryThumbsOutput($data, $captionArgs, true, '');
					?>

					</div>

					<?php

				}

// end get related artwork

		endwhile; // End of the loop.
		?>

		</div>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
//get_sidebar();
get_footer();
