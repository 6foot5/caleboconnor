

<div class="reading">

<?php

$post_slug = $post->post_name;

if ($post_slug == 'press') {
  $repeaterField = 'press_repeater';
  $dateField = 'press_date';
  $titleField = 'press_title';
  $notesField = 'press_notes';
}
elseif ($post_slug == 'exhibitions') {
  $repeaterField = 'exhibition_repeater';
  $dateField = 'exhibition_date';
  $titleField = 'exhibition_title';
  $notesField = 'exhibition_notes';
}
elseif ($post_slug == 'awards') {
  $repeaterField = 'award_repeater';
  $dateField = 'award_date';
  $titleField = 'award_title';
  $notesField = 'award_notes';
}

$repeater = get_field($repeaterField);

// vars
$explodedYear = array();
$explodedMonth = array();
$explodedDay = array();

//print_r($repeater);

// https://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
// need to enqueue admin scripts to make repeater blocks look better

// populate order
//$i = 0;
foreach( $repeater as $i => $row ) {

  //print_r($row);
  $phpDate = strtotime($row[$dateField]);
  $explodedYear[$i] = date('Y', $phpDate);
  $explodedMonth[$i] = date('m', $phpDate);
  $explodedDay[$i] = date('d', $phpDate);


  //$i++;
}

array_multisort($explodedYear, SORT_DESC, SORT_NUMERIC,
                $explodedMonth, SORT_DESC, SORT_NUMERIC,
                $explodedDay, SORT_DESC, SORT_NUMERIC, $repeater);
/*
                $explodedDates[1], SORT_DESC, SORT_NUMERIC,
                $explodedDates[2], SORT_DESC, SORT_NUMERIC, $repeater);
*/
// loop through repeater field
// to separate group shows and one-man shows, just loop over array twice:
// once for group (with IF) and once for one-man (with IF)
if( $repeater ) {

  $i = 0;
  $lastHeaderYear = date('Y', strtotime($repeater[0][$dateField]));
  echo '<h2>' . $lastHeaderYear . '</h2>';

  foreach( $repeater as $row ) {
    //echo '<p>' . $lastHeaderYear . ' - ' . date('Y', strtotime($row['exhibition_date'])) . '</p>';
    if ($lastHeaderYear != date('Y', strtotime($row[$dateField]))) {
      $lastHeaderYear = date('Y', strtotime($row[$dateField]));
      echo '<h2>' . $lastHeaderYear . '</h2>';
    }
    echo '<p>';

    if ($post_slug == 'press') {
      if ($row['press_link']) {
      ?>
        <a href="<?php echo $row['press_link']; ?>" rel="nofollow noreferrer" target="_blank"><?php echo $row[$titleField]; ?></a>&nbsp;
        <i class="fal fa-external-link-square"></i><br />
      <?php
      }
      else {
        echo $row[$titleField] . '<br />';
      }
    }
    else {
      if ($post_slug == 'exhibitions') {
        echo $row[$titleField];
        if ($row['exhibition_type']) {
          echo ' (' . $row['exhibition_type'] . ')';
        }
        echo '<br />';
      }
      else {
        echo $row[$titleField] . '<br />';
      }
    }


    if ($post_slug == 'exhibitions') {
      if ($row['exhibition_gallery']) {
        echo $row['exhibition_gallery'];
      }
      if ($row['exhibition_location']) {
        echo ', ' . $row['exhibition_location'] . '<br />';
      }
    }

    if ($post_slug == 'press') {
      if ($row['press_outlet']) {
        echo $row['press_outlet'] . '<br />';
      }
    }

    if ($row[$notesField]) {
      echo $row[$notesField] . '<br />';
    }
    echo '</p>';
  }

}
?>
</div>
