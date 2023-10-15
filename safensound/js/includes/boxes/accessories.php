<?php
/*
  $Id: specials.php,v 1.31 2003/06/09 22:21:03 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- specials //-->
          <tr>
            <td >
<?php
    $info_box_contents = array();
    $info_box_contents[] = array('text' => "Accessories");

    new infoBoxHeading($info_box_contents, false, false, tep_href_link(FILENAME_SPECIALS), $column_location);

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'center',
                                 'text' => '<table border="0" width="171px" cellspacing="0" cellpadding="1"><tr><td align="center"><a href=""> <img src="images/acc_kenwood.jpg" border="0"/> </a> <br/>
                                            <a href=""> <img src="images/acc_steering_control.jpg" border="0"/> </a> <br/>
                                            <a href=""> <img src="images/acc_accessories.jpg" border="0"/> </a> <br/></td></tr></table>
                                            ');


    new infoBox($info_box_contents, $column_location);
?>
            </td>
          </tr>
