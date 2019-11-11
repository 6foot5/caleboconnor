
//$(".tag-fishing").toggleClass("gallery-thumb--hidden");


const flipSelector = (targetClass) => {
  //$(".all-thumbs").addClass("gallery-thumb--hidden");
  const thisClass = targetClass.options[targetClass.selectedIndex].value;
  const thisLabel = targetClass.options[targetClass.selectedIndex].text;
  $(".all-thumbs").addClass("gallery-thumb--hidden");
  $(".gallery-thumb__lightbox-trigger").attr('data-fancybox', '');
  $(`.${thisClass}`).toggleClass("gallery-thumb--hidden");
  $(`.${thisClass} .gallery-thumb__lightbox-trigger`).attr('data-fancybox', 'gallery');
  $(".selected-filter").html(`: ${thisLabel}`);
  targetClass.selectedIndex = 0;
  //$(`.${thisClass} > div > a`).attr('data-fancybox', $(`.${thisClass} > div > a`).attr('data-fancybox') == 'gallery' ? '' : 'gallery');
};

const showAll = () => {
  $(".all-thumbs").removeClass("gallery-thumb--hidden");
  $(".gallery-thumb__lightbox-trigger").attr('data-fancybox', 'gallery');
  $(".selected-filter").html('');
};

const hideAll = () => {
  $(".all-thumbs").addClass("gallery-thumb--hidden");
  $(".gallery-thumb__lightbox-trigger").attr('data-fancybox', '');
  $(".selected-filter").html('');
};
