<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package SayHey
 */

?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js"> <!-- Modernizr will remove the "no-" prefix if JS is available -->
<head>

	<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico"/>

	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Caleb O'Connor, accomplished fine artist and Fulbright scholar. Commissions, narrative realist works, portraiture and sculpture works that challenge."/>
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php	wp_head(); ?>

	<?php

		$thisID = get_queried_object_id();
		$thisType = get_post_type($thisID);

		if ( is_front_page() || ( is_404() && !($wp->request == 'artwork/gallery' || $wp->request == 'artwork/gallery/') ) ) {

			if ( is_front_page() ) {

				// Preferred images from the website options page
				$optionsHomeImageDesktop = get_field('homepage_image_desktop', 'options');
				$optionsHomeImageMobile = get_field('homepage_image_mobile', 'options');

				// Backup images (featured image for desktop, banner for mobile)
				$featuredImageArray = wp_get_attachment_image_src( get_post_thumbnail_id($thisID), 'large' );
				$bannerImageArray = get_field('page_banner_background_image');

				// If there's an options value for DESKTOP homepage image...
				if ( $optionsHomeImageDesktop ) {
					$bgImgURL = $optionsHomeImageDesktop['sizes']['large'];
				}
				// If there's no option for homepage desktop image, use front-page featured image
				else {
					$featuredImageArray = wp_get_attachment_image_src( get_post_thumbnail_id($thisID), 'large' );
					$bgImgURL = $featuredImageArray[0];
				}

				// If there's an options value for MOBILE homepage image...
				if ( $optionsHomeImageMobile ) {
					$bgImgSmall = $optionsHomeImageMobile['sizes']['medium_large'];
				}
				// If there's no option for homepage mobile image, use front-page banner image
				else {
					$bgImgSmall = $bannerImageArray['sizes']['medium_large'];
				}

				$bodyClass = '.home';

			}

			// We've got a 404, let's get a random bg image to look pretty
			else {

	/*
		This query is grabbing a random image (attachment) from the media library
		It is looking for images selected in site settings page (WP admin)
		These images will serve as background for 404 page
	*/

				$bgImagesFound = get_field('page_not_found_backgrounds', 'options');
				$bgPermalinks = array();

				if ( $bgImagesFound ) {
					$numBGs = 0;
					foreach($bgImagesFound as $image) {
						$thisImg = wp_get_attachment_image_src($image->ID, 'large');
						array_push($bgPermalinks, $thisImg[0]);
						$numBGs += 1;
					}
					$bgImgURL = $bgPermalinks[mt_rand(0,$numBGs-1)];
				}
				else {
					$bgImgURL = get_theme_file_uri('/img/canvas-background-light.jpg');
				}

				$bgImgSmall = $bgImgURL;

				$bodyClass = '.error404';


			} // End if-else block to pull background images for home and 404

			// Pull different bg sizes here and use media queries below to change bg src on the fly
			// https://www.w3schools.com/Css/css_rwd_images.asp
			// https://stackoverflow.com/questions/31848576/html-picture-or-srcset-for-responsive-images

			?>

			<style type="text/css">
				<?php echo $bodyClass; ?> {
					width: 100%;
			    height: auto;
			    background-image: url('<?php echo $bgImgSmall; ?>');
			    background-size: cover;
					background-position: center center;
					background-repeat: no-repeat;
					background-attachment: fixed;
					-ms-background-size: cover;
					-o-background-size: cover;
					-moz-background-size: cover;
					-webkit-background-size: cover;
				}
				@media (min-width: 630px) {
					<?php echo $bodyClass; ?> {
						background-image: url('<?php echo $bgImgURL; ?>');
					}
				}
			</style>

			<?php
		}
	?>

	<meta name="pageID" content="<?php echo $thisID; ?>">

	<meta name="postType" content="<?php echo $thisType; ?>" />

</head>

<?php
?>
<body <?php body_class(); ?>>

<?php

if ($thisType != 'spin') {

?>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'sayhey' ); ?></a>

	<!-- This block is for the desktop site logo, to take it out of the
	site-header sticky area so it will scroll freely. Will disappear for
	small displays -->
	<div class="content-area">
		<a class="no-border" href="<?php echo site_url(); ?>"><div class="site-header__logo">
			<div class="site-header__logo__image"></div>
			<div class="site-header__logo__text"><span>Caleb O'Connor</span></div>
		</div></a><!-- .site-header__logo -->
	</div><!-- .site-header__logo -->

	<header id="masthead" class="site-header">

		<div class="content-area">

			<!-- This block is for the mobile site header
			Will disappear for desktop displays -->
			<div class="mobile-header">
				<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
					<i id="hamburger" class="fal fa-bars"></i>
				</button>
				<a class="no-border" href="<?php echo site_url(); ?>"><div class="mobile-header__text"><span>Caleb O'Connor</span></div></a>
				<a class="no-border" href="<?php echo site_url(); ?>"><div class="mobile-header__image"></div></a>
			</div><!-- .mobile-header -->


			<nav id="site-navigation" class="main-navigation">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'header',
						'menu_id' => 'primary-menu'
					)
				);
				?>
			</nav><!-- .main-navigation -->

		</div> <!-- .content-area -->


	</header><!-- .site-header -->

	<?php
		if ( !is_front_page() && ( !is_404() || ($wp->request == 'artwork/gallery' || $wp->request == 'artwork/gallery/') ) ) {
			pageBanner();
		}

	?>

<?php
	$siteContentExtraClass = '';

	if ( !is_front_page() && ( !is_404() || ($wp->request == 'artwork/gallery' || $wp->request == 'artwork/gallery/') ) ) {
		$siteContentExtraClass .= " site-content--bg-cover site-content--padded-bottom";
	}
	if ( is_404() && !($wp->request == 'artwork/gallery' || $wp->request == 'artwork/gallery/') ) {
		$siteContentExtraClass .= "";
	}
?>

	<div id="content" class="site-content <?php echo $siteContentExtraClass; ?>">

<?php
}		// Only output visible header if not on a 'spin' type (Magic360)

	if (isset($_SERVER['HTTP_REFERER'])) {
		$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
	}
	else {
		$url = '';
	}
	// echo $url;

	// $allImageSizes = get_intermediate_image_sizes();
	// print_r($allImageSizes);
?>
