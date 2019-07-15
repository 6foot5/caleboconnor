<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package SayHey
 */


 if ($wp->request == 'artwork/gallery') {

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
	$siteMainExtraClass = "";
	if ($wp->request == 'artwork/gallery') {
		$siteMainExtraClass .= "contents-aligncenter";
	}
?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main <?php echo $siteMainExtraClass; ?>">

			<?php
				if ($wp->request == 'artwork/gallery') {
					require get_theme_file_path('/template-parts/tax-landing.php');
				}
				else {
			?>
					<section class="error-404 not-found">
						<header class="page-header">
							<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'sayhey' ); ?></h1>
						</header><!-- .page-header -->

						<div class="page-content">

							<p><?php esc_html_e( '404!! It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'sayhey' );
							echo $wp->request; ?></p>

							<?php
							get_search_form();

							the_widget( 'WP_Widget_Recent_Posts' );
							?>

							<div class="widget widget_categories">
								<h2 class="widget-title"><?php esc_html_e( 'Most Used Categories', 'sayhey' ); ?></h2>
								<ul>
									<?php
									wp_list_categories( array(
										'orderby'    => 'count',
										'order'      => 'DESC',
										'show_count' => 1,
										'title_li'   => '',
										'number'     => 10,
									) );
									?>
								</ul>
							</div><!-- .widget -->

							<?php
							/* translators: %1$s: smiley */
							$sayhey_archive_content = '<p>' . sprintf( esc_html__( 'Try looking in the monthly archives. %1$s', 'sayhey' ), convert_smilies( ':)' ) ) . '</p>';
							the_widget( 'WP_Widget_Archives', 'dropdown=1', "after_title=</h2>$sayhey_archive_content" );

							the_widget( 'WP_Widget_Tag_Cloud' );
							?>

						</div><!-- .page-content -->
					</section><!-- .error-404 -->

			<?php
				}
			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
