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
  $info_box_contents[] = array('text' => 'About Us');

  new infoBoxHeading($info_box_contents, false, false, false, $column_location);

  $info_box_contents = array();
  $info_box_contents[] = array('text' => '<table><tr><td class="main">Welcome to the Safe\'n\'Sound car audio & security store.<br><br>Browse our website to find a wide array of manufactures such as Alpine, Focal, JL Audio, Kenwood, JVC, Genesis, Pioneer, Sony and many more.<br><br>We are here to help you decide on the correct car stereo, amplifier, speakers or just a facia for your vehicle. </td></tr></table>');



  new infoBox($info_box_contents, $column_location);
?>
            </td>
          </tr>
<!-- information_eof //-->
