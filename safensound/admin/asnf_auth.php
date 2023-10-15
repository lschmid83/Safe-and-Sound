<?php

/*
___________________________________________________

project : asn forum version 1.5
file	: auth.php
author	: asn - webmaster@tourdebali.com
date	: february 16th, 2004
note	: copyright 2004 by asn
modifed for OSC : Pawel Rychlewski (prychl@o2.pl)
___________________________________________________

*/

require_once("includes/application_top.php");

if (($vadmin == "") AND ($vpassword == "")) {
   //header("Location: asnf_login.php");
   tep_redirect(tep_href_link('asnf_login.php'),'','SSL');
   exit();
}

if (($vadmin == $admin) AND ($vpassword == $password)) {

   //login succes
   tep_session_register("vadmin"); 
   tep_session_register("vpassword"); 

   } else {

   //failed to login
   //header("Location: asnf_login.php?error=1");
   tep_redirect(tep_href_link('asnf_index.php','error=1','SSL'));
   exit();
}

?>