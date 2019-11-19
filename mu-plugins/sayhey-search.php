<?php
/*
Plugin Name: Say Hey Filter Search
Plugin URI:
Description: Filters main query when "s" term is present, joins on postmeta
Version: 1.0
Author: Rick Barley
Author URI:
License: GPLv2 or later
*/

// Join main query with postmeta....

add_filter( 'posts_join', function ( $join, $query ) {

    $searchTerm = $query->get('s');

    if ( $searchTerm ) {
      global $wpdb;
      $join .= " INNER JOIN {$wpdb->prefix}postmeta ON {$wpdb->prefix}posts.ID = {$wpdb->prefix}postmeta.post_id";
    }

    return $join;
}, 10, 2);

// Add search term comparison to postmeta.meta_value....

add_filter('posts_where', function ( $where, $query ) {
	//$label = $query->query['query_label'] ?? '';
  $searchTerm = $query->get('s');
  if ( $searchTerm ) {
		global $wpdb;
    $where .= " OR {$wpdb->prefix}postmeta.meta_value LIKE '%{$searchTerm}%' ";
	}

	return $where;
}, 10, 2);

// Group results by postID to prevent duplicates in result set....

add_filter('posts_groupby', function ( $groupby, $query ) {

  $searchTerm = $query->get('s');

  if ( $searchTerm ) {

		global $wpdb;

    // need to group by ID so posts do not show multiple times in results
    $mygroupby = "{$wpdb->posts}.ID";

    if( preg_match( "/$mygroupby/", $groupby )) {
      // grouping we need is already there
      return $groupby;
    }

    if( !strlen(trim($groupby))) {
      // groupby was empty, use ours
      return $mygroupby;
    }

    // wasn't empty, append ours
    return $groupby . ", " . $mygroupby;

	}

	return $groupby;
}, 10, 2);
