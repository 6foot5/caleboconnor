<?php

// Load values and assing defaults.
$date = get_field('press_date') ?: '[Enter a date]';
$outlet = get_field('press_outlet') ?: '[Enter a media outlet name]';
$title = get_field('press_title') ?: '[Enter a title]';
$link = get_field('press_link');

if ($link) {
  $anchorTag = '<a href="' . $link . '" title="' . $title . '" target="_blank">' . $title . '</a>';
}
else {
  $anchorTag = $title;
}
?>

<h1><?php echo $date ?></h1>


<p>
  <?php echo $outlet . '<br />' . $anchorTag;  ?>
</p>
