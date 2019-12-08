<?php

function galleryThumbsOutput($restResponse, $captionArgs = array('get_spin' => false, 'get_stories' => true, 'get_processes' => true), $showDetailButton = false, $cssSelectors = NULL) {

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

      foreach ( $restResponse as $work ) {

        $relatedCaption = '';

        $relatedSpin = $work['spinID'];
        $relatedStories = $work['stories'];
        $relatedProcesses = $work['processes'];

        if ($captionArgs['get_spin'] || $captionArgs['get_stories'] || $captionArgs['get_processes']) {
          $relatedCaption = artworkCaptioner($relatedSpin, $relatedStories, $relatedProcesses, $relatedCaption, $captionArgs);
        }

        ?>

				<div class="gallery-thumb <?php echo $cssSelectors . ' ' . $work['selectors']; ?>">
					<div class="gallery-thumb__image">

            <a
              class="gallery-thumb__lightbox-trigger"
              href = "<?php echo $work['imageSrc']['large']; ?>"
              title="View Larger Image  - <?php echo $work['title']; ?>"
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
  					<div class="gallery-thumb__button-explore"><a href="<?php echo $work['permalink']; ?>"  title="View Details - <?php echo $work['title']; ?>">Details
  						&nbsp; <i class="fal fa-info-circle"></i><!--<i class="fal fa-newspaper"></i>--></a>
  					</div>
          <?php
          } ?>

					<div class="gallery-thumb__caption">
						&bull; <a href="<?php echo $work['permalink']; ?>" title="View Details - <?php echo $work['title']; ?>"><?php echo $work['title']; ?></a> &bull;
            <?php
            if ($work['stories']) {
            ?>
              &nbsp;<a href="<?php echo $work['stories'][0]['permalink']; ?>" title="Story Behind the Art - <?php echo $work['title']; ?>"><i class="fal fa-newspaper"></i></a>
            <?php
            }
            if ($work['processes']) {
            ?>
              &nbsp;<a href="<?php echo $work['processes'][0]['permalink']; ?>" title="Process Explained - <?php echo $work['title']; ?>"><i class="fal fa-palette"></i></a>
            <?php
            }
            ?>


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
