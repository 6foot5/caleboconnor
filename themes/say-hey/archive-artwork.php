<?php
/**
 * This template is to display a gallery index page, with links to each category of art
 *
 * @package SayHey
 */

get_header();
?>

<?php //pageBanner();	?>

	<div id="primary" class="content-area content-area--padded-sides content-area--bg-color">
		<main id="main" class="site-main contents-aligncenter">

			<div class="aligncenter">
			  <h1 class="heading">All Artwork</h1>
				<hr class="heading__line" />
			</div>

			<?php

			$args = array(
				'posts_per_page' => -1,
				'post_type' => 'artwork',
				'orderby' => 'title',
				'order' => 'asc'
			);

			galleryThumbsOutput($args, true);

			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php

get_footer();
