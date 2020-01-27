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

/**/
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

/*
  $('body').click(function(e) {
    var parentUL = $(e.target).parents('ul').map(function(){
      return this.className;}).get().join(", ");

    //console.log('In submenu? - ' + parentUL + ' - ' + parentUL.includes('sub-menu'));
    if( !(parentUL.includes('sub-menu')) )
    {
      //console.log('collapse!');
    }
  });
*/

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

$('.js-back').on('click', function(evt) {
  if (document.referrer.indexOf(window.location.host) !== -1) {
    evt.preventDefault();
    history.back();
  } 
});


class Search {
  // 1. Constructor is where we describe and initiate our object
  constructor () {
    this.addSearchMarkup();
    this.resultsDiv =$("#search-overlay__results");
    this.openButton = $(".js-search-trigger");
    this.closeButton = $(".js-search-overlay__close");
    this.searchOverlay = $(".search-overlay");
    this.searchField = $("#search-term");
    this.events();
    this.isOverlayOpen = false;
    this.typingTimer;
    this.previousValue;
    this.isSpinnerVisible = false;
  }

  // 2. List all the Events (connects properties of the object to its methods)

  events() {
    this.openButton.on("click", this.openOverlay.bind(this));
    this.closeButton.on("click", this.closeOverlay.bind(this));
    $(document).on("keydown", this.keyPressDispatcher.bind(this));
    this.searchField.on("keyup", this.typingLogic.bind(this));
  }

  // 3. Methods (function, action)

  keyPressDispatcher(e) {

    if (e.keyCode == 83 && !this.isOverlayOpen && !$("input,textarea").is(':focus')) {
      this.openOverlay();
    }
    if (e.keyCode == 27 && this.isOverlayOpen) {
      this.closeOverlay();
    }

  }

  typingLogic() {

    if (this.previousValue != this.searchField.val()) {
      clearTimeout(this.typingTimer);
      if (this.searchField.val()) {
        if (!this.isSpinnerVisible) {
          this.resultsDiv.html('<div class="spinner-loader"></div>');
          this.isSpinnerVisible = true;
        }
        this.typingTimer = setTimeout(this.getResults.bind(this),500);
      } else {
        this.resultsDiv.html('');
        this.isSpinnerVisible = false;
      }
    }

    this.previousValue = this.searchField.val();

  }

  getResults() {
    // Below uses an ES6 arrow function, which is basically saying take the returned JSON, pack it into userSearch, and send it to this fn
    // arrow function maintains the "this" value

    $.getJSON(sayHeyData.root_url + '/wp-json/sayhey/v1/search?term=' + this.searchField.val(), (results) => {
      this.resultsDiv.html(`
        <div class="flexible__flex-left flexible__flex-left--left-align search-gutter">
        <div class="search-results__block-header">
          <h3 class="heading heading--small">Artwork</h3>
        </div>
          <div class="search-results__block">
            ${results.artworkInfo.length ? '<ul class="search-results-list">' : '<p class="search-results__no-results"><i class="fal fa-empty-set fa-lg"></i></p>'}
              ${results.artworkInfo.map(result => `
                <li class="search-result">
                  <div class="search-result__image-container">
                    <a href="${result.permalink}"><img class="search-result__image" src="${result.thumbnail}" /></a>
                  </div>
                  <div class="search-result__text">
                    <a href="${result.permalink}">${result.title}</a>
                  </div>
                </li>
              `).join('')}
            ${results.artworkInfo.length ? '</ul>' : ''}
          </div>
        </div>
        <div class="flexible__flex-right flexible__flex-right--left-align">
          <div class="search-results__block-header">
            <h3 class="heading heading--small">Pages</h3>
          </div>
          <div class="search-results__block">
            ${results.generalInfo.length ? '<ul class="search-results-list">' : '<p class="search-results__no-results"><i class="fal fa-empty-set fa-lg"></i></p>'}
              ${results.generalInfo.map(result => `
                <li class="search-result"><a
                  href="${result.permalink}">${result.title}</a>
                </li>
              `).join('')}
            ${results.generalInfo.length ? '</ul>' : ''}
          </div>

          <div class="search-results__block-header">
            <h3 class="heading heading--small">Behind the Art</h3>
          </div>
          <div class="search-results__block">
            ${results.storyInfo.length ? '<ul class="search-results-list">' : results.processInfo.length ? '<ul class="search-results-list">' : '<p class="search-results__no-results"><i class="fal fa-empty-set fa-lg"></i></p>'}
              ${results.storyInfo.map(result => `
                <li class="search-result"><a
                  href="${result.permalink}">Story | ${result.title}</a>
                </li>
              `).join('')}
              ${results.processInfo.map(result => `
                <li class="search-result"><a
                  href="${result.permalink}">Process | ${result.title}</a>
                </li>
              `).join('')}
            ${results.storyInfo.length ? '</ul>' : results.processInfo.length ? '</ul>' : ''}
          </div>

        </div>
      `);
      this.isSpinnerVisible = false;
    });
  }

  openOverlay() {
    this.searchOverlay.addClass("search-overlay--active");
    $("body").addClass("body-no-scroll");
    this.searchField.val('');
    setTimeout(() => this.searchField.focus(), 301);
    console.log("OPEN");
    this.isOverlayOpen = true;
    // below prevents default behavior of anchor links (will prevent fallback search page from loading when JS is enabled)
    return false;
  }

  closeOverlay() {
    this.searchOverlay.removeClass("search-overlay--active");
    $("body").removeClass("body-no-scroll");
    console.log("CLOSE");
    this.isOverlayOpen = false;
  }

  addSearchMarkup() {
    $("body").append(`
      <div class="search-overlay">
        <div class="search-overlay__top">
          <div class="content-area ">
            <i class="fal fa-search fa-lg search-overlay__icon" aria-hidden="true"></i>
            <input type="text" class="search-term" placeholder="Enter search criteria" id="search-term" />
            <i class="fal fa-window-close fa-2x js-search-overlay__close" aria-hidden="true"></i>
            <hr width="100%" />
          </div>
          <div class="content-area">
              <div class="flexible" id="search-overlay__results">

              </div>
          </div>
        </div>
      </div>
    `);
  }

}

var siteSearch = new Search;


class ArtFilter {

  constructor() {

    // Let's be aware of the elements we'll be dealing with
    this.allThumbs = $(".all-thumbs");
    this.allFancyTriggers = $(".gallery-thumb__lightbox-trigger");
    this.filterButtons = $(".filter-buttons");
    this.filterControls = $(".artwork-filter");
    this.showHideControls = $(".show-hide-filter");
    this.selectMedium = $("#filter-medium");
    this.selectYear = $("#filter-year");
    this.selectTag = $("#filter-tag");
    this.selectCategory = $("#filter-category");
    this.selectHas = $("#filter-has");
    this.showFilters =  $("#show-filters");
    this.showAll =  $("#show-all");
    this.showNone =  $("#show-none");

    this.isFilterVisible = true;

    // These are the different "types" of filters that can be applied
    this.filterProperties = ['medium','year','tag','category','has'];

    // This object tracks the state of selections and matches
    this.filterSelections = {
      mediumKeys: [],
      mediumLabels: [],
      yearKeys: [],
      yearLabels: [],
      tagKeys: [],
      tagLabels: [],
      categoryKeys: [],
      categoryLabels: [],
      hasKeys: [],
      hasLabels: [],
      matchedIDs: [],
      thumbSelectors: [],
      triggerSelectors: []
    };

    // Localized artwork dump from WP (array of artworkID+selectors)
    this.artworkIDs = sayHeyArtworkFilter;
    this.events();

  } // end constructor

  events() {
    /*
    -------------------------------------------------------------------------
      Method to deal with "remove filter" actions
      That is, when a "selected filter" button is toggled off
    -------------------------------------------------------------------------
    */
    $(".selected-filters").on("click", ".filter-button", (e) => {

      // get data-filter-type attribute from the filter button clicked
      let removedType = e.target.attributes[2].nodeValue;

      // check for index using data-filter-value attribute of the button clicked
      // i.e. locate the position of that filter in the "state-tracking" object
      let removedValueIndex = this.filterSelections[`${removedType}Keys`].indexOf(e.target.attributes[1].nodeValue);

      if (removedValueIndex >= 0) {
        // remove the removed filter value from its position in the "state-tracking" object
        this.filterSelections[`${removedType}Keys`].splice(removedValueIndex,1);
        this.filterSelections[`${removedType}Labels`].splice(removedValueIndex,1);
      }

      // remove the filter button itself from the page
      $(`[data-filter-value='${e.target.attributes[1].nodeValue}']`).remove();

      // console.log(this.filterSelections);

      // re-scan the "state" object and refresh display with matched items
      this.filtersRefresh();

    });

    this.selectMedium.change( () => {
      this.filtersAdd.call(this, this.selectMedium, 'medium');
    });

    this.selectYear.change( () => {
      this.filtersAdd.call(this, this.selectYear, 'year');
    });

    this.selectTag.change( () => {
      this.filtersAdd.call(this, this.selectTag, 'tag');
    });

    this.selectCategory.change( () => {
      this.filtersAdd.call(this, this.selectCategory, 'category');
    });

    this.selectHas.change( () => {
      this.filtersAdd.call(this, this.selectHas, 'has');
    });

    // Simple method to show/hide the filter selector controls
    this.showFilters.on("click", () => {
      if ( this.isFilterVisible ) {
        this.filterControls.slideUp(300, () => {
          this.isFilterVisible = false;
          this.showHideControls.html('Show Options ');
          //this.filterButtons.addClass("filter-buttons--bottom-margin");
        });
      } else {
        this.filterControls.slideDown(300, () => {
          this.isFilterVisible = true;
          this.showHideControls.html('Hide Options ');
          //this.filterButtons.removeClass("filter-buttons--bottom-margin");
        });
      }
    })

    // Hide all artwork (show nothing, remove all filters)
    this.showNone.on("click", () => {
      this.allThumbs.addClass("gallery-thumb--hidden");
      this.allFancyTriggers.attr('data-fancybox', '');
      this.filterProperties.forEach( (prop) => {
        this.filterSelections[`${prop}Keys`].length = 0;
        this.filterSelections[`${prop}Labels`].length = 0;
      });
      this.filterSelections.matchedIDs.length = 0;
      this.filterSelections.thumbSelectors = '';
      this.filterSelections.triggerSelectors = '';
    })

    // Show all artwork (remove all filters)
    this.showAll.on("click", () => {
      this.allThumbs.removeClass("gallery-thumb--hidden");
      this.allFancyTriggers.attr('data-fancybox', 'gallery');
      this.filterProperties.forEach( (prop) => {
        this.filterSelections[`${prop}Keys`].length = 0;
        this.filterSelections[`${prop}Labels`].length = 0;
      });
      this.filterSelections.matchedIDs.length = 0;
      this.filterSelections.thumbSelectors = '';
      this.filterSelections.triggerSelectors = '';
      $(".selected-filters").html('');
    })
  }

  // Method to scan state object and refresh display with matched items
  filtersRefresh() {

    // Slice it to make a copy, not a pointer!
    let remainingWorks = this.artworkIDs.slice();
    let matchingWorks = [];

    //console.log(remainingWorks);

    // for each possible property, look for user-selected filters
    this.filterProperties.forEach( (property) => {

      // if the property has filters selected...
      if (this.filterSelections[`${property}Keys`].length) {
        // ...then filter the set of artwork (remainingWorks)
        matchingWorks = remainingWorks.filter( (item) => {
          let filtermatches = 0;
          this.filterSelections[`${property}Keys`].forEach( (currProp) => {
            if (item.selectors.indexOf(currProp) >= 0) {
              filtermatches++;
              //console.log('match found');
            }
          });
          return filtermatches;
        });

        remainingWorks = matchingWorks.slice();
        matchingWorks.length = 0;
      }
    });

    // refresh all matched-item values in the state object
    this.filterSelections.matchedIDs.length = 0;
    this.filterSelections.matchedIDs = remainingWorks.slice();
    this.filterSelections.thumbSelectors = '';
    this.filterSelections.triggerSelectors = '';

    let matchCount = 0;

    // Now, for each matched artwork...
    this.filterSelections.matchedIDs.forEach( (work) => {

      // ...build a list of selectors for targeting
      this.filterSelections.thumbSelectors += '.artwork-' + work.artworkID;
      this.filterSelections.triggerSelectors += '.trigger-' + work.artworkID;

      matchCount++;

      // We don't want a comma at the end of the selectors list
      if (matchCount < this.filterSelections.matchedIDs.length) {
        this.filterSelections.thumbSelectors += ', ';
        this.filterSelections.triggerSelectors += ', ';
      }

    });

    // Hide all thumbnails so we have a clean slate
    this.allThumbs.addClass("gallery-thumb--hidden");
    this.allFancyTriggers.attr('data-fancybox', '');

    // If any filters are still selected, reveal those thumbnails
    if(this.filterSelections.matchedIDs.length) {
      $(`${this.filterSelections.thumbSelectors}`).toggleClass("gallery-thumb--hidden");
      $(`${this.filterSelections.triggerSelectors}`).attr('data-fancybox', 'gallery');
    }

  } // End filtersRefresh()

  // Method to add newly selected filters to state object
  filtersAdd(thisSelectedObject, thisSelectedType) {

    // If it's not already in the state object, let's add it!
    if ( this.filterSelections[`${thisSelectedType}Keys`].indexOf(thisSelectedObject.val()) < 0 ) {

      // Push newly selected filter onto the value and label arrays
      this.filterSelections[`${thisSelectedType}Keys`].push(thisSelectedObject.val());
      this.filterSelections[`${thisSelectedType}Labels`].push(thisSelectedObject.children("option:selected").text());

      this.filtersRefresh();

      // Add a "selected filter" button for the newly selected filter
      $(".selected-filters").append(` <button class="filter-button" data-filter-value="${thisSelectedObject.val()}" data-filter-type="${thisSelectedType}">${thisSelectedObject.children("option:selected").text()} (X)</button>`);
      $(".selected-filters").addClass("selected-filters--bottom-margin");
    }

    thisSelectedObject[0].selectedIndex = 0;

  } // end filtersAdd()

}

if ( typeof sayHeyArtworkFilter !== 'undefined' ) {
  var artworkFilter = new ArtFilter;
}
