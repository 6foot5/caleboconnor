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


	<div id="primary" class="content-area content-area--padded-sides">
		<main id="main" class="site-main">

			<div class="flexible">
				<div class="flexible__flex-left">
					<img class="about-photo" alt="Caleb O'Connor" src="<?php the_post_thumbnail_url('gallery-category'); ?>">
				</div>
				<div class="flexible__flex-right">
					<h2 class="about-head">About Caleb</h2>
					<hr class="about-hr" />
					<a href="#" class="about-link">Resume</a>
					<a href="#" class="about-link">Awards</a>
					<a href="#" class="about-link">Exhibitions</a>
					<a href="#" class="about-link">Contact</a>
				</div>
			</div>

		<?php

		while ( have_posts() ) :
			the_post();



			the_content();


			//get_template_part( 'template-parts/content', 'page' );


		endwhile; // End of the loop.

		?>

ABOUTTTT?!
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
//get_sidebar();
get_footer();
