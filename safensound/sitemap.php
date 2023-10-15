<?php
/*
  $Id: sitemap_seo.php 1739 2008-12-20 by Jack_mcs

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  Portions Copyright 2009 oscommerce-solution.com

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_WS_MODULES . FILENAME_SITEMAP_SEO);
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SITEMAP_SEO);

  /****************** DISPLAY ARTICLES MANAGER LINKS *********************/
  $showArticlesManager = '';
  if (count($articlesManagerLinksArray) > 0)
  {
    if (tep_not_null($settings['heading_articles_manager'])) {
      $showArticlesManager .= '<tr><th class="sitemapHeading" align="' . SITEMAP_SEO_HEADING_ALIGNMENT . '">' . $settings['heading_articles_manager'] . '</td></tr>';
    }
    $showArticlesManager .= '<tr><td width="50%" class="sitemap"><ul class="sitemap">';
    $pageCount = count($articlesManagerLinksArray);
    for ($i = 0; $i < $pageCount; ++$i)
      $showArticlesManager .= '<li><a class="sitemap" href="' . tep_href_link(FILENAME_ARTICLE_INFO, 'articles_id=' . $articlesManagerLinksArray[$i]['link']) .'" title="' . $articlesManagerLinksArray[$i]['anchor_text'] . '">' . $articlesManagerLinksArray[$i]['text'] . '</a></li>';
    $showArticlesManager .= '</ul></td></tr>';
  }

  /****************** DISPLAY CATEGORIES *********************/
  $showCategories = '';
  if (tep_not_null($settings['heading_categories'])) {
    $showCategories .= '<tr><th class="sitemapHeading" align="' . SITEMAP_SEO_HEADING_ALIGNMENT . '">' . $settings['heading_categories'] . '</td></tr>';
  }
  $showCategories .= '<tr><td class="sitemap">';
  $class = (SITEMAP_SEO_DISPLAY_PRODUCTS_CATEGORIES == 'true') ? 'category_tree.php' : 'category_tree_no_products.php';
  require DIR_WS_CLASSES . $class;
  $osC_CategoryTree = new osC_CategoryTree;
  $showCategories .= $osC_CategoryTree->buildTree();
  $showCategories .= '</td></tr>';

  /****************** DISPLAY INFOPAGES LINKS *********************/
  $showInfoPages = '';
  if (count($infoPagesArray) > 0)
  {
    if (tep_not_null($settings['heading_infopages'])) {
      $showInfoPages .= '<tr><th class="sitemapHeading" align="' . SITEMAP_SEO_HEADING_ALIGNMENT . '">' . $settings['heading_infopages'] . '</td></tr>';
    }
    $showInfoPages .= '<tr><td width="50%" class="sitemap"><ul class="sitemap">';
    $pageCount = count($infoPagesArray);
    for ($i = 0; $i < $pageCount; ++$i)
 			$showInfoPages .= '<li><a class="sitemap" href="' . tep_href_link(FILENAME_INFORMATION, 'info_id='. $infoPagesArray[$i]['link']) .'" title="' . $infoPagesArray[$i]['anchor_text'] . '">' . $infoPagesArray[$i]['text'] . '</a></li>';
    $showInfoPages .= '</ul></td></tr>';
  }

  /****************** DISPLAY MANUFACTURERS *********************/
  $showManufacturers = '';
  if (count($manufacturersArray) > 0)
  {
    if (tep_not_null($settings['heading_manufacturers'])) {
      $showManufacturers .= '<tr><th class="sitemapHeading" align="' . SITEMAP_SEO_HEADING_ALIGNMENT . '">' . $settings['heading_manufacturers'] . '</td></tr>';
    }
    $showManufacturers .= '<tr><td width="50%" class="sitemap"><ul class="sitemap">';
    $pageCount = count($manufacturersArray);
    for ($i = 0; $i < $pageCount; ++$i)
    {
  		$showManufacturers .= '<li><a class="sitemap" href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturersArray[$i]['link']) .'" title="' . $manufacturersArray[$i]['anchor_text'] . '">' . $manufacturersArray[$i]['text'] . '</a></li>';
      $cnt = count($manufacturersArray[$i]['productArray']);

      if ($cnt > 0)
      {
        $showManufacturers .= '<ul>';
        for ($p = 0; $p < $cnt; ++$p)
        {
          $pA = $manufacturersArray[$i]['productArray'][$p]; //makes it more readable
     		 $showManufacturers .= '<li><a class="sitemapProducts" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $pA['link']) .'" title="' . $pA['anchor_text'] . '">' . $pA['text'] . '</a></li>';
        }
        $showManufacturers .= '</ul>';
      }
    }
    $showManufacturers .= '</ul></td></tr>';
  }

  /****************** DISPLAY PAGEMANAGER LINKS *********************/
  $showPageManager = '';
  if (count($pageManagerLinksArray) > 0)
  {
    if (tep_not_null($settings['heading_page_manager'])) {
      $showPageManager .= '<tr><th class="sitemapHeading" align="' . SITEMAP_SEO_HEADING_ALIGNMENT . '">' . $settings['heading_page_manager'] . '</td></tr>';
    }
    $showPageManager .= '<tr><td width="50%" class="sitemap"><ul class="sitemap">';
    $pageCount = count($pageManagerLinksArray);
    for ($i = 0; $i < $pageCount; ++$i)
 			$showPageManager .= '<li><a class="sitemap" href="' . tep_href_link(FILENAME_PAGES, 'page=' . $pageManagerLinksArray[$i]['link']) .'" title="' . $pageManagerLinksArray[$i]['anchor_text'] . '">' . $pageManagerLinksArray[$i]['text'] . '</a></li>';
    $showPageManager .= '</ul></td></tr>';
  }

  /****************** DISPLAY STANDARD INFOBOXES LINKS *********************/
  $showInfoBoxes = '';
  if (count($boxDataArray) > 0)
  {
    if (tep_not_null($settings['heading_standard_boxes'])) {
      $showInfoBoxes .= '<tr><th class="sitemapHeading" align="' . SITEMAP_SEO_HEADING_ALIGNMENT . '">' . $settings['heading_standard_boxes'] . '</td></tr>';
    }
    $showInfoBoxes .= '<tr><td width="50%" class="sitemap"><ul class="sitemap">';
 		$showInfoBoxes .= '<li>'.$boxDataArray[0]['heading'].'</li><ul>';
    $pageCount = count($boxDataArray);
    for ($i = 0; $i < $pageCount; ++$i)
 			$showInfoBoxes .= '<li><a class="sitemap" href="' . tep_href_link($boxDataArray[$i]['link']) .'" title="' . $boxDataArray[$i]['anchor_text'] . '">' . $boxDataArray[$i]['text'] . '</a></li>';
    $showInfoBoxes .= '</ul></ul></td></tr>';
  }

  /****************** DISPLAY STANDARD PAGES *********************/
  $showPages = '';
  if (count($pagesArray) > 0)
  {
    if (tep_not_null($settings['heading_standard_pages'])) {
      $showPages .= '<tr><th class="sitemapHeading" align="' . SITEMAP_SEO_HEADING_ALIGNMENT . '">' . $settings['heading_standard_pages'] . '</td></tr>';
    }
    $showPages .= '<tr><td width="50%" class="sitemap"><ul class="sitemap">';
    $pageCount = count($pagesArray);
    if ($pageCount > 0)
      for ($b = 0; $b < $pageCount; ++$b)
        $showPages .= '<li><a class="sitemap" title="'. $pagesArray[$b]['anchor_text'] .'" href="' . tep_href_link($pagesArray[$b]['link']) . '">' . $pagesArray[$b]['text'] . '</a></li>';
    $showPages .= '</ul></td></tr>';
  }


  /****************** BUILT THE DISPLAY  *********************/
  $leftColDisplay = array();
  $rightColDisplay = array();
  $sortOrderArray = array(array('placement' => SITEMAP_SEO_MODULE_PLACEMENT_ARTICLES_MANAGER, 'sortkey' => SITEMAP_SEO_SORTORDER_ARTICLES_MANAGER, 'module' => $showArticlesManager),
                          array('placement' => SITEMAP_SEO_MODULE_PLACEMENT_CATEGORIES, 'sortkey' => SITEMAP_SEO_SORTORDER_CATEGORIES, 'module' => $showCategories),
                          array('placement' => SITEMAP_SEO_MODULE_PLACEMENT_INFOPAGES, 'sortkey' => SITEMAP_SEO_SORTORDER_INFOPAGES, 'module' => $showInfoPages),
                          array('placement' => SITEMAP_SEO_MODULE_PLACEMENT_MANUFACTURERS, 'sortkey' => SITEMAP_SEO_SORTORDER_MANUFACTURERS, 'module' => $showManufacturers),
                          array('placement' => SITEMAP_SEO_MODULE_PLACEMENT_PAGE_MANAGER, 'sortkey' => SITEMAP_SEO_SORTORDER_PAGE_MANAGER, 'module' => $showPageManager),
                          array('placement' => SITEMAP_SEO_MODULE_PLACEMENT_STANDARD_BOXES, 'sortkey' => SITEMAP_SEO_SORTORDER_STANDARD_BOXES, 'module' => $showInfoBoxes),
                          array('placement' => SITEMAP_SEO_MODULE_PLACEMENT_STANDARD_PAGES, 'sortkey' => SITEMAP_SEO_SORTORDER_STANDARD_PAGES, 'module' => $showPages));

  foreach($sortOrderArray as $key)
  {
    if (tep_not_null($key['module']))
    {
      if ($key['placement'] == 'left')
        $leftColDisplay[] = $key;
      else
        $rightColDisplay[] = $key;
    }
  }

  usort($leftColDisplay, "SortOnKeys");
  usort($rightColDisplay, "SortOnKeys");

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_SITEMAP_SEO));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title>Safe'n'Sound - Car audio including stereos, speakers, subwoofers, amplifiers and navigation </title>
<meta name="description" content="Car audio & security at great prices! Free UK mainland delivery on all products, including car stereo, car subwoofers, car amplifiers & in-car navigation.   We are authorised dealers for Alpine, Dice, Dension, Genesis, Kenwood, JL Audio, JVC, Pioneer, and Sony." >
<meta name="keywords" content="alpine,car audio,car radio,car stereo,car amps,car recievers,headunits,car speakers,bluetooth,changers,dvd players,car speaker,subs,stereo,amplifiers,subwoofer,ipod,monitors,london,croydon" >
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
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
    <td width="960px" valign="top" class="maincont_mid_td">
    <?php require(DIR_WS_INCLUDES . 'sub_header.php'); ?>
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">Sitemap</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>

      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2">
          <tr>

            <!-- DISPALY THE LEFT COLUMN -->
            <td width="50%" valign="top"><table border="0" cellpadding="0">
             <?php
              for ($i = 0; $i < count($leftColDisplay); ++$i)
               echo $leftColDisplay[$i]['module'];
              ?>
            </table></td>

            <!-- DISPALY THE RIGHT COLUMN -->
            <td width="50%" valign="top"><table border="0" cellpadding="0">
             <?php
              for ($i = 0; $i < count($rightColDisplay); ++$i)
               echo $rightColDisplay[$i]['module'];
              ?>
            </table></td>

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