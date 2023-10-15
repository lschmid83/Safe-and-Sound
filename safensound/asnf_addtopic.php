<?php
/*
___________________________________________________

project : asn forum
file	: addtopic.php
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


if (trim($nama)=="" or trim($topic=="") or trim($topictext)=="")
   tep_redirect(tep_href_link('asnf_newtopic.php?error=empty_field','','SSL'));

if (trim($homepage)=="http://") $homepage = "";

//check_email($email);

$nama = htmlspecialchars($nama);
$email = htmlspecialchars($email);
$homepage = htmlspecialchars($homepage);
$topic = htmlspecialchars($topic);
$topic= mysql_real_escape_string($topic);

$test_comment = explode(" ",$topictext);
$jmltest = count($test_comment);

for ($t=0; $t<$jmltest; $t++) {
   if (strlen(trim($test_comment[$t])) > 100) {
   	   tep_redirect(tep_href_link('asnf_newtopic.php?error=message_length','','SSL'));
      //erro("Maximum chars per word is 100!");
   }
}

$topictext= mysql_real_escape_string($topictext);
$topictext = htmlspecialchars($topictext);
$topictext = str_replace("\n","<BR>",$topictext);


$tgl = htmlspecialchars($reply_date);

$ins1 = tep_db_query("INSERT INTO " . TABLE_ASNFORUM_TOPIC . " VALUES (' ', '$topic', '$nama', '$tgl', '0', '0', '0')");
$topic_rnd = mysql_insert_id();
$ins2 = tep_db_query("INSERT INTO " . TABLE_ASNFORUM_POST . " VALUES(' ', '$topic_rnd', '$nama', '$tgl', '$email', '$homepage')");
$post_rnd = mysql_insert_id();
$ins3 = tep_db_query("INSERT INTO " . TABLE_ASNFORUM_POSTEXT . " VALUES($post_rnd, '$topictext')");
$ins4 = tep_db_query("UPDATE " . TABLE_ASNFORUM_TOPIC . " SET topic_last_post_id=$post_rnd WHERE topic_id = $topic_rnd");

$url = "Location: asnf_index.php";
//header($url);
tep_redirect(tep_href_link('asnf_index.php','','SSL'));



?>