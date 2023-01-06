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
//id,user_id,amount,date_time,status,phone_sent,mpesa_ref,conversation_id,transaction_id

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
    $stmt = $conn->prepare("SELECT `user_id`,`transaction_cost` FROM `withdrawals_savings` WHERE (conversation_id = ? AND originator_conversation_id = ?)");
    $stmt->bind_param("ss",$conversation_id,$originator_conversation_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id,$transaction_cost);
    $stmt->fetch();	
        
    $stmt = $conn->prepare("SELECT `fullname`,`phone` FROM `user` WHERE `id` = ?");
    $stmt->bind_param("i",$user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($fullname,$fetched_phone);
    $stmt->fetch();
    
    $stmt = $conn->prepare("SELECT SUM(available_amount_with_interest) FROM `savings` WHERE `user_id` = ?");
    $stmt->bind_param("s",$user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($available_balance);
    $stmt->fetch(); 
        
    $status = "active";
    $stmt = $conn->prepare("SELECT SUM(`amount`), SUM(`transaction_cost`) FROM `withdrawals_savings` WHERE `user_id` = ? AND `status` = ?");
    $stmt->bind_param("s",$user_id,$status);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($total_withdrawals,$sum_transaction_costs);
    $stmt->fetch();
        
    $available_balance = $available_balance - ($total_withdrawals + $sum_transaction_costs);
    
    $message = "$appname: Hello $fullname. You have withdrawn a sum of Kshs.".number_format($amount)." from you wallet. Transaction cost is Kshs.".number_format($transaction_cost)." and available balance is Kshs.".number_format($available_balance);
    
    send_general_sms($fetched_phone,$message);
    

    $status = "active";
    $stmt = $conn->prepare("UPDATE `withdrawals_savings` SET `date_time` = ?,`phone_sent` = ?,`mpesa_ref` = ?,`status` = ? WHERE (conversation_id = ? AND originator_conversation_id = ?)");
    $stmt->bind_param("ssssss",$transaction_timestamp,$partyb,$transaction_id,$status,$conversation_id,$originator_conversation_id);
    $stmt->execute();

}else{
    

    $status = "failed";
    $transaction_timestamp = date("Y-m-s h:i:s");
    $stmt = $conn->prepare("UPDATE `withdrawals_savings` SET `date_time` = ?,`status` = ? WHERE (conversation_id = ? AND originator_conversation_id = ?)");
    $stmt->bind_param("ssss",$transaction_timestamp,$status,$conversation_id,$originator_conversation_id);
    $stmt->execute();

    $stmt = $conn->prepare("SELECT `user_id` FROM `withdrawals_savings` WHERE (conversation_id = ? AND originator_conversation_id = ?)");
    $stmt->bind_param("ss",$conversation_id,$originator_conversation_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id);
    $stmt->fetch();	
        
    $stmt = $conn->prepare("SELECT `fullname`,`phone` FROM `user` WHERE `id` = ?");
    $stmt->bind_param("i",$user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($fullname,$fetched_phone);
    $stmt->fetch();	

    send_general_sms($fetched_phone,"$appname: Withdrawa; failed, something went wrong with our systems. Please try again later.");
    
    
    if($result_desc == "The balance is insufficient for the transaction."){
        send_general_sms($phone_notifications,"Settlements can't be made on $appname because of insufficient funds");
    }
}