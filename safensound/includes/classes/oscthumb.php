<?php
/*
  $Id: oscthumb.php, v1.1 2009/05/14 01:01:00 osCommaniak Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

class oscthumb {
  var $cache_dir, $width, $height, $thumbnail_size, $process, $calculate, $enabled, $image_type;

  function oscthumb() {
    $this->cache_dir = DIR_FS_CATALOG.'phpThumb/cache';  // For now we can change the cache folder only from here.
    $this->enabled = CFG_MASTER_SWITCH=="On"? true : false;
  }

  function check_hash() {
    // Check hash and delete cache if changed.
    $sql = "select configuration_key as cfgKey, configuration_value as cfgValue from configuration where configuration_group_id='333' or configuration_group_id='4'";
    $result = tep_db_query($sql);
    while ($row = tep_db_fetch_array($result)) {
      if ($row['cfgKey'] != "LAST_HASH") $config_values.=$row['cfgKey'].'='.$row['cfgValue'];  // To be fed to hashing function.
    }

    // Compute a hash of all the thumbnail config variables, so that if they change new cache files are created.
    $append_hash=md5($config_values);

    // Have the config vars changed.
    if ($append_hash != LAST_HASH) {
      $sql = "update configuration set configuration_value ='".$append_hash."' where configuration_key='LAST_HASH'";
      $result = tep_db_query($sql);
      $this->_clean_cache();
    }
}

function set_type ($src, $width, $height, $thumbnail_type = 0) {
  // Try to detect what kind of image we are currently processing, so we can do what have to do with it.
  global $product_info;

  $this->thumbnail_type = $thumbnail_type;
  if ($this->thumbnail_type==0) {  // If thumbnail type is already chosen, do not try to find it again.

    if ( $width == SMALL_IMAGE_WIDTH && $height == SMALL_IMAGE_HEIGHT) $this->thumbnail_type=1;  // Small image
      elseif ($width == HEADING_IMAGE_WIDTH && $height == HEADING_IMAGE_HEIGHT) $this->thumbnail_type=2;  // Heading image
      elseif ($width == SUBCATEGORY_IMAGE_WIDTH && $height == SUBCATEGORY_IMAGE_HEIGHT) $this->thumbnail_type=3;  // Subcategory image

      // Allow for a new intermediate sized thumbnail size to be set without any changes having to be made to the product_info page itself.
      elseif (basename ($_SERVER['PHP_SELF'])== FILENAME_PRODUCT_INFO) {
        if (isset($product_info['products_image'])
          && $src == DIR_WS_IMAGES . $product_info['products_image']
          && $product_info['products_id']==(int)$_GET['products_id']) {  // Final check just to make sure that we don't interfere with other contribs.
          $this->thumbnail_type=4;  // Product info image
        }
      } elseif (basename ($_SERVER['PHP_SELF'])== FILENAME_POPUP_IMAGE) {
        $this->thumbnail_type=5;  // Popup image
      }
    }
  }

  function set_size($src, &$width, &$height) {

    // Find out what will be the size of the image to display and if phpThumb is used.
    $this->calculate = CONFIG_CALCULATE_IMAGE_SIZE == 'true' ? true : false;  // Check if we want to calculate the missing dimension.
    $this->process = true;  // So far we want to process the image with phpThumb.
    $this->width = $width;  // Save the desired display dimensions.
    $this->height = $height;

    // Don't calculate nor process if the image is set to a "%" width or height.
    if (strstr($width,'%') == true || strstr($height,'%') == true) {
      $this->process = false;
      return true;
    }

    // Get real image size and type.
    if ($image_size = @getimagesize($src))
      $this->image_type = $image_size[2];  // Image exists, remember its type.
      else {
        $this->process=false;
        if (IMAGE_REQUIRED == 'false') return false;  // Do not display the image.
          else return true;  // Try to display it anyway.
      }

    if ($image_size[0]==1 || $image_size[0]==1 || strstr($src, 'pixel')) {
      // No need to use phpThumb when dealing with small graphics.
      $this->process=false;
      return true;
    }

    // Decide whether or not we want to process this image. Note that we want to always process the popup.
    if (($width == '' && $height == '' && $this->thumbnail_type==0 ) || ($width == $image_size[0] && $height == $image_size[0] && $this->thumbnail_type==0)) {
      if (CFG_PROCESS_GRAPHICS=="False") {
        $this->calculate = false;  // Looks like this is a store graphic rather than product image.
        $this->process = false;
      } else $this->calculate = false;
    }

    // If product info image, set size. Like that no need to modify product_info.php
    if ($this->thumbnail_type ==4) {
      $width = PRODUCT_INFO_IMAGE_WIDTH == '' ? '' : PRODUCT_INFO_IMAGE_WIDTH;
      $height = PRODUCT_INFO_IMAGE_HEIGHT == '' ? '' : PRODUCT_INFO_IMAGE_HEIGHT;
    }

    if ( ($this->calculate) && (empty($width) || empty($height) )) {
      if (empty($width) && tep_not_null($height)) {
        $ratio = $height / $image_size[1];
        $width = $image_size[0] * $ratio;
      } elseif (tep_not_null($width) && empty($height)) {
        $ratio = $width / $image_size[0];
        $height = $image_size[1] * $ratio;
      } elseif (empty($width) && empty($height)) {
        $width = $image_size[0];
        $height = $image_size[1];
      }
    }

    // If the size asked for is greater than the image itself, we check the configs to see if this is allowed and if not over-ride.
    if ($width > $image_size[0] || $height > $image_size[1]) {
      if (CFG_ALLOW_LARGER  != 'True') {
        $width = $image_size[0];
        $height = $image_size[1];
      }
    }

    $this->width = $width;
    $this->height = $height;

    return true;

  }

  function process($src) {
    if ($this->process) {
      // Get parameters for phpThumb.
      $src = '/'.$src;
      $params = 'src='.$this->_encrypt($src)
      .'&w=' . tep_output_string($this->width)
      .'&h=' . tep_output_string($this->height).$this->_get_params();

      // If we are going to ZoomCrop
      if( OSCTHUMB_USEZC == 'True'){
    	$params .= '&zc=1';
      }

      // if there were additional params set in the admin
      if(strlen(trim(OSCTHUMB_ADDITIONAL) ) > 0){
    	$params .= '&'.OSCTHUMB_ADDITIONAL;
      }
      $image = '<img src="'.$this->_phpThumbURL($params).'"';
      // die ('<pre>'.$src.'</pre>');
    } else $image = '<img src="' . $src . '"';
      return $image;
  }

// =================
// PRIVATE FUNCTIONS
// =================

  function _encrypt($src) {
    // Encrypt the image filename if switched on.
    if (CFG_ENCRYPT_FILENAMES == "True" && CFG_ENCRYPTION_KEY !="") {
      $result = '';
      $key = CFG_ENCRYPTION_KEY;
      for($i=0; $i<strlen($src); $i++) {
        $char = substr($src, $i, 1);
        $keychar = substr($key, ($i % strlen($key))-1, 1);
        $char = chr(ord($char)+ord($keychar));
        $result.=$char;
      }
      $src = $this->_urlsafe_b64encode($result);
    }
    return $src;
  }

  function _get_params() {

    // Set the output quality and effects based on the type of thumbnail.
    switch ($this->thumbnail_type) {
      case 1:  // Small image.
      if (FRAME_SMALL=="Yes") $frame=true;
      if (BEVEL_SMALL=="Yes") $bevel=true;
      if (USE_WATERMARK_IMAGE_SMALL =="Yes") $image_watermark=true;
      if (USE_WATERMARK_TEXT_SMALL =="Yes") $text_watermark=true;
      $quality=(int)SMALL_JPEG_QUALITY;
      break;

      case 2:  // Heading image.
      if (FRAME_HEADING=="Yes") $frame=true;
      if (BEVEL_HEADING=="Yes") $bevel=true;
      if (USE_WATERMARK_IMAGE_HEADING =="Yes") $image_watermark=true;
      if (USE_WATERMARK_TEXT_HEADING =="Yes") $text_watermark=true;
      $quality=(int)HEADING_JPEG_QUALITY;
      break;

      case 3:  // Category image.
      if (FRAME_CATEGORY=="Yes") $frame=true;
      if (BEVEL_CATEGORY=="Yes") $bevel=true;
      if (USE_WATERMARK_IMAGE_CATEGORY =="Yes") $image_watermark=true;
      if (USE_WATERMARK_TEXT_CATEGORY =="Yes") $text_watermark=true;
      $quality=(int)CATEGORY_JPEG_QUALITY;
      break;

      case 4:  // Product info.
      if (FRAME_PRODUCT=="Yes") $frame=true;
      if (BEVEL_PRODUCT=="Yes") $bevel=true;
      if (USE_WATERMARK_IMAGE_PRODUCT =="Yes") $image_watermark=true;
      if (USE_WATERMARK_TEXT_PRODUCT =="Yes") $text_watermark=true;
      $quality=(int)PRODUCT_JPEG_QUALITY;
      break;

      case 5:  // Popup.
      if (FRAME_POPUP=="Yes") $frame=true;
      if (BEVEL_POPUP=="Yes") $bevel=true;
      if (USE_WATERMARK_IMAGE_POPUP =="Yes") $image_watermark=true;
      if (USE_WATERMARK_TEXT_POPUP =="Yes") $text_watermark=true;
      $quality=(int)POPUP_JPEG_QUALITY;
      break;

      default:
      $frame = false;
      $bevel = false;
      $image_watermark = false;
      $text_watermark = false;
      $quality=95;
      break;
    }
    if ($text_watermark) {
      $watermark_font= WATERMARK_TEXT_FONT=='default'? '':WATERMARK_TEXT_FONT;
      $params = "&fltr[]=wmt|".WATERMARK_TEXT."|".WATERMARK_TEXT_SIZE."|".WATERMARK_TEXT_POSITION."|".WATERMARK_TEXT_COLOR."|".$watermark_font."|"
      .WATERMARK_TEXT_OPACITY."|".WATERMARK_TEXT_MARGIN."|".WATERMARK_TEXT_ANGLE;
    }
    if ($image_watermark) {
      $params .= "&fltr[]=wmi|/phpThumb/watermarks/".WATERMARK_IMAGE."|".WATERMARK_IMAGE_POSITION."|".WATERMARK_IMAGE_OPACITY."|".WATERMARK_IMAGE_MARGIN;
    }
    if ($bevel) {
      $params .= "&fltr[]=bvl|".BEVEL_HEIGHT."|".BEVEL_HIGHLIGHT."|".BEVEL_SHADOW;
    }
    if ($frame) {
      $params .= "&fltr[]=fram|".FRAME_WIDTH."|".FRAME_EDGE_WIDTH."|".FRAME_COLOR."|".FRAME_INSIDE_COLOR1."|".FRAME_INSIDE_COLOR2;
    }

    // 1 = GIF, 2 = JPG, 3 = PNG, 4 = SWF, 5 = PSD, 6 = BMP, 7 = TIFF(intel byte order), 8 = TIFF(motorola byte order), 9 = JPC, 10 = JP2, 11 = JPX, 12 = JB2, 13 = SWC, 14 = IFF
    switch($this->image_type) {
      case 1:  // GIF
      $params.= CFG_GIFS_AS_JPEGS == 'True'? "&f=jpg" : "&f=gif";
      $params.= "&bg=".CFG_MATTE_COLOR;
      break;
      case 3:  // PNG
      $params.= "&f=png";
      break;
      default:
      $params .="&f=jpg";
    }  // Switch

    $params .= "&q=$quality";
    return $params;
  }

  function _urlsafe_b64encode($string) {
    $data = base64_encode($string);
    // First, perform a base64_encode, THEN, remplace + by -, / by _, = by .
    $data = str_replace(array('+','/','='),array('-','_','.'),$data);
    return $data;
  }

  function _clean_cache() {
    // Clean up the cache.
    if (chdir($this->cache_dir)) {
      if (glob("*.*")!=false)
      foreach (glob("*.*") as $filename) {
        if (!is_dir($filename)) {
          unlink($filename);
        }
      }
    chdir(DIR_FS_CATALOG);
    }
  }

  // Function for generating hashed calls to phpThumb if 'high_security_enabled'.
  function _phpThumbURL($ParameterString) {
    return 'oscthumb.php?'.$ParameterString.'&hash='.md5($ParameterString.CFG_ENCRYPTION_KEY);
  }

}
?>
