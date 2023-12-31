<?php
/*
  $Id: index.php,v 1.1 2003/06/11 17:37:59 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

// the following cPath references come from application_top.php
  $category_depth = 'top';
  if (isset($cPath) && tep_not_null($cPath)) {
    $categories_products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
    $cateqories_products = tep_db_fetch_array($categories_products_query);
    if ($cateqories_products['total'] > 0) {
      $category_depth = 'products'; // display products
    } else {
      $category_parent_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$current_category_id . "'");
      $category_parent = tep_db_fetch_array($category_parent_query);
      if ($category_parent['total'] > 0) {
        $category_depth = 'nested'; // navigate through the categories
      } else {
        $category_depth = 'products'; // category has no products, but display the 'no products' message
      }
    }
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
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
<meta name="verify-v1" content="KPfeqNvZOb/UW+FYBFN/OZHVC/5Vh+95SOiW+2NRR7g=" >

<style type="text/css" >
td.welcome_box
{
	width:576px;
	height:204px;

	padding-left:15px;
	padding-top:12px;
	background-repeat:no-repeat;
	color:#232323;
	vertical-align:top;
}

td.welcome_box a
{
color:#245D93;
}

td.welcome_box a:hover
{
color:#CC3300;
}


td.welcome_box h1
{
	font-size:10pt;
	color: #245D93;
	font-weight: bold;
	padding:0px;
	margin:0px;
}

table.welcome_nav td
{
	padding-top:8px;
	padding-right:20px;
}

td.welcome_installations img
{
	border:0;
	margin-top:13px;
	margin-right:10px;
	width:90px;
	height:85px;
}

table.welcome_address td
{
	color: #000000;
	vertical-align:top;
	padding-right:20px;
}

table.welcome_address img
{
	border:0;
}

td.welcome_text p
{
	margin-top: 10px;
	padding-right:15px;
	color: #000000;
}
</style>

<script type="text/javascript">

function setText(id)
{
	if(id == "About")
	{
		document.getElementById("welcome_box").innerHTML = '<table border="0"cellspacing="0" cellpadding="0"><tr><td><h1>Car Audio Installations, Car Stereos, In-Car Entertainment & Vehicle Security<\/h1><\/td><\/tr><tr><td><table border="0"cellspacing="0" cellpadding="0" class="welcome_nav"><tr><td><a href="javascript:setText(\'About\')" style="text-decoration: underline;">About<\/a><\/td><td><a href="javascript:setText(\'Installation\')">Installation Services<\/a><\/td><td><a href="javascript:setText(\'Shipping\')">Shipping & Delivery<\/a><\/td><td><a href="javascript:setText(\'Contact\')">Contact Us<\/a><\/td><\/tr><\/table><\/td><\/tr><tr><td class="welcome_text"><p>Safe n Sound Croydons showroom is regularly updated to provide excellent surroundings to experience the latest in-car technology.<br><br>Whether you\'re looking to simply replace your existing radio, add functionality, improve your speaker set-up, go for a whole new system or if youre looking at securing your vehicle and deterring possible intruders, our staff will be pleased to help you decide.<br><br>With an extensive range, industry experience and with on-site technical personnel providing installation and repair services, you can discuss any aspect of the systems and arrange the after-sales services you need.	<\/p><\/td><\/tr><\/table>'
	}
	else if(id =="Installation")
	{
		document.getElementById("welcome_box").innerHTML = '<table border="0"cellspacing="0" cellpadding="0"><tr><td><h1>Car Audio Installations, Car Stereos, In-Car Entertainment & Vehicle Security<\/h1><\/td><\/tr><tr><td><table border="0"cellspacing="0" cellpadding="0" class="welcome_nav"><tr><td><a href="javascript:setText(\'About\')">About<\/a><\/td><td><a href="javascript:setText(\'Installation\')" style="text-decoration: underline;">Installation Services<\/a><\/td><td><a href="javascript:setText(\'Shipping\')">Shipping & Delivery<\/a><\/td><td><a href="javascript:setText(\'Contact\')">Contact Us<\/a><\/td><\/tr><\/table><\/td><\/tr><tr><td class="welcome_text"><p>Safe n Sound provides a complete range of installation services. These galleries show some of our workmanship in installing extensive arrays of in-car entertainment and security equipment. <\/p><\/td><\/tr><tr><td class="welcome_installations" align="center"><table border="0"cellspacing="0" cellpadding="0"><tr><td><a href="catalog/images/gallery/Volkswagon Golf R32 1.jpg" target="_new" rel="lightbox[VW Golf R32]" title="VW Golf R32 Image 1"><img src="catalog/images/gallery/thumbs/Volkswagon Golf R32 1.png" alt="VW Golf R32 Image 1"><\/a><\/td><td><a href="catalog/images/gallery/Volkswagon Golf R32 2.jpg" target="_new" rel="lightbox[VW Golf R32]" title="VW Golf R32 Image 2"><img src="catalog/images/gallery/thumbs/Volkswagon Golf R32 2.png" alt="VW Golf R32 Image 2"><\/a><\/td><td><a href="catalog/images/gallery/Volkswagon Golf R32 3.jpg" target="_new" rel="lightbox[VW Golf R32]" title="VW Golf R32 Image 3"><img src="catalog/images/gallery/thumbs/Volkswagon Golf R32 3.png" alt="VW Golf R32 Image 3"><\/a><\/td><td><a href="catalog/images/gallery/Volkswagon Golf R32 4.jpg" target="_new" rel="lightbox[VW Golf R32]" title="VW Golf R32 Image 4"><img src="catalog/images/gallery/thumbs/Volkswagon Golf R32 4.png" alt="VW Golf R32 Image 4"><\/a><\/td><\/tr><\/table><\/td><\/tr><\/table>'
	  Slimbox.scanPage();
	}
	else if(id =="Shipping")
	{
		document.getElementById("welcome_box").innerHTML = '<table border="0"cellspacing="0" cellpadding="0"><tr><td><h1>Car Audio Installations, Car Stereos, In-Car Entertainment & Vehicle Security<\/h1><\/td><\/tr><tr><td><table border="0"cellspacing="0" cellpadding="0" class="welcome_nav"><tr><td><a href="javascript:setText(\'About\')">About<\/a><\/td><td><a href="javascript:setText(\'Installation\')">Installation Services<\/a><\/td><td><a href="javascript:setText(\'Shipping\')" style="text-decoration: underline;">Shipping & Delivery<\/a><\/td><td><a href="javascript:setText(\'Contact\')">Contact Us<\/a><\/td><\/tr><\/table><\/td><\/tr><tr><td class="welcome_text"><p>Place your order for in-stock items up to 2.00pm Monday to Friday and subject to your credit card being authorised and barring system failure, we will despatch your goods the same day.<br><br>Any orders placed on Sat/Sun will be processed on Monday. This is part of the standard delivery service associated with your order and is not a guaranteed service.<br><br>Orders that are dispatched by Business Post can be tracked by visiting <a href="http://www.ukmail.biz/index.html" target="_new">Business Post Tracking.<\/a><\/p><\/td><\/tr><\/table>'
	}
	else if(id =="Contact")
	{
		document.getElementById("welcome_box").innerHTML = '<table border="0"cellspacing="0" cellpadding="0"><tr><td><h1>Car Audio Installations, Car Stereos, In-Car Entertainment & Vehicle Security<\/h1><\/td><\/tr><tr><td><table border="0"cellspacing="0" cellpadding="0" class="welcome_nav"><tr><td><a href="javascript:setText(\'About\')">About<\/a><\/td><td><a href="javascript:setText(\'Installation\')">Installation Services<\/a><\/td><td><a href="javascript:setText(\'Shipping\')">Shipping & Delivery<\/a><\/td><td><a href="javascript:setText(\'Contact\')" style="text-decoration: underline;">Contact Us<\/a><\/td><\/tr><\/table><\/td><\/tr><tr><td class="welcome_text"><table border="0"cellspacing="0" cellpadding="0" ><tr><td><p>Safe n Sound\'s headquarters are based in Croydon, with an extensive showroom and workshop complex offering our full range of showroom sales and services.<\/p><td><\/tr><tr><td><table border="0"cellspacing="0" cellpadding="0" class="welcome_address"><tr><td><br>Address:<br><br>Unit 25, Croydon House<br>1 Peall Road<br>Croydon<br>CR0 3EX<\/td><td><br><a href="images/map_large.png" target="_new"><img src="images/map.png" width="150px" height="89px" alt="Click here to enlarge"><\/a><\/td><td><br>Open: Monday - Friday 9am to 5:30pm<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Saturday 10am-2pm<br><br>Telephone: 020 8288 0181<br>Email: <a href="contact_us.php" target="_new">sales@safensound.co.uk<\/a><br><\/td><\/tr><\/table><\/td><\/tr><\/table><\/td><\/tr><\/table>'
	}

}
</script>

<!--lightbox2-->
<script type="text/javascript" src="js/mootools.js"></script>
<script type="text/javascript" src="js/slimbox.js"></script>

</head>

<body>
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
<?php
  if ($category_depth == 'nested') {
    $category_query = tep_db_query("select cd.categories_name, c.categories_image from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$current_category_id . "' and cd.categories_id = '" . (int)$current_category_id . "' and cd.language_id = '" . (int)$languages_id . "'");
    $category = tep_db_fetch_array($category_query);
?>
    <td width="586px" valign="top" class="maincont_mid_td">
    <?php require(DIR_WS_INCLUDES . 'sub_header.php'); ?>
    <table border="0" width="100%" cellspacing="0" cellpadding="0">

      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
<?php
    if (isset($cPath) && strpos('_', $cPath)) {
// check to see if there are deeper categories within the current category
      $category_links = array_reverse($cPath_array);
      for($i=0, $n=sizeof($category_links); $i<$n; $i++) {
        $categories_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$category_links[$i] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "'");
        $categories = tep_db_fetch_array($categories_query);
        if ($categories['total'] < 1) {
          // do nothing, go through the loop
        } else {
          $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$category_links[$i] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
          break; // we've found the deepest category the customer is in
        }
      }
    } else {
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
    }

    $number_of_categories = tep_db_num_rows($categories_query);

    $rows = 0;
    while ($categories = tep_db_fetch_array($categories_query)) {
      $rows++;
      $cPath_new = tep_get_path($categories['categories_id']);

			$path = substr($cPath, 0, 5);
			if($path == 60545 && strlen($cPath)==11) //car install wizard vehicle category selected
			{
				//printf($cPath);
				//set category image width
				$img_width = 170;
				$img_height = 102;

				//set 3 columns per row
				$width = (int)(100 / 3) . '%';
				echo '                <td align="center" class="smallText" width="' . $width . '" valign="top" style="background-color:#FFF"><a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">' . tep_image(DIR_WS_IMAGES . $categories['categories_image'], $categories['categories_name'], $img_width, $img_height) . '<br>' . $categories['categories_name'] . '</a></td>' . "\n";
				if ((($rows / 3) == floor($rows / 3)) && ($rows != $number_of_categories)) {
					echo '              </tr>' . "\n";
					echo '              <tr>' . "\n";
				}

			}
			else //normal category listing
			{


				$width = (int)(100 / MAX_DISPLAY_CATEGORIES_PER_ROW) . '%';
				echo '                <td align="center" class="smallText" width="' . $width . '" valign="top" style="background-color:#FFF"><a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">' . tep_image(DIR_WS_IMAGES . $categories['categories_image'], $categories['categories_name'], SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT) . '<br>' . $categories['categories_name'] . '</a></td>' . "\n";
				if ((($rows / MAX_DISPLAY_CATEGORIES_PER_ROW) == floor($rows / MAX_DISPLAY_CATEGORIES_PER_ROW)) && ($rows != $number_of_categories)) {
					echo '              </tr>' . "\n";
					echo '              <tr>' . "\n";
				}

      }



    }

// needed for the new products module shown below
    $new_products_category_id = $current_category_id;
?>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
          <tr>
            <td style="padding-right:1px">
            <?php
            if($path != 60545) //car install wizard vehicle category not selected
            	include(DIR_WS_MODULES . FILENAME_NEW_PRODUCTS);
            ?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<?php
  } elseif ($category_depth == 'products' || isset($HTTP_GET_VARS['manufacturers_id'])) {
// create column list
    $define_list = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,
                         'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,
                         'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER,
                         'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE,
                         'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY,
                         'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT,
                         'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE,
                         'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW);

    asort($define_list);

    $column_list = array();
    reset($define_list);
    while (list($key, $value) = each($define_list)) {
      if ($value > 0) $column_list[] = $key;
    }

    $select_column_list = '';

    for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {
      switch ($column_list[$i]) {
        case 'PRODUCT_LIST_MODEL':
          $select_column_list .= 'p.products_model, ';
          break;
        case 'PRODUCT_LIST_NAME':
          $select_column_list .= 'pd.products_name, ';
          break;
        case 'PRODUCT_LIST_MANUFACTURER':
          $select_column_list .= 'm.manufacturers_name, ';
          break;
        case 'PRODUCT_LIST_QUANTITY':
          $select_column_list .= 'p.products_quantity, ';
          break;
        case 'PRODUCT_LIST_IMAGE':
          $select_column_list .= 'p.products_image, ';
          break;
        case 'PRODUCT_LIST_WEIGHT':
          $select_column_list .= 'p.products_weight, ';
          break;
      }
    }

// show the products of a specified manufacturer
    if (isset($HTTP_GET_VARS['manufacturers_id'])) {
      if (isset($HTTP_GET_VARS['filter_id']) && tep_not_null($HTTP_GET_VARS['filter_id'])) {
// We are asked to show only a specific category
        $listing_sql = "select " . $select_column_list . " p.products_id, pd.short_desc, p.manufacturers_id, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c left join " . TABLE_SPECIALS . " s on p2c.products_id = s.products_id where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$HTTP_GET_VARS['filter_id'] . "'";
} else {
// We show them all
        $listing_sql = "select " . $select_column_list . " p.products_id, pd.short_desc, p.manufacturers_id, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from (" . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m ) left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_status = '1' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'";
      }
    } else {
// show the products in a given categorie
      if (isset($HTTP_GET_VARS['filter_id']) && tep_not_null($HTTP_GET_VARS['filter_id'])) {
// We are asked to show only specific catgeory
        $listing_sql = "select " . $select_column_list . " p.products_id, pd.short_desc, p.manufacturers_id, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$HTTP_GET_VARS['filter_id'] . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$current_category_id . "'";
      } else {
// We show them all
        $listing_sql = "select " . $select_column_list . " p.products_id, pd.short_desc, p.manufacturers_id, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c left join " . TABLE_SPECIALS . " s on p2c.products_id = s.products_id where p.products_status = '1' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$current_category_id . "'";
}
    }

    if ( (!isset($HTTP_GET_VARS['sort'])) || (!ereg('[1-8][ad]', $HTTP_GET_VARS['sort'])) || (substr($HTTP_GET_VARS['sort'], 0, 1) > sizeof($column_list)) ) {
      for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {
        if ($column_list[$i] == 'PRODUCT_LIST_NAME') {
          $HTTP_GET_VARS['sort'] = $i+1 . 'a';
          $listing_sql .= " order by pd.products_name";
          break;
        }
      }
    } else {
      $sort_col = substr($HTTP_GET_VARS['sort'], 0 , 1);
      $sort_order = substr($HTTP_GET_VARS['sort'], 1);
      $listing_sql .= ' order by ';
      switch ($column_list[$sort_col-1]) {
        case 'PRODUCT_LIST_MODEL':
          $listing_sql .= "p.products_model " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
          break;
        case 'PRODUCT_LIST_NAME':
          $listing_sql .= "pd.products_name " . ($sort_order == 'd' ? 'desc' : '');
          break;
        case 'PRODUCT_LIST_MANUFACTURER':
          $listing_sql .= "m.manufacturers_name " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
          break;
        case 'PRODUCT_LIST_QUANTITY':
          $listing_sql .= "p.products_quantity " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
          break;
        case 'PRODUCT_LIST_IMAGE':
          $listing_sql .= "pd.products_name";
          break;
        case 'PRODUCT_LIST_WEIGHT':
          $listing_sql .= "p.products_weight " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
          break;
        case 'PRODUCT_LIST_PRICE':
          $listing_sql .= "final_price " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
          break;
      }
    }
?>
    <td width="950px" valign="top" class="maincont_mid_td">
    <?php require(DIR_WS_INCLUDES . 'sub_header.php'); ?>
    <table border="0" width="100%" cellspacing="0" cellpadding="0">


<?php


// Get the right image for the top-right
    $image = DIR_WS_IMAGES . 'table_background_list.gif';
    if (isset($HTTP_GET_VARS['manufacturers_id'])) {
      $image = tep_db_query("select manufacturers_image from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'");
      $image = tep_db_fetch_array($image);
      $image = $image['manufacturers_image'];
    } elseif ($current_category_id) {
      $image = tep_db_query("select categories_image from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
      $image = tep_db_fetch_array($image);
      $image = $image['categories_image'];
    }
?>

      <tr>
        <td><?php include(DIR_WS_MODULES . FILENAME_PRODUCT_LISTING); ?></td>
      </tr>
    </table></td>
<?php
  } else { // default page
?>
    <td width="576px" valign="top" class="maincont_mid_td" style="table-layout:fixed" >
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td>
				<table border="0" width="100%" cellspacing="0" cellpadding="0">
					<tr>
						<td align="center" style="padding-left:1px; padding-right:1px;">
						<div>
								<?php
									// START of "Frontpage Slideshow" settings
									$nameOfSlideshowToDisplay = "myslideshow"; 					// Enter the name of your slideshow. Slideshows are in folders inside /fpss/slideshows/.
									$URLofyoursite = "http://www.safensound.co.uk"; 						// Enter your sites URL.
									$AbsoluteServerPathofyoursite = "/home/sites/safensound.co.uk/public_html/";		// Enter the root path of your site on the server.

									// do not edit below this line
									include_once($AbsoluteServerPathofyoursite."/fpss/mod_fpslideshow.php");
								// END of "Frontpage Slideshow" settings
								?>
						</div>
						</td>
          </tr>
          <tr>
            <td width="576px" style="table-layout:fixed; padding-top:2px;"  ><?php include(DIR_WS_MODULES . 'new_products.php'); ?></td>
          </tr>
<?php
    include(DIR_WS_MODULES . FILENAME_UPCOMING_PRODUCTS);
?>
					<tr>
						<td class="welcome_box" id="welcome_box">
							<table border="0"cellspacing="0" cellpadding="0">
								<tr>
									<td><h1>Car Audio Installations, Car Stereos, In-Car Entertainment & Vehicle Security</h1></td>
								</tr>
								<tr>
									<td>
										<table border="0"cellspacing="0" cellpadding="0" class="welcome_nav">
											<tr>
												<td><a href="javascript:setText('About')" style="text-decoration: underline;">About</a></td>
												<td><a href="javascript:setText('Installation')">Installation Services</a></td>
												<td><a href="javascript:setText('Shipping')">Shipping & Delivery</a></td>
												<td><a href="javascript:setText('Contact')">Contact Us</a></td>
											</tr>
										</table>
									</td>
									</tr>
									<tr>
										<td class="welcome_text">
										<p>Safe n Sound Croydons showroom is regularly updated to provide excellent surroundings to experience the latest in-car technology.<br><br>
										Whether you're looking to simply replace your existing radio, add functionality, improve your speaker set-up, go for a whole new system or if youre looking at securing your vehicle and deterring possible intruders, our staff will be pleased to help you decide.<br><br>
										With an extensive range, industry experience and with on-site technical personnel providing installation and repair services, you can discuss any aspect of the systems and arrange the after-sales services you need.	</p>
										</td>
									</tr>
							</table>
						</td>
					</tr>

        </table>
        </td>
        </tr>
 		<tr>
			<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '5'); ?></td>
		</tr>
   </table></td>
<?php
  }
?>
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

</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
