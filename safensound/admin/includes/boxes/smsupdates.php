<?php
/*

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
/*
  
  File: catalog/admin/includes/boxes/smsupdates.php

  Order Updates by SMS, contribution for osCommerce
  http://www.scriptmagic.net
  
  Copyright (c) 2005 Thunder Raven-Stoker


*/
?>
<!-- smsupdates //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_SMSUPDATES,
                     'link'  => tep_href_link(FILENAME_SMSDEFAULTS, 'selected_box=smsupdates'));

  if ($selected_box == 'smsupdates') {
    $contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_SMSDEFAULTS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_SMSUPDATES_DEFAULTS . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_SMSCOUNTRIES, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_SMSUPDATES_COUNTRIES . '</a>');
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- smsupdates_eof //-->
