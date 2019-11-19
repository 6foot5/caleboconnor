
class ArtFilter {

  constructor() {
    this.allThumbs = $(".all-thumbs");
    //this.allThumbs.addClass("gallery-thumb--hidden");
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
    this.selectedFilters = [];
    this.selectedFilterLabels = [];
    this.currentMedium = "";
    this.currentYear = "";
    this.removedFilters = [];
    this.events();
  }

  events() {

    $(".selected-filters").on("click", ".filter-button", (e) => {

      let removedValue = this.selectedFilters.indexOf(e.target.attributes[1].nodeValue);

      if (removedValue >= 0) {
        this.selectedFilters.splice(removedValue,1);
        this.selectedFilterLabels.splice(removedValue,1);
      }

      $(`[data-filter-value='${e.target.attributes[1].nodeValue}']`).remove();

      console.log('.' + this.selectedFilters.join('.'));
      console.log(this.selectedFilterLabels);

      const combinedSelectors = this.selectedFilters.join('.');

      // Hide all thumbnails
      this.allThumbs.addClass("gallery-thumb--hidden");
      this.allFancyTriggers.attr('data-fancybox', '');

      // If any filters are still selected, reveal those thumbnails
      if(this.selectedFilters.length) {
        $(`.${combinedSelectors}`).toggleClass("gallery-thumb--hidden");
        $(`.${combinedSelectors} .gallery-thumb__lightbox-trigger`).attr('data-fancybox', 'gallery');
      }

    })

    this.selectMedium.change( () => {
      /*
      let priorMedium = this.selectedFilters.indexOf(this.currentMedium);
      this.currentMedium = this.selectMedium.val();
      console.log(priorMedium + ' - ' + this.currentMedium);
      if (priorMedium >= 0) {
        this.selectedFilters.splice(priorMedium,1);
        this.selectedFilterLabels.splice(priorMedium,1);
      }
      */
      this.filtersAdd.call(this, this.selectMedium);
    });

    this.selectYear.change( () => {
      /*
      let priorYear = this.selectedFilters.indexOf(this.currentYear);
      this.currentYear = this.selectYear.val();
      console.log(priorYear + ' - ' + this.currentYear);
      if (priorYear >= 0) {
        this.selectedFilters.splice(priorYear,1);
        this.selectedFilterLabels.splice(priorYear,1);
      }
      */
      this.filtersAdd.call(this, this.selectYear);
    });

    this.selectTag.change( () => {
      this.filtersAdd.call(this, this.selectTag);
    });

    this.selectCategory.change( () => {
      this.filtersAdd.call(this, this.selectCategory);
    });

    this.selectHas.change( () => {
      this.filtersAdd.call(this, this.selectHas);
    });

    this.showFilters.on("click", () => {
      //console.log("made it - " + this.isFilterVisible);
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
      this.selectedFilters.length = 0;
      this.selectedFilterLabels.length = 0;
    })

    this.showAll.on("click", () => {
      this.allThumbs.removeClass("gallery-thumb--hidden");
      this.allFancyTriggers.attr('data-fancybox', 'gallery');
      this.selectedFilters.length = 0;
      this.selectedFilterLabels.length = 0;
      $(".selected-filters").html('');
    })

  }

  filtersAdd(thisSelected) {
    //console.log(thisSelected.val());
    if ( this.selectedFilters.indexOf(thisSelected.val()) < 0 ) {

      // Push newly selected filter onto the value and label arrays
      this.selectedFilters.push(thisSelected.val());
      this.selectedFilterLabels.push(thisSelected.children("option:selected").text());

      const combinedSelectors = this.selectedFilters.join('.');

      this.allThumbs.addClass("gallery-thumb--hidden");
      this.allFancyTriggers.attr('data-fancybox', '');

      $(`.${combinedSelectors}`).toggleClass("gallery-thumb--hidden");
      $(`.${combinedSelectors} .gallery-thumb__lightbox-trigger`).attr('data-fancybox', 'gallery');
      $(".selected-filters").append(` <button class="filter-button" data-filter-value="${thisSelected.val()}">${thisSelected.children("option:selected").text()} (X)</button>`);
      $(".selected-filters").addClass("selected-filters--bottom-margin");
    }

    thisSelected[0].selectedIndex = 0;
  }

}

var artworkFilter = new ArtFilter;
