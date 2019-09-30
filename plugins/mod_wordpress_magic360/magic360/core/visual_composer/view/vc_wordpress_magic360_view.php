<?php
    $path = dirname(dirname(dirname(dirname(__FILE__))));
    require_once($path.'/constructor_magic360/wordpress_magic360_fns.php');

    $attributes = (shortcode_atts(array('title' => '', 'shortcode' => 'empty'), $atts));

    $html = '';
    if ($attributes['shortcode'] !== 'empty') {
        $css_class = '';
        if (isset($atts['css'])) {
            $css_class = vc_shortcode_custom_css_class( $atts['css'], ' ' );
            $css_class = ' '.$css_class;
        }
        if ($attributes['title'] !== '') {
            $html .= '<h3>';
            $html .= $attributes['title'];
            $html .= '</h3>';
        }
        $html .= '<div style="display: inline-block;" class="vc_wordpress_magic360_content_wrapper'.$css_class.'">';

        $html .= magictoolbox_WordPress_Magic360_shortcode(array('id' => $attributes['shortcode'], 'additional_id' => '-vc-'.rand()));

        $html .= '</div>';
    }

    echo $html;
?>