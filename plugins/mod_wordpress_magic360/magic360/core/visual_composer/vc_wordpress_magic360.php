<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

if (!defined('WPB_VC_VERSION')) { return; }
$path = dirname(dirname(dirname(__FILE__)));
require_once($path.'/constructor_magic360/wordpress_magic360_tool_db.php');
require_once($path.'/constructor_magic360/wordpress_magic360_fns.php');

function vc_wordpress_magic360_get_params() {
    $params = array();
    $shortcodes = array('' => 'empty');

    // title
    $params[] = array(
        'type' => 'textfield',
        'holder' => 'h3',
        'class' => 'vc-Magic360',
        'heading' => __('Title', 'text-domain'),
        'param_name' => 'title',
        'value' => __('', 'text-domain'),
        'description' => __('Magic 360 title', 'text-domain'),
        'admin_label' => false,
        'weight' => 0,
        'group' => 'Shortcode'
    );

    $table_data = magictoolbox_WordPress_Magic360_get_data();
    if ($table_data && count($table_data) > 0) {
        foreach ($table_data as $key => $value) {
            if ( !isset( $value->shortcode ) ) {
                $id = $value->shortcode;
            } else {
                $id = $value->id;
            }

            $shortcodes[$value->name] = $id;
        }
    }

    // shortcode select
    $params[] = array(
        'type' => 'dropdown',
        'holder' => 'div',
        'class' => 'vc-Magic360',
        'heading' => __('Shortcode', 'text-domain'),
        'param_name' => 'shortcode',
        'value' => $shortcodes,
        'description' => sprintf( __( 'Choose or <a href="%s" target="_blank">create</a> shortcode.', 'js_composer' ), admin_url().'admin.php?page=WordPressMagic360-shortcodes-page&id=new' ),
        'admin_label' => false,
        'weight' => 0,
        'group' => 'Shortcode'
    );

    $params[] = array(
        'type' => 'css_editor',
        'heading' => __( 'CSS box', 'js_composer' ),
        'param_name' => 'css',
        'group' => __( 'Design Options', 'js_composer' ),
    );

    return $params;
}

function vc_wordpress_magic360_map_init() {
    $settings = array(
        'name' => __('Magic 360', 'js_composer'),
        'base' => 'vc_wordpress_magic360_shortcode',
        'category' => __('Magictoolbox', 'js_composer'),
        'description' => __( 'Insert Magic 360', 'js_composer' ),
        'show_settings_on_create' => true,
        'weight' => 0,
        'icon' => dirname(plugin_dir_url(__FILE__))."/admin_graphics/icon.svg",
        'html_template' => dirname( __FILE__ ).'/view/vc_wordpress_magic360_view.php',

        'admin_enqueue_js' => preg_replace('/\s/', '%20', plugins_url('js/vc_wordpress_magic360_admin_enqueue.js', __FILE__)),
        'admin_enqueue_css' => preg_replace('/\s/', '%20', plugins_url('css/vc_wordpress_magic360_admin_enqueue.css', __FILE__)),
        'front_enqueue_js' => preg_replace('/\s/', '%20', plugins_url( 'js/vc_wordpress_magic360_front_enqueue.js', __FILE__)),
        'front_enqueue_css' => preg_replace('/\s/', '%20', plugins_url( 'css/vc_wordpress_magic360_front_enqueue.css', __FILE__)),
        'js_view' => 'vc_wordpress_magic360_admin',
        'params' => vc_wordpress_magic360_get_params()
    );

    vc_map( $settings );

    if ( class_exists( "WPBakeryShortCode" ) ) {
        class WPBakeryShortCode_vc_wordpress_magic360_shortcode extends WPBakeryShortCode {
            var $corePath = '';
            var $imagePath = '';

            public function __construct($settings) {
                parent::__construct( $settings );
                $this->corePath = dirname(plugin_dir_url(__FILE__));
                $this->imagePath = $this->corePath."/admin_graphics/icon.svg";
                $this->jsCssScripts();

                if ($this->isInline() || vc_is_page_editable()) {
                    magictoolbox_WordPress_Magic360_set_global_variable();
                }
            }

            public function vcLoadIframeJsCss() {
                wp_enqueue_style('vc_wordpress_magic360_shortcode_iframe');
            }

            public function contentInline($atts, $content) {
                $this->vcLoadIframeJsCss();
                $html = '';
                $css_class = '';
                if (isset($atts['css'])) {
                    $css_class = vc_shortcode_custom_css_class( $atts['css'], ' ' );
                    $css_class = ' '.$css_class;
                }

                if (isset($atts['shortcode']) &&  $atts['shortcode'] !== 'empty') {
                    $html .= '<div class="vc_wordpress_magic360_content_wrapper'.$css_class.'">';
                    if (isset($atts['title']) && $atts['title'] !== '') {
                        $html .= '<h3>';
                        $html .= $atts['title'];
                        $html .= '</h3>';
                    }
                    $html .= magictoolbox_WordPress_Magic360_shortcode(array('id' => $atts['shortcode'], 'additional_id' => '-vc-'.rand()));
                    $html .= '</div>';
                } else {
                    $html .= '<div class="vc_wordpress_magic360_wrapper'.$css_class.'">';
                    $html .= '    <div class="vc_wordpress_magic360_content">';
                    $html .= '        <div class="vc_wordpress_magic360_icon">';
                    $html .= '            <img src="'.$this->imagePath.'">';
                    $html .= '        </div>';
                    $html .= '        <div class="vc_wordpress_magic360_description">';
                    $html .= '            <h4>Magic 360</h4>';
                    $html .= '            <p>';
                    $html .= '                The block is empty and you will see nothing.<br/>';
                    $html .= '                Please choose some shortcode or '.sprintf( __( '<a href="%s" target="_blank">create shortcode</a>', 'js_composer' ), admin_url().'admin.php?page=WordPressMagic360-shortcodes-page&id=new' ).' in Magic 360 constructor.';
                    $html .= '            </p>';
                    $html .= '        </div>';
                    $html .= '    </div>';
                    $html .= '</div>';
                }

                return $html;
            }
            public function jsCssScripts() {
                wp_register_style('vc_wordpress_magic360_shortcode_iframe', plugins_url('css/vc_wordpress_magic360_front_enqueue_iframe.css', __FILE__));
            }
        }
    }
}

add_action('vc_after_init', 'vc_wordpress_magic360_map_init');
?>