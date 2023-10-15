
<?php
/*
  $Id: header.php, template: OS03C00286 v3.00 09/25/08  13:07:09 hpdl Exp $

  This file created as a part of graphical design by AlgoZone, Inc
  http://www.algozone.com for osCommerce v 2.2ms2

  Copyright (c) 2003-2005 AlgoZone, Inc

*/

//PR Define template image directory. Can be used to store template images in different location
define('DIR_WS_TEMPLATE_IMAGES', DIR_WS_IMAGES);
//PR Redefine DIR_WS_BOXES_AZ for template boxes. Redundent but needed
define('DIR_WS_BOXES_AZ', DIR_WS_BOXES);

// check if the 'install' directory exists, and warn of its existence
  if (WARN_INSTALL_EXISTENCE == 'true') {
    if (file_exists(dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/install')) {
      $messageStack->add('header', WARNING_INSTALL_DIRECTORY_EXISTS, 'warning');
    }
  }

// check if the configure.php file is writeable
  if (WARN_CONFIG_WRITEABLE == 'true') {
    if ( (file_exists(dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/includes/configure.php')) && (is_writeable(dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/includes/configure.php')) ) {
      $messageStack->add('header', WARNING_CONFIG_FILE_WRITEABLE, 'warning');
    }
  }

// check if the session folder is writeable
  if (WARN_SESSION_DIRECTORY_NOT_WRITEABLE == 'true') {
    if (STORE_SESSIONS == '') {
      if (!is_dir(tep_session_save_path())) {
        $messageStack->add('header', WARNING_SESSION_DIRECTORY_NON_EXISTENT, 'warning');
      } elseif (!is_writeable(tep_session_save_path())) {
        $messageStack->add('header', WARNING_SESSION_DIRECTORY_NOT_WRITEABLE, 'warning');
      }
    }
  }

// check session.auto_start is disabled
  if ( (function_exists('ini_get')) && (WARN_SESSION_AUTO_START == 'true') ) {
    if (ini_get('session.auto_start') == '1') {
      $messageStack->add('header', WARNING_SESSION_AUTO_START, 'warning');
    }
  }

  if ( (WARN_DOWNLOAD_DIRECTORY_NOT_READABLE == 'true') && (DOWNLOAD_ENABLED == 'true') ) {
    if (!is_dir(DIR_FS_DOWNLOAD)) {
      $messageStack->add('header', WARNING_DOWNLOAD_DIRECTORY_NON_EXISTENT, 'warning');
    }
  }

 //if ($messageStack->size('header') > 0) {
 //   echo $messageStack->output('header');
 // }
?>


<script type='text/javascript' src='js/x_core.js'></script>
<script type='text/javascript' src='js/x_dom.js'></script>
<script type='text/javascript' src='js/x_event.js'></script>
<script type='text/javascript' src='js/az_animebox.js'></script>
<?php
	//NOTE: To change width of the site you need to change $template_width variable.
	//      Example, $template_width='100%' to make width 100%
	//      NOTE: Not all templates can be 100% width
	$template_width='950';
?>
<?php require(DIR_WS_INCLUDES . '/browser_agent.php'); ?>
<?php require(DIR_WS_LANGUAGES . 'english/menu.php'); ?>
<?php
	$css_buttons_fix = ($agent['browser'] != 'msie') ? 'style="padding-top: 1px; padding-bottom: 4px;"' : '' ;
	$browser_type = $agent['browser'];
	function az_tep_image($image, $alt = '', $parameters = '') {
		global $css_buttons_fix, $browser_type;
		if($browser_type ==  'msie'){
			$button_html = '<div class="az_button_img" '. $css_buttons_fix .'>';
			$button_html .= '<img src="'. DIR_WS_TEMPLATE_IMAGES . 'az_button_left.gif" border="0" align="absmiddle">';
			$button_html .= '&nbsp;&nbsp;'.$alt.'&nbsp;&nbsp;';
			$button_html .= '<img src="'. DIR_WS_TEMPLATE_IMAGES . 'az_button_right.gif" border="0" align="absmiddle">';
			$button_html .= '</div>';
		}
		else {
			$button_html = '<table border=0 cellspacing=0 cellpadding=0><tr>';
			$button_html .= '<td><img src="'. DIR_WS_TEMPLATE_IMAGES . 'az_button_left.gif" border="0" align="absmiddle"></td>';
			$button_html .= '<td class="az_button_submit" style="vertical-align:middle">&nbsp;&nbsp;'.$alt.'&nbsp;&nbsp;</td>';
			$button_html .= '<td><img src="'. DIR_WS_TEMPLATE_IMAGES . 'az_button_right.gif" border="0" align="absmiddle"></td>';
			$button_html .= '</tr></table>';
		}
		return $button_html;
	}
	function az_tep_image_submit($image, $alt = '', $parameters = '') {
		global $css_buttons_fix;
		$button_html = '<IMG border="0" SRC="'. DIR_WS_TEMPLATE_IMAGES . 'az_button_left.gif">';
		$button_html .= '<input class="az_button_submit" '. $css_buttons_fix .' type="submit" value="'.$alt.'" ' . $parameters.  ' >';
		$button_html .= '<IMG border="0" SRC="'. DIR_WS_TEMPLATE_IMAGES . 'az_button_right.gif">';
		return $button_html;
	}
?>

<!-- animBoxes -->
<script type="text/javascript">
xAddEventListener(window, 'load', function() { newbox = new animBox('animBoxCart', 1, 2, 1, <?php echo ($cart->count_contents() > 0) ? (sizeof($cart->get_products()) * 500 - (sizeof($cart->get_products()) - 1) * 250) : 250; ?>); xOpacity('animBoxCart', 0.9); newbox.reposition('btn_animBoxCart', '-', '+'); }, false);
</script>

<div id="animBoxCart" class="animBoxDropover jsContainer">
<?php
	if (!defined('CART_IMAGE_WIDTH')) { define('CART_IMAGE_WIDTH', 50); }
	if (!defined('CART_IMAGE_HEIGHT')) { define('CART_IMAGE_HEIGHT', NULL); }

	if ($cart->count_contents() > 0)
	{
		echo '<table class="animBoxCartContent" width="100%" cellpadding="5" cellspacing="0">';
		echo '  <tr><td class="animBoxCartHeader" colspan="2"><a href="'. tep_href_link(FILENAME_SHOPPING_CART) .'">'. TEXT_ANIMEBOX_PRE_LINK . TEXT_ANIMEBOX_CART_LINK .'</a></td></tr>';
		$products = $cart->get_products();
		for ($i=0, $n=sizeof($products); $i<$n; $i++)
		{
			$products_name = '  <tr>' .
											 '    <td class="animBoxCartImage" width="'. CART_IMAGE_WIDTH .'" align="center"><a href="'. tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) .'">'. tep_image(DIR_WS_IMAGES . $products[$i]['image'], $products[$i]['name'], CART_IMAGE_WIDTH, CART_IMAGE_HEIGHT) .'</a></td>' .
											 '    <td>' .
											 '      <strong><a href="'. tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) .'">'. $products[$i]['name'] .'</a></strong><br />';

			if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes']))
			{
				reset($products[$i]['attributes']);
				while (list($option, $value) = each($products[$i]['attributes']))
				{
          $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix
                                      from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                                      where pa.products_id = '" . (int)$products[$i]['id'] . "'
                                       and pa.options_id = '" . (int)$option . "'
                                       and pa.options_id = popt.products_options_id
                                       and pa.options_values_id = '" . (int)$value . "'
                                       and pa.options_values_id = poval.products_options_values_id
                                       and popt.language_id = '" . (int)$languages_id . "'
                                       and poval.language_id = '" . (int)$languages_id . "'");
          $attributes_values = tep_db_fetch_array($attributes);
          $products[$i][$option]['products_options_name'] = $attributes_values['products_options_name'];
          $products[$i][$option]['options_values_id'] = $value;
          $products[$i][$option]['products_options_values_name'] = $attributes_values['products_options_values_name'];
          $products[$i][$option]['options_values_price'] = $attributes_values['options_values_price'];
          $products[$i][$option]['price_prefix'] = $attributes_values['price_prefix'];
					$products_name .= '<em> - '. $products[$i][$option]['products_options_name'] .': '. $products[$i][$option]['products_options_values_name'] . '</em><br />';
				}
			}

			$products_name .= '      '. TEXT_ANIMEBOX_CART_QUANTITY .' '. $products[$i]['quantity'] . '<br />' .
												'      '. $currencies->display_price($products[$i]['final_price'], tep_get_tax_rate($products[$i]['tax_class_id']), $products[$i]['quantity']) .'</a><br />' .
												'      <a href="'. tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) .'">'. TEXT_ANIMEBOX_PRE_LINK . TEXT_ANIMEBOX_CART_MORE_INFO .'</a>' .
												'    </td>' .
												'  </tr>';
			echo $products_name;
		}
		echo '  <tr><td class="animBoxCartFooter" colspan="2"><a>'. TEXT_ANIMEBOX_CART_SUBTOTAL .'&nbsp;'. $currencies->format($cart->show_total()) .'</a></td></tr>';
		echo '</table>';
	}

	else // ($cart->count_contents() > 0)
	{
		echo '<div class="animBoxCartNotice">'. TEXT_ANIMEBOX_CART_EMPTY .'</div>';
	}
?>
</div>
<!-- animBoxes_eof -->

<!-- top logo header -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
<td class="az_leftpage_side" height="100%" valign="top">
	<table width="100%" height="100%" border="0" align="right" cellpadding="0" cellspacing="0">
	<tr><td valign="top" class="az_leftpage_side_top" height="376"></td></tr>
	<tr><td valign="top"><?php echo tep_draw_separator('pixel_trans.gif', '1', '100%'); ?></td></tr>
	<tr><td valign="bottom" class="az_leftpage_side_bottom" height="109"></td></tr>
	</table>
</td>
<td width="<?= $template_width ?>">
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="az_main_cont">
<tr>
	<td>
	<table width="100%" border="0" align="center" valign="top" cellpadding="0" cellspacing="0">
	<tr>
		<td width="950" style="padding-top:3px">
			<img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>az_header_top.jpg" border="0"/><br/>
		</td>
	</tr>
	<tr>
		<td width="950" class="topbanner_tb">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="menubarmain">
				<tr>
					<td align="center" width="180"><a href="<?php echo tep_href_link(FILENAME_DEFAULT); ?>">Home</a></td>
					<td align="center" width="6"><img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>az_header_div.gif" border="0"></td>
					<td align="center" width="180"><a href="/catalog/gallery.php">Gallery</a></td>
					<td align="center" width="6"><img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>az_header_div.gif" border="0"></td>
					<td align="center" width="180"><a href="asnf_index.php">Forum</a></td>
					<td align="center" width="6"><img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>az_header_div.gif" border="0"></td>
					<?php

						echo '<td align="center" width="180"><a href="';
					  	echo tep_href_link(FILENAME_ACCOUNT, '', 'SSL');

						// if the customer is not logged on, display account login
						if (!tep_session_is_registered('customer_id'))
						{
					  		echo '">Login</a></td>';
					  	}
					  	else
					  	{
					  		echo '">Account</a></td>';
					  	}
					?>
					<td align="center" width="6"><img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>az_header_div.gif" border="0"></td>
					<td align="center" width="180"><a href="<?php echo tep_href_link(FILENAME_CONTACT_US); ?>">Contact Us</a></td>
				</tr>
			</table>
		</td>
	</tr>
	</table>
	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td width="950"><img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>az_main_pic.jpg" border="0"></td>
	</tr>
	</table>
	<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center"  class="az_info_bar" style="padding-bottom:1px;">
	<tr>
		<td width="9" valign="top"><img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>az_header_bar_left.gif" border="0"></td>
	    <td width="290" valign="top" align="left" style="color:#FFF; padding-left:4px; padding-top:5px" class="az_info_bar_td2">Free UK Mainland Delivery</td>
	    <td valign="top" width="10" ><img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>az_header_bar_div.gif" border="0"></td>
	    <td valign="top" align="center">
	    	<table valign="top" border="0" cellspacing="0" cellpadding="0" width="100%">
	  		<?php $column_location = 'Search'; ?>
	        <?php require(DIR_WS_BOXES_AZ . 'search.php'); ?>
	        <?php $column_location = ''; ?>
	        </table>
	    </td>
	  	   <td width="10" valign="top"><img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>az_header_bar_div.gif" border="0"></td>
	  	   <td width="300" valign="top" align="center" class="az_info_bar_td3">
					<div valign="top" id="btn_animBoxCart" class="jsTrigger">
						<table valign="top" style="padding-top:1px" width="200" border="0" cellspacing="0" cellpadding="0" align="right">
						<?php $column_location = 'Cart'; ?>
						<?php include(DIR_WS_BOXES_AZ . 'shopping_cart_short_az02.php'); ?>
						<?php $column_location = ''; ?>
						</table>
					</div>
	  		</td>
	  	   <td width="6" style="padding-top:2px" valign="top"><img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>az_header_bar_right.gif" border="0"></td>
	  	</tr>
	  	</table>
	  	<table width="100%" border="0" valign="top" cellpadding="0" cellspacing="0">
	  	<tr>
		<td>
<?php
  if (isset($HTTP_GET_VARS['error_message']) && tep_not_null($HTTP_GET_VARS['error_message'])) {
?>

<!-- Top table with error area-->
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr class="headerError">
    <td class="headerError"><?php echo urldecode($HTTP_GET_VARS['error_message']); ?></td>
  </tr>
</table>
<?php
  }

  if (isset($HTTP_GET_VARS['info_message']) && tep_not_null($HTTP_GET_VARS['info_message'])) {
?>

<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr class="headerInfo">
    <td class="headerInfo"><?php echo $HTTP_GET_VARS['info_message']; ?></td>
  </tr>
</table>
<?php
  }
?>

