<?php
error_reporting(0);
header('Content-type: text/html; charset=utf-8');

include("../includes/dbconn.php");
require "../includes/class/class.phpmailer.php";
include("../includes/encdec.php");

$apiKEY = "0616013733edaa2fd371b981bd06118392e10b4a6fedaa2fd06caaa7706118392e10b0ea47d084a6f848a6fecbcc219f091f2db62e6";
$consumerKEY = "0ea47d098a6fecbcc219f091f2db62e630b2f319f8f395fb13733edaa2fd371b981bd06118392e10b4a6fedaa2fd06caaa770611";

$data = json_decode(file_get_contents('php://input'), true);

$savings_interest_rate = 0.01;
$savings_interest_days = 30;
$loans_interest_rate_per_day = 0.01;
$goods_interest_rate_per_day = 0.01;
$minimum_withdrawable = 500;
$maximum_withdrawable = 50000;

function get_withdrawal_charge($amount){
    if($amount <= 500){
        $savings_withdrawal_charge = 10;
    }elseif($amount > 500 and $amount < 1000){
        $savings_withdrawal_charge = 20;
    }elseif($amount > 1000 and $amount < 10000){
        $savings_withdrawal_charge = 30;
    }elseif($amount > 10000 and $amount < 20000){
        $savings_withdrawal_charge = 40;
    }elseif($amount > 20000 and $amount < 50000){
        $savings_withdrawal_charge = 50;
    }elseif($amount > 50000){
        $savings_withdrawal_charge = 60;
    }
    return $savings_withdrawal_charge;
}
$b2c_remarks_withdraw_from_savings = "salary";
$maximum_loan_amount = 10000;
$max_days_to_end_date = 5;        
$maxium_loan_days = 29;
$maxium_goods_loan_days = 29;
$loan_sales_factor = 10;
$minumum_loan_amount = 500;
$duration_of_sales_days_for_loan_calculation = 61;
$agrovets_user_id = 2;
$b2c_remarks = "SalaryPayment";

$savings_enabled = "yes";

$base_app_name = "FINTAB";
$paybill_shorcode_user = "539100 (QUOUGAR)";
$paybill_shorcode = "539100";
$deposit_paybill_shortcode = "539100";

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


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
function send_sms_loan($phone,$message,$user_id){
	    include("../includes/dbconn.php");
	    
        $phone = str_replace(" ","",$phone);
        $phone = str_replace("+254","",$phone);
        
        $date_today = date('Y-m-d');

        
        if(substr($phone, 0, 1) == "0"){
            $phone = str_replace("+","",$phone);
            $phone = substr($phone, 1);
            $phone = "254".$phone;  
        }else if(substr($phone, 0, 3) == "254"){
              
        }else{
        	$phone = "254".$phone;
		}

        $start = strtotime(''.$date_today.' 08:00:00');
        $end = strtotime(''.$date_today.' 17:00:00');
        
        if(time() >= $start && time() <= $end) {
    
            $stmt = $conn->prepare("SELECT `id` FROM `sent_sms` WHERE `user_id` = ? AND `date_sent` = ?");
            $stmt->bind_param("ss",$user_id,$date_today);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id_sent);
            $stmt->fetch();   
                
            if($id_sent === null){
                $stmt = $conn->prepare("INSERT INTO `sent_sms`(`date_sent`,`user_id`) VALUES (?,?)");
                $stmt->bind_param("ss",$date_today,$user_id);
                $stmt->execute();
                
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
        }

}

function writeFile($name, $string) {
    $filename = $name.".txt"; 
    $fp = fopen($filename,"a+");  
    fputs($fp,$string); 
    fclose($fp);  
}

function humanTiming ($time){

    $time = time() - $time; // to get the time since that moment
    $time = ($time<1)? 1 : $time;
    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
    }

}


if($data['apiKEY'] === $apiKEY and $data['consumerKEY'] === $consumerKEY and $data['service'] !== null){
    $service = $data['service'];
    if ($service === "do_login") {
    	$username = str_replace(" ", "",(strtolower($data['username'])));
    	$password = sha1($data['password']);
    	
    

        $stmt = $conn->prepare("SELECT `id`,`fullname`,`user_type`,`phone` FROM `user` WHERE (`phone` = ? OR `id_number` = ? OR `username` = ?) AND `password` = ?");
        $stmt->bind_param("ssss",$username,$username,$username,$password);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($user_id,$fullname,$user_type,$phone);
        $stmt->fetch();	
    

        if ($user_id !== null) {

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


            $otp_code = random_int(100000, 999999);
            //$otp_code = "123456";
              
              
              //send otp to farmer
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
            	"message":" You '.$base_app_name.' OTP code is '.$otp_code.'",
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
        
        
            $stmt = $conn->prepare("SELECT `user_type` FROM `user_type` WHERE `id` = ?");
            $stmt->bind_param("i",$user_type);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($user_type);
            $stmt->fetch();	
            
            $stmt = $conn->prepare("UPDATE `user` SET `otp_login` = ? WHERE `id` = ?");
            $stmt->bind_param("si",$otp_code,$user_type);
            $stmt->execute();
        
	       	$json['message'] = "success";
	        $json['fullname'] = $fullname;
	        $json['user_id'] = $user_id;
	        $json['user_type'] = $user_type;
	        $json['otp_code'] = $otp_code;
        }else{
        	$json['message'] = "failed";
        }
        
        echo json_encode($json);
    }elseif($service === "verify_porter"){
        $id_or_phone = $data['id_or_phone'];
        
        $stmt = $conn->prepare("SELECT `id`,`phone` FROM `user` WHERE (`phone` = ? OR `id_number` = ?)");
        $stmt->bind_param("ss",$id_or_phone,$id_or_phone);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($farmer_id,$phone);
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
        
        
        if($farmer_id === null){
            $json['message'] = "invalid";
        }else{
        
        
        $otp = random_int(100000, 999999);
          
          
          //send otp to farmer
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
        	"message":" You '.$base_app_name.' OTP code is '.$otp.'",
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
        //echo $response;
           
           
           
          $json['message'] = "success";
          $json['otp_farmer'] = $otp;
          $json['farmer_id'] = $farmer_id;
          
        }
        echo json_encode($json);
           
    
    }elseif ($service === "change_password") {
        $otp = $data['otp'];
        $password = sha1($data['password']);

        $stmt = $conn->prepare("UPDATE `user` SET `password` = ? WHERE `otp_login` = ?");
        $stmt->bind_param("ss",$password,$otp);
        $stmt->execute();

        $stmt = $conn->prepare("SELECT `id`,`user_type`,`fullname` FROM `user` WHERE `otp_login` = ?");
        $stmt->bind_param("s",$otp);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($user_id,$user_type,$fullname);
        $stmt->fetch();	
        
        
        $stmt = $conn->prepare("SELECT `user_type` FROM `user_type` WHERE `id` = ?");
        $stmt->bind_param("i",$user_type);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($user_type);
        $stmt->fetch();	
        

	    $json['message'] = "success";
	    $json['user_id'] = $user_id;
	    $json['user_name'] = $fullname;
	    $json['user_type'] = $user_type;
        
        echo json_encode($json);
        
    }elseif($service === "get_product_types"){
        $stmt = $conn->prepare("SELECT * FROM `products`");
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $array_products[] = $row;
        }
        
        for($i = 0; $i < sizeof($array_products); $i++){
            $unit_id = $array_products[$i]['unit_id'];
            
            $stmt = $conn->prepare("SELECT `name` FROM `units` WHERE `id` = ?");
            $stmt->bind_param("i",$unit_id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($unit_name);
            $stmt->fetch();
            
            $products[$i] = $array_products[$i]['name'];
        }
        
        $json['products'] = $products;
        $json['message'] = "success";
        
        echo json_encode($json);
    }elseif($service === "get_product_units"){
        $product_name = $data['product_name'];
                
        $stmt = $conn->prepare("SELECT `unit_id` FROM `products` WHERE `name` = ?");
        $stmt->bind_param("s",$product_name);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($unit_id);
        $stmt->fetch();
        
        $stmt = $conn->prepare("SELECT `name` FROM `units` WHERE `id` = ?");
        $stmt->bind_param("i",$unit_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($unit_name);
        $stmt->fetch();

        $json['unit_name'] = $unit_name;
        $json['message'] = "success";
        
        echo json_encode($json);
    }elseif($service === "record_measurement"){
        $porter_id = $data['porter_id'];
        $farmer_id = $data['farmer_id'];
        $selected_product = $data['selected_product'];
        $entered_amount = $data['entered_amount'];
        
        $stmt = $conn->prepare("SELECT `id` FROM `products` WHERE `name` = ?");
        $stmt->bind_param("s",$selected_product);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($product_id);
        $stmt->fetch();
        $created_by = "Max what field is this???";
        $date = date('Y-m-d');
        
        $stmt = $conn->prepare("INSERT INTO `measurements`(`porter_id`,`product_id`,`user_id`,`amount`,`date`,`created_by`)VALUES(?,?,?,?,?,?)");
        $stmt->bind_param("ssssss",$porter_id,$product_id,$farmer_id,$entered_amount,$date,$created_by);
        $stmt->execute();
        
        $json['message'] = "success";
        echo json_encode($json);
        
    }elseif($service === "send_otp_code"){
        $id_or_phone = $data['phone'];
        
        $stmt = $conn->prepare("SELECT `id`,`phone` FROM `user` WHERE (`phone` = ? OR `id_number` = ?)");
        $stmt->bind_param("ss",$id_or_phone,$id_or_phone);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($user_id,$phone);
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
        
        
        if($user_id === null){
            $json['message'] = "invalid";
        }else{
        
        
        $otp = $data['otp'];
        $stmt = $conn->prepare("UPDATE `user` SET `otp_login` = ? WHERE (`phone` = ? OR `id_number` = ?)");
        $stmt->bind_param("sss",$otp,$id_or_phone,$id_or_phone);
        $stmt->execute();          
          
          //send otp to farmer
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
        	"message":" You '.$base_app_name.' OTP code is '.$otp.'",
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
        //echo $response;
           
           
           
          $json['message'] = "success";
          $json['otp'] = $otp;
          $json['user_id'] = $user_id;
          
        }
        echo json_encode($json);
           
    
    }elseif($service === "get_loan_balance"){
        $user_id = $data['user_id'];
        
        $status = "active";
        $stmt = $conn->prepare("SELECT SUM(amount_with_interest) FROM `disbursed_loans` WHERE `user_id` = ? AND `status` = ?");
        $stmt->bind_param("ss",$user_id,$status);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($total_loan_given);
        $stmt->fetch();

        $stmt = $conn->prepare("SELECT SUM(amount) FROM `loan_repayment` WHERE `user_id` = ?");
        $stmt->bind_param("s",$user_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($total_repayment_received);
        $stmt->fetch();
        
        $loan_balance = round($total_loan_given - $total_repayment_received);
        
        $goods_status = "active";
        $stmt = $conn->prepare("SELECT SUM(amount_disbursed_with_interest) FROM `disbursed_goods_from_agrovets` WHERE `farmer_id` = ? AND `status` = ?");
        $stmt->bind_param("ss",$user_id,$goods_status);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($total_worth_of_goods_disbursed);
        $stmt->fetch();
        
        $stmt = $conn->prepare("SELECT SUM(amount) FROM `disbursed_goods_from_agrovets_repayments` WHERE `farmer_id` = ?");
        $stmt->bind_param("s",$user_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($total_disbursed_goods_from_agrovets_repayments);
        $stmt->fetch();
        
        $remaining_payment_for_goods_from_agrovets = ($total_worth_of_goods_disbursed - $total_disbursed_goods_from_agrovets_repayments);
        
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
            "service":"check_eligible_amount",
            "user_id":"'.$user_id.'"
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));
        
        $response = json_decode(curl_exec($curl),TRUE);
        
        curl_close($curl);
        $verified_limit = $response['amount_eligible'];
        
        $json['message'] = "success";
        $json['loan_limit'] = $verified_limit;
        $json['formatted_loan_limit'] = "Kshs.".number_format($verified_limit);
        $json['loan_balance'] = $loan_balance;
        $json['formatted_loan_balance'] = "Kshs.".number_format($loan_balance);
        $json['goods_from_agrovets_balance'] = $remaining_payment_for_goods_from_agrovets;
        $json['formatted_goods_from_agrovets_balance'] = "Kshs.".number_format($remaining_payment_for_goods_from_agrovets);
        $json['interest_rate'] = $loans_interest_rate_per_day;
        $json['minimum_amount'] = $minumum_loan_amount;
        $json['formatted_minimum_amount'] = "Kshs.".number_format($minumum_loan_amount);
        $json['max_loan_days'] = $maxium_loan_days;
        
        $start_date = date('Y-m-d');
        $deadline = date('Y-m-d', strtotime($start_date. ' + '.$maxium_loan_days.' days'));
        
        $deadline = date("D, M j, Y", strtotime($deadline));
        
        $json['deadline'] = $deadline;
        
        $json['savings_enabled'] = $savings_enabled;
        
        echo json_encode($json);
        
    }elseif($service === "check_eligible_amount"){
        $user_id = $data['user_id'];
        
        $goods_status = "active";
        $stmt = $conn->prepare("SELECT SUM(amount_disbursed_with_interest) FROM `disbursed_goods_from_agrovets` WHERE `farmer_id` = ? AND `status` = ?");
        $stmt->bind_param("ss",$user_id,$goods_status);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($total_worth_of_goods_disbursed);
        $stmt->fetch();
        
        $stmt = $conn->prepare("SELECT SUM(amount) FROM `disbursed_goods_from_agrovets_repayments` WHERE `farmer_id` = ?");
        $stmt->bind_param("s",$user_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($total_disbursed_goods_from_agrovets_repayments);
        $stmt->fetch();
        
        $remaining_payment_for_goods_from_agrovets = ($total_worth_of_goods_disbursed - $total_disbursed_goods_from_agrovets_repayments);
        
        $loan_status = "active";
        $stmt = $conn->prepare("SELECT SUM(amount_with_interest) FROM `disbursed_loans` WHERE `user_id` = ? AND `status` = ?");
        $stmt->bind_param("ss",$user_id,$loan_status);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($total_loan_given);
        $stmt->fetch();
        
        $stmt = $conn->prepare("SELECT SUM(amount) FROM `loan_repayment` WHERE `user_id` = ?");
        $stmt->bind_param("s",$user_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($total_repayment_received);
        $stmt->fetch();
        
        
        $loan_balance = ($total_loan_given - $total_repayment_received);
        

        // 2. Last loan due date
        $stmt = $conn->prepare("SELECT `date_due` FROM `disbursed_loans` WHERE `user_id` = ? ORDER BY `id` DESC LIMIT 1");
        $stmt->bind_param("s",$user_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($last_due_date);
        $stmt->fetch();
        
   
        //Get average value of sales
        $end_date = date("Y-m-d h:i:s");
        $start_date = date('Y-m-d h:i:s' , strtotime($end_date. ' - '.$duration_of_sales_days_for_loan_calculation.' days'));
        
        $stmt = $conn->prepare("SELECT `product_id`,`amount` FROM `measurements` WHERE `user_id` = ? AND `date` > ?");
        $stmt->bind_param("ss",$user_id,$start_date);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $porter_sales[] = $row;
        }
        
        
        $stmt = $conn->prepare("SELECT `product_id`,`amount` FROM `measurements_cooler` WHERE `user_id` = ? AND `date_time` > ?");
        $stmt->bind_param("ss",$user_id,$start_date);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $cooler_sales[] = $row;
        } 
        
        $porter_sales_total = 0;
        for($j = 0; $j < sizeof($porter_sales); $j++){
            $amount = $porter_sales[$j]['amount'];
            $product_id = $porter_sales[$j]['product_id'];
            
            $stmt = $conn->prepare("SELECT `buying_price` FROM `prices` WHERE `product_id` = ?");
            $stmt->bind_param("s",$product_id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($buying_price);
            $stmt->fetch();
            
            $cost_of_product = round(($buying_price * $amount),2);
            
            $porter_sales_total = $porter_sales_total + $cost_of_product;
            
        }
        $porter_sales_average = round($porter_sales_total/sizeof($porter_sales),2);
        $cooler_sales_total = 0;
        for($i = 0; $i < sizeof($cooler_sales); $i++){
            $amount = $cooler_sales[$i]['amount'];
            $product_id = $cooler_sales[$i]['product_id'];
            
            $stmt = $conn->prepare("SELECT `buying_price` FROM `prices` WHERE `product_id` = ?");
            $stmt->bind_param("s",$product_id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($buying_price);
            $stmt->fetch();
            
            $cost_of_product = round(($buying_price * $amount),2);
            
            $cooler_sales_total = $cooler_sales_total + $cost_of_product;
            
        }
        $cooler_sales_average = round($cooler_sales_total/sizeof($cooler_sales),2);
        $total_average_sales = round(($porter_sales_total + $cooler_sales_total)/(sizeof($cooler_sales) + sizeof($porter_sales)),2);

   
        
        //3. Value max eligible from sales
        $max_eligible_value = $total_average_sales * $loan_sales_factor;
        
        if($max_eligible_value > $maximum_loan_amount){
            $max_eligible_value = $maximum_loan_amount;
        }
 
        $start_date = $last_due_date;
        $max_date = date('Y-m-d h:i:s' , strtotime($start_date. ' + '.$max_days_to_end_date.' days'));
        
        // Begin checks
        //echo "Start date:$start_date Max date: $max_date";
        //Start date:2023-02-02 12:12:12 Max date: 2023-02-07 12:12:12
        //Cant apply loan 5 days before due date
        if(($loan_balance < $maximum_loan_amount) and (new DateTime($max_date) > new DateTime(date('Y-m-d h:i:s')))){
            //User qualifies for x_amount
            $eligible_loan = $max_eligible_value - $loan_balance - $remaining_payment_for_goods_from_agrovets;
            if(($eligible_loan < 0) or ($minumum_loan_amount > $eligible_loan)){
                $eligible_loan = 0;
            }
        }else{
            $eligible_loan = 0;
        }
        
        $json['message'] = "success";
        $json['amount_eligible'] = $eligible_loan;
        $json['minimum_amount'] = $minumum_loan_amount;
        $json['formatted_eligible_amount'] = "Kshs.".number_format($eligible_loan);
        $json['formatted_minimum_amount'] = "Kshs.".number_format($minumum_loan_amount);
        
        echo json_encode($json);
    
    }elseif($service === "request_loan"){
        $user_id = $data['user_id'];
        $amount = $data['amount'];
        
        $stmt = $conn->prepare("SELECT `phone` FROM `user` WHERE `id` = ?");
        $stmt->bind_param("i",$user_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($phone);
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
        //Get general loan limit
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
            "service":"check_eligible_amount",
            "user_id":"'.$user_id.'"
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));
        
        $response = json_decode(curl_exec($curl),TRUE);
        
        curl_close($curl);
        $verified_limit = $response['amount_eligible'];
        
        
        if(($amount-1) < $verified_limit){
            //  Send money

            $curl = curl_init();
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://www.shuklo.com/payments/b2c.php',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>'{
                "apiKEY":"caaa7706118392e10b0ea47d084a6f848a6fecbcc219f091f2db62e60616013733edaa2fd371b981bd06118392e10b4a6fedaa2fd06",
                "consumerKEY":"a2fd371b981bd06118392e10b4a6fedaa2fd06caaa7706110ea47d098a6fecbcc219f091f2db62e630b2f319f8f395fb13733eda",
                "service":"disburse_funds",
                "amount":"'.$amount.'",
                "phone":"'.$phone.'",
                "remarks":"'.$b2c_remarks.'"
            }',
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
              ),
            ));
            
        
            $response = json_decode(curl_exec($curl),TRUE);
            
            $response_code = $response['response_code'];
            $conversation_id = $response['conversation_id'];
            $originator_conversation_id = $response['originator_conversation_id'];
            $response_description = $response['response_description'];
            
          
            if($response_code == 0){
                $last_interest_charge_date = date('Y-m-d');
                
                $date_issued = date('Y-m-d h:i:s');
                $start_date = $date_issued;
                $status = "inactive";
                $created_by = "android_app";
                $amount_with_interest  = $amount + ($amount * $loans_interest_rate_per_day);
                
                
                //Record disbursement
                //Check if user has another loan, if so, pick due date of previous loan, else, calculate new due date
                $status___ = "active";
                $stmt = $conn->prepare("SELECT `date_due` FROM `disbursed_loans` WHERE `user_id` = ? AND `status` = ? ORDER BY `id` DESC LIMIT 1");
                $stmt->bind_param("ss",$user_id,$status___);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($last_due_date);
                $stmt->fetch();
                if($last_due_date == null or $last_due_date == ""){
                    $due_date = date('Y-m-d' , strtotime($start_date. ' + '.$maxium_loan_days.' days'));
                }else{
                    $due_date = $last_due_date;
                }
                $filtered_originator_conversation_id = $originator_conversation_id;
                
                $stmt = $conn->prepare("INSERT INTO `disbursed_loans`(`user_id`,`disbursed_amount`,`amount_with_interest`,`date_issued`,`date_due`,`last_interest_charge_date`,`status`,`created_by`,`response_code`,`conversation_id`,`originator_conversation_id`,`response_description`) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)");
                $stmt->bind_param("ssssssssssss",$user_id,$amount,$amount_with_interest,$date_issued,$due_date,$last_interest_charge_date,$status,$created_by,$response_code,$conversation_id,$filtered_originator_conversation_id,$response_description);
                if($stmt->execute()){

                    $json['message'] = "success";
                }else{
                    $json['message'] = "failed";
                }
            }else{
                $message = "$base_app_name: Hello $fullname, Your withdrawal request FAILED due to an internal system error. Please try again later. Thank your for being a valued $base_app_name customer.";
                send_general_sms($phone,$message);
            }
          
        }else{
            $json['message'] = "failed";
        }
       
        echo json_encode($json);
      
        
    }elseif($service === "repay_loan"){
        $amount = $data['amount'];
        $loan_id = "No longer used";
        $status = "success";
        $mpesa_receipt = $data['mpesa_receipt'];
        $phone = $data['phone'];
        
        $date_time = date("Y-m-d h:i:s");
        
        //Update DBs & Send message
        $stmt = $conn->prepare("UPDATE `mpesa_transactions_c2b` SET `status` = ?,`transaction_ref` = ?,`phone` = ?,`completed_date_time` = ? WHERE `merchant_request_id` = ? AND `checkout_request_id` = ?");
        $stmt->bind_param("ssssss",$status,$mpesa_receipt,$phone,$date_time,$merchant_request_id,$checkout_request_id);
        $stmt->execute();

        $stmt = $conn->prepare("SELECT `account_ref` FROM `mpesa_transactions_c2b` WHERE `merchant_request_id` = ? AND `checkout_request_id` = ?");
        $stmt->bind_param("ss",$merchant_request_id,$checkout_request_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($user_id);
        $stmt->fetch();
        
        $transaction_id = $data['mpesa_receipt'];
        $date_paid = date('Y-m-d h:i:s');

        $stmt = $conn->prepare("INSERT INTO `loan_repayment`(`loan_id`,`user_id`,`amount`,`transaction_id`,`date`,`created_by`)
            VALUES(?,?,?,?,?,?)");
        $stmt->bind_param("ssssss",$loan_id,$user_id,$amount,$transaction_id,$date_paid,$created_by);
        $stmt->execute();

        // 1. Total loan given
        $stmt = $conn->prepare("SELECT SUM(`amount_with_interest`) FROM `disbursed_loans` WHERE `user_id` = ?");
        $stmt->bind_param("s",$user_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($total_loan_given);
        $stmt->fetch();
        
        $stmt = $conn->prepare("SELECT SUM(`amount`) FROM `loan_repayment` WHERE `user_id` = ?");
        $stmt->bind_param("s",$user_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($total_repayment_received);
        $stmt->fetch();
        
        $loan_balance = round($total_loan_given - $total_repayment_received);  

        
        if($loan_balance <= 0){
            $status = "inactive";
            $stmt = $conn->prepare("UPDATE `disbursed_loans` SET `status` = ? WHERE `user_id` = ?");
            $stmt->bind_param("ss",$status,$user_id);
            $stmt->execute();
        }
        
        $stmt = $conn->prepare("SELECT `fullname`,`phone` FROM `user` WHERE `id` = ?");
        $stmt->bind_param("i",$user_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($fullname,$fetched_phone);
        $stmt->fetch();	

        //Get loan balance
        // Loan balance
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
            "service":"get_loan_balance",
            "user_id":"'.$user_id.'"
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));
        
        $response = json_decode(curl_exec($curl),TRUE);
        
        curl_close($curl);

        
        
        $loan_balance = $response['loan_balance'];
        $formatted_loan_balance = $response['formatted_loan_balance'];
        //Send message of loan confirmation
        $date_time_formatted = date('D, M j, Y \a\t g:ia');
        $message = "$base_app_name: Congrats $fullname"."! We have received your payment of Kshs.".number_format($amount)." on $date_time_formatted".". Your outstanding loan balance is ".$formatted_loan_balance.". Thank your for being a valuable client.";
        
        send_general_sms($fetched_phone,$message);
        if($fetched_phone != $phone){
            send_general_sms($phone,$message);
        }
        
        $json['message'] = "success";
        
        echo json_encode($json);        
    }elseif($service === "loan_statement"){
        //DO NOT USE THIS SERVICE
        $subtype = $data['sub_type'];
        $user_id = $data['user_id'];
  
   
        if($subtype == "repayments"){
            $stmt = $conn->prepare("SELECT * FROM `loan_repayment` WHERE `user_id` = ? ORDER BY `id` DESC");
            $stmt->bind_param("s",$user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $array_repayments[] = $row;
            } 
            for($i = 0; $i < sizeof($array_disbursements); $i++){
                $array_disbursements[$i]['formatted_date'] = date('D, M j, Y \a\t g:ia', strtotime($array_disbursements[$i]['date']));
            }
            $json['array_repayments'] = $array_repayments;
            
        }elseif($subtype == "disbursements"){
            
            $stmt = $conn->prepare("SELECT * FROM `disbursed_loans` WHERE `user_id` = ? ORDER BY `id` DESC");
            $stmt->bind_param("s",$user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $array_disbursements[] = $row;
            } 
            for($i = 0; $i < sizeof($array_disbursements); $i++){
                $array_disbursements[$i]['formatted_date'] = date('D, M j, Y \a\t g:ia', strtotime($array_disbursements[$i]['date_issued']));
            }
            $json['array_disbursements'] = $array_disbursements;
        }
        echo json_encode($json);
    }elseif($service === "cron_loans_interest_adder"){
        //Get all active
        $status = "active";
        
        $stmt = $conn->prepare("SELECT * FROM `disbursed_loans` WHERE `status` = ?");
        $stmt->bind_param("s",$status);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $array_active_loans[] = $row;
        } 
        $date_today = date('Y-m-d');
        for($i = 0; $i < sizeof($array_active_loans); $i++){
            $amount_with_interest = $array_active_loans[$i]['amount_with_interest'];
            $last_updated_date = $array_active_loans[$i]['last_interest_charge_date'];
            $id = $array_active_loans[$i]['id'];

            // Begin checks
            if((new DateTime($last_updated_date) < new DateTime($date_today))){
                $amount_with_interest = $amount_with_interest + ($amount_with_interest * $loans_interest_rate_per_day);

                $stmt = $conn->prepare("UPDATE `disbursed_loans` SET `amount_with_interest` = ? WHERE `id` = ?");
                $stmt->bind_param("si",$amount_with_interest,$id);
                $stmt->execute();
            }
        }
    }elseif($service === "cron_goods_interest_adder"){
       //Get all active
        $status = "active";
        
        $stmt = $conn->prepare("SELECT * FROM `disbursed_goods_from_agrovets` WHERE `status` = ?");
        $stmt->bind_param("s",$status);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $array_active_goods[] = $row;
        } 
        $date_today = date('Y-m-d');
        for($i = 0; $i < sizeof($array_active_goods); $i++){
            $amount_disbursed_with_interest = $array_active_goods[$i]['amount_disbursed_with_interest'];
            $last_updated_date = $array_active_goods[$i]['last_interest_charge_date'];
            $id = $array_active_goods[$i]['id'];

            // Begin checks
            if((new DateTime($last_updated_date) < new DateTime($date_today))){
                $amount_disbursed_with_interest = $amount_disbursed_with_interest + ($amount_disbursed_with_interest * $goods_interest_rate_per_day);

                $stmt = $conn->prepare("UPDATE `disbursed_goods_from_agrovets` SET `amount_disbursed_with_interest` = ? WHERE `id` = ?");
                $stmt->bind_param("si",$amount_disbursed_with_interest,$id);
                $stmt->execute();
            }
        }
    }elseif($service === "cron_message_notifier"){
        /*  TO DO
        *   1. Get all active loans
        *   2. Check due date
        *   3. Message users who are a day to due date
        *   4. Message users who are in due date
        *   5. Message users who have defulted
        *  
        *
        *        $message_for_users_who_are_a_day_to_due_date = "$app_name: Hello, Your loan of KES is due date is due tomorrow. Please pay on time.Enter Paybill number: $paybill_shorcode_user and Account Number: ";
        *        $message_for_users_who_are_in_due_date = ;
        *        $message_for_users_who_have_defulted = ;
        *
        */
        
        $status = "active";
        
        $stmt = $conn->prepare("SELECT * FROM `disbursed_loans` WHERE `status` = ?");
        $stmt->bind_param("s",$status);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $array_active_loans[] = $row;
        } 
        $date_today = date('Y-m-d');
        
        for($i = 0; $i < sizeof($array_active_loans); $i++){
            $amount_with_interest = $array_active_loans[$i]['amount_with_interest'];
            $last_updated_date = $array_active_loans[$i]['last_interest_charge_date'];
            $due_date = $array_active_loans[$i]['date_due'];
            $id = $array_active_loans[$i]['id'];
            $user_id = $array_active_loans[$i]['user_id'];
            
            $stmt = $conn->prepare("SELECT `fullname`,`phone` FROM `user` WHERE `id` = ?");
            $stmt->bind_param("i",$user_id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($fullname,$phone);
            $stmt->fetch();	
            
            //Loan balance
            // 1. Total loan given
            $stmt = $conn->prepare("SELECT SUM(`amount_with_interest`) FROM `disbursed_loans` WHERE `user_id` = ?");
            $stmt->bind_param("s",$user_id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($total_loan_given);
            $stmt->fetch();
            
            $stmt = $conn->prepare("SELECT SUM(`amount`) FROM `loan_repayment` WHERE `user_id` = ?");
            $stmt->bind_param("s",$user_id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($total_repayment_received);
            $stmt->fetch();
            
            $loan_balance = number_format(round($total_loan_given - $total_repayment_received)).".00"; 

            // Begin checks
            // 1. users who are a day to due date
                // 1.1 Subtract 1 day to due date
            $a_day_to_due_date = date('Y-m-d' , strtotime($due_date. ' - 1 days'));
            
            if((new DateTime($date_today) === new DateTime($a_day_to_due_date))){
                // A day to due date
                $message = "$base_app_name: Hello $fullname. Your loan which is currently at Kshs.$loan_balance is due tomorrow. Please make payment on time. Paybill Business Number: $paybill_shorcode_user & Account Number: $phone";
                send_sms_loan($phone,$message,$user_id);
            }
            if((new DateTime($date_today) === new DateTime($due_date))){
                // Due date
                $message = "$base_app_name: Hello $fullname. Your loan which is currently at Kshs.$loan_balance is due today. Make payment on time. Paybill Business Number: $paybill_shorcode_user & Account Number: $phone";
                send_sms_loan($phone,$message,$user_id);
            }
            if((new DateTime($date_today) > new DateTime($due_date))){
                // Past Due date
                $message = "$base_app_name: Hello $fullname. Your loan which is currently at Kshs.$loan_balance is past due date. Please make payment immediately on Paybill Business Number: $paybill_shorcode_user & Account Number: $phone";
                send_sms_loan($phone,$message,$user_id);
            }
        }
    }elseif($service === "get_account_balances"){
        $user_id = $data['user_id'];
        
        // Loan balance
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
            "service":"get_loan_balance",
            "user_id":"'.$user_id.'"
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));
        
        $response = json_decode(curl_exec($curl),TRUE);
        
        curl_close($curl);

        $json['loan_balance'] = round($response['loan_balance']);
    }elseif($service === "fetch_farmers_agrovets"){
        $user_id = $data['user_id'];

        $stmt = $conn->prepare("SELECT `cooperative_id` FROM `user` WHERE `id` = ?");
        $stmt->bind_param("i",$user_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($cooperative_id);
        $stmt->fetch();	
        
        $stmt = $conn->prepare("SELECT `id`,`fullname`,`phone`,`location` FROM `user` WHERE `user_type` = ? AND `cooperative_id` = ?");
        $stmt->bind_param("ss",$agrovets_user_id,$cooperative_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $agrovets_array[] = $row;
        }
        $agrovets;
        for($i = 0; $i < sizeof($agrovets_array); $i++){
            $agrovets[$i] = $agrovets_array[$i]['fullname']." - ".$agrovets_array[$i]['phone']." - ".$agrovets_array[$i]['location']."-".$agrovets_array[$i]['id'];
        }
        $json['loan_goods_instructions'] = "This service will be charged an interest of ".($goods_interest_rate_per_day*100)."% per day";
        $json['message'] = "success";
        $json['agrovets'] = $agrovets;
        
        echo json_encode($json);
    }elseif($service === "submit_goods_request"){
        $user_id = $data['user_id'];
        $amount_entered = $data['amount_entered'];
        $selected_agrovet = $data['selected_agrovet'];
        
        $selected_agrovet_ = explode('-', $selected_agrovet);
        $agrovet_id = $selected_agrovet_[3];
        $amount_with_interest = $amount_entered + ($amount_entered * $goods_interest_rate_per_day);
        $last_interest_charge_date = date('Y-m-d');
        $date_initiated = date('Y-m-d h:i:s');
        $due_date = date('Y-m-d' , strtotime($last_interest_charge_date. ' + '.$maxium_goods_loan_days.' days'));
        $status = "waiting_verification";

        $stmt = $conn->prepare("SELECT `phone` FROM `user` WHERE `id` = ?");
        $stmt->bind_param("i",$agrovet_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($phone_agrovet);
        $stmt->fetch();	

        $phone_agrovet = str_replace(" ","",$phone_agrovet);
        $phone_agrovet = str_replace("+254","",$phone_agrovet);

        
        if(substr($phone_agrovet, 0, 1) == "0"){
            $phone_agrovet = str_replace("+","",$phone_agrovet);
            $phone_agrovet = substr($phone_agrovet, 1);
            $phone_agrovet = "254".$phone_agrovet;  
        }else if(substr($phone_agrovet, 0, 3) == "254"){
              
        }else{
            $phone_agrovet = "254".$phone_agrovet;
        }

        $stmt = $conn->prepare("SELECT `phone` FROM `user` WHERE `id` = ?");
        $stmt->bind_param("i",$user_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($phone_farmer);
        $stmt->fetch();	

        $phone_farmer = str_replace(" ","",$phone_farmer);
        $phone_farmer = str_replace("+254","",$phone_farmer);

        
        if(substr($phone_farmer, 0, 1) == "0"){
            $phone_farmer = str_replace("+","",$phone_farmer);
            $phone_farmer = substr($phone_farmer, 1);
            $phone_farmer = "254".$phone_farmer;  
        }else if(substr($phone_farmer, 0, 3) == "254"){
              
        }else{
            $phone_farmer = "254".$phone_farmer;
        }
        
        $message_farmer = "OTP for disbursement has been sent to agrovet. Make sure to key in OTP only after receiving requested value of goods";
        send_general_sms($phone_farmer,$message_farmer);
        
        $otp = random_int(100000, 999999);
        $message_agrovet = "Your disbursement OTP is $otp. Give this OTP to farmer and ensure they submit it once your products are disbursed to them. Loss of funds/fraud may occur if you do not ensure this.";
        send_general_sms($phone_agrovet,$message_agrovet);
        
        $stmt = $conn->prepare("INSERT INTO `disbursed_goods_from_agrovets`(`farmer_id`,`agrovet_id`,`amount_disbursed`,`amount_disbursed_with_interest`,`date_initiated`,`due_date`,`last_interest_charge_date`,`otp`,`status`) 
        VALUES(?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("sssssssss",$user_id,$agrovet_id,$amount_entered,$amount_with_interest,$date_initiated,$due_date,$last_interest_charge_date,$otp,$status);
        $stmt->execute();

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
            "service":"check_eligible_amount",
            "user_id":"'.$user_id.'"
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));
        
        $response = json_decode(curl_exec($curl),TRUE);
        
        curl_close($curl);
        $verified_limit = $response['amount_eligible'];
 
        // Test amount again
        if(($amount_entered - 10) > $verified_limit){
            $json['message'] = "invalid";
        }else{
            $json['message'] = "success";
            $json['otp'] = $otp;
        }
        echo json_encode($json);
    }elseif($service === "confirm_goods"){
        $otp = $data['otp'];
        $status = "active";
        
        $stmt = $conn->prepare("UPDATE `disbursed_goods_from_agrovets` SET `status` = ? WHERE `otp` = ?");
        $stmt->bind_param("ss",$status,$otp);
        $stmt->execute();
        
        $json['message'] = "success";
        $json['otp'] = $otp;
        
        echo json_encode($json);
        
    }elseif($service === "change_password_account"){
        $user_id = $data['user_id'];
        $old_password = sha1($data['old_password']);
        $new_password = sha1($data['new_password']);
        
        $stmt = $conn->prepare("SELECT `password` FROM `user` WHERE `id` = ?");
        $stmt->bind_param("i",$user_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($password);
        $stmt->fetch();	
        
        if($password === $old_password){
            $stmt = $conn->prepare("UPDATE `user` SET `password` = ? WHERE `id` = ?");
            $stmt->bind_param("si",$new_password,$user_id);
            $stmt->execute();
            
            $json['message'] = "success";
        }else{
            $json['message'] = "invalid";
        }
        echo json_encode($json);
    }elseif($service === "get_loan_repayment_details"){
        $user_id = $data['user_id']; 
        // Phone
        $stmt = $conn->prepare("SELECT `phone` FROM `user` WHERE `id` = ?");
        $stmt->bind_param("i",$user_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($phone);
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
        
        // Loan balance
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
            "service":"get_loan_balance",
            "user_id":"'.$user_id.'"
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));
        
        $response = json_decode(curl_exec($curl),TRUE);
        
        curl_close($curl);

        $json['loan_balance'] = round($response['loan_balance']);
        $json['formatted_loan_balance'] = $response['formatted_loan_balance'];
        $json['message'] = "success";
        $json['phone'] = $phone;
        $json['paybill_shortcode'] = $paybill_shorcode;
        
        echo json_encode($json);
    }elseif($service === "trigger_payment"){
        $account_number = $data['account_number'];
        $phone = $data['phone'];
        $amount = $data['amount'];
        $user_id = $account_number;

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
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://www.shuklo.com/payments/c2b.php',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
            "apiKEY":"18392e10b0ea47d1b981bd06118392e10b084a6f848a6fecbcc219f091f2db62e60616013733edaa2fd37caaa770614a6fedaa2fd06",
            "consumerKEY":"daa2b4a6fef319f8f395fb13733efd06caaa7706110ea47d098a6fecbcc219f091f2db62e630b2a2fd371b981bd06118392e10da",
            "service":"trigger_payment_online",
            "account_number":"'.$account_number.'",
            "phone":"'.$phone.'",
            "amount":"'.$amount.'"
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));
        $response = json_decode(curl_exec($curl),TRUE);
        curl_close($curl);
        
        $merchant_request_id = $response['merchant_request_id'];
        $checkout_request_id = $response['checkout_request_id'];
        $response_code = $response['response_code'];
        $response_description = $response['response_description'];
        $customer_message = $response['customer_message'];
        
        
        //  Dump data
        $payment_descritiption = "loan repayment";
        $stmt = $conn->prepare("INSERT INTO `mpesa_transactions_c2b`(`account_ref`,`patya`,`partyb`,`amount`,`payment_descritiption`,`merchant_request_id`,`checkout_request_id`,`response_code`,`response_description`,`customer_message`) 
        VALUES (?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("ssssssssss",$account_number,$phone,$paybill_shorcode,$amount,$payment_descritiption,$merchant_request_id,$checkout_request_id,$response_code,$response_description,$customer_message);
        $stmt->execute();        
        
        if($response_code === "0"){
            $json['message'] = "success";
        }else{
            $json['message'] = "failed";
        }
        
        echo json_encode($json);
    }elseif($service === "repay_loan_c2b"){
        $amount = $data['amount'];
        $loan_id = "No longer used";
        $status = "success";
        $mpesa_receipt = $data['transaction_ref'];
        $account_ref = $data['account_ref'];
        $phone = $data['partya'];
        $payment_descritiption = $data['payment_descritiption'];
        $patya = $data['partya'];
        $partyb = $data['partyb'];
        $date_time = date("Y-m-d h:i:s");
        $completed_date_time = $date_time;
        $transaction_ref = $mpesa_receipt;
        $customer_name = $data['customer_name'];//Missing
        
        $stmt = $conn->prepare("SELECT `id`,`status` FROM `mpesa_transactions_c2b` WHERE `transaction_ref` = ?");
        $stmt->bind_param("s",$transaction_ref);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($trans_id,$status);
        $stmt->fetch();
  
        if($trans_id === "" or $trans_id === null){
            $stmt = $conn->prepare("INSERT INTO `mpesa_transactions_c2b`(`transaction_ref`,`account_ref`,`patya`,`partyb`,`customer_name`,`amount`,`payment_descritiption`,`phone`,`completed_date_time`,`status`)VALUES(?,?,?,?,?,?,?,?,?,?)");
            $stmt->bind_param("ssssssssss",$transaction_ref,$account_ref,$patya,$partyb,$customer_name,$amount,$payment_descritiption,$phone,$completed_date_time,$status);
            $stmt->execute();
    
            $user_id = $account_ref;
            
            $transaction_id = $data['transaction_ref'];
            $date_paid = date('Y-m-d h:i:s');
    
            $stmt = $conn->prepare("INSERT INTO `loan_repayment`(`loan_id`,`user_id`,`amount`,`transaction_id`,`date`,`created_by`)
                VALUES(?,?,?,?,?,?)");
            $stmt->bind_param("ssssss",$loan_id,$user_id,$amount,$transaction_id,$date_paid,$created_by);
            $stmt->execute();
        }else{
            if($status === "success"){
                
                $stmt = $conn->prepare("UPDATE `mpesa_transactions_c2b` SET `customer_name` = ?,`status` = ? WHERE `id` = ?");
                $stmt->bind_param("ssi",$customer_name,$status,$trans_id);
                $stmt->execute();
                
            }else{
                
                $stmt = $conn->prepare("UPDATE `mpesa_transactions_c2b` SET `customer_name` = ?,`status` = ? WHERE `id` = ?");
                $stmt->bind_param("ssi",$customer_name,$status,$trans_id);
                $stmt->execute();
        
                $user_id = $account_ref;
                
                $transaction_id = $data['transaction_ref'];
                $date_paid = date('Y-m-d h:i:s');
        
                $stmt = $conn->prepare("INSERT INTO `loan_repayment`(`loan_id`,`user_id`,`amount`,`transaction_id`,`date`,`created_by`)
                    VALUES(?,?,?,?,?,?)");
                $stmt->bind_param("ssssss",$loan_id,$user_id,$amount,$transaction_id,$date_paid,$created_by);
                $stmt->execute();
                
            }
        }

        // 1. Total loan given
        $stmt = $conn->prepare("SELECT SUM(`amount_with_interest`) FROM `disbursed_loans` WHERE `user_id` = ?");
        $stmt->bind_param("s",$user_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($total_loan_given);
        $stmt->fetch();
        
        $stmt = $conn->prepare("SELECT SUM(`amount`) FROM `loan_repayment` WHERE `user_id` = ?");
        $stmt->bind_param("s",$user_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($total_repayment_received);
        $stmt->fetch();
        
        $loan_balance = round($total_loan_given - $total_repayment_received);  

        
        if($loan_balance <= 0){
            $status = "inactive";
            $stmt = $conn->prepare("UPDATE `disbursed_loans` SET `status` = ? WHERE `user_id` = ?");
            $stmt->bind_param("ss",$status,$user_id);
            $stmt->execute();
        }
        
        $stmt = $conn->prepare("SELECT `fullname`,`phone` FROM `user` WHERE `id` = ?");
        $stmt->bind_param("i",$user_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($fullname,$fetched_phone);
        $stmt->fetch();	

        //Get loan balance
        // Loan balance
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
            "service":"get_loan_balance",
            "user_id":"'.$user_id.'"
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));
        
        $response = json_decode(curl_exec($curl),TRUE);
        
        curl_close($curl);

        
        
        $loan_balance = $response['loan_balance'];
        $formatted_loan_balance = $response['formatted_loan_balance'];
        //Send message of loan confirmation
        $date_time_formatted = date('D, M j, Y \a\t g:ia');
        $message = "$base_app_name: Congrats $fullname"."! We have received your payment of Kshs.".number_format($amount)." on $date_time_formatted".". Your outstanding loan balance is ".$formatted_loan_balance.". Thank your for being a valuable client.";
        
        send_general_sms($fetched_phone,$message);
        if($fetched_phone != $phone){
            send_general_sms($phone,$message);
        }
        
        $json['message'] = "success";
        
        echo json_encode($json);  
    }elseif($service === "get_loan_report"){
        $sub_type = $data['sub_type'];
        $user_id = $data['user_id'];
        
        //Get reports
        if($sub_type === "disbursements"){
            $status = "active";
            $stmt = $conn->prepare("SELECT * FROM `disbursed_loans` WHERE `user_id` = ? AND `status` = ? ORDER BY `id` DESC");
            $stmt->bind_param("ss",$user_id,$status);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $disbursement_array[] = $row;
            }
            for($i = 0; $i < sizeof($disbursement_array); $i++){
                $disbursement_array[$i]['date'] = date('D, M j, Y \a\t g:ia', strtotime($disbursement_array[$i]['date_issued']));
                $disbursement_array[$i]['amount'] = "Kshs.".number_format($disbursement_array[$i]['disbursed_amount']);
            }
            
            $json['message'] = "success";
            $json['reports'] = $disbursement_array;
            
            echo json_encode($json);
        }elseif($sub_type === "repayments"){
            $stmt = $conn->prepare("SELECT * FROM `loan_repayment` WHERE `user_id` = ? ORDER BY `id` DESC");
            $stmt->bind_param("s",$user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $repayment_array[] = $row;
            }
            for($i = 0; $i < sizeof($disbursement_array); $i++){
                $disbursement_array[$i]['date'] = date('D, M j, Y \a\t g:ia', strtotime($disbursement_array[$i]['date']));
                $disbursement_array[$i]['amount'] = "Kshs.".number_format($disbursement_array[$i]['amount']);
            }
            
            $json['message'] = "success";
            
            echo json_encode($json);
        }
        
    }elseif($service === "get_porter_collection_reports"){
        $user_id = $data['user_id'];
        
        $status = "active";
        $stmt = $conn->prepare("SELECT * FROM `measurements` WHERE `porter_id` = ? ORDER BY `id` DESC");
        $stmt->bind_param("s",$user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $measurements_array[] = $row;
        }
        
        for($i = 0; $i < sizeof($measurements_array); $i++){
            $measurements_array[$i]['date'] = date('D, M j, Y \a\t g:ia', strtotime($measurements_array[$i]['date']));
            
            $product_id = $measurements_array[$i]['product_id'];
            
            $stmt = $conn->prepare("SELECT `name`,`unit_id` FROM `products` WHERE `id` = ?");
            $stmt->bind_param("i",$product_id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($product_name,$product_unit_id);
            $stmt->fetch();	
            
            $measurements_array[$i]['product'] = $product_name;
            
            $stmt = $conn->prepare("SELECT `name` FROM `units` WHERE `id` = ?");
            $stmt->bind_param("i",$product_unit_id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($unit_name);
            $stmt->fetch();	
            
            $user_id = $measurements_array[$i]['user_id'];
            
            
            $stmt = $conn->prepare("SELECT `fullname`,`phone` FROM `user` WHERE `id` = ?");
            $stmt->bind_param("i",$user_id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($fullname,$fetched_phone);
            $stmt->fetch();	
            
            
            $measurements_array[$i]['farmer'] = strtoupper($fullname."(".$fetched_phone.")");
            $measurements_array[$i]['quantity'] = number_format($measurements_array[$i]['amount'])." ".$unit_name;
            
        } 
        
        $json['reports'] = $measurements_array;
        $json['message'] = "success";
        
        echo json_encode($json);
    }elseif($service === "get_open_orders_agrovet"){
        $user_id = $data['user_id'];
        $start_date = $data['start_date'];
        $end_date = $data['end_date'];

        $status = "waiting_verification";
        $stmt = $conn->prepare("SELECT * FROM `disbursed_goods_from_agrovets` WHERE `status` = ? AND `agrovet_id` = ? AND (`date_initiated` BETWEEN ? AND ?) ORDER BY `id` DESC");
        $stmt->bind_param("ssss",$status,$user_id,$start_date,$end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $open_orders_array[] = $row;
        }
        // Date time (ago),  Farmer - Worth of Goods - Pass OTP
      
        for($i = 0; $i < sizeof($open_orders_array); $i++){
            
            $open_orders_array[$i]['date'] = date('D, M j, Y \a\t g:ia', strtotime($open_orders_array[$i]['date_initiated']));
         
            $farmer_id = $open_orders_array[$i]['farmer_id'];
            $stmt = $conn->prepare("SELECT `fullname`,`phone` FROM `user` WHERE `id` = ?");
            $stmt->bind_param("i",$farmer_id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($fullname,$fetched_phone);
            $stmt->fetch();	
            
            $open_orders_array[$i]['farmer'] = $fullname."($fetched_phone)";
            $open_orders_array[$i]['amount'] = "Kshs.".number_format($open_orders_array[$i]['amount_disbursed']);
            
        }
     
        $json['orders'] = $open_orders_array;
        
        $json['message'] = "success";
        
        echo json_encode($json);     
    }elseif($service === "get_all_orders_agrovet"){
        $user_id = $data['user_id'];
        $start_date = $data['start_date'];
        $end_date = $data['end_date'];
        
        $stmt = $conn->prepare("SELECT * FROM `disbursed_goods_from_agrovets` WHERE `agrovet_id` = ?  AND (`date_initiated` BETWEEN ? AND ?) ORDER BY `id` DESC");
        $stmt->bind_param("sss",$user_id,$start_date,$end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $orders_array[] = $row;
        }
        // Date time (ago),  Farmer - Worth of Goods - Pass OTP
        for($i = 0; $i < sizeof($orders_array); $i++){
            $orders_array[$i]['date'] = date('D, M j, Y \a\t g:ia', strtotime($orders_array[$i]['date_initiated']));
            /*  Farmer */
            $farmer_id = $orders_array[$i]['farmer_id'];
            $stmt = $conn->prepare("SELECT `fullname`,`phone` FROM `user` WHERE `id` = ?");
            $stmt->bind_param("i",$farmer_id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($fullname,$fetched_phone);
            $stmt->fetch(); 
            
            $orders_array[$i]['farmer'] = $fullname."($fetched_phone)";
            $orders_array[$i]['amount'] = "Kshs.".number_format($orders_array[$i]['amount_disbursed']);
        }
        
        $json['orders'] = $orders_array;
        $json['message'] = "success";
        
        echo json_encode($json);     
    }elseif($service === "get_order_repayment_agrovet"){
        $user_id = $data['user_id'];
        //	cooperative_id	farmer_id	agrovet_id	amount	date_time
        $stmt = $conn->prepare("SELECT * FROM `disbursed_goods_from_agrovets_repayments` WHERE `agrovet_id` = ? ORDER BY `id` DESC");
        $stmt->bind_param("s",$user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $repayments_array[] = $row;
        }
        for($i = 0; $i < sizeof($repayments_array); $i++){
            $repayments_array[$i]['date'] = date('D, M j, Y \a\t g:ia', strtotime($repayments_array[$i]['date_time']));
            /*  Farmer */
            $farmer_id = $repayments_array[$i]['farmer_id'];
            $stmt = $conn->prepare("SELECT `fullname`,`phone` FROM `user` WHERE `id` = ?");
            $stmt->bind_param("i",$farmer_id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($fullname,$fetched_phone);
            $stmt->fetch(); 
            
            $repayments_array[$i]['farmer'] = $fullname."($fetched_phone)";
            $repayments_array[$i]['amount'] = "Kshs.".number_format($repayments_array[$i]['amount']);
            
        }
        
        $stmt = $conn->prepare("SELECT SUM(amount) FROM `disbursed_goods_from_agrovets_repayments` WHERE `agrovet_id` = ?");
        $stmt->bind_param("s",$user_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($total_repaid);
        $stmt->fetch();
        
        $json['total_repaid'] = "Kshs.".number_format($total_repaid);

        $status = "active";
        $stmt = $conn->prepare("SELECT SUM(amount_disbursed) FROM `disbursed_goods_from_agrovets` WHERE `agrovet_id` = ? AND `status` = ?");
        $stmt->bind_param("ss",$user_id,$status);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($total_owed);
        $stmt->fetch();

        $json['total_owed'] = "Kshs.".number_format($total_owed);
        
        $json['amount_remaining'] = "Kshs.".number_format($total_owed - $total_repaid);
        
        
        $json['orders'] = $repayments_array;
        $json['message'] = "success";
        
        echo json_encode($json);
    }elseif($service === "get_all_orders_farmer"){
        $user_id = $data['user_id'];
        $start_date = $data['start_date'];
        $end_date = $data['end_date'];

        $stmt = $conn->prepare("SELECT * FROM `disbursed_goods_from_agrovets` WHERE `farmer_id` = ? AND (`date_initiated` BETWEEN ? AND ?)  ORDER BY `id` DESC");
        $stmt->bind_param("sss",$user_id,$start_date,$end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $orders_array[] = $row;
        }
        // Date time (ago),  Farmer - Worth of Goods - Pass OTP
        for($i = 0; $i < sizeof($orders_array); $i++){
            $orders_array[$i]['date'] = date('D, M j, Y \a\t g:ia', strtotime($orders_array[$i]['date_initiated']));
            /*  Farmer */
            $agrovet_id = $orders_array[$i]['agrovet_id'];
            $stmt = $conn->prepare("SELECT `fullname`,`phone` FROM `user` WHERE `id` = ?");
            $stmt->bind_param("i",$farmer_id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($fullname,$fetched_phone);
            $stmt->fetch(); 
            
            $orders_array[$i]['agrovet'] = $fullname."($fetched_phone)";
            $orders_array[$i]['amount'] = "Kshs.".number_format($orders_array[$i]['amount_disbursed']);
        }
        //Total repaid
        //Total remaining
        
        
        $json['orders'] = $orders_array;
        $json['message'] = "success";
        
        echo json_encode($json);     
    }elseif($service === "measurements_report_farmer"){
        $start_date = $data['start_date'];
        $end_date = $data['end_date'];
        $user_id = $data['user_id'];
        
   
        $stmt = $conn->prepare("SELECT `date`,`product_id`,`amount` FROM `measurements` WHERE `user_id` = ? AND (`date` BETWEEN ? AND ?)");
        $stmt->bind_param("sss",$user_id,$start_date,$end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $measurements_sales[] = $row;
        }
        
        $measurements_sales_totals = 0;
        for($i = 0; $i < sizeof($measurements_sales); $i++){
            $measurements_sales[$i]['date'] = date('D, M j, Y \a\t g:ia', strtotime($measurements_sales[$i]['date']));
            
            $amount = $measurements_sales[$i]['amount'];
            $product_id = $measurements_sales[$i]['product_id'];
            
            $stmt = $conn->prepare("SELECT `buying_price` FROM `prices` WHERE `product_id` = ?");
            $stmt->bind_param("s",$product_id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($buying_price);
            $stmt->fetch();
            
            $cost_of_product = round(($buying_price * $amount),2);
            $measurements_sales[$i]['total_gained'] = "Kshs.".number_format($cost_of_product);
            
            $measurements_sales_totals = $measurements_sales_totals + $cost_of_product;
            
            
            $stmt = $conn->prepare("SELECT `name`,`unit_id` FROM `products` WHERE `id` = ?");
            $stmt->bind_param("i",$product_id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($product_name,$product_unit_id);
            $stmt->fetch(); 
            
            $measurements_sales[$i]['product'] = $product_name;
            
            $stmt = $conn->prepare("SELECT `name` FROM `units` WHERE `id` = ?");
            $stmt->bind_param("i",$product_unit_id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($unit_name);
            $stmt->fetch(); 
            
            /*
            $user_id = $measurements_sales[$i]['user_id'];
            
            
            $stmt = $conn->prepare("SELECT `fullname`,`phone` FROM `user` WHERE `id` = ?");
            $stmt->bind_param("i",$user_id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($fullname,$fetched_phone);
            $stmt->fetch(); 
            */
            
            
            //$measurements_sales[$i]['agrovet'] = strtoupper($fullname."(".$fetched_phone.")");
            $measurements_sales[$i]['quantity'] = number_format($measurements_sales[$i]['amount'])." ".$unit_name;
                    
        } 

        $stmt = $conn->prepare("SELECT `date_time`,`product_id`,`amount` FROM `measurements_cooler` WHERE `user_id` = ? AND (`date_time` BETWEEN ? AND ?)");
        $stmt->bind_param("sss",$user_id,$start_date,$end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $measurements_cooler_sales[] = $row;
        }
  
        $measurements_cooler_sales_totals = 0;
        for($j = 0; $j < sizeof($measurements_cooler_sales); $j++){
            
            $amount = $measurements_cooler_sales[$j]['amount'];
            $product_id = $measurements_cooler_sales[$j]['product_id'];
            
            $stmt = $conn->prepare("SELECT `buying_price` FROM `prices` WHERE `product_id` = ?");
            $stmt->bind_param("i",$product_id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($buying_price);
            $stmt->fetch();
            
            $cost_of_product = round(($buying_price * $amount),2);
            
            $measurements_cooler_sales[$j]['total_gained'] = "Kshs.".number_format($cost_of_product);
            
            $measurements_cooler_sales[$j]['buying_price'] = $buying_price;
            $measurements_cooler_sales_totals = $measurements_cooler_sales_totals + $cost_of_product;
            
            $measurements_cooler_sales[$j]['date'] = date('D, M j, Y \a\t g:ia', strtotime($measurements_cooler_sales[$j]['date_time']));
            
            $product_id = $measurements_cooler_sales[$j]['product_id'];
            
            $stmt = $conn->prepare("SELECT `name`,`unit_id` FROM `products` WHERE `id` = ?");
            $stmt->bind_param("i",$product_id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($product_name,$product_unit_id);
            $stmt->fetch(); 
            
            $measurements_cooler_sales[$j]['product'] = $product_name;
            
            $stmt = $conn->prepare("SELECT `name` FROM `units` WHERE `id` = ?");
            $stmt->bind_param("i",$product_unit_id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($unit_name);
            $stmt->fetch(); 
            
            $user_id = $measurements_cooler_sales[$j]['user_id'];
            
            
            /*
            $stmt = $conn->prepare("SELECT `fullname`,`phone` FROM `user` WHERE `id` = ?");
            $stmt->bind_param("i",$user_id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($fullname,$fetched_phone);
            $stmt->fetch(); 
            */
            
            //$measurements_cooler_sales[$j]['agrovet'] = strtoupper($fullname."(".$fetched_phone.")");
            $measurements_cooler_sales[$j]['quantity'] = number_format($measurements_cooler_sales[$j]['amount'])." ".$unit_name;
            
        } 
        
        
        //$json['measurements_cooler_sales_totals'] = $measurements_cooler_sales_totals;
        //$json['measurements_cooler'] = $measurements_cooler_sales;
        
        //$json['measurements_sales_totals'] = $measurements_sales_totals;
        //$json['measurements'] = $measurements_sales;
        
        $json['orders'] = array_merge($measurements_cooler_sales, $measurements_sales);
        
        $json['total_sales_for_period'] = "Kshs.".number_format($measurements_sales_totals + $measurements_cooler_sales_totals);
        $json['date_time'] = "Collection reports from ".$start_date." to ".$end_date;
        
        echo json_encode($json);
        
    }elseif($service === "get_available_balance"){
        $user_id = $data['user_id'];
        
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
        
        
        $json['available_balance'] = "Kshs.".number_format($available_balance - ($total_withdrawals + $sum_transaction_costs));
        
       
        
        echo json_encode($json);
    }elseif($service === "deposit_to_wallet"){
        $user_id = $data['user_id'];
        
        $json['paybill_shortcode'] = $deposit_paybill_shortcode;
        
        echo json_encode($json);
    }elseif($service === "deposit_to_wallet"){
        $user_id = $data['user_id'];
        $deposit_method = $data['deposit_method'];
        $transaction_reference = $data['transaction_ref'];
        $account_ref = $data['account_ref'];
        $amount = $data['amount'];
        $payment_descritiption = $data['payment_descritiption'];
        $deposit_name = $data['customer_name'];
        $partya = $data['partya'];
        $partyb = $data['partyb'];
        
        $available_amount_with_interest = $amount;
        $start_date = date('Y-m-d');
        $next_interest_date = date('Y-m-d' , strtotime($start_date. ' + '.$savings_interest_days.' days'));
        
        $stmt = $conn->prepare("INSERT INTO `savings`(`user_id`,`amount`,`available_amount_with_interest`,`next_interest_date`,`deposit_method`,`transaction_reference`,`deposit_name`,`partya`,`partyb`) VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("sssssssss",$user_id,$amount,$available_amount_with_interest,$next_interest_date,$deposit_method,$transaction_reference,$deposit_name,$partya,$partyb);
        $stmt->execute();
        
        //Notify
        $stmt = $conn->prepare("SELECT `fullname`,`phone` FROM `user` WHERE `id` = ?");
        $stmt->bind_param("i",$user_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($fullname,$fetched_phone);
        $stmt->fetch();	
        
        $date_time_formatted = date('D, M j, Y \a\t g:ia');

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
        
        $message = "$base_app_name: Hello $fullname"."! We have received your savings deposit payment of Kshs.".number_format($amount)." on $date_time_formatted".". Your new account balance is Kshs.".number_format($available_balance).". Thank your for being a valuable client.";
 
        send_general_sms($fetched_phone,$message);
        if($fetched_phone != $partya){
            send_general_sms($partya,$message);
        }
        
        $json['message'] = "success";
        
        echo json_encode($json);
        
    }elseif($service === "cron_savings_interest_adder"){
        $start_date = date('Y-m-d');
        $next_interest_date = date('Y-m-d' , strtotime($start_date. ' + '.$savings_interest_days.' days'));
        
        $stmt = $conn->prepare("SELECT * FROM `savings` WHERE `next_interest_date` = ?");
        $stmt->bind_param("s",$start_date);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $savings_array[] = $row;
        }
        for($i = 0; $i < sizeof($savings_array); $i++){
            $id = $savings_array[$i]['id'];
            
            $stmt = $conn->prepare("UPDATE `savings` SET `next_interest_date` = ? WHERE `id` = ?");
            $stmt->bind_param("si",$next_interest_date,$id);
            $stmt->execute();
        }
        
        $json['message'] = "success";
        
        echo json_encode($json);
    }elseif($service === "get_withdraw_parameters"){
        $user_id = $data['user_id'];
        
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
        
        //phone_receiving_money
        $stmt = $conn->prepare("SELECT `fullname`,`phone` FROM `user` WHERE `id` = ?");
        $stmt->bind_param("i",$user_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($fullname,$fetched_phone);
        $stmt->fetch();	
        
        
        $json['phone_receiving_money'] = $fetched_phone;
        $json['available_balance'] = $available_balance;
        $json['formatted_available_balance'] = "Kshs.".number_format($available_balance);
        $json['minimum_withdrawable'] = $minimum_withdrawable;
        $json['maximum_withdrawable'] = $maximum_withdrawable;
        $json['formatted_minimum_withdrawable'] = "Kshs.".number_format($minimum_withdrawable);
        $json['formatted_maximum_withdrawable'] = "Kshs.".number_format($maximum_withdrawable);
        $json['message'] = "success";
        
        echo json_encode($json);
        
        //get_withdrawal_charge($amount)
    }elseif($service === "get_deposit_reports"){
        $start_date = $data['start_date'];
        $end_date = $data['end_date'];
        $user_id = $data['user_id'];
        
        $stmt = $conn->prepare("SELECT SUM(amount) FROM `savings` WHERE `user_id` = ? AND (`date_time_deposited` BETWEEN ? AND ?)");
        $stmt->bind_param("sss",$user_id,$start_date,$end_date);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($total_deposited_for_period);
        $stmt->fetch(); 
        
        $json['total_deposited_for_period'] = "Kshs.".number_format($total_deposited_for_period);
        
        $stmt = $conn->prepare("SELECT `amount`,`date_time_deposited`,`deposit_method`,`transaction_reference`,`partya`,`deposit_name` FROM `savings` WHERE `user_id` = ? AND (`date_time_deposited` BETWEEN ? AND ?)");
        $stmt->bind_param("sss",$user_id,$start_date,$end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $deposits_array[] = $row;
        }
        for($i = 0;$i < sizeof($deposits_array); $i++){
            /*consume date,amount,deposit_method,transaction_reference,deposit_initiator*/
             $deposits_array[$i]['date'] = date('D, M j, Y \a\t g:ia', strtotime($deposits_array[$i]['date_time_deposited']));
             $deposits_array[$i]['amount'] = "Kshs.".number_format($deposits_array[$i]['amount']);
             $deposits_array[$i]['deposit_initiator'] = $deposits_array[$i]['partya']."(".$deposits_array[$i]['deposit_name'].")";
        }
        $json['report'] = $deposits_array;
        echo json_encode($json);
    }elseif($service === "initiate_b2c_withdrawal"){
        $user_id = $data['user_id'];
        $phone = $data['user_id'];
        $amount = $data['amount'];
        
        $phone = str_replace(" ","",$phone);
        $phone = str_replace("+254","",$phone);
        
        $date_today = date('Y-m-d');

        
        if(substr($phone, 0, 1) == "0"){
            $phone = str_replace("+","",$phone);
            $phone = substr($phone, 1);
            $phone = "254".$phone;  
        }else if(substr($phone, 0, 3) == "254"){
              
        }else{
        	$phone = "254".$phone;
		}
        
        $withdrawal_cost = get_withdrawal_charge($amount);
        
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
        
        $stmt = $conn->prepare("SELECT `fullname`,`phone` FROM `user` WHERE `id` = ?");
        $stmt->bind_param("i",$user_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($fullname,$fetched_phone);
        $stmt->fetch();	
        
        $amount_needed = $amount + $withdrawal_cost;
        
        if(($amount + $withdrawal_cost) > $available_balance){
            $message = "$base_app_name: Hello $fullname, Your withdrawal request FAILED! You have insufficient funds in your account to withdraw Kshs".number_format($amount).". Amount plus withdrawal charges is Kshs.".number_format($amount_needed).". Your account balance is Kshs.".number_format($available_balance);
            send_general_sms($fetched_phone,$message);
            $json['message'] = "failed";
        }else{
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://www.shuklo.com/payments/savings_b2c.php',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>'{
                "apiKEY":"caaa7706118392e10b0ea47d084a6f848a6fecbcc219f091f2db62e60616013733edaa2fd371b981bd06118392e10b4a6fedaa2fd06",
                "consumerKEY":"a2fd371b981bd06118392e10b4a6fedaa2fd06caaa7706110ea47d098a6fecbcc219f091f2db62e630b2f319f8f395fb13733eda",
                "service":"disburse_funds",
                "amount":"'.$amount.'",
                "phone":"'.$phone.'",
                "remarks":"'.$b2c_remarks_withdraw_from_savings.'"
            }',
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
              ),
            ));
            
        
            $response = json_decode(curl_exec($curl),TRUE);
            
            $response_code = $response['response_code'];
            $conversation_id = $response['conversation_id'];
            $originator_conversation_id = $response['originator_conversation_id'];
            $response_description = $response['response_description'];
            
          
            if($response_code == 0){
                $json['message'] = "success";
                
                
                $status = "initiated";
                $transaction_cost = get_withdrawal_charge($amount);
                $stmt = $conn->prepare("INSERT INTO `withdrawals_savings`(`user_id`,`amount`,`phone_sent`,`conversation_id`,`originator_conversation_id`,`transaction_cost`,`status`) VALUES (?,?,?,?,?,?,?)");
                $stmt->bind_param("sssssss",$user_id,$phone,$conversation_id,$originator_conversation_id,$transaction_cost,$status);
                $stmt->execute();
                
            }else{
                $json['message'] = "failed";
                $message = "$base_app_name: Hello $fullname, Your withdrawal request FAILED due to an internal system error. Please try again later. Thank your for being a valued $base_app_name customer.";
                send_general_sms($fetched_phone,$message);
            }   
        }
        
        echo json_encode($json);
    }elseif($service === "get_withdrawal_reports"){
        $start_date = $data['start_date'];
        $end_date = $data['end_date'];
        $user_id = $data['user_id'];
        
        $status = "active";
        $stmt = $conn->prepare("SELECT SUM(amount) FROM `withdrawals_savings` WHERE `user_id` = ? AND `status` = ? AND (`date_time` BETWEEN ? AND ?)");
        $stmt->bind_param("ssss",$user_id,$status,$start_date,$end_date);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($total_withdrawn_for_period);
        $stmt->fetch(); 
        
        $json['total_withdrawn_for_period'] = "Kshs.".number_format($total_withdrawn_for_period);
        
        //id	user_id	amount	date_time	phone_sent	mpesa_ref	conversation_id	originator_conversation_id	transaction_cost	status

        $stmt = $conn->prepare("SELECT `date_time`,`amount`,`transaction_cost`,`phone_sent`,`mpesa_ref` FROM `withdrawals_savings` WHERE `user_id` = ? AND `status` = ? AND (`date_time` BETWEEN ? AND ?)");
        $stmt->bind_param("ssss",$user_id,$status,$start_date,$end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $withdrawals_array[] = $row;
        }
        
        for($i = 0;$i < sizeof($withdrawals_array); $i++){
            /*consume `date`,`amount`,`transaction_cost`,`phone_sent`,`mpesa_ref`*/
             $withdrawals_array[$i]['date'] = date('D, M j, Y \a\t g:ia', strtotime($withdrawals_array[$i]['date_time']));
             $withdrawals_array[$i]['amount'] = "Kshs.".number_format($withdrawals_array[$i]['amount']);
             $withdrawals_array[$i]['transaction_cost'] = "Kshs.".number_format($withdrawals_array[$i]['transaction_cost']);
            
        }
        $json['report'] = $withdrawals_array;
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