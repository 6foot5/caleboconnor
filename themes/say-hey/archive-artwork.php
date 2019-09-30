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

			$relatedWorks = new WP_Query(array(
				'posts_per_page' => -1,
				'post_type' => 'artwork',
				'orderby' => 'title',
				'order' => 'asc'
				)
			);

			if ($relatedWorks->have_posts()) {
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

				<?php

					//echo '<hr>';
					//echo '<h2>' . get_the_title() . '</h2>';
					//echo '<ul>';
					?>

					<?php

					while ($relatedWorks->have_posts()) {

						$relatedWorks->the_post();

						$workID = get_the_ID();
						$relatedCaption = '';
						$captionArgs = array(
								'get_spin' => false,
								'get_stories' => true,
								'get_processes' => true
						);

						$relatedSpin = get_field('related_spin');

						if ($relatedSpin) {
							$relatedCaption .= ' | ' . '<a data-fancybox data-type=\'iframe\' href=\'' . get_permalink($relatedSpin->ID) . '\'>View 360-degree Spin</a>';
						}

						$relatedCaption = artworkCaptioner($workID, $relatedCaption, $captionArgs);


						?>

						<div class="gallery-thumb">
							<div class="gallery-thumb__image">

								<a
									href = "<?php the_post_thumbnail_url('large'); ?>"
									data-fancybox = "gallery"
									data-caption = "<a href='<?php the_permalink(); ?>'><?php the_title(); ?></a> <?php echo $relatedCaption ?>">
										<img
											alt="<?php the_title(); ?>"
											src="<?php the_post_thumbnail_url('thumbnail'); ?>">

									<div class="gallery-thumb__shadow-overlay">
									</div>

								</a>
							</div>
							<div class="gallery-thumb__caption">
								&bull; <?php the_title(); ?> &bull;
							</div>
						</div>



					<?php

				}

			}

			?>

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

				//alert('Sucka!');

					 //-->
			</script>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php

get_footer();
