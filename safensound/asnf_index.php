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
file	: index.php
author	: asn - webmaster@tourdebali.com
date	: february 16th, 2004
note	: copyright 2004 by asn
modifed for OSC : Pawel Rychlewski (prychl@o2.pl)
___________________________________________________

*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/asnf_index.php');

  require_once("includes/asnf_config.php");
  require_once("includes/asnf_lib.php");
  require_once("includes/asnf_header.php");

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link('asnf_index.php'));
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0" >
          <tr>
            <td class="main">
<?php

/*
___________________________________________________

project : asn forum version 1.5
file	: index.php
author	: asn - webmaster@tourdebali.com
date	: february 16th, 2004
note	: copyright 2004 by asn
modifed for OSC : Pawel Rychlewski (prychl@o2.pl)
___________________________________________________

*/

?>

  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>

<?php

$hasil = tep_db_query("SELECT post_id FROM " . TABLE_ASNFORUM_POST);
$total_post = tep_db_num_rows($hasil);

$hasil_topic = tep_db_query("SELECT topic_id FROM " . TABLE_ASNFORUM_TOPIC);
$total_topic = tep_db_num_rows($hasil_topic);

if (!$total_topic) {
	echo '
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr bgcolor="' . $headercolor . '">
            <td width="100%" class="asnf_normal"><b><font color="#ff0000">' .
                 TEXT_ANSF_NO_TOPICS_FOUND . '
            </font></b></td>
        </tr>
     </table>';
}
else
{  //s¹ tematy

?>
  <td align="left" style="padding-left:0px"> <table border="0" cellspacing="0" cellpadding="1" bgcolor="#CCCCCC" width="125">
                <tr>
                  <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="3" bgcolor="#f5f5f5">
                      <tr>
                        <td class="asnf_normal" align="center"><b><a href="<?php echo tep_href_link('asnf_newtopic.php'); ?>" class="asnf_none">
                        <?php echo TEXT_ASN_NEW_TOPIC; ?></a></b></td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table></td>
 <td class="asnf_normal" align="right"><?php echo TEXT_ASN_TOPICS; ?>: <b><?php echo $total_topic; ?></b> | <?php echo TEXT_ASN_POSTS; ?>: <b><?php echo $total_post; ?></b>

<?php

if (!isset($start) OR ($start == "")) $start = 0 ;

$query_jumlah = tep_db_query("SELECT count(*) as all_record FROM " . TABLE_ASNFORUM_TOPIC);
$data_jumlah = tep_db_fetch_array($query_jumlah);
$all_record = $data_jumlah['all_record'];

$query = tep_db_query("SELECT topic.*, post.post_id, post.poster as pposter, post.email as pemail, post.web as pweb, SUBSTRING_INDEX(postext.post_text,' ',20) as post_text FROM " . TABLE_ASNFORUM_TOPIC . " topic, " . TABLE_ASNFORUM_POST . " post, " . TABLE_ASNFORUM_POSTEXT . " postext where topic.topic_id = post.topic_id and post.post_id = postext.post_id GROUP BY topic.topic_id ORDER BY topic.topic_time DESC LIMIT $start,$itemperpage");
$this_page = tep_db_num_rows($query);

$paging = generate_pagination(tep_href_link('asnf_index.php', "action=none"), $all_record, $itemperpage, $start);
if (!$paging == "") echo " | ";
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
  <img src="images/space.gif" width="100" height="5"><br>
  <table width="<?php echo $tablewidth; ?>" border="0" cellspacing="1" cellpadding="5" bgcolor="white">
    <tr bgcolor="<?php echo $headercolor; ?>">
      <td class="asnf_normal" width="15%"><font color="#FFFFFF"><b><?php echo TEXT_ASN_AUTHOR; ?></b></font></td>
      <td class="asnf_normal" width="50%"><b><font color="#FFFFFF"><?php echo TEXT_ASN_TOPIC; ?></font></b></td>
      <td class="asnf_normal" width="10%" align="center"><b><font color="#FFFFFF"><?php echo TEXT_ASN_VIEWS; ?></font></b></td>
      <td class="asnf_normal" width="10%" align="center"><b><font color="#FFFFFF"><?php echo TEXT_ASN_REPLIES; ?></font></b></td>
      <td class="asnf_normal" width="15%"><b><font color="#FFFFFF"><?php echo TEXT_ASN_LAST_REPLY; ?></font></b></td>
    </tr>
<?php

if ($this_page < $itemperpage) $looping = $this_page; else $looping = $itemperpage;

$i = 1;

while ($data = tep_db_fetch_array($query))	{

   if ((($i+1) % 2) == 0) $kolor = $rowcolor1; else $kolor = $rowcolor2;
   $tgl = substr($data['topic_time'],8,2) . "-" . substr($data['topic_time'],5,2) . "-" . substr($data['topic_time'],0,4);

   echo "<tr bgcolor=\"$kolor\">";
   echo "<td valign=\"top\" class=\"asnf_normal\">$tgl<br>";

   $user = $data['pposter'];
   $user_mail = $data['pemail'];
   $user_web = $data['pweb'];

   echo TEXT_ASN_BY . " <b>$user</b><br>";
   if (!$user_mail == "") {
	   $user_mail = str_replace("@","-at-",$user_mail);
       $user_mail = str_replace(".","-dot-",$user_mail);
	   echo "<img src=\"images/sym_email.gif\" border=\"0\" alt=\"$user_mail\" title=\"$user_mail\">  ";
   }
   if ((!$user_web == "") AND (!$user_web == "http://")) echo "<img src=\"images/sym_www.gif\" border=\"0\" title=\"$user_web\">";

   echo "</td>";

   echo "<td class=\"asnf_normal\"><a href=\"" . tep_href_link('asnf_viewtopic.php', "id=" . $data['topic_id']) . "\"><b>" . $data['topic_title'] . "</b></a><br>";

   //$hasil_post = tep_db_query("SELECT * FROM " . TABLE_ASNFORUM_POST . " WHERE topic_id=" . $data['topic_id'] . " ORDER BY post_time ASC");
   //$data_post = tep_db_fetch_array($hasil_post);
   //$hasil_text = tep_db_query("SELECT post_id, SUBSTRING_INDEX(post_text,' ',20) FROM " . TABLE_ASNFORUM_POSTEXT . " WHERE post_id=" . $data_post['post_id']);
   //$data_text = tep_db_fetch_array($hasil_text);
   $hasil_last = tep_db_query("SELECT * FROM " . TABLE_ASNFORUM_POST . " WHERE post_id=" . $data['topic_last_post_id']);
   $data_last = tep_db_fetch_array($hasil_last);

   $tgl_last = substr($data_last['post_time'],8,2) . "-" . substr($data_last['post_time'],5,2) . "-" . substr($data_last['post_time'],0,4);

   $kontet = smile($data['post_text']);
   $kontet = auto_url($kontet);
   echo  "$kontet ...</td>";

   echo     "<td align=\"center\" class=\"asnf_normal\"><b>" . $data['topic_views'] . "</b></td>
             <td align=\"center\" class=\"asnf_normal\"><b>" . $data['topic_replies'] . "</b></td>
             <td align=\"center\" class=\"asnf_normal\">$tgl_last<br>";

   $hasil_last_user = tep_db_query("SELECT * FROM " . TABLE_ASNFORUM_POST . " WHERE topic_id=" . $data['topic_id'] . " ORDER BY post_time DESC");
   $data_last_user = tep_db_fetch_array($hasil_last_user);
   $last = $data_last_user['poster'];

   echo TEXT_ASN_BY . " <b>$last</b></font></td></tr>";

   $i++;
}

?>

</table>

<?php

echo "<table width=\"$tablewidth\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\">
      <tr><td class=\"asnf_normal\" align=\"right\"> $paging </td></tr></table>";

}//sa tematy
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
