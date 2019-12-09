
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
