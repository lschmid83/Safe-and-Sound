<?php
/*
	$Id: includes/modules/payment/epdq.php,v 1.08 2006/10/30 Aqua Technologies$

	osCommerce, Open Source E-Commerce Solutions
	http://www.oscommerce.com

	Released under the GNU General Public License

	Copyright © 2006 Aqua Technologies Limited
	http://www.aqnet.co.uk
	support@aqnet.co.uk


	The following script is the core epdq CPI module for
	osCommerce.

	Requires associated language file.

	v 1.08, 19.09.2007.  Modified to use CURL if available in preferance to sockets.  CURL code contributed by Philip Kay

	v1.09, 03.07.2008.  Fixed errors in sending across totals in different currencies to ePDQ, and billing/delivery details inc. USA states are now sent across correctly too.  Contributed by Sam G.
*/

define('EPDQ_VERSION', 'v1.09');

class EPDQ {
    var $code, $title, $description, $enabled, $epdq_allowed_currencies;
    // Creates encrypted epdqdata hidden field
    // N.B. CURL libraries required
    function EPDQ() {
        $this->code = 'ePDQ';
        $this->title = MODULE_PAYMENT_EPDQ_TEXT_TITLE ;
        $this->description = MODULE_PAYMENT_EPDQ_TEXT_DESCRIPTION . ' ' . EPDQ_VERSION
         . '<p>' . MODULE_PAYMENT_EPDQ_TEXT_MULTICURRENCY . '</p>';
        $this->enabled = MODULE_PAYMENT_EPDQ_STATUS;
        $this->sort_order = MODULE_PAYMENT_EPDQ_SORT_ORDER;
        $this->form_action_url = 'https://secure2.epdq.co.uk/cgi-bin/CcxBarclaysEpdq.e';
        $this->epdq_allowed_currencies = array ("AUD" => array("036", 2),
            "CAD" => array("124", 2),
            "CYR" => array("196", 2),
            "CZK" => array("203", 2),
            "DKK" => array("208", 2),
            "EUR" => array("978", 2),
            "HKD" => array("344", 2),
            "ISK" => array("352", 2),
            "INR" => array("356", 2),
            "JPY" => array("392", 0),
            "NZD" => array("554", 2),
            "NOK" => array("578", 2),
            "SGD" => array("702", 2),
            "SEK" => array("752", 2),
            "CHF" => array("756", 2),
            "GBP" => array("826", 2),
            "USD" => array("840", 2),
            "SAR" => array("682", 2),
            "ZAR" => array("710", 2),
            "THB" => array("764", 2),
            "AED" => array("784", 2)
            );
    }
    // Put any javascript validation required here.
    // Should be any for CPI as the Barclays servers handle all this
    function javascript_validation() {
        return false;
    }
    // Create form fields for user selection of this payment method
    function selection() {
        $selection = array('id' => $this->code,
            'module' => '<img src="http://www.safensound.co.uk/catalog/images/icons/epdq.gif" width="73px" height="25px" align="left" style="margin-right:7px;vertical-align:middle"">' . $this->title,
            'fields' => array(array('title' => '',
                    'field' => '')),
            );
        return $selection;
    }
    function pre_confirmation_check() {
        return false;
    }
    // Confirmation page code, validation, etc.
    function confirmation() {
        global $cartID, $cart_ePDQ_temp_id, $customer_id, $languages_id, $order, $order_total_modules;
        if (tep_session_is_registered('cartID')) {
            $insert_order = false;

            if (tep_session_is_registered('cart_ePDQ_temp_id')) {
                $order_id = substr($cart_ePDQ_temp_id, strpos($cart_ePDQ_temp_id, '-') + 1);

                $curr_check = tep_db_query("select currency from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");
                $curr = tep_db_fetch_array($curr_check);

                if (($curr['currency'] != $order->info['currency']) || ($cartID != substr($cart_ePDQ_temp_id, 0, strlen($cartID)))) {
                    $check_query = tep_db_query('select orders_id from ' . TABLE_ORDERS_STATUS_HISTORY . ' where orders_id = "' . (int)$order_id . '" limit 1');

                    if (tep_db_num_rows($check_query) < 1) {
                        tep_db_query('delete from ' . TABLE_ORDERS . ' where orders_id = "' . (int)$order_id . '"');
                        tep_db_query('delete from ' . TABLE_ORDERS_TOTAL . ' where orders_id = "' . (int)$order_id . '"');
                        tep_db_query('delete from ' . TABLE_ORDERS_STATUS_HISTORY . ' where orders_id = "' . (int)$order_id . '"');
                        tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS . ' where orders_id = "' . (int)$order_id . '"');
                        tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . ' where orders_id = "' . (int)$order_id . '"');
                        tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS_DOWNLOAD . ' where orders_id = "' . (int)$order_id . '"');
                    }

                    $insert_order = true;
                }
            } else {
                $insert_order = true;
            }

            if ($insert_order == true) {
                $order_totals = array();
                if (is_array($order_total_modules->modules)) {
                    reset($order_total_modules->modules);
                    while (list(, $value) = each($order_total_modules->modules)) {
                        $class = substr($value, 0, strrpos($value, '.'));
                        if ($GLOBALS[$class]->enabled) {
                            for ($i = 0, $n = sizeof($GLOBALS[$class]->output); $i < $n; $i++) {
                                if (tep_not_null($GLOBALS[$class]->output[$i]['title']) && tep_not_null($GLOBALS[$class]->output[$i]['text'])) {
                                    $order_totals[] = array('code' => $GLOBALS[$class]->code,
                                        'title' => $GLOBALS[$class]->output[$i]['title'],
                                        'text' => $GLOBALS[$class]->output[$i]['text'],
                                        'value' => $GLOBALS[$class]->output[$i]['value'],
                                        'sort_order' => $GLOBALS[$class]->sort_order);
                                }
                            }
                        }
                    }
                }

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
                    'orders_status' => MODULE_PAYMENT_EPDQ_PAYPENDING,
                    'currency' => $order->info['currency'],
                    'currency_value' => $order->info['currency_value']);

                if (tep_session_is_registered('noaccount')) {
                    $sql_data_array['purchase_without_account'] = '1';
                }

                tep_db_perform(TABLE_ORDERS, $sql_data_array);

                $insert_id = tep_db_insert_id();

                for ($i = 0, $n = sizeof($order_totals); $i < $n; $i++) {
                    $sql_data_array = array('orders_id' => $insert_id,
                        'title' => $order_totals[$i]['title'],
                        'text' => $order_totals[$i]['text'],
                        'value' => $order_totals[$i]['value'],
                        'class' => $order_totals[$i]['code'],
                        'sort_order' => $order_totals[$i]['sort_order']);

                    tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
                }

                $sql_data_array = array('orders_id' => $insert_id,
                    'orders_status_id' => MODULE_PAYMENT_EPDQ_PAYPENDING,
                    'date_added' => 'now()',
                    'customer_notified' => '0',
                    'comments' => $order->info['comments']);
                tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);

                for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
                    $sql_data_array = array('orders_id' => $insert_id,
                        'products_id' => tep_get_prid($order->products[$i]['id']),
                        'products_model' => $order->products[$i]['model'],
                        'products_name' => $order->products[$i]['name'],
                        'products_price' => $order->products[$i]['price'],
                        'final_price' => $order->products[$i]['final_price'],
                        'products_tax' => $order->products[$i]['tax'],
                        'products_quantity' => $order->products[$i]['qty']);

                    tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array);

                    $order_products_id = tep_db_insert_id();

                    $attributes_exist = '0';
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

                            $sql_data_array = array('orders_id' => $insert_id,
                                'orders_products_id' => $order_products_id,
                                'products_options' => $attributes_values['products_options_name'],
                                'products_options_values' => $attributes_values['products_options_values_name'],
                                'options_values_price' => $attributes_values['options_values_price'],
                                'price_prefix' => $attributes_values['price_prefix']);

                            tep_db_perform(TABLE_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);

                            if ((DOWNLOAD_ENABLED == 'true') && isset($attributes_values['products_attributes_filename']) && tep_not_null($attributes_values['products_attributes_filename'])) {
                                $sql_data_array = array('orders_id' => $insert_id,
                                    'orders_products_id' => $order_products_id,
                                    'orders_products_filename' => $attributes_values['products_attributes_filename'],
                                    'download_maxdays' => $attributes_values['products_attributes_maxdays'],
                                    'download_count' => $attributes_values['products_attributes_maxcount']);

                                tep_db_perform(TABLE_ORDERS_PRODUCTS_DOWNLOAD, $sql_data_array);
                            }
                        }
                    }
                }

                tep_session_register('cart_ePDQ_temp_id');
                $cart_ePDQ_temp_id = $cartID . '-' . $insert_id;
            }
        }
        return false;
    }

    function PostData($requesthost, $requestdocument, $requestdata) {
        $requestbodyarray = array();
        $requestbody = '';

        if (is_array($requestdata)) {
            foreach($requestdata as $key => $value) {
                $requestbodyarray[] = urlencode($key) . "=" . urlencode($value);
            }
            $requestbody = implode("&", $requestbodyarray);
        }

		if(function_exists(curl_init)){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://" . $requesthost . $requestdocument);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $requestbody);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            $responsedata = curl_exec($ch);
            curl_close($ch);

            $EncryptedResult = trim($responsedata);
            $EncryptedResult = strstr($responsedata, "value=");
            $EncryptedResult = substr(strstr($EncryptedResult, "\""), 1, strlen(strstr($EncryptedResult, "\"")) - strlen(strrchr($EncryptedResult, "\"")) - 1);
            $returnarray["EncryptedResult"] = $EncryptedResult;
            return $returnarray;
		} else {
	        $requestheader = "POST $requestdocument HTTP/1.0\r\n";
	        $requestheader .= "Host: $requesthost\r\n";
	        $requestheader .= "Content-type: application/x-www-form-urlencoded\r\n";
	        $requestheader .= "Content-length: " . strlen($requestbody) . "\r\n";
	        $requestheader .= "\r\n";

	        $connection = fsockopen("$requesthost", 80);
	        fputs($connection, $requestheader . $requestbody);
	        $responsedata = "";
	        while (!feof($connection)) $responsedata .= fgets($connection, 1024);
	        fclose($connection);

	        $EncryptedResult = trim($responsedata);
	        $EncryptedResult = strstr($responsedata, "value=");
	        $EncryptedResult = substr(strstr($EncryptedResult, "\""), 1, strlen(strstr($EncryptedResult, "\"")) - strlen(strrchr($EncryptedResult, "\"")) - 1);

	        $returnarray = explode("\n", str_replace("\r", "", $responsedata));

	        if (preg_match("|^HTTP/\S+ (\d+) |i", trim($returnarray[0]), $matches)) {
	            $returnarray["EncryptedResult"] = $EncryptedResult;
	            return $returnarray;
	        } else {
	            return false;
	        }
        }
    }
    // Get the CPI encrypted data field and draw other hidden fields
    function process_button() {
        global $HTTP_POST_VARS, $order, $currencies, $customer_id, $cart_ePDQ_temp_id;

        $grandtotal = $order->info['total'];
        $temp_order_id = $customer_id . "-" . date("YmdHis");
        $customer_email = tep_db_query("select customers_email_address from " . TABLE_CUSTOMERS . " where customers_id = '" . $customer_id . "'");
        $customer_email_values = tep_db_fetch_array($customer_email);

        /**
         * Multi currency patch
         * ====================
         * 09.08.2006, SGS
         * With multi currency CPI requires a different store with its own
         * clientid, username and password along with it associatd currency
         * code. N.B. username not used here, just for cpi admin panel
         */
        if (strstr(MODULE_PAYMENT_EPDQ_STORE_ID, ':') === false &&
                strstr(MODULE_PAYMENT_EPDQ_PWD, ':') === false &&
                strstr(MODULE_PAYMENT_EPDQ_CURRENCY, ':') === false) {
            // No colon seperator means just one clientid => single currency mode
            $epdq_merchant_id = MODULE_PAYMENT_EPDQ_STORE_ID;
            $epdq_pwd = MODULE_PAYMENT_EPDQ_PWD;
            if (array_key_exists(MODULE_PAYMENT_EPDQ_CURRENCY, $this->epdq_allowed_currencies) == true) {
                $epdq_currency = $this->epdq_allowed_currencies[MODULE_PAYMENT_EPDQ_CURRENCY][0]; // Get 3 digit iso code required by epdq
                $epdq_decimals = $this->epdq_allowed_currencies[MODULE_PAYMENT_EPDQ_CURRENCY][1];
            } else {
                return '<p style="color:red;font-weight:bold">THE EPDQ PAYMENT MODULE IS INCORRECTLY CONFIGURED<br /><br />The payment currency is not a valid currency code for ePDQ</p>';
            }
        } else {
            /**
             * We have colon seperated data => multi currency mode
             * Check we have same number in each of the three configs
             */
            $EPDQ_MERCHANT_IDS = explode(':', MODULE_PAYMENT_EPDQ_STORE_ID);
            $EPDQ_PWDS = explode(':', MODULE_PAYMENT_EPDQ_PWD);
            $EPDQ_CURRENCIES = explode(':', MODULE_PAYMENT_EPDQ_CURRENCY);
            if (count($EPDQ_MERCHANT_IDS) == count($EPDQ_PWDS) && count($EPDQ_MERCHANT_IDS) == count($EPDQ_CURRENCIES)) {
                // $order->info['currency']
                $order->info['currency'] = strtoupper($order->info['currency']);
                if (($currency_index = array_search($order->info['currency'], $EPDQ_CURRENCIES)) !== false &&
                        array_key_exists($order->info['currency'], $this->epdq_allowed_currencies) == true) {
                    $epdq_merchant_id = $EPDQ_MERCHANT_IDS[$currency_index];
                    $epdq_pwd = $EPDQ_PWDS[$currency_index];
                    $epdq_currency = $this->epdq_allowed_currencies[$order->info['currency']][0]; // Get 3 digit iso code required by epdq
                    $epdq_decimals = $this->epdq_allowed_currencies[$order->info['currency']][1];
                } else {
                    // Selected currency is not configured for payment so process in default currency
                    if (($currency_index = array_search(strtoupper(DEFAULT_CURRENCY), $EPDQ_CURRENCIES)) !== false &&
                            array_key_exists(strtoupper(DEFAULT_CURRENCY), $this->epdq_allowed_currencies) == true) {
                        $epdq_merchant_id = $EPDQ_MERCHANT_IDS[$currency_index];
                        $epdq_pwd = $EPDQ_PWDS[$currency_index];
                        $epdq_currency = $this->epdq_allowed_currencies[strtoupper(DEFAULT_CURRENCY)][0]; // Get 3 digit iso code required by epdq
                        $epdq_decimals = $this->epdq_allowed_currencies[strtoupper(DEFAULT_CURRENCY)][1];
                    } else {
                        // Fail, default currency not valid for payment
                        return '<p style="color:red;font-weight:bold">THE EPDQ PAYMENT MODULE IS INCORRECTLY CONFIGURED<br /><br />The default currency is not a valid currency code for ePDQ</p>';
                    }
                }
            } else {
                // Fail, invalid configuration
                return '<p style="color:red;font-weight:bold">THE EPDQ PAYMENT MODULE IS INCORRECTLY CONFIGURED<br /><br />The number of colon seperated entries for currency, client id and passphrase are different</p>';
            }
        }
        $postparams["clientid"] = $epdq_merchant_id;
        $postparams["password"] = $epdq_pwd;
        $postparams['oid'] = substr($cart_ePDQ_temp_id, strpos($cart_ePDQ_temp_id, '-') + 1);
        $postparams["chargetype"] = MODULE_PAYMENT_EPDQ_CHARGE_TYPE;
		// Graith mod 25-Mar-2010
        $postparams["mandatecsc"] = 1;
		
        $postparams["currencycode"] = $epdq_currency;

		// ***********************************************
		// Start of v1.09 modification
		// Modified so that the correct total is passed to ePDQ for different currencies

		$postparams["total"] = number_format(($order->info['total'] * $order->info['currency_value']), $epdq_decimals, '.', '');

		// End of v1.09 modification
		// ***********************************************


        $oid = (int) $postparams['oid'];

        tep_db_query("delete from epdq_session WHERE oid = $oid");

        $val = serialize($_SESSION);

        $qry = "insert into epdq_session values ($oid,'" . mysql_real_escape_string($val) . "')";
        tep_db_query($qry);

        $postresponse = $this->PostData("secure2.epdq.co.uk", "/cgi-bin/CcxBarclaysEpdqEncTool.e", $postparams);
        if ($postresponse["response"] == 200)
            if ($postresponse["bodylines"] == 1)
                $epdqencodedstring = $postresponse["body"];

			// ***********************************************
			// Start of v1.09 modification
			// Modified so that the billing and delivery address details are passed over to ePDQ correctly, including state info for USA

			// Check the BILLING address
			$usa_billing = $order->billing['country']['iso_code_2'];
			if ($usa_billing == "US"){
				// Billing address is in the USA
				$process_button_string = tep_draw_hidden_field('epdqdata', $postresponse["EncryptedResult"]) . "\n"
				. tep_draw_hidden_field('returnurl', MODULE_PAYMENT_EPDQ_RETURNURL) . "\n"
				. tep_draw_hidden_field('merchantdisplayname', MODULE_PAYMENT_EPDQ_DISPLAY_NAME) . "\n"
				. tep_draw_hidden_field('supportedcardtypes', MODULE_PAYMENT_EPDQ_SUPPORTEDCARDS) . "\n"
				. tep_draw_hidden_field('email', $order->customer['email_address']) . "\n"

				. tep_draw_hidden_field('baddr1', $order->billing['street_address']) . "\n"
				. tep_draw_hidden_field('baddr2', $order->billing['suburb']) . "\n"
				. tep_draw_hidden_field('bcity', $order->billing['city']) . "\n"
				. tep_draw_hidden_field('bstate', $order->billing['state']) . "\n"
				. tep_draw_hidden_field('bpostalcode', $order->billing['postcode']) . "\n"
				. tep_draw_hidden_field('bcountry', $order->billing['country']['iso_code_2']) . "\n"
				. tep_draw_hidden_field('btelephonenumber', $order->customer['telephone']) . "\n" ;
			} else {
				// Billing address is outside of the USA
				$process_button_string = tep_draw_hidden_field('epdqdata', $postresponse["EncryptedResult"]) . "\n"
				. tep_draw_hidden_field('returnurl', MODULE_PAYMENT_EPDQ_RETURNURL) . "\n"
				. tep_draw_hidden_field('merchantdisplayname', MODULE_PAYMENT_EPDQ_DISPLAY_NAME) . "\n"
				. tep_draw_hidden_field('supportedcardtypes', MODULE_PAYMENT_EPDQ_SUPPORTEDCARDS) . "\n"
				. tep_draw_hidden_field('email', $order->customer['email_address']) . "\n"

				. tep_draw_hidden_field('baddr1', $order->billing['street_address']) . "\n"
				. tep_draw_hidden_field('baddr2', $order->billing['suburb']) . "\n"
				. tep_draw_hidden_field('bcity', $order->billing['city']) . "\n"
				. tep_draw_hidden_field('bcountyprovince', $order->billing['state']) . "\n"
				. tep_draw_hidden_field('bpostalcode', $order->billing['postcode']) . "\n"
				. tep_draw_hidden_field('bcountry', $order->billing['country']['iso_code_2']) . "\n"
				. tep_draw_hidden_field('btelephonenumber', $order->customer['telephone']) . "\n" ;
			}

			// Check the DELIVERY address
			$usa_shipping = $order->delivery['country']['iso_code_2'];
			if ($usa_shipping == "US"){
				// Shipping address is in the USA
				$process_button_string .= tep_draw_hidden_field('saddr1', $order->delivery['street_address']) . "\n"
				. tep_draw_hidden_field('saddr2', $order->delivery['suburb']) . "\n"
				. tep_draw_hidden_field('scity', $order->delivery['city']) . "\n"
				. tep_draw_hidden_field('sstate', $order->delivery['state']) . "\n"
				. tep_draw_hidden_field('spostalcode', $order->delivery['postcode']) . "\n"
				. tep_draw_hidden_field('scountry', $order->delivery['country']['iso_code_2']) . "\n" ;
			} else {
				// Shipping address is outside of the USA
				$process_button_string .= tep_draw_hidden_field('saddr1', $order->delivery['street_address']) . "\n"
				. tep_draw_hidden_field('saddr2', $order->delivery['suburb']) . "\n"
				. tep_draw_hidden_field('scity', $order->delivery['city']) . "\n"
				. tep_draw_hidden_field('scountyprovince', $order->delivery['state']) . "\n"
				. tep_draw_hidden_field('spostalcode', $order->delivery['postcode']) . "\n"
				. tep_draw_hidden_field('scountry', $order->delivery['country']['iso_code_2']) . "\n" ;
			}
			// End of v1.09 modification
			// ***********************************************

            if (MODULE_PAYMENT_EPDQ_DISPLAY_TEXTCOLOR != '') {
                $process_button_string .= tep_draw_hidden_field('cpi_textcolor', MODULE_PAYMENT_EPDQ_DISPLAY_TEXTCOLOR) . "\n";
            }

            if (MODULE_PAYMENT_EPDQ_DISPLAY_BGCOLOR != '') {
                $process_button_string .= tep_draw_hidden_field('cpi_bgcolor', MODULE_PAYMENT_EPDQ_DISPLAY_BGCOLOR) . "\n";
            }

            if (MODULE_PAYMENT_EPDQ_DISPLAY_LOGO != '') {
                $process_button_string .= tep_draw_hidden_field('cpi_logo', MODULE_PAYMENT_EPDQ_DISPLAY_LOGO) . "\n";
            }
            return $process_button_string;
        }
        // Exit the checkout_process script if card txn failure and go back to cart
        // Clean session and cart if success, leave script early and goto success page
        function before_process() {
            global $cart, $order;
            $oid = $_GET['oid'];
            $rs = tep_db_query("select orders_status from " . TABLE_ORDERS . " where orders_id = '" . $oid . "'");
            if (mysql_num_rows($rs)) {
                $order_status = mysql_fetch_assoc($rs);
                $order_status_id = $order_status['orders_status'];
            }
            if ($order_status_id == MODULE_PAYMENT_EPDQ_PAYSUCCESS) {
                $cart->reset(true);
                tep_session_unregister('sendto');
                tep_session_unregister('billto');
                tep_session_unregister('shipping');
                tep_session_unregister('payment');
                tep_session_unregister('comments');
                tep_session_unregister('cart_ePDQ_temp_id');
                tep_redirect(tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL'));
            } else {
                tep_session_unregister('cart_ePDQ_temp_id');
                tep_redirect(tep_href_link(FILENAME_SHOPPING_CART, '', 'SSL'));
            }
            exit();
            return false;
        }

        function after_process() {
            return false;
        }

        function output_error() {
            global $HTTP_GET_VARS;

            if ($HTTP_GET_VARS['cc_errormsg']) {
                $cc_errormsg = urldecode($HTTP_GET_VARS['cc_errormsg']);
            } elseif ($HTTP_GET_VARS['cc_val']) {
                $cc_errormsg = stripslashes($HTTP_GET_VARS['cc_val']);
            }

            $output_error_string = '<table border="0" cellspacing="0" cellpadding="0" width="100%">' . "\n" . '  <tr>' . "\n" . '    <td class="main">' . MODULE_PAYMENT_EPDQ_ERROR_MESSAGE . '<br><font color="#ff0000"><b>' . $cc_errormsg . '<br>';
            if ($HTTP_GET_VARS['cc_additional']) {
                $output_error_string .= '(' . urldecode($HTTP_GET_VARS['cc_additional']) . ')<br>';
            }
            $output_error_string .= MODULE_PAYMENT_EPDQ_ERROR_MESSAGE2 . '</b></font></td>' . "\n" . '  </tr>' . "\n" . '</table>' . "\n";

            return $output_error_string;
        }

        function check() {
            if (!isset($this->check)) {
                $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_EPDQ_STATUS'");
                $this->check = tep_db_num_rows($check_query);
            }
            return $this->check;
        }

        function install() {
            tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Allow EPDQ', 'MODULE_PAYMENT_EPDQ_STATUS', 'True', 'Do you want to accept EPDQ payments?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());");
            tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Store ID', 'MODULE_PAYMENT_EPDQ_STORE_ID',         '9999', 'Your ePDQ Client ID. ePDQ supply this when they set you up.<br />[colon seperated for multi currency]', '6', '2', now())");
            tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Merchant Pass Phrase', 'MODULE_PAYMENT_EPDQ_PWD',        '**pwd**', 'Your ePDQ passphrase. Configure this in the CPI admin panel - see below.<br />[colon seperated for multi currency]', '6', '3', now())");
            tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('EPDQ Currency', 'MODULE_PAYMENT_EPDQ_CURRENCY',          'GBP', 'The currency ePDQ should charge in.<br />See ePDQ documentation for supported currencies.<br />[colon seperated for multi currency]', '6', '4', now())");
            tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('EPDQ Charge Type', 'MODULE_PAYMENT_EPDQ_CHARGE_TYPE',    'Auth', 'The charge type.', '6', '5', now())");
            tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('EPDQ ReturnURL', 'MODULE_PAYMENT_EPDQ_RETURNURL',        'http://www.yoursite.co.uk/checkout_process.php', 'Return URL page', '6', '6', now())");
            tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('EPDQ Display Name', 'MODULE_PAYMENT_EPDQ_DISPLAY_NAME',  'Your company name', 'This is the company name displayed on the ePDQ payment collection page', '6', '7', now())");
            tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('EPDQ Display Logo', 'MODULE_PAYMENT_EPDQ_DISPLAY_LOGO',  'http://www.yoursite.co.uk/logo.gif', 'Company logo to be displayed on the payment page (500px x 100px)', '6', '8', now())");
            tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('EPDQ Display Background Colour', 'MODULE_PAYMENT_EPDQ_DISPLAY_BGCOLOR',  '', 'Background colour for the payment page (either hexadecimal or name of colour)', '6', '9', now())");
            tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('EPDQ Display Text Colour', 'MODULE_PAYMENT_EPDQ_DISPLAY_TEXTCOLOR',  '', 'Text colour for the payment page (either hexadecimal or name of colour)', '6', '10', now())");
            tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('EPDQ Supported Cards', 'MODULE_PAYMENT_EPDQ_SUPPORTEDCARDS',  '127', 'Supported card types (All - 127, All except Amex - 125, Visa & Electron - 65)', '6', '11', now())");
            tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('EPDQ Pay Pending', 'MODULE_PAYMENT_EPDQ_PAYPENDING', '0', 'Select the status for orders that have clicked through to ePDQ', '6', '12', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
            tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('EPDQ Pay Success', 'MODULE_PAYMENT_EPDQ_PAYSUCCESS', '0', 'Select the status of orders for successful payment', '6', '13', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
            tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('EPDQ Pay Failed', 'MODULE_PAYMENT_EPDQ_PAYFAILED', '0', 'Select the status of orders for failed payment', '6', '14', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
            tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order of display', 'MODULE_PAYMENT_EPDQ_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
            $this->create_tables();
        }

        function remove() {
            tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_EPDQ_STATUS'");
            tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_EPDQ_STORE_ID'");
            tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_EPDQ_CURRENCY'");
            tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_EPDQ_RETURNURL'");
            tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_EPDQ_PWD'");
            tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_EPDQ_CHARGE_TYPE'");
            tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_EPDQ_DISPLAY_NAME'");
            tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_EPDQ_DISPLAY_LOGO'");
            tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_EPDQ_DISPLAY_BGCOLOR'");
            tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_EPDQ_DISPLAY_TEXTCOLOR'");
            tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_EPDQ_SUPPORTEDCARDS'");
            tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_EPDQ_PAYPENDING'");
            tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_EPDQ_PAYSUCCESS'");
            tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_EPDQ_PAYFAILED'");
            tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_EPDQ_SORT_ORDER'");
            $this->drop_tables();
        }

        function create_tables() {
            $this->drop_tables();
            tep_db_query("CREATE TABLE epdq_session (`oid` int(11) default NULL, `session` text) TYPE=MyISAM ROW_FORMAT=DYNAMIC");
            tep_db_query("CREATE TABLE epdq_txnlog (`oid` varchar(30) NOT NULL default '',`txn_status` varchar(30) default NULL,`txn_time` datetime default NULL) TYPE=MyISAM");
        }

        function drop_tables() {
            tep_db_query("DROP TABLE IF EXISTS epdq_session");
            tep_db_query("DROP TABLE IF EXISTS epdq_txnlog");
        }

        function keys() {
            $keys = array('MODULE_PAYMENT_EPDQ_STATUS',
                'MODULE_PAYMENT_EPDQ_DISPLAY_NAME',
                'MODULE_PAYMENT_EPDQ_DISPLAY_LOGO',
                'MODULE_PAYMENT_EPDQ_DISPLAY_BGCOLOR',
                'MODULE_PAYMENT_EPDQ_DISPLAY_TEXTCOLOR',
                'MODULE_PAYMENT_EPDQ_STORE_ID',
                'MODULE_PAYMENT_EPDQ_CURRENCY',
                'MODULE_PAYMENT_EPDQ_PWD',
                'MODULE_PAYMENT_EPDQ_RETURNURL',
                'MODULE_PAYMENT_EPDQ_SUPPORTEDCARDS',
                'MODULE_PAYMENT_EPDQ_PAYPENDING',
                'MODULE_PAYMENT_EPDQ_PAYSUCCESS',
                'MODULE_PAYMENT_EPDQ_PAYFAILED',
                'MODULE_PAYMENT_EPDQ_CHARGE_TYPE',
                'MODULE_PAYMENT_EPDQ_SORT_ORDER');
            return $keys;
        }
    }

    ?>