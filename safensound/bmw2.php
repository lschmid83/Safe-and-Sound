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
<SCRIPT language="JavaScript">
function imgchange(img_name, img_src)
{
	document[img_name].src = img_src;
}
</SCRIPT>

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
    <td width="100%" valign="top" class="maincont_mid_td">
    <?php require(DIR_WS_INCLUDES . 'sub_header.php'); ?>
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
          	<td class="pageHeading" valign="top">BMW Gallery 2<br><span class="smallText">Multimedia Installations Gallery</span></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2" class="maincont_mid_td">
          <tr>
			  <td >
				<table style="width: 100%">
				<tr>
					<td style="padding: 10px;" align="center">
						<img name="target" src="catalog/images/gallery/bmw2-1.jpg" />
					</td>
				</tr>
				<tr>
				<td style="color:#000">
					Mouse over to&nbsp;view:<br/><br/>
					<a onmouseover="imgchange('target', 'catalog/images/gallery/bmw2-1.jpg');"><img src="catalog/images/gallery/bmw2-1.jpg" style="width: 80px; margin-bottom: 2px" /></a>
					<a onmouseover="imgchange('target', 'catalog/images/gallery/bmw2-2.jpg');"><img src="catalog/images/gallery/bmw2-2.jpg" style="width: 80px; margin-bottom: 2px" /></a>
					<a onmouseover="imgchange('target', 'catalog/images/gallery/bmw2-3.jpg');"><img src="catalog/images/gallery/bmw2-3.jpg" style="width: 80px; margin-bottom: 2px" /></a>
				</td>

				</tr>
				<tr>
					<td style="width: 40%; padding: 10px 5px 5px 0px" colspan="2" class="main">
					<p>All headrest work is completed on our premises, so nothing needs to be sent away and you are assured an excellent finish.  You can even send your headrests to us, we then trim in the screens and send them back ready to go.</p>
					<p>In this installation, two Necrox screens were used, available from our online store.  Those shown are seven inch widescreen models - FD-7569.</p>
					<p>The headrest still functions as normal, with wires hidden away, passed through the seats to a DVD player.</p>

					</td>
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
