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

project : asn forum version 1.5
file	: viewtopic.php
author	: asn - webmaster@tourdebali.com
date	: february 16th, 2004
note	: copyright 2004 by asn
modifed for OSC : Pawel Rychlewski (prychl@o2.pl)
___________________________________________________

*/

  require('includes/application_top.php');

  if (!isset($_GET["id"])) tep_redirect(tep_href_link('asnf_index.php','','SSL'));

  require(DIR_WS_LANGUAGES . $language . '/asnf_viewtopic.php');

  require_once("includes/asnf_config.php");
  require_once("includes/asnf_lib.php");
  require_once("includes/asnf_header.php");

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link('asnf_viewtopic.php'));
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

<SCRIPT LANGUAGE="JavaScript">
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="main">
<?php
/*
___________________________________________________

project : asn forum version 1.5
file	: viewtopic.php
author	: asn - webmaster@tourdebali.com
date	: february 16th, 2004
note	: copyright 2004 by asn
modifed for OSC : Pawel Rychlewski (prychl@o2.pl)
___________________________________________________

*/
$hasil_topic = tep_db_query("SELECT * FROM ". TABLE_ASNFORUM_TOPIC . " WHERE topic_id=$id");
$data_topic = tep_db_fetch_array($hasil_topic);

if (!tep_db_num_rows($hasil_topic)) {
	echo '
	<table width="100%" border="0" cellspacing="1" cellpadding="5">
        <tr bgcolor="' . $headercolor . '">
            <td width="100%" class="asnf_normal"><b><font color="#ff0000">' .
                 TEXT_ANSF_NO_TOPIC_FOUND . '
            </font></b></td>
        </tr>
     </table>';
}
else //istnieje temat
{

$ins2 = tep_db_query("UPDATE " . TABLE_ASNFORUM_TOPIC . " SET topic_views=topic_views+1 WHERE topic_id=$id");

?>

  <table width="<?php echo $tablewidth; ?>" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td>
        <table width="230" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>
			  <?php makebutton(TEXT_ASN_HOME, "asnf_index.php", "210"); ?>
			</td>
          </tr>
        </table>
		</td>

      <td class="asnf_normal" align="right"><?php echo TEXT_ASN_VIEWS; ?>: <b><?php echo $data_topic['topic_views']; ?></b> -- <?php echo TEXT_ASN_REPLIES; ?>: <b><?php echo $data_topic['topic_replies']; ?></b>

<?php


if (!isset($start) OR ($start == "")) $start = 0 ;

$query_jumlah = tep_db_query("SELECT count(*) as all_record FROM " . TABLE_ASNFORUM_POST . " WHERE topic_id=$id");
$data_jumlah = tep_db_fetch_array($query_jumlah);
$all_record = $data_jumlah['all_record'];

$query = tep_db_query("SELECT post.*, postext.post_text FROM " . TABLE_ASNFORUM_POST . " post, " . TABLE_ASNFORUM_POSTEXT . " postext WHERE post.topic_id=$id and post.post_id = postext.post_id ORDER BY post.post_time LIMIT $start, $itemperpage");
$this_page = tep_db_num_rows($query);

if (!$this_page) {
	echo '
	<table width="100%" border="0" cellspacing="1" cellpadding="5">
        <tr bgcolor="' . $headercolor . '">
            <td width="100%" class="asnf_normal"><b><font color="#ff0000">' .
                 TEXT_ANSF_NO_POST_FOUND . '
            </font></b></td>
        </tr>
     </table>';
}
else //istniej¹ posty
{

$paging = generate_pagination(tep_href_link('asnf_viewtopic.php', "id=$id"), $all_record, $itemperpage, $start);
if (!$paging == "") echo " -- ";
echo $paging . "</td></tr></table>";

//search by mastergigi
echo '<img height="5" src="images/space.gif" width="100%"><br>
      <form method="get" action="' . tep_href_link('asnf_search_result.php') . '" name="asnf_search">' . '
      <table width="100%" border="0" cellspacing="1" cellpadding="5">
        <tr >
            <td width="40%" class="asnf_normal" align="left">' .
                 TEXT_ASN_FIND . ':
            </td>
            <td width="60%" class="asnf_normal">
              <input type="text" name="asnfkeywords" size="60">
            </td>
        </tr>
        <tr >
            <td width="40%" class="asnf_normal" align="left">' .
                 TEXT_ASN_FIND_IN_POST . ':
            </td>
            <td width="60%" class="asnf_normal">
              <input type="checkbox" name="search_in_post" value="1" checked>
            </td>
        </tr>
        <tr >
            <td width="40%" class="asnf_normal" align="left">' .
                 TEXT_ASN_FIND_WHERE . ':
            </td>
            <td width="60%" class="asnf_normal">' .
              listTopics($id, TEXT_ASN_FIND_ALL) . '
            </td>
        </tr>
        <tr >
            <td width="100%" class="asnf_normal" align="right" colspan="2">' .
                tep_image_submit('button_search_asnf.gif', IMAGE_BUTTON_SEARCH) . '
            </td>
        </tr>
        </table>
        </form>';



?>
        <img height="5" src="images/space.gif" width="<?php echo $tablewidth; ?>"><br>
        <table width="<?php echo $tablewidth; ?>" border="0" cellspacing="1" cellpadding="5" bgcolor="white">
          <tr bgcolor="<?php echo $headercolor; ?>">
            <td width="25%" class="asnf_normal"><b><font color="#FFFFFF"><?php echo TEXT_ASN_POSTED; ?></font></b></td>
            <td width="75%" class="asnf_normal"><b><font color="#FFFFFF"><?php echo $data_topic['topic_title'] ?></font></b></td>
          </tr>

<?php

if ($this_page < $itemperpage) $looping = $this_page; else $looping = $itemperpage;

$i = 1;
while ($data = tep_db_fetch_array($query))	{
   if ((($i+1) % 2) == 0) $kolor = $rowcolor1; else $kolor = $rowcolor2;
   $tgl = substr($data['post_time'],8,2) . "-" . substr($data['post_time'],5,2) . "-" . substr($data['post_time'],0,4);

   echo "<tr bgcolor=\"$kolor\">";
   echo "<td valign=\"top\" class=\"asnf_normal\">$tgl<br>";

   $user = $data['poster'];
   $user_mail = $data['email'];
   $user_web = $data['web'];

   echo TEXT_ASN_BY . " <b>$user</b><br>";
   if (!$user_mail == "") {
	   $user_mail = str_replace("@","-at-",$user_mail);
       $user_mail = str_replace(".","-dot-",$user_mail);
	   echo "<img src=\"images/sym_email.gif\" border=\"0\" alt=\"$user_mail\" title=\"$user_mail\">  ";
   }
   if (!$user_web == "") echo "<img src=\"images/sym_www.gif\" border=\"0\" title=\"$user_web\">";

   echo "</td>";
   echo "<td class=\"asnf_normal\">";

   $kontet = smile($data['post_text']);
   $kontet = auto_url($kontet);
   echo  "$kontet</td></tr>";

   $i++;
}

?>
      </table>
<?php

echo "<table width=\"$tablewidth\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\">
      <tr><td class=\"asnf_normal\" align=\"right\"> $paging </td></tr></table>";

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
  <img src="images/space.gif" width="100" height="5"><br><a name="form"></a>
<SCRIPT LANGUAGE="JavaScript">
function textCounter(field, countfield, maxlimit) {
if (field.value.length > maxlimit) // if too long...trim it!
field.value = field.value.substring(0, maxlimit);
// otherwise, update 'characters left' counter
else
countfield.value = maxlimit - field.value.length;
}
</script>
	<form method="post" action="<?php echo tep_href_link('asnf_reply.php'); ?>" name="posting">
    <table width="100%" border="0" cellspacing="0" cellpadding="10" class="asnf_replyform" bgcolor="#FBFBF0">
      <tr>
        <td>
          <table width="100%" border="0" cellspacing="0" cellpadding="3">
            <tr>
              <td colspan="2" class="asnf_normal">
			  	<?php
                   $pecah = explode(".",$REMOTE_ADDR);
				   $pecah3 = $pecah[0] . $pecah[1] . $pecah[2] . tep_session_id($HTTP_GET_VARS[tep_session_name()]);
				?>
	            <input type="hidden" name="verify" value="<?php echo md5($floodpass.$pecah3); ?>">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <span class="asnf_normal"><b><font color="#990000"><?php echo TEXT_ASN_INFO; ?>:</font></b></span> </td>
            </tr>
            <tr>
              <td colspan="2" class="asnf_normal">
                <hr size="1" noshade>
              </td>
            </tr>
            <tr>
              <td width="28%" class="asnf_normal"><b><?php echo TEXT_ASN_NAME; ?> <font color="#FF0000">*</font></b></td>
              <td width="72%" class="asnf_normal">
                <input type="text" name="nama"
                <?php
                if (tep_session_is_registered('customer_id'))
                echo 'value="'. $customer_first_name . '"';
                ?>>
              </td>
            </tr>
            <tr>
              <td width="28%" class="asnf_normal"><b><?php echo TEXT_ASN_EMAIL; ?></b></td>
              <td width="72%" class="asnf_normal">
                <input type="text" name="email" size="30">
              </td>
            </tr>
            <tr>
              <td width="28%" class="asnf_normal"><b><?php echo TEXT_ASN_WWW; ?></b></td>
              <td width="72%" class="asnf_normal">
                <input type="text" name="homepage" size="30" value="http://">
              </td>
            </tr>
            <tr>
				<td width="28%" class="asnf_normal"><b>Date</b></td>
			    <td width="72%" class="asnf_normal">
			    <input type="text" name="reply_date" size="30" value="2009-08-21">
			    </td>
            </tr>

            <tr>
              <td width="28%" class="asnf_normal" valign="top"><br><br><br><b><?php echo TEXT_ASN_TEXT; ?><font color="#FF0000">*</font></b></td>
              <td width="72%" class="asnf_normal">
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
              <td width="28%" class="asnf_normal">&nbsp;</td>
              <td width="72%" class="asnf_normal">
                <input type="submit" name="Submit" value="<?php echo TEXT_ASN_SUBMIT; ?>">
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    </form>

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

} //s¹ posty

} //istnieje temat

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
