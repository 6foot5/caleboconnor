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
<html <?php language_attributes(); ?>>
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

				$bgImgURL = wp_get_attachment_url( get_post_thumbnail_id($thisID) );

				if( get_field('page_banner_background_image') ) {
					$bannerImg = get_field('page_banner_background_image');
					$bgImgSmall = $bannerImg['sizes']['medium_large'];
				}
				else {
					$bgImgSmall = $bgImgURL;
				}
				$bodyClass = '.home';
			}

			// We've got a 404, let's get a random bg image to look pretty
			else {

				$queryArgs = array(
					'post_status' => 'inherit',
					'posts_per_page' => -1,
					'post_type' => 'attachment',
				);

	/*
		This query is grabbing a random image (attachment) from the media library
		It is looking for a specific "folder" (ID 87)
		This folder contains images to serve as background for 404 page
	*/

				$queryArgs['tax_query'] = array(
					array(
						'taxonomy' => 'nt_wmc_folder',
						'terms' => array( 87 ), // ID of the "Random Banners" media folder
						'field' => 'term_id',
					)
				);

				$the_query = new WP_Query( $queryArgs );

				if ( $the_query->have_posts() ) {
					$theBGs = array();
					$numBGs = 0;
						while ( $the_query->have_posts() ) {
						$the_query->the_post();
							$thisImg = wp_get_attachment_image_src(get_the_ID(), 'medium_large');
							array_push($theBGs, $thisImg[0]);
							$numBGs += 1;
						}
						$bgImgURL = $theBGs[mt_rand(0,$numBGs-1)];
				}
				else {
					$bgImgURL = get_theme_file_uri('/img/banner.jpg');
				}

				$bgImgSmall = $bgImgURL;

				$bodyClass = '.error404';

				wp_reset_postdata();
			}

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
// ontouchstart="" onmouseover="" relates to hover/touch behavior
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
				//wp_nav_menu( array(	'theme_location' => 'header',	'menu_id' => 'primary-menu',	'link_after' => '<i></i>') );
				?>
			</nav><!-- .main-navigation -->

		</div> <!-- .content-area -->


	</header><!-- .site-header -->

	<?php
		if ( !is_front_page() && ( !is_404() || ($wp->request == 'artwork/gallery' || $wp->request == 'artwork/gallery/') ) ) {
			pageBanner();
		}
		if (is_post_type_archive('artwork')) {
			//echo 'Artwork archive page!';
		}
		//echo '++--' . get_post_type() . '++';
		//$mediaCats = get_categories(array('taxonomy' => 'nt_wmc_folder'));
		//print_r($mediaCats);

	?>

<?php
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
?>


<?php
  $url = htmlspecialchars($_SERVER['HTTP_REFERER']);
	//echo $url;
?>
