
<?php
/*
  $Id: column_left.php, template: OS03C00286 v3.00 09/25/08  13:07:10 hpdl Exp $

  This file created as a part of graphical design by AlgoZone, Inc
  http://www.algozone.com for osCommerce v 2.2ms2

  Copyright (c) 2003-2005  AlgoZone, Inc

*/
$column_location = 'Left';
?>




<?php
  if ( (USE_CACHE == 'true') && !defined('SID')) {
    echo tep_cache_categories_box();
  } else {
    include(DIR_WS_BOXES_AZ . 'categories_az01.php');
  }
?>

	<?php include(DIR_WS_BOXES_AZ . 'shopbybrand.php');	?>
	<?php require(DIR_WS_BOXES_AZ . 'information.php'); ?>


<?php
$column_location = '';
?>

