<?php
//  Title: Google Base / Froogle Data Feeder 1.03
//  Author: Calvin K
//  Contact: calvink@conceptulanetworking.com
//	Organization: Conceptual Networking
//  Last Update: 07/15/09 by Jack_mcs at oscommerce-solution.com


/*************** BEGIN MASTER SETTINGS ******************/

define('SEO_ENABLED','true');    //Change to 'false' to disable if Ultimate SEO URLs is not installed
define('FEEDNAME', 'data_feed.txt');       //from your googlebase account
define('DOMAIN_NAME', 'www.safensound.co.uk'); //your correct domain name (don't include www unless it is used)
define('FTP_USERNAME', 'davidwitney'); //from your googlebase account
define('FTP_PASSWORD', 'datafeeder1'); //from your googlebase account
define('FTP_ENABLED', '1');      //set to 0 to disable
define('CONVERT_CURRENCY', '0'); //set to 0 to disable - only needed if a feed in a difference currecny is required
define('CURRENCY_TYPE', 'GBP');  //(eg. USD, EUR, GBP)
define('DEFAULT_LANGUAGE', 1);   //Change this to the id of your language.  BY default 1 is english

define('OPTIONS_ENABLED', 1);
define('OPTIONS_ENABLED_AGE_RANGE', 0);
define('OPTIONS_ENABLED_BRAND', 0);
define('OPTIONS_ENABLED_CONDITION', 1);
define('OPTIONS_ENABLED_CURRENCY', 0);
define('OPTIONS_ENABLED_FEED_LANGUAGE', 0);
define('OPTIONS_ENABLED_FEED_MANUFACTURE_ID', 0);
define('OPTIONS_ENABLED_FEED_QUANTITY', 0);
define('OPTIONS_ENABLED_MADE_IN', 0);
define('OPTIONS_ENABLED_MANUFACTURER', 0);
define('OPTIONS_ENABLED_PAYMENT_ACCEPTED', 0);
define('OPTIONS_ENABLED_PRODUCT_TYPE', 0);
define('OPTIONS_ENABLED_SHIPPING', 0);
define('OPTIONS_ENABLED_UPC', 0);
define('OPTIONS_ENABLED_WEIGHT', 0);

//the following only matter if the matching option is enabled above.
define('OPTIONS_AGE_RANGE', '0-9');
define('OPTIONS_BRAND', '');
define('OPTIONS_CONDITION', 'new');  //possible entries are New, Refurbished, Used
define('OPTIONS_DEFAULT_CURRENCY', 'USD');
define('OPTIONS_DEFAULT_FEED_LANGUAGE', 'en');
define('OPTIONS_LOWEST_SHIPPING', ''); //this is not binary.  Custom Code is required to provide the shipping cost per product.  ###needs to be an array for per product.
define('OPTIONS_MADE_IN', 'USA');
define('OPTIONS_PAYMENT_ACCEPTED_METHODS', ''); //Acceptable values: cash, check, GoogleCheckout, Visa, MasterCard, AmericanExpress, Discover, wiretransfer
define('OPTIONS_WEIGHT_ACCEPTED_METHODS', 'lb'); //Valid units include lb, pound, oz, ounce, g, gram, kg, kilogram.

/*************** END MASTER SETTINGS ******************/

/*************** NO EDITS NEEDED BELOW THIS LINE *****************/

require_once('../includes/configure.php');

if(SEO_ENABLED=='true'){
  //********************
  // Modification for SEO
  // Since the ultimate SEO was only installed on the public side, we will include our files from there.
  require_once('../includes/filenames.php');
  require_once('../includes/database_tables.php');

  include_once('../' .DIR_WS_CLASSES . 'seo.class.php');
  $seo_urls = new SEO_URL(DEFAULT_LANGUAGE);

  function tep_href_link($page = '', $parameters = '', $connection = 'NONSSL', $add_session_id = true, $search_engine_safe = true) {
     global $seo_urls;
     return $seo_urls->href_link($page, $parameters, $connection, $add_session_id);
  }
}

//********************
//  Start TIMER
//  -----------
$stimer = explode( ' ', microtime() );
$stimer = $stimer[1] + $stimer[0];
//  -----------


$OutFile = "../feeds/" . FEEDNAME;
$destination_file = FEEDNAME;
$source_file = $OutFile;
$imageURL = 'http://' . DOMAIN_NAME . '/catalog/images/';
if(SEO_ENABLED=='true'){
   $productURL = 'product_info.php'; // ***** Revised for SEO
   $productParam = "products_id=";   // ***** Added for SEO
}else{
   $productURL = 'http://' . DOMAIN_NAME . '/product_info.php?products_id=';
}

$already_sent = array();
$taxRate = 15; //default = 0 (e.g. for 17.5% tax use "$taxRate = 17.5;")
$taxCalc = ($taxRate/100) + 1;  //Do not edit

if(CONVERT_CURRENCY)
{
   if(SEO_ENABLED=='true'){
       $productParam="currency=" . CURRENCY_TYPE . "&products_id=";
   }else{
       $productURL = "http://" . DOMAIN_NAME . "/product_info.php?currency=" . CURRENCY_TYPE . "&products_id=";  //where CURRENCY_TYPE is your currency type (eg. USD, EUR, GBP)
   }
}

$feed_exp_date = date('Y-m-d', strtotime("+14 days"));

if (!($link=mysql_connect(DB_SERVER,DB_SERVER_USERNAME,DB_SERVER_PASSWORD)))
{
echo "Error when connecting itself to the data base";
exit();
}
if (!mysql_select_db( DB_DATABASE , $link ))
{
echo "Error the data base does not exist";
exit();
}

$sql = "
SELECT concat( '" . $productURL . "' ,products.products_id) AS product_url,
products_model AS prodModel,
manufacturers.manufacturers_name AS mfgName,
manufacturers.manufacturers_id,
products.products_id AS id,
products_description.products_name AS name,
products_description.products_description AS description,
products.products_quantity AS quantity,
products.products_status AS prodStatus,
products.products_weight AS prodWeight,
FORMAT( IFNULL(specials.specials_new_products_price, products.products_price) * " . $taxCalc . ",2) AS price,
CONCAT( '" . $imageURL . "' ,products.products_image) AS image_url,
products_to_categories.categories_id AS prodCatID,
categories.parent_id AS catParentID,
categories_description.categories_name AS catName
FROM (categories,
categories_description,
products,
products_description,
products_to_categories)

left join manufacturers on ( manufacturers.manufacturers_id = products.manufacturers_id )
left join specials on ( specials.products_id = products.products_id AND ( ( (specials.expires_date > CURRENT_DATE) OR (specials.expires_date is NULL) ) AND ( specials.status = 1 ) ) )

WHERE products.products_id=products_description.products_id
AND products.products_id=products_to_categories.products_id
AND products_to_categories.categories_id=categories.categories_id
AND categories.categories_id=categories_description.categories_id
ORDER BY
products.products_id ASC
";


$catInfo = "
SELECT
categories.categories_id AS curCatID,
categories.parent_id AS parentCatID,
categories_description.categories_name AS catName
FROM
categories,
categories_description
WHERE categories.categories_id = categories_description.categories_id
";

function findCat($curID, $catTempPar, $catTempDes, $catIndex)
{
	if( (isset($catTempPar[$curID])) && ($catTempPar[$curID] != 0) )
	{
		if(isset($catIndex[$catTempPar[$curID]]))
		{
			$temp=$catIndex[$catTempPar[$curID]];
		}
		else
		{
			$catIndex = findCat($catTempPar[$curID], $catTempPar, $catTempDes, $catIndex);
			$temp = $catIndex[$catTempPar[$curID]];
		}
	}
	if( (isset($catTempPar[$curID])) && (isset($catTempDes[$curID])) && ($catTempPar[$curID] == 0) )
	{
		$catIndex[$curID] = $catTempDes[$curID];
	}
	else
	{
		$catIndex[$curID] = $temp . ", " . $catTempDes[$curID];
	}
	return $catIndex;

}

$catIndex = array();
$catTempDes = array();
$catTempPar = array();
$processCat = mysql_query( $catInfo )or die( $FunctionName . ": SQL error " . mysql_error() . "| catInfo = " . htmlentities($catInfo) );
while ( $catRow = mysql_fetch_object( $processCat ) )
{
	$catKey = $catRow->curCatID;
	$catName = $catRow->catName;
	$catParID = $catRow->parentCatID;
	if($catName != "")
	{
		$catTempDes[$catKey]=$catName;
		$catTempPar[$catKey]=$catParID;
	}
}

foreach($catTempDes as $curID=>$des)  //don't need the $des
{
	$catIndex = findCat($curID, $catTempPar, $catTempDes, $catIndex);
}

$_strip_search = array(
"![\t ]+$|^[\t ]+!m", // remove leading/trailing space chars
'%[\r\n]+%m'); // remove CRs and newlines
$_strip_replace = array(
'',
' ');
$_cleaner_array = array(">" => "> ", "&reg;" => "", "�" => "", "&trade;" => "", "�" => "", "\t" => "", "	" => "");

if ( file_exists( $OutFile ) )
unlink( $OutFile );


$output = "link\ttitle\tdescription\texpiration_date\tprice\timage_link\tlabel\tid";

//create optional section
if(OPTIONS_ENABLED == 1)
{
	if(OPTIONS_ENABLED_CONDITION == 1)
		$output .= "\tcondition";
	if(OPTIONS_ENABLED_AGE_RANGE == 1)
		$output .= "\tage_range";
	if(OPTIONS_ENABLED_BRAND == 1)
		$output .= "\tbrand";
	if(OPTIONS_ENABLED_CURRENCY == 1)
		$output .= "\tcurrency";
	if(OPTIONS_ENABLED_FEED_LANGUAGE == 1)
		$output .= "\tlanguage";
	if(OPTIONS_ENABLED_FEED_MANUFACTURER_ID == 1)
		$output .= "\tmanufacturer_id";
	if(OPTIONS_ENABLED_FEED_QUANTITY == 1)
		$output .= "\tquantity";
	if(OPTIONS_ENABLED_MADE_IN == 1)
		$output .= "\tmade_in";
	if(OPTIONS_ENABLED_MANUFACTURER == 1)
		$output .= "\tmanufacturer";
	if(OPTIONS_ENABLED_PAYMENT_ACCEPTED == 1)
		$output .= "\tpayment_accepted";
	if(OPTIONS_ENABLED_PRODUCT_TYPE == 1)
		$output .= "\tproduct_type";
	if(OPTIONS_ENABLED_SHIPPING == 1)
		$output .= "\tshipping";
	if(OPTIONS_ENABLED_UPC == 1)
		$output .= "\tupc";
	if(OPTIONS_ENABLED_WEIGHT == 1)
		$output .= "\tweight";
}
$output .= "\n";


$result=mysql_query( $sql )or die( $FunctionName . ": SQL error " . mysql_error() . "| sql = " . htmlentities($sql) );

//Currency Information
if(CONVERT_CURRENCY)
{
	$sql3 = "
	SELECT
	currencies.value AS curUSD
	FROM
	currencies
	WHERE currencies.code = '" . CURRENCY_TYPE . "'";

	$result3=mysql_query( $sql3 )or die( $FunctionName . ": SQL error " . mysql_error() . "| sql3 = " . htmlentities($sql3) );
	$row3 = mysql_fetch_object( $result3 );
}

$loop_counter = 0;

while( $row = mysql_fetch_object( $result ) )
{
	if (isset($already_sent[$row->id])) continue; // if we've sent this one, skip the rest of the while loop

	if( $row->prodStatus == 1 || (OPTIONS_ENABLED == 1 && $quantity == 1) )
	{
		if (CONVERT_CURRENCY)
		{
			$row->price = ereg_replace("[^.0-9]", "", $row->price);
			$row->price = $row->price *  $row3->curUSD;
			$row->price = number_format($row->price, 2, '.', ',');
		}

    if(SEO_ENABLED=='true'){
            $output .= tep_href_link($productURL,$productParam . $row->id) . "\t" .
            preg_replace($_strip_search, $_strip_replace, strip_tags( strtr($row->name, $_cleaner_array) ) ) . "\t" .
            preg_replace($_strip_search, $_strip_replace, strip_tags( strtr($row->description, $_cleaner_array) ) ) . "\t" .
            $feed_exp_date . "\t" .
            $row->price . "\t" .
            $row->image_url . "\t" .
            $catIndex[$row->prodCatID] . "\t" .
            $row->id;
    }else{
      		$output .= $row->product_url . "\t" .
      		preg_replace($_strip_search, $_strip_replace, strip_tags( strtr($row->name, $_cleaner_array) ) ) . "\t" .
      		preg_replace($_strip_search, $_strip_replace, strip_tags( strtr($row->description, $_cleaner_array) ) ) . "\t" .
      		$feed_exp_date . "\t" .
      		$row->price . "\t" .
      		$row->image_url . "\t" .
      		$catIndex[$row->prodCatID] . "\t" .
      		$row->id;
    }

	//optional values section
	if(OPTIONS_ENABLED == 1)
	{
		if(OPTIONS_ENABLED_CONDITION == 1)
			$output .= " \t " . OPTIONS_CONDITION;
		if(OPTIONS_ENABLED_SHIPPING == 1)
			$output .= " \t " . OPTIONS_LOWEST_SHIPPING;
		if(OPTIONS_ENABLED_MANUFACTURER == 1)
			$output .= " \t " . $row->mfgName;
		if(OPTIONS_ENABLED_UPC == 1)
			$output .= " \t " . "Not Supported";
		if(OPTIONS_ENABLED_PAYMENT_ACCEPTED == 1)
			$output .= " \t " . OPTIONS_PAYMENT_ACCEPTED_METHODS;
		if(OPTIONS_ENABLED_PRODUCT_TYPE == 1)
		{
			$catNameTemp = strtolower($catName);
			$output .= " \t " . $row->catName;
		}
		if(OPTIONS_ENABLED_CURRENCY == 1)
			$output .= " \t " . OPTIONS_DEFAULT_CURRENCY;
		if(OPTIONS_ENABLED_FEED_LANGUAGE == 1)
			$output .= " \t " . OPTIONS_DEFAULT_FFEED_LANGUAGE;
		if(OPTIONS_ENABLED_FEED_MANUFACTURER_ID == 1)
			$output .= " \t " . $row->prodModel;
		if(OPTIONS_ENABLED_FEED_QUANTITY == 1)
			$output .= " \t " . $row->quantity;
		if(OPTIONS_ENABLED_BRAND == 1)
			$output .= " \t " . OPTIONS_BRAND;

		if(OPTIONS_ENABLED_AGE_RANGE == 1)
			$output .= " \t " . OPTIONS_AGE_RANGE;
		if(OPTIONS_ENABLED_MADE_IN == 1)
			$output .= " \t " . OPTIONS_MADE_IN;
		if(OPTIONS_ENABLED_WEIGHT == 1)
			$output .= " \t " . $row->prodWeight . ' ' .OPTIONS_WEIGHT_ACCEPTED_METHODS;
	}

	$output .= " \n";
	$output = str_replace("Description", "<b>Free UK Mainland Delivery</b>", $output );


	}
	$already_sent[$row->id] = 1;


	$loop_counter++;
	if ($loop_counter>750) {
	$fp = fopen( $OutFile , "a" );
	$fout = fwrite( $fp , $output );
	fclose( $fp );
	$loop_counter = 0;
	$output = "";
 }
}

$fp = fopen( $OutFile , "a" );
$fout = fwrite( $fp , $output );
fclose( $fp );
echo "File completed: <a href=\"" . $OutFile . "\" target=\"_blank\">" . $destination_file . "</a><br>\n";
chmod($OutFile, 0777);


//Start FTP

function ftp_file( $ftpservername, $ftpusername, $ftppassword, $ftpsourcefile, $ftpdirectory, $ftpdestinationfile )
{
// set up basic connection
$conn_id = ftp_connect($ftpservername);
if ( $conn_id == false )
{
echo "FTP open connection failed to $ftpservername <BR>\n" ;
return false;
}

// login with username and password
$login_result = ftp_login($conn_id, $ftpusername, $ftppassword);

// check connection
if ((!$conn_id) || (!$login_result)) {
echo "FTP connection has failed!<BR>\n";
echo "Attempted to connect to " . $ftpservername . " for user " . $ftpusername . "<BR>\n";
return false;
} else {
echo "Connected to " . $ftpservername . ", for user " . $ftpusername . "<BR>\n";
}

if ( strlen( $ftpdirectory ) > 0 )
{
if (ftp_chdir($conn_id, $ftpdirectory )) {
echo "Current directory is now: " . ftp_pwd($conn_id) . "<BR>\n";
} else {
echo "Couldn't change directory on $ftpservername<BR>\n";
return false;
}
}

ftp_pasv ( $conn_id, true ) ;
// upload the file
$upload = ftp_put( $conn_id, $ftpdestinationfile, $ftpsourcefile, FTP_ASCII );

// check upload status
if (!$upload) {
echo "$ftpservername: FTP upload has failed!<BR>\n";
return false;
} else {
echo "Uploaded " . $ftpsourcefile . " to " . $ftpservername . " as " . $ftpdestinationfile . "<BR>\n";
}

// close the FTP stream
ftp_close($conn_id);

return true;
}

if (FTP_ENABLED)
 ftp_file( "uploads.google.com", FTP_USERNAME, FTP_PASSWORD, $source_file, "", $destination_file);

//End FTP


//  End TIMER
//  ---------
$etimer = explode( ' ', microtime() );
$etimer = $etimer[1] + $etimer[0];
echo '<p style="margin:auto; text-align:center">';
printf( "Script timer: <b>%f</b> seconds.", ($etimer-$stimer) );
echo '</p>';
//  ---------

?>
