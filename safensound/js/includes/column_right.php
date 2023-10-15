
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
	<?php require(DIR_WS_BOXES_AZ . 'best_sellers.php'); ?>
	<?php require(DIR_WS_BOXES_AZ . 'specials.php'); ?>

<tr>
	<td>
		<?php require(DIR_WS_BOXES_AZ . 'accessories.php'); ?>

	</td>
</tr>


<?php
$column_location = '';
?>

