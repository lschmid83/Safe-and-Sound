osCommerce eBay Exporter v1.0
=============================

Create a csv file containg the product database which can be imported into 
eBay using Turbo Lister to auction osCommerce products with a custom template.

Author: Lawrence Schmid

Installation
============

1) Upload the contents of the admin directory in this contribution to the 
   corresponding directory of your osCommerce installation

2) Modify admin/includes/column_left.php
 
   Add this line:
   
   require(DIR_WS_BOXES . 'osc_ebay_exporter.php');

   Immediately below the line:
   
   require(DIR_WS_BOXES . 'tools.php');  

3) Open http://[yoursite]/admin/osc_ebay_exporter.php in a browser
  
4) Click the Start Export button and save the file to your computer

5) Optional: If you have selected the compress csv option extract the contents 
   of osc_ebay_export.zip using a program such as WinRar (www.rarlabs.com)

6) Download Turbo Lister (make sure you download the correct version) 

7) Setup Turbo Lister with your eBay store information

8) Run Turbo Lister and select the Inventory Tab from the left column

9) Select File->Import items->From File

10) Browse for the .csv or .zip file and press Open to import the contents of 
    your osCommerce product catalog 

This is my first osCommerce contribution I hope you enjoy using it and sell 
lots of stuff on eBay ;)







