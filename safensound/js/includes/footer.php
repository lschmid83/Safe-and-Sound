
<?php
/*
  $Id: footer.php, template: OS03C00286 v3.00 09/25/08  13:07:10 hpdl Exp $

  This file created as a part of graphical design by AlgoZone, Inc
  http://www.algozone.com for osCommerce v 2.2ms2

  Copyright (c) 2003-2005 AlgoZone, Inc

*/
  require(DIR_WS_INCLUDES . 'counter.php');
?>



</td></tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="footer_tb">
<tr>
  <td align="left" class="footer_td1">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="footer_menu_tb">
	<tr>
	  <td align="left">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
			<?php $column_location = 'Bottom'; ?>
			<?php include(DIR_WS_BOXES_AZ . 'information_az01.php');	?>
			<?php $column_location = ''; ?>
			</table>
	  </td>
	</tr>
	</table>
	<table border="0" cellspacing="0" cellpadding="2" width="100%" class="footer_cprt_tb">
	<tr>
		<td align="left" class="smalltext"></td>
	</tr>
	</table>
  </td>
  <td width="200" align="center" class="footer_td2"><img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>az_cc_icons.png" border="0"></td>
</tr>
</table>
  </td>
</tr>
</table>
    </td>
	 <td class="az_rightpage_side" height="100%" valign="top">
		<table width="100%" height="100%" border="0" align="left" cellpadding="0" cellspacing="0">
		<tr><td valign="top" class="az_rightpage_side_top" height="372"></td></tr>
		<tr><td valign="top"><?php echo tep_draw_separator('pixel_trans.gif', '1', '100%'); ?></td></tr>
		<tr><td valign="bottom" class="az_rightpage_side_bottom" height="109"></td></tr>
		</table>
	</td>
</tr>
</table>

