<?php
/**
 * The template for displaying all single-artwork pages
 *
 *
 * @package SayHey
 */

get_header();
?>

	<div id="primary" class="content-area content-area--padded-sides content-area--bg-color">
		<main id="main" class="site-main">

		<?php
		while ( have_posts() ) {

			the_post();

			$argsREST['id'] = get_the_ID();

			$request = new WP_REST_Request( 'GET', '/sayhey/v1/artwork' );
			$request->set_query_params( $argsREST );
			$response = rest_do_request( $request );
			$server = rest_get_server();
			$data = $server->response_to_data( $response, false );


			foreach ( $data as $work ) {

				$fullsize = get_the_post_thumbnail_url(get_the_ID(), 'large');
				$thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'gallery-category');

				?>

				<section class="flexible">
					<div class="flexible__flex-left font-zero">

						<div class="gallery-thumb gallery-thumb--large-single">
							<div class="gallery-thumb__image">

		            <a
		              href = "<?php echo $work['imageSrc']['large']; ?>"
									data-fancybox = "gallery"
									data-caption = "<?php echo $work['title']; ?>">
										<img
											class="gallery-thumb__img"
											alt="<?php echo $work['title']; ?>"
											src="<?php echo $work['imageSrc']['gallery-category']; ?>">

		              <div class="gallery-thumb__shadow-overlay">
		              </div>

								</a>
		          </div>

						</div>

						<div class="gallery-thumb-container gallery-thumb-container--tiny">

						<?php

							$detailCount = 0;

							foreach( $work['detailImages'] as $detail ) {

								$fullsize = $detail['imageSizes']['large'];
								$thumbnail = $detail['imageSizes']['thumbnail'];
								$detailCount = $detailCount + 1;
								$relatedCaption = '';

								?>

								<div class="gallery-thumb gallery-thumb--tiny">

										<a data-fancybox="gallery" href="<?php echo $fullsize; ?>" data-caption="Detail Image <?php echo $detailCount ?>"> <img alt="Detail Image <?php echo $detailCount ?>" src="<?php echo $thumbnail; ?>" width="100%">

										<div class="gallery-thumb__shadow-overlay">
										</div></a>

								</div>

								<?php
							} // end foreach detail image loop
								?>
						</div>
					</div>

					<div class="flexible__flex-right">
						<h2 class="heading heading--small"><?php echo $work['title']; ?></h2>
						<hr class="heading__line heading__line--tight-bottom" />
						<?php

						if ($work['tags'] || $work['categories']) {
							echo 'Tags:&nbsp; ';
							foreach ($work['tags'] as $thisTag) {
								echo "<a href='{$thisTag['permalink']}' class='button button--related' alt='View items with the tag {$thisTag['name']}'>{$thisTag['name']}</a> ";
							}
							foreach ($work['categories'] as $thisCat) {
								echo "<a href='{$thisCat['permalink']}' class='button button--related' alt='View items in the category {$thisCat['name']}'>{$thisCat['name']}</a> ";
							}
							echo '<br /><br />';
						}

						if ($work['medium']) {
							echo $work['medium']['label'] . '<br /><br />';
						}
						if ($work['size']) {
							echo $work['size'] . '<br /><br />';
						}
						if ($work['year']) {
							echo $work['year'] . '<br /><br />';
						}
						if ($work['location']) {
							echo $work['location'] . '<br /><br />';
						}
						if ($work['description']) {
							echo '<div class="text-block">';
							echo $work['description'];
							echo '</div>';
						}

						?>

					</div>
				</section>

				<?php
				if ($work['stories'] || $work['processes']) {
					echo "<h2 class='heading heading--small'>Behind the Artwork - {$work['title']}</h2>";
					echo '<hr class="heading__line heading__line--align-left heading__line--full-width" />';
				}
				?>

				<section class="post-card-container">

					<?php foreach ($work['stories'] as $story) {
								cptCardsOutput($story['ID'], $story['permalink'], $story['title'], $story['excerpt'], 'Story');
						}
					?>

					<?php foreach ($work['processes'] as $process) {
								cptCardsOutput($process['ID'], $process['permalink'], $process['title'], $process['excerpt'], 'Process');
						}
					?>

				</section>




				<?php


			if ($work['detailImages']) {
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

			} // end "has detail images" style / script block


		} // End foreach loop over REST results (should only be one element for this single-artwork page)


	} // End of the default WP loop.
	?>



		</main><!-- #main -->
	</div><!-- #primary -->

<?php
//get_sidebar();
get_footer();
