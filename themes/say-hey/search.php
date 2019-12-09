<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package SayHey
 */

get_header();
?>

	<section id="primary" class="content-area">
		<div class="content-area content-area--padded-sides content-area--bg-color">
			<main id="main" class="site-main contents-aligncenter">

				<?php get_search_form(); ?>


				<?php
				if ( have_posts() ) {
				?>

					<header class="page-header">
						<h2 class="heading--small">
							<?php
							/* translators: %s: search query. */
							printf( esc_html__( 'Results for: %s', 'sayhey' ), '<span>"' . get_search_query() . '"</span>' );
							?>
						</h2>
						<hr class="heading__line" />
					</header><!-- .page-header -->

					<?php
					/* Start the Loop */
					while ( have_posts() ) {

						the_post();

						/**
						 * Run the loop for the search to output the results.
						 * If you want to overload this in a child theme then include a file
						 * called content-search.php and that will be used instead.
						 */
						get_template_part( 'template-parts/content', 'search' );

					}

				}
				else {
					echo 'No results';
				}

				?>




			</main><!-- #main -->
		</div>
	</section><!-- #primary -->

<?php
get_footer();
