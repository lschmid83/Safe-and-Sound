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

    new infoBoxHeading($info_box_contents, false, false, false, $column_location);

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'center',
                                 'text' => '<table border="0" width="174px" cellspacing="0" cellpadding="1"><tr><td align="center">
                                            <a href="http://www.safensound.co.uk/handsfree-leads-c-60325.html"> <img src="images/acc_accessories.jpg" border="0" alt=""> </a> <br>
                                            <a href="http://www.safensound.co.uk/ipod-solutions-ipod-c-60318_60330.html"> <img src="images/acc_dice_kits.jpg" border="0" alt=""> </a> <br>
                                            <a href="http://www.safensound.co.uk/steering-control-leads-c-60357.html"> <img src="images/acc_steering_control.jpg" border="0" alt=""> </a> <br>
                                            <a href="http://www.safensound.co.uk/full-fitting-kits-c-60364.html"> <img src="images/acc_car_kits.jpg" border="0" alt=""> </a> <br>
                                            </td></tr></table>
                                            ');


    new infoBox($info_box_contents, $column_location);
?>
