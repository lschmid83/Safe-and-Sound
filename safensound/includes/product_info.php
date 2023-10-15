<?php
/*
  $Id: product_info.php,v 1.97 2003/07/01 14:34:54 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);

  $product_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
  $product_check = tep_db_fetch_array($product_check_query);

  $product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
  $product_info = tep_db_fetch_array($product_info_query);

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo $product_info['products_name']; ?></title>
<?php
ob_start();
# cDynamic Meta Tags

require(DIR_WS_INCLUDES . 'meta_tags.php');
$preventDuplicates->checkTarget(ob_get_clean());
echo $preventDuplicates->finalMeta . "\n";
?>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">

<script>
function popUp(pageURL, title,w,h) {
var left = (screen.width/2)-(w/2);
var top = (screen.height/2)-(h/2);
var targetWin = window.open (pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
}
</script>

<script type="text/javascript">
var d;

function sendIt() {
 if (d) document.body.removeChild(d);
 var info=arguments[0].options[arguments[0].selectedIndex].text
 d = document.createElement("script");
 d.src = "store_selection.php?info="+info;
 d.type = "text/javascript";
 document.body.appendChild(d);
}
</script>


<!--lightbox2-->
<script type="text/javascript" src="js/mootools.js"></script>
<script type="text/javascript" src="js/slimbox.js"></script>

<!-- Begin tab pane //-->
<script type="text/javascript" src="includes/tabs/webfxlayout.js"></script>
<link type="text/css" rel="stylesheet" href="includes/tabs/tabpanewebfx.css" />
<script type="text/javascript" src="includes/tabs/tabpane.js"></script>
<!-- End tab pane //-->

<script language="javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
}
//--></script>


<?php
if(!is_firefox())
{
?>
	<link rel="stylesheet" type="text/css" href="tabpane_ie.css">
<?php
}
else
{
?>
	<link rel="stylesheet" type="text/css" href="tabpane_firefox.css">
<?php
}
?>



</head>

<body>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="maincont_tb">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top" class="maincont_left_td"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0" class="leftbar_tb">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="600px" valign="top" class="maincont_mid_td">
	<?php require(DIR_WS_INCLUDES . 'sub_header.php'); ?>
    <?php echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=add_product')); ?><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?php
  if ($product_check['total'] < 1) {
?>
      <tr>
        <td><?php new infoBox(array(array('text' => TEXT_PRODUCT_NOT_FOUND))); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
  } else {
    $product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
    $product_info = tep_db_fetch_array($product_info_query);

    tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and language_id = '" . (int)$languages_id . "'");
	$price = $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id']));

    if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
      $products_price = '<span style="color: black; text-decoration: line-through;">' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span> <span class="productSpecialPrice" style="color: black;" >' . $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
    } else {
      $products_price = '<span class="productSpecialPrice" style="color: black;">' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
    }

    if (tep_not_null($product_info['products_model'])) {
      $products_name = $product_info['products_name'] . '<br><span class="smallText">[' . $product_info['products_model'] . ']</span>';
      $products_model = $product_info['products_model'];
    } else {
      $products_name = $product_info['products_name'];
    }
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading" valign="top"><?php echo $products_name; ?></td>

          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main">

<?php
if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
      $products_price = '<span style="color: black; text-decoration: line-through;">' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span> <span style="color:#FF0000;  font-size:14px" >' . $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
    } else {
      $products_price = '<span style="color:#FF0000; font-size:14px">' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
    }


    if (tep_not_null($product_info['products_image'])) {
?>
				<table width="100%">
                <tr>
                	<td width="350px">
						<center><script language="javascript"><!--
						document.write('<?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image']) . '" target="_blank"  rel="lightbox" title="'.$product_info['products_name'].'" >' . tep_image(DIR_WS_IMAGES . $product_info['products_image'], $product_info['products_name'], 255, 204, 'hspace="5" vspace="5"') . '<br><img src="images/icons/magnify.gif" width="14px" height="15px" style="vertical-align:middle" border="0"> ' . TEXT_CLICK_TO_ENLARGE . '</a>'; ?>');
						//--></script>
						<noscript>
						<?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image']) . '"  target="_blank" rel="lightbox" title="'.$product_info['products_name'].'" >' . tep_image(DIR_WS_IMAGES . $product_info['products_image'], $product_info['products_name'], 255, 204, 'hspace="5" vspace="5"') . '<br>' . TEXT_CLICK_TO_ENLARGE . '</a>'; ?>');
						</noscript>
						</center>
						<br/><br/>
                    </td>
					<td valign="top">
						<table border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td valign="top" width="195px" height="129px" style="background: url('images/product_info_bg.png'); background-repeat: no-repeat; background-position:0px 10px; ">
							<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="195px" style="color:#336699; padding-top:23px; font-size:14px"><center><b>Item No: <?php echo $products_model; ?></b></center></td>
							</tr>
							<tr>
								<td width="195px" style="color:#000000; padding-top:6px; font-size:14px"><center>In Stock Now</center></td>
							</tr>
							<tr>
								<td width="195px" style="padding-top:6px;"><center><b><?php echo $products_price; ?></b></center></td>
							</tr>
							<tr>
								<td width="195px" border="0" style="padding-top:6px;"><center><?php echo tep_draw_hidden_field('products_id', $product_info['products_id']) . tep_add_to_cart('', ''); ?></center></td>
							</tr>
							</table>
							</td>
						</tr>
						<tr>
							<td>
								<table border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td valign="center" style="color:#000000; padding-top:10px;"><img src="images/tick.png" style="padding-top:5px" width="16px" height="16px" border="0"/></td>
									<td style="color:#000000; padding-top:11px; padding-left:5px; font-size:12px">Free UK Mainland Delivery</td>
								</tr>
								<tr>
									<td valign="center" style="color:#000000; padding-top:10px;"><img src="images/tick.png" style="padding-top:5px" width="16px" height="16px" border="0"/></td>
									<td style="color:#000000; padding-top:11px; padding-left:5px; font-size:12px">100% Secure Payment System</td>
								</tr>

								</table>
							</td>
						</tr>
						<tr>
							<td>
								<table border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td style="padding-top:12px;">
									<?php include (DIR_WS_MODULES . FILENAME_ADD_THIS); ?>
									</td>

								</tr>
								</table>
							</td>
						</tr>

						</table>

                    </td>
                </tr>
                </table>
<?php
    }
?>

<!-- begin tab pane //-->
<?php

	//extract product description string from the full description
	$full_product_desc = $product_info['products_description'];
	//$full_product_desc = str_replace('id="prodDesc"', 'style="font-family: Verdana, Arial, sans-serif; font-size: 11px; color: #000;"', $full_product_desc);

	$product_description_string = substr($full_product_desc, 0, strpos($full_product_desc, "</tabtext>")); //remove unwanted html tags (fix replace this with a function which returns the trim with unwanted tags removed)
	$product_description_string = str_replace("<tabname>", "<!--", $product_description_string);
	$product_description_string = str_replace("</tabname>", "-->", $product_description_string);
	$product_description_string = str_replace("<br/>", "", $product_description_string);
	$product_description_string = str_replace("<br />", "", $product_description_string);
	$product_description_string = str_replace("<br>", "", $product_description_string);
	$product_description_string = str_replace("<p></p>", "", $product_description_string);
	$product_description_string = $product_description_string . '<br>';

	//create info box
	if(strlen($product_description_string) > 10)
	{
		//create info box
		$info_box_contents = array();
		$info_box_contents[] = array('text' => 'Description');
		new infoBoxHeading($info_box_contents, false, false, false, $column_location);
		$info_box_contents = array();
		$info_box_contents[] = array('text' => $product_description_string); //add product description to info box
		new infoBox($info_box_contents, $column_location);

		echo '<br>';
	}

	//extract product features from the full description
	$product_features_string = strstr ($full_product_desc, "</newtab>");
	$product_features_string = substr($product_features_string, 0, strpos($product_features_string, "</tabtext>"));  //remove support and more images tab data
	$product_features_string = str_replace("<tabname>", "<!--", $product_features_string);
	$product_features_string = str_replace("</tabname>", "-->", $product_features_string);
	$product_features_string = str_replace("<br/>", "", $product_features_string);
	$product_features_string = str_replace("<br />", "", $product_features_string);
	$product_features_string= str_replace("<br>", "", $product_features_string);
	$product_features_string= str_replace("<p></p>", "", $product_features_string);
	$product_features_string = $product_features_string . '<br>';

	//create info box
	if(strlen($product_features_string) > 20)
	{
		$info_box_contents = array();
		$info_box_contents[] = array('text' => 'Features');
		new infoBoxHeading($info_box_contents, false, false, false, $column_location);
		$info_box_contents = array();
		$info_box_contents[] = array('text' => $product_features_string);
		new infoBox($info_box_contents, $column_location);
		echo '<br>';
	}

	//extract product support from the full description
	$product_support_string  = strstr ($full_product_desc, "</newtab>");
	$product_support_string  = substr($product_support_string, 10); //remove </newtab> from the start of the string
	$product_support_string  = strstr ($product_support_string, "</newtab>"); //get everything up to the end of the support tab
	$product_support_string = substr($product_support_string, 0, strpos($product_support_string, "</tabtext>"));  //remove support and more images tab data
	$product_support_string  = str_replace("<p></p>", "", $product_support_string );
	$product_support_string  = str_replace("<tabname>", "<!--", $product_support_string );
	$product_support_string  = str_replace("</tabname>", "-->", $product_support_string );

	$product_support_string = $product_support_string  . '<br>';


	if(is_firefox())
	{
		$product_support_string  = str_replace("Instruction Manual", "", $product_support_string);
		$product_support_string  = str_replace("Compatible Vehicles", "", $product_support_string);
		$product_support_string  = str_replace("<br/>", "", $product_support_string );
		$product_support_string  = str_replace("<br />", "", $product_support_string );
		$product_support_string = str_replace("<br>", "", $product_support_string );

	}

	if(strlen($product_support_string) > 200)
	{
		//create info box
		$info_box_contents = array();
		$info_box_contents[] = array('text' => 'Support');
		new infoBoxHeading($info_box_contents, false, false, false, $column_location);
		$info_box_contents = array();
		$info_box_contents[] = array('text' => $product_support_string);
		new infoBox($info_box_contents, $column_location);
		echo '<br>';
	}

	//extract more images from the full description
	$product_images_string  = strstr ($full_product_desc, "</newtab>");
	$product_images_string  = substr($product_images_string, 10); //remove </newtab> from the start of the string
	$product_images_string  = strstr ($product_images_string, "</newtab>");
	$product_images_string  = substr($product_images_string, 10); //remove </newtab> from the start of the string

	$product_images_string  = strstr ($product_images_string, "</newtab>"); //get everything up to the end of the support tab

	$product_images_string = str_replace("<tabname>", "<!--", $product_images_string );
	$product_images_string  = str_replace("</tabname>", "-->",$product_images_string );
	$product_images_string  = str_replace("<br/>", "", $product_images_string );
	$product_images_string  = str_replace("<br />", "", $product_images_string );
	$product_images_string = str_replace("<br>", "",$product_images_string );
	$product_images_string = str_replace("<p></p>", "",$product_images_string );
	$product_images_string = $product_images_string  . '<br>';

	if(strlen($product_images_string) > 200)
	{
		//create info box
		$info_box_contents = array();
		$info_box_contents[] = array('text' => 'More Images');
		new infoBoxHeading($info_box_contents, false, false, false, $column_location);
		$info_box_contents = array();
		$info_box_contents[] = array('text' => $product_images_string);
		new infoBox($info_box_contents, $column_location);

	}

?>
	<table border="0" cellspacing="0" cellpadding="2" width="100%">
    <tr>
 		<td width="100%">

		</td>
  	</tr>
 	</table>


        </td>
      </tr>

      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td>
<?php
    if ((USE_CACHE == 'true') && empty($SID)) {
      echo tep_cache_also_purchased(3600);
    } else {
      include(DIR_WS_MODULES . FILENAME_ALSO_PURCHASED_PRODUCTS);
    }
  }
?>
        </td>
      </tr>

      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
    $reviews_query = tep_db_query("select count(*) as count from " . TABLE_REVIEWS . " where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "'");
    $reviews = tep_db_fetch_array($reviews_query);
    if ($reviews['count'] > 0) {
?>
      <tr>
        <td class="main"><?php echo TEXT_CURRENT_REVIEWS . ' ' . $reviews['count']; ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
    }

    if (tep_not_null($product_info['products_url'])) {
?>
      <tr>
        <td class="main"><?php echo sprintf(TEXT_MORE_INFORMATION, tep_href_link(FILENAME_REDIRECT, 'action=url&goto=' . urlencode($product_info['products_url']), 'NONSSL', true, false)); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
    }

    if ($product_info['products_date_available'] > date('Y-m-d H:i:s')) {
?>
      <tr>
        <td align="center" class="smallText"><?php echo sprintf(TEXT_DATE_AVAILABLE, tep_date_long($product_info['products_date_available'])); ?></td>
      </tr>
<?php
    } else {
?>
    <!--  <tr>
        <td align="center" class="smallText"><?php echo sprintf(TEXT_DATE_ADDED, tep_date_long($product_info['products_date_added'])); ?></td>
      </tr> -->
<?php
    }
?>


    </table></form></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top" class="maincont_right_td"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0" class="rightbar_tb">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
