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

		<?php if ( have_posts() ) { ?>

			<header>
				<h1 class="page-header heading heading--centered"><?php
				$post = get_queried_object();
				$postType = get_post_type_object($post->name);
				$typeName = esc_html($postType->labels->menu_name);
				echo 'Behind the Artwork - ' . $typeName;
				?></h1>
				<hr class="heading__line" />
			</header>

			<section class="post-card-container">

				<?php
				/* Start the Loop */
				while ( have_posts() ) {

					the_post();

					cptCardsOutput(get_the_ID(), get_the_permalink(), get_the_title(), get_the_excerpt(), $typeName);

				}

			}
			?>

		</section>


		</main><!-- #main -->
	</div><!-- #primary -->

<?php
//get_sidebar();
get_footer();
