<?php

function galleryThumbsOutput($restResponse, $args = NULL, $showDetailButton = false, $cssSelectors = NULL) {

  //$relatedWorks = new WP_Query($args);

  if ($restResponse) {
    ?>
    <style>
      @media all and (min-width: 800px) {
        .fancybox-thumbs {
          top: auto;
          width: auto;
          bottom: 0;
          left: 0;
          right : 0;
          height: 95px;
          padding: 10px 10px 5px 10px;
          box-sizing: border-box;
          background: rgba(0, 0, 0, 0.7);
        }

        .fancybox-show-thumbs .fancybox-inner {
          right: 0;
          bottom: 95px;
        }
      }
    </style>

    <?php

      //echo '<hr>';
      //echo '<h2>' . get_the_title() . '</h2>';
      //echo '<ul>';
      ?>

      <?php

      //print_r($restResponse);

      foreach ( $restResponse as $work ) {

        //print_r($work);

        $relatedCaption = '';
        $captionArgs = array(
            'get_spin' => false,
            'get_stories' => true,
            'get_processes' => true
        );

        $relatedSpin = $work['spinID'];

        if ($relatedSpin) {
          $relatedCaption .= ' | ' . '<a data-fancybox data-type=\'iframe\' href=\'' . get_permalink($relatedSpin) . '\'>View 360-degree Spin</a>';
        }

        $relatedCaption = artworkCaptioner($work['ID'], $relatedCaption, $captionArgs);


        ?>

				<div class="gallery-thumb <?php echo $cssSelectors . ' ' . $work['selectors']; ?>">
					<div class="gallery-thumb__image">

            <a
              class="gallery-thumb__lightbox-trigger"
              href = "<?php echo $work['imageSrc']['large']; ?>"
							data-fancybox = "gallery"
							data-caption = "<a href='<?php echo $work['permalink']; ?>'><span class='gallery-thumb__detail-link'><?php echo $work['title']; ?> (Click to view details)</span></a> <?php echo $relatedCaption ?>">
								<img
									class="gallery-thumb__img"
									alt="<?php echo $work['title']; ?>"
									src="<?php echo $work['imageSrc']['thumbnail']; ?>">

              <div class="gallery-thumb__shadow-overlay">
              </div>

						</a>
          </div>

          <?php
          if ($showDetailButton) { ?>
  					<div class="gallery-thumb__button-explore"><a href="<?php echo $work['permalink']; ?>">Details
  						&nbsp; <i class="fal fa-info-circle"></i><!--<i class="fal fa-newspaper"></i>--></a>
  					</div>
          <?php
          } ?>

					<div class="gallery-thumb__caption">
						&bull; <a href="<?php echo $work['permalink']; ?>"><?php echo $work['title']; ?></a> &bull;
					</div>
				</div>

<?php
    }
  }
?>

  <script type="text/javascript">
       <!--
       	$.fancybox.defaults.loop = true;
       	$.fancybox.defaults.protect = true;
       	$.fancybox.defaults.buttons = ['fullScreen', 'close'];
				$.fancybox.defaults.preventCaptionOverlap = true;

        $('[data-fancybox="gallery"]').fancybox({
  				thumbs : {
			    	autoStart : false,
			    	axis      : 'x'
					}
  			})

       //-->
  </script>

<?php
}
