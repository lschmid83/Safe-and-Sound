<?php
/*
  $Id: information.php,v 1.6 2003/02/10 22:31:00 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- information //-->
          <tr>
            <td width="182px">
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => BOX_HEADING_INFORMATION);

  new infoBoxHeading($info_box_contents, false, false, false, $column_location);

  $info_box_contents = array();
  $info_box_contents[] = array('text' => '<a style="padding-left:24px" href="delivery.php">Delivery</a><br>' .
                                         '<a style="padding-left:24px" href="privacy.php">Privacy Policy</a><br>' .
                                         '<a style="padding-left:24px" href="terms.php">Terms & Conditions</a><br>' .
                                         '<a style="padding-left:24px" href="' . tep_href_link(FILENAME_CONTACT_US) . '">' . BOX_INFORMATION_CONTACT . '</a>');

  new infoBox($info_box_contents, $column_location);
?>
            </td>
          </tr>
<!-- information_eof //-->
