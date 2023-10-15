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
file	: search.php
author	: asn - webmaster@tourdebali.com
date	: february 16th, 2004
note	: copyright 2004 by asn
modifed for OSC : Pawel Rychlewski (prychl@o2.pl)
___________________________________________________

*/

  require('includes/application_top.php');

  if (!isset($_GET["asnfkeywords"])) tep_redirect(tep_href_link('asnf_index.php','','SSL'));

  require(DIR_WS_LANGUAGES . $language . '/asnf_search_result.php');

  require_once("includes/asnf_config.php");
  require_once("includes/asnf_lib.php");
  require_once("includes/asnf_header.php");

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link('asnf_search.php'));
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
            	echo TEXT_ASN_SEARCH_TOPIC . ': <b><i>' . stripslashes($_GET['asnfkeywords']) . '</i></b>';
            ?>
            </td>
          </tr>
          <tr>
            <td class="main">
<?php
/*
___________________________________________________

project : asn forum version 1.5
file	: search.php
author	: asn - webmaster@tourdebali.com
date	: february 16th, 2004
note	: copyright 2004 by asn
modifed for OSC : Pawel Rychlewski (prychl@o2.pl)
___________________________________________________

*/


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

      <td class="asnf_normal" align="right">

<?php

if (!isset($start) OR ($start == "")) $start = 0 ;

//$query_jumlah = tep_db_query("SELECT count(*) FROM " . TABLE_ASNFORUM_POST . " WHERE topic_id=$id");
//$data_jumlah = mysql_fetch_row($query_jumlah);
//$all_record = $data_jumlah[0];

$search_keywords = $_GET['asnfkeywords'];
$search_keywords = strip_tags($search_keywords);
$search_keywords = addslashes($search_keywords);

tep_parse_search_string($search_keywords, $search_parse_keywords);

$where_str = "";

if ($_GET['asnf_search_topic'] != -1) $where_str = "and topic.topic_id='" . $_GET['asnf_search_topic'] . "' ";

if (isset($search_parse_keywords) && (sizeof($search_parse_keywords) > 0)) {
    $where_str .= " and (";
    for ($i=0, $n=sizeof($search_parse_keywords); $i<$n; $i++ ) {
      switch ($search_parse_keywords[$i]) {
        case '(':
        case ')':
        case 'and':
        case 'or':
          $where_str .= " " . $search_parse_keywords[$i] . " ";
          break;
        default:
          $keyword = tep_db_prepare_input($search_parse_keywords[$i]);
          $where_str .= "(topic.topic_title like '%" . tep_db_input($keyword) . "%'";
          if (isset($_GET['search_in_post']) && ($_GET['search_in_post'] == '1')) $where_str .= " or postext.post_text like '%" . tep_db_input($keyword) . "%'";
          $where_str .= ')';
          break;
      }
    }
    $where_str .= " )";
  }

$query_jumlah = tep_db_query("SELECT count(*) from " . TABLE_ASNFORUM_TOPIC . " topic, " . TABLE_ASNFORUM_POST . " post, " . TABLE_ASNFORUM_POSTEXT . " postext where post.post_id=postext.post_id and topic.topic_id=post.topic_id " . $where_str . " group by post.post_id");
$all_record = tep_db_num_rows($query_jumlah);

$query = tep_db_query("SELECT topic.topic_id, topic.topic_title, post.*, postext.post_text from " . TABLE_ASNFORUM_TOPIC . " topic, " . TABLE_ASNFORUM_POST . " post, " . TABLE_ASNFORUM_POSTEXT . " postext where post.post_id=postext.post_id and topic.topic_id=post.topic_id " . $where_str . " group by post.post_id order by post.post_time DESC LIMIT $start, $itemperpage");

$this_page = tep_db_num_rows($query);


$paging = generate_pagination(tep_href_link('asnf_search_result.php', tep_get_all_get_params(array('start','submit','x','y'))), $all_record, $itemperpage, $start);
if (!$paging == "") echo " -- ";
echo $paging . "</td></tr></table>";

if (!$all_record) {
	echo '
	<table width="100%" border="0" cellspacing="1" cellpadding="5">
        <tr bgcolor="' . $headercolor . '">
            <td width="100%" class="asnf_normal"><b><font color="#ff0000">' .
                 TEXT_ANSF_NOTHING_FOUND . '
            </font></b></td>
        </tr>
     </table>';
}
else { //cos znalazlem

?>
        <img height="5" src="images/space.gif" width="<?php echo $tablewidth; ?>"><br>
        <table width="<?php echo $tablewidth; ?>" border="0" cellspacing="1" cellpadding="5" bgcolor="white">
          <tr bgcolor="<?php echo $headercolor; ?>">
            <td width="25%" class="asnf_normal"><b><font color="#FFFFFF">&nbsp;</font></b></td>
            <td width="75%" class="asnf_normal"><b><font color="#FFFFFF"><?php echo TEXT_ASN_SEARCH_TOPIC; ?></font></b></td>
          </tr>

<?php

if ($this_page < $itemperpage) $looping = $this_page; else $looping = $itemperpage;

$i = 1;

while ($data = tep_db_fetch_array($query))	{

   if ((($i+1) % 2) == 0) $kolor = $rowcolor1; else $kolor = $rowcolor2;
   $tgl = substr($data['post_time'],8,2) . "-" . substr($data['post_time'],5,2) . "-" . substr($data['post_time'],0,4);

   if (($_GET['asnf_search_topic'] == -1) || ($i==1)) {
   echo '<tr bgcolor="' . $headercolor . '">
            <td width="25%" class="asnf_normal"><b><font color="#FFFFFF">'. TEXT_ASN_POSTED .'</font></b></td>
            <td width="75%" class="asnf_normal"><b><a href="'. tep_href_link('asnf_viewtopic.php', "id=" . $data['topic_id']) . '">' . $data['topic_title'] . '</a></b></td>
          </tr>';
   }

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
   echo  tep_mark_keywords($kontet, $search_keywords) . "</td></tr>";

   $i++;
}

?>
      </table>
<?php

echo "<table width=\"$tablewidth\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\">
      <tr><td class=\"asnf_normal\" align=\"right\"> $paging </td></tr></table>";
}  //cos znalazlem
include("includes/asnf_footer.php");
?>
            </td>
          </tr>
        </table></td>
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
