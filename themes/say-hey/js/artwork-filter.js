
//$(".tag-fishing").toggleClass("gallery-thumb--hidden");

const flipSelector = (targetClass) => {
  //$(".all-thumbs").addClass("gallery-thumb--hidden");
  $(`.${targetClass}`).toggleClass("gallery-thumb--hidden");
};

const showSelector = () => {
  $(".all-thumbs").removeClass("gallery-thumb--hidden");
};

const hideSelector = () => {
  $(".all-thumbs").addClass("gallery-thumb--hidden");
};
