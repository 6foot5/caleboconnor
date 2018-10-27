<?php

if(!defined('MagicToolboxImageHelperClassLoaded')) {

    define('MagicToolboxImageHelperClassLoaded', true);

    require_once('magictoolbox.params.class.php');

    if(!defined('MT_DS')) {
        define('MT_DS', DIRECTORY_SEPARATOR);
        //define('MT_DS', '/');
    }

    class MagicToolboxImageHelperClass {

        // link to original image file
        var $src = '';

        // original file extension
        var $ext = '';

        // destination file (without sufix and extension)
        var $out = '';

        // full path for webdir
        var $path = '';

        // web address for wesite
        var $url = '';

        // full destination file
        var $file = '';

        // path to Image Magick
        var $imageMagick = false;

        // options (imagick path, resize params, etc)
        var $options = null;

        //options hash for folder name
        var $hash;

        // is there critical errors?
        var $errors = false;

        //path to folder with cached images
        var $cache = '';

        var $pid = null;

        /**
        * @constructor
        * @param string $path full path for webdir
        * @param string $cache cache folder path relative to webdir
        * @param object $options options (imagick path, resize params, etc)
        * @param string $pid product ID
        * @param string $url web address for wesite
        * @return nothing
        */
        function __construct($path, $cache = null, $options = null, $pid = null, $url = null) {

            clearstatcache();

            //prepare params
            $this->path = preg_replace('/(\/|\\\\)$/is', '', $path);
            if(!$cache) {
                $cache = MT_DS.'magictoolbox_cache';
            } else {
                $cache = preg_replace('/(\/|\\\\)$/is', '', $cache);
            }
            if(!$options) {
                $this->options = new MagicToolboxParamsClass();
                $this->options->appendArray(array(
				"square-images"=>array("id"=>"square-images","group"=>"Positioning and Geometry","order"=>"310","default"=>"disable","label"=>"Create square images","description"=>"The white/transparent padding will be added around the image or the image will be cropped.","type"=>"array","subType"=>"radio","values"=>array("extend","crop","disable"),"scope"=>"module"),
				"imagemagick"=>array("id"=>"imagemagick","advanced"=>"1","group"=>"Miscellaneous","order"=>"550","default"=>"off","label"=>"Path to ImageMagick binaries (convert tool)","description"=>"You can set 'auto' to automatically detect ImageMagick location or 'off' to disable ImageMagick and use php GD lib instead","type"=>"text","scope"=>"module"),
				"watermark"=>array("id"=>"watermark","group"=>"Watermark","order"=>"10","default"=>"","label"=>"Watermark image path","description"=>"Enter location of watermark image on your server. Leave field empty to disable watermark","type"=>"text","scope"=>"module"),
				"watermark-opacity"=>array("id"=>"watermark-opacity","group"=>"Watermark","order"=>"40","default"=>"50","label"=>"Watermark image opacity (1-100)","description"=>"0 = transparent, 100 = solid color","type"=>"num","scope"=>"module"),
				"watermark-max-width"=>array("id"=>"watermark-max-width","group"=>"Watermark","order"=>"20","default"=>"30%","label"=>"Maximum width of watermark image","description"=>"pixels = fixed size (e.g. 50) / percent = relative for image size (e.g. 50%)","type"=>"text","scope"=>"module"),
				"watermark-max-height"=>array("id"=>"watermark-max-height","group"=>"Watermark","order"=>"21","default"=>"30%","label"=>"Maximum height of watermark image","description"=>"pixels = fixed size (e.g. 50) / percent = relative for image size (e.g. 50%)","type"=>"text","scope"=>"module"),
				"watermark-position"=>array("id"=>"watermark-position","group"=>"Watermark","order"=>"50","default"=>"center","label"=>"Watermark position","description"=>"Watermark size settings will be ignored when watermark position is set to 'stretch'","type"=>"array","subType"=>"select","values"=>array("top","right","bottom","left","top-left","bottom-left","top-right","bottom-right","center","stretch"),"scope"=>"module"),
				"watermark-offset-x"=>array("id"=>"watermark-offset-x","advanced"=>"1","group"=>"Watermark","order"=>"60","default"=>"0","label"=>"Watermark horizontal offset","description"=>"Offset from left and/or right image borders. Pixels = fixed size (e.g. 20) / percent = relative for image size (e.g. 20%). Offset will disable if 'watermark position' set to 'center'","type"=>"text","scope"=>"module"),
				"watermark-offset-y"=>array("id"=>"watermark-offset-y","advanced"=>"1","group"=>"Watermark","order"=>"70","default"=>"0","label"=>"Watermark vertical offset","description"=>"Offset from top and/or bottom image borders. Pixels = fixed size (e.g. 20) / percent = relative for image size (e.g. 20%). Offset will disable if 'watermark position' set to 'center'","type"=>"text","scope"=>"module"),
				"image-quality"=>array("id"=>"image-quality","group"=>"Miscellaneous","order"=>"560","default"=>"75","label"=>"Quality of thumbnails and watermarked images (1-100)","description"=>"1 = worst quality / 100 = best quality","type"=>"num","scope"=>"module")
			));
                $this->logError('MagicToolbox ImageHelper :: Invalid options (use defauls)');
            } else {
                $this->options = $options;
            }
            $this->pid = $pid;
            if($url) {
                $this->url = preg_replace('/\/$/is', '', $url);
            }
            $this->hash = $this->getOptionsHash();
            $cache = $cache.MT_DS.$this->hash;
            $this->cache = $this->path.$cache;
            //create cache
            if(!@is_dir($this->cache)) {
                $this->cache = $this->path;
                //recursively check/create subdirs
                $cache = explode(MT_DS, $cache);
                foreach($cache as $sub_dir) {
                    if(!strlen($sub_dir)) continue;
                    $this->cache .= MT_DS.$sub_dir;
                    if(!is_dir($this->cache) && (!@mkdir($this->cache) || !@chmod($this->cache, 0777))) {
                        $this->logError('MagicToolbox ImageHelper :: Can\'t create cache folder or change permission ('.$this->cache.')', true);
                        return;
                    }
                }
            }

            // check path to Image Magick
            $this->imageMagick = $this->checkImagick($this->options->getValue('imagemagick'));

        }

        function getOptionsHash() {
            $params = array();
            $wIDs = array('watermark-opacity', 'watermark-max-width', 'watermark-max-height', 'watermark-position', 'watermark-offset-x', 'watermark-offset-y');
            $params[] = $this->options->getValue('square-images');
            $params[] = $this->options->getValue('image-quality');
            //$params[] = $this->options->getValue('use-original-file-names');
            $watermark = $this->options->getValue('watermark');
            if($watermark) {
                $params[] = $watermark;
                foreach($wIDs as $id) {
                    $params[] = $this->options->getValue($id);
                }
            }
            return md5(implode('', $params));
        }

        /**
        * create thumbnail
        *
        * @access public
        * @param string $src relative path to original image
        * @param string / array $type type of thumbnail to create ('original', 'thumb', 'selector') or sizes
        * @param string $out relative path to result image
        * @param string $pid product ID
        * @param boolean $force force to replace existing image
        */

        function create($src, $type, $pid = null, $out = null, $force = false) {
            if($this->errors) {
                return false;
            }

            if ($retina = is_string($type) && preg_match('/.*2x$/', $type)) {
                $type = preg_replace('/(.*)2x$/','$1',$type);
            }
            
            $src = str_replace('/', MT_DS, $src);
            $this->src = $this->path.$src;
            if(!file_exists($this->src) || !is_file($this->src)) {
                $this->logError('MagicToolbox ImageHelper :: Invalid image file ('.$this->src.')');
                return false;
            } else {
                if(is_string($type)) {
                    if($type == 'original') {
                        $size = array(0, 0);
                    } else {
                        $size = array(
                            $this->options->getValue($type.'-max-width')*($retina?2:1), 
                            $this->options->getValue($type.'-max-height')*($retina?2:1)
                        );
                        $type = $type.$size[0].'x'.$size[1];
                    }
                } else {
                    $size = $type;
                    $type = $type[0].'x'.$type[1];
                }
                if($pid === null) $pid = $this->pid;
                $this->ext = substr($src, strrpos($src, '.'));
                if($out === null) {
                    if($pid === null) {
                        $out = $this->cache.MT_DS.$type;
                    } else {
                        $out = $this->cache.MT_DS.$this->getPathPrefix($pid).$pid;
                        if(!is_dir($out)) {
                            $out = $this->cache;
                            $path = explode(MT_DS, $this->getPathPrefix($pid).$pid);
                            foreach($path as $part) {
                                if(!strlen($part)) continue;
                                $out .= MT_DS.$part;
                                if(!is_dir($out) && (!@mkdir($out) || !@chmod($out, 0777))) {
                                    $this->logError('MagicToolbox ImageHelper :: Can\'t create cache folder or change permission ('.$out.')', true);
                                    return false;
                                }
                            }
                        }
                        $out = $out.MT_DS.$type;
                    }
                    if(!is_dir($out) && (!@mkdir($out) || !@chmod($out, 0777))) {
                        $this->logError('MagicToolbox ImageHelper :: Can\'t create cache folder or change permission ('.$out.')', true);
                        return false;
                    }
                    //if($this->options->checkValue('use-original-file-names', 'No')) {
                    //    $this->out = $out.MT_DS.md5($src);
                    //} else {
                        //NOTE: added file path hash (if files have the same name)
                        $out = $out.MT_DS.crc32($src);
                        if(!is_dir($out) && (!@mkdir($out) || !@chmod($out, 0777))) {
                            $this->logError('MagicToolbox ImageHelper :: Can\'t create cache folder or change permission ('.$out.')', true);
                            return false;
                        }
                        $this->out = $out.MT_DS.substr($src, strrpos($src, MT_DS)+1, -strlen($this->ext));
                    //}
                    $this->file = $this->out.$this->ext;
                } else {
                    $this->out = $this->path.$out;
                    $this->file = $this->out;
                }
                if($force || !file_exists($this->file) || !is_file($this->file) || (@filemtime($this->file) - @filemtime($this->src)) < 0) {
                    $this->resize($size[0], $size[1]);
                    if(file_exists($this->file) && is_file($this->file)) {
                        @chmod($this->file, 0755);
                        return $this->getLink($this->file);
                    }
                } elseif(file_exists($this->file) && is_file($this->file)) {
                    return $this->getLink($this->file);
                }
            }
            return $this->getLink($this->src);
        }

        /*function createDir($dir) {
            //recursively check/create subdirs
            $dirs = explode(MT_DS, $dir);
            $_dir = '';
            foreach($dirs as $sub_dir) {
                if(!strlen($sub_dir)) continue;
                $_dir .= MT_DS.$sub_dir;
                if(!is_dir($_dir) && (!@mkdir($_dir) || !@chmod($_dir, 0777))) {
                    $this->logError('MagicToolbox ImageHelper :: Can\'t create cache folder or change permission ('.$_dir.')', true);
                    return false;
                }
            }
            return true;
        }*/

        function getPathPrefix($str) {
            $str = preg_replace('/[^a-z0-9_]+/i', '_', $str);
            //if(preg_match('/^_+$/', $str)) {
            //    $str = '';
            //}
            //$i = 0;
            //$pathPrefix = '';
            //for($i = 0, $l = strlen($str); $i < 2 && $i < $l ; $i++) {
            //    $pathPrefix .= $str[$i].MT_DS;
            //}
            //return $pathPrefix;
            if(strlen($str) < 2) return '';
            return $str[0].MT_DS.$str[1].MT_DS;
        }

        function getLink($link) {
            $link = $this->path ? str_replace($this->path, $this->url, $link) : ($this->url.$link);
            $link = str_replace(MT_DS, '/', $link);
            return $link;
        }

        function getTotalSize($originalW, $originalH, $maxW = 0, $maxH = 0) {
            if(!$maxW && !$maxH) {
                return array($originalW, $originalH);
            } elseif(!$maxW) {
                $maxW = ($maxH * $originalW) / $originalH;
            } elseif(!$maxH) {
                $maxH = ($maxW * $originalH) / $originalW;
            }
            $sizeDepends = $originalW/$originalH;
            $placeHolderDepends = $maxW/$maxH;
            if($sizeDepends > $placeHolderDepends) {
                $newW = $maxW;
                $newH = $originalH * ($maxW / $originalW);
            } else {
                $newW = $originalW * ($maxH / $originalH);
                $newH = $maxH;
            }
            return array(round($newW), round($newH));
        }

        function resize($w = null, $h = null, $square = null) {
            if($this->errors) return false;
            $watermark = $this->options->getValue('watermark');
            if($square == null) {
                $square = $this->options->getValue('square-images');
            }
            if($square == 'disable') {
                $square = false;
            }
            if($watermark) {
                $watermark = $this->path.'/'.preg_replace('/^\/|\/$/is', '', $watermark);
                if(!(file_exists($watermark) && is_file($watermark))) {
                    $watermark = false;
                } else {
                    $wpos = strtolower($this->options->getValue('watermark-position'));
                    $wopacity = $this->options->getValue('watermark-opacity');
                    $woffsetx = $this->options->getValue('watermark-offset-x');
                    $woffsety = $this->options->getValue('watermark-offset-y');
                    $ww = $this->options->getValue('watermark-max-width');
                    $wh = $this->options->getValue('watermark-max-height');
                }
            } else if(!($w || $h || $square)) {
                return;
            }
            $q = intval($this->options->getValue('image-quality'));
            //if($imagick = $this->checkImagick($this->options->getValue('imagemagick'))) {
            if($imagick = $this->imageMagick) {
                // use imagemagick
                if($imagick == 'native') {
                    //not support yet
                    //$imagick = new Imagick($this->img);
                    //if($h === null) {
                    //    $imagick->thumbnailImage($depends != 'height' ? $w : 0, $depends != 'width' ? $w : 0, $depends == 'both' ? true : false);
                    //} else {
                    //    $imagick->thumbnailImage($w, $h, false);
                    //}
                    //$imagick->writeImage($this->file);
                    // TODO implement watermark
                    // TODO implement square
                } else {
                    $imagick = escapeshellarg($imagick);
                    $size = $this->getImageInfo($this->src, $imagick);
                    if(empty($size[0]) || !$size[0]) {
                        $this->logError('MagicToolbox ImageHelper :: Can\'t get the picture size.  ('.$this->src.')', true);
                        return false;
                    }
                    if(!$w && !$h) {
                        $w = $size[0]; $h = $size[1];
                    } else {
                        if($square == 'crop') {
                            $s = max(($w ? $w : 0), ($h ? $h : 0));
                            if($size[0] < $size[1]) {
                                $w = $s;
                                $h = 0;
                            } else if($size[0] > $size[1]) {
                                $w = 0;
                                $h = $s;
                            } else {
                                $square = false;
                                $w = $h = $s;
                            }
                        }
                        list($w, $h) = $this->getTotalSize($size[0], $size[1], $w, $h);
                    }

                    $imagickComposite = str_replace('convert', 'composite', $imagick);
                    $escapedFilePath = escapeshellarg($this->file);
                    $coalesceOption = $this->ext == '.gif' ? ' -coalesce' : '';

                    exec($imagick.' '.escapeshellarg($this->src).$coalesceOption.' -quality '.$q.' -resize '.$w.'x'.$h.'! '.$escapedFilePath);

                    if($square == 'crop') {
                        if($w > $h) {
                            $offsetx = round(($w - $h)/2);
                            $offsety = 0;
                            $w = $h;
                            //exec($imagick.' '.$escapedFilePath.$coalesceOption.' -quality '.$q.' -crop '.$w.'x'.$h.'+'.$offsetx.'+'.$offsety.'! '.$escapedFilePath);
                            exec($imagick.' '.$escapedFilePath.$coalesceOption.' -quality '.$q.' -shave '.$offsetx.'x'.$offsety.' '.$escapedFilePath);
                        } else if($w < $h) {
                            $offsetx = 0;
                            $offsety = round(($h - $w)/2);
                            $h = $w;
                            //exec($imagick.' '.$escapedFilePath.$coalesceOption.' -quality '.$q.' -crop '.$w.'x'.$h.'+'.$offsetx.'+'.$offsety.'! '.$escapedFilePath);
                            exec($imagick.' '.$escapedFilePath.$coalesceOption.' -quality '.$q.' -shave '.$offsetx.'x'.$offsety.' '.$escapedFilePath);
                        }
                    }

                    if($watermark) {
                        $wsize = $this->getImageInfo($watermark, $imagick);
                        $mins = min($w, $h);
                        $ww = $this->getPercent($ww, $mins);
                        $wh = $this->getPercent($wh, $mins);
                        list($ww, $wh) = $this->getTotalSize($wsize[0], $wsize[1], $ww, $wh);

                        $woffsetx = $this->getPercent($woffsetx, $w);
                        $woffsety = $this->getPercent($woffsety, $h);

                        if($wpos == 'stretch') {
                            $wcmd = '-size '.$w.'x'.$h.' -depth '.$wsize[2].' NULL: -write mpr:watermarkblank +delete '
                                  .escapeshellarg($watermark).' -quality '.$q.' -resize '.($w - 2 * $woffsetx).'x'.($h - 2 * $woffsety).'! -write mpr:watermark +delete '
                                  .'mpr:watermarkblank -gravity Center mpr:watermark -composite -write mpr:watermark +delete ';
                        } else {
                            $wcmd = '-size '.($ww + 2 * $woffsetx).'x'.($wh + 2 * $woffsety).' -depth '.$wsize[2].' NULL: -write mpr:watermarkblank +delete '
                                  .escapeshellarg($watermark).'  -quality '.$q.' -resize '.$ww.'x'.$wh.'! -write mpr:watermark +delete '
                                  .'mpr:watermarkblank -gravity Center mpr:watermark -composite -write mpr:watermark +delete ';
                        }
                        $escapedTmpFilePath = escapeshellarg($this->file.'.png');
                        exec($imagick.' '.$wcmd.' mpr:watermark -quality '.$q.' '.$escapedTmpFilePath);

                        switch($wpos) {
                            case 'stretch':
                            case 'center':
                                $wcmd = 'Center';
                                break;
                            case 'tile':
                                // TODO implement
                                // we can use -tile option here
                                break;
                            case 'top-right':
                                $wcmd = 'NorthEast';
                                break;
                            case 'top-left':
                                $wcmd = 'NorthWest';
                                break;
                            case 'bottom-right':
                                $wcmd = 'SouthEast';
                                break;
                            case 'bottom-left':
                                $wcmd = 'SouthWest';
                                break;
                            case 'top':
                                $wcmd = 'North';
                                break;
                            case 'bottom':
                                $wcmd = 'South';
                                break;
                            case 'left':
                                $wcmd = 'West';
                                break;
                            case 'right':
                                $wcmd = 'East';
                                break;
                            default: break;
                        }
                        if($this->ext != '.gif') {
                            //NOTE: this bad for animated GIF
                            exec($imagickComposite.' '.$escapedTmpFilePath.' -dissolve '.$wopacity.' -gravity '.$wcmd.' '.$escapedFilePath.' '.$escapedFilePath);
                        } else {
                            //NOTE: this good for animated GIF (but without transparency)
                            exec($imagick.' '.$escapedFilePath.' -coalesce -gravity '.$wcmd.' -geometry +0+0 null: '.$escapedTmpFilePath.' -layers composite -layers optimize '.$escapedFilePath);
                        }
                        @unlink($this->file.'.png');
                    }

                    if($square == 'extend') {
                        /*
                        $s = max($w, $h);
                        if($size[3] == 'png' || $size[3] == 'gif') {
                            // null for transparent images
                            $wrapper = 'NULL:';
                        } else {
                            // white background for opaque images
                            $wrapper = 'xc:white';
                        }
                        //$wrapper = 'NULL:';
                        //NOTE: this command causes problems with animated GIF
                        $cmd = ' -size '.$s.'x'.$s.' -depth '.$size[2].' '.$wrapper.' -write mpr:resultblank +delete '
                              .'mpr:resultblank -gravity Center '.$escapedFilePath
                              .' -compose src-over -composite '.$escapedFilePath;
                        */
                        if($size[3] == 'png' || $size[3] == 'gif') {
                            $bordercolor = '-bordercolor none';
                            //$bordercolor = '-matte -bordercolor none';
                        } else {
                            $bordercolor = '-bordercolor White';
                        }
                        if($w > $h) {
                            $borderx = 0;
                            $bordery = round(($w - $h)/2);
                        } else/* if($w < $h)*/ {
                            $borderx = round(($h - $w)/2);
                            $bordery = 0;
                        }
                        $cmd = ' '.$escapedFilePath." {$bordercolor} -border {$borderx}x{$bordery} ".$escapedFilePath;

                        exec($imagick.$cmd);
                    }

                }
            } else {
                // use GD library
                list($data, $size) = $this->loadImage($this->src);
                if(!$data) {
                    $this->logError('MagicToolbox ImageHelper :: Can\'t get the image data.  ('.$this->src.')', true);
                    return false;
                }
                if(!$w && !$h) {
                    $w = $size[0]; $h = $size[1];
                    if($square == 'extend') {
                        $rw = $rh = max($w, $h);
                    } else if($square == 'crop') {
                        $rw = $rh = min($w, $h);
                    } else {
                        $rw = $w;
                        $rh = $h;
                    }
                } else {
                    list($w, $h) = $this->getTotalSize($size[0], $size[1], $w, $h);
                    if($square) {
                        $rw = $rh = max($w, $h);
                    } else {
                        $rw = $w;
                        $rh = $h;
                    }
                }

                $out = $this->createImage($rw,  $rh);

                $fCopy = function_exists('imagecopyresampled') ? 'imagecopyresampled' : 'imagecopyresized';

                if($square == 'crop') {
                    if($size[0] > $size[1]) {
                        $offsetx = round(($size[0]- $size[1])/2);
                        $offsety = 0;
                        $src_size = $size[1];
                    } else if($size[0] < $size[1]) {
                        $offsetx = 0;
                        $offsety = round(($size[1] - $size[0])/2);
                        $src_size = $size[0];
                    } else {
                        $offsetx = $offsety = 0;
                        $src_size = $size[0];
                    }
                    $w = $h = $rw;//= $rh
                    call_user_func($fCopy, $out, $data, 0, 0, $offsetx, $offsety, $rw, $rh, $src_size, $src_size);
                } else {
                    call_user_func($fCopy, $out, $data, ($rw-$w)/2, ($rh-$h)/2, 0, 0, $w, $h, $size[0], $size[1]);
                }

                // include watermark
                if($watermark) {
                    list($wdata, $wsize) = $this->loadImage($watermark);
                    $mins = min($w, $h);
                    $ww = $this->getPercent($ww, $mins);
                    $wh = $this->getPercent($wh, $mins);
                    list($ww, $wh) = $this->getTotalSize($wsize[0], $wsize[1], $ww, $wh);

                    $woffsetx = $this->getPercent($woffsetx, $w);
                    $woffsety = $this->getPercent($woffsety, $h);

                    if($wpos == 'stretch') {
                        $wdatanew = $this->createImage($w - 2 * $woffsetx, $h - 2 * $woffsety);
                        call_user_func($fCopy, $wdatanew, $wdata, 0, 0, 0, 0, $w - 2 * $woffsetx, $h - 2 * $woffsety, $wsize[0], $wsize[1]);
                    } else {
                        $wdatanew = $this->createImage($ww,  $wh);
                        call_user_func($fCopy, $wdatanew, $wdata, 0, 0, 0, 0, $ww, $wh, $wsize[0], $wsize[1]);
                    }
                    //imagealphablending($wdatanew, true);

                    //NOTE: if both (image and watermark image) are PNG
                    $imagecopymerge = ($wsize[2] == 3 && $wsize[2] == $size[2]) ? 'imagecopymerge_extra' : 'imagecopymerge';

                    switch($wpos) {
                        case 'center':
                            //call_user_func($fCopy, $out, $wdata, ($rw-$ww)/2, ($rh-$wh)/2, 0, 0, $ww, $wh, $wsize[0], $wsize[1]);//NOTE: 'watermark-opacity' does't work in this case
                            //imagecopymerge($out, $wdatanew, ($rw-$ww)/2, ($rh-$wh)/2, 0, 0, $ww, $wh, $wopacity);
                            $this->{$imagecopymerge}($out, $wdatanew, ($rw-$ww)/2, ($rh-$wh)/2, 0, 0, $ww, $wh, $wopacity);
                            break;
                        case 'tile':
                            // TODO implement
                            break;
                        case 'stretch':
                            $this->{$imagecopymerge}($out, $wdatanew, $woffsetx+($rw-$w)/2, $woffsety+($rh-$h)/2, 0, 0, $w-2*$woffsetx, $h-2*$woffsety, $wopacity);
                            break;
                        case 'top-right':
                            $this->{$imagecopymerge}($out, $wdatanew, $rw-$woffsetx-$ww-($rw-$w)/2, $woffsety+($rh-$h)/2, 0, 0, $ww, $wh, $wopacity);
                            break;
                        case 'top-left':
                            $this->{$imagecopymerge}($out, $wdatanew, $woffsetx+($rw-$w)/2, $woffsety+($rh-$h)/2, 0, 0, $ww, $wh, $wopacity);
                            break;
                        case 'bottom-right':
                            $this->{$imagecopymerge}($out, $wdatanew, $rw-$woffsetx-$ww-($rw-$w)/2, $rh-$woffsety-$wh-($rh-$h)/2, 0, 0, $ww, $wh, $wopacity);
                            break;
                        case 'bottom-left':
                            $this->{$imagecopymerge}($out, $wdatanew, $woffsetx+($rw-$w)/2, $rh-$woffsety-$wh-($rh-$h)/2, 0, 0, $ww, $wh, $wopacity);
                            break;
                        case 'top':
                            $this->{$imagecopymerge}($out, $wdatanew, ($rw-$ww)/2, $woffsety+($rh-$h)/2, 0, 0, $ww, $wh, $wopacity);
                            break;
                        case 'bottom':
                            $this->{$imagecopymerge}($out, $wdatanew, ($rw-$ww)/2, $rh-$woffsety-$wh-($rh-$h)/2, 0, 0, $ww, $wh, $wopacity);
                            break;
                        case 'left':
                            $this->{$imagecopymerge}($out, $wdatanew, $woffsetx+($rw-$w)/2, ($rh-$wh)/2, 0, 0, $ww, $wh, $wopacity);
                            break;
                        case 'right':
                            $this->{$imagecopymerge}($out, $wdatanew, $rw-$woffsetx-$ww-($rw-$w)/2, ($rh-$wh)/2, 0, 0, $ww, $wh, $wopacity);
                            break;
                        default: break;
                    }

                }

                switch($size[2]) {
                    case 1: function_exists('imagegif') && imagegif($out, $this->file);
                    case 3: imagepng($out, $this->file); break;
                    case 2: imagejpeg($out, $this->file, $q); break;
                }
                imagedestroy($data);
                imagedestroy($out);
            }
        }

        //for merge with png alpha channel
        function imagecopymerge($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct) {
            // creating a cut resource
            $cut = imagecreatetruecolor($src_w, $src_h);//$cut = $this->createImage($src_w, $src_h);
            // copying relevant section from background to the cut resource
            imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
            // copying relevant section from watermark to the cut resource
            imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);
            // insert cut resource to destination image
            imagecopymerge($dst_im, $cut, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct);
        }

        //for merge two png images with alpha channel
        function imagecopymerge_extra($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct) {
            if(!$pct) return false;
            $pct /= 100;
            // Get image width and height
            $w = imagesx($src_im);
            $h = imagesy($src_im);
            // Turn alpha blending off
            imagealphablending($src_im, false);
            // Find the most opaque pixel in the image (the one with the smallest alpha value)
            $minalpha = 127;
            for($x = 0; $x < $w; $x++) {
                for($y = 0; $y < $h; $y++) {
                    $alpha = (imagecolorat($src_im, $x, $y) >> 24) & 0xFF;
                    if($alpha < $minalpha) {
                        $minalpha = $alpha;
                    }
                }
            }
            // Loop through image pixels and modify alpha for each
            for($x = 0; $x < $w; $x++) {
                for($y = 0; $y < $h; $y++) {
                    // Get current alpha value (represents the TANSPARENCY!)
                    $colorxy = imagecolorat($src_im, $x, $y);
                    $alpha = ($colorxy >> 24) & 0xFF;
                    // Calculate new alpha
                    if($minalpha !== 127) {
                        $alpha = 127 + 127 * $pct * ($alpha - 127) / (127 - $minalpha);
                    } else {
                        $alpha += 127 * $pct;
                    }
                    // Get the color index with new alpha
                    $alphacolorxy = imagecolorallocatealpha($src_im, ($colorxy >> 16) & 0xFF, ($colorxy >> 8) & 0xFF, $colorxy & 0xFF, $alpha);
                    // Set pixel with the new color + opacity
                    if(!imagesetpixel($src_im, $x, $y, $alphacolorxy)) {
                        return false;
                    }
                }
            }
            // The image copy
            imagecopy($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h);
            return true;
        }

        function getImageInfo($src, $imagick) {

            $src = escapeshellarg($src);
            $imagickIdentify = str_replace('convert', 'identify', $imagick);
            $commands = array(
                $imagick.' '.$src.' -format \'%w::%h::%[depth]::%e\' info:',
                $imagick.' '.$src.' -format \'%w::%h::%z::%e\' info:',
                $imagickIdentify.' -format \'%w::%h::%[depth]::%e\' '.$src,
                $imagickIdentify.' -format \'%w::%h::%z::%e\' '.$src
            );

            $info = array();
            foreach($commands as $c) {
                $result = array();
                exec($c, $result);
                if(!empty($result)) {
                    $info = explode('::', $result[0]);
                    if(!empty($info[2])) break;
                }
            }
            return $info;
        }

        function getPercent($p, $s) {
            preg_match('/^([0-9]+)(%|px|Px|pX|PX)?$/is', $p, $matches);
            if(isset($matches[2]) && $matches[2] == '%') {
                $p = round($s*$matches[1]/100);
            } else {
                $p = $matches[1];
            }
            return $p;
        }

        function createImage($w, $h, $op = 127) {
            $fCreate = function_exists('imagecreatetruecolor') ? 'imagecreatetruecolor' : 'imagecreate';
            $out = call_user_func($fCreate, $w,  $h);

            if(function_exists('imageantialias')) { imageantialias($out, true); }
            if(function_exists('imagealphablending')) { imagealphablending($out, false); }
            if(function_exists('imagecolorallocatealpha')) {
                // white transparent BG
                $clr = imagecolorallocatealpha($out, 255, 255, 255, $op);
                imagefill($out, 0, 0, $clr);
            }
            if(function_exists('imagesavealpha')) { imagesavealpha($out, true); }
            if(function_exists('imagealphablending')) { imagealphablending($out, true); }

            return $out;
        }

        function loadImage($src, $size = null) {
            if($size === null) {
                $size = getimagesize($src);
            }
            /*
                1 GIF
                2 JPG
                3 PNG
                4 SWF
                5 PSD
                6 BMP
                7 TIFF (intel byte order)
                8 TIFF (motorola byte order)
                9 JPC
               10 JP2
               11 JPX
               12 JB2
               13 SWC
               14 IFF
            */
            switch($size[2]) {
                case 1:
                    // unfortunately this function does not work on windows
                    // via the precompiled php installation :(
                    // it should work on all other systems however.
                    if(function_exists('imagecreatefromgif')) {
                        $data = imagecreatefromgif($src);
                    } else {
                        $data = false;
                        $this->logError('MagicToolbox ImageHelper :: Sorry, this server doesn\'t support <b>imagecreatefromgif()</b> function', true);
                    }
                    break;
                case 2:
                    // php5 & gd2 bug. see issue #0024583 for details
                    @ini_set('gd.jpeg_ignore_warning', 1);
                    $data = imagecreatefromjpeg($src);
                    break;
                case 3: $data = imagecreatefrompng($src); break;
                // GD doesn't support other formats
                default:
                    $data = false;
                    $this->logError('MagicToolbox ImageHelper :: Unsupported image type ('.$size[2].')', true);
            }
            return array($data, $size);
        }

        function isFileExists($f, $check = false) {
            if(@file_exists($f) && (!$check || $check && @is_file($f))) {
                return true;
            } elseif(@exec('ls -l '.escapeshellarg($f).' | grep '.escapeshellarg($f))) {
                return true;
            } else {
                return false;
            }
        }

        function checkImagick($imagick) {
            if((strtolower($imagick) != 'off') && !preg_match('/\bexec\b/is', ini_get('disable_functions'))) {
                if(empty($imagick) || strtolower($imagick) == 'auto') {
                    $imagick = false;
                    // auto detect
                    if($this->isFileExists('/usr/bin/convert')) {
                        // found UNIX imagick tools in /usr/bin
                        $imagick = '/usr/bin/convert';
                    } else if($this->isFileExists('/usr/local/bin/convert')) {
                        // found UNIX imagick tools in /usr/local/bin
                        $imagick = '/usr/local/bin/convert';
                    } else {
                        $output = array();
                        @exec('compgen -ac', $output);
                        if(in_array('convert', $output) && in_array('identify', $output)) {
                            // UNIX imagick command line tools is available
                            $imagick = 'convert';
                        }
                    }
                } else {
                    if(!preg_match('/convert$/s', $imagick)) {
                        if(!preg_match('/\/$/s', $imagick)) {
                            $imagick .= '/';
                        }
                        $imagick .= 'convert';
                    }
                    if(!$this->isFileExists($imagick)) {
                        $imagick = false;
                    }
                }
            } else {
                $imagick = false;
            }

            if($imagick) {
                // we should also check does we can run imagick bin file
                @exec(escapeshellarg($imagick).' logo: /tmp/logo.png', $ret, $exitCode);
                if($exitCode > 0) {
                    // got error, disable imagick
                    $imagick = false;
                }
            }

            // check imagick version (for some reason, resize option dosn't working in imagick 5.x)
            if($imagick) {
                @exec(escapeshellarg($imagick).' --version', $ret);
                foreach($ret as $line) {
                    if(preg_match('/version:/is', $line)) {
                        $v = preg_replace('/^.*?\s((?:[0-9]+\.){2}[0-9]+)(\-\d+)?\s.*$/is', '$1', $line);
                        if(version_compare($v, '6.0.0', '<')) {
                            $imagick = false;
                        }
                        break;
                    }
                }
            }

            // temporary disabled
            /*if(!$imagick && (in_array('Imagick', get_declared_classes()) || in_array('imagick', get_declared_classes()))) {
                $imagick = 'native';
            }*/

            return $imagick;
        }

        function logError($message, $critical = false) {
            if($this->options->checkValue('imagehelper-errors', 'Yes')) {
                error_log($message);
            }
            if($critical) {
                $this->errors = true;
            }
            //for debugging purposes
            global $_GET;
            if(isset($_GET['magic']) && $_GET['magic']) {
                die($message);
            }
        }

    }

}
