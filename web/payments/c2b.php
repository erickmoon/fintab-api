<?php
error_reporting(0);
date_default_timezone_set('Africa/Nairobi');

// SETTINGS
define('CONSUMER_KEY', 'Jm7Xid4uV5toYCGAlcHsuYHTYoqyObeu'); // Consumer key
define('CONSUMER_SECRET', 'OZGkGX16N685gG3y'); // Consumer secret

//C2B Credentials
define('LNM_SHORTCODE', '539100'); // The Lipa Na M-Pesa shortcode
define('LNM_KEY', 'd5fbc6177c3161969eca05b1c2f55d5aae78412f36ec1ccc6a5430f082d025d0');
define('TIMESTAMP', date("YmdHis")); // The current timestamp
define('LNM_PASSWD', base64_encode(LNM_SHORTCODE . LNM_KEY . TIMESTAMP)); // The Lipa na M-Pesa password

function get_accesstoken()
{
    $credentials = base64_encode(CONSUMER_KEY . ':' . CONSUMER_SECRET);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . $credentials, 'Content-Type: application/json'));
    $response = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($response);

    $access_token = $response->access_token;

    // The above $access_token expires after an hour, find a way to cache it to minimize requests to the server
    if (!$access_token) {
        throw new Exception("Invalid access token generated");
        return false;
    }
    return $access_token;
}

function submit_request($endpoint_url, $json_body)
{ // Returns cURL response
    $access_token = get_accesstoken();

    if ($access_token != '' || $access_token !== false) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $endpoint_url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token));

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json_body);

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    } else {
        throw new Exception("Access token is invalid");
        return false;
    }
}

function register_url()
{
    $request_data = array(
        'ShortCode' => LNM_SHORTCODE,
        'ResponseType' => 'Completed',
        'ConfirmationURL' => 'https://www.shuklo.com/payments/secured_confirmation.php',
        'ValidationURL' => 'https://www.shuklo.com/payments/secured_validation.php',
    );
    $data = json_encode($request_data);
    $url = 'https://api.safaricom.co.ke/mpesa/c2b/v2/registerurl';
    $response = submit_request($url, $data);
    return $response;
}

function stk_push($amount, $msisdn, $ref)
{

    $data = array(

        'BusinessShortCode' => LNM_SHORTCODE,
        'Password' => LNM_PASSWD,
        'Timestamp' => TIMESTAMP,
        'TransactionType' => 'CustomerPayBillOnline',
        'Amount' => $amount,
        'PartyA' => $msisdn,
        'PartyB' => LNM_SHORTCODE,
        'PhoneNumber' => $msisdn,
        'CallBackURL' => 'https://www.shuklo.com/payments/callback.php',
        'AccountReference' => $ref,
        'TransactionDesc' => 'test',
    );

    $data = json_encode($data);
    $url = 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
    $response = submit_request($url, $data);
    return $response;
}

$apiKEY = "18392e10b0ea47d1b981bd06118392e10b084a6f848a6fecbcc219f091f2db62e60616013733edaa2fd37caaa770614a6fedaa2fd06";
$consumerKEY = "daa2b4a6fef319f8f395fb13733efd06caaa7706110ea47d098a6fecbcc219f091f2db62e630b2a2fd371b981bd06118392e10da";
$data = json_decode(file_get_contents('php://input'), true);
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if($data['apiKEY'] === $apiKEY and $data['consumerKEY'] === $consumerKEY and $data['service'] !== null){
    $service = $data['service'];
    
    if($service === "trigger_payment_online"){
        $account_number = $data['account_number'];
        $phone = $data['phone'];
        $amount = $data['amount'];
        
        $response = json_decode(stk_push($amount, $phone, $account_number),TRUE); 
        
        $json['merchant_request_id'] = $response['MerchantRequestID'];
        $json['checkout_request_id'] = $response['CheckoutRequestID'];
        $json['response_code'] = $response['ResponseCode'];
        $json['response_description'] = $response['ResponseDescription'];
        $json['customer_message'] = $response['CustomerMessage'];
        
        //transaction_ref,account_ref,patya,partyb,amount,payment_descritiption,merchant_request_id,checkout_request_id,response_code,response_description,customer_message
        
        echo json_encode($json);
    }
}else{
?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
<p>The requested URL was not found on this server.</p>
<p>Additionally, a 404 Not Found
error was encountered while trying to use an ErrorDocument to handle the request.</p>
</body></html>
<?php
}


?>