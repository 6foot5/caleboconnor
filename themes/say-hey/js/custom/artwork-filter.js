
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
