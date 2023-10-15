<html>
<head>
<title>Stock Check</title>
<style type="text/css">
   body {background-image: url(http://localhost:7080/safensound/catalog/images/bg.png); background-repeat: repeat-x; margin:10px;}
   p {font-size: 10.5pt;}
   #submit {font-size:90%;}
</style>
</head>
<body>
<p>Due to the high volume turnover of products you might like to make sure that the item is in stock before you buy.</p>
<p>Please enter your email address below and we will send you an email as quickly as possible to confirm stock levels.</p>
<?php
if (isset($_REQUEST['email']))
//if "email" is filled out, send email
  {
  //send email
  $email = $_REQUEST['email'];
  $subject = 'Stock Check from Safe\'n\'Sound';
  $message = $email . "\r\n\r\n" . $_REQUEST['url'];
  mail( "stock.check@safensound.co.uk", "Subject: $subject", $message, "From: $email" );
?>
<script language=JavaScript>
window.close();
</script>
<?php

  }
else
//if "email" is not filled out, display the form
  {
  echo "<form method='post' action='stock_check.php'>
  <p>Email: <input name='email' size='33' type='text' style='margin-left: 5px; margin-bottom:-2px' />
  <input name='url' type='hidden' value='" . $HTTP_GET_VARS['url'] ."'/>
  <input type='submit' value='  Submit  ' id='submit' style='margin-bottom:-3px'/></p>
</form>\n";
  }
?>
<p>Your email address will not be used for any other reason.</p>
</body>
</html>


