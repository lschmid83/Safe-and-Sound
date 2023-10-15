<?php
/*
  $Id: search.php,v 1.0 2005/02/10 22:31:05 hpdl Exp $

  For osCommerce, Open Source E-Commerce Solutions
  http://www.template-faq.com

  Copyright (c) 2005 template-faq.com

  Released under the GNU General Public License
*/
?>
<!-- search //-->
          <tr>
            <td>
<?php
  $box_string = '<table border="0" width="100%" cellspacing="0" cellpadding="0"><tr>';
  $box_string .= '<td valign="right" class="infoBoxHeadingSearch">' . BOX_HEADING_SEARCH . "&nbsp;</td>";
  $box_string .= '<td valign="right" class="infoBoxContentsSearch">';
  $box_string .= "" . tep_draw_input_field('keywords', '', 'class="az_search_input" size="11" maxlength="30" style="height:12px; width: 150px"') . tep_hide_session_id();
  $box_string .= '</td><td valign="right" class="infoBoxContentsSearch">';
  $box_string .= '&nbsp;'. tep_image_submit('button_quick_find.gif', BOX_HEADING_SEARCH, 'class="submit_button" align=absMiddle', true);
  $box_string .= '</td></tr></table>';


  $info_box_contents = array();
  $info_box_contents[] = array('form' => tep_draw_form('quick_find', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', true), 'get'),
                               'align' => 'center',
                               'text' =>  $box_string);

  new infoBox($info_box_contents, "search", 0, 0);
?>
            </td>
          </tr>
<!-- search_eof //-->
