<?php
/*
___________________________________________________

project : asn forum
file	: reply.php
author	: asn - script@tourdebali.com
modifed for OSC : Pawel Rychlewski (prychl@o2.pl)
___________________________________________________

*/

require_once("includes/application_top.php");
require_once("includes/asnf_config.php");
require_once("includes/asnf_lib.php");

$IP = $_POST["verify"];
$pecah = explode(".",$REMOTE_ADDR);
$pecah3 = $pecah[0] . $pecah[1] . $pecah[2] . tep_session_id($HTTP_GET_VARS[tep_session_name()]);

/*
if ($IP != md5($floodpass.$pecah3)) {
   exit();
}
*/

if (trim($nama)=="" or trim($topictext)=="") {
  // erro("Some fields is still blank. Please fill in all field with * mark");
     tep_redirect(tep_href_link('asnf_viewtopic.php?'."id=$id" .'&error=empty_field',"",'SSL'));
   }

if (trim($homepage)=="http://") $homepage = "";

//check_email($email);

$nama = htmlspecialchars($nama);
$email = htmlspecialchars($email);
$homepage = htmlspecialchars($homepage);

$test_comment = explode(" ",$topictext);
$jmltest = count($test_comment);

for ($t=0; $t<$jmltest; $t++) {
   if (strlen(trim($test_comment[$t])) > 100) {
      //erro("The maximum char per word is 100");
      tep_redirect(tep_href_link('asnf_viewtopic.php?'."id=$id".'&error=message_length','','SSL'));
   }
}

$topictext= mysql_real_escape_string($topictext);
$topictext = htmlspecialchars($topictext);
$topictext = str_replace("\n","<BR>",$topictext);


$tgl = htmlspecialchars($reply_date);

$ins1 = tep_db_query("INSERT INTO " . TABLE_ASNFORUM_POST . " VALUES(' ', '$id', '$nama', '$tgl', '$email', '$homepage')");
$this_post = mysql_insert_id();
$ins2 = tep_db_query("INSERT INTO " . TABLE_ASNFORUM_POSTEXT . " VALUES($this_post, '$topictext')");
$ins2 = tep_db_query("UPDATE " . TABLE_ASNFORUM_TOPIC . " SET topic_replies=topic_replies+1, topic_last_post_id='$this_post' WHERE topic_id=$id");

$url = "Location: asnf_viewtopic.php?id=$id";
tep_redirect(tep_href_link('asnf_index.php', "id=$id",'SSL'));
//header($url);

?>