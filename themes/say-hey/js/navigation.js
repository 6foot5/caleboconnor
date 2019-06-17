/* global sayheyScreenReaderText */
/**
 * Theme functions file.
 *
 * Contains handlers for navigation and widget area.
 */

(function( $ ) {
	var masthead, menuToggle, siteNavContain, siteNavigation;

	function initMainNavigation( container ) {

		// Add dropdown toggle that displays child menu items.
		var dropdownToggle = $( '<button />', { 'class': 'dropdown-toggle', 'aria-expanded': false })
      .append( $( '<span />', { 'class': 'fas fa-chevron-down dds', text: '' }) )
			.append( $( '<span />', { 'class': 'screen-reader-text', text: sayheyScreenReaderText.expand }) );

      container.find( '.menu-item-has-children > a, .page_item_has_children > a' ).after( dropdownToggle );
      //container.find( '.menu-item-has-children > a > i, .page_item_has_children > a > i' ).after( dropdownToggle );

		// Set the active submenu dropdown toggle button initial state.
    /*

    ***
    Commented out May 4, 2019; Do not want active submenu expanded by default
    (OK in mobile, wrong in desktop)
    If I can make this mobile-only, then will re-instate (e.g. via JS media query)
    ***

		container.find( '.current-menu-ancestor > button' )
			.addClass( 'toggled-on' )
			.attr( 'aria-expanded', 'true' )
			.find( '.screen-reader-text' )
			.text( sayheyScreenReaderText.collapse );

    */

		// Set the active submenu initial state.
		/*
    Commented out May 4, 2019; Do not want active submenu expanded by default when page loads

    container.find( '.current-menu-ancestor > .sub-menu' ).addClass( 'toggled-on' );
    */

		container.find( '.dropdown-toggle' ).click( function( e ) {
			var _this = $( this ),
				screenReaderSpan = _this.find( '.screen-reader-text' ),
        dropdownSymbolFa = _this.find( '.dds' );

        if (dropdownSymbolFa.hasClass('fa-chevron-down')) {
          dropdownSymbolFa.removeClass('fa-chevron-down');
          dropdownSymbolFa.addClass('fa-chevron-up');
        } else {
          dropdownSymbolFa.removeClass('fa-chevron-up');
          dropdownSymbolFa.addClass('fa-chevron-down');
        }

/* This part is working...to toggle the chevron

      dropdownSymbolFa.class( dropdownSymbolFa.hasClass('fa-chevron-down') ? ( dropdownSymbolFa.removeClass('fa-chevron-down'), dropdownSymbolFa.addClass('fa-chevron-up') ) : ( dropdownSymbolFa.removeClass('fa-chevron-up'), dropdownSymbolFa.addClass('fa-chevron-down') ) );
 */

			e.preventDefault();
      console.log(_this);
			_this.toggleClass( 'toggled-on' );
			_this.next( '.children, .sub-menu' ).toggleClass( 'toggled-on' );

			_this.attr( 'aria-expanded', _this.attr( 'aria-expanded' ) === 'false' ? 'true' : 'false' );

			screenReaderSpan.text( screenReaderSpan.text() === sayheyScreenReaderText.expand ? sayheyScreenReaderText.collapse : sayheyScreenReaderText.expand );
		});
	}

	initMainNavigation( $( '.main-navigation' ) );

	masthead       = $( '#masthead' );
	menuToggle     = masthead.find( '.menu-toggle' );
  menuBurger     = masthead.find( '#hamburger');
	siteNavContain = masthead.find( '.main-navigation' );
	siteNavigation = masthead.find( '.main-navigation > div > ul' );

	// Enable menuToggle.
	(function() {

		// Return early if menuToggle is missing.
		if ( ! menuToggle.length ) {
			return;
		}

		// Add an initial value for the attribute.
		menuToggle.attr( 'aria-expanded', 'false' );

		menuToggle.on( 'click.sayhey', function() {

			siteNavContain.toggleClass( 'toggled-on' );

      if (menuBurger.hasClass('fa-bars')) {
        menuBurger.removeClass('fa-bars');
        menuBurger.addClass('fa-times');
      } else {
        menuBurger.removeClass('fa-times');
        menuBurger.addClass('fa-bars');
      }

			$( this ).attr( 'aria-expanded', siteNavContain.hasClass( 'toggled-on' ) );

		});
	})();

	// Fix sub-menus for touch devices and better focus for hidden submenu items for accessibility.
	(function() {
		if ( ! siteNavigation.length || ! siteNavigation.children().length ) {
			return;
		}

		// Toggle `focus` class to allow submenu access on tablets.
		function toggleFocusClassTouchScreen() {
			if ( 'none' === $( '.menu-toggle' ).css( 'display' ) ) {

				$( document.body ).on( 'touchstart.sayhey', function( e ) {
					if ( ! $( e.target ).closest( '.main-navigation li' ).length ) {
						$( '.main-navigation li' ).removeClass( 'focus' );
					}
				});

				siteNavigation.find( '.menu-item-has-children > a, .page_item_has_children > a' )
					.on( 'touchstart.sayhey', function( e ) {
						var el = $( this ).parent( 'li' );

						if ( ! el.hasClass( 'focus' ) ) {
							e.preventDefault();
							el.toggleClass( 'focus' );
							el.siblings( '.focus' ).removeClass( 'focus' );
						}
					});

			} else {
				siteNavigation.find( '.menu-item-has-children > a, .page_item_has_children > a' ).unbind( 'touchstart.sayhey' );
			}
		}

		if ( 'ontouchstart' in window ) {
			$( window ).on( 'resize.sayhey', toggleFocusClassTouchScreen );
			toggleFocusClassTouchScreen();
		}

		siteNavigation.find( 'a' ).on( 'focus.sayhey blur.sayhey', function() {
			$( this ).parents( '.menu-item, .page_item' ).toggleClass( 'focus' );
		});
	})();
})( jQuery );
