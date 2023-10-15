<?php
/*

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
/*

  File: catalog/admin/includes/classes/smsupdater.php
  Source Class: smsupdater.clickatell.php
  Order Notification by SMS, contribution for osCommerce
  http://www.scriptmagic.net

  Copyright (c) 2005 Thunder Raven-Stoker

  Version: 1.0

*/

ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
error_reporting(E_ALL);


class smsUpdater {

    // set these to your required variables.
    // See the web site (http://www.scriptmagic.net/) for information
    // on how to obtain your free client id and password

    /*
    SOME VALID ORIGINATOR EXAMPLES

    1) As a UK phone number (eg 0207 123 4567)         :  '+442071234567'
    2) As a text string for "My UK Motorcycle Shop"    :  'UK Bikes'

    */

       // Originator - set this to the value you wish your SMS to appear from
       // stricly max of 16 numbers or 11 alphanumeric characters
        var $Originator = "SafenSound";

        // This is the Api Id that you created in the clickatell web interface
        var $SMS_Service_Api_ID =  "3196738";

        // This is your Clickatell username
        var $SMS_Service_User_ID = "DavidWitney";

        // This is your Clickatell password
        var $SMS_Service_User_Pass = "WDI497";


        // Do not change below this line ---------------------------------------
        var $ErrorCode = "";
        var $ErrorMessage = "";
        var $Response_Data = array();
        var $Debug = array();
        var $Destination = "";
        var $Body = "";
        var $orderId = "";
        var $smsReference = "";

    // Service definintions - Do not alter these UNLESS you know what you are doing!
        var $SMS_Service_Host = "api.clickatell.com";
        var $SMS_Service_Socket = "";
        var $SMS_Session_ID = "";

    // Constructor
    function smsUpdater($smsDestination,$smsMessage, $oID, $smsReference) {

        $this->Debug[] = "Constructor: Initiating object";
        $this->Destination = $smsDestination;
        $this->Body = $smsMessage;
        $this->orderId = $oID;
        $this->smsReference = $smsReference;

        // Open the socket
        $this->smsOpenSocket();

        // Authenticate
        $this->smsAuthenticate();

    }

    function smsAuthenticate() {
        // attempts authentication with the server
        // and stores the session id on success

        $this->Debug[] = "Authenticate method:";
        if(!$this->SMS_Service_Socket) $this->smsOpenSocket();

        // authenticate and obtain a session id
        $url = "/http/auth";
        $api_id = $this->SMS_Service_Api_ID;
        $user = $this->SMS_Service_User_ID;
        $pass = $this->SMS_Service_User_Pass;
        $postdata = "api_id=$api_id&user=$user&password=$pass";

        $response = $this->smsSendRequest($url,$postdata);
		echo($response);
        if(preg_match("/^OK:\s([a-f0-9]+)$/", $response, $match)) {
            $sessionId =  $match['1'];
            $this->SMS_Session_ID = $sessionId;
        } elseif (preg_match("/^ERR:\s(.*)$/",$response,$match)) {
            $this->Response_Data[] = $response;
            $this->ErrorCode = $match['1'];
            $this->ErrorMessage = "Check error code";
        }
        $this->smsCloseSocket();
        $this->SMS_Service_Socket = "";
        return;
    }
    function smsOpenSocket() {
        // Open socket
        $this->Debug[] = "smsOpenSocket: Opening socket to " . $this->SMS_Service_Host;
        $this->SMS_Service_Socket = fsockopen ($this->SMS_Service_Host, 80, $errno, $errstr, 30);
        $this->Debug[] =  "Socket ID: " . $this->SMS_Service_Socket;

    }

    function smsCloseSocket() {
        $this->Debug[] = "smsCloseSocket:";
        fclose ($this->SMS_Service_Socket);
    }


    function SendSmsMessage() {

         if(!$this->SMS_Service_Socket) $this->smsOpenSocket();

        if(!$this->SMS_Session_ID) {
            // no session id - we cannot proceed
            $this->ErrorCode=-1;
            $this->Debug[] = "SendSmsMessage: No session id received - please check configs";
            $this->ErrorMessage = TEXT_SMS_CLASS_NO_SESSION;
            return false;
        }

        $text = $this->Body;
        $destination = $this->Destination;
        $session = $this->SMS_Session_ID;
        $from = $this->Originator;


        $url = "/http/sendmsg";
        $postdata = "session_id=" . urlencode($session) . "&to=" . urlencode($destination) . "&text=" . urlencode($text) . "&from=" . urlencode($from);
        $this->smsSendRequest($url,$postdata);

    }

    // Function to pipe stuff to remote server
    // and return response data
    function smsSendRequest($url, $data) {


        $this->Debug[] = "smsSendRequest: incoming data: url = $url  data = $data";

        // Set up the headers
        $header = "POST $url HTTP/1.0\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen($data) . "\r\n\r\n";



        if (!$this->SMS_Service_Socket) {
            // HTTP ERROR - handle accordingly
        } else {

            // Write data to socket
            fputs ($this->SMS_Service_Socket, $header . $data);

            // Collect response from socket
            while (!feof($this->SMS_Service_Socket)) {
                $responseData = fgets ($this->SMS_Service_Socket, 1024);
            }

            $this->Response_Data[] = $responseData;
        }

       $this->Debug[] = "smsSendRequest: Response: $responseData";
        return $responseData;
    }



    function smsFormatField($property, $maxlength, $notnull = true) {
        // simple function to strip unwanted chars
        // from one of this object's properties
        // and to make sure it doesn't exceed the
        // maximum length allowed.

        // set field to an arbitrary value if not allowed to be null
        if($notnull && empty($this->$property)) {
            $this->$property = time(); // adds the UNIX timestamp as a value
        }

        // replace chars
        $pats = array("/\'\"\$/");
        $reps = array("");

        $this->$property = preg_replace($pats,$reps,$this->$property);


	    // trim if required
        $this->$property = substr($this->$property,0,$maxlength);

        return true;
     }
}

// End of class
?>
