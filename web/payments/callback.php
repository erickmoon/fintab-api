<?php
$data = json_decode(file_get_contents('php://input'), true);

file_put_contents("callback.txt", file_get_contents('php://input'));


$merchant_request_id = $data['Body']['stkCallback']['MerchantRequestID'];
$checkout_request_id = $data['Body']['stkCallback']['CheckoutRequestID'];
$result_code = $data['Body']['stkCallback']['ResultCode'];
$result_description = $data['Body']['stkCallback']['ResultDesc'];

if($result_code === 0){
    $status = "success";
    $amount = $data['Body']['stkCallback']['CallbackMetadata']['Item'][0]['Value'];
    $mpesa_receipt = $data['Body']['stkCallback']['CallbackMetadata']['Item'][1]['Value'];
    $phone = $data['Body']['stkCallback']['CallbackMetadata']['Item'][4]['Value'];
    
    /*
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
        "service":"repay_loan",
        "amount":"'.$repay_loan.'",
        "mpesa_receipt":"'.$mpesa_receipt.'",
        "phone":"'.$phone.'"
        
    }',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      ),
    ));
    
    
    $response = curl_exec($curl);
    
    curl_close($curl);  
    */
    
}elseif($result_code === 1032){
}else{
    //Update DBs
}

?>