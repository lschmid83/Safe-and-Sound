
<?php
/*
  $Id: column_right.php, template: OS03C00286 v3.00 09/25/08  13:07:10 hpdl Exp $

  This file created for osCommerce v 2.2ms2 by AlgoZone, Inc
  http://www.algozone.com

*/
$column_location = 'Right';
?>



<?php
if (tep_session_is_registered('customer_id')) include(DIR_WS_BOXES_AZ . 'order_history.php');
?>


<?php
	if (isset($HTTP_GET_VARS['products_id'])) include(DIR_WS_BOXES_AZ . 'manufacturer_info.php');
?>


	<?php include(DIR_WS_BOXES . 'carinstallwizard.php'); ?>


	<tr>
	<td width="182px" align="center" style="padding-bottom:3px; table-layout:fixed"  ><a href="contact_us.php"> <img border=0 src="/safensound/catalog/images/showroom.jpg" width="182px" alt=""></a></td>
	</tr>

	<?php require(DIR_WS_BOXES_AZ . 'all_specials_slideshow.php'); ?>
	<?php require(DIR_WS_BOXES_AZ . 'best_sellers.php'); ?>
	<tr>
		<td><img src="images/epdq.png" width="180px" height="217px" border="0" alt=""></td>
	</tr>
	<tr>
		<td align="center"><a href="http://stores.shop.ebay.co.uk/safensound-croydon" target="_new"><img src="images/ebay.png" width="115px" height="108px" border="0" alt=""></a></td>
	</tr>

<?php
$column_location = '';
?>

