<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package SayHey
 */

?>

<article class="search-result--fallback" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( sprintf( '<h2 class="heading--smallest"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
	</header><!-- .entry-header -->


	<?php
	$thumbURL = wp_get_attachment_image_src(get_post_thumbnail_id(), 'thumbnail')[0];

	if ($thumbURL) {
	?>
		<a class="img" href="<?php echo esc_url( get_permalink() ); ?>" title="<?php get_the_title() ?>">
			<img src="<?php echo $thumbURL; ?>" />
			<div class="search-result--fallback__label">
				<?php echo get_post_type(); ?>
			</div>
		</a>
	<?php
	}
	?>

	<div class="entry-summary">
		<?php //the_excerpt(); ?>
	</div><!-- .entry-summary -->

</article><!-- #post-<?php the_ID(); ?> -->
