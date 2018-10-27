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
 * @package Say_Hey
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();

			$postid = get_the_ID();

			$args = array(
			    'post_type'      => 'page',
			    'posts_per_page' => -1,
			    'post_parent'    => $postid,
			    'order'          => 'ASC',
			    'orderby'        => 'menu_order'
			 );

			$children = new WP_Query( $args );

			if ( $children->have_posts() ) {

			    while ( $children->have_posts() ) {

						$children->the_post();
?>
			        <div>

			            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

			        </div>

			    <?php
					}
			}

			wp_reset_query();


			get_template_part( 'template-parts/content', 'page' );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
