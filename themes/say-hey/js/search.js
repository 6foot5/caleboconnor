
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

    $.getJSON(sayHeyData.root_url + '/wp-json/sayhey/v1/works?term=' + this.searchField.val(), (results) => {
      this.resultsDiv.html(`
        <div class="row">
          <div class="one-third">
            <h2 class="search-overlay__section-title">General Information</h2>
            ${results.workInfo.length ? '<ul class="link-list min-list">' : '<p>No pages or posts! Search better next time!</p>'}
              ${results.workInfo.map(result => `
                <li><a
                  href="#">${result.title}</a>
                </li>
              `).join('')}
              ${results.workInfo.length ? '</ul>' : ''}
          </div>
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
    return false; // prevents default behavior of anchor links (will prevent fallback search page from loading when JS is enabled)
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
          <div class="container">
            <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
            <input type="text" class="search-term" placeholder="Tell me watcha want!" id="search-term" />
            <i class="fa fa-window-close js-search-overlay__close" aria-hidden="true"></i>
          </div>
          <div class="container">
              <div id="search-overlay__results">

              </div>
          </div>
        </div>
      </div>
    `);
    $("#primary-menu").append(`
      <li>
        <a href="#" class="search-trigger js-search-trigger"><i class="fa fa-search" aria-hidden="true"></i></a>
      </li>
    `);
  }

}

var siteSearch = new Search;
//export default Search;
