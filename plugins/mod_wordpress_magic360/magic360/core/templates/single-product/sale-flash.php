<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $woocommerce, $product, $main_id;

if(!function_exists('WordPress_Magic360_remove_placeholder')){
    function WordPress_Magic360_remove_placeholder($images){
        $arr_copy = $images;
        $pattern = 'placeholder.png';
        foreach($images as $key => $image){
            if(stripos($image, $pattern) !== false){
                unset($arr_copy[$key]);
            }
        }
        return $arr_copy;
    }
}

if (!isset($GLOBALS['magictoolbox']['WordPress_Magic360_product_loaded'])) {

?>
<div class="images">

	<?php
		$flag = (isset($main_id) or has_post_thumbnail()) ? true : false; 

        if ( $flag ) {                 
                        $pid = isset($main_id) ? $main_id : $product->get_id();
                        
                        $plugin = $GLOBALS['magictoolbox']['WordPressMagic360'];
                        
                        $GLOBALS['custom_template_headers'] = true;
                        
                        $plugin->params->setProfile('product');
                        $useWpImages = $plugin->params->checkValue('use-wordpress-images','yes');
                        $plugin->params->setProfile('product');
                        
                        if (!$useWpImages) { //no need in watermark with wp images

                            /*set watermark options for all profiles START */
                            $defaultParams = $plugin->params->getParams('default');
                            $wm = array();
                            $profiles = $plugin->params->getProfiles();
                            foreach ($defaultParams as $id => $values) {
                                if (($values['group']) == 'Watermark') {
                                    $wm[$id] = $values;
                                }
                            }
                            foreach ($profiles as $profile) {
                                $plugin->params->appendParams($wm,$profile);
                            }
                            /*set watermark options for all profiles END */
                        }
                        
                            
                        $thumbs = WordPress_Magic360_get_prepared_selectors($pid, $useWpImages);
                        $thumbs = WordPress_Magic360_remove_placeholder($thumbs);
                        
                        $id = '_Main';
                        $thumbnail_id  = get_post_thumbnail_id($pid);
                        
                        $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
                        $title = get_post($thumbnail_id)->post_title;
                        if (empty($title)) $title = $post->post_title;
                        
                        $additionalDescription = preg_replace ('/<a[^>]*><img[^>]*><\/a>/is','',$post->post_excerpt);
                        $description = preg_replace ('/<a[^>]*><img[^>]*><\/a>/is','',$post->post_content);
                        $description = preg_replace ('/\[caption id=\"attachment_[0-9]+\"[^\]]*?\][^\[]*?\[\/caption\]/is','',$description);
                        
                        $link = '';
                        
                        if ($useWpImages) {
                            
                            $img = wp_get_attachment_image_src( $thumbnail_id, 'full' ); 
                            $img = $img[0];
                            
                            $thumb = wp_get_attachment_image_src( $thumbnail_id, $plugin->params->getValue('single-wordpress-image') );
                            $thumb = $thumb[0];
                            
                            $img_result = $plugin->getMainTemplate(compact('img','thumb','thumb2x','id','title','alt','description','additionalDescription','link'));
                            
                        } else {

                            $img_name = str_replace(get_site_url(),'',wp_get_attachment_url( $thumbnail_id ));
                            
                            $thumb = WordPress_Magic360_get_product_image($img_name,'thumb');
                            $thumb2x = WordPress_Magic360_get_product_image($img_name,'thumb2x');
                            
                            WordPress_Magic360_get_product_variations(); //call only for onload variation check

                            $img = WordPress_Magic360_get_product_image($img_name,'original');
                            $img_result = $plugin->getMainTemplate(compact('img','thumb','thumb2x','id','title','alt','description','additionalDescription','link'));
                        }
                        $img_result = preg_replace('/(<a.*?class=\".*?)\"/is', "$1" . ' lightbox-added"', $img_result);
                        $GLOBALS['magictoolbox']['Magic360']['main'] = $img_result;
                        $mainHTML = $GLOBALS['magictoolbox']['Magic360']['main'];
                        
                        
                        
                        $invisImg = '<figure class="woocommerce-product-gallery__image--placeholder"><a class="zoom invisImg wp-post-image" href="'.$img.'" style="display:none;"><img style="display:none;" src="'.$thumb.'"/></a></figure>';
                        
                        $scroll =  WordPress_Magic360_LoadScroll($plugin);
                        
                        $html = MagicToolboxTemplateHelperClass::render(array(
                            'main' => $mainHTML,
                            'thumbs' => (count($thumbs) >= 1) ? $thumbs : array(),
                            'magicscrollOptions' => $scroll ? $scroll->params->serialize(false, '', 'product-magicscroll-options') : '',
                            'pid' => $pid,
                        ));
                        echo $invisImg.$html;
                        $GLOBALS['magictoolbox']['WordPress_Magic360_product_loaded'] = true;
                            
                        
		}

?>

</div>

<?php } ?>