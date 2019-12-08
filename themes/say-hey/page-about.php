<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 */

get_header();
?>


<?php // pageBanner();	?>


	<div id="primary" class="content-area content-area--padded-sides content-area--bg-color">
		<main id="main" class="site-main">

			<div class="flexible">
				<div class="flexible__flex-left">
					<img class="about-photo" alt="Caleb O'Connor" src="<?php the_post_thumbnail_url('gallery-category'); ?>">
				</div>
				<div class="flexible__flex-right">
					<h2 class="heading heading--tight">About Caleb</h2>
					<hr class="heading__line" />

<?php
					$args = array(
						'child_of' => get_the_id(),
						'sort_column' => 'menu_order'
					);

					$child_pages = get_pages($args);

					foreach ($child_pages as $child_page) {
					?>
						<a href="<?php the_permalink($child_page->ID); ?>" title="<?php echo $child_page->post_title; ?>" class="button"><?php echo $child_page->post_title; ?></a>
					<?php
					}
?>
				</div>
			</div>

		<?php

		wp_reset_postdata();

		while ( have_posts() ) :
			the_post();



			the_content();


			//get_template_part( 'template-parts/content', 'page' );


		endwhile; // End of the loop.

		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
//get_sidebar();
get_footer();
