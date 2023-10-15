
<!-- osc_ebay_exporter //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => 'eBay Exporter',
                     'link'  => tep_href_link('osc_ebay_exporter_admin.php', 'selected_box=ebay_exporter_admin'));

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- osc_ebay_exporter_eof //-->
