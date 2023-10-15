<?php
/*
  $Id: header_tags_fill_tags.php,v 1.0 2005/08/25
  Originally Created by: Jack York - http://www.oscommerce-solution.com
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
 
  require('includes/application_top.php'); 
  require(DIR_WS_LANGUAGES . $language . '/asnf_login.php');

/*
___________________________________________________

project : asn forum version 2.0
file	: login.php
author	: asn - script@tourdebali.com
modifed for OSC : Pawel Rychlewski (prychl@o2.pl)
___________________________________________________

*/


require_once("includes/asnf_config.php");
$default_font = "verdana, arial, helvetica, sans-serif";

function html_header() {
   global $version, $tablewidth, $default_font, $asnforum_title;

   echo "
      <html>
      <head><title>$asnforum_title $version</title></head>
      <body bgcolor=\"#FFFFFF\">
      <center><br>
      <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"$tablewidth\">
      <tr><td align=\"center\">
	  <font face=\"$default_font\" size=\"5\" color=\"#990000\"><b>" . HEADING_TITLE_ADMIN . "</b></font><br><br><hr size=1 noshade><br>";
}

function html_footer() {
   global $version, $default_font;

   echo "
   </td></tr>
   </table>
   <font face=\"$default_font\" size=\"1\">
   [ <a href=\"http://script.tourdebali.com\" target=\"_blank\">asn forum $version</a> ]
   </font><br>
   </center>
   </body>
   </html>";
}
  
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<style type="text/css">
td.HTC_Head {color: sienna; font-size: 24px; font-weight: bold; } 
td.HTC_subHead {color: sienna; font-size: 14px; } 
</style>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
     <tr>
      <td class="HTC_Head"><?php echo HEADING_TITLE_ASNF; ?></td>
     </tr>
     <tr>
      <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
     
     <!-- Begin of ASN Forum -->      
          
     <tr>
      <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
     </tr>
     <tr>
      <td><table width="100%" border="0">
       <tr>
<?php
html_header();
echo "
   <form method='post' action='asnf_admin.php'>
   <table width=90% cellpadding=3 cellspacing=0 border=0>
      <tr><td>
          <font face='arial, helvetica' size=3>";
          if ($error == "1") { 
			  echo "<font color=#ff0000>" . TEXT_ASN_INVALID . "</font>"; 
			  } else { 
				  echo TEXT_ASN_ENTER;
		   }
echo "
		  </font><P>
          <font face='arial, helvetica' size=2><b>" . TEXT_ASN_USERNAME . ":</b></font><br>
          <input type='text' name='vadmin' size='40' maxlength=100><br>
          <font face='arial, helvetica' size=2><b>" . TEXT_ASN_PASSWORD . ":</b></font><br>
          <input type='password' name='vpassword' size='20' maxlength=10><p>
          <input type='submit' value='" . TEXT_ASN_LOGIN . "'>
		  <br><br>
      </td></tr>
   </table>
   </form>
";
echo "<hr size=1 noshase><br>";
html_footer();

?>
       </tr>
      </table> 

     <!-- end of ASN Forum -->

         
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
