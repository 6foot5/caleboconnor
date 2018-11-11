<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Say_Hey
 */

?>

	</div><!-- #content -->

	<footer id="colophon" class="sh-footer">
		<a class="sh-footer__link" href="https://www.facebook.com/OConnorArtStudios/" target="_blank"><i class="fa fa-facebook fa-2x"></i></a>
		<a class="sh-footer__link" href="https://www.instagram.com/oconnorartstudios/" target="_blank"><i class="fa fa-instagram fa-2x"></i></a>
		<a class="sh-footer__link" href="https://twitter.com/oconnorartists" target="_blank"><i class="fa fa-twitter fa-2x"></i></a>
		<a class="sh-footer__link" href="https://www.pinterest.com/Oconnorarts/" target="_blank"><i class="fa fa-pinterest fa-2x"></i></a>
		<a class="sh-footer__link" href="<?php echo get_permalink(78); ?>"><i class="fa fa-envelope-o fa-2x"></i></a>

	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
