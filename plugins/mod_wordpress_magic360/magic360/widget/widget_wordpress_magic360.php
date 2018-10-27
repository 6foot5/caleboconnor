<?php
require_once(dirname(dirname(__FILE__)) . '/constructor_magic360/wordpress_magic360_tool_db.php');
require_once(dirname(dirname(__FILE__)) . '/constructor_magic360/wordpress_magic360_fns.php');

// Register and load the widget
function magictoolbox_WordPress_Magic360_load_widget() {
    register_widget( 'magictoolbox_WordPress_Magic360_widget' );
}

add_action( 'widgets_init', 'magictoolbox_WordPress_Magic360_load_widget' );

// Creating the widget 
class magictoolbox_WordPress_Magic360_widget extends WP_Widget {
    var $shortcodes_data = array();
    var $shortcode_url = '';

    function __construct() {
        parent::__construct(
            // Base ID of your widget
            'magictoolbox_WordPress_Magic360_widget',

            // Widget name will appear in UI
            __('Magic 360', 'magictoolbox_WordPress_Magic360_widget_domain'),

            // Widget description
            array( 'description' => __( 'Insert Magic 360', 'magictoolbox_WordPress_Magic360_widget_domain' ), )
        );

        $table_data = magictoolbox_WordPress_Magic360_get_data();
        if ($table_data && count($table_data) > 0) {
            $this->shortcodes_data = $table_data;
        }

        $this->shortcode_url = admin_url().'admin.php?page=WordPressMagic360-shortcodes-page&id=new';
    }

    private function getShortcodeData($id) {
        foreach ($this->shortcodes_data as $key => $value) {
            if ($value->id == $id) {
                return $value;
            }
        }
        return null;
    }

    // Creating widget front-end
    public function widget( $args, $instance ) {
        $html = '';
        $title = '';
        if (isset($instance['title'])) {
            $title = apply_filters( 'widget_title', $instance['title'] );
        }
        if (isset($instance['shortcode']) && $instance['shortcode'] != 'empty') {
            $data = $this->getShortcodeData($instance['shortcode']);
            if ($data) {
                if (!isset( $data->shortcode )) {
                    $id = $data->shortcode;
                } else {
                    $id = $data->id;
                }

                $html .= $args['before_widget'];
                if (!empty($title)) {
                    $html .= $args['before_title'];
                    $html .= $title;
                    $html .= $args['after_title'];
                }

                if (isset($instance['panels_info'])) {
                    $widget_id = $instance['panels_info']['widget_index'];
                } else {
                    $widget_id = rand();
                }

                // $html .= ('[magic360 id="'.$id.'"]');
                $html .= magictoolbox_WordPress_Magic360_shortcode(array('id' => $id, 'additional_id' => '-widget-'.$widget_id));
                $html .= $args['after_widget'];
            }
        }
        echo $html;
    }

    // Widget Backend 
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        } else {
            $title = __( '', 'magictoolbox_WordPress_Magic360_widget_domain' );
        }

        if ( isset( $instance[ 'shortcode' ] ) ) {
            $shortcode = $instance[ 'shortcode' ];
        } else {
            $shortcode = __( 'empty', 'magictoolbox_WordPress_Magic360_widget_domain' );
        }

        include 'view/widget_admin_wordpress_magic360.php';
    }
    
    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = (!empty( $new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['shortcode'] = $new_instance['shortcode'];
        return $instance;
    }
}