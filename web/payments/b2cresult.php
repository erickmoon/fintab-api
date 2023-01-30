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

    if($balance <= $low_amount_warning){
        send_general_sms($phone_notifications,"WARNING: B2C balance running low on $appname. Current balance is Kshs.$balance");
    }
    
    $status = "active";
    //file_put_contents("disbursed_loans_update.txt","Status: $status, result code: $result_code, result description: $result_desc, transaction id: $transaction_id, conversation id: $conversation_id, originator conversation id: $originator_conversation_id");
    $stmt = $conn->prepare("UPDATE `disbursed_loans` SET `status` = ?,`response_code` = ?,`response_description` = ?,`transaction_id` = ? WHERE (`conversation_id` = ? AND `originator_conversation_id` = ?)");
    $stmt->bind_param("ssssss",$status,$result_code,$result_desc,$transaction_id,$conversation_id,$originator_conversation_id);
    if($stmt->execute()){
        
    }
    
    //amount	transaction_ref	partya	partyb	description	conversation_id	originator_conversation_id	transaction_timestamp
    $partya = $shortcode;
    $description = "Loan disbursement";
    $stmt = $conn->prepare("INSERT INTO `mpesa_transactions_b2c`(`amount`,`transaction_ref`,`partya`,`partyb`,`description`,`conversation_id`,`originator_conversation_id`,`transaction_timestamp`) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->bind_param("ssssssss",$amount,$transaction_id,$partya,$partyb,$description,$conversation_id,$originator_conversation_id,$transaction_timestamp);
    $stmt->execute();
    
    $stmt = $conn->prepare("SELECT `user_id` FROM `disbursed_loans` WHERE `conversation_id` = ? AND `originator_conversation_id` = ?");
    $stmt->bind_param("ss",$conversation_id,$originator_conversation_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id);
    $stmt->fetch();

    $stmt = $conn->prepare("SELECT `fullname`,`phone` FROM `user` WHERE `id` = ?");
    $stmt->bind_param("i",$user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($fullname,$phone);
    $stmt->fetch();
    
    $phone = str_replace(" ","",$phone);
    $phone = str_replace("+254","",$phone);

        
    if(substr($phone, 0, 1) == "0"){
        $phone = str_replace("+","",$phone);
        $phone = substr($phone, 1);
        $phone = "254".$phone;  
    }else if(substr($phone, 0, 3) == "254"){
          
    }else{
    	$phone = "254".$phone;
    }
    $stmt = $conn->prepare("SELECT `due_date` FROM `disbursed_loans` WHERE `conversation_id` = ? AND `originator_conversation_id` = ?");
    $stmt->bind_param("ss",$conversation_id,$originator_conversation_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($due_date);
    $stmt->fetch();
    
    $due_date = date('D, M j, Y \a\t g:ia', strtotime($due_date));
    
    send_general_sms($phone_notifications,"$appname: Hello $fullname. We have disbursed a loan of Kshs.".number_format($amount)." to your MPESA mobile wallet. The loan is due on $due_date. Thank your for being a valued $app_name user.");
    
}else{
    //$partyb
    $stmt = $conn->prepare("SELECT `user_id` FROM `disbursed_loans` WHERE `conversation_id` = ? AND `originator_conversation_id` = ?");
    $stmt->bind_param("ss",$conversation_id,$originator_conversation_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id);
    $stmt->fetch();

    $stmt = $conn->prepare("SELECT `fullname`,`phone` FROM `user` WHERE `id` = ?");
    $stmt->bind_param("i",$user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($fullname,$phone);
    $stmt->fetch();
    
    $phone = str_replace(" ","",$phone);
    $phone = str_replace("+254","",$phone);

        
    if(substr($phone, 0, 1) == "0"){
        $phone = str_replace("+","",$phone);
        $phone = substr($phone, 1);
        $phone = "254".$phone;  
    }else if(substr($phone, 0, 3) == "254"){
          
    }else{
    	$phone = "254".$phone;
    }
        
    
    
    $partyb = $phone;
    
    //file_put_contents("partyb.txt","Party B is: $partyb");
    //send_general_sms("254710293886","Hello there");
    send_general_sms($partyb,"$appname: Loan disbursement failed, something went wrong with our systems. Please try again later.");
    
    $status = "failed";
    $stmt = $conn->prepare("DELETE FROM `disbursed_loans` WHERE `conversation_id` = ? AND `originator_conversation_id` = ?");
    $stmt->bind_param("ss",$conversation_id,$originator_conversation_id);
    $stmt->execute();
    
    if($result_desc == "The balance is insufficient for the transaction."){
        send_general_sms($phone_notifications,"Settlements can't be made on $appname because of insufficient funds");
    }
}