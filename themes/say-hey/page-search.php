<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package SayHey
 */

get_header();
?>


	<?php //pageBanner();	?>


	<div id="primary" class="content-area content-area--padded-sides content-area--bg-color">
		<main id="main" class="site-main">

			<?php get_search_form(); ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
//get_sidebar();
get_footer();
