<div id="billingAddress"><?php
 if (tep_session_is_registered('customer_id') && ONEPAGE_CHECKOUT_SHOW_ADDRESS_INPUT_FIELDS == 'False'){
	 echo tep_address_label($customer_id, $billto, true, ' ', '<br>');
 }else{
	 if (tep_session_is_registered('onepage')){
		 $billingAddress = $onepage['billing'];
		 $customerAddress = $onepage['customer'];
	 }
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if (ACCOUNT_GENDER == 'true' && !tep_session_is_registered('customer_id')) {
	  $gender = $billingAddress['entry_gender'];
	if (isset($gender)) {
	  $male = ($gender == 'm') ? true : false;
	  $female = ($gender == 'f') ? true : false;
	} else {
	  $male = false;
	  $female = false;
	}
?>
 <tr>
  <td class="main"><?php echo ENTRY_GENDER; ?><br><?php echo tep_draw_radio_field('billing_gender', 'm', $male) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('billing_gender', 'f', $female) . '&nbsp;&nbsp;' . FEMALE; ?></td>
 </tr>
<?php
  }
?>
 <tr>
  <td><table cellpadding="0" cellspacing="0" border="0" width="100%">
   <tr>
	<td class="main" width="50%"><?php echo ENTRY_FIRST_NAME; ?></td>
	<td class="main" width="50%"><?php echo ENTRY_LAST_NAME; ?></td>
   </tr>
   <tr>
	<td class="main" width="50%"><?php echo tep_draw_input_field('billing_firstname', (isset($billingAddress) ? $billingAddress['firstname'] : ''), 'class="required" style="width:75%;float:left;"'); ?></td>
	<td class="main" width="50%"><?php echo tep_draw_input_field('billing_lastname', (isset($billingAddress) ? $billingAddress['lastname'] : ''), 'class="required" style="width:75%;float:left;"'); ?></td>
   </tr>
  </table></td>
 </tr>
<?php
  if (ACCOUNT_DOB == 'true' && !tep_session_is_registered('customer_id')) {
?>
 <tr>
  <td class="main"><?php echo ENTRY_DATE_OF_BIRTH; ?></td>
 </tr>
 <tr>
  <td class="main"><?php echo tep_draw_input_field('billing_dob', (isset($customerAddress) ? $customerAddress['dob'] : ''), 'style="width:80%;float:left;"'); ?></td>
 </tr>
<?php
  }

  if (!tep_session_is_registered('customer_id')){
?>
 <tr id="newAccountEmail">
  <td class="main"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
 </tr>
 <tr>
  <td class="main"><?php echo tep_draw_input_field('billing_email_address', (isset($customerAddress) ? $customerAddress['email_address'] : ''), 'class="required" style="width:80%;float:left;"'); ?></td>
 </tr>
<?php
  }
  if (ACCOUNT_COMPANY == 'true') {
?>
 <tr>
  <td class="main"><?php echo ENTRY_COMPANY; ?></td>
 </tr>
 <tr>
  <td class="main"><?php echo tep_draw_input_field('billing_company', (isset($billingAddress) ? $billingAddress['company'] : ''), 'style="width:80%;float:left;"'); ?></td>
 </tr>
<?php
  }
?>

 <tr>
  <td class="main"><?php echo ENTRY_STREET_ADDRESS; ?></td>
 </tr>
 <tr>
  <td class="main"><?php echo tep_draw_input_field('billing_street_address', (isset($billingAddress) ? $billingAddress['street_address'] : ''), 'class="required" style="width:80%;float:left;"'); ?></td>
 </tr>
<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
 <tr>
  <td class="main"><?php echo ENTRY_SUBURB; ?></td>
 </tr>
 <tr>
  <td class="main"><?php echo tep_draw_input_field('billing_suburb', (isset($billingAddress) ? $billingAddress['suburb'] : ''), 'style="width:80%;float:left"'); ?></td>
 </tr>
<?php
  }
?>
 <tr>
  <td><table cellpadding="0" cellspacing="0" border="0" width="100%">
   <tr>
	<td class="main" width="33%"><?php echo ENTRY_CITY; ?></td>
<?php
  if (ACCOUNT_STATE == 'true') {
?>
	<td class="main" width="33%"><?php echo ENTRY_STATE; ?></td>
<?php
  }
?>
	<td class="main" width="33%"><?php echo ENTRY_POST_CODE; ?></td>
   </tr>
   <tr>
	<td class="main" width="33%"><?php echo tep_draw_input_field('billing_city', (isset($billingAddress) ? $billingAddress['city'] : ''), 'class="required" style="width:80%;float:left;"'); ?></td>
<?php
  if (ACCOUNT_STATE == 'true') {
	$defaultCountry = (isset($billingAddress) && tep_not_null($billingAddress['country_id']) ? $billingAddress['country_id'] : ONEPAGE_DEFAULT_COUNTRY);
?>
	<td class="main" width="33%" id="stateCol_billing"><?php echo $onePageCheckout->getAjaxStateField($defaultCountry);?><div <? if(tep_not_null($billingAddress['zone_id']) || tep_not_null($billingAddress['state'])){ ?>class= "success_icon ui-icon-green ui-icon-circle-check" <? }else{?> class="required_icon ui-icon-red ui-icon-gear" <?} ?> style="margin-left: 3px; margin-top: 1px; float: left;" title="Required" /></div></td>
<?php
  }
?>
	<td class="main" width="33%"><?php echo tep_draw_input_field('billing_zipcode', (isset($billingAddress) ? $billingAddress['postcode'] : ''), 'class="required" style="width:80%;float:left;"'); ?></td>
   </tr>
  </table></td>
 </tr>
<?php if(!tep_session_is_registered('customer_id')){ ?>
 <tr>
  <td class="main"><?php echo ENTRY_TELEPHONE; ?><br><?php echo tep_draw_input_field('billing_telephone', (isset($customerAddress) ? $customerAddress['telephone'] : ''), 'style="width:80%;float:left;"'); ?></td>
 </tr>
 <tr>
  <td><table cellpadding="0" cellspacing="0" border="0" width="100%">
<?php if (ONEPAGE_ACCOUNT_CREATE != 'required'){ ?>
   <tr>
	<td colspan="2" class="main"><br>If you would like to create an account please enter a password below</td>
   </tr>
<?php } ?>
   <tr>
	<td class="main"><?php echo ENTRY_PASSWORD; ?></td>
	<td class="main"><?php echo ENTRY_PASSWORD_CONFIRMATION; ?></td>
   </tr>
   <tr>
	<td class="main"><?php echo tep_draw_password_field('password', '', 'autocomplete="off" ' . (ONEPAGE_ACCOUNT_CREATE == 'required' ? 'class="required" maxlength="40" ' : 'maxlength="40" ') . 'style="float:left;"'); ?></td>
	<td class="main"><?php echo tep_draw_password_field('confirmation', '', 'autocomplete="off" ' . (ONEPAGE_ACCOUNT_CREATE == 'required' ? 'class="required" ' : '') . 'maxlength="40" style="float:left;"'); ?></td>
   </tr>
   <tr>
	<td class="main" colspan="2"><div id="pstrength_password"></div></td>
   </tr>
  </table></td>
 </tr>
 <tr>
  <td class="main"><?php echo ENTRY_NEWSLETTER; ?><br><?php echo tep_draw_checkbox_field('billing_newsletter', '1', (isset($customerAddress) && $customerAddress['newsletter'] == '1' ? true : false)); ?></td>
 </tr>
<?php } ?>
</table>
<?php
 }
?></div>