<?php
/*
	$Id: epdq_cpi/callback.php,v 1.06 2006/08/08 Aqua Technologies$

	osCommerce, Open Source E-Commerce Solutions
	http://www.oscommerce.com

	Released under the GNU General Public License

	Copyright © 2006 Aqua Technologies Limited
	http://www.aqnet.co.uk
	support@aqnet.co.uk


	The following callback script must be place in a basic auth
	protected folder and the cpi admin panel at
	https://cpiadmin.epdq.co.uk/cgi-bin/CcxBarclaysEpdqAdminTool.e
	configured with the relevent information to access it
 */

$debug_string = "CPI complete received\n\noid=$oid\n\n";

if (is_array ($HTTP_POST_VARS)) {
    $debug_string .= "Posted variables\n";
    reset ($HTTP_POST_VARS);
    while (list ($key, $val) = each ($HTTP_POST_VARS)) {
        if (is_array ($val)) {
            reset ($val);
            while (list ($k2, $v2) = each ($val)) {
                $debug_string .= "$key $k2 => $v2\n";
            }
        } else {
            $debug_string .= "$key => $val\n";
        }
    }
}

$oid = (int) $HTTP_POST_VARS['oid'];
if (!($cid = strtok($oid, '-')))
    $cid = $oid;

if ($oid<1) {
	echo "Invalid call - no Order ID provided";
	exit();
}

// Make sure we dont output anything until done
ob_start();
chdir("../");

include('includes/application_top.php');

tep_db_connect();
$rs = tep_db_query("select session from epdq_session where oid = $oid");
$row = mysql_fetch_row($rs);
$val = $row[0];
$array = unserialize($val);
foreach($array as $kn => $v) {
    global $$kn;
    $_SESSION[$key] = $$kn = $v;
}

include(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PROCESS);
require(DIR_WS_CLASSES . 'payment.php');
$payment_modules = new payment($payment);
require(DIR_WS_CLASSES . 'shipping.php');
$shipping_modules = new shipping($shipping);

require(DIR_WS_CLASSES . 'order.php');
$order = new order();

$cid = addslashes($cid);
$status = addslashes($HTTP_POST_VARS['transactionstatus']);
if ($cid == "") {
    $t = time();
    $d = date ("d-M-y H:i:s", $t);
    $cid = "bad" . $d;
    $status = "manual call";
}

$customer_notification = (SEND_EMAILS == 'true') ? '1' : '0';

if ($status == "Success") {
    $status_id = MODULE_PAYMENT_EPDQ_PAYSUCCESS;
} else {
    $status_id = MODULE_PAYMENT_EPDQ_PAYFAILED;
    $customer_notification = 0; // whatever this is set too it is zeroed if no transaction as notification wont be sent
}
$qry1 = "INSERT INTO epdq_txnlog (oid, txn_status, txn_time) VALUES ('$oid','$status',NOW());";
$qry2 = "UPDATE orders SET orders_status = '$status_id', last_modified = NOW() WHERE orders_id = '$oid'";
$sql_data_array = array('orders_id' => $oid,
    'orders_status_id' => $status_id,
    'date_added' => 'now()',
    'customer_notified' => $customer_notification,
    'comments' => $order->info['comments']);
tep_db_perform('orders_status_history', $sql_data_array);
$retval = tep_db_query($qry1);
$retval2 = tep_db_query($qry2);

$err = mysql_error();
$debug_string .= "cid = $cid\n$qry1\nResult1 = $retval\n$qry2\nResult2 = $retval2\nErr = $err\n";
mail (STORE_OWNER_EMAIL_ADDRESS,"osCommerce EPDQ Transaction for " . STORE_NAME, $debug_string, "From: " . STORE_OWNER_EMAIL_ADDRESS . "\nReply-To: " . STORE_OWNER_EMAIL_ADDRESS . "\nX-Script-Author: Aqua Technologies Limited\nX-ip:$REMOTE_ADDR\nX-Mailer: PHP/" . phpversion());

if ($status != "Success")
    exit();

// N.B. make sure the before_process method isn't called here as it destroy cart and sessions

// Majority of code below lifted from checkout_process as we cannot rely on
// the user clicking back to the site from the CPI pages, therefore process here
require(DIR_WS_CLASSES . 'order_total.php');
$order_total_modules = new order_total;

$order_totals = $order_total_modules->process();

$sql_data_array = array('customers_id' => $customer_id,
    'customers_name' => $order->customer['firstname'] . ' ' . $order->customer['lastname'],
    'customers_company' => $order->customer['company'],
    'customers_street_address' => $order->customer['street_address'],
    'customers_suburb' => $order->customer['suburb'],
    'customers_city' => $order->customer['city'],
    'customers_postcode' => $order->customer['postcode'],
    'customers_state' => $order->customer['state'],
    'customers_country' => $order->customer['country']['title'],
    'customers_telephone' => $order->customer['telephone'],
    'customers_email_address' => $order->customer['email_address'],
    'customers_address_format_id' => $order->customer['format_id'],
    'delivery_name' => $order->delivery['firstname'] . ' ' . $order->delivery['lastname'],
    'delivery_company' => $order->delivery['company'],
    'delivery_street_address' => $order->delivery['street_address'],
    'delivery_suburb' => $order->delivery['suburb'],
    'delivery_city' => $order->delivery['city'],
    'delivery_postcode' => $order->delivery['postcode'],
    'delivery_state' => $order->delivery['state'],
    'delivery_country' => $order->delivery['country']['title'],
    'delivery_address_format_id' => $order->delivery['format_id'],
    'billing_name' => $order->billing['firstname'] . ' ' . $order->billing['lastname'],
    'billing_company' => $order->billing['company'],
    'billing_street_address' => $order->billing['street_address'],
    'billing_suburb' => $order->billing['suburb'],
    'billing_city' => $order->billing['city'],
    'billing_postcode' => $order->billing['postcode'],
    'billing_state' => $order->billing['state'],
    'billing_country' => $order->billing['country']['title'],
    'billing_address_format_id' => $order->billing['format_id'],
    'payment_method' => $order->info['payment_method'],
    'cc_type' => $order->info['cc_type'],
    'cc_owner' => $order->info['cc_owner'],
    'cc_number' => $order->info['cc_number'],
    'cc_expires' => $order->info['cc_expires'],
    'date_purchased' => 'now()',
    'orders_status' => $status_id,
    'currency' => $order->info['currency'],
    'currency_value' => $order->info['currency_value']);

tep_db_perform(TABLE_ORDERS, $sql_data_array, 'update', 'orders_id=' . $oid . '');

// initialized for the email confirmation
$products_ordered = '';
$subtotal = 0;
$total_tax = 0;
for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
    if (STOCK_LIMITED == 'true') {
        if (DOWNLOAD_ENABLED == 'true') {
            $stock_query_raw = "SELECT products_quantity, pad.products_attributes_filename
        FROM " . TABLE_PRODUCTS . " p
        LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES . " pa
        ON p.products_id=pa.products_id
        LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
        ON pa.products_attributes_id=pad.products_attributes_id
        WHERE p.products_id = '" . tep_get_prid($order->products[$i]['id']) . "'";
            // Will work with only one option for downloadable products
            // otherwise, we have to build the query dynamically with a loop
            $products_attributes = $order->products[$i]['attributes'];
            if (is_array($products_attributes)) {
                $stock_query_raw .= " AND pa.options_id = '" . $products_attributes[0]['option_id'] . "' AND pa.options_values_id = '" . $products_attributes[0]['value_id'] . "'";
            }
            $stock_query = tep_db_query($stock_query_raw);
        } else {
            $stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
        }
        if (tep_db_num_rows($stock_query) > 0) {
            $stock_values = tep_db_fetch_array($stock_query);
            // do not decrement quantities if products_attributes_filename exists
            if ((DOWNLOAD_ENABLED != 'true') || (!$stock_values['products_attributes_filename'])) {
                $stock_left = $stock_values['products_quantity'] - $order->products[$i]['qty'];
            } else {
                $stock_left = $stock_values['products_quantity'];
            }
            tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '" . $stock_left . "' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
            if (($stock_left < 1) && (STOCK_ALLOW_CHECKOUT == 'false')) {
                tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
            }
        }
    }
    // Update products_ordered (for bestsellers list)
    tep_db_query("update " . TABLE_PRODUCTS . " set products_ordered = products_ordered + " . sprintf('%d', $order->products[$i]['qty']) . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");

    // ------insert customer choosen option to order--------
    $attributes_exist = '0';
    $products_ordered_attributes = '';
    if (isset($order->products[$i]['attributes'])) {
        $attributes_exist = '1';
        for ($j = 0, $n2 = sizeof($order->products[$i]['attributes']); $j < $n2; $j++) {
            if (DOWNLOAD_ENABLED == 'true') {
                $attributes_query = "select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix, pad.products_attributes_maxdays, pad.products_attributes_maxcount , pad.products_attributes_filename
          from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
          left join " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
          on pa.products_attributes_id=pad.products_attributes_id
          where pa.products_id = '" . $order->products[$i]['id'] . "'
          and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "'
          and pa.options_id = popt.products_options_id
          and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "'
          and pa.options_values_id = poval.products_options_values_id
          and popt.language_id = '" . $languages_id . "'
          and poval.language_id = '" . $languages_id . "'";
                $attributes = tep_db_query($attributes_query);
            } else {
                $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $order->products[$i]['id'] . "' and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . $languages_id . "' and poval.language_id = '" . $languages_id . "'");
            }
            $attributes_values = tep_db_fetch_array($attributes);
            $products_ordered_attributes .= "\n\t" . $attributes_values['products_options_name'] . ' ' . $attributes_values['products_options_values_name'];
        }
    }
    // ------insert customer choosen option eof ----
    $total_weight += ($order->products[$i]['qty'] * $order->products[$i]['weight']);
    $total_tax += tep_calculate_tax($total_products_price, $products_tax) * $order->products[$i]['qty'];
    $total_cost += $total_products_price;

    $products_ordered .= $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . ' (' . $order->products[$i]['model'] . ') = ' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . $products_ordered_attributes . "\n";
}
// lets start with the email confirmation
$email_order = STORE_NAME . "\n" .
EMAIL_SEPARATOR . "\n" .
EMAIL_TEXT_ORDER_NUMBER . ' ' . $oid . "\n" .
EMAIL_TEXT_INVOICE_URL . ' ' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $oid, 'SSL', false) . "\n" .
EMAIL_TEXT_DATE_ORDERED . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n";
if ($order->info['comments']) {
    $email_order .= tep_db_output($order->info['comments']) . "\n\n";
}
$email_order .= EMAIL_TEXT_PRODUCTS . "\n" .
EMAIL_SEPARATOR . "\n" . $products_ordered .
EMAIL_SEPARATOR . "\n";

for ($i = 0, $n = sizeof($order_totals); $i < $n; $i++) {
    $email_order .= strip_tags($order_totals[$i]['title']) . ' ' . strip_tags($order_totals[$i]['text']) . "\n";
}

if ($order->content_type != 'virtual') {
    $email_order .= "\n" . EMAIL_TEXT_DELIVERY_ADDRESS . "\n" .
    EMAIL_SEPARATOR . "\n" .
    tep_address_label($customer_id, $sendto, 0, '', "\n") . "\n";
}

$email_order .= "\n" . EMAIL_TEXT_BILLING_ADDRESS . "\n" .
EMAIL_SEPARATOR . "\n" .
tep_address_label($customer_id, $billto, 0, '', "\n") . "\n\n";
if (is_object($$payment)) {
    $email_order .= EMAIL_TEXT_PAYMENT_METHOD . "\n" .
    EMAIL_SEPARATOR . "\n";
    $payment_class = $$payment;
    $email_order .= $payment_class->title . "\n\n";
    if ($payment_class->email_footer) {
        $email_order .= $payment_class->email_footer . "\n\n";
    }
}
tep_mail($order->customer['firstname'] . ' ' . $order->customer['lastname'], $order->customer['email_address'], EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
// send emails to other people
if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
    tep_mail('', SEND_EXTRA_ORDER_EMAILS_TO, EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
}
// load the after_process function from the payment modules
$payment_modules->after_process();

$cart->reset(true);

require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
<html>
<head><title>CPI Callback</title></head>
<body>CPI Callback complete</body>
</html>
<?php
ob_end_flush();
?>