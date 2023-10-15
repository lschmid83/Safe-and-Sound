<?php
/*
  $Id: shopping_cart.php,v 1.18 2003/02/10 22:31:06 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- shopping_cart //-->
          <tr>
            <td>
<?php

  $cart_contents_string = '<table border="0" cellspacing="0" cellpadding="0" width="100%" class="az_cart_body">';
  $cart_contents_string .= '<tr><td style="padding-left:1px">';
  $cart_contents_string .= '<a href="'.tep_href_link('shopping_cart.php'). '">'.tep_image(DIR_WS_TEMPLATE_IMAGES .'az_cart_icon.gif',HEADER_TITLE_CART_CONTENTS).'</a>&nbsp;&nbsp;</td><td style="padding-left:2px; color:#ffffff">' .BOX_HEADING_SHOPPING_CART;
  $cart_contents_string .= '&nbsp;<span class="shopcart_items">'.$cart->count_contents().' item(s) </span> ';
  $cart_contents_string .= '</td></tr>';
  $cart_contents_string .= '</table>';

  $info_box_contents = array();
  $info_box_contents[] = array('text' => $cart_contents_string);

  new infoBox($info_box_contents, $column_location, 0, 0);
?>
            </td>
          </tr>
<!-- shopping_cart_eof //-->
