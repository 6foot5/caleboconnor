/* global say_heyScreenReaderText */
/**
 * Theme functions file. Copied.
 *
 * Contains handlers for navigation and widget area.
 */

 // MOBILE MENU

 class MobileMenu {

   constructor() {
     this.siteHeader = $(".site-header");
     this.menuIcon = $("#hamburger");
     this.menuContent = $(".site-header__menu-content");
     this.siteNav = $(".menu-header-container");
     this.events();
   }

   events() {
     this.menuIcon.click(this.toggleTheMenu.bind(this));
   }

   toggleTheMenu() {

     if (this.menuIcon.hasClass('fa-bars')) {
       this.menuIcon.removeClass('fa-bars');
       this.menuIcon.addClass('fa-times');
     } else {
       this.menuIcon.removeClass('fa-times');
       this.menuIcon.addClass('fa-bars');
     }
   }

 }

 //export default MobileMenu;

 var mobileMenu = new MobileMenu();

(function( $ ) {
	var masthead, menuToggle, siteNavContain, siteNavigation;

	function initMainNavigation( container ) {

		// Add dropdown toggle that displays child menu items.
		/*
    var dropdownToggle = $( '<button />', { 'class': 'dropdown-toggle', 'aria-expanded': false })
			.append( $( '<span />', { 'class': 'dropdown-symbol', text: '+' }) )
			.append( $( '<span />', { 'class': 'screen-reader-text', text: say_heyScreenReaderText.expand }) );
    */

    var dropdownToggle = $( '<button />', { 'class': 'dropdown-toggle fa fa-angle-down', 'aria-expanded': false })
      .append( say_heyScreenReaderText.icon )
      .append( $( '<span />', { 'class': 'screen-reader-text', text: say_heyScreenReaderText.expand }) );

		container.find( '.menu-item-has-children > a, .page_item_has_children > a' ).after( dropdownToggle );

		// Set the active submenu dropdown toggle button initial state.

		container.find( '.current-menu-ancestor > button' )
			.addClass( 'toggled-on' )
			.attr( 'aria-expanded', 'true' )
			.find( '.screen-reader-text' )
			.text( say_heyScreenReaderText.collapse );

		// Set the active submenu initial state.
		container.find( '.current-menu-ancestor > .sub-menu' ).addClass( 'toggled-on' );

		container.find( '.dropdown-toggle' ).click( function( e ) {
			var _this = $( this ),
      screenReaderSpan = _this.find( '.screen-reader-text' );
      dropdownSymbol = _this.find( '.dropdown-symbol' );
      dropdownSymbol.text( dropdownSymbol.text() === '-' ? '+' : '-');

			e.preventDefault();
			_this.toggleClass( 'toggled-on' );
			_this.next( '.children, .sub-menu' ).toggleClass( 'toggled-on' );

			_this.attr( 'aria-expanded', _this.attr( 'aria-expanded' ) === 'false' ? 'true' : 'false' );

			screenReaderSpan.text( screenReaderSpan.text() === say_heyScreenReaderText.expand ? say_heyScreenReaderText.collapse : say_heyScreenReaderText.expand );
		});
	}

	initMainNavigation( $( '.main-navigation' ) );

	masthead       = $( '#masthead' );
	menuToggle     = masthead.find( '.menu-toggle' );
	siteNavContain = masthead.find( '.main-navigation' );
	siteNavigation = masthead.find( '.main-navigation > div > ul' );

	// Enable menuToggle.
	(function() {

		// Return early if menuToggle is missing.
		if ( ! menuToggle.length ) {
      console.log('Empty');
			return;
		}

		// Add an initial value for the attribute.
		menuToggle.attr( 'aria-expanded', 'false' );

		menuToggle.on( 'click.say_hey', function() {
			siteNavContain.toggleClass( 'toggled-on' );

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

				$( document.body ).on( 'touchstart.say_hey', function( e ) {
					if ( ! $( e.target ).closest( '.main-navigation li' ).length ) {
						$( '.main-navigation li' ).removeClass( 'focus' );
					}
				});

				siteNavigation.find( '.menu-item-has-children > a, .page_item_has_children > a' )
					.on( 'touchstart.say_hey', function( e ) {
						var el = $( this ).parent( 'li' );

						if ( ! el.hasClass( 'focus' ) ) {
							e.preventDefault();
							el.toggleClass( 'focus' );
							el.siblings( '.focus' ).removeClass( 'focus' );
						}
					});

			} else {
				siteNavigation.find( '.menu-item-has-children > a, .page_item_has_children > a' ).unbind( 'touchstart.say_hey' );
			}
		}

		if ( 'ontouchstart' in window ) {
			$( window ).on( 'resize.say_hey', toggleFocusClassTouchScreen );
			toggleFocusClassTouchScreen();
		}

		siteNavigation.find( 'a' ).on( 'focus.say_hey blur.say_hey', function() {
			$( this ).parents( '.menu-item, .page_item' ).toggleClass( 'focus' );
		});
	})();
})( jQuery );
