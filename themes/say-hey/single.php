<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package SayHey
 */

get_header();
?>

<?php //pageBanner();	?>

	<div id="primary" class="content-area content-area--padded-sides">
		<main id="main" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();
			?>

			<header class="page-header">
				<?php
				$post = get_queried_object();
				$postType = get_post_type_object(get_post_type($post));
				//print_r($postType);
				if ($postType) {
				    echo '<a href="' . get_post_type_archive_link($postType->name) . '" class="heading__post-type">' . strtoupper(esc_html($postType->labels->menu_name)) . '</a><br />';
				}
				the_title('<h1 class="heading heading--small">','</h1>');
				?>
				<hr class="heading__line heading__line--align-left heading__line--full-width" />
			</header><!-- .page-header -->

			<?php
			the_content( sprintf(
				wp_kses(

					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'sayhey' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			) );

			// get_template_part( 'template-parts/content', get_post_type() );

			//	the_post_navigation();

// get related artwork

				$relatedArtwork = get_field('related_artwork');

//print_r($relatedArtwork);
				if ($relatedArtwork) {
					?>

					<style>
						@media all and (min-width: 800px) {
							.fancybox-thumbs {
								top: auto;
								width: auto;
								bottom: 0;
								left: 0;
								right : 0;
								height: 95px;
								padding: 10px 10px 5px 10px;
								box-sizing: border-box;
								background: rgba(0, 0, 0, 0.7);
							}

							.fancybox-show-thumbs .fancybox-inner {
								right: 0;
								bottom: 95px;
							}
						}
					</style>


					<hr class="heading__line heading__line--align-left heading__line--full-width" />
					<h2 class="heading heading--small">Related Artwork</h2>

					<div class="gallery-thumbs">

					<?php

						foreach($relatedArtwork as $artwork) {

							$relatedCaption = '';
							$workID = $artwork->ID;

							$captionArgs = array(
					        'get_spin' => true,
					        'get_stories' => true,
					        'get_processes' => true
					    );

							$relatedCaption = artworkCaptioner($workID, $relatedCaption, $captionArgs);

					?>

							<div class="gallery-thumb">

									<a data-fancybox="gallery"
										href="<?php echo get_the_post_thumbnail_url($artwork->ID, 'large'); ?>"
										data-caption="<a href='<?php echo get_the_permalink($workID); ?>'><?php echo get_the_title($workID); ?></a> <?php echo $relatedCaption ?>"> <img alt="<?php echo get_the_title($workID); ?>" src="<?php echo get_the_post_thumbnail_url($artwork->ID, 'thumbnail'); ?>">

									<div class="gallery-thumb__shadow-overlay">
									</div></a>

							</div>

					<?php

						}
						?>

					</div>

					<script type="text/javascript">
							 <!--
								$.fancybox.defaults.loop = true;
								$.fancybox.defaults.protect = true;
								$.fancybox.defaults.buttons = ['thumbs', 'fullScreen', 'close'];

						$('[data-fancybox="gallery"]').fancybox({
								thumbs : {
									autoStart : false,
									axis      : 'x'
							}
						})

					</script>


					<?php

			}

// end get related artwork

		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
//get_sidebar();
get_footer();
