<?php
error_reporting(0);
define('CONSUMER_KEY', '6hp1NwGs6C7FuDGAcg1S3Pk99K3YvySe'); // Consumer key
define('CONSUMER_SECRET', 'Er46DyG7UL5tPLBJ'); // Consumer secret
define('B2C_SHORTCODE', '251349'); //Paybill number
define('B2C_INITIATOR', 'IVUTO ERICK'); // Initiator name



define('SECURITY_CREDENTIAL', 'gQPBnxWPaun7Bpj7kbbN8cc+S+Wh+0M0cHJA6HHImob8L5iPGb2EC5eIaY+tznJmd1e4u66wGhmI7ZiJTV0qxzt+7KCB4wvEmJN2CKFE8I6IaqwOMIpfVQfWbkDwGSsdin60boPffClkH5ZJSsUT71vfC5d+Xq0Lsagc9oFCwyC6HWI621kKvm60Q1z3OjIPFsNxhRwTruudqCVqktqnkXu1i4bt5jCAwTgV9ABhXs4P7DDvzN2K8HNUS/qDGybF9xyJzrKMPlHlwoMQSyKvkUT32m1yegYT5USjk87msG2TKiXjjfZujTkKY0uCkz8tcDN3T4+fLSisd+a39Q1b5g==');

$apiKEY = "caaa7706118392e10b0ea47d084a6f848a6fecbcc219f091f2db62e60616013733edaa2fd371b981bd06118392e10b4a6fedaa2fd06";
$consumerKEY = "a2fd371b981bd06118392e10b4a6fedaa2fd06caaa7706110ea47d098a6fecbcc219f091f2db62e630b2f319f8f395fb13733eda";

$data = json_decode(file_get_contents('php://input'), true);
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

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
        
        function b2c_request($amount, $msisdn, $remarks)
        {
        
            $data = array(
        
                'InitiatorName' => B2C_INITIATOR,
                'SecurityCredential' => SECURITY_CREDENTIAL,
                'CommandID' => 'SalaryPayment',
                'Amount' => $amount,
                'PartyA' => B2C_SHORTCODE,
                'PartyB' => $msisdn,
                'Remarks' => $remarks, // mandatory
                'QueueTimeOutURL' => 'https://www.shuklo.com/payments/b2ctimeout.php',
                'ResultURL' => 'https://www.shuklo.com/payments/savings_b2cresult.php',
                'Occasion' => '', //optional
            );
        
            $data = json_encode($data);
            $url = 'https://api.safaricom.co.ke/mpesa/b2c/v1/paymentrequest';
            $response = submit_request($url, $data);
            return $response;
        }
        
if($data['apiKEY'] === $apiKEY and $data['consumerKEY'] === $consumerKEY and $data['service'] !== null){
    $service = $data['service'];
    
    if($service === "disburse_funds"){
        $amount = $data['amount'];
        $phone = $data['phone'];
        $remarks = $data['remarks'];
        
        $response = b2c_request($amount, $phone, $remarks);
        $response = json_decode($response,TRUE);
        
        $response_code = $response['ResponseCode'];
        $conversation_id = $response['ConversationID'];
        $originator_conversation_id = $response['OriginatorConversationID'];
        $response_description = $response['ResponseDescription'];
        
        $json['response_code'] = $response_code;
        $json['conversation_id'] = $conversation_id;
        $json['originator_conversation_id'] = trim($originator_conversation_id);
        $json['response_description'] = $response_description;
        
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
