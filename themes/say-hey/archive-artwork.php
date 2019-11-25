<?php
/**
 * This template is to display the "All Artwork" Archive page.
 * Separate from the curated "Gallery" nav, this is more of a self-guided exploration
 *
 * @package SayHey
 */

get_header();
?>

<?php //pageBanner();	?>

	<div id="primary" class="content-area content-area--padded-sides content-area--bg-color">
		<main id="main" class="site-main contents-aligncenter">

			<div class="aligncenter">
			  <h1 class="heading">Explore All Works</h1>
				<div class="selected-filters"></div>
				<hr class="heading__line" />
			</div>

			<?php

			$argsREST['per_page'] = -1;

			$request = new WP_REST_Request( 'GET', '/sayhey/v1/artwork' );
			$request->set_query_params( $argsREST );
			$response = rest_do_request( $request );
			$server = rest_get_server();
			$data = $server->response_to_data( $response, false );

			// Encode as JSON if needed for client-side processing. In this case, no.
			// $json = wp_json_encode( $data );

?>

<div class="filter-buttons">
	<!--<button id="show-none" class="button button--inline">Show None</button>-->
	<button id="show-all" class="button button--inline">Show All Work</button>
	<button id="show-filters" class="button button--inline"><span class="show-hide-filter">Hide Options</span> &nbsp; <i class="fal fa-filter"></i></button>
</div>

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
						$thisLabel = $oneTag['name'];
						$thisValue = 'tag-' . $oneTag['slug'];
						if ( !array_key_exists($thisValue, $tagSelectors) ) {
							$tagSelectors[$thisValue] = $thisLabel;
						}
					}
					ksort($tagSelectors);
				}

				if ($work['categories']) {
					foreach ($work['categories'] as $oneCat) {
						$thisLabel = $oneCat['name'];
						$thisValue = 'category-' . $oneCat['slug'];
						if ( !array_key_exists($thisValue, $tagSelectors) ) {
							$categorySelectors[$thisValue] = $thisLabel;
						}
					}
					ksort($categorySelectors);
				}

				if ($work['stories']) {
					if ( !array_key_exists('has-story', $hasSelectors) ) {
						$hasSelectors['has-story'] = 'Its story told';
					}
				}

				if ($work['processes']) {
					if ( !array_key_exists('has-process', $hasSelectors) ) {
						$hasSelectors['has-process'] = 'Its process explained';
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

				echo '<select id="filter-medium" class="artwork-filter__dropdown"><option value="">Medium</option>';
				foreach (array_keys($mediumSelectors) as $key) {
					echo '<option value="' . $key . '">' . $mediumSelectors[$key] . '</option>';
					//echo $key . ' > ' . $mediumSelectors[$key] . '<br />';
				}
				echo '</select>';
			}

			if ($yearSelectors) {

				echo '<select id="filter-year" class="artwork-filter__dropdown" onchange=""><option value="">Year</option>';
				foreach (array_keys($yearSelectors) as $key) {
					echo '<option value="' . $key . '">' . $yearSelectors[$key] . '</option>';
				}
				echo '</select>';
			}

			if ($tagSelectors) {

				echo '<select id="filter-tag" class="artwork-filter__dropdown" onchange=""><option value="">Tags</option>';
				foreach (array_keys($tagSelectors) as $key) {
					echo '<option value="' . $key . '">' . $tagSelectors[$key] . '</option>';
				}
				echo '</select>';
			}

			if ($categorySelectors) {

				echo '<select id="filter-category" class="artwork-filter__dropdown" onchange=""><option value="">Categories</option>';
				foreach (array_keys($categorySelectors) as $key) {
					echo '<option value="' . $key . '">' . $categorySelectors[$key] . '</option>';
				}
				echo '</select>';
			}

			if ($hasSelectors) {

				echo '<select id="filter-has" class="artwork-filter__dropdown" onchange=""><option value="">Artwork that has...<i class="fa fa-times"></i></option>';
				foreach (array_keys($hasSelectors) as $key) {
					echo '<option value="' . $key . '">' . $hasSelectors[$key] . '</option>';
				}
				echo '</select>';
			}

			echo '</div>';

			echo '<div class="gallery-thumb-container">';

			$captionArgs = array('get_spin' => false, 'get_stories' => true, 'get_processes' => true);
			galleryThumbsOutput($data, $captionArgs, true, 'all-thumbs');

			echo '</div>';



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
