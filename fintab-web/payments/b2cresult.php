<?php
error_reporting(0);
include("../fintab-android/includes/dbconn.php");

$phone_notifications = "254710293886";
$appname = "FINTAB";
$low_amount_warning = 10000;
$shortcode = "251349";

$data = json_decode(file_get_contents('php://input'), true);
$filePath = "output.txt";
$file = fopen($filePath,"a");

$result_code = $data['Result']['ResultCode'];
$result_desc = $data['Result']['ResultDesc'];
$originator_conversation_id = $data['Result']['OriginatorConversationID'];
$conversation_id = $data['Result']['ConversationID'];
$transaction_id = $data['Result']['TransactionID'];


if($result_desc == ""){
    $result_desc = "Unknown";
}

function send_general_sms($phone,$message){
                $curl = curl_init();
                
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://sms.textsms.co.ke/api/services/sendsms/',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>'{
                	"apikey":"7cad0a203f66336727cf78484b2d88aa",
                	"partnerID":"6460",
                	"mobile":"'.$phone.'",
                	"message":"'.$message.'",
                	"shortcode":"TextSMS",
                	"pass_type":"plain"
                }',
                  CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Cookie: PHPSESSID=s4ugl3t67dtnhknq679bno9ve5'
                  ),
                ));
    
                $response = curl_exec($curl);
                
                curl_close($curl);  
}

if($result_code == 0){
    $balance = $data['Result']['ResultParameters']['ResultParameter'][4]['Value'];
    $amount = $data['Result']['ResultParameters']['ResultParameter'][0]['Value'];
    $partyb = $data['Result']['ResultParameters']['ResultParameter'][2]['Value'];
    $transaction_timestamp = $data['Result']['ResultParameters']['ResultParameter'][3]['Value'];
    // Update and post to b2c this is success
    if($balance <= $low_amount_warning){
        send_general_sms($phone_notifications,"WARNING: B2C b2c balance running low on $appname. Current balance is Kshs.$balance");
    }
    
    $status = "active";
    $stmt = $conn->prepare("UPDATE `disbursed_loans` SET `status` = ?,`response_code` = ?,`response_description` = ? WHERE `conversation_id` = ? AND `originator_conversation_id` = ?) VALUES(?,?,?,?,?,?)");
    $stmt->bind_param("ssssss",$status,$result_code,$result_desc,$transaction_id,$conversation_id,$originator_conversation_id);
    $stmt->execute();
    
    //Update mpesa
    $partya = $shortcode;
    $description = "Loan disbursement";
    $stmt = $conn->prepare("INSERT INTO `mpesa_transactions_b2c`(`amount`,`transaction_ref`,`partya`,`partb`,`description`,`conversation_id`,`originator_conversation_id`,`transaction_timestamp`) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->bind_param("ssssssss",$amount,$transaction_id,$partya,$partyb,$description,$conversation_id,$originator_conversation_id,$transaction_timestamp);
    $stmt->execute();
}else{
    //$partyb
    send_general_sms($partyb,"$appname: Loan disbursement failed, something went wrong with our systems. Please try again later.");
    
    $status = "failed";
    $stmt = $conn->prepare("DELETE FROM `disbursed_loans` WHERE `conversation_id` = ? AND `originator_conversation_id` = ?");
    $stmt->bind_param("ss",$conversation_id,$originator_conversation_id);
    $stmt->execute();
    
    if($result_desc == "The balance is insufficient for the transaction."){
        send_general_sms($phone_notifications,"Settlements can't be made on $appname because of insufficient funds");
    }
}