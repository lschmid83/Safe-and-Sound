<?php
/*
  $Id: conditions.php,v 1.22 2003/06/05 23:26:22 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CONDITIONS);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_CONDITIONS));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo META_TITLE; ?></title>
<meta name="description" content="<?php echo META_DESCRIPTION; ?>" >
<meta name="keywords" content="<?php echo META_KEYWORDS; ?>" >
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="maincont_tb">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top" class="maincont_left_td"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0" class="leftbar_tb">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top" class="maincont_mid_td">
    <?php require(DIR_WS_INCLUDES . 'sub_header.php'); ?>
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">Terms & Conditions</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main">
                    <p>safensound.co.uk does not sell products on a trial basis. Customers are strongly   advised to check suitability and specifications of products before ordering.</p>
                    <h1>Shortages and damaged items.</h1>
                    <ul>
                      <li>safensound.co.uk does not accept any liability for shortages and damages to   supplied or delivered goods unless the customer notifies safensound.co.uk by phone   of the shortages or damages within 24 hours of supply or delivery followed by a   written notification within 3 days in order to process a claim with the   manufacturer or delivery company. You must retain all the original packaging for   inspection by the courier or ourselves. Help and advice will be available in the   first instance by using the Contact Us link, which is located at the top and   bottom of all safensound.co.uk web pages.</li>
                      <li>In the case of mail order, &quot;Delivery&quot; is deemed to take place when the goods   are delivered to the customer's nominated address, whereupon the risks of loss,   breakage and all damage shall pass to the customer.</li>
                      <li>safensound.co.uk cannot be held responsible for any consequential losses, costs   or damages involved with shortages and damages to items however; safensound.co.uk   shall endeavour to resolve any problem with expediency.</li>
                    </ul>
                    <h1>Return of faulty goods under warranty</h1>
                    <p>safensound.co.uk is committed to providing our customers with the highest   quality products and service. However, on rare occasions products may be found   to be faulty or defective and in keeping with our commitment to providing you   with excellent service, we offer the following returns facilities:</p>
                    <ul>
                      <li>The warranty shall not apply if the goods have been tampered with, altered   or damaged in any way by the customer; this includes connection cables and   looms.</li>
                      <li>Unless otherwise stated in the manufacturer's documentation, all goods   supplied or delivered to UK mainland addresses carry a 12-month manufacturer's   warranty.</li>
                      <li>Prior to taking any action we would ask you to contact our returns   department, using the Contact Us link which can be found at the top and bottom   of all safensound.co.uk web pages. We may be able to resolve the problem or give   you advice on the appropriate action to take.</li>
                      <li>If the problem cannot be resolved we would suggest that you return the goods   to us or the original manufacturer depending on your specific circumstances.   There may be occasions due to the nature of the fault when it could be necessary   to return the product to the original manufacturer. The manufacturer's   turnaround time will be dependant on many factors.</li>
                      <li>On receipt of the returned product we will test it to identify the fault you   have notified to us. Provided that the goods are found to be faulty under the   terms of the manufacturers warranty there will be no charge for rectification   and in the case of mail order customers safensound.co.uk will pay for the return of   the goods. If following the testing process the product is found to be in good   working order without defect we will return the product to you, the carriage   cost of this return will be your responsibility.</li>
                    </ul>
                    <p>Mail order customers will be responsible for the organisation and   cost of returning the goods to us. The following may be helpful:</p>
                    <ul>
                      <li>Please ensure the equipment is adequately packed and protected to prevent   damage in transit.</li>
                      <li>Do not forget to include a copy of your proof of purchase and also a full   description of the fault. Please supply a daytime contact telephone number and   return address.</li>
                      <li>Contact a safensound.co.uk representative for the relevant returns   address.</li>
                      <li>Our recommended method of return would be via Parcel Force Standard Delivery   Service. This service is available through your local post office and we would   recommend that you take out adequate compensation to cover the value of the   goods.</li>
                    </ul>
                    <p>safensound.co.uk cannot accept liability for packages damaged or lost during   transit. It is the customer's responsibility to adequately pack and protect the   goods and to ensure that the parcel is sufficiently insured.</p>
                    <h1>Refunds</h1>
                    <p>Purchases made from our online store are not sold on a &quot;Trial basis&quot;.   Customers are strongly advised to check the suitability and specification of   products prior to purchasing. In the event that safensound.co.uk, at its   discretion, may agree to accept the goods back the following conditions will   apply:</p>
                    <ul>
                      <li>a) The goods are to be returned unopened and in perfect resalable   condition.</li>
                      <li>b) The goods are to be returned within 7 days of purchase.</li>
                      <li>If points a) and b) are met then a handling fee of 15% or £20, whichever is   the greater will be deducted from the refunded amount. Alternatively the   customer may choose to exchange the returned goods for credit against   alternative products to the equivalent value or greater without a handling   fee.</li>
                      <li>Contracts for the purchase of goods by a customer not acting in the course   of a business and made over the telephone or through the safensound.co.uk website   are, subject to The Consumer Protection (Distance Selling) Regulations 2000,   with the exception of Software/mapping and Music CD/DVD products. These items   are not refundable due to their nature. Consumers may cancel goods purchased   from safensound.co.uk by sending a notice of cancellation by e-mail to:   sales@safensound.co.uk. The notice of cancellation must be delivered within seven   working days of the date of delivery of goods to the nominated address. The   following conditions apply:</li>
                      <li>a) The customer will be responsible for all delivery costs, including the   organisation and cost of returning the goods making sure they are adequately   packaged and insured.</li>
                      <li>b) The goods are to be returned unopened and in perfect resalable   condition.</li>
                      <li>c) If points a) and b) are met upon inspection of the returned goods, a   refund will be made to the customer.</li>
                    </ul>
                    <h1>Prices</h1>
                    <p>Goods and services, together with vat, are invoiced at the price prevailing   at time of order. safensound.co.uk reserves the right to modify the prices from   time to time.</p>
                    <h1>Errors and omissions</h1>
                    <p>safensound.co.uk makes every effort to ensure that all prices and descriptions   quoted in its showrooms and website are correct and accurate.</p>
                    <p>However, in the event that a mistake may occur due to the rapidly changing   nature of the market safensound.co.uk will be entitled to rescind the contract, not   withstanding that it has already accepted the customer's order, and   safensound.co.uk's liability in that event will be limited to the return of any   money the customer has paid in respect of that order.</p>
                    <p>These terms and conditions do not affect your statutory rights.</p><br>
                </td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top" class="maincont_right_td"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0" class="rightbar_tb">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
