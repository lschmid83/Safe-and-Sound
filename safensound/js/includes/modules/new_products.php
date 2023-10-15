<?php
/*
  $Id: new_products.php,v 1.0 2004/01/09 22:49:58 hpdl Exp $

  template-faq.com, Inc
  http://www.template-faq.com

  Copyright (c) 2004 template-faq.com, Inc

  Released under the GNU General Public License
*/
?>
<!-- new_products //-->


<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => sprintf(TABLE_HEADING_NEW_PRODUCTS, strftime('%B')));

  new contentBoxHeading($info_box_contents, "Center");

  if ( (!isset($new_products_category_id)) || ($new_products_category_id == '0') ) {
    $new_products_query = tep_db_query("select p.products_id, p.products_image, p.products_tax_class_id, if(s.status, s.specials_new_products_price, p.products_price) as products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where products_status = '1' order by p.products_date_added desc limit " . MAX_DISPLAY_NEW_PRODUCTS);
  } else {
    $new_products_query = tep_db_query("select distinct p.products_id, p.products_image, p.products_tax_class_id, if(s.status, s.specials_new_products_price, p.products_price) as products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c where p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and c.parent_id = '" . (int)$new_products_category_id . "' and p.products_status = '1' order by p.products_date_added desc limit " . MAX_DISPLAY_NEW_PRODUCTS);
  }

  $row = 0;
  $col = 0;
  $info_box_contents = array();
  while ($new_products = tep_db_fetch_array($new_products_query)) {
    $product_query = tep_db_query("select products_name, products_description from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$new_products['products_id'] . "' and language_id = '" . (int)$languages_id . "'");
    $product = tep_db_fetch_array($product_query);

     if (tep_not_null($new_products['specials_new_products_price'])) {
       $products_price = '&nbsp;<s>' .  $currencies->display_price($new_products['products_price'], tep_get_tax_rate($new_products['products_tax_class_id'])) . '</s>&nbsp;&nbsp;<span class="productSpecialPrice">' . $currencies->display_price($new_products['specials_new_products_price'], tep_get_tax_rate($new_products['products_tax_class_id'])) . '</span>&nbsp;';
     } else {
       $products_price = '<b>' . $currencies->display_price($new_products['products_price'], tep_get_tax_rate($new_products['products_tax_class_id'])) . '</b>';
     }

    #PR Build product information box
    $product_info_str =  '<table border="0" width="185" cellspacing="0" cellpadding="0" class="hl_product_box" onmouseover="this.className=\'hl_product_box_over\'" onmouseout="this.className=\'hl_product_box\'"><tr><td valign="top">';
    $product_info_str .= '<table border="0" cellspacing="0" cellpadding="0" class="productBoxHeading_tb"><tr>';
    $product_info_str .= '<td class="productBoxHeadingLcorner">&nbsp;</td>';
    $product_info_str .= '<td class="productBoxHeading">'.'</td>';
    $product_info_str .= '<td class="productBoxHeadingRcorner">&nbsp;</td>';
    $product_info_str .= '</tr></table>';
    $product_info_str .= '<table border="0" cellspacing="0" cellpadding="0" class="productBoxOuter"><tr>';
    $product_info_str .= '<td class="productBoxLSide"></td>';
    $product_info_str .= '<td height=100% valign="top" class="productBoxMSide">';
    $product_info_str .= '<table border="0" width="100%" cellspacing="0" cellpadding="0" class="productBox" valign="top"><tr><td align="left">';
    $product_info_str .= '<table width="100%" cellspacing="0" cellpadding="3">';
    $product_info_str .= '<tr><td colspan="2" align="center"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']) . '">'.$product['products_name'].'</a></td></tr>';
    $product_info_str .= '<tr><td align="center"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $new_products['products_image'], $new_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
    $product_info_str .= '<tr><td colspan="2" align="center" class="productBoxPrice">';
    $product_info_str .= $products_price;
    $product_info_str .= '</td></tr></table>';
    $product_info_str .= '</td>';
    $product_info_str .= '</tr></table>';
    $product_info_str .= '</td><td class="productBoxRSide"> </td></tr></table>';
    $product_info_str .= '</td></tr></table>';
    $product_info_str .= '<table border="0" width="100%" cellspacing="0" cellpadding="0" class="productBottomLine" valign="top">';
    $product_info_str .= '<tr><td>';
    $product_info_str .= '</td></tr></table>';


    $info_box_contents[$row][$col] = array('align' => 'center',
                                           'params' => 'class="smallText" width="50%" valign="top"',
                                           'text' => $product_info_str);
    $col ++;
    if ($col > 3) {
      $col = 0;
      $row ++;
    } else {
			$info_box_contents[$row][$col] = array('align' => 'center',
												 'params' => 'class="product_middle_line"',
												 'text' => '<img src="'. DIR_WS_TEMPLATE_IMAGES .'az_prod_vrt_line.gif" alt="" />');
			$col ++;
	}

  }

  new newProductsBox($info_box_contents, true, "Center");
?>
<!-- new_products_eof //-->

