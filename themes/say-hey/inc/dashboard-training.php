<?php

// $tutorialVideos is a stupid hard-coded array of tutorial video titles and
// Youtube IDs. The loop renders them in the dashboard widget.
// To add a new video, just copy what was done for the others!

$tutorialVideos = array(
  '00 General | Tour of Admin Area,HLZJlydE2FM',
  '02 General | Media Library | Adding Images,ae-jLDyaHFg',
  '04 General | Set Crop Region for Images,oWudO8qgvUs',
  '06 General | Change Homepage Images,vxTfU4GQeg0',
  '08 General | Page Banners | Fixed and Random,_lhs_Lmv2qo',
  '10 Artwork | Add New Artwork,zq3Ze-3G8No',
  '12 Artwork | Featured Image and Sort Order,gRmHQgBzEcU',
  '14 Artwork | Editing Artwork Information,kuOWAZm1q8g',
  '16 Artwork | Show or Hide Artwork,7PJtNwt4qao',
  '18 Artwork | Change Gallery Category Image,Vf9NH9WYdL8',
  '20 Artwork | Managing Gallery Categories,KnwodSaRuLY',
  '22 Artwork | Using Tags to Classify Artwork,R0RWzxzbCj0',
  '30 About | Edit Artist Statement and Contact Info,IBtR1G-442g',
  '32 About | Manage Press-Awards-Exhibitions,z7_WRqZOQ2w',
  '40 Behind the Work | Create New Story,1z9CI_40Gvo'
);

    foreach ($tutorialVideos as $video) {

      $pieces = explode(',', $video);
      ?>

      <div class="dashboard-video">
        <?php echo $pieces[0] . '<br /><br />'; ?>
        <iframe width="320" height="180"
          src="https://www.youtube.com/embed/<?php echo $pieces[1]; ?>"
          frameborder="0"
          allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
          allowfullscreen></iframe>
      </div>

      <?php
    }
