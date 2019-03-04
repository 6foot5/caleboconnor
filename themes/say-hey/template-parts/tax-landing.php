
<div class="aligncenter">
  <h1>ARTWORK</h1>
</div>

<?php
//echo '**' . $cat_id->term_id . '**';
//print_r($cat_id);

$terms = get_terms( array(
  'taxonomy' => 'gallery',
  'hide_empty' => 1,
  'childless' => false,		// includes top-level categories that have subcategories
  'parent' => 0, 					// returns only top-level categories
  'orderby' => 'parent'
  )
);

$itemCount = 0;

foreach ( $terms as $childTerm ) {
/*

  -->  Displaying gallery thumbs as inline-block divs allows them to naturally
  flow from one line to the next, obviating the need to row/column logic.

*/
  echo '<div class="gallery-index-item">';
  //print_r($childTerm);


    $image = get_field('term_image', $childTerm);

    $label = $childTerm->name;

    if ($childTerm->parent) {
      $childOf = get_term($childTerm->parent);
      $label = $childOf->name . '<hr>' . $label;
    }

    $galleryThumbURL = $image['sizes']['gallery-category'];

    $imgTag = '<img width="100%" src="' . $galleryThumbURL . '" alt="' . $label . '" />';

    printf( '<a href="%1$s">%2$s</a>',
        esc_url( get_term_link( $childTerm->term_id ) ),
        $imgTag
    );

    ?>

    <a href="<?php echo esc_url( get_term_link( $childTerm->term_id ) ) ?>">
      <div class="gallery-index-item__shadow-overlay">
        <div class="gallery-index-item__text-content">
          <?php	echo $label; ?>
        </div>
      </div>
    </a>

    <?php

    echo '</div>';

}  //for each item in gallery


?>
