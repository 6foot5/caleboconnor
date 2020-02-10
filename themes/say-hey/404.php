<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package SayHey
 */


 if ($wp->request == 'artwork/gallery' || $wp->request == 'artwork/gallery/') {

   // WP will set page title to "not found"; reset it to Gallery title
    add_filter( 'pre_get_document_title', 'cyb_change_page_title' );
    function cyb_change_page_title () {
      return 'Gallery | ' . get_bloginfo('name');
    }
    status_header(200); // WP thinks the artwork/gallery is a 404, but it's wrong. make it 200
 }

get_header();
?>

<?php
	$siteMainExtraClass = '';
  $contentAreaExtraClass = '';
	if ($wp->request == 'artwork/gallery' || $wp->request == 'artwork/gallery/') {
    $siteMainExtraClass .= "contents-aligncenter";
    $contentAreaExtraClass .= "content-area--padded-sides content-area--bg-color";
	}
?>

<?php
				if ($wp->request == 'artwork/gallery' || $wp->request == 'artwork/gallery/') {
?>
          <div id="primary" class="content-area <?php echo $contentAreaExtraClass; ?>">
        		<main id="main" class="site-main <?php echo $siteMainExtraClass; ?>">
<?php
					require get_theme_file_path('/template-parts/tax-landing.php');
				}
				else {
?>


					<section class="error-404 not-found contents-aligncenter">
						<header class="page-header">
							<h1 class="page-title"><?php esc_html_e( '404 - No such page', 'sayhey' ); ?></h1>
              <hr class="heading__line" />
						</header><!-- .page-header -->

						<div class="page-content">

              <P><a class="button" href="<?php echo site_url( '/artwork/gallery', 'https' ); ?>">View Caleb's Work</a></P>

							<?php	//get_search_form(); ?>

						</div><!-- .page-content -->
					</section><!-- .error-404 -->

<?php
				}

        if ($wp->request == 'artwork/gallery' || $wp->request == 'artwork/gallery/') {
?>
            </main><!-- #main -->
          </div><!-- #primary -->
<?php
				}
?>

<?php
get_footer();
