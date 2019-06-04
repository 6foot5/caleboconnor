<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package SayHey
 */

?>

<?php

$thisID = get_queried_object_id();
$thisType = get_post_type($thisID);

//echo '****' . $thisType . '***';

if ($thisType != 'spin' && !is_front_page()) {

?>
</div><!-- #content | .site-content-->

	<footer id="colophon" class="site-footer">
		<a title="Check out O'Connor Art Studios on Facebook" class="site-footer__link" href="https://www.facebook.com/OConnorArtStudios/" target="_blank"><i class="fab fa-facebook-f"></i></a>
		<a title="Follow O'Connor Art Studios on Instagram" class="site-footer__link" href="https://www.instagram.com/oconnorartstudios/" target="_blank"><i class="fab fa-instagram"></i></a>
		<a title="Follow O'Connor Art Studios on Twitter" class="site-footer__link" href="https://twitter.com/oconnorartists" target="_blank"><i class="fab fa-twitter"></i></a>
		<a title="Check out O'Connor Art Studios on Pinterest" class="site-footer__link" href="https://www.pinterest.com/Oconnorarts/" target="_blank"><i class="fab fa-pinterest-p"></i></i></a>
		<a title="Vist O'Connor Studios" class="site-footer__link" href="http://www.oconnorartstudios.com/" target="_blank"><i class="far fa-palette"></i></a>
		<a class="site-footer__link" href="<?php echo get_permalink(78); ?>"><i class="fa fa-envelope-o"></i></a>

	</footer><!-- #colophon | .site-footer-->
</div><!-- #page | .site-->

<?php

}		// Only output visible footer if not on a 'spin' type (Magic360)
?>

<?php wp_footer(); ?>

</body>
</html>
