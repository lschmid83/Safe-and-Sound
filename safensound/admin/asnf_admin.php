<?php
/*
  $Id: header_tags_fill_tags.php,v 1.0 2005/08/25
  Originally Created by: Jack York - http://www.oscommerce-solution.com
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
 ini_set('display_errors', 1);
 ini_set('log_errors', 1);
 ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
error_reporting(E_ALL);
  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/asnf_admin.php');

/*
___________________________________________________

project : asn forum version 2.0
file	: asnf_admin.php
author	: asn - script@tourdebali.com
modifed for OSC : Pawel Rychlewski (prychl@o2.pl)
___________________________________________________

*/

require_once("includes/asnf_config.php");
require_once("asnf_auth.php");
require_once("includes/asnf_lib.php");

//#########################################################################################################################//
//  B R O W S E   T O P I C
//#########################################################################################################################//

function browtopic() {
   global $itemperpage, $start, $tablewidth, $admheadercolor, $admrowcolor1, $admrowcolor2;

   $index_url = tep_href_link('asnf_admin.php', "action=browtopic");
   if (!isset($start) OR ($start == "")) $start = 0 ;

   $query_jumlah = tep_db_query("SELECT count(*) FROM " . TABLE_ASNFORUM_TOPIC);
   $data_jumlah = mysql_fetch_row($query_jumlah);
   $all_record = $data_jumlah[0];

   $query = tep_db_query("SELECT * FROM " . TABLE_ASNFORUM_TOPIC . " ORDER BY topic_time DESC LIMIT $start, $itemperpage");
   $this_page= tep_db_num_rows($query) or die("No records found!");

   echo"<table width=\"$tablewidth\" border=\"0\" cellspacing=\"1\" cellpadding=\"2\">
          <tr><td class=\"normal\" align=\"center\">";

   echo "<font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\"><b>" . TEXT_ASN_BROWSE;

   $paging = generate_pagination($index_url, $all_record, $itemperpage, $start);
   echo $paging . "</td></tr></table>";

   echo "</b></font>
     <form method=\"get\" action=\"" . tep_href_link('asnf_admin.php') . "\">
	 <input type=\"hidden\" name=\"action\" value=\"deltopic\">
	 <input type=\"hidden\" name=\"start\" value=\"$start\">";

   echo "
	<table width=\"$tablewidth\" border=\"0\" cellspacing=\"1\" cellpadding=\"5\" bgcolor=\"#d0d0d0\">
    <tr bgcolor=\"$admheadercolor\">
      <td align=\"center\" width=\"5%\">&nbsp;</td>
	  <td width=\"20%\" class=\"normal\"><font color=\"#FFFFFF\"><b>" . TEXT_ASN_POSTER . "</b></font></td>
	  <td width=\"55%\" class=\"normal\"><font color=\"#FFFFFF\"><b>" . TEXT_ASN_TITLE . "</b></font></td>
	  <td align=\"center\" width=\"5%\" class=\"normal\"><font color=\"#FFFFFF\"><b>" . TEXT_ASN_VIEWS . "</b></font></td>
	  <td align=\"center\" width=\"5%\" class=\"normal\"><font color=\"#FFFFFF\"><b>" . TEXT_ASN_REPLIES . "</b></font></td>
	  <td width=\"10%\">&nbsp;</td></tr>\n";

if ($this_page < $itemperpage) $looping = $this_page; else $looping = $itemperpage;

for ($i=1; $i<=$looping; $i++)	{
   //$counter = $end - $i;
   $data = mysql_fetch_row($query) or die("No topic found!");
   //$nomor = $counter + 1;

   if ((($i+1) % 2) == 0) $kolor = $admrowcolor1; else $kolor = $admrowcolor2;
   $tgl = substr($data[3],8,2) . "-" . substr($data[3],5,2) . "-" . substr($data[3],0,4);

   echo "<tr bgcolor=\"$kolor\">";
   echo "<td align=\"center\"><input type=\"checkbox\" name=\"check[]\" value=\"$data[0]\"></td>";
   echo "<td valign=\"top\" class=\"normal\">$tgl<br>";

   $hasil_user = tep_db_query("SELECT * FROM " . TABLE_ASNFORUM_POST . " WHERE topic_id=$data[0] ORDER BY post_time ASC");
   $data_user = mysql_fetch_row($hasil_user) or die("No posting found!");
   $user = $data_user[2];
   $user_mail = $data_user[4];
   $user_web = $data_user[5];

   echo TEXT_ASN_BY . " <b>$user</b><br>";
   if (!$user_mail == "") echo "<a href=\"mailto:$user_mail\"><img src=\"images/sym_email.gif\" border=\"0\"></a>  ";
   if ($user_web <> "" AND $user_web <> "http://") echo "<a href=\"$user_web\" target=\"_blank\"><img src=\"images/sym_www.gif\" border=\"0\"></a>";

   echo "</td>";

   echo "<td class=\"normal\"><a href=\"" . tep_href_link('asnf_admin.php', "action=browpost&id=$data[0]") . "\"><b>$data[1]</b></a><br>";

   $hasil_post = tep_db_query("SELECT * FROM " . TABLE_ASNFORUM_POST . " WHERE topic_id=$data[0] ORDER BY post_time ASC");
   $data_post = mysql_fetch_row($hasil_post) or die("No records found!");
   $hasil_text = tep_db_query("SELECT post_id, SUBSTRING_INDEX(post_text,' ',20) FROM " . TABLE_ASNFORUM_POSTEXT . " WHERE post_id=$data_post[0]");
   $data_text = mysql_fetch_row($hasil_text) or die("No records found!");

   $kontet = smile($data_text[1]);
   $kontet = auto_url($kontet);
   echo  "$kontet ...</td>";

   echo     "<td align=\"center\" class=\"normal\"><b>$data[4]</b></td>
             <td align=\"center\" class=\"normal\"><b>$data[5]</b></td>
             <td align=\"center\" class=\"normal\"><a href=\"" . tep_href_link('asnf_admin.php',"action=edittopic&id=$data[0]&start=$start") . "\">" . TEXT_ASN_EDIT . "</a></td></tr>";
}

   echo "
   </table>
   <br>
   <table width=\"$tablewidth\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
    <tr>
      <td>
        <input type=\"submit\" value=\"" . TEXT_ASN_DELETE_RECORD . "\">
	  </td>
      <td align=\"right\" valign=\"top\">
        <font size=\"1\" face=\"Verdana, Arial, Helvetica, sans-serif\">" . TEXT_ASN_TOTAL_TOPICS . " : <font color=\"#ff0000\">$all_record</font></font>
      </td>
    </tr>
  </table>
 </form>
   ";
}

//#########################################################################################################################//
//  B R O W S E   P O S T I N G
//#########################################################################################################################//

function browpost() {
   global $itemperpage, $start, $tablewidth, $admheadercolor, $admrowcolor1, $admrowcolor2, $id;

   if (!isset($start) OR ($start == "")) $start = 0 ;
   $hasil_topic = tep_db_query("SELECT * FROM " . TABLE_ASNFORUM_TOPIC . " WHERE topic_id=$id");
   $data_topic = mysql_fetch_row($hasil_topic) or die("No topic found!");

   $index_url = tep_href_link('asnf_admin.php', "action=browpost&id=$id");
   $query_jumlah = tep_db_query("SELECT count(*) FROM " . TABLE_ASNFORUM_POST . " WHERE topic_id=$id");
   $data_jumlah = mysql_fetch_row($query_jumlah);
   $all_record = $data_jumlah[0];

   $query = tep_db_query("SELECT * FROM " . TABLE_ASNFORUM_POST . " WHERE topic_id=$id ORDER BY post_time LIMIT $start, $itemperpage");
   $this_page = mysql_num_rows($query) or die("No post records found!");

   echo "
     <form method=\"get\" action=\"" . tep_href_link('asnf_admin.php') . "\">
	 <input type=\"hidden\" name=\"action\" value=\"delpost\">
	 <input type=\"hidden\" name=\"id\" value=\"$id\">
	 <input type=\"hidden\" name=\"start\" value=\"$start\">";

   echo"<table width=\"$tablewidth\" border=\"0\" cellspacing=\"1\" cellpadding=\"2\">
          <tr><td class=\"normal\" align=\"center\">";
   $paging = generate_pagination($index_url, $all_record, $itemperpage, $start);
   echo "<a href=\"" .tep_href_link('asnf_admin.php') . "\">" . TEXT_ASN_GOTO . "</a>";
   if (!empty($paging)) echo " | ";
   echo $paging;
   echo "</td></tr></table>";

   echo "
	    <img height=\"5\" src=\"images/space.gif\" width=\"$tablewidth\"><br>
        <table width=\"$tablewidth\" border=\"0\" cellspacing=\"1\" cellpadding=\"5\" bgcolor=\"white\">
          <tr bgcolor=\"$admheadercolor\">
            <td align=\"center\" width=\"5%\">&nbsp;</td>
			<td width=\"20%\" class=\"normal\"><b><font color=\"#FFFFFF\">" . TEXT_ASN_POSTER . "</font></b></td>
            <td width=\"65%\" class=\"normal\"><b><font color=\"#FFFFFF\">$data_topic[1]</font></b></td>
			<td width=\"10%\">&nbsp;</td>
          </tr>\n";

if ($this_page < $itemperpage) $looping = $this_page; else $looping = $itemperpage;

for ($i=1; $i<=$looping; $i++)	{
   $data = mysql_fetch_row($query) or die("No records found!");

   if ((($i+1) % 2) == 0) $kolor = $admrowcolor1; else $kolor = $admrowcolor2;
   $tgl = substr($data[3],8,2) . "-" . substr($data[3],5,2) . "-" . substr($data[3],0,4);

   echo "<tr bgcolor=\"$kolor\">";
   if ($i <> 1)
	   echo "<td align=\"center\"><input type=\"checkbox\" name=\"check[]\" value=\"$data[0]\"></td>";
       else echo "<td></td>";
   echo "<td valign=\"top\" class=\"normal\">$tgl<br>";

   $user = $data[2];
   $user_mail = $data[4];
   $user_web = $data[5];

   echo TEXT_ASN_BY . " <b>$user</b><br>";
   if (!$user_mail == "") echo "<a href=\"mailto:$user_mail\"><img src=\"images/sym_email.gif\" border=\"0\"></a>  ";
   if ($user_web <> "" AND $user_web <> "http://") echo "<a href=\"$user_web\" target=\"_blank\"><img src=\"images/sym_www.gif\" border=\"0\"></a>";

   echo "</td>";
   echo "<td class=\"normal\">";

   $hasil_text = tep_db_query("SELECT * FROM " . TABLE_ASNFORUM_POSTEXT . " WHERE post_id=$data[0]");
   $data_text = mysql_fetch_row($hasil_text) or die("No records found!");

   $kontet = smile($data_text[1]);
   $kontet = auto_url($kontet);
   echo  "$kontet</td>";
   echo "<td align=\"center\" class=\"normal\"><a href=\"" . tep_href_link('asnf_admin.php',"action=editpost&topicid=$id&id=$data[0]&start=$start") . "\">" . TEXT_ASN_EDIT . "</a></td></tr>";
}

   echo "
   </table>
   <br>
   <table width=\"$tablewidth\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
    <tr>
      <td>
        <input type=\"submit\" value=\"" . TEXT_ASN_DELETE_RECORD . "\">
	  </td>
      <td align=\"right\" valign=\"top\">
        <font size=\"1\" face=\"Verdana, Arial, Helvetica, sans-serif\">" . TEXT_ASN_TOTAL_POSTING . " : <font color=\"#ff0000\">$all_record</font></font>
      </td>
    </tr>
  </table>
  </form>
   ";
}


//#########################################################################################################################//
//  E D I T   T O P I C
//#########################################################################################################################//

function edittopic() {
	global $start, $id;

$query = tep_db_query("SELECT * FROM " . TABLE_ASNFORUM_TOPIC . " WHERE topic_id=$id");
$data = mysql_fetch_row($query);

echo "
   <center><form method=\"get\" action=\"" . tep_href_link('asnf_admin.php') . "\">
   <input type=\"hidden\" name=\"action\" value=\"doedittopic\">
   <input type=\"hidden\" name=\"id\" value=\"$id\">
   <input type=\"hidden\" name=\"start\" value=\"$start\">
   <table width=\"400\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\">
      <tr bgcolor=\"#f0f0f0\">
        <td><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">" . TEXT_ASN_ID . "</font></td>
        <td><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">$data[0]</font></td>
      </tr>
      <tr>
        <td><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">" . TEXT_ASN_DATE . "</font></td>
        <td><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">$data[3]</font></td>
      </tr>
      <tr bgcolor=\"#f0f0f0\">
        <td><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">" . TEXT_ASN_VIEWS . "</font></td>
        <td><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">$data[4]</font></td>
      </tr>
      <tr>
        <td><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">" . TEXT_ASN_REPLIES . "</font></td>
        <td><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">$data[5]</font></td>
      </tr>
      <tr bgcolor=\"#f0f0f0\">
        <td><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">" . TEXT_ASN_POSTER . "</font></td>
        <td><input type=\"text\" name=\"poster\" size=\"30\" value=\"$data[2]\"></td>
      </tr>
      <tr>
        <td><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">" . TEXT_ASN_TITLE . "</font></td>
        <td><input type=\"text\" name=\"title\" size=\"40\" value=\"$data[1]\"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type=\"submit\" value=\"" . TEXT_ASN_UPDATE . "\"> <font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\"><a href=\"" . tep_href_link('asnf_admin.php', "action=browtopic&start=$start") . "\">" . TEXT_ASN_BACK . "</a></font></td>
      </tr>
    </table>
    </form><p>&nbsp;</p>";
}


//#########################################################################################################################//
//  D O   E D I T   T O P I C
//#########################################################################################################################//

function doedittopic() {
	global $id, $poster, $title, $start;

if (trim($poster)=="" OR trim($title)=="") {
   erro(TEXT_ASN_REQUIRED);
   }

$query = tep_db_query("UPDATE " . TABLE_ASNFORUM_TOPIC . " SET topic_poster='$poster', topic_title='$title' WHERE topic_id=$id");

if (mysql_affected_rows() <> 0) {
	echo "<span class=\"error\">" . TEXT_ASN_TOPIC_UPDATED . "</span>";
    } else {
		echo "<span class=\"error\">" . TEXT_ASN_TOPIC_UPDATED_ERR . "</span>";
}

$urut = tep_db_query("SELECT * FROM " . TABLE_ASNFORUM_POST . " WHERE topic_id = $id ORDER BY post_time ASC");
$data_temp = mysql_fetch_row($urut);
$id_temp = $data_temp[0];
$update_name = tep_db_query("UPDATE " . TABLE_ASNFORUM_POST . " SET poster='$poster' WHERE post_id=$id_temp");

echo "<span class=\"normal\"><br><br><a href=\"" . tep_href_link('asnf_admin.php',"start=$start") . "\">" . TEXT_ASN_BACK . "</a></span><br><br><br>";

}

//#########################################################################################################################//
//  E D I T   P O S T
//#########################################################################################################################//

function editpost() {
   global $topicid, $start, $id;

$topic = tep_db_query("SELECT * FROM " . TABLE_ASNFORUM_TOPIC . " WHERE topic_id=$topicid");
$data_topic = mysql_fetch_row($topic);

$query = tep_db_query("SELECT * FROM " . TABLE_ASNFORUM_POST . " WHERE post_id=$id");
$data = mysql_fetch_row($query);

$get_post_text = tep_db_query("SELECT * FROM " . TABLE_ASNFORUM_POSTEXT . " WHERE post_id=$id");
$data_post_text = mysql_fetch_row($get_post_text);

echo "
   <center><form method=\"post\" action=\"" . tep_href_link('asnf_admin.php') . "\">
   <input type=\"hidden\" name=\"action\" value=\"doeditpost\">
   <input type=\"hidden\" name=\"id\" value=\"$id\">
   <input type=\"hidden\" name=\"topicid\" value=\"$topicid\">
   <input type=\"hidden\" name=\"start\" value=\"$start\">
   <table width=\"400\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\">
      <tr>
        <td><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">" . TEXT_ASN_TOPIC . "</font></td>
        <td><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"blue\"><b>$data_topic[1]</b></font></td>
      </tr>
      <tr bgcolor=\"#f0f0f0\">
        <td><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">" . TEXT_ASN_ID . "</font></td>
        <td><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">$data[0]</font></td>
      </tr>
      <tr>
        <td><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">". TEXT_ASN_DATE . "</font></td>
        <td><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">$data[3]</font></td>
      </tr>
      <tr bgcolor=\"#f0f0f0\">
        <td><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">" . TEXT_ASN_POSTER . "</font></td>
        <td><input type=\"text\" size=\"35\" name=\"poster\" value=\"$data[2]\"></td>
      </tr>
      <tr>
        <td><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">" . TEXT_ASN_EMAIL . "</font></td>
        <td><input type=\"text\" size=\"35\" name=\"email\" value=\"$data[4]\"></td>
      </tr>
      <tr bgcolor=\"#f0f0f0\">
        <td><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">" . TEXT_ASN_WWW . "</font></td>
        <td><input type=\"text\" size=\"35\" name=\"web\" value=\"$data[5]\"></td>
      </tr>
      <tr>
        <td><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">" . TEXT_ASN_TEXT . "</font></td>
        <td><textarea  name=\"postext\" rows=\"10\" cols=\"35\">$data_post_text[1]</textarea></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type=\"submit\" value=\"" . TEXT_ASN_UPDATE . "\"> <font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\"><a href=\"" . tep_href_link('asnf_admin.php', "action=browpost&id=$topicid&start=$start") . "\">" . TEXT_ASN_BACK2 . "</a></font></td>
      </tr>
    </table>
    </form><hr noshade size=\"1\"></center>";
}

//#########################################################################################################################//
//  D O   E D I T   P O S T
//#########################################################################################################################//

function doeditpost() {
	global $topicid, $poster, $email, $web, $postext, $start, $id;

if (trim($poster)=="" or trim($email)=="" or trim($postext)=="") {
   erro(TEXT_ASN_REQUIRED);
   }

if (trim($web)=="http://") $web = "";
check_email($email);

$query_post_string = "UPDATE " . TABLE_ASNFORUM_POST . " SET poster='$poster', email='$email', web='$web' WHERE post_id=$id";
$post_string = tep_db_query($query_post_string);
if (mysql_affected_rows() <> 0) $edit1 = 1; else $edit1 = 0;

$query_postext_string = "UPDATE " . TABLE_ASNFORUM_POSTEXT . " SET post_text='$postext' WHERE post_id=$id";
$postext_string = tep_db_query($query_postext_string);
if (mysql_affected_rows() <> 0) $edit2 = 1; else $edit2 = 0;

/*if (($edit1 == 1) AND ($edit2 == 1)) {
	echo "<span class=\"error\">Update Success! Both tables affected.</span><br>";
    } elseif (($edit1 == 1) OR ($edit2 == 1)) {
	   echo "<font face=\"$default_font\" size=2>Update Success! Only 1 table affected!</font>";
	   } else {
	      echo "<font face=\"$default_font\" size=2>No Records Affected!</font>";
} */

echo "<span class=\"error\">" . TEXT_ASN_TOPIC_UPDATED2 . "</span>";

$check_topic = tep_db_query("SELECT * FROM " . TABLE_ASNFORUM_POST . " WHERE topic_id=$topicid ORDER BY post_time ASC");
$data_check = mysql_fetch_row($check_topic);
$id_check = $data_check[0];
if ($id_check == $id) {
   $update_poster_query = "UPDATE " . TABLE_ASNFORUM_TOPIC . " SET topic_poster='$poster' WHERE topic_id=$topicid";
   $update_poster = tep_db_query($update_poster_query);
}

echo "<br><br><span class=\"normal\"><a href=\"" . tep_href_link('asnf_admin.php', "action=browpost&id=$topicid&start=$start") . "\">" . TEXT_ASN_BACK2 . "</a></span><br><br><br>";

}

//#########################################################################################################################//
//  D E L E T E   T O P I C
//#########################################################################################################################//

function deltopic() {
   global $id, $check, $start;

   $posting_deleted = 0;
   $deleted = 0;
   for ($i=0; $i<=count($check); $i++) {
      if ($check[$i] <> "" ) {
		  $hapus = @tep_db_query("DELETE FROM " . TABLE_ASNFORUM_TOPIC  . " WHERE topic_id=$check[$i]");
		  if (mysql_affected_rows() <> 0) {
			 $deleted++;
		     echo "<font face=\"verdana\" size=1 color=red>" . sprintf(TEXT_ASN_DELETE_TOPICS_1, "<b>$check[$i]</b>") . "</font><br>";

			 $pilih_post = tep_db_query("SELECT post_id FROM " . TABLE_ASNFORUM_POST . " WHERE topic_id=$check[$i]");
			 if (tep_db_num_rows($pilih_post) <> 0) {
		        while ($data = mysql_fetch_row($pilih_post)) {
			       $hapus1 = tep_db_query("DELETE FROM " . TABLE_ASNFORUM_POSTEXT . " WHERE post_id=$data[0]");
				   if(mysql_affected_rows() <> 0) {
					  $posting_deleted++;
			          echo "<font face=\"verdana\" size=1>" . sprintf(TEXT_ASN_DELETE_POSTING_1, "<b>$data[0]</b>") . "</font><br>";
				   }
		        }
			    $hapus2 = tep_db_query("DELETE FROM " . TABLE_ASNFORUM_POST . " WHERE topic_id=$check[$i]");
			 }
		  }
	  }
   }

   if ($deleted == 0) echo "<br><br><span class=\"error\">" . TEXT_ASN_DELETE_TOPICS_2 . "</span>";
      else
	    echo "<br><br><span class=\"error\">" . sprintf(TEXT_ASN_DELETE_TOPICS_3, "<font color=blue><b>$deleted</b></font>") . "</span>";
   if ($posting_deleted == 0) echo "<br><span class=\"error\">No posting deleted!</span>";
      else
	    echo "<br><span class=\"error\">" . sprintf(TEXT_ASN_DELETE_POSTING_3, "<font color=blue><b>$posting_deleted</b></font>") . "</span>";

   echo "<br><br><span class=\"normal\"><a href=\"" . tep_href_link('asnf_admin.php', "start=$start") . "\">" . TEXT_ASN_BACK . "</a></span><br><br></font>";
}

//#########################################################################################################################//
//  D E L E T E   P O S T
//#########################################################################################################################//

function delpost() {
   global $id, $check, $start;

   $posting_deleted = 0;
   for ($i=0; $i<=count($check); $i++) {
	   if ($check[$i] <> "" ) {
		  $hapus = @tep_db_query("DELETE FROM " . TABLE_ASNFORUM_POST. " WHERE post_id=$check[$i]");
		  $hapus1 = @tep_db_query("DELETE FROM " . TABLE_ASNFORUM_POSTEXT . " WHERE post_id=$check[$i]");
		  if (mysql_affected_rows() <> 0) {
			  $posting_deleted++;
			  echo "<span class=\"normal\">" . sprintf(TEXT_ASN_DELETE_POSTING_1 , "<b>$check[$i]</b>") . "</span><br>";
			  $kurangin = tep_db_query("UPDATE " . TABLE_ASNFORUM_TOPIC . " SET topic_replies=topic_replies - 1 WHERE topic_id=$id");
			  $reply = tep_db_query("SELECT topic_replies FROM " . TABLE_ASNFORUM_TOPIC . " WHERE topic_id=$id");
			  $reply_data = mysql_fetch_row($reply);
			  if ($reply_data[0] < 0) {
			  	  $delete_topic = tep_db_query("DELETE FROM " . TABLE_ASNFORUM_TOPIC . " WHERE topic_id=$id");
		      }
		  }
	   }
   }

   $check_last = tep_db_query("SELECT topic_last_post_id FROM " . TABLE_ASNFORUM_TOPIC . " WHERE topic_id=$id");
   $data = @mysql_fetch_row($check_last) or die("The topic is not exist or have been deleted!");
   $check_post = tep_db_query("SELECT post_id FROM " . TABLE_ASNFORUM_POST . " WHERE topic_id=$id ORDER BY post_time DESC");
   $data = @mysql_fetch_row($check_post) or die("No posting in this topic");
   $update_las = tep_db_query("UPDATE " . TABLE_ASNFORUM_TOPIC . " SET topic_last_post_id = $data[0] WHERE topic_id = $id");

   if ($posting_deleted == 0)
	  echo "<br><span class=\"error\">" . TEXT_ASN_DELETE_POSTING_2 . "</span>";
      else
		  echo "<br><span class=\"error\">" . sprintf(TEXT_ASN_DELETE_POSTING_3, "<font color=blue><b>$posting_deleted</b></font>") . "</span>";

   echo "<br><br><span class=\"normal\"><a href=\"" . tep_href_link('asnf_admin.php', "action=browpost&id=$id&start=$start") . "\">" . TEXT_ASN_BACK2 . "</a></span><br>";
   echo "<br><br>";
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
//#########################################################################################################################//
//  M A I N   P R O G R A M
//#########################################################################################################################//

include("includes/asnf_header.php");

switch ($action) {

case "":
   browtopic();
   break;

case "browtopic":
   browtopic();
   break;

case "edittopic":
   edittopic();
   break;

case "doedittopic":
   doedittopic();
   break;

case "deltopic":
   deltopic();
   break;

case "browpost":
   browpost();
   break;

case "editpost":
   editpost();
   break;

case "doeditpost":
   doeditpost();
   break;

case "delpost":
   delpost($id);
   break;

}

include("includes/asnf_footer.php");
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
