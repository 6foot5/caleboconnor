
<?php

function sayhey_loginURL() {
  return esc_url(site_url('/'));
}
add_filter('login_headerurl', 'sayhey_loginURL');

function sayhey_loginTitle() {
  return get_bloginfo('name');
}
add_filter('login_headertitle', 'sayhey_loginTitle');

function sayhey_loginCSS() {
  wp_enqueue_style( 'sayhey-style', get_stylesheet_uri(), NULL, microtime());
  wp_enqueue_style( 'sayhey-font-arimo', 'https://fonts.googleapis.com/css?family=Arimo:400,700|Cinzel:400,700|Crimson+Text:400,700');
}
add_action('login_enqueue_scripts', 'sayhey_loginCSS');
