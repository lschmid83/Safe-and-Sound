<?php
//
// Google Base Feed vX.05 - 2007-04-21
// Original Author unknown.
//

include('includes/application_top.php');
//  Start TIMER
//  -----------
$stimer = explode( ' ', microtime() );
$stimer = $stimer[1] + $stimer[0];
//  -----------

$OutFile = DIR_FS_DOCUMENT_ROOT."feeds/".FROOGLE_DE_FTP_FILENAME;
$destination_file = FROOGLE_DE_FTP_FILENAME;  //"CHANGEME-filename-to-upload-to-froogle.txt" ;
$source_file = $OutFile;
$imageURL = HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES;
$productURL = HTTP_SERVER . DIR_WS_CATALOG .'product_info.php?products_id=';
$already_sent = array();

$home=DB_SERVER;
$user=DB_SERVER_USERNAME;
$pass=DB_SERVER_PASSWORD;
$base=DB_DATABASE;

$ftp_server = FROOGLE_FTP_SERVER;

$ftp_user_name = FROOGLE_FTP_USER;

$ftp_user_pass = FROOGLE_FTP_PASS;

$ftp_directory = ""; // leave blank for froogle

$taxRate = 0; //default = 0 (e.g. for 17.5% tax use "$taxRate = 17.5;")
$taxCalc = ($taxRate/100) + 1;  //Do not edit
$convertCur = true; //default = true
	$curType = "EUR"; // Converts Currency to any defined currency (eg. USD, EUR, GBP)
if($convertCur)
{
	$productURL = HTTP_SERVER . DIR_WS_CATALOG .'product_info.php?currency=' . $curType . '&products_id=';  // Product URL if Currency Conversion is enabled
}

// Mod by Ben - Use the Product and Category Descriptions of a specific language
$chooseLanguage = true; //default = false
$lang_code = "de";  // Language code of the description to use, eg. "en", "fr, "es", "de", etc.  Must match the one used in your shop in Localization -> Languages
if($chooseLanguage)
{
	if($convertCur)
	{
		$productURL = HTTP_SERVER . DIR_WS_CATALOG .'product_info.php?language='. $lang_code .'&currency=' . $curType . '&products_id=';  // Product URL if Currency Conversion AND Language Selection is enabled
	}
	else
	{
		$productURL = HTTP_SERVER . DIR_WS_CATALOG .'product_info.php?language='. $lang_code .'&products_id=';  // Product URL if Language Selection but NOT Currency Conversion is enabled
	}
}
// End mod by Ben - Use the Product and Category Descriptions of a specific language

// Mod by Ben - Define which localization of Google Base (which country) is this feed used for
$GB_localization = "de";  // Do not modify! - unless Google Base opens up other localized versions, e.g. Australiia.


//START Advance Optional Values

//(0=False 1=True) (optional_sec must be enabled to use any options)
$optional_sec = 1;   // Suggested to leave enabled if you use a specific language for your descriptions
$instock = 1;
$shipping = 1;
	$lowestShipping = "DE:Ground:0";  // Google Base DE does not support multiple shipping values for German bulk uploads at this time - Put only 1.
$brand = 1;
$upc = 0;   //Not supported by default osC
$manufacturer_id = 0;  //Not supported by default osC
$feed_quantity = 1;
$payment_accepted = 1;
	$default_payment_methods = "Visa,MasterCard,AmericanExpress,Discover,check,WireTransfer,Überweisung,PayPal,COD,Nachnahme"; //Acceptable values: cash, check, GoogleCheckout, Visa, MasterCard, AmericanExpress, Discover, wiretransfer
$payment_notes = 1;
	$default_payment_notes = "Wir akzeptieren Bezahlung per Überweisung, Paypal und per Nachnahme.";
$tax_percent = 1;  // Suggested to use this option only if *all* your products have the same tax rate
	$default_tax_percent = "19";  // Does not need to be an integer (e.g. "7.5") - do not put the "%" sign.
$tax_region = 1;  // Suggested to use this option only if *all* your products have the same tax region
	$default_tax_region = "Europe";  // Change this according to your applicable tax region
$product_type = 1;
$product_model = 1;
$currency = 1;
	$default_currency = "EUR";  // The currency of the prices of this feed.
$feed_language = 1;   // Suggested to leave enabled if you use a specific language for your descriptions
	$default_feed_language = $lang_code;  //this is not binary.
$ship_to = 1;
	$default_ship_to = "ALL"; //this is not binary, not supported by default osC for individual products.
$condition = 1;
$default_condition = "new"; // Change to suit your store
$feed_exp_date = date('Y-m-d', time() + 2592000 );
$location = 1;
	$default_location = "Address of the items's location here";  // Addresses should be formatted as: street, city, state, postal code, country. Each location element should be separated by a comma.

//END of Advance Optional Values


if (!($link=mysql_connect($home,$user,$pass)))
{
echo "Error when connecting itself to the data base";
exit();
}
if (!mysql_select_db( $base , $link ))
{
echo "Error the data base does not exist";
exit();
}

// Mod by Ben - Use the Product and Category Descriptions of a specific language
if($chooseLanguage)
{
	// Find the ID of the language chosen
	$sql4 = "
	SELECT languages.languages_id AS lang_id
	FROM languages
	WHERE languages.code = '" . $lang_code ."'";

	$lang_id_query = mysql_query( $sql4 )or die( $FunctionName . ": SQL error " . mysql_error() . "| lang_id = " . htmlentities($sql4) );
	$langRow = mysql_fetch_object( $lang_id_query );
	$lang_id = $langRow->lang_id;


	// SQL query if Language Selection is enabled
	$sql = "
	SELECT concat( '" . $productURL . "' ,products.products_id) AS product_url,
	products_model AS prodModel, products_weight,
	manufacturers.manufacturers_name AS mfgName,
	manufacturers.manufacturers_id,
	products.products_id AS id,
	products_description.products_name AS name,
	products_description.products_description AS description,
	products.products_quantity AS quantity,
	products.products_status AS prodStatus,
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
	left join specials on ( specials.products_id = products.products_id AND ( ( (specials.expires_date > CURRENT_DATE) OR (specials.expires_date = 0) ) AND ( specials.status = 1 ) ) )

	WHERE products.products_id=products_description.products_id
	AND products.products_id=products_to_categories.products_id
	AND products_to_categories.categories_id=categories.categories_id
	AND categories.categories_id=categories_description.categories_id
	AND products.products_status != 0
	AND products.products_price != 0
	AND products.products_price != ''
	AND products_description.language_id = '" . $lang_id . "'
	AND products_description.language_id = '" . $lang_id . "'
	AND categories_description.language_id = '" . $lang_id . "'
	ORDER BY
	products.products_id ASC
	";
}
else {
	// SQL query if Language Selection is enabled
	$sql = "
	SELECT concat( '" . $productURL . "' ,products.products_id) AS product_url,
	products_model AS prodModel, products_weight,
	manufacturers.manufacturers_name AS mfgName,
	manufacturers.manufacturers_id,
	products.products_id AS id,
	products_description.products_name AS name,
	products_description.products_description AS description,
	products.products_quantity AS quantity,
	products.products_status AS prodStatus,
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
	left join specials on ( specials.products_id = products.products_id AND ( ( (specials.expires_date > CURRENT_DATE) OR (specials.expires_date = 0) ) AND ( specials.status = 1 ) ) )

	WHERE products.products_id=products_description.products_id
	AND products.products_id=products_to_categories.products_id
	AND products_to_categories.categories_id=categories.categories_id
	AND categories.categories_id=categories_description.categories_id
	AND products.products_status != 0
	AND products.products_price != 0
	AND products.products_price != ''
	ORDER BY
	products.products_id ASC
	";
}
// Mod by Ben - Use the Product and Category Descriptions of a specific language

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
		$catIndex[$curID] = $temp . " > " . $catTempDes[$curID];
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
'%[\r\n]+%m', // remove CRs and newlines
'%[,]+%m'); // remove CRs and newlines
$_strip_replace = array(
'',
' ',
' ');
$_cleaner_array = array(">" => "> ", "&reg;" => "", "?" => "", "&trade;" => "", "?" => "", "\t" => "", "	" => "");

if ( file_exists( $OutFile ) )
unlink( $OutFile );

$output = "link \t title \t description \t expiration_date \t label \t price \t image_link \t id";

//create optional section
if($optional_sec == 1)
{
	if($instock == 1)
		$output .= "\t instock ";
	if($shipping == 1)
		$output .= "\t shipping ";
	if($brand == 1)
		$output .= "\t brand ";
	if($upc == 1)
		$output .= "\t upc ";
	if($manufacturer_id == 1)
		$output .= "\t manufacturer_id ";
      if($feed_quantity == 1)
            $output .= "\t quantity";
      if($payment_accepted == 1)
            $output .= "\t payment_accepted";
      if($payment_notes == 1)
            $output .= "\t payment_notes";
      if($tax_percent == 1)
            $output .= "\t tax_percent";
      if($tax_region == 1)
            $output .= "\t tax_region";
	if($product_type == 1)
		$output .= "\t product_type ";
	if($product_model == 1)
		$output .= "\t model ";
	if($currency == 1)
		$output .= "\t currency ";
	if($feed_language == 1)
		$output .= "\t language ";
	if($ship_to == 1)
		$output .= "\t ship_to ";
	if($ship_from == 1)
		$output .= "\t ship_from ";
      if($condition == 1)
            $output .= "\tcondition";
      if($location == 1)
            $output .= "\t location";
}
$output .= "\n";


$result=mysql_query( $sql )or die( $FunctionName . ": SQL error " . mysql_error() . "| sql = " . htmlentities($sql) );

//Currency Information
if($convertCur)
{
	$sql3 = "
	SELECT
	currencies.value AS curUSD
	FROM
	currencies
	WHERE currencies.code = '$curType'
	";

	$result3=mysql_query( $sql3 )or die( $FunctionName . ": SQL error " . mysql_error() . "| sql3 = " . htmlentities($sql3) );
	$row3 = mysql_fetch_object( $result3 );
}

$loop_counter = 0;

while( $row = mysql_fetch_object( $result ) )
{
	if (isset($already_sent[$row->id])) continue; // if we've sent this one, skip the rest of the while loop

	if( $row->prodStatus == 1 || ($optional_sec == 1 && $instock == 1) )
	{

		if($convertCur)
		{
			$row->price = ereg_replace("[^.0-9]", "", $row->price);
			$row->price = $row->price *  $row3->curUSD;
			$row->price = number_format($row->price, 2, '.', ',');
		}

		if ($imageURL == $row->image_url) {
			$image = "";
		}else{
			$image = $row->image_url;
		}
		$label="";
		if ($row->name != ""){
			$label .= preg_replace($_strip_search,
$_strip_replace, strip_tags( strtr($row->name,
$_cleaner_array) ) );
		}
		if ($row->prodModel != "" && $row->name != ""){
			$label .= ",". $row->prodModel;
		}else{
			$label .= $row->prodModel;
		}
		if ($row->mfgName != "" && ($row->prodModel != ""
|| $row->name != "")){
			$label .= ",". $row->mfgName;
		}else{
			$label .= $row->mfgName;
		}
		$output .= $row->product_url . "\t" .
		preg_replace($_strip_search, $_strip_replace, strip_tags( strtr($row->name, $_cleaner_array) ) ) . "\t" .
		preg_replace($_strip_search, $_strip_replace, strip_tags( strtr($row->description, $_cleaner_array) ) ) . "\t" .
		$feed_exp_date . "\t" .
		$label . "\t" .
		$row->price . "\t" .
		$image . "\t" .
		// Mod by Ben - Added the Google Base localization country to the id, to permit submitting to the different Google Base (US, UK & DE)
		$row->id . "" . $GB_localization;
		// End mod by Ben - Added the Google Base localization country to the id, to permit submitting to the different Google Base (US, UK & DE)

	//optional values section
	if($optional_sec == 1)
	{
		if($instock == 1)
		{
			if($row->prodStatus == 1)
			{
				$prodStatusOut = "Y";
			}
			else
			{
				$prodStatusOut = "N";
			}
			$output .= " \t " . $prodStatusOut;
		}
		if($shipping == 1)
			$output .= " \t " . $lowestShipping;
		if($brand == 1)
			$output .= " \t " . $row->mfgName;
		if($upc == 1)
			$output .= " \t " . "Not Supported";
		if($manufacturer_id == 1)
			$output .= " \t " . "Not Supported";
		if($feed_quantity == 1)
			$output .= " \t " . $row->quantity;
		if($payment_accepted == 1)
			$output .= " \t " . $default_payment_methods;
		if($payment_notes == 1)
			$output .= " \t " . $default_payment_notes;
		if($tax_percent == 1)
			$output .= " \t " . $default_tax_percent;
		if($tax_region == 1)
			$output .= " \t " . $default_tax_region;
		if($product_type == 1)
		{
			$catNameTemp = strtolower($catName);
			$output .= " \t " . $row->catName;
		}
		if($product_model == 1)
			$output .= " \t " . $row->prodModel;
		if($currency == 1)
			$output .= " \t " . $default_currency;
		if($feed_language == 1)
			$output .= " \t " . $default_feed_language;
		if($ship_to == 1)
			$output .= " \t " . $default_ship_to;
		if($ship_from == 1)
			$output .= " \t " . $default_ship_from;
		if($condition == 1)
			$output .= " \t " . $default_condition;
		if($location == 1)
			$output .= " \t " . $default_location;
	}
	$output .= " \n";
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
echo "File completed: " . $destination_file . "<br>\n";
chmod($OutFile, 0777);


//Start FTP to Froogle

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

ftp_file( $ftp_server, $ftp_user_name, $ftp_user_pass, $source_file, $ftp_directory, $destination_file);

//End FTP to Froogle


//  End TIMER
//  ---------
$etimer = explode( ' ', microtime() );
$etimer = $etimer[1] + $etimer[0];
echo '<p style="margin:auto; text-align:center">';
printf( "Script timer: <b>%f</b> seconds.", ($etimer-$stimer) );
echo '</p>';
//  ---------

?>