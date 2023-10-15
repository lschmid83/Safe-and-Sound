<?php
/*
  $Id: visitor_mail_list.php,v 1.0 2008/10/06

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// Contribution by: Gosub

  require('includes/application_top.php');

  if ($_GET['page'] && $_GET['page'] !== "" && $_GET['page'] !== $HTTP_SESSION_VARS['mail_list_page']){
    $_SESSION["mail_list_page"] = $_GET['page'];
}

    if (isset($_GET['delete_mail'])) {
        tep_db_query("delete from " . TABLE_VISITOR . " where email = '" . $_GET['delete_mail'] . "'");
        tep_redirect(tep_href_link(FILENAME_VISITOR_MAIL_LIST, 'deleted_ok='.$_GET['delete_mail']));
    }

    if (isset($_POST['add_mail'])) {
        $query = tep_db_query("select v.email, c.customers_email_address from ".TABLE_VISITOR." v, ".TABLE_CUSTOMERS." c where v.email = '".$_POST['add_mail']."' or c.customers_email_address = '".$_POST['add_mail']."' ");
        if(mysql_num_rows($query) < 1){
            $insert_sql_data = array('email' => $_POST['add_mail']);
            tep_db_perform(TABLE_VISITOR, $insert_sql_data);
            ++$count_mail;
        }
    }

  if (isset($_FILES['usrfl'])){
    $string = file_get_contents($_FILES['usrfl']['tmp_name']);
    $string = strip_tags($string);
    // Find all email addresses in $string
    preg_match_all("([0-9a-zA-Z]([-.\w]*[0-9a-zA-Z])*@([0-9a-zA-Z][-\w]*[0-9a-zA-Z]\.)+[a-zA-Z]{2,9})", $string, $matches);
    $count_mail = 0;
    foreach ($matches[0] as $mail) {
        $query = tep_db_query("select v.email, c.customers_email_address from ".TABLE_VISITOR." v, ".TABLE_CUSTOMERS." c where v.email = '".$mail."' or c.customers_email_address = '".$mail."' ");
        if(mysql_num_rows($query) < 1){
            $insert_sql_data = array('email' => $mail);
            tep_db_perform(TABLE_VISITOR, $insert_sql_data);
            ++$count_mail;
        }
        //$messageStack->add($mail, 'success');
    }

    if (empty($string)) $messageStack->add(NOTICE_FILE_EMPTY, 'error');
    $messageStack->add(TEXT_ADD_EMAIL_SUCCESS . $count_mail, 'success');

  }

  if (isset($_GET['delete_ok'])) $messageStack->add(NOTICE_MAIL_DELETED.$_GET['delete_ok'], 'success');

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
        <td width="100%" valign="top">
        <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right">
            <?php  ?></td>
          </tr>
          <tr>
            <td colspan="2" class="main">
              <?php echo tep_draw_form('add_an_email', FILENAME_VISITOR_MAIL_LIST); ?>
                <p style="margin-top: 8px;"><b><?php echo ADD_VISITOR_MAIL; ?></b></p>
                <p>
                  <?php echo tep_draw_input_field('add_mail'); ?>
                  <input type="submit" name="buttoninsert" title="<?php echo INSERT; ?>" value="<?php echo INSERT; ?>">
                </p>
              </form>
            </td>
          </tr>
          <tr>
            <td colspan="2" class="main">
              <FORM ENCTYPE="multipart/form-data" ACTION="visitor_mail_list.php" METHOD=POST>
                <p style="margin-top: 8px;"><b><?php echo UPLOAD_AND_IMPORT_FROM_FILE; ?></b></p>
                <p>
                  <INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="100000000">
                  <input name="usrfl" type="file" size="50">
                  <input type="submit" name="buttoninsert" title="<?php echo UPLOAD; ?>" value="<?php echo UPLOAD; ?>">
                </p>
              </form>
            </td>
          </tr>
        </table>
<?php
$mail_list_query_raw = "select email from ".TABLE_VISITOR;
$query = tep_db_query($mail_list_query_raw);
$count_mail = mysql_num_rows($query);
?>
        <table border="0" width="100%" cellspacing="5" cellpadding="2">
            <tr>
                <td>
                <table width="100%" border="0" cellpadding="2" cellspacing="0">
                    <tr class="dataTableHeadingRow">
                        <td class="formAreaTitle">&nbsp;<?php echo HEADING_EMAIL . $count_mail; ?></td>
                        <td width="30%" class="formAreaTitle"><?php echo HEADING_ACTIONS; ?></td>
                    </tr>
<?php
        $mail_list_split = new splitPageResults($_SESSION["mail_list_page"], 100, $mail_list_query_raw, $mail_list_query_numrows);
        $mail_list_query = tep_db_query($mail_list_query_raw);
        while ($mail_list = tep_db_fetch_array($mail_list_query)) {             ?>
                    <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">
                        <td class="dataTableContent" valign="middle" >&nbsp;
                        <?php echo $mail_list["email"]; ?>                        
						</td>
                        <td class="dataTableContent" width="30%" valign="middle">
                        <?php echo '<a href="' . tep_href_link(FILENAME_VISITOR_MAIL_LIST, 'delete_mail='. $mail_list["email"]) . '" onClick="return check();">'.tep_image_button('button_remove.gif', MAIL_DELETE).'</a>'; ?>
                        </td>
                    </tr>
<!--					<tr>
					    <td colspan="2"><hr style="height:1px; margin:0px; color:#FFFFFF">
                        </td>
                    </tr>-->
<?php   } ?>
            </table></td>
            </tr><tr>
               <td class="smallText" valign="top"><?php echo $mail_list_split->display_count($mail_list_query_numrows, 100, $_SESSION["mail_list_page"], TEXT_DISPLAY_NUMBER_OF_MAILS); ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $mail_list_split->display_links($mail_list_query_numrows, 100, MAX_DISPLAY_PAGE_LINKS, $_SESSION["mail_list_page"]); ?></td>
              </tr>
        </table>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
