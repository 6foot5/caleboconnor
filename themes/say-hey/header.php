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
		if (is_front_page()) {
			$thisID = get_queried_object_id();
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



</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'say-hey' ); ?></a>


	<header class="sh-header">
    <div class="sh-wrapper">
      <div class="sh-header__logo">
				<a href="<?php echo site_url(); ?>"><img src="<?php bloginfo('template_url') ?>/img/logo-t.png"><br /><span>caleb o'connor</span></a>
      </div>
      <div class="sh-header__menu-icon">

      </div>
      <div class="sh-header__menu-content">

				<nav id="site-navigation" class="main-navigation">
			    <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'say-hey' ); ?></button>
			    <?php
			    wp_nav_menu( array(
			      'theme_location' => 'menu-1',
			      'menu_id'        => 'primary-menu',
			    ) );
			    ?>
			  </nav><!-- #site-navigation -->

      </div>
    </div>
  </header>





	<div id="content" class="site-content sh-wrapper">
