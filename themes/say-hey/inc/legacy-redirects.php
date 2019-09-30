<?php

if ( isset($_SERVER['HTTPS']) &&
      ( $_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1 ) ||
      isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
      $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'
    ) {

  $protocol = 'https://';
}
else {
  $protocol = 'http://';
}

$currenturl = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$currenturl_relative = wp_make_link_relative($currenturl);

$urlParts = explode('/', $currenturl_relative);

if ($urlParts[1] == 'caleb') { // That is, if request is for a page on the old site

  if ( $currenturl_relative == '/caleb/' || $currenturl_relative == '/caleb' ) {
    if (wp_safe_redirect( get_site_url(), 301 ) ) {
      exit;
    }
  }
  elseif ( $currenturl_relative == '/caleb/portfolio/index.asp?glID=33&displayID=1&imgID=1&label=The%20Process' ) {
    if (wp_safe_redirect( get_site_url(null, 'process/'), 301 ) ) {
      exit;
    }
  }
  elseif ( $currenturl_relative == '/caleb/portfolio/index.asp?glID=21&displayID=1&imgID=1&label=Figurative' ) {
    if (wp_safe_redirect( get_site_url(null, 'artwork/gallery/painting/figurative/'), 301 ) ) {
      exit;
    }
  }
  elseif ( $currenturl_relative == '/caleb/portfolio/index.asp?glID=27&displayID=1&imgID=1&label=Portraits' ) {
    if (wp_safe_redirect( get_site_url(null, 'artwork/gallery/painting/portraits/'), 301 ) ) {
      exit;
    }
  }
  elseif ( $currenturl_relative == '/caleb/portfolio/index.asp?glID=30&displayID=1&imgID=1&label=Still%20Life' ) {
    if (wp_safe_redirect( get_site_url(null, 'artwork/gallery/painting/still-life/'), 301 ) ) {
      exit;
    }
  }
  elseif ( $currenturl_relative == '/caleb/portfolio/index.asp?glID=24&displayID=1&imgID=1&label=Interiors' ) {
    if (wp_safe_redirect( get_site_url(null, 'artwork/gallery/painting/interiors/'), 301 ) ) {
      exit;
    }
  }
  elseif ( $currenturl_relative == '/caleb/portfolio/index.asp?glID=25&displayID=1&imgID=1&label=Landscapes' ) {
    if (wp_safe_redirect( get_site_url(null, 'artwork/gallery/painting/landscape/'), 301 ) ) {
      exit;
    }
  }
  elseif ( $currenturl_relative == '/caleb/portfolio/index.asp?glID=36&displayID=1&imgID=1&label=Tuscaloosa%20Murals' ) {
    if (wp_safe_redirect( get_site_url(null, 'artwork/gallery/painting/'), 301 ) ) {
      exit;
    }
  }
  elseif ( $currenturl_relative == '/caleb/portfolio/index.asp?glID=26&displayID=1&imgID=1&label=Sculpture' ) {
    if (wp_safe_redirect( get_site_url(null, 'artwork/gallery/sculpture/'), 301 ) ) {
      exit;
    }
  }
  elseif ( $currenturl_relative == '/caleb/artist/' || $currenturl_relative == '/caleb/artist' ) {
    if (wp_safe_redirect( get_site_url(null, 'about/'), 301 ) ) {
      exit;
    }
  }
  elseif ( $currenturl_relative == '/caleb/portfolio/' || $currenturl_relative == '/caleb/portfolio' ) {
    if (wp_safe_redirect( get_site_url(null, 'artwork/gallery/'), 301 ) ) {
      exit;
    }
  }
  elseif ( $currenturl_relative == '/caleb/contact/' || $currenturl_relative == '/caleb/contact' ) {
    if (wp_safe_redirect( get_site_url(null, 'about/contact/'), 301 ) ) {
      exit;
    }
  }
  elseif ( $currenturl_relative == '/caleb/press/' || $currenturl_relative == '/caleb/press' ) {
    if (wp_safe_redirect( get_site_url(null, 'about/press/'), 301 ) ) {
      exit;
    }
  }
  elseif ( $currenturl_relative == '/caleb/awards/' || $currenturl_relative == '/caleb/awards' ) {
    if (wp_safe_redirect( get_site_url(null, 'about/awards/'), 301 ) ) {
      exit;
    }
  }
  elseif ( $currenturl_relative == '/caleb/exhibitions/' || $currenturl_relative == '/caleb/exhibitions' ) {
    if (wp_safe_redirect( get_site_url(null, 'about/exhibitions/'), 301 ) ) {
      exit;
    }
  }
}
