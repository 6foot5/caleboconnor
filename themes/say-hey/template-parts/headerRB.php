<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Say_Hey
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
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
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'say-hey' ); ?></a>


	<header id="masthead" class="site-header" style="background-image: url(<?php echo pageBanner(); ?>); background-size: cover; background-repeat: no-repeat;">

    <div class="content-area">

      <div class="site-header__logo">
				<a href="<?php echo site_url(); ?>"><img src="<?php bloginfo('template_url') ?>/img/logo-t.png"><br /><span>caleb o'connor</span></a>
      </div><!-- #site-navigation -->


      <div class="site-header__menu-content">

				<nav id="site-navigation" class="main-navigation">
			    <!-- <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><i class="fa fa-bars fa-2x"></i> Menu</button> -->
					<div class="menu-toggle site-header__menu-icon" aria-controls="primary-menu" aria-expanded="false">
						<div class="site-header__menu-icon__middle"></div>
		      </div><!-- #site-header__menu-icon -->

			    <?php
			    wp_nav_menu( array(
			      'theme_location' => 'header',
			      'menu_id'        => 'primary-menu',
			    ) );
			    ?>
			  </nav><!-- .main-navigation -->

      </div><!-- .site-header__menu-content -->

    </div><!-- .content-area -->
  </header><!-- .site-header -->


	<div id="content" class="site-content">

		<?php
			if ( !is_front_page()) {
				//pageBanner();
			}
		?>
<?php


}		// Only output visible header if not on a 'spin' type (Magic360)
?>
