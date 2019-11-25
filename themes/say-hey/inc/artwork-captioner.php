<?php

function artworkCaptioner($spinID = 0, $relatedStories = array(), $relatedProcesses = array(), $relatedCaption = '', $args = array('get_spin' => false, 'get_stories' => true, 'get_processes' => true)) {

// Get 360-degree spin for specified artwork ID

  if ($args['get_spin']) {

    if ($spinID) {
      $relatedCaption .= ' | ' . '<a data-fancybox data-type=\'iframe\' href=\'' . get_permalink($spinID) . '\'>View 360-degree Spin</a>';
    }

  }

  // Get related stories for specified artwork ID

  if ($args['get_stories']) {

    if ($relatedStories) {

      $relatedCaption .= '<br />Related Stories: ';

      foreach($relatedStories as $story) {
        $relatedCaption .= '<a href=\'' . $story['permalink'] . '\'>' . $story['title'] . '</a> ';
      }
    }

  }

  // Get related processes for specified artwork ID

  if ($args['get_processes']) {

    if ($relatedProcesses) {

      $relatedCaption .= '<br />Related Processes: ';

      foreach($relatedProcesses as $process) {
        $relatedCaption .= '<a href=\'' . $process['permalink'] . '\'>' . $process['title'] . '</a> ';
      }
    }

  }

  return $relatedCaption;
}
