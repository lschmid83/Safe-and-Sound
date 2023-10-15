<?php
/*=======================================================================*\
|| #################### //-- SCRIPT INFO --// ########################### ||
|| #	Script name: meta_tags.php                                      # ||
|| #	Contribution: cDynamic Meta Tags                                # ||
|| #	Version: 1.5.2                                                  # ||
|| #	Date: April 13 2009                                             # ||
|| # ------------------------------------------------------------------ # ||
|| #################### //-- COPYRIGHT INFO --// ######################## ||
|| #	Copyright (C) 2005 Chris LaRocque								# ||
|| #																	# ||
|| #	This script is free software; you can redistribute it and/or	# ||
|| #	modify it under the terms of the GNU General Public License		# ||
|| #	as published by the Free Software Foundation; either version 2	# ||
|| #	of the License, or (at your option) any later version.			# ||
|| #																	# ||
|| #	This script is distributed in the hope that it will be useful,	# ||
|| #	but WITHOUT ANY WARRANTY; without even the implied warranty of	# ||
|| #	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the	# ||
|| #	GNU General Public License for more details.					# ||
|| #																	# ||
|| #	Script is intended to be used with:								# ||
|| #	osCommerce, Open Source E-Commerce Solutions					# ||
|| #	http://www.oscommerce.com										# ||
|| #	Copyright (c) 2003 osCommerce									# ||
|| ###################################################################### ||
\*========================================================================*/

# Show Model in Title/Description
$show_model_in_title = false;  # no ' or "
$show_model_in_desc = false;
#  true or false

# Show Manufacturer in Title
$show_man_in_title = false;  # no ' or "
#  true or false

# Enter y=text to be removed from Manufacturers when using them for keywords
# enter in all lower case
$strip_man_array = array('inc.','co.','inc'); 

# Pages to use HEADING_TITLE for title
# Do not list pages w/ specific meta tags: (index.php, product_info.php, specials.php, products_new.php)
$heading_pages = array('contact_us.php', 'product_reviews.php');

define('META_FROM_MANU_AT_STORE', 'Izdelki proizvajalca %s @ %s');
define('META_FROM_MANU', 'proizvajalca %s');
define('META_AT_STORE', '@');
?>