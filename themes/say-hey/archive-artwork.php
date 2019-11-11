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
			  <h1 class="heading">Explore All Works<i class="selected-filter"></i></h1>
				<hr class="heading__line" />
			</div>

			<?php

			$argsREST = array(
				'post_type' => 'artwork',
				'orderby' => 'title',
				'order' => 'asc'
			);

			$argsREST['per_page'] = -1;

			if (false) {
				$argsREST['id'] = 642;
			}

			if (false) {
				$argsREST['tax_query'] = array(
					array(
						'taxonomy' => 'post_tag',
						'field' => 'slug',
						'terms' => 'fishing'
					)
				);
			}

			//galleryThumbsOutput($args, true);

			$request = new WP_REST_Request( 'GET', '/sayhey/v1/artwork' );
			$request->set_query_params( $argsREST );
			$response = rest_do_request( $request );
			$server = rest_get_server();
			$data = $server->response_to_data( $response, false );
			//$json = wp_json_encode( $data );

?>

	<a href="#" class="button button--inline" onclick="hideAll();">Show None</a>
	<a href="#" class="button button--inline" onclick="showAll();">Show All</a>

<?php

			$mediumSelectors = array();
			$yearSelectors = array();
			$tagSelectors = array();
			$categorySelectors = array();
			$hasSelectors = array();

			foreach ( $data as $work ) {

				if ($work['medium']) {
					$thisLabel = $work['medium']['label'];
					$thisValue = 'medium-' . $work['medium']['value'];
					if ( !array_key_exists($thisValue, $mediumSelectors) ) {
						$mediumSelectors[$thisValue] = $thisLabel;
					}
					ksort($mediumSelectors);
				}

				if ($work['year']) {
					$thisLabel = $work['year'];
					$thisValue = 'year-' . $work['year'];
					if ( !array_key_exists($thisValue, $yearSelectors) ) {
						$yearSelectors[$thisValue] = $thisLabel;
					}
					ksort($yearSelectors);
				}

				if ($work['tags']) {
					foreach ($work['tags'] as $oneTag) {
						$thisLabel = $oneTag['tagName'];
						$thisValue = 'tag-' . $oneTag['tagSlug'];
						if ( !array_key_exists($thisValue, $tagSelectors) ) {
							$tagSelectors[$thisValue] = $thisLabel;
						}
					}
					ksort($tagSelectors);
				}

				if ($work['categories']) {
					foreach ($work['categories'] as $oneCat) {
						$thisLabel = $oneCat['catName'];
						$thisValue = 'category-' . $oneCat['catSlug'];
						if ( !array_key_exists($thisValue, $tagSelectors) ) {
							$categorySelectors[$thisValue] = $thisLabel;
						}
					}
					ksort($categorySelectors);
				}

				if ($work['stories']) {
					if ( !array_key_exists('has-story', $hasSelectors) ) {
						$hasSelectors['has-story'] = 'Has Story';
					}
				}

				if ($work['processes']) {
					if ( !array_key_exists('has-process', $hasSelectors) ) {
						$hasSelectors['has-process'] = 'Has Process';
					}
				}

				if ($work['spinID']) {
					if ( !array_key_exists('has-spin', $hasSelectors) ) {
						$hasSelectors['has-spin'] = 'Has Spin';
					}
				}

			}

			ksort($hasSelectors);

			echo '<div class="artwork-filter">';

			if ($mediumSelectors) {

				echo '<select class="artwork-filter__dropdown" onchange="flipSelector(this);"><option value="">Medium</option>';
				foreach (array_keys($mediumSelectors) as $key) {
					echo '<option value="' . $key . '">' . $mediumSelectors[$key] . '</option>';
					//echo $key . ' > ' . $mediumSelectors[$key] . '<br />';
				}
				echo '</select>';
			}

			if ($yearSelectors) {

				echo '<select class="artwork-filter__dropdown" onchange="flipSelector(this);"><option value="">Year</option>';
				foreach (array_keys($yearSelectors) as $key) {
					echo '<option value="' . $key . '">' . $yearSelectors[$key] . '</option>';
				}
				echo '</select>';
			}

			if ($tagSelectors) {

				echo '<select class="artwork-filter__dropdown" onchange="flipSelector(this);"><option value="">Tags</option>';
				foreach (array_keys($tagSelectors) as $key) {
					echo '<option value="' . $key . '">' . $tagSelectors[$key] . '</option>';
				}
				echo '</select>';
			}

			if ($categorySelectors) {

				echo '<select class="artwork-filter__dropdown" onchange="flipSelector(this);"><option value="">Categories</option>';
				foreach (array_keys($categorySelectors) as $key) {
					echo '<option value="' . $key . '">' . $categorySelectors[$key] . '</option>';
				}
				echo '</select>';
			}

			if ($hasSelectors) {

				echo '<select class="artwork-filter__dropdown" onchange="flipSelector(this);"><option value="">Behind the Artwork <i class="fa fa-times"></i></option>';
				foreach (array_keys($hasSelectors) as $key) {
					echo '<option value="' . $key . '">' . $hasSelectors[$key] . '</option>';
				}
				echo '</select>';
			}

			echo '</div>';

			galleryThumbsOutput($data, NULL, true, 'all-thumbs');



/*
			foreach ( $data as $work ) {

				echo '<div class="all-thumbs gallery-thumb--hidden' . $work['selectors'] . '"><p><a href="' . $work['permalink'] . '">' . $work['title'] . '</a><br />' . $work['selectors'] . '<br />+' . $work['medium'] . '+<br />' . $work['categories'];
				echo '<br />' . $work['imageSrc']['large'] . '<br />' . $work['imageSrc']['thumbnail'] . '</div>';
			}
*/
			//print_r($data);

			?>


		</main><!-- #main -->
	</div><!-- #primary -->

<?php

get_footer();
