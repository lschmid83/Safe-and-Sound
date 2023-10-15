<?php
/*
  LoginBox v5.2.wfc1.0
  For osCommerce, Open Source E-Commerce Solutions

  http://www.template-faq.com
  Copyright (c) 2004 template-faq.com, Inc

*/
  define('BOX_HEADING_LOGIN_BOX_MY_ACCOUNT','My Account Info.');
  define('LOGIN_BOX_MY_ACCOUNT','My Account Overview');
  define('LOGIN_BOX_ACCOUNT_EDIT','Edit My Account Information');
  define('LOGIN_BOX_ADDRESS_BOOK','Edit Address Book');
  define('LOGIN_BOX_ACCOUNT_HISTORY','View My Order History');
  define('LOGIN_BOX_PRODUCT_NOTIFICATIONS','Product Notifications');
  define('LOGIN_BOX_PASSWORD_FORGOTTEN','Password Forgotten?');

?>
<!-- loginbox //-->
<?php
    if (!tep_session_is_registered('customer_id')) {
?>
          <tr>
            <td>
<?php
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => HEADER_TITLE_LOGIN
                                );
    new infoBoxHeading($info_box_contents, false, false, false, $column_location);
    $loginboxcontent = "
            <table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" class=\"login_box\">
            <form name=\"login\" method=\"post\" action=\"" . tep_href_link(FILENAME_LOGIN, 'action=process', 'SSL') . "\">
              <tr>
                <td align=\"center\" class=\"boxText\">
                  " . ENTRY_EMAIL_ADDRESS . "
                </td>
              </tr>
              <tr>
                <td align=\"center\" class=\"boxText\">
                  <input type=\"text\" name=\"email_address\" maxlength=\"96\" size=\"15\" value=\"\">
                </td>
              </tr>
              <tr>
                <td align=\"center\" class=\"boxText\">
                  " . ENTRY_PASSWORD . "
                </td>
              </tr>
              <tr>
                <td align=\"center\" class=\"boxText\">
                  <input type=\"password\" name=\"password\" maxlength=\"40\" size=\"15\" value=\"\"
                </td>
              </tr>
              <tr>
                <td align=\"center\" class=\"boxText\">
                  " . tep_image_submit('button_login.gif', IMAGE_BUTTON_LOGIN, 'SSL') . "
                </td>
              </tr>
            </form>
            </table>";
  
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'center',
                                 'text'  => $loginboxcontent
                                );
    new infoBox($info_box_contents, $column_location, '0', '0');
?>
            </td>
          </tr>
<?php
  } else {
  // If you want to display anything when the user IS logged in, put it
  // in here...  Possibly a "You are logged in as :" text or something.
  }
?>
<!-- loginbox_eof //-->
<?php
  if (tep_session_is_registered('customer_id')) {
?>

<!-- my_account_info //-->
          <tr>
            <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => BOX_HEADING_LOGIN_BOX_MY_ACCOUNT
                              );
  new infoBoxHeading($info_box_contents, false, false, false, $column_location);

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  =>
                                          '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . LOGIN_BOX_MY_ACCOUNT . '</a><br>' .
                                          '<a href="' . tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL') . '">' . LOGIN_BOX_ACCOUNT_EDIT . '</a><br>' .
                                          '<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . LOGIN_BOX_ADDRESS_BOOK . '</a><br>' .
                                          '<a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '">' . LOGIN_BOX_ACCOUNT_HISTORY . '</a><br>' .
                                          '<a href="' . tep_href_link(FILENAME_ACCOUNT_NOTIFICATIONS, '', 'NONSSL') . '">' . LOGIN_BOX_PRODUCT_NOTIFICATIONS . '</a><br>' .
                                          '<a href="' . tep_href_link(FILENAME_LOGOFF, '', 'NONSSL') . '">' . HEADER_TITLE_LOGOFF . '</a>'
                              );
  new infoBox($info_box_contents, $column_location, '0', '0');
?>
            </td>
          </tr>
<!-- my_account_info_eof //-->

<?php
}
?>

