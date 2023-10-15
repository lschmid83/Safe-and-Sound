<?php
/*
  $Id: information.php,v 1.6 2003/02/10 22:31:00 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- information //-->
          <tr>
            <td width="182px" style="table-layout:fixed"  >
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => BOX_HEADING_INFORMATION);

  new infoBoxHeading($info_box_contents, false, false, false, $column_location);

  $info_box_contents = array();
  $info_box_contents[] = array('text' => '<table><tr><td style="padding-left:24px"><a href="delivery.php">Delivery</a><br></td></tr>' .
                                         '<tr><td style="padding-left:24px"><a href="privacy.php">Privacy Policy</a><br></td></tr>' .
                                         '<tr><td style="padding-left:24px"><a href="terms.php">Terms & Conditions</a><br></td></tr>' .
                                         '<tr><td style="padding-left:24px"><a href="' . tep_href_link(FILENAME_CONTACT_US) . '">' . BOX_INFORMATION_CONTACT . '</a><br></td></tr>' .
                                         '<tr><td style="padding-left:24px"><a href="http://www.safensound.co.uk/blog/" target="_blank" >Car Audio Blog</a><br></td></tr>' .
                                         '<tr><td style="padding-left:24px"><a href="' . tep_href_link(FILENAME_SITEMAP_SEO) . '">' . BOX_INFORMATION_SITEMAP_SEO . '</a></td></tr></table>');
  new infoBox($info_box_contents, $column_location);
?>
            </td>
          </tr>
<!-- information_eof //-->
