<?php
/*
--------------------------------------------------------------------------------
  Transient Helper Functions
--------------------------------------------------------------------------------
*/

function sayhey_transient_lifespan() {
  if( false ) { // for debugging, make this "true" for transients to immediately expire
    return 1;
  }
  else {
    return DAY_IN_SECONDS;
  }
}

function sayhey_update_transient_keys( $new_transient_key ) {

  // Get our list of transient keys from the DB (managed by this fn)
  $transient_keys = get_option( 'sayhey_transient_keys' );
  // print_r($transient_keys); echo '<-- CURR Transient keys<br>';

  if ( !$transient_keys ) {
    $transient_keys[]= $new_transient_key; // (push new key)
    // print_r($transient_keys); echo '<-- NEW (no previous keys)<br>';

    // Save it to the DB.
    update_option( 'sayhey_transient_keys', $transient_keys );
  }
  else {
    // Does the new key already exist?
    $newKeyFound = in_array($new_transient_key, $transient_keys);
    // echo $newKeyFound; echo '<-- NEW KEY?<br>';

    if ( !$newKeyFound ) {
      // Append our new one.
      $transient_keys[]= $new_transient_key; // (push new key)
      // print_r($transient_keys); echo '<-- NEW (not already there)<br>';

      // Save it to the DB.
      update_option( 'sayhey_transient_keys', $transient_keys );
    }
    else {
      // echo 'KEY IS ALREADY THERE!';
    }
  }
}

function sayhey_transient_purge() {

  // Get our list of transient keys from the DB (managed by sayhey_update_transient_keys() above)
  $transient_keys = get_option( 'sayhey_transient_keys' );
  // print_r($transient_keys); echo '<-- CURR Transient keys<br>';

  if ( $transient_keys ) {
    // For each key, delete that transient.
    foreach( $transient_keys as $t ) {
      delete_transient( $t );
      // echo $t . '<-- Deleted<br>';
    }

    // Reset our list of transient keys with an empty array
    update_option( 'sayhey_transient_keys', array() );
  }
}

add_action( 'save_post_artwork', 'sayhey_transient_purge' );

function sayhey_trashed_action( $post_id ) {
   if ( 'artwork' != get_post_type( $post_id ) ) {
     return;
   }
   else {
     sayhey_transient_purge();
   }
}

add_action( 'trashed_post', 'sayhey_trashed_action' );
add_action( 'untrash_post', 'sayhey_trashed_action' );
add_action( 'delete_post', 'sayhey_trashed_action' );
