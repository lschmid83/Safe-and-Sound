<?php
/*
  $Id: specials.php,v 1.49 2003/06/09 22:35:33 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SPECIALS);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_SPECIALS));
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
            <td class="pageHeading">Installations Gallery</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2" class="maincont_mid_td">
          <tr>
            <td >
            <table style="width: 100%">
			  <tr>
			    <td class="circle" style="width: 20%"><a href="bmw1.php"><img src="catalog/images/gallery/bmw1-1.jpg" style="width: 100px" alt="BMW gallery 1" border="0"/></a></td>
			    <td class="circle" style="width: 20%"><a href="bmw2.php"><img src="catalog/images/gallery/bmw2-1.jpg" style="width: 100px" alt="BMW gallery 2" border="0"/></a></td>
			    <td class="circle" style="width: 20%"><a href="bmw3.php"><img src="catalog/images/gallery/bmw3-1.jpg" style="width: 100px" alt="BMW gallery 3 (M3)" border="0"/></a></td>
			    <td class="circle" style="width: 20%"><a href="#"><img src="catalog/images/gallery/cherokee1-1.jpg" style="width: 100px" alt="Cherokee" border="0"/></a></td>
			    <td class="circle" style="width: 20%"><a href="discovery1.php"><img src="catalog/images/gallery/discovery1-1.jpg" style="width: 100px" alt="Land Rover Discovery" border="0"/></a></td>
			  </tr>
			  <tr>
			    <td class="circle"><a href="fiesta1.php"><img src="catalog/images/gallery/fiesta1-1.jpg" style="width: 100px" alt="Ford Fiesta" border="0"/></a></td>
			    <td class="circle"><a href="peugeot1.php"><img src="catalog/images/gallery/peugeot1-1.jpg" style="width: 100px" alt="Peugeot" border="0"/></a></td>
			    <td class="circle"><a href="porsche1.php"><img src="catalog/images/gallery/porsche1-1.jpg" style="width: 100px" alt="Porsche gallery 1" border="0"/></a></td>
			    <td class="circle"><a href="porsche2.php"><img src="catalog/images/gallery/porsche2-1.jpg" style="width: 100px" alt="Porsche gallery 2" border="0"/></a></td>
			    <td class="circle"><a href="porsche3.php"><img src="catalog/images/gallery/porsche3-1.jpg" style="width: 100px" alt="Porsche gallery 3" border="0"/></a></td>
			  </tr>
			  <tr>
			    <td class="circle">&nbsp;</td>
			    <td class="circle"><a href="rangerover1.php"><img src="catalog/images/gallery/rangerover1-1.jpg" style="width: 100px" alt="Range Rover Sport" border="0"/></a></td>
			    <td class="circle"><a href="renault1.php"><img src="catalog/images/gallery/renault1-1.jpg" style="width: 100px" alt="Renault" border="0"/></a></td>
			    <td class="circle"><a href="smart1.php"><img src="catalog/images/gallery/smart1-1.jpg" style="width: 100px" alt="SNS Smart" border="0"/></a></td>
			    <td class="circle">&nbsp;</td>
			  </tr>
			</table>
            </td>
          </tr>
        </table>
        </td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
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
