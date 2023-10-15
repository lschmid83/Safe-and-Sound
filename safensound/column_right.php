
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

<tr><td></td></tr>
<tr>
<td>
<center>
<br/>
<a href="https://www.securitymetrics.com/site_certificate.adp?s=212%2e84%2e187%2e67&amp;i=307923" target="_blank" >
<img src="http://www.securitymetrics.com/images/sm_tested_pci2.gif" alt="SecurityMetrics for PCI Compliance, QSA, IDS, Penetration Testing, Forensics, and Vulnerability Assessment" border="0"> </a> <br/><br/>

<a href="https://www.securitymetrics.com/site_certificate.adp?s=212%2e84%2e187%2e67&amp;i=307923" target="_blank" >
<img src="http://www.securitymetrics.com/images/identity_theft_protected.gif" alt="SecurityMetrics for PCI Compliance, QSA, IDS, Penetration Testing, Forensics, and Vulnerability Assessment" border="0"> </a> <br/><br/>

<a href="http://www.paypal.com" target="_blank" >
<img src="http://www.safensound.co.uk/catalog/images/paypal.png" alt="" border="0"> </a>


</center>

</td>

</tr>


<?php
$column_location = '';
?>

