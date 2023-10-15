<?php
/*
  $Id: all_specials_slideshow.php v1.0 created by Michael Hazzard  May 19 2007 http://www.miramardesign.com

  based on all_specials.php v1.01 created by Kornel Hartung on 2003/11/03 21:02:00 hpdl Exp $
  based on /includes/boxes/specials.php of MS2
  Example can be seen here: www.livecarts.com

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
$check_query = tep_db_query("select count(*) as count from " . TABLE_SPECIALS . " where status='1'");
$check = tep_db_fetch_array($check_query);
if ($check['count'] > 0) {
  $specials_query_raw = "select p.products_id, pd.products_name, p.products_image, p.products_price, p.products_tax_class_id, p.products_image, s.specials_new_products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s where p.products_status = '1' and s.products_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and s.status = '1' order by s.specials_date_added DESC";
  $specials_query = tep_db_query($specials_query_raw);
?>
<!-- specials //-->
          <tr>
            <td width="182px">


<?php
    $info_box_contents = array();
    $info_box_contents[] = array('text' => BOX_HEADING_SPECIALS);

       new infoBoxHeading($info_box_contents, false, false, tep_href_link(FILENAME_SPECIALS),$column_location);
	$box_text = '';
	$rown = tep_db_num_rows($specials_query);
    $row = 0;
	while ($specials = tep_db_fetch_array($specials_query)) {
	  $row++;
      $box_text .= '  <table border=0 cellpadding=0 cellspacing=0 style="height 40px;" id="special'.$row.'"><tr><td align="center"  ><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $specials["products_id"]) . '">' . tep_image(DIR_WS_IMAGES . $specials['products_image'], $specials['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></td></tr><tr><td align="center" width="100%"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $specials['products_id']) . '">' . $specials['products_name'] . '</a></td></tr><tr><td class="smalltext" width="100%" align="center"><s>' . $currencies->display_price($specials['products_price'], tep_get_tax_rate($specials['products_tax_class_id'])) . '</s><br><span class="productSpecialPrice">' . $currencies->display_price($specials['specials_new_products_price'], tep_get_tax_rate($specials['products_tax_class_id'])) . '</span></td></tr></table>';
	  if ($rown == $row) {
	    $box_text .= "\n";
	  } else {
	    $box_text .= '<br>' . "\n";
	  }
	}
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text' => $box_text);
	new infoBox2($info_box_contents);
?>
          </td>
          </tr>
          <tr>
          <td>

		    <script language="javascript" type="text/javascript">

	var row =  <?php echo $row; ?>;
function start(){

var k = Math.floor(Math.random()* row +1)  //make random starting special //http://www.shawnolson.net/a/789/make-javascript-mathrandom-useful.html
var i ;

for(i= 1; i < (row + 1); i++){    //echo total # of special's
if(i == k) {
document.getElementById('special'+i).style.display="";
}
else{
document.getElementById('special'+i).style.display="none";  //hide all
}
}

// need to randomize beginnning value
setTimeout("slideshow("+ k +")",3000);  //change 3000 to custom time if desired
//setTimeout("slideshow(1)",3000);  //static if starting at 1
}

function slideshow(i){
document.getElementById('special'+i).style.display="";
if (i > 1) {
var j = i - 1;
document.getElementById('special'+j).style.display="none";
}else {
document.getElementById('special'+ row).style.display="none";
}
++i;

if (i > row ){
i = 1;
}
setTimeout("slideshow("+i+")",3000);
}
start();
</script>
</td>
</tr>



<!-- specials_eof //-->
<?php
}
?>
