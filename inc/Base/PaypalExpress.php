<?php 
/* 
 * PaypalExpress Class 
 * This class is used to handle PayPal API related operations 
 * @author    CodexWorld.com 
 * @url        http://www.codexworld.com 
 * @license    http://www.codexworld.com/license 
 */ 
// Include configuration file  
// require_once './PaypalConfig.php'; 
require_once dirname(__FILE__, 2) . '/Base/PaypalConfig.php';
class PaypalExpress{ 
    public $paypalEnv       = PAYPAL_SANDBOX?'sandbox':'production'; 
    public $paypalURL       = PAYPAL_SANDBOX?'https://api.sandbox.paypal.com/v1/':'https://api.paypal.com/v1/'; 
    public $paypalClientID  = PAYPAL_API_CLIENT_ID; 
    private $paypalSecret   = PAYPAL_API_SECRET; 
     
    public function validate($paymentID, $paymentToken, $payerID, $productID){ 
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $this->paypalURL.'oauth2/token'); 
        curl_setopt($ch, CURLOPT_HEADER, false); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_POST, true); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_USERPWD, $this->paypalClientID.":".$this->paypalSecret); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials"); 
        $response = curl_exec($ch); 
        curl_close($ch); 
         
        if(empty($response)){ 
            return false; 
        }else{ 
            $jsonData = json_decode($response); 
            $curl = curl_init($this->paypalURL.'payments/payment/'.$paymentID); 
            curl_setopt($curl, CURLOPT_POST, false); 
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 
            curl_setopt($curl, CURLOPT_HEADER, false); 
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
            curl_setopt($curl, CURLOPT_HTTPHEADER, array( 
                'Authorization: Bearer ' . $jsonData->access_token, 
                'Accept: application/json', 
                'Content-Type: application/xml' 
            )); 
            $response = curl_exec($curl); 
            curl_close($curl); 
             
            // Transaction data 
            $result = json_decode($response); 
             
            return $result; 
        } 
     
    } 
}
