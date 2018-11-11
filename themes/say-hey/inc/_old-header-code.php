<header id="masthead" class="site-header">
  <div class="site-branding">
    <?php
    the_custom_logo();
    if ( is_front_page() && is_home() ) :
      ?>
      <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">HH<?php bloginfo( 'name' ); ?></a></h1>
      <?php
    else :
      ?>
      <p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">PP<?php bloginfo( 'name' ); ?></a></p>
      <?php
    endif;
    $say_hey_description = get_bloginfo( 'description', 'display' );
    if ( $say_hey_description || is_customize_preview() ) :
      ?>
      <p class="site-description">PD<?php echo $say_hey_description; /* WPCS: xss ok. */ ?></p>
    <?php endif; ?>
  </div><!-- .site-branding -->

  <nav id="site-navigation" class="main-navigation">
    <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'say-hey' ); ?></button>
    <?php
    wp_nav_menu( array(
      'theme_location' => 'menu-1',
      'menu_id'        => 'primary-menu',
    ) );
    ?>
  </nav><!-- #site-navigation -->
</header><!-- #masthead -->

<!--
<nav class="main-navigation">
  <ul>
    <li><a href="#about">About</a>
      <ul>
        <li><a href="#exhibitions">Exhibitions</a>
        <li><a href="#awards">Awards</a>
        <li><a href="#resume">Resume</a>
        <li><a href="#contact">Contact</a>
      </ul>
    </li>
    <li><a href="">Artwork</a>
      <ul>
        <li><a href="#painting">Painting</a>
        <li><a href="#sculpture">Sculpture</a>
        <li><a href="#public">Public Projects</a>
        <li><a href="#private">Private Commissions</a>
      </ul>
    </li>
    <li><a href="#stories">Stories</a></li>
    <li><a href="#process">Process</a></li>
  </ul>
</nav>
-->
