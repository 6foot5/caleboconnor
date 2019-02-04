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
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php	wp_head(); ?>

	<?php

		$thisID = get_queried_object_id();
		$thisType = get_post_type($thisID);

		if (is_front_page()) {
			$bgImgURL = wp_get_attachment_url( get_post_thumbnail_id($thisID) );

			?>

			<style type="text/css">
				.home {
					width: 100%;
			    height: auto;
			    background-image: url('<?php echo $bgImgURL; ?>');
			    background-size: cover;
					background-position: center center;
					background-repeat: no-repeat;
					background-attachment: fixed;
				}
			</style>

			<?php
		}
	?>

	<meta name="pageID" content="<?php echo $thisID; ?>">

	<meta name="postType" content="<?php echo $thisType; ?>" />

</head>

<body <?php body_class(); ?>>

<?php

if ($thisType != 'spin') {

?>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'sayhey' ); ?></a>

	<header id="masthead" class="site-header">

		<div class="content-area">

			<div class="mobile-header">
				<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
					<?php
					//echo sayhey_get_svg( array( 'icon' => 'bars' ) );
					//echo sayhey_get_svg( array( 'icon' => 'close' ) );
					//_e( '', 'sayhey' );
					?>
					<i id="hamburger" class="fa fa-bars"></i>
				</button>

				<a href="<?php echo site_url(); ?>"><div class="site-header__logo">
					<div class="site-header__logo__image"></div>
					<div class="site-header__logo__text"><span>caleb o'connor</span></div>
				</div></a><!-- .site-header__logo -->
			</div>

			<nav id="site-navigation" class="main-navigation">

				<?php
				wp_nav_menu( array(	'theme_location' => 'header',	'menu_id' => 'primary-menu') );
				//wp_nav_menu( array(	'theme_location' => 'header',	'menu_id' => 'primary-menu',	'link_after' => '<i></i>') );
				?>
			</nav><!-- .main-navigation -->

		</div>


	</header><!-- .site-header -->

	<?php
		if ( !is_front_page()) {
			pageBanner();
		}
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
