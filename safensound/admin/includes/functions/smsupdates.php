<?php
/*

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
/*

  File: catalog/admin/includes/functions/smsupdates.php

  Order Updates by SMS, contribution for osCommerce
  http://www.scriptmagic.net

  Copyright (c) 2005 Thunder Raven-Stoker


*/

function tep_get_sms_default_message_name($sms_default_message_id, $sms_language_id = '') {

    global $languages_id;

    if ($sms_default_message_id < 1) return TEXT_DEFAULT;

    if (!is_numeric($sms_language_id)) $sms_language_id = $languages_id;

    $sql = "select sms_default_message_name from " . TABLE_SMS_DEFAULTS . " where sms_default_message_id = '" . (int)$sms_default_message_id . "' and sms_language_id = '" . (int)$sms_language_id . "'";

    $status_query = tep_db_query($sql);
    $status = tep_db_fetch_array($status_query);

    return $status['sms_default_message_name'];
}

function tep_get_sms_default_message_text($sms_default_message_id, $sms_language_id = '') {
    global $languages_id;

    if ($sms_default_message_id < 1) return TEXT_DEFAULT;

    if (!is_numeric($sms_language_id)) $sms_language_id = $languages_id;

    $status_query = tep_db_query("select sms_default_message_text from " . TABLE_SMS_DEFAULTS . " where sms_default_message_id = '" . (int)$sms_default_message_id . "' and sms_language_id = '" . (int)$sms_language_id . "'");
    $status = tep_db_fetch_array($status_query);

    return $status['sms_default_message_text'];
}

function tep_get_sms_default_message_selector($language_id='') {
    global  $languages_id;

    if (!is_numeric($sms_language_id)) $sms_language_id = $languages_id;

    $retVal = "<select name='smsdefaultselector' onChange='document.status.smscomments.value = document.status.smsdefaultselector[document.status.smsdefaultselector.selectedIndex].value;'>\n";
    $retVal .= "<option value=''>Choose default sms</option>\n";

    $sms_default_query_raw = "SELECT sms_default_message_name, sms_default_message_text FROM " . TABLE_SMS_DEFAULTS . " WHERE sms_language_id='$language_id' ORDER BY sms_default_message_name ASC";
    $sms_default_query = tep_db_query($sms_default_query_raw);

    if(tep_db_num_rows($sms_default_query)) {
        while($sms_default_msg = tep_db_fetch_array($sms_default_query)) {
            $retVal .= "<option value='" . tep_db_output($sms_default_msg['sms_default_message_text']) . "'>" . tep_db_output($sms_default_msg['sms_default_message_name']) . "</option>\n";
        }
        $retVal .= "</select>";
    } else {
        $retVal = "";
    }

    return $retVal;

}

function tep_get_sms_for_customer($oID) {

    if(empty($oID)) return "";

    $sql = "SELECT c.customers_telephone
            FROM " . TABLE_CUSTOMERS . " AS c,
                 " . TABLE_ORDERS . " AS o
            WHERE  c.customers_id = o.customers_id
            AND    o.orders_id = '" . (int)$oID . "'";
    $customers_sms = tep_db_query($sql);

    $retVal = "";
    if(tep_db_num_rows($customers_sms)) {
        $customer = tep_db_fetch_array($customers_sms);
        $retVal = tep_db_output($customer['customers_smsnumber']);
    }

    return $retVal;

}

function tep_valid_sms_country($oID) {
    if(empty($oID)) return false;

    $sql = "SELECT  c.dialling_prefix
            FROM    " . TABLE_COUNTRIES . " AS c,
                    " . TABLE_CUSTOMERS . " AS cu,
                    " . TABLE_ADDRESS_BOOK . " AS a,
                    " . TABLE_ORDERS . " AS o
            WHERE   c.countries_id = a.entry_country_id
            AND     a.address_book_id = cu.customers_default_address_id
            AND     cu.customers_id = o.customers_id
            AND     o.orders_id='" . (int)$oID . "'";
    $valid_country_query = tep_db_query($sql);
    if(tep_db_num_rows($valid_country_query)) {
        $valid_country_result = tep_db_fetch_array($valid_country_query);
        if(!empty($valid_country_result['dialling_prefix'])) return true;
    }

    return false;
}
?>
