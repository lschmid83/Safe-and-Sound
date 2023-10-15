<?php
/*

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
/*
  
  File: catalog/admin/smsdefaults.php

  Order Updates by SMS, contribution for osCommerce
  http://www.scriptmagic.net
  
  Copyright (c) 2005 Thunder Raven-Stoker


*/
  require('includes/application_top.php');
  require(DIR_WS_FUNCTIONS . "smsupdates.php");
  
  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'insert':
      case 'save':
        if (isset($HTTP_GET_VARS['dID'])) $sms_default_message_id = tep_db_prepare_input($HTTP_GET_VARS['dID']);

        $languages = tep_get_languages();
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $sms_default_message_name_array = $HTTP_POST_VARS['sms_default_message_name'];
          $sms_default_message_text_array = $HTTP_POST_VARS['sms_default_message_text'];
          $language_id = $languages[$i]['id'];

          $sql_data_array = array('sms_default_message_name' => tep_db_prepare_input($sms_default_message_name_array[$language_id]),
                                  'sms_default_message_text' => tep_db_prepare_input($sms_default_message_text_array[$language_id]) );

          if ($action == 'insert') {
            if (empty($sms_default_message_id)) {
              $next_id_query = tep_db_query("select max(sms_default_message_id) as sms_default_message_id from " . TABLE_SMS_DEFAULTS . "");
              $next_id = tep_db_fetch_array($next_id_query);
              $sms_default_message_id = $next_id['sms_default_message_id'] + 1;
            }

            $insert_sql_data = array('sms_default_message_id' => $sms_default_message_id,
                                     'sms_language_id' => $language_id);

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            tep_db_perform(TABLE_SMS_DEFAULTS, $sql_data_array);
          } elseif ($action == 'save') {
            tep_db_perform(TABLE_SMS_DEFAULTS, $sql_data_array, 'update', "sms_default_message_id = '" . (int)$sms_default_message_id . "' and sms_language_id = '" . (int)$language_id . "'");
          }
          $sms_default_message_id = ""; 
        }
        tep_redirect(tep_href_link(FILENAME_SMSDEFAULTS, 'page=' . $HTTP_GET_VARS['page'] . '&dID=' . $sms_default_message_id));
        break;
      case 'deleteconfirm':
        $dID = tep_db_prepare_input($HTTP_GET_VARS['dID']);
        tep_db_query("delete from " . TABLE_SMS_DEFAULTS . " where sms_default_message_id = '" . tep_db_input($dID) . "'");
        tep_redirect(tep_href_link(FILENAME_SMSDEFAULTS, 'page=' . $HTTP_GET_VARS['page']));
        break;
      case 'delete':
        $dID = tep_db_prepare_input($HTTP_GET_VARS['dID']);
        $remove_status = true;
        break;
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_SMS_DEFAULT_MSG; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $sms_default_message_query_raw = "select sms_default_message_id, sms_default_message_name from " . TABLE_SMS_DEFAULTS . " where sms_language_id = '" . (int)$languages_id . "' order by sms_default_message_id";
  $sms_default_message_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $sms_default_message_query_raw, $sms_default_message_query_numrows);
  $sms_default_message_query = tep_db_query($sms_default_message_query_raw);
  while ($sms_default_message = tep_db_fetch_array($sms_default_message_query)) {
    if ((!isset($HTTP_GET_VARS['dID']) || (isset($HTTP_GET_VARS['dID']) && ($HTTP_GET_VARS['dID'] == $sms_default_message['sms_default_message_id']))) && !isset($oInfo) && (substr($action, 0, 3) != 'new')) {
      $oInfo = new objectInfo($sms_default_message);
    }

    if (isset($oInfo) && is_object($oInfo) && ($sms_default_message['sms_default_message_id'] == $oInfo->sms_default_message_id)) {
      echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_SMSDEFAULTS, 'page=' . $HTTP_GET_VARS['page'] . '&dID=' . $oInfo->sms_default_message_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_SMSDEFAULTS, 'page=' . $HTTP_GET_VARS['page'] . '&dID=' . $sms_default_message['sms_default_message_id']) . '\'">' . "\n";
    }

      echo '                <td class="dataTableContent">' . $sms_default_message['sms_default_message_name'] . '</td>' . "\n";
    
?>
                <td class="dataTableContent" align="right"><?php if (isset($oInfo) && is_object($oInfo) && ($sms_default_message['sms_default_message_id'] == $oInfo->sms_default_message_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_SMSDEFAULTS, 'page=' . $HTTP_GET_VARS['page'] . '&dID=' . $sms_default_message['sms_default_message_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $sms_default_message_split->display_count($sms_default_message_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_SMSDEFAULTS); ?></td>
                    <td class="smallText" align="right"><?php echo $sms_default_message_split->display_links($sms_default_message_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></td>
                  </tr>
<?php
  if (empty($action)) {
?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_SMSDEFAULTS, 'page=' . $HTTP_GET_VARS['page'] . '&action=new') . '">' . tep_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>
                  </tr>
<?php
  }
?>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_SMS_DEFAULT_MESSAGE . '</b>');

      $contents = array('form' => tep_draw_form('status', FILENAME_SMSDEFAULTS, 'page=' . $HTTP_GET_VARS['page'] . '&action=insert'));
      $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);

      $sms_default_message_inputs_string = '';
      $languages = tep_get_languages();
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        $sms_default_message_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('sms_default_message_name[' . $languages[$i]['id'] . ']');
        $sms_default_message_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_textarea_field('sms_default_message_text[' . $languages[$i]['id'] . ']',"soft", 20, 5);
      }

      $contents[] = array('text' => '<br>' . TEXT_INFO_SMS_DEFAULT_MSG_NAME . $sms_default_message_inputs_string);
      
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_insert.gif', IMAGE_INSERT) . ' <a href="' . tep_href_link(FILENAME_SMSDEFAULTS, 'page=' . $HTTP_GET_VARS['page']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_SMS_DEFAULT_MESSAGE . '</b>');

      $contents = array('form' => tep_draw_form('status', FILENAME_SMSDEFAULTS, 'page=' . $HTTP_GET_VARS['page'] . '&dID=' . $oInfo->sms_default_message_id  . '&action=save'));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);

      $sms_default_message_inputs_string = '';
      $languages = tep_get_languages();
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        $sms_default_message_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('sms_default_message_name[' . $languages[$i]['id'] . ']', tep_get_sms_default_message_name($oInfo->sms_default_message_id, $languages[$i]['id']));
        $sms_default_message_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_textarea_field('sms_default_message_text[' . $languages[$i]['id'] . ']',"soft", 20, 5, tep_get_sms_default_message_text($oInfo->sms_default_message_id, $languages[$i]['id']));
        $sms_default_message_inputs_string .= '<p>';                                                                                                                                                                               
      
      }

      $contents[] = array('text' => '<br>' . TEXT_INFO_ORDERS_STATUS_NAME . $sms_default_message_inputs_string);
      if (DEFAULT_ORDERS_STATUS_ID != $oInfo->sms_default_message_id) $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . tep_href_link(FILENAME_SMSDEFAULTS, 'page=' . $HTTP_GET_VARS['page'] . '&dID=' . $oInfo->sms_default_message_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_SMS_DEFAULT_MESSAGE . '</b>');

      $contents = array('form' => tep_draw_form('status', FILENAME_SMSDEFAULTS, 'page=' . $HTTP_GET_VARS['page'] . '&dID=' . $oInfo->sms_default_message_id  . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $oInfo->sms_default_message_name . '</b>');
      if ($remove_status) $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_SMSDEFAULTS, 'page=' . $HTTP_GET_VARS['page'] . '&dID=' . $oInfo->sms_default_message_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (isset($oInfo) && is_object($oInfo)) {
        $heading[] = array('text' => '<b>' . $oInfo->sms_default_message_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_SMSDEFAULTS, 'page=' . $HTTP_GET_VARS['page'] . '&dID=' . $oInfo->sms_default_message_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_SMSDEFAULTS, 'page=' . $HTTP_GET_VARS['page'] . '&dID=' . $oInfo->sms_default_message_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');

        $sms_default_message_inputs_string = '';
        $languages = tep_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $sms_default_message_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_get_sms_default_message_name($oInfo->sms_default_message_id, $languages[$i]['id']);
          $sms_default_message_inputs_string .= '<p>';
        }

        $contents[] = array('text' => $sms_default_message_inputs_string);
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
