<?php
$data1 = file_get_contents('php://input');
$data = json_decode($data1,true);

$transaction_ref = $data['TransID'];
$account_ref = $data['BillRefNumber'];
$amount = $data['TransAmount'];
$payment_descritiption = "loan repayment";
$partya = $data['MSISDN'];
$partyb = $data['BusinessShortCode'];
$customer_name = $data['FirstName'];


function contains($needle, $haystack)
{
    return strpos($haystack, $needle) !== false;
}
$account_ref = str_replace(" ","",$account_ref);



if(contains("deposit",$account_ref)){
    
    //Log
    $user_id = str_replace("deposit","",$account_ref);
    
    //Send details for deposit
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://www.shuklo.com/fintab-android/v1/',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>'{
        "apiKEY":"0616013733edaa2fd371b981bd06118392e10b4a6fedaa2fd06caaa7706118392e10b0ea47d084a6f848a6fecbcc219f091f2db62e6",
        "consumerKEY":"0ea47d098a6fecbcc219f091f2db62e630b2f319f8f395fb13733edaa2fd371b981bd06118392e10b4a6fedaa2fd06caaa770611",
        "service":"deposit_to_wallet",
        "deposit_method":"MPESA",
        "transaction_ref":"'.$transaction_ref.'",
        "account_ref":"'.$account_ref.'",
        "amount":"'.$amount.'",
        "payment_descritiption":"'.$payment_descritiption.'",
        "customer_name":"'.$customer_name.'",
        "partya":"'.$partya.'",
        "user_id":"'.$user_id.'",
        "partyb":"'.$partyb.'"
        
    }',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);  
}else{

//Send data via cURL
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://www.shuklo.com/fintab-android/v1/',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>'{
        "apiKEY":"0616013733edaa2fd371b981bd06118392e10b4a6fedaa2fd06caaa7706118392e10b0ea47d084a6f848a6fecbcc219f091f2db62e6",
        "consumerKEY":"0ea47d098a6fecbcc219f091f2db62e630b2f319f8f395fb13733edaa2fd371b981bd06118392e10b4a6fedaa2fd06caaa770611",
        "service":"repay_loan_c2b",
        "transaction_ref":"'.$transaction_ref.'",
        "account_ref":"'.$account_ref.'",
        "amount":"'.$amount.'",
        "payment_descritiption":"'.$payment_descritiption.'",
        "customer_name":"'.$customer_name.'",
        "partya":"'.$partya.'",
        "partyb":"'.$partyb.'"
        
    }',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      ),
    ));
    //file_put_contents("repay_loan1.txt","Ref: $transaction_ref Account Ref: $account_ref Amount: $amount Payment desc: $payment_descritiption customer name: $customer_name party a: $partya party B: $partyb");
    $response = curl_exec($curl);
    
    curl_close($curl);    
}
?>