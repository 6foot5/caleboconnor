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

	<div id="primary" class="content-area content-area--padded-sides">
		<main id="main" class="site-main contents-aligncenter ">

		<?php if ( have_posts() ) : ?>

			<header class="page-header heading heading--centered">
				<?php
				$post = get_queried_object();
				$postType = get_post_type_object($post->name);
				$typeName = esc_html($postType->labels->menu_name);
				echo 'Behind the Artwork - ' . $typeName;
				?>
				<hr class="heading__line" />
			</header><!-- .page-header -->

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
							<a href="<?php echo get_the_permalink() ?>"><img width="100%" src="<?php echo $thumbnail; ?>" alt="" /></a>
						</div>
						<div class="post-card__excerpt">
							<span class="heading heading--small">
							<?php
								printf( '<a href="%1$s">%2$s</a>',
										esc_url( get_the_permalink() ),
										get_the_title()
								);
							?>
							</span>
							<hr class="heading__line heading__line--align-left heading__line--full-width" />
							<?php the_excerpt(); ?>
						</div>
					</div>

					<?php

					//the_excerpt();

				endwhile;

				// the_posts_navigation();

			else :
				echo 'ELSE!';
				//get_template_part( 'template-parts/content', 'none' );

			endif;
			?>

		</div>


		</main><!-- #main -->
	</div><!-- #primary -->

<?php
//get_sidebar();
get_footer();
