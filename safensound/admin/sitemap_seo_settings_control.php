<?php
/*
  $Id: sitemap_seo_settings_control.php 2009-01-06 by Jack_mcs

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  Portions Copyright 2009 oscommerce-solution.com

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_WS_FUNCTIONS . FILENAME_SITEMAP_SEO);
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SITEMAP_SEO);

  $pageArray = array();
  $linkArray = array();
  $languages = tep_get_languages();

  $action = (isset($HTTP_POST_VARS['action']) ? $HTTP_POST_VARS['action'] : '');

  if (tep_not_null($action))
  {
     if ($action == 'process_settings')
     {
        for ($i = 0; $i < count($languages); ++$i)
        {
           $append = '_' . $languages[$i]['id'];
           $enable_articles_manager = 'enable_articles_manager' . $append;
           $enable_infopages = 'enable_infopages' . $append;
           $enable_page_manager = 'enable_page_manager' . $append;
		   $heading_title = 'heading_title' . $append;
		   $heading_sub_text = 'heading_sub_text' . $append;
           $heading_articles_manager = 'heading_text_articles_manager' . $append;
           $heading_categories = 'heading_text_categories' . $append;
           $heading_infopages = 'heading_text_infopages' . $append;
           $heading_manufacturers = 'heading_text_manufacturers' . $append;
           $heading_page_manager = 'heading_text_page_manager' . $append;
           $heading_standard_boxes = 'heading_text_standard_boxes' . $append;
           $heading_standard_pages = 'heading_text_standard_pages' . $append;

           $enable_articles_manager = (isset($_POST[$enable_articles_manager]) && $_POST[$enable_articles_manager] == 'on') ? 1 : 0;
           $enable_infopages = (isset($_POST[$enable_infopages]) && $_POST[$enable_infopages] == 'on') ? 1 : 0;
           $enable_page_manager = (isset($_POST[$enable_page_manager]) && $_POST[$enable_page_manager] == 'on') ? 1 : 0;
           $heading_title = (isset($_POST[$heading_title]) && tep_not_null($_POST[$heading_title]) ? $_POST[$heading_title] : '');
           $heading_sub_text = (isset($_POST[$heading_sub_text]) && tep_not_null($_POST[$heading_sub_text]) ? $_POST[$heading_sub_text] : '');
           $heading_articles_manager = (isset($_POST[$heading_articles_manager]) && tep_not_null($_POST[$heading_articles_manager]) ? $_POST[$heading_articles_manager] : '');
           $heading_categories = (isset($_POST[$heading_categories]) && tep_not_null($_POST[$heading_categories]) ? $_POST[$heading_categories] : '');
           $heading_infopages = (isset($_POST[$heading_infopages]) && tep_not_null($_POST[$heading_infopages]) ? $_POST[$heading_infopages] : '');
           $heading_manufacturers = (isset($_POST[$heading_manufacturers]) && tep_not_null($_POST[$heading_manufacturers]) ? $_POST[$heading_manufacturers] : '');
           $heading_page_manager = (isset($_POST[$heading_page_manager]) && tep_not_null($_POST[$heading_page_manager]) ? $_POST[$heading_page_manager] : '');
           $heading_standard_boxes = (isset($_POST[$heading_standard_boxes]) && tep_not_null($_POST[$heading_standard_boxes]) ? $_POST[$heading_standard_boxes] : '');
           $heading_standard_pages = (isset($_POST[$heading_standard_pages]) && tep_not_null($_POST[$heading_standard_pages]) ? $_POST[$heading_standard_pages] : '');

           $settings_query = tep_db_query("select count(*) as ttl from " . TABLE_SITEMAP_SEO_SETTINGS . " where language_id = '" . (int)$languages[$i]['id'] . "' LIMIT 1");
           $settings = tep_db_fetch_array($settings_query);

           if ($settings['ttl'] > 0)  //then entry exists
             tep_db_query("update " . TABLE_SITEMAP_SEO_SETTINGS . " set enable_articles_manager = '" . tep_db_input($enable_articles_manager) . "', enable_infopages = '" . tep_db_input($enable_infopages) . "', enable_page_manager = '" . $enable_page_manager . "', heading_title = '" . $heading_title . "', heading_sub_text = '" . $heading_sub_text . "', heading_articles_manager = '" . tep_db_input($heading_articles_manager) . "', heading_categories = '" . tep_db_input($heading_categories) . "', heading_infopages = '" . tep_db_input($heading_infopages) . "', heading_manufacturers = '" . tep_db_input($heading_manufacturers) . "', heading_page_manager = '" . tep_db_input($heading_page_manager) . "', heading_standard_boxes = '" . tep_db_input($heading_standard_boxes) . "', heading_standard_pages = '" . tep_db_input($heading_standard_pages) . "' where language_id = '" . (int)$languages[$i]['id'] . "'");
           else
             tep_db_query("insert into " . TABLE_SITEMAP_SEO_SETTINGS . " ( enable_articles_manager, enable_infopages, enable_page_manager, heading_title, heading_sub_text, heading_articles_manager, heading_categories, heading_infopages, heading_manufacturers, heading_page_manager, heading_standard_boxes, heading_standard_pages, language_id) values ('" . (int)$enable_articles_manager . "', '" . (int)$enable_infopages . "', '" . (int)$enable_page_manager . "', '" . tep_db_input($heading_title) . "', '" . tep_db_input($heading_sub_text) . "', '" . tep_db_input($heading_articles_manager) . "', '" . tep_db_input($heading_categories) . "', '" . tep_db_input($heading_infopages) . "', '" . tep_db_input($heading_manufacturers) . "', '" . tep_db_input($heading_page_manager) . "', '" . tep_db_input($heading_standard_boxes) . "', '" . tep_db_input($heading_standard_pages) . "', '" . (int)$languages[$i]['id'] . "')");
        }
     }
  }

 $settings = array();
 for ($i = 0; $i < count($languages); ++$i)
 {
   $settings_query = tep_db_query("select * from " . TABLE_SITEMAP_SEO_SETTINGS . " where language_id = '" . (int)$languages[$i]['id'] . "'");
   $settings[$i] = tep_db_fetch_array($settings_query);
 }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
     <tr>
      <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
     </tr>
     <tr>
      <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
     </tr>


     <!-- Begin of Sitemap SEO Settings Control -->
     <tr>
      <td><table border="0" width="100%" style="background-color: #fff; border: ridge #CCFFCC 3px;">
			 <tr>
			  <td><table border="0" width="100%" cellspacing="0" cellpadding="2" style="background-color: #f0f1f1; border: ridge #CCFFCC 3px;">
         <tr>
          <th class="main" style="font-size: 14px"><?php echo HEADING_TITLE_SETTINGS; ?></th>
         </tr>
         <tr><td height="6"></td></tr>
         <tr>
          <td class="main"><?php echo TEXT_TITLE_SETTINGS; ?></td>
         </tr>
         <tr><td height="6"></td></tr>
        </table></td>
       </tr>
       <tr>
        <td align="right"><?php echo tep_draw_form('sitemap_seo_settings', FILENAME_SITEMAP_SEO_SETTINGS_CONTROL, '', 'post') . tep_draw_hidden_field('action', 'process_settings'); ?></td>
         <tr>
          <td><table border="2" width="100%">

           <tr>
            <td><table border="0" width="100%">
             <tr>
              <td><table border="0" width="100%" cellpadding="0">
               <tr class="smallText">
                <th width="30%" align="left"><?php echo TEXT_SETTING_NAME; ?></th>
                <?php
                 $langCnt = count($languages);
                 for ($i=0; $i < $langCnt; ++$i) {
                  echo '<th width="10%" align="left" style="font-weight: bold;">' . $languages[$i]['name'] . '</th>';
                 }
                ?>
               </tr>

               <!-- **************** ENABLE MODULES **************** -->
               <tr class="smallText">
                <td><?php echo TEXT_SETTING_ENABLE_ARTICLES_MANAGER; ?></td>
                <?php
                 for ($i=0; $i < $langCnt; ++$i)
                 {
                   $checked = $settings[$i]['enable_articles_manager'] ? 'checked' : '';
                   $id = $languages[$i]['id'];
                   echo '<td><input type="checkbox" name="enable_articles_manager_' . $id . '"' . $checked . '></td>';
                 }
                 ?>
               </tr>

               <tr class="smallText">
                <td><?php echo TEXT_SETTING_ENABLE_INFOPAGES; ?></td>
                <?php
                 for ($i=0; $i < $langCnt; ++$i)
                 {
                   $checked = $settings[$i]['enable_infopages'] ? 'checked' : '';
                   $id = $languages[$i]['id'];
                   echo '<td><input type="checkbox" name="enable_infopages_' . $id . '"' . $checked . '></td>';
                 }
                 ?>
               </tr>

               <tr class="smallText">
                <td><?php echo TEXT_SETTING_ENABLE_PAGE_MANAGER; ?></td>
                <?php
                 for ($i=0; $i < $langCnt; ++$i)
                 {
                   $checked = $settings[$i]['enable_page_manager'] ? 'checked' : '';
                   $id = $languages[$i]['id'];
                   echo '<td><input type="checkbox" name="enable_page_manager_' . $id . '"' . $checked . '></td>';
                 }
                 ?>
               </tr>

               <!-- **************** HEADING TITILE **************** -->
               <tr class="smallText">
                <td><?php echo TEXT_SETTING_HEADING_TITLE; ?></td>
                <?php
                 for ($i=0; $i < $langCnt; ++$i)
                 {
                   $id = $languages[$i]['id'];
                   echo '<td><input type="text" name="heading_title_' . $id . '" value="' . $settings[$i]['heading_title'] . '" size="' . INPUT_BOX_WIDTH . '"></td>';
                 }
                 ?>
               </tr>

               <!-- **************** HEADING SUB TEXT **************** -->
               <tr class="smallText">
                <td><?php echo TEXT_SETTING_HEADING_SUB_TEXT; ?></td>
                <?php
                 for ($i=0; $i < $langCnt; ++$i)
                 {
                   $id = $languages[$i]['id'];
                   echo '<td><input type="text" name="heading_sub_text_' . $id . '" value="' . $settings[$i]['heading_sub_text'] . '" size="' . INPUT_BOX_WIDTH . '"></td>';
                 }
                 ?>
               </tr>

               <!-- **************** HEADING TEXT **************** -->
               <tr class="smallText">
                <td><?php echo TEXT_SETTING_HEADING_TEXT_ARTICLES_MANAGER; ?></td>
                <?php
                 for ($i=0; $i < $langCnt; ++$i)
                 {
                   $id = $languages[$i]['id'];
                   echo '<td><input type="text" name="heading_text_articles_manager_' . $id . '" value="' . $settings[$i]['heading_articles_manager'] . '" size="' . INPUT_BOX_WIDTH . '"></td>';
                 }
                 ?>
               </tr>

               <tr class="smallText">
                <td><?php echo TEXT_SETTING_HEADING_TEXT_CATEGORIES; ?></td>
                <?php
                 for ($i=0; $i < $langCnt; ++$i)
                 {
                   $id = $languages[$i]['id'];
                   echo '<td><input type="text" name="heading_text_categories_' . $id . '" value="' . $settings[$i]['heading_categories'] . '" size="' . INPUT_BOX_WIDTH . '"></td>';
                 }
                 ?>
               </tr>

               <tr class="smallText">
                <td><?php echo TEXT_SETTING_HEADING_TEXT_INFOPAGES; ?></td>
                <?php
                 for ($i=0; $i < $langCnt; ++$i)
                 {
                   $id = $languages[$i]['id'];
                   echo '<td><input type="text" name="heading_text_infopages_' . $id . '" value="' . $settings[$i]['heading_infopages'] . '" size="' . INPUT_BOX_WIDTH . '"></td>';
                 }
                 ?>
               </tr>

               <tr class="smallText">
                <td><?php echo TEXT_SETTING_HEADING_TEXT_MANUFACTURERS; ?></td>
                <?php
                 for ($i=0; $i < $langCnt; ++$i)
                 {
                   $id = $languages[$i]['id'];
                   echo '<td><input type="text" name="heading_text_manufacturers_' . $id . '" value="' . $settings[$i]['heading_manufacturers'] . '" size="' . INPUT_BOX_WIDTH . '"></td>';
                 }
                 ?>
               </tr>

               <tr class="smallText">
                <td><?php echo TEXT_SETTING_HEADING_TEXT_PAGE_MANAGER; ?></td>
                <?php
                 for ($i=0; $i < $langCnt; ++$i)
                 {
                   $id = $languages[$i]['id'];
                   echo '<td><input type="text" name="heading_text_page_manager_' . $id . '" value="' . $settings[$i]['heading_page_manager'] . '" size="' . INPUT_BOX_WIDTH . '"></td>';
                 }
                 ?>
               </tr>

               <tr class="smallText">
                <td><?php echo TEXT_SETTING_HEADING_TEXT_STANDARD_BOXES; ?></td>
                <?php
                 for ($i=0; $i < $langCnt; ++$i)
                 {
                   $id = $languages[$i]['id'];
                   echo '<td><input type="text" name="heading_text_standard_boxes_' . $id . '" value="' . $settings[$i]['heading_standard_boxes'] . '" size="' . INPUT_BOX_WIDTH . '"></td>';
                 }
                 ?>
               </tr>

               <tr class="smallText">
                <td><?php echo TEXT_SETTING_HEADING_TEXT_STANDARD_PAGES; ?></td>
                <?php
                 for ($i=0; $i < $langCnt; ++$i)
                 {
                   $id = $languages[$i]['id'];
                   echo '<td><input type="text" name="heading_text_standard_pages_' . $id . '" value="' . $settings[$i]['heading_standard_pages'] . '" size="' . INPUT_BOX_WIDTH . '"></td>';
                 }
                 ?>
               </tr>



              </table></td>
             </tr>
             <tr><td height="6"></td></tr>


            </table></td>
           </tr>
          </table></td>
         </tr>
        <tr>
         <td align="center"><?php echo (tep_image_submit('button_update.gif', IMAGE_UPDATE, 'name="update_pages"') ); ?></td>
        </tr>
        </form>
        </td>
       </tr>
      </table></td>
     </tr>
     <!-- end of Sitemap SEO Settings -->

    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
