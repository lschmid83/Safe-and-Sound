<div id="shippingAddress"><?php
 if (tep_session_is_registered('customer_id') && ONEPAGE_CHECKOUT_SHOW_ADDRESS_INPUT_FIELDS == 'False'){
	 if((int)$sendto<1)	 	$sendto = $billto;
	 echo tep_address_label($customer_id, $sendto, true, ' ', '<br>');
 }else{
	 if (tep_session_is_registered('onepage')){
		 $shippingAddress = $onepage['delivery'];
	 }
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
 <tr>
  <td><table cellpadding="0" cellspacing="0" border="0" width="100%">
   <tr>
	<td class="main" width="50%"><?php echo ENTRY_FIRST_NAME; ?></td>
	<td class="main" width="50%"><?php echo ENTRY_LAST_NAME; ?></td>
   </tr>
   <tr>
	<td class="main" width="50%"><?php echo tep_draw_input_field('shipping_firstname', $shippingAddress['firstname'], 'class="required" style="width:80%;float:left;"'); ?></td>
	<td class="main" width="50%"><?php echo tep_draw_input_field('shipping_lastname', $shippingAddress['lastname'], 'class="required" style="width:80%;float:left;"'); ?></td>
   </tr>
  </table></td>
 </tr>
<?php
  if (ACCOUNT_COMPANY == 'true') {
?>
 <tr>
  <td class="main"><?php echo ENTRY_COMPANY; ?></td>
 </tr>
 <tr>
  <td class="main"><?php echo tep_draw_input_field('shipping_company', '', 'style="width:80%;float:left;"'); ?></td>
 </tr>
<?php
  }
?>
 <tr>
  <td class="main"><?php echo ENTRY_COUNTRY; ?></td>
 </tr>
 <tr>
  <td class="main"><?php echo tep_get_country_list('shipping_country', (isset($shippingAddress['country_id']) ? $shippingAddress['country_id'] : ONEPAGE_DEFAULT_COUNTRY), 'class="required" style="width:80%;float:left;"'); ?></td>
 </tr>
 <tr>
  <td class="main"><?php echo ENTRY_STREET_ADDRESS; ?></td>
 </tr>
 <tr>
  <td class="main"><?php echo tep_draw_input_field('shipping_street_address', $shippingAddress['street_address'], 'class="required" style="width:80%;float:left;"'); ?></td>
 </tr>
<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
 <tr>
  <td class="main"><?php echo ENTRY_SUBURB; ?></td>
 </tr>
 <tr>
  <td class="main"><?php echo tep_draw_input_field('shipping_suburb', $shippingAddress['suburb'], 'style="width:80%;float:left;"'); ?></td>
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
	<td class="main" width="33%"><?php echo tep_draw_input_field('shipping_city', $shippingAddress['city'], 'class="required" style="width:80%;float:left;"'); ?></td>
<?php
  if (ACCOUNT_STATE == 'true') {
	$defaultCountry = (isset($billingAddress) && tep_not_null($shippingAddress['country_id']) ? $shippingAddress['country_id'] : ONEPAGE_DEFAULT_COUNTRY);
?>
	<td class="main" width="33%" id="stateCol_shipping"><?php echo $onePageCheckout->getAjaxStateField($defaultCountry, 'delivery');?></td>
<?php
  }
?>
	<td class="main" width="33%"><?php echo tep_draw_input_field('shipping_zipcode', $shippingAddress['postcode'], 'class="required" style="width:80%;float:left;"'); ?></td>
   </tr>
  </table></td>
 </tr>
</table><?php
 }
?></div>