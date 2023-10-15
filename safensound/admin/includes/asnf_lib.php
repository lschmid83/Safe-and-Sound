<?php
//require_once('application_top.php');
/*
___________________________________________________

project : asn forum version 2.0
file	: lib.php
author	: asn - script@tourdebali.com
modifed for OSC : Pawel Rychlewski (prychl@o2.pl) 
___________________________________________________

*/


/* *******************************************************************************
 * Original from PHPMyAdmin 2.3.0 
 * $Id: grab_globals.lib.php,v 1.7 2002/07/06 22:10:32 loic1 Exp $ 
 * This library grabs the names and values of the variables sent or posted to a
 * script in the '$HTTP_*_VARS' arrays and sets simple globals variables from
 * them. It does the same work for the $PHP_SELF variable.
 *
 * loic1 - 2001/25/11: use the new globals arrays defined with php 4.1+
 **********************************************************************************/

if (!defined('PMA_GRAB_GLOBALS_INCLUDED')) {
    define('PMA_GRAB_GLOBALS_INCLUDED', 1);

    if (!empty($_GET)) {
        extract($_GET, EXTR_OVERWRITE);
    } else if (!empty($HTTP_GET_VARS)) {
        extract($HTTP_GET_VARS, EXTR_OVERWRITE);
    } // end if

    if (!empty($_POST)) {
        extract($_POST, EXTR_OVERWRITE);
    } else if (!empty($HTTP_POST_VARS)) {
        extract($HTTP_POST_VARS, EXTR_OVERWRITE);
    } // end if

    if (!empty($_FILES)) {
        while (list($name, $value) = each($_FILES)) {
            $$name = $value['tmp_name'];
        }
    } else if (!empty($HTTP_POST_FILES)) {
        while (list($name, $value) = each($HTTP_POST_FILES)) {
            $$name = $value['tmp_name'];
        }
    } // end if

    if (!empty($_SERVER) && isset($_SERVER['PHP_SELF'])) {
        $PHP_SELF = $_SERVER['PHP_SELF'];
    } else if (!empty($HTTP_SERVER_VARS) && isset($HTTP_SERVER_VARS['PHP_SELF'])) {
        $PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'];
    } // end if

} // $__PMA_GRAB_GLOBALS_LIB__


/********************************************* 
 * Simple email validation
 *********************************************/

function check_email($email) {
   if(!ereg("([[:alnum:]\.\-]+)(\@[[:alnum:]\.\-]+\.+)", $email)) {
      erro("Invalid email address!");
   }
}




/********************************************* 
 * Convert smiley code into emoticon
 *********************************************/

function smile($message) {
   global $url_smiles;
   $message = ' ' . $message;
   if ($getsmiles = tep_db_query("SELECT *, length(code) as length FROM " . TABLE_ASNFORUM_SMILE . " ORDER BY length DESC")) {
      while ($smiles = tep_db_fetch_array($getsmiles)) {
			$smile_code = preg_quote($smiles['code']);
			$smile_code = str_replace('/', '//', $smile_code);
			$message = preg_replace("/([\n\\ \\.])$smile_code/si", '\1<IMG SRC="' . 'images' . '/' . $smiles['smile_url'] . '">', $message);
      }
   }
   
   $message = substr($message, 1);
   return($message);
}




/****************************************************************************************** 
 * convert url text become clickable
 * original from PHPBB
 ******************************************************************************************/

function auto_url($text) {
	$ret = " " . $text;
	$ret = preg_replace("#([\n ])([a-z]+?)://([^, \n\r]+)#i", "\\1<a href=\"\\2://\\3\" target=\"_blank\">\\2://\\3</a>", $ret);
	$ret = preg_replace("#([\n ])www\.([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[^, \n\r]*)?)#i", "\\1<a href=\"http://www.\\2.\\3\\4\" target=\"_blank\">www.\\2.\\3\\4</a>", $ret);
	$ret = preg_replace("#([\n ])([a-z0-9\-_.]+?)@([^, \n\r]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $ret);
	$ret = substr($ret, 1);
	return($ret);
}




/****************************************************************************************** 
 * Generate paging navigation. 
 * Original from PHPBB with some modifications to make them more simple
 ******************************************************************************************/

function generate_pagination($base_url, $num_items, $per_page, $start_item, $add_prevnext_text = TRUE) {
	$total_pages = ceil($num_items/$per_page);
	if ( $total_pages == 1 ) {
		return "";
	}
	$on_page = floor($start_item / $per_page) + 1;
	$page_string = "";
	if ( $total_pages > 10 ) {
		$init_page_max = ( $total_pages > 3 ) ? 3 : $total_pages;
		for($i = 1; $i < $init_page_max + 1; $i++) {
			$page_string .= ( $i == $on_page ) ? "<b>" . $i . "</b>" : "<a href=\"" . $base_url . "&start=" . ( ( $i - 1 ) * $per_page )  . "\">" . $i . "</a>";
			if ( $i <  $init_page_max ) {
				$page_string .= ", ";
			}
		}
		if ( $total_pages > 3 ) {
			if ( $on_page > 1  && $on_page < $total_pages ) {
				$page_string .= ( $on_page > 5 ) ? " ... " : ", ";
				$init_page_min = ( $on_page > 4 ) ? $on_page : 5;
				$init_page_max = ( $on_page < $total_pages - 4 ) ? $on_page : $total_pages - 4;

				for($i = $init_page_min - 1; $i < $init_page_max + 2; $i++) {
					$page_string .= ($i == $on_page) ? "<b>" . $i . "</b>" : "<a href=\"" . $base_url . "&start=" . ( ( $i - 1 ) * $per_page )  . "\">" . $i . "</a>";
					if ( $i <  $init_page_max + 1 ) {
						$page_string .= ", ";
					}
				}
				$page_string .= ( $on_page < $total_pages - 4 ) ? " ... " : ", ";
			} 
			else {
				$page_string .= " ... ";
			}
			for($i = $total_pages - 2; $i < $total_pages + 1; $i++) {
				$page_string .= ( $i == $on_page ) ? "<b>" . $i . "</b>"  : "<a href=\"" . $base_url . "&start=" . ( ( $i - 1 ) * $per_page )  . "\">" . $i . "</a>";
				if( $i <  $total_pages ) {
					$page_string .= ", ";
				}
			}
		}
	}
	else {
		for($i = 1; $i < $total_pages + 1; $i++) {
			$page_string .= ( $i == $on_page ) ? "<b>" . $i . "</b>" : "<a href=\"" . $base_url . "&start=" . ( ( $i - 1 ) * $per_page )  . "\">" . $i . "</a>";
			if ( $i <  $total_pages ) {
				$page_string .= ", ";
			}
		}
	}
	if ( $add_prevnext_text ) {
		if ( $on_page > 1 ) {
			$page_string = " <a href=\"" . $base_url . "&start=" . ( ( $on_page - 2 ) * $per_page )  . "\">" . PREVNEXT_BUTTON_PREV . "</a>&nbsp;&nbsp;" . $page_string;
		}

		if ( $on_page < $total_pages ) {
			$page_string .= "&nbsp;&nbsp;<a href=\"" . $base_url . "&start=" . ( $on_page * $per_page )  . "\">" . PREVNEXT_BUTTON_NEXT . "</a>";
		}
	}

	$page_string = PREVNEXT_TITLE_PAGE . ': ' . $page_string;
	return $page_string;
}


/********************************************* 
 * Error and Message handling 
 * Not so cool coding, but IT WORKS!
 *********************************************/

function erro($message) {
global $tipe;

include("header.php");

if ($tipe == no) $title = "MESSAGE"; else $title = "ERROR!";
echo "  
        <table width=\"300\" border=\"0\" cellspacing=\"0\" cellpadding=\"10\" class=\"asnf_replyform\" bgcolor=\"#f0f0f0\">
          <tr>
            <td align=\"center\" class=\"asnf_normal\"><font size=\"3\" color=\"#CC0000\"><b>$title</b></font><br><br>
              $message
              </td>
          </tr>
        </table>
        <br>
        <br>
        <table width=\"250\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
          <tr> 
            <td> 
              <table border=\"0\" cellspacing=\"0\" cellpadding=\"1\" bgcolor=\"#CCCCCC\" width=\"125\">
                <tr> 
                  <td> 
                    <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" bgcolor=\"#f5f5f5\">
                      <tr> 
                        <td class=\"asnf_normal\" align=\"center\"><b>&nbsp;&nbsp;&nbsp;<a href=\"javascript:window.history.back()\" class=\"asnf_none\">Back</a>&nbsp;&nbsp;</b></td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
            <td><img src=\"images/space.gif\" width=\"5\" height=\"10\"></td>
            <td> 
              <table border=\"0\" cellspacing=\"0\" cellpadding=\"1\" bgcolor=\"#CCCCCC\" width=\"125\">
                <tr> 
                  <td> 
                    <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" bgcolor=\"#f5f5f5\">
                      <tr> 
                        <td class=\"asnf_normal\" align=\"center\"><b>&nbsp;&nbsp;&nbsp;<a href=\"" . tep_href_link(asnf_index.php) . "\" class=\"asnf_none\">Home</a>&nbsp;&nbsp;&nbsp;</b></td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table><br><br><br>";
include("footer.php");
die();
}

function makebutton($xtext, $xlink, $xwidth) {
echo"
  <table border=\"0\" cellspacing=\"0\" cellpadding=\"1\" bgcolor=\"#CCCCCC\" width=\"$xwidth\">
	<tr> 
	  <td> 
		<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" bgcolor=\"#f5f5f5\">
		  <tr> 
			<td class=\"asnf_normal\" align=\"center\"><b><a href=\"" . tep_href_link($xlink) . "\" class=\"asnf_none\">$xtext</a></b></td>
		  </tr>
		</table>
	  </td>
	</tr>
  </table>";
}

$b2smiliestrans = array(
	':-?'		=> 'icon_confused.gif',
	'8)'		=> 'icon_cool.gif',
	':cry:'		=> 'icon_cry.gif',
	':oops:'	=> 'icon_redface.gif',
	':evil:'	=> 'icon_evil.gif',
	':))'		=> 'icon_lol.gif',
	':x'		=> 'icon_mad.gif',
	':p'		=> 'icon_razz.gif',
	':roll:'	=> 'icon_rolleyes.gif',
	':('		=> 'icon_frown.gif',
	':)'		=> 'icon_smile.gif',
	':o'		=> 'icon_eek.gif',
	':D'		=> 'icon_biggrin.gif',
	':wink:'	=> 'icon_wink.gif'
);

# sorts the smilies' array
if (!function_exists('smiliescmp')) {
	function smiliescmp ($a, $b) {
	   if (strlen($a) == strlen($b)) {
		  return strcmp($a, $b);
	   }
	   return (strlen($a) > strlen($b)) ? -1 : 1;
	}
}
uksort($b2smiliestrans, 'smiliescmp');

# generates smilies' search & replace arrays
foreach($b2smiliestrans as $smiley => $img) {
	$b2_smiliessearch[] = $smiley;
	$smiley_masked = '';
	for ($i = 0; $i < strlen($smiley); $i = $i + 1) {
		$smiley_masked .= substr($smiley, $i, 1).chr(160);
	}
	$b2_smiliesreplace[] = "<img src='images/$img' alt='$smiley_masked' />";
}

function convert_smilies($content) {
	global $smilies_directory, $use_smilies;
	global $b2_smiliessearch, $b2_smiliesreplace;
	if ($use_smilies) {
		$content = str_replace($b2_smiliessearch, $b2_smiliesreplace, $content);
	}
	return ($content);
}
?>