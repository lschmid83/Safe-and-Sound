<?php
/*
  $Id: $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

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
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr><!-- body_text //-->
    <td width="100%" valign="top">
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
<?php
  if ($messageStack->size('visitor') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('visitor'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  } else {
?>
      <tr>
       <td class="main">Thank you for submiting your e-mail for our newsletter.</b></td>
      </tr>
<?php } ?>
            </table></td>
          </tr>
        </table></td>
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
