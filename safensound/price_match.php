<html>
<head>
<title>Price Match</title>
<style type="text/css">
   body {background-image: url(http://localhost:7080/safensound/catalog/images/bg.png); background-repeat: repeat-x; margin:10px;}
   p {font-size: 10.5pt;}
   #submit {font-size:90%;}
</style>
</head>
<body>
<p>Prices at different shops can vary enormously, especially when you take postage into account.</p>
<p>Please enter your email address and the website where you have found the product cheaper and we will try and match the price.</p>
<?php
if (isset($_REQUEST['email']))
//if "email" is filled out, send email
  {
  //send email
  $email = $_REQUEST['email'];
  $website = $_REQUEST['website'];
  $subject = 'Price Match from Safe\'n\'Sound';
  $message = $email . "\r\n\r\n" . $_REQUEST['url'] . "\r\n\r\n" . $website;
  mail( "price.match@safensound.co.uk", "Subject: $subject", $message, "From: $email" );
?>
<script language=JavaScript>
window.close();
</script>
<?php

  }
else
//if "email" is not filled out, display the form
  {
  echo "<form method='post' action='price_match.php'>
  <p>Email: <input name='email' size='33' type='text' style='margin-left: 19px; margin-bottom:-2px' />
  <input name='url' type='hidden' value='" . $HTTP_GET_VARS['url'] ."'/>
  <p>Website: <input name='website' size='33' type='text' style='margin-left: 5px; margin-bottom:-2px' />
  <input type='submit' value='  Submit  ' id='submit' style='margin-bottom:-3px'/></p>
</form>\n";
  }
?>
<p>Your email address will not be used for any other reason.</p>
</body>
</html>


