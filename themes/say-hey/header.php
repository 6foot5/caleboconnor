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
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php	wp_head(); ?>

	<?php

		$thisID = get_queried_object_id();
		$thisType = get_post_type($thisID);

		if (is_front_page()) {
			$bgImgURL = wp_get_attachment_url( get_post_thumbnail_id($thisID) );

			if(get_field('page_banner_background_image')) {
				$bannerImg = get_field('page_banner_background_image');
				$bgImgSmall = $bannerImg['sizes']['large'];
			}

			// Pull different bg sizes here and use media queries below to change bg src on the fly
			// https://www.w3schools.com/Css/css_rwd_images.asp
			// https://stackoverflow.com/questions/31848576/html-picture-or-srcset-for-responsive-images

			?>

			<style type="text/css">
				.home {
					width: 100%;
			    height: auto;
			    background-image: url('<?php echo $bgImgSmall; ?>');
			    background-size: cover;
					background-position: center center;
					background-repeat: no-repeat;
					background-attachment: fixed;

				}
				@media (min-width: 630px) {
					.home {
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
<body ontouchstart="" onmouseover="" <?php body_class(); ?>>

<?php

if ($thisType != 'spin') {

?>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'sayhey' ); ?></a>

	<!-- This block is for the desktop site logo, to take it out of the
	site-header sticky area so it will scroll freely. Will disappear for
	mobile displays -->
	<div class="content-area">
		<a href="<?php echo site_url(); ?>"><div class="site-header__logo">
			<div class="site-header__logo__image"></div>
			<div class="site-header__logo__text"><span>caleb o'connor</span></div>
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
				<a href="<?php echo site_url(); ?>"><div class="mobile-header__text"><span>caleb o'connor</span></div></a>
				<a href="<?php echo site_url(); ?>"><div class="mobile-header__image"></div></a>
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
		if ( !is_front_page()) {
			pageBanner();
		}
		if (is_post_type_archive('artwork')) {
			//echo 'Artwork archive page!';
		}
		//echo '++--' . get_post_type() . '++';
		//$mediaCats = get_categories(array('taxonomy' => 'nt_wmc_folder'));
		//print_r($mediaCats);

	?>



	<div id="content" class="site-content">

		<?php
			if ( !is_front_page()) {
				//pageBanner();
			}
		?>
<?php


}		// Only output visible header if not on a 'spin' type (Magic360)
?>
<?php
  $url = htmlspecialchars($_SERVER['HTTP_REFERER']);
?>

<!-- <a href="<?php echo $url ?>" onclick="window.history.back()">BACK</a> -->
