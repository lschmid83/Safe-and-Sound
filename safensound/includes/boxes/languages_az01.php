<?php
/*
  $Id: languages_az01.php,v 1.0 2004/09/09  hpdl Exp $

  For osCommerce, Open Source E-Commerce Solutions
  Created by http://www.template-faq.com

  Copyright (c) 2004 template-faq.com, Inc

*/
?>
		<tr><td>
<!-- languages //-->
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => BOX_HEADING_LANGUAGES);

  if (!isset($lng) || (isset($lng) && !is_object($lng))) {
    include(DIR_WS_CLASSES . 'language.php');
    $lng = new language;
  }

  $languages_string = '';
  $languages_count = 0;
  reset($lng->catalog_languages);
  while (list($key, $value) = each($lng->catalog_languages)) {
    $languages_string .= '<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('language', 'currency')) . 'language=' . $key, $request_type) . '">' . tep_image(DIR_WS_LANGUAGES .  $value['directory'] . '/images/' . $value['image'], $value['name']) . '</a>&nbsp;';
    $languages_count++;
  }

  if($languages_count > 1)
  {
   
	  $info_box_contents = array();
	  $info_box_contents[] = array('align' => 'center',
	                               'text' => $languages_string);

	  new infoBox($info_box_contents, $column_location,0,0);
  }
?>
		</td></tr>
<!-- languages_eof //-->
