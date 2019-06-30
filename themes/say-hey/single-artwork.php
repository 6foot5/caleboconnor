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

	<div id="primary" class="content-area">
		<main id="main" class="site-main contents-aligncenter">

		<?php
		while ( have_posts() ) :

			the_post();

			$fullsize = get_the_post_thumbnail_url(get_the_ID(), 'large');
			$thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'gallery-category');

?>

		<div class="flexible">
			<div class="flexible__flex-left">
				<a data-fancybox="gallery" href="<?php echo $fullsize; ?>" data-caption="<?php the_title(); ?>"> <img alt="<?php the_title(); ?>" src="<?php echo $thumbnail; ?>"></a>
			</div>
			<div class="flexible__flex-right">
				<h2 class="heading--small"><?php the_title(); ?></h2>
				<hr class="about-hr" />
				<?php
				if (get_field('artwork_medium')) {
					echo get_field('artwork_medium') . '<br /><br />';
				}
				if (get_field('artwork_size')) {
					echo get_field('artwork_size') . '<br /><br />';
				}
				if (get_field('artwork_year')) {
					echo get_field('artwork_year') . '<br /><br />';
				}
				if (get_field('artwork_description')) {
					echo get_field('artwork_description') . '<br /><br />';
				}
				?>

			</div>
		</div>


<?php
			$workTags = wp_get_post_tags(get_the_id());
			$workCats = wp_get_object_terms(get_the_id(), 'gallery');
			$spins = get_field('related_spin');

/*
			echo '<br /><br />';
			print_r($workTags);
			echo '<br /><br />';
			print_r($spins);
			echo '<br /><br />HELLO -- ' . get_field('related_spin')->ID;
*/
			// fa-icons for image view: expand-arrows-alt, external-link-alt

			// get related artwork

			$detailImages = get_field('detail_images');

			//print_r($detailImages);
						if ($detailImages) {
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


								<hr />
								<h2>Detail Images for <?php single_post_title(); ?> </h2>

								<p>

								<?php

									$detailCount = 0;

									foreach($detailImages as $artwork) {

										$workID = $artwork->ID;
										$fullsize = wp_get_attachment_image_src($workID, 'large')[0];
										$thumbnail = wp_get_attachment_image_src($workID, 'thumbnail')[0];
										$detailCount = $detailCount + 1;
										$relatedCaption = '';
										$captionArgs = array(
								        'get_spin' => false,
								        'get_stories' => false,
								        'get_processes' => false
								    );

										$relatedCaption = artworkCaptioner($workID, $relatedCaption, $captionArgs);

								?>

								<div class="gallery-thumb">

										<a data-fancybox="gallery" href="<?php echo $fullsize; ?>" data-caption="Detail Image <?php echo $detailCount ?>"> <img alt="Detail Image <?php echo $detailCount ?>" src="<?php echo $thumbnail; ?>">

										<div class="gallery-thumb__shadow-overlay">
										</div></a>

								</div>

								<?php

							} //end for loop
									?>

								</p>

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
