<?php

if ( ! function_exists( 'say_hey_attachment_terms' ) ) :
  /**
  * Creates custom taxonomy for attachments.
  *
  */

  function say_hey_attachment_terms() {

    // Add the ability to use custom taxonomies with images, attachments, etc

   //register_taxonomy_for_object_type( 'post_tag', 'attachment' );

   register_taxonomy_for_object_type( 'category', 'attachment' );

   $labels = array(
       'name'              => 'Attachment Folders',
       'singular_name'     => 'Folder',
       'search_items'      => 'Search Folders',
       'all_items'         => 'All Folders',
       'parent_item'       => 'Parent Folder',
       'parent_item_colon' => 'Parent Folder:',
       'edit_item'         => 'Edit Folder',
       'update_item'       => 'Update Folder',
       'add_new_item'      => 'Add New Folder',
       'new_item_name'     => 'New Folder Name',
       'menu_name'         => 'Folders',
   );

   $args = array(
       'labels' => $labels,
       'hierarchical' => true,
       'query_var' => 'true',
       'rewrite' => 'true',
       'show_admin_column' => 'true',
   );

   register_taxonomy( 'folder', 'attachment', $args );
 }

endif;

add_action( 'init' , 'say_hey_attachment_terms' );


if ( ! function_exists( 'say_hey_taxonomy_filter' ) ) :
 /**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 */

   // Add the ability to use custom taxonomies with images, attachments, etc

   function say_hey_taxonomy_filter() {

    global $typenow; // this variable stores the current custom post type

    if( $typenow == 'attachment' ) {
      // choose one or more post types to apply taxonomy filter for them if( in_array( $typenow  array('post','games') )

      $taxonomy_names = array('folder');
      foreach ($taxonomy_names as $single_taxonomy) {

        $current_taxonomy = isset( $_GET[$single_taxonomy] ) ? $_GET[$single_taxonomy] : '';
        $taxonomy_object = get_taxonomy( $single_taxonomy );
        $taxonomy_name = strtolower( $taxonomy_object->labels->name );
        $taxonomy_terms = get_terms( $single_taxonomy );
        print_r($taxonomy_terms);

        //if(count($taxonomy_terms) > 0) {

          echo "<select name='$single_taxonomy' id='$single_taxonomy' class='postform'>";
          echo "<option value=''>All $taxonomy_name</option>";

          foreach ($taxonomy_terms as $single_term) {
            echo '<option value='. $single_term->slug, $current_taxonomy == $single_term->slug ? ' selected="selected"' : '','>' . $single_term->name .' (' . $single_term->count .')</option>';
          }

          echo "</select>";

        //}
      }
    }
  }


endif;

add_action( 'restrict_manage_posts', 'say_hey_taxonomy_filter' );
