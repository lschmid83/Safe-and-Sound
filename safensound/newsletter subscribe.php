<?php
/*
  $Id: specials.php,v 1.49 2003/06/09 22:35:33 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SPECIALS);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_SPECIALS));


  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_VISITOR_EMAIL);

  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process')) {
    $error = false;

    $to_email_address = tep_db_prepare_input($HTTP_POST_VARS['to_email_address']);
    $visitor_query = tep_db_query("select email from " . TABLE_VISITOR . " where email = '" . $HTTP_POST_VARS['to_email_address'] . "'");
    $customer_query = tep_db_query("select customers_email_address from " . TABLE_CUSTOMERS . " where customers_email_address = '" . $HTTP_POST_VARS['to_email_address'] . "'");

    if (!tep_validate_email($to_email_address)) {
      $error = true;

      $messageStack->add('visitor', ERROR_TO_ADDRESS);
    }
    if ($visitor = tep_db_fetch_array($visitor_query)) { //check if this e-mail already exists in visitor database
      $error = true;
      $messageStack->add('visitor', ALREADY_EXIST);
    } elseif ($customer = tep_db_fetch_array($customer_query)) { //okay, does it exist in our customer database?
      $error = true;
      $messageStack->add('visitor', ALREADY_EXIST);
    }

    if ($error == false) { // everything is okay
      $sql_data_array = array('email' => $to_email_address);

      tep_db_perform(TABLE_VISITOR, $sql_data_array);

    }
  }



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
    <td width="960px" valign="top" class="maincont_mid_td">
    <?php require(DIR_WS_INCLUDES . 'sub_header.php'); ?>
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">Newsletter Subscribe</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2" class="maincont_mid_td">
          <tr>
            <td >
            <table style="width: 100%">
 			  <tr>
				<?php
				  if ($messageStack->size('visitor') > 0) {
				?>
						<td class="main"><p>The email address you entered is either invalid or you have already subscribed to our newsletter.</p><p></p></td>
				<?php
				  } else {
				?>
					   <td class="main"><p>Thank you for submiting your e-mail for our newsletter.</p><p></p></td>
				<?php } ?>
			  </tr>

			  <tr>
			  	<td class="main"><p>Our Newsletter will keep you informed of all the latest car audio deals.</p>
					<p>We take your privacy seriously and will never give your details to third parties and you can opt-out at any time.</p>
				</td>
			  </tr>
			  <tr>
			    <td align="center" style="padding-top:18px; padding-bottom:15px; padding-left:1px">
			    	<a href="/catalog/images/newsletter.png" target="_blank"><img src="/catalog/images/newsletter.png" alt="Newsletter Preview" border="0" width="550px"/></a>
			    </td>
			  </tr>
			  <tr>
			  <td class="main">
					<p>Thanks for your interest. The Safe'n'Sound Team</p>
				</td>
			  </tr>

			</table>
            </td>
          </tr>
        </table>
        </td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
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
