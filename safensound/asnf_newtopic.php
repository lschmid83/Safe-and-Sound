<?php
/*
  $Id: privacy.php,v 1.22 2003/06/05 23:26:23 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
/*
___________________________________________________

project : asn forum
file	: newtopic.php
author	: asn - script@tourdebali.com
modifed for OSC : Pawel Rychlewski (prychl@o2.pl)
___________________________________________________

*/

  require_once('includes/application_top.php');

  require_once(DIR_WS_LANGUAGES . $language . '/asnf_newtopic.php');

  require_once("includes/asnf_config.php");
  require_once("includes/asnf_lib.php");
  require_once("includes/asnf_header.php");

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link('asnf_newtopic.php'));
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
<body style="margin-bottom:5px">
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
    <td width="100%" valign="top" class="maincont_mid_td" style="padding-left:4px"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading" valign="top"><?php echo HEADING_TITLE; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main">
<?php

/*
___________________________________________________

project : asn forum
file	: newtopic.php
author	: asn - script@tourdebali.com
modifed for OSC : Pawel Rychlewski (prychl@o2.pl)
___________________________________________________

*/

?>

<SCRIPT LANGUAGE="JavaScript">
function textCounter(field, countfield, maxlimit) {
if (field.value.length > maxlimit) // if too long...trim it!
field.value = field.value.substring(0, maxlimit);
// otherwise, update 'characters left' counter
else
countfield.value = maxlimit - field.value.length;
}

function getURLParam(strParamName){
  var strReturn = "";
  var strHref = window.location.href;
  if ( strHref.indexOf("?") > -1 ){
    var strQueryString = strHref.substr(strHref.indexOf("?")).toLowerCase();
    var aQueryString = strQueryString.split("&");
    for ( var iParam = 0; iParam < aQueryString.length; iParam++ ){
      if (
aQueryString[iParam].indexOf(strParamName.toLowerCase() + "=") > -1 ){
        var aParam = aQueryString[iParam].split("=");
        strReturn = aParam[1];
        break;
      }
    }
  }
  return unescape(strReturn);
}
</script>




              <table width="200" border="0" cellspacing="0" cellpadding="1" bgcolor="#CCCCCC">
                <tr>
                  <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="3" bgcolor="#f5f5f5">
                      <tr>
                        <td class="asnf_normal" align="center"><b><a href="<?php echo tep_href_link('asnf_index.php') . '" class="asnf_none">' . TEXT_ASN_HOME; ?></a></b></td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
      <br>
<?php
$allow_post = 1;

if (($asnf_registered_only == "true") && (tep_session_is_registered('customer_id')) && ($customer_id != 0)) {
  $allow_post = 1;
}
else
$allow_post = 0;

if ($asnf_registered_only != "true") $allow_post = 1;

if ($allow_post == 1)
{
?>
      <b><span class="asnf_normal"><?php echo TEXT_ASN_INFO; ?></span></b><br><br>
	  <form method="post" action="<?php echo tep_href_link('asnf_addtopic.php'); ?>" name="posting">
			  	<?php
                   $pecah = explode(".",$REMOTE_ADDR);
				   $pecah3 = $pecah[0] . $pecah[1] . $pecah[2] . tep_session_id($HTTP_GET_VARS[tep_session_name()]);
				?>
	            <input type="hidden" name="verify" value="<?php echo md5($floodpass.$pecah3); ?>">
	  <table width="100%" border="0" cellspacing="0" cellpadding="15" class="asnf_replyform" bgcolor="#FBFBF0">
        <tr><td>
		      <table width="100%" border="0" cellspacing="0" cellpadding="3"><tr>

          <td width="25%" class="asnf_normal"><b><?php echo TEXT_ASN_NAME; ?><font color="#FF0000">*</font></b></td>
          <td width="75%">
            <input type="text" name="nama"
            <?php
                if (tep_session_is_registered('customer_id'))
                echo 'value="'. $customer_first_name . '"';
            ?>>
          </td>
        </tr>
        <tr>
          <td class="asnf_normal"><b><?php echo TEXT_ASN_EMAIL; ?></b></td>
          <td width="72%">
            <input type="text" name="email" size="30">
          </td>
        </tr>
        <tr>
          <td class="asnf_normal"><b><?php echo TEXT_ASN_WWW; ?></b></td>
          <td>
            <input type="text" name="homepage" size="30" value="http://">
          </td>
        </tr>
        <tr>
          <td class="asnf_normal"><b><?php echo TEXT_ASN_SUBJECT; ?><font color="#FF0000">*</font></b></td>
          <td>
            <input type="text" name="topic" size="40">
          </td>
        </tr>
            <tr>
				<td width="28%" class="asnf_normal"><b>Date</b></td>
			    <td width="72%" class="asnf_normal">
			    <input type="text" name="reply_date" size="30" value="2009-08-21">
			    </td>
            </tr>
		<tr>
          <td class="asnf_normal" valign="top"><br><br><br><b><?php echo TEXT_ASN_TEXT; ?><font color="#FF0000">*</font></b></td>
          <td class="asnf_normal">
            <textarea name="topictext" rows="7" cols="35" onKeyDown="textCounter(this.form.topictext,this.form.remLen,<?php echo $maxchar; ?>);" onKeyUp="textCounter(this.form.topictext,this.form.remLen,<?php echo $maxchar; ?>);"></textarea><br>
			<?php echo TEXT_ASN_LEFT; ?>: <input readonly type=text name=remLen size=4 maxlength=4 value="<?php echo $maxchar; ?>">
			      <table border="0" cellspacing="0" cellpadding="5">

	  <?php
	  $query_smile = tep_db_query("SELECT MIN(id), code, smile_url, emotion FROM " . TABLE_ASNFORUM_SMILE . " GROUP BY emotion LIMIT 0,20");
	  $counter = 10;
	  while($data_smile = mysql_fetch_row($query_smile)) {
	     if (($counter % 10) == 0) echo "<tr>";
		 echo "<td><a href=\"javascript:void(0);\" onClick=\"document.posting.topictext.value+='$data_smile[1] ';\"><img src=\"images/$data_smile[2]\" border=\"0\" alt=\"$data_smile[3]\"></a></td>";
	     if (($counter % 10) == 9) echo "</tr>";
		 $counter++;

	  }
	  ?>
      </table>
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>
            <input type="submit" name="Submit" value="<?php echo TEXT_ASN_SUBMIT; ?>">
          </td>
        </tr>
      </table></tr></table></form>
<?php
}
else
{
	echo '
	<table width="100%" border="0" cellspacing="0" cellpadding="3">
	 <tr>
        <td width="100%" class="asnf_normal" align="center"><b>' . TEXT_ASN_REGISTERED . '</td>
     </tr>
    </table>';
}

include("includes/asnf_footer.php"); ?>
            </td>
          </tr>
        </table></td>
        </tr>
        <tr>
			<td colspan="2" style="color:#000">
				<script type="text/javascript">
				if(getURLParam("error")=="empty_field")
						document.write("<br/><b>Please complete all fields marked with * and try the send button again.</b>");
				else if(getURLParam("error")=="message_length")
					document.write("The maximum number of characters per word must not exceed 100.");
				</script>
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
