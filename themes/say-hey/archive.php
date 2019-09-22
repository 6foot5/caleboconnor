<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package SayHey
 */

get_header();
?>

<?php //pageBanner();	?>

	<div id="primary" class="content-area content-area--padded-sides content-area--bg-color">
		<main id="main" class="site-main contents-aligncenter ">

		<?php if ( have_posts() ) : ?>

			<header>
				<h1 class="page-header heading heading--centered"><?php
				$post = get_queried_object();
				$postType = get_post_type_object($post->name);
				$typeName = esc_html($postType->labels->menu_name);
				echo 'Behind the Artwork - ' . $typeName;
				?></h1>
				<hr class="heading__line" />
			</header>

			<div class="post-card-container">

				<?php
				/* Start the Loop */
				while ( have_posts() ) :
					the_post();

					/*
					 * Include the Post-Type-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
					 */
					// get_template_part( 'template-parts/content', get_post_type() );

					$thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'cpt-thumb');

					?>
					<div class="post-card">
						<div class="post-card__image">
							<a class="no-border" href="<?php echo get_the_permalink() ?>"><img width="100%" src="<?php echo $thumbnail; ?>" alt="" /></a>
						</div>
						<div class="post-card__excerpt">
							<h2 class="heading heading--small">
							<?php
								printf( '<a class="no-border" href="%1$s">%2$s</a>',
										esc_url( get_the_permalink() ),
										get_the_title()
								);
							?>
						</h2>
							<hr class="heading__line heading__line--align-left heading__line--full-width" />
							<?php the_excerpt(); ?>
						</div>
					</div>

					<?php

					//the_excerpt();

				endwhile;

				// the_posts_navigation();

			else :

				//get_template_part( 'template-parts/content', 'none' );

				$request = new WP_REST_Request( 'GET', '/sayhey/v1/artwork' );
				//$request->set_query_params( [ 'per_page' => 12 ] );
				$response = rest_do_request( $request );
				$server = rest_get_server();
				$data = $server->response_to_data( $response, false );
				$json = wp_json_encode( $data );

				echo '+' . $data[1]['title'] . '+<br />';
				echo '--' . $data[1]['imageSrc']['medium_large'] . '--';

				//echo $json;

				// print_r($data[1]);

			endif;
			?>

		</div>


		</main><!-- #main -->
	</div><!-- #primary -->

<?php
//get_sidebar();
get_footer();
