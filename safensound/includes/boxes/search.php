<?php
/*
  $Id: search.php,v 1.0 2005/02/10 22:31:05 hpdl Exp $

  For osCommerce, Open Source E-Commerce Solutions
  http://www.template-faq.com

  Copyright (c) 2005 template-faq.com

  Released under the GNU General Public License
*/

/**
    * checks for user agent whether this is firefox or not
    * @param void
    * @return bool
    * @author svetoslavm##gmail.com
    * @link http://devquickref.com/
*/



?>
<!-- search //-->
          <tr>
            <td>
<?php




  $box_string = '<table width="100%" cellspacing="0" cellpadding="0"><tr>';

  if(is_firefox())
  {
	  $box_string .= '<td valign=top class="infoBoxHeadingSearch" style="padding-top:2px">' . BOX_HEADING_SEARCH . "&nbsp;</td>";
	  $box_string .= '<td valign="top" class="infoBoxContentsSearch">';
	  $box_string .= "" . tep_draw_input_field('keywords', '', 'class="az_search_input" size="11" align=top maxlength="30" style="padding-top:0px; margin-top:1px; height:16px; width: 150px"') . tep_hide_session_id();
	  $box_string .= '</td><td valign=top  style="padding-top:0px;margin-top:0px">';
	  $box_string .= '&nbsp;'. tep_image_submit('button_quick_find.gif', BOX_HEADING_SEARCH, 'style="margin-top:0px; padding-top:0px" class="submit_button" align=top', true);
  }
  else
  {
	  $box_string .= '<td valign=top class="infoBoxHeadingSearch" style="padding-top:3px; padding-left:6px; padding-right:12px">' . BOX_HEADING_SEARCH . "&nbsp;</td>";
	  $box_string .= '<td valign="top" class="infoBoxContentsSearch">';
	  $box_string .= "" . tep_draw_input_field('keywords', '', 'class="az_search_input" size="11" align=top maxlength="30" style="padding-top:0px; margin-left:0px; margin-top:1px; height:16px; width: 150px"') . tep_hide_session_id();
	  $box_string .= '</td><td valign=top  style="padding-top:0px;margin-top:0px; padding-left:1px; margin-left:0px">';
	  $box_string .= '&nbsp;'. tep_image_submit('button_quick_find.gif', BOX_HEADING_SEARCH, 'style="margin-left:0px; padding-left:0px; margin-top:1px; padding-top:0px" class="submit_button" align=top', true);

  }



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
