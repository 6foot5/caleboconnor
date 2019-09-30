<?php
    $id = 'WordPressMagic360';
    $settings = get_option("WordPressMagic360CoreSettings");
    $map = WordPressMagic360_getParamsMap();

    if(isset($_POST["submit"])) {
        $allSettings = array();
        /* save settings */
        foreach (WordPressMagic360_getParamsProfiles() as $profile => $name) {
            $GLOBALS['magictoolbox'][$id]->params->setProfile($profile);
            foreach($_POST as $name => $value) {
                if(preg_match('/magic360settings_'.ucwords($profile).'_(.*)/is',$name,$matches)) {
                    $GLOBALS['magictoolbox'][$id]->params->setValue($matches[1], $value);
                }
            }
            $allSettings[$profile] = $GLOBALS['magictoolbox'][$id]->params->getParams($profile);
        }

        update_option($id . "CoreSettings", $allSettings);
        $settings = $allSettings;
    }

    $corePath = preg_replace('/https?:\/\/[^\/]*/is', '', get_option("siteurl"));
    $corePath .= '/wp-content/'.preg_replace('/^.*?\/(plugins\/.*?)$/is', '$1', str_replace("\\", "/", dirname(dirname(dirname(__FILE__))) ));

    
    if (!function_exists('magictoolbox_WordPress_Magic360_get_wordpress_image_sizes')) {
        function magictoolbox_WordPress_Magic360_get_wordpress_image_sizes( $unset_disabled = true ) {
        $wais = & $GLOBALS['_wp_additional_image_sizes'];

        $sizes = array();

        foreach ( get_intermediate_image_sizes() as $_size ) {
            if ( in_array( $_size, array('thumbnail', 'medium', 'medium_large', 'large') ) ) {
                $sizes[ $_size ] = array(
                    'width'  => get_option( "{$_size}_size_w" ),
                    'height' => get_option( "{$_size}_size_h" ),
                    'crop'   => (bool) get_option( "{$_size}_crop" ),
                );
            }
            elseif ( isset( $wais[$_size] ) ) {
                $sizes[ $_size ] = array(
                    'width'  => $wais[ $_size ]['width'],
                    'height' => $wais[ $_size ]['height'],
                    'crop'   => $wais[ $_size ]['crop'],
                );
            }

            // size registered, but has 0 width and height
            if( $unset_disabled && ($sizes[ $_size ]['width'] == 0) && ($sizes[ $_size ]['height'] == 0) )
                unset( $sizes[ $_size ] );
        }

        return $sizes;
        }
    }

    
    function WordPressMagic360_get_description(&$description) {
        $result = '';
        if (gettype($description) == "array" && count($description)) {
            $result .= '<span>'.array_shift($description).'</span>';
        }
        return $result;
    }

    function WordPressMagic360_widthout_img($id) {
        $result = false;
        //$arr = array('include-headers');
        $arr = array();


        if (in_array($id, $arr)) {
            $result = true;
        }
        return $result;
    }

    function WordPressMagic360_get_options_groups ($settings, $profile = 'default', $map, $id, $corePath) {
        $html = '';
        $toolAbr = '';
        $abr = explode(" ", strtolower("Magic 360"));

        foreach ($abr as $word) $toolAbr .= $word{0};

        if (!isset($settings[$profile])) return false;
        $settings = $settings[$profile];
        $imgSizes = magictoolbox_WordPress_Magic360_get_wordpress_image_sizes();

        if (!isset($map[$profile]['General'])) {
            $map[$profile]['General'] = array('include-headers',
                                              'page-status');
        }

        $groups = array();
        $imgArray = array('zoom & expand','zoom&expand','yes','zoom','expand','swap images only','original','expanded','no','left','top left','top','top right', 'right', 'bottom right', 'bottom', 'bottom left'); //array for the images ordering

        $result = '';

        foreach($settings as $name => $s) {
            if (!isset($map[$profile][$s['group']]) || !in_array($s['id'], $map[$profile][$s['group']])) continue;
            if (preg_match('/watermark/', $name)) continue;
            if ($profile == 'product' || $profile == 'default') {
                if ($s['id'] == 'page-status' && !isset($s['value'])) {
                    $s['default'] = 'Yes';
                }
            }

            if (!isset($s['value'])) $s['value'] = $s['default'];

            if ($profile == 'product') {
                if ($s['id'] == 'page-status' && !isset($s['value'])) {
                    $s['default'] = 'Yes';
                }
            }


            if (strtolower($s['id']) == 'direction') continue;
            if (strtolower($s['id']) == 'class') continue;
            if (strtolower($s['id']) == 'enabled-effect' || strtolower($s['id']) == 'class' || strtolower($s['id']) == 'nextgen-gallery'  ) {
                $s['group'] = 'top';
            }


            if (!isset($groups[$s['group']])) {
                $groups[$s['group']] = array();
            }

            //$s['value'] = $GLOBALS['magictoolbox'][$id]->params->getValue($name);
            if (strpos($s["label"],'(')) {
                $before = substr($s["label"],0,strpos($s["label"],'('));
                $after = ' '.str_replace(')','',substr($s["label"],strpos($s["label"],'(')+1));
            } else {
                $before = $s["label"];
                $after = '';
            }
            if (strpos($after,'%')) $after = ' %';
            if (strpos($after,'in pixels')) $after = ' pixels';
            if (strpos($after,'milliseconds')) $after = ' milliseconds';

            $description2 = array();
            if (isset($s["description"]) && trim($s["description"]) != '') {
                $description = $s["description"];
                if (strtolower($s['id']) == 'include-headers') {
                    $description2 = explode('|', $description);
                    $description = '';
                }
            } else {
                $description = '';
            }

            $html  .= '<tr>';
            $html  .= '<th width="30%">';
            $html  .= '<label for="magic360settings'.'_'.ucwords($profile).'_'. $name.'">'.$before.'</label>';

           
            if(($s['type'] != 'array') && isset($s['values']) && $s['type'] != 'dropdown') $html .= '<br/> <span class="afterText">' . implode(', ',$s['values']).'</span>';

            $html .= '</th>';
            $html .= '<td width="70%">';

            switch($s["type"]) {
                case "array":
                    $rButtons = array();
                    foreach($s["values"] as $p) {
                        $rButtons[strtolower($p)] = '<label><input type="radio" value="'.$p.'"'. ($s["value"]==$p?"checked=\"checked\"":"").' name="magic360settings'.'_'.ucwords($profile).'_'.$name.'" id="magic360settings'.'_'.ucwords($profile).'_'. $name.$p.'">';
                        $pName = ucwords($p);
                        if(strtolower($p) == "yes") {
                            if (WordPressMagic360_widthout_img(strtolower($s['id']))) {
                                $rButtons[strtolower($p)] .= WordPressMagic360_get_description($description2);
                            } else {
                                $rButtons[strtolower($p)] .= '<img src="'.$corePath.'/admin_graphics/yes.gif" alt="'.$pName.'" title="'.$pName.'" />';
                            }
                            $rButtons[strtolower($p)] .= '</label>';
                        } elseif(strtolower($p) == "no") {
                            if (WordPressMagic360_widthout_img(strtolower($s['id']))) {
                                $rButtons[strtolower($p)] .= WordPressMagic360_get_description($description2);
                            } else {
                                $rButtons[strtolower($p)] .= '<img src="'.$corePath.'/admin_graphics/no.gif" alt="'.$pName.'" title="'.$pName.'" />';
                            }
                            $rButtons[strtolower($p)] .= '</label>';
                        }
                        elseif(strtolower($p) == "left")
                            $rButtons[strtolower($p)] .= '<img src="'.$corePath.'/admin_graphics/left.gif" alt="'.$pName.'" title="'.$pName.'" /></label>';
                        elseif(strtolower($p) == "right")
                            $rButtons[strtolower($p)] .= '<img src="'.$corePath.'/admin_graphics/right.gif" alt="'.$pName.'" title="'.$pName.'" /></label>';
                        elseif(strtolower($p) == "top")
                            $rButtons[strtolower($p)] .= '<img src="'.$corePath.'/admin_graphics/top.gif" alt="'.$pName.'" title="'.$pName.'" /></label>';
                        elseif(strtolower($p) == "bottom")
                            $rButtons[strtolower($p)] .= '<img src="'.$corePath.'/admin_graphics/bottom.gif" alt="'.$pName.'" title="'.$pName.'" /></label>';
                        elseif(strtolower($p) == "bottom left" || strtolower($p) == "bl")
                            $rButtons[strtolower($p)] .= '<img src="'.$corePath.'/admin_graphics/bottom-left.gif" alt="'.$pName.'" title="'.$pName.'" /></label>';
                        elseif(strtolower($p) == "bottom right" || strtolower($p) == "br")
                            $rButtons[strtolower($p)] .= '<img src="'.$corePath.'/admin_graphics/bottom-right.gif" alt="'.$pName.'" title="'.$pName.'" /></label>';
                        elseif(strtolower($p) == "top left" || strtolower($p) == "tl")
                            $rButtons[strtolower($p)] .= '<img src="'.$corePath.'/admin_graphics/top-left.gif" alt="'.$pName.'" title="'.$pName.'" /></label>';
                        elseif(strtolower($p) == "top right" || strtolower($p) == "tr")
                            $rButtons[strtolower($p)] .= '<img src="'.$corePath.'/admin_graphics/top-right.gif" alt="'.$pName.'" title="'.$pName.'" /></label>';
                        else {
                            // if (strtolower($p) == 'load,hover' || strtolower($p) == 'load,click') {
                            //     if (strtolower($p) == 'load,hover') $pl = 'Load & hover';
                            //     if (strtolower($p) == 'load,click') $pl = 'Load & click';
                            //         $rButtons[strtolower($p)] .= '<span>'.ucwords($pl).'</span></label>';
                            // } else {
                            //     $rButtons[strtolower($p)] .= '<span>'.ucwords($p).'</span></label>';
                            // } //TODO

                            // if (strtolower($p) == 'load,hover') $p = 'Load & hover';
                            // if (strtolower($p) == 'load,click') $p = 'Load & click';
                            // $rButtons[strtolower($p)] .= '<span>'.ucwords($p).'</span></label>';


                            $rButtons[strtolower($p)] .= '<span>'.ucwords(('load,hover' == $p || 'load,click' == $p) ? str_replace(',', ' & ', $p) : $p).'</span></label>';
                        }
                    }
                    foreach ($imgArray as $img){
                        if (isset($rButtons[$img])) {
                            $html .= $rButtons[$img];
                            unset($rButtons[$img]);
                        }
                    }
                    $html .= implode('',$rButtons);
                    break;
                case "num":
                    $html .= '<input  style="width:60px;" type="text" name="magic360settings'.'_'.ucwords($profile).'_'.$name.'" id="magic360settings'.'_'.ucwords($profile).'_'. $name.'" value="'.$s["value"].'" />';
                    break;
                case "text":
                    if (strtolower($s["value"]) == 'auto' ||
                        strtolower($s["value"]) == 'fit' ||
                        strpos($s["value"],'%') !== false ||
                        ctype_digit($s["value"])) {
                            $width = 'style="width:60px;"';
                    } else {
                        $width = '';
                    }
                    if (strtolower($name) == 'message' || strtolower($name) == 'selector-path' || strtolower($name) == 'watermark') {
                        $width = 'style="width:95%;"';
                    }
                    
                    $html .= '<input '.$width.' type="text" name="magic360settings'.'_'.ucwords($profile).'_'.$name.'" id="magic360settings'.'_'.ucwords($profile).'_'. $name.'" value="'.$s["value"].'" />';

                    break;
                case "dropdown":
                    $html .= '<select name="magic360settings'.'_'.ucwords($profile).'_'.$name.'" id=magic360settings'.'_'.ucwords($profile).'_'. $name.'">';
                    $html .= '<option '.($s["value"]=='full'?"selected":"").' value="full">Original image</option>';
                    foreach ($s['values'] as $subvalue) {
                    
                        $subvalue_title = $subvalue;
                        if (isset($imgSizes[$subvalue])) {
                            $subvalue_title = $subvalue.' ('.$imgSizes[$subvalue]['width'].'x'.$imgSizes[$subvalue]['height'].')';
                        } 
                        
                        $html .= '<option '.(strtolower($s["value"])==strtolower($subvalue)?"selected":"").' value="'.$subvalue.'">'.$subvalue_title.'</option>';
                    }
                    $html .= '</select>';
                   
                    break;

                default:
                    if (strtolower($name) == 'message' || strtolower($name) == 'selector-path') {
                        $width = 'style="width:95%;"';
                    } else {
                        $width = '';
                    }
                    $html .= '<input '.$width.' type="text" name="magic360settings'.'_'.ucwords($profile).'_'.$name.'" id="magic360settings'.'_'.ucwords($profile).'_'. $name.'" value="'.$s["value"].'" />';
                    break;
            }
            if ('autospin-speed' == $name && 'ms' == trim($after)) {
                $after = '<acronym title="milliseconds">'.$after.'</acronym>';
            }
            $html .= '<span class="afterText">'.$after.'</span>';
            if (!empty($description)) $html .= '<span class="help-block">'.$description.'</span>';
            $html .= '</td>';
            $html .= '</tr>';
            $groups[$s['group']][] = $html;
            $html = '';
        }
        $result .= '<div class="'.$toolAbr.'params">
                    <table class="params" cellspacing="0">';
        $i = 0;
        $keys = array_keys($groups);

        if (isset($groups['top'])) { //move 'top' group to the top
            $top = $groups['top'];
            unset($groups['top']);
            array_unshift($groups, $top);
        }

        if (isset($groups['Miscellaneous'])) {
            $misc = $groups['Miscellaneous'];
            unset($groups['Miscellaneous']);
            $groups['Miscellaneous'] = $misc; //move Miscellaneous to bottom
        }
        
        if (isset($groups['Use Wordpress images'])) {
            $uwpi = $groups['Use Wordpress images'];
            if (isset($groups['General'])) {
                $general = $groups['General'];
                unset($groups['General']);
            }
            unset($groups['Use Wordpress images']);
            $oldgroups = $groups;
            $groups = array();
            if (isset($general)) {
                $groups['General'] = $general;
            }
            $groups['Use Wordpress images'] = $uwpi; //move wp images to General
            $groups = array_merge($groups,$oldgroups);
        }

        foreach ($groups as $name => $group) {
            if ($name == '0') {
                $name = '';
                $group = preg_replace('/(^.*)(Class\sName)(.*?<span>)(All)(<\/span>.*?<span>)(Magic360)(<\/span>.*)/is','$1Apply effect to all image links$3Yes$5No$7',$group);
            }
            if ($name == $keys[count($keys)-1]) {
                $group[count($group)-1] = str_replace('<tr','<tr class="last"',$group[count($group)-1]); //set "last" class
            }
            if (is_array($group)) {
                foreach ($group as $g) {
                    if (++$i%2==0) { //set stripes
                        if (strpos($g,'class="last"')) {
                            $g = str_replace('class="last"','class="back last"',$g);
                        } else {
                            $g = str_replace('<tr','<tr class="back"',$g);
                        }
                    }
                    $result .= $g;
                }
            }
        }
        $result .= '</table> </div>';

        return $result;
    }
?>

<div class="icon32" id="icon-options-general"><br></div>

<h1>Magic 360 settings</h1>
<p>Choose the default settings for your spins.</p>
<br/>
<p style="margin-right:20px; float:right; font-size:15px; white-space: nowrap;">
        &nbsp;<a href="<?php echo admin_url().'admin.php?page=WordPressMagic360-shortcodes-page'; ?>" target="_self">Spins</a>&nbsp;|
        &nbsp;<a href="<?php echo WordPressMagic360_url('http://www.magictoolbox.com/magic360/modules/wordpress/',' configuration page resources settings link'); ?>" target="_blank">Documentation<span class="dashicons dashicons-share-alt2" style="text-decoration: none;line-height:1.3;margin-left:5px;"></span></a>&nbsp;|
        &nbsp;<a href="<?php echo WordPressMagic360_url('http://www.magictoolbox.com/magic360/examples/',' configuration page resources examples link'); ?>" target="_blank">Examples<span class="dashicons dashicons-share-alt2" style="text-decoration: none;line-height:1.3;margin-left:5px;"></span></a>&nbsp;|
        &nbsp;<a href="<?php echo WordPressMagic360_url('http://www.magictoolbox.com/contact/','configuration page resources support link'); ?>" target="_blank">Support<span class="dashicons dashicons-share-alt2" style="text-decoration: none;line-height:1.3;margin-left:5px;"></span></a>&nbsp;
        |&nbsp;<a href="<?php echo WordPressMagic360_url('http://www.magictoolbox.com/buy/magic360/','configuration page resources buy link'); ?>" target="_blank">Buy<span class="dashicons dashicons-share-alt2" style="text-decoration: none;line-height:1.3;margin-left:5px;"></span></a>
</p>
<form action="" method="post" id="magic360-config-form">
    <div id="tabs">

        <?php 
            foreach (WordPressMagic360_getParamsProfiles() as $block_id => $block_name) {
            ?>
            <div id="tab-<?php echo $block_id; ?>">
                <?php echo WordPressMagic360_get_options_groups($settings, $block_id, $map, $id, $corePath); ?>
            </div>
        <?php }  ?>
    </div>

    <p id="set-main-settings"><input type="submit" name="submit" class="button-primary" value="Save settings" />&nbsp;<a id="resetLink" style="color:red; margin-left:25px;" href="admin.php?page=WordPressMagic360-config-page&reset_settings=true">Reset to defaults</a></p>
</form>

<!-- === onlyForMod start: wordpress -->
<div style="font-size:12px;margin:5px auto;text-align:center;">Learn more about the <a href="http://www.magictoolbox.com/magic360_integration/" target="_blank">customisation options<span class="dashicons dashicons-share-alt2" style="text-decoration: none;margin-left:2px;"></span></a></div>
<!-- === onlyForMod end -->