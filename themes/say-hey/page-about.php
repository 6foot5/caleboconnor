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
