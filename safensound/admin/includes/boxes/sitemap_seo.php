<?php
/*
  $Id: sitemap_seo.php 2008-12-20 by Jack_mcs

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  Portions Copyright 2009 oscommerce-solution.com

  Released under the GNU General Public License
*/
?>
<!-- sitemap_seo //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_SITEMAP_SEO,
                     'link'  => tep_href_link(FILENAME_SITEMAP_SEO_PAGE_CONTROL, 'selected_box=sitemap_seo'));

  if ($selected_box == 'sitemap_seo') {
    $contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_SITEMAP_SEO_BOX_CONTROL, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_SITEMAP_SEO_BOX_CONTROL . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_SITEMAP_SEO_PAGE_CONTROL, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_SITEMAP_SEO_PAGE_CONTROL . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_SITEMAP_SEO_SETTINGS_CONTROL, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_SITEMAP_SEO_SETTINGS_CONTROL . '</a>');


  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- sitemap_seo eof //-->
