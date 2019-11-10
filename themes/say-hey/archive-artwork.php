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
<a href="#" class="whatever" onclick="hideSelector('all-thumbs');">RESET</a>
|
<a href="#" onclick="showSelector('all-thumbs');">SHOW ALL</a>

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

			echo '<p>';
			foreach (array_keys($mediumSelectors) as $key) {
				echo '<a href="#" onclick="flipSelector(\'' . $key . '\');">' . $mediumSelectors[$key] . '</a><br />';
				//echo $key . ' > ' . $mediumSelectors[$key] . '<br />';
			}
			echo '</p>';

			echo '<p>';
			foreach (array_keys($yearSelectors) as $key) {
				echo $key . ' > ' . $yearSelectors[$key] . '<br />';
			}
			echo '</p>';

			echo '<p>';
			foreach (array_keys($tagSelectors) as $key) {
				echo $key . ' > ' . $tagSelectors[$key] . '<br />';
			}
			echo '</p>';

			echo '<p>';
			foreach (array_keys($categorySelectors) as $key) {
				echo $key . ' > ' . $categorySelectors[$key] . '<br />';
			}
			echo '</p>';

			echo '<p>';
			foreach (array_keys($hasSelectors) as $key) {
				echo $key . ' > ' . $hasSelectors[$key] . '<br />';
			}
			echo '</p>';

			//print_r($mediumSelectors);

			foreach ( $data as $work ) {

				echo '<div class="all-thumbs gallery-thumb--hidden' . $work['selectors'] . '"><p><a href="' . $work['permalink'] . '">' . $work['title'] . '</a><br />' . $work['selectors'] . '<br />+' . $medium . '+<br />' . $work['categories'];
				echo '<p></div>';
			}

			//print_r($data);

			?>



		</main><!-- #main -->
	</div><!-- #primary -->

<?php

get_footer();
