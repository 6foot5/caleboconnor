<?php
/**
 * The template for displaying all (ABOUT) pages
 *
 * This is the template that displays all pages by default.
 * This theme has only ABOUT pages and a "search" page
 * The "search" page is handled by page-search.php
 */

get_header();
?>


<?php // Setup for "About" section page header

	global $post;

	$post_slug = $post->post_name;
	$aboutPage = get_page_by_path( 'about' );
	$aboutID = $aboutPage->ID;

	if ( has_post_thumbnail( $aboutID ) ) {
		$featuredImageArr = wp_get_attachment_image_src( get_post_thumbnail_id( $aboutID ), 'gallery-category' );
		$featuredImageURL = $featuredImageArr[0];
	}

	$subpageArgs = array(
		'child_of' => $aboutID,
		'sort_column' => 'menu_order'
	);

	$childPages = get_pages($subpageArgs);

?>

	<div id="primary" class="content-area content-area--padded-sides content-area--bg-color">
		<main id="main" class="site-main">

			<div class="flexible">
				<div class="flexible__flex-left">
					<img class="about-photo" alt="Caleb O'Connor" src="<?php echo $featuredImageURL; ?>">
				</div>
				<div class="flexible__flex-right">
					<!-- <h1 class="heading heading--tight">
					</h1> -->
					<h1 class="heading heading--tight">
					<?php
						if ( $post_slug == 'about' ) {
							echo 'About Caleb';
						} else {
							$postType = $post->post_title;
						  $typeName = esc_html($postType->labels->menu_name);
						  echo $postType;
						}
				  ?>
					</h1>
					<hr class="heading__line" />

<?php
// End About page heading section

					$extraStyle = '';
					foreach ($childPages as $childPage) {
						if ( $childPage->ID == $post->ID ) {
							$extraStyle = 'button--selected'; // to show active page in sub-nav
						}
?>
						<a href="<?php the_permalink($childPage->ID); ?>" title="<?php echo $childPage->post_title; ?>" class="button <?php echo $extraStyle ?>"><?php echo $childPage->post_title; ?></a>
<?php
						$extraStyle = '';
					}
?>
				</div>
			</div>

			<hr class="heading__line" />

			<div class="reading">

			<?php

			// Begin (sub-)page-specific output

			wp_reset_postdata();

			while ( have_posts() ) {

				the_post();

				// $pageContent = get_the_content();

				if ( get_the_content() ) {
					the_content();
				}

				// if ( $post_slug == 'statement' ) {
				// 	get_template_part( 'template-parts/content', 'statement' );
				// }
				// else

				if ( 	$post_slug == 'awards' ||
									$post_slug == 'exhibitions' ||
									$post_slug == 'press' ) {

					get_template_part( 'template-parts/content', 'repeater' );
				}

				elseif ( $post_slug == 'contact' ) {

					get_template_part( 'template-parts/content', 'contact' );
				}

			}

			?>

			</div>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
//get_sidebar();
get_footer();
