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
            <td>
<?php
  #$info_box_contents = array();
  #$info_box_contents[] = array('text' => BOX_HEADING_INFORMATION);

  #new infoBoxHeading($info_box_contents, false, false, false, $column_location);

  $info_box_contents = array();
  $info_box_contents[] = array( 'align' => 'left',
								'text' => '<a href="delivery.php">&nbsp;&nbsp;&nbsp;&nbsp;Delivery&nbsp;&nbsp;' .
										 '<img src="' . DIR_WS_TEMPLATE_IMAGES . 'az_arrow_1.gif" \ border="0">' .
                                         '</a>&nbsp;&nbsp;<a href="privacy.php">Privacy Policy&nbsp;&nbsp;' . 						 '<img src="' . DIR_WS_TEMPLATE_IMAGES . 'az_arrow_1.gif" \ border="0">' .

                                         '</a>&nbsp;&nbsp;<a href="terms.php">Terms & Conditions&nbsp;&nbsp;' . 						 '<img src="' . DIR_WS_TEMPLATE_IMAGES . 'az_arrow_1.gif" \ border="0">' .
                                         '</a>&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CONTACT_US) . '">' . BOX_INFORMATION_CONTACT . '&nbsp;&nbsp;');

  new infoBox($info_box_contents, $column_location, 0, 0);
?>
            </td>
          </tr>
<!-- information_eof //-->
