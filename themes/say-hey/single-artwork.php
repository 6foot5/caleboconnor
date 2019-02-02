<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package SayHey
 */

//get_header();
?>

<?php //pageBanner();	?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main contents-aligncenter">

		<?php
		while ( have_posts() ) :

			the_post();

			the_post_thumbnail('large');

			the_content();

		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
//get_sidebar();
//get_footer();
