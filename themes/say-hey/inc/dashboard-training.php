<?php

  $tutorialVideoDir = get_theme_file_path('/assets/videos/admintutorial');
  $tutorialVideoURL = get_template_directory_uri() . '/assets/videos/admintutorial';
  $tutorialVideoFiles = scandir($tutorialVideoDir);

  // print_r($tutorialVideoFiles);

  foreach ($tutorialVideoFiles as $videoFile) {
    if ( $videoFile != '.' && $videoFile != '..' && $videoFile != '.DS_Store') {
      // echo $tutorialVideoDir . '/' . $videoFile;

?>

    <div class="dashboard-video">
      <?php echo str_replace('.mp4', '', $videoFile) . '<br /><br />' ?>
      <video width="320" controls>
        <source src="<?php echo $tutorialVideoURL . '/' . $videoFile ?>" type="video/mp4">
        Your browser does not support HTML5 video.
      </video><br /><br />
    </div>

<?php

    }
  }
