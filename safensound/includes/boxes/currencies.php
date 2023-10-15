<?php
/*
  $Id: currencies_az01.php,v 1.0 2004/09/09  hpdl Exp $

  For osCommerce, Open Source E-Commerce Solutions
  Created by http://www.template-faq.com

  Copyright (c) 2004 template-faq.com, Inc

*/
?>
<!-- currencies //-->
<tr>
	<td>
<?php
if (isset($currencies) && is_object($currencies)) {

	reset($currencies->currencies);
	$currencies_array = array();
	while (list($key, $value) = each($currencies->currencies)) {
		$currencies_array[] = array('id' => $key, 'text' => $value['title']);
	}

	$hidden_get_variables = '';
	reset($HTTP_GET_VARS);
	while (list($key, $value) = each($HTTP_GET_VARS)) {
		if ( ($key != 'currency') && ($key != tep_session_name()) && ($key != 'x') && ($key != 'y') ) {
			$hidden_get_variables .= tep_draw_hidden_field($key, $value);
		}
	}

	$currencies_string = BOX_HEADING_CURRENCIES . '&nbsp;:&nbsp;';	
	$currencies_string .= tep_draw_form('currencies', tep_href_link(basename($PHP_SELF), '', $request_type, false), 'get');
	$currencies_string .= tep_draw_pull_down_menu('currency', $currencies_array, $currency, 'onChange="this.form.submit();"') . $hidden_get_variables . tep_hide_session_id();
	$currencies_string .= '</form>';
	
	$info_box_contents = array();
	$info_box_contents[] = array('align' => 'center',
								 'text' => $currencies_string);
	
	new infoBox($info_box_contents, $column_location, 0, 0);

}
?>
	</td>
</tr>
<!-- currencies_eof //-->