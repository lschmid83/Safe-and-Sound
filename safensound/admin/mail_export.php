<?php
########################INSTALL #################################################
# This little hack gives you the possibility to export your customers's email addresses  list in a SYLK format readable by Excel
# I use it to upload the file in  an other newsletter script than the admin one which is not powerfull enough for my needs
# This script only select email addresses from customers who subscribe to the newsletter

# 1 . fill with your database data the lines 23-24-25-26
# 2 . upload this file in the admin directory
# 3 . in admin/includes/boxes/tools.php add  '<a href="' . tep_href_link('mail_export.php', '', 'NONSSL') . '" class="menuBoxContentLink" target="_blank">Email Export</a><br>'.
# 4 . That's it !
###################################################################################

define ( "FORMAT_REEL" ,    1 ); // #,##0.00
define ( "FORMAT_ENTIER" , 2 ); // #,##0
define ( "FORMAT_TEXTE" ,   3 ); // @

$cfg_formats [ FORMAT_ENTIER ] = "FF0" ;
$cfg_formats [ FORMAT_REEL ]   = "FF2" ;
$cfg_formats [ FORMAT_TEXTE ]  = "FG0" ;

// ----------------------------------------------------------------------------

$cfg_hote = 'localhost' ;
$cfg_user = 'web13-sns' ;
$cfg_pass = 'N1cole' ;
$cfg_base = 'web13-sns' ;

// ----------------------------------------------------------------------------

if ( mysql_connect ( $cfg_hote , $cfg_user , $cfg_pass ))
{
     // ------------------------------------------------------------------------
$sql   = "SELECT  customers_email_address " ;
$sql .= "FROM customers where customers_newsletter = '1'  " ;
$sql .= "ORDER BY customers_email_address" ;

     // ------------------------------------------------------------------------
$champs = Array(
Array( 'customers_email_address' ,    'Email' ,    FORMAT_TEXTE ,   'L' ,         30 )

     );
// ------------------------------------------------------------------------


if ( $resultat = mysql_db_query ( $cfg_base , $sql ))
     {
//  HTTP HEADER
         // --------------------------------------------------------------------
header ( 'Content-disposition: filename=file.slk' );
header ( 'Content-type: application/octetstream' );
header ( 'Pragma: no-cache' );
header ( 'Expires: 0' );

         // --------------------------------------------------------------------
echo "ID;PASTUCES-phpInfo.net\n" ;
echo "\n" ;
echo "P;PGeneral\n" ;
         echo "P;P#,##0.00\n" ;
echo "P;P#,##0\n" ;
echo "P;P@\n" ;
echo "\n" ;
// polices
echo "P;EArial;M200\n" ;
         echo "P;EArial;M200\n" ;
         echo "P;EArial;M200\n" ;
         echo "P;FArial;M200;SB\n" ;
         echo "\n" ;
//
echo "B;Y" .( mysql_num_rows ( $resultat )+ 1 );
         echo ";X" .( $nbcol = mysql_num_fields ( $resultat )). "\n" ;
         echo "\n" ;

         // --------------------------------------------------------------------
for ( $cpt = 0 ; $cpt < $nbcol ; $cpt ++)
         {
$num_format [ $cpt ] = $champs [ $cpt ][ 2 ];
$format [ $cpt ] = $cfg_formats [ $num_format [ $cpt ]]. $champs [ $cpt ][ 3 ];
         }

         // --------------------------------------------------------------------
for ( $cpt = 1 ; $cpt <= $nbcol ; $cpt ++)
         {
echo "F;W" . $cpt . " " . $cpt . " " . $champs [ $cpt - 1 ][ 4 ]. "\n" ;
         }
         echo "F;W" . $cpt . " 256 8\n" ;
echo "\n" ;

         // --------------------------------------------------------------------
for ( $cpt = 1 ; $cpt <= $nbcol ; $cpt ++)
         {
             echo "F;SDM4;FG0C;" .( $cpt == 1 ? "Y1;" : "" ). "X" . $cpt . "\n" ;
             echo "C;N;K\"" . $champs [ $cpt - 1 ][ 1 ]. "\"\n" ;
         }
         echo "\n" ;

         // --------------------------------------------------------------------
$ligne = 2 ;
         while ( $enr = mysql_fetch_array ( $resultat ))
         {
for ( $cpt = 0 ; $cpt < $nbcol ; $cpt ++)
             {
// format
echo "F;P" . $num_format [ $cpt ]. ";" . $format [ $cpt ];
                 echo ( $cpt == 0 ? ";Y" . $ligne : "" ). ";X" .( $cpt + 1 ). "\n" ;
// values
if ( $num_format [ $cpt ] == FORMAT_TEXTE )
                     echo "C;N;K\"" . str_replace ( ';' , ';;' , $enr [ $cpt ]). "\"\n" ;
                 else
                     echo "C;N;K" . $enr [ $cpt ]. "\n" ;
             }
             echo "\n" ;
$ligne ++;
         }

// EOF
         // --------------------------------------------------------------------
echo "E\n" ;
     }

mysql_close ();
}

?>