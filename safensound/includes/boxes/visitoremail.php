<?php
/*
  $Id: visitoremail.php,v 1.16 2003/06/10 18:26:33 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- visitor_e-mail //-->
          <tr>
            <td width="182px" >
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => 'Newsletter');
  new infoBoxHeading($info_box_contents, false, false, false, $column_location);
  $info_box_contents = array();
  $info_box_contents[] = array('form' => tep_draw_form('email', tep_href_link('newsletter subscribe.php', 'action=process')),
                               'align' => 'center',
                               'text' => '<table><tr><td class="main">Enter your email address to receive our special offers!</td></tr></table>' . tep_draw_input_field('to_email_address', '', 'size="24"') . '<br><table><tr><td style="height:1px"></td></tr></table>' . tep_image_submit('button_visitor_email.gif', 'Subscribe'));

  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- visitor_email_eof //-->
