<?php

function cptCardsOutput($ID = null, $permalink = '#', $title = 'Title', $excerpt = 'Read more...', $cptName = 'Behind the Artwork') {

  $thumbnail = get_the_post_thumbnail_url($ID, 'cpt-thumb');
  $linkDescr = $cptName . ': ' . $title;

  ?>

  <div class="post-card">
    <div class="post-card__image">
      <a class="no-border" title="<?php echo $linkDescr; ?>" href="<?php echo $permalink; ?>"><img width="100%" src="<?php echo $thumbnail; ?>" alt="<?php echo $linkDescr; ?>" /></a>
    </div>
    <div class="post-card__excerpt">
      <h2 class="heading heading--small">
      <?php
        printf( '<a class="no-border" title="%1$s" href="%2$s">%3$s</a>',
            $linkDescr,
            esc_url( $permalink ),
            $title
        );
      ?>
    </h2>
      <hr class="heading__line heading__line--align-left heading__line--full-width" />
      <?php echo $excerpt; ?>
    </div>
  </div>

<?php
}
