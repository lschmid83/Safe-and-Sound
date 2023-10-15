<?php

/*
___________________________________________________

project : asn forum
file	: config.php
author	: asn - script@tourdebali.com
___________________________________________________

*/

$version = "2.0";

//---- General Option ----

$admin = "snsDavidW";
$password = "gateways";

//---- Layout configuration ----

$asnforum_title = "Asn Forum";
$itemperpage = 10;
$tablewidth = 620;			//minimum value is 600
$maxchar = 2000;			//maximum character for every posting to avoid flooding

//---original
//$headercolor = "#006699";
//$rowcolor1 = "#DEE3E7";
//$rowcolor2 = "#EFEFEF";

$headercolor = "#999999";
$rowcolor1 = "#e5e5e5";
$rowcolor2 = "#f0f0f0";

$admheadercolor = "#999999";
$admrowcolor1 = "#e5e5e5";
$admrowcolor2 = "#f0f0f0";

$REMOTE_ADDR=getenv('REMOTE_ADDR');
$floodpass = "flood";	//'second password', basic method to prevent spambots

?>