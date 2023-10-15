<?php
header('Content-type: text/xml');
ob_start();
echo '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
';

include 'includes/application_top.php';

$p_query=tep_db_query('select categories_id, date_added from categories');
while($rs=tep_db_fetch_array($p_query)){
    echo '<url>', "\n", '<loc>';
    echo tep_href_link('index.php', 'cPath='.$rs['categories_id']);
    echo '</loc>', "\n", '</url>', "\n";
}

$p_query=tep_db_query('select products_id, products_date_added from products');
while($rs=tep_db_fetch_array($p_query)){
    echo '<url>', "\n", '<loc>';
    echo tep_href_link('product_info.php', 'products_id='.$rs['products_id']);
    echo '</loc>', "\n", '</url>', "\n";
}
echo '</urlset>';
$c=ob_get_contents();
ob_clean();
echo $c;
//$fp=fopen('yahoo.xml', 'w');
//fwrite($fp, $c);
//fclose($fp);
