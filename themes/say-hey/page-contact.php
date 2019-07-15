<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 */

get_header();
?>


<?php // pageBanner();	?>


	<div id="primary" class="content-area content-area--padded-sides">
		<main id="main" class="site-main">

			<header class="page-header heading heading--centered">
			  <h1 class="heading">About - Contact</h1>
			  <hr class="heading__line" />
			</header><!-- .page-header -->

			<div class="flexible">
				<div class="flexible__flex-left flexible__flex-left--left-align search-gutter">
					<h2>Contact Caleb</h4>

					<?php

					while ( have_posts() ) :
						the_post();
						the_content();
					endwhile; // End of the loop.

					?>
				</div>
				<div class="flexible__flex-right flexible__flex-right--left-align">
					<h2>Visit Us Online</h4>

						<a title="Check out O'Connor Art Studios on Facebook" class="contact-card__link" href="https://www.facebook.com/OConnorArtStudios/" target="_blank" rel="noopener noreferrer">
							<div class="contact-card">
								<div class="contact-card__icon"><i class="fab fa-facebook-f fa-2x"></i></div>
								<div class="contact-card__text">Facebook &nbsp <i class="fal fa-external-link-square"></i></div>
							</div>
						</a>

						<a title="Follow O'Connor Art Studios on Instagram" class="contact-card__link" href="https://www.instagram.com/oconnorartstudios/" target="_blank" rel="noopener noreferrer">
							<div class="contact-card">
								<div class="contact-card__icon"><i class="fab fa-instagram fa-2x"></i></div>
								<div class="contact-card__text">Instagram &nbsp <i class="fal fa-external-link-square"></i></div>
							</div>
						</a>

						<a title="Follow O'Connor Art Studios on Twitter" class="contact-card__link" href="https://twitter.com/oconnorartists" target="_blank" rel="noopener noreferrer">
							<div class="contact-card">
								<div class="contact-card__icon"><i class="fab fa-twitter fa-2x"></i></div>
								<div class="contact-card__text">Twitter &nbsp <i class="fal fa-external-link-square"></i></div>
							</div>
						</a>

						<a title="Check out O'Connor Art Studios on Pinterest" class="contact-card__link" href="https://www.pinterest.com/Oconnorarts/" target="_blank" rel="noopener noreferrer">
							<div class="contact-card">
								<div class="contact-card__icon"><i class="fab fa-pinterest-p fa-2x"></i></div>
								<div class="contact-card__text">Pinterest &nbsp <i class="fal fa-external-link-square"></i></div>
							</div>
						</a>

						<a title="Vist O'Connor Art Studios in Tuscaloosa" class="contact-card__link" href="http://www.oconnorartstudios.com/" target="_blank" rel="noopener noreferrer">
							<div class="contact-card">
								<div class="contact-card__icon"><i class="far fa-palette fa-2x"></i></div>
								<div class="contact-card__text">O'Connor Art Studios &nbsp <i class="fal fa-external-link-square"></i></div>
							</div>
						</a>

				</div>
			</div>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
//get_sidebar();
get_footer();
