
class ArtFilter {

  constructor() {

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
    this.filterProperties = ['medium','year','tag','category','has'];

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

    this.artworkIDs = sayHeyArtworkFilter; // Localized artwork dump from WP
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

      // data-filter-type attribute from the button clicked
      let removedType = e.target.attributes[2].nodeValue;

      // check for index using data-filter-value attribute of the button clicked
      let removedValueIndex = this.filterSelections[`${removedType}Keys`].indexOf(e.target.attributes[1].nodeValue);

      if (removedValueIndex >= 0) {
        // remove the removed filter value from the arrays
        this.filterSelections[`${removedType}Keys`].splice(removedValueIndex,1);
        this.filterSelections[`${removedType}Labels`].splice(removedValueIndex,1);
      }

      // remove the filter button itself form the page
      $(`[data-filter-value='${e.target.attributes[1].nodeValue}']`).remove();

      // console.log(this.filterSelections);

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

  filtersRefresh() {

    let remainingWorks = this.artworkIDs.slice();
    let matchingWorks = [];

    //console.log(remainingWorks);

    this.filterProperties.forEach( (property) => {

      if (this.filterSelections[`${property}Keys`].length) {
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
        //console.log('matching works');
        //console.log(matchingWorks);

        remainingWorks = matchingWorks.slice();
        matchingWorks.length = 0;
        //console.log(remainingWorks);
      }
    });

    this.filterSelections.matchedIDs.length = 0;
    this.filterSelections.matchedIDs = remainingWorks.slice();
    this.filterSelections.thumbSelectors = '';
    this.filterSelections.triggerSelectors = '';

    let matchCount = 0;

    this.filterSelections.matchedIDs.forEach( (work) => {

      this.filterSelections.thumbSelectors += '.artwork-' + work.artworkID;
      this.filterSelections.triggerSelectors += '.trigger-' + work.artworkID;

      matchCount++;

      if (matchCount < this.filterSelections.matchedIDs.length) {
        this.filterSelections.thumbSelectors += ', ';
        this.filterSelections.triggerSelectors += ', ';
      }

    });

    // Hide all thumbnails
    this.allThumbs.addClass("gallery-thumb--hidden");
    this.allFancyTriggers.attr('data-fancybox', '');

    // If any filters are still selected, reveal those thumbnails
    if(this.filterSelections.matchedIDs.length) {
      $(`${this.filterSelections.thumbSelectors}`).toggleClass("gallery-thumb--hidden");
      $(`${this.filterSelections.triggerSelectors}`).attr('data-fancybox', 'gallery');
    }

  } // End filtersRefresh()

  filtersAdd(thisSelectedObject, thisSelectedType) {

    if ( this.filterSelections[`${thisSelectedType}Keys`].indexOf(thisSelectedObject.val()) < 0 ) {

      // Push newly selected filter onto the value and label arrays
      this.filterSelections[`${thisSelectedType}Keys`].push(thisSelectedObject.val());
      this.filterSelections[`${thisSelectedType}Labels`].push(thisSelectedObject.children("option:selected").text());

      this.filtersRefresh();

      // Add a "selected filter" button
      $(".selected-filters").append(` <button class="filter-button" data-filter-value="${thisSelectedObject.val()}" data-filter-type="${thisSelectedType}">${thisSelectedObject.children("option:selected").text()} (X)</button>`);
      $(".selected-filters").addClass("selected-filters--bottom-margin");
    }

    thisSelectedObject[0].selectedIndex = 0;

  } // end filtersAdd()

}

var artworkFilter = new ArtFilter;
