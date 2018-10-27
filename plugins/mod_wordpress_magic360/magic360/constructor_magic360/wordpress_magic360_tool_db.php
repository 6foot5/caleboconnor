<?php
function magictoolbox_WordPress_Magic360_create_teble() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'magic360_store';
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $sql = "CREATE TABLE $table_name (
          id int unsigned NOT NULL auto_increment,
          name varchar(300) DEFAULT NULL,
          shortcode varchar(50) DEFAULT NULL,
          startimg varchar(10) DEFAULT NULL,
          images text DEFAULT NULL,
          options text DEFAULT NULL,
          additional_options text DEFAULT NULL,
          UNIQUE KEY id (id));";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
        magictoolbox_WordPress_Magic360_create_example_chortcode();
    }
}

function magictoolbox_WordPress_Magic360_remove_teble() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'magic360_store';

    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
        $wpdb->query("DROP TABLE IF EXISTS ".$table_name);
    }
}

function magictoolbox_WordPress_Magic360_remove_element($id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'magic360_store';

    return $wpdb->delete( $table_name, array( 'id' => $id ), array( '%d' ));
}

function magictoolbox_WordPress_Magic360_add_data_to_table($name, $shortcode, $startimg, $images, $options, $additional_options) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'magic360_store';

    $r = $wpdb->insert($table_name, array('name' => $name, 'shortcode' => $shortcode, 'startimg' => $startimg, 'images' => $images, 'options' => $options, 'additional_options' => $additional_options));

    if ($r) {
        $r = $wpdb->insert_id;
    }

    return $r;
}

function magictoolbox_WordPress_Magic360_get_data($field=false, $value=false) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'magic360_store';

    if (!$field) {
        return $wpdb->get_results("SELECT * FROM ".$table_name);
    } else {
        return $wpdb->get_results("SELECT * FROM ".$table_name." WHERE ".$field." = ".$value);
    }
}

function magictoolbox_WordPress_Magic360_add_image_to_media($image_url, $name) {
    $image_url        = esc_url($image_url);
    $image_name       = $name;
    $upload_dir       = wp_upload_dir();
    $image_data       = file_get_contents($image_url);
    $unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name );
    $filename         = basename( $unique_file_name );

    if( wp_mkdir_p( $upload_dir['path'] ) ) {
        $file = $upload_dir['path'] . '/' . $filename;
    } else {
        $file = $upload_dir['basedir'] . '/' . $filename;
    }

    file_put_contents( $file, $image_data );
    $wp_filetype = wp_check_filetype( $filename, null );

    $attachment = array(
        'guid' => $upload_dir['url'] . '/' . basename( $file ),
        'post_mime_type' => $wp_filetype['type'],
        'post_title'     => sanitize_file_name( $filename ),
        'post_content'   => '',
        'post_status'    => 'inherit'
    );

    if( file_exists( ABSPATH . 'wp-admin/includes/image.php') && file_exists( ABSPATH . 'wp-admin/includes/media.php') ) {
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );

        $attach_id = wp_insert_attachment( $attachment, $file);

        if (!is_wp_error( $attach_id )) {
            $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
            wp_update_attachment_metadata( $attach_id, $attach_data );
            return $attach_id;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function magictoolbox_WordPress_Magic360_get_images_from_media_library() {
    $args = array(
        'post_type' => 'attachment',
        'post_mime_type' =>'image',
        'post_status' => 'inherit',
        'posts_per_page' => -1,
        'orderby' => 'rand'
    );
    return new WP_Query( $args );
}

function magictoolbox_WordPress_Magic360_get_image_name($url) {
    $name = explode("/", $url);
    $name = $name[count($name) - 1];
    return $name;
}

function magictoolbox_WordPress_Magic360_create_example_chortcode() {
    $ids = array();
    $images = array();
    $imageUrl = 'https://magictoolbox.sirv.com/images/magic360/';

    for ($i = 1; $i < 37; $i++) {
        $tmp = $i;
        if ($i < 10) {
            $tmp = '0'.$tmp;
        }
        $images[] = 'shoe-960-'.$tmp.'.jpg';
    }

    $ml_images = magictoolbox_WordPress_Magic360_get_images_from_media_library();

    foreach ($images as $value) {
        $tmp = false;
        foreach ($ml_images->posts as $img) {
            if (magictoolbox_WordPress_Magic360_get_image_name($img->guid) == $value) {
                $tmp = $img->ID;
                break;
            }
        }

        if (false == $tmp) {
            $tmp = magictoolbox_WordPress_Magic360_add_image_to_media($imageUrl.$value, $value);
        }

        if (false != $tmp) {
            $ids[] = $tmp;
        }
    }

    if (count($ids)) {
        magictoolbox_WordPress_Magic360_add_example_data($ids);
    }
}

function magictoolbox_WordPress_Magic360_add_example_data($ids) {
    $name = 'Example shortcode';
    $shortcode = '';
    $startimg = $ids[0];
    $images = implode(",", $ids);
    $options = 'columns:36;rows:1;multiRow:false;numberOfImages:36;useDefOpt:true;resize-image:medium;thumb-max-width:400;thumb-max-height:400;watermark:;watermark-to-thumbnail:true;watermark-max-width:30%;watermark-max-height:30%;watermark-opacity:50;watermark-position:center;watermark-offset-x:0;watermark-offset-y:0;';
    $additional_options = 'default';
    magictoolbox_WordPress_Magic360_add_data_to_table($name, $shortcode, $startimg, $images, $options, $additional_options);
}

?>
