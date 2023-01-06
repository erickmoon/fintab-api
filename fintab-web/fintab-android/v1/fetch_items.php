<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


include("../includes/dbconn.php");
require "../includes/class/class.phpmailer.php";
include("../includes/settings.php");
include("../includes/encdec.php");;

$apiKEY = "3948a6fecbcc219f0910616013733edaa2fd06caaa7706118f2db62e6";
$consumerKEY = "0ea47d09371b981b30b2f3d061183cc219f091f2db62e619f8f395fb";





$page =  $_GET['page'];
$limit = $_GET['limit'];


$starting_limit_number = $page;
$results_per_page = $limit;
$service = $_GET['service'];

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

if(($apiKEY === $_GET['apiKEY'] or $apiKEY1 === $_GET['apiKEY'])  and $_GET['consumerKEY'] === $consumerKEY and $_GET['service'] !== null){
    if($service === "buzz"){
            $sort_order = $_GET['sort'];
            
            $stmt = $conn->prepare("SELECT * FROM `tblnews` ORDER BY `id` $sort_order LIMIT $starting_limit_number,$results_per_page");
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $array_buzz[] = $row;
            }
            for($k = 0; $k < sizeof($array_buzz); $k++){
                $array_buzz[$k]['views'] = number_format($array_buzz[$k]['views'])." View(s)";
            }
            foreach ($array_buzz as &$str) {
                $str =  preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $str);
            }
            echo json_encode($array_buzz);
            //var_dump($array_buzz);
    }elseif($service === "get_general_event"){
            $sort_order = $_GET['sort'];
            $club = $_GET['club'];
            $institution = $_GET['institution'];
            
            if($sort_order === null){
                $sort_order = "DESC";
            }
            
            $status = "active";
            
            if($club === "all"){
                $stmt = $conn->prepare("SELECT * FROM `tblevents` WHERE `status` = ? AND `institution` = ? ORDER BY `id` $sort_order LIMIT $starting_limit_number,$results_per_page");
                $stmt->bind_param("ss",$status,$institution);
            }else{
                $stmt = $conn->prepare("SELECT * FROM `tblevents` WHERE `status` = ? AND `institution` = ? AND `club` = ? ORDER BY `id` $sort_order LIMIT $starting_limit_number,$results_per_page");
                $stmt->bind_param("sss",$status,$institution,$club);              
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            
            
            while ($row = $result->fetch_assoc()) {
                $array_events[] = $row;
            }
            for($k = 0; $k < sizeof($array_events); $k++){
                $array_events[$k]['photo_url'] = "http://apps.soweaa.co.ke/android/v1/".$array_events[$k]['photo_url'];
                $user_id = $array_events[$k]['user_id'];
                

                if (new DateTime() > new DateTime($array_events[$k]['expiry_date'])) {
                    $event_id = $array_events[$k]['id'];

                    $stmt = $conn->prepare("DELETE FROM `tblevents` WHERE `id` = ?");
                    $stmt->bind_param("i",$event_id);
                    $stmt->execute();
                }
                
                $stmt = $conn->prepare("SELECT `fullname`,`class_year`,`group_number` FROM `tblmembers` WHERE `id` = ?");
                $stmt->bind_param("i",$user_id);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($fullname,$class_year,$group_number);
                $stmt->fetch();
                
                $array_events[$k]['display_name'] = "Created by ".ucfirst(strtolower($fullname))." Class of $class_year, group no.$group_number";
                $array_events[$k]['created_date_time'] = humanTiming($array_events[$k]['created_date_time']);
                

       
            }
            
            if($club == "all"){
                $stmt = $conn->prepare("SELECT * FROM `tblevents` WHERE `status` = ? AND `institution` = ? ORDER BY `id` $sort_order LIMIT $starting_limit_number,$results_per_page");
                $stmt->bind_param("ss",$status,$institution);
            }else{
                $stmt = $conn->prepare("SELECT * FROM `tblevents` WHERE `status` = ? AND `institution` = ? AND `club` = ? ORDER BY `id` $sort_order LIMIT $starting_limit_number,$results_per_page");
                $stmt->bind_param("sss",$status,$institution,$club);              
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            
            while ($row = $result->fetch_assoc()) {
                $array_new_events[] = $row;
            }
            for($o = 0; $o < sizeof($array_new_events); $o++){
                $array_new_events[$o]['photo_url'] = "http://apps.soweaa.co.ke/android/v1/".$array_new_events[$o]['photo_url'];
                $user_id = $array_new_events[$o]['user_id'];
                
                
                $stmt = $conn->prepare("SELECT `fullname`,`class_year`,`group_number` FROM `tblmembers` WHERE `id` = ?");
                $stmt->bind_param("i",$user_id);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($fullname,$class_year,$group_number);
                $stmt->fetch();
                
                $array_new_events[$o]['display_name'] = "Created by ".ucfirst(strtolower($fullname))." Class of $class_year, group no.$group_number";
                $time = strtotime($array_new_events[$o]['created_date_time']);
                $array_new_events[$o]['created_date_time'] = "Created ".humanTiming($time)." ago";
                

       
            }
        
            foreach ($array_new_events as &$str) {
                $str =  preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $str);
            }
            echo json_encode($array_new_events);
            //var_dump($array_buzz);
    }elseif($service === "get_affiliate_schools"){
        $user_id = $_GET['user_id'];
        
        $stmt = $conn->prepare("SELECT `affiliate_schools` FROM `tblmembers` WHERE `id` = ?");
        $stmt->bind_param("i",$user_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($user_affiliate_schools);
        $stmt->fetch();
        
        $user_affiliate_schools = explode(',', $user_affiliate_schools);


        $in_stmt = $user_affiliate_schools[$i]['following'];
        
        
        if(sizeof($in_stmt) > 0){   
        }else{
            $in_stmt[0] = "2673gv236sbzwscytvzabuiSHBJHSDXUAISXIASKJ@gshdjksd788sd7.com";
        } 
        $in_stmt = "'" . implode("','", $in_stmt) ."'";
        
            
        $stmt = $conn->prepare("SELECT * FROM `tblschools` WHERE `school_code` IN ($in_stmt) ORDER BY `id` DESC LIMIT $starting_limit_number,$results_per_page");
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $array_schools[] = $row;
        }
        for($i = 0; $i < sizeof($array_schools); $i++){
            $array_schools[$i]['school_sub_county'] = $array_schools[$i]['sub_county'];
            $array_schools[$i]['school_county'] = $array_schools[$i]['county'];
        }
        echo json_encode($array_schools);
        
    }elseif($service === "get_groups"){
        $user_id = $_GET['user_id'];
        
        $alumni_limit = 7;
        $teachers_limit = 1;
        $parents_limit = 1;
        $sponsor_limit = 1;
        
        $overal_limit = 10;
        
        $stmt = $conn->prepare("SELECT `account_type`,`school_code` FROM `tblmembers` WHERE `id` = ?");
        $stmt->bind_param("i",$user_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($account_type,$institution);
        $stmt->fetch();  
        

        $stmt = $conn->prepare("SELECT * FROM `tblsalesinfo` WHERE `school_code` = ? ORDER BY `id` DESC LIMIT $starting_limit_number,$results_per_page");
        $stmt->bind_param("s",$institution);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $array_groups[] = $row;
        }
        $section_accessible =  false;
        if(1 === 1){
            for($i = 0; $i < sizeof($array_groups); $i++){
                $parents = $array_groups[$i]['parents'];
                $teachers = $array_groups[$i]['teachers'];
                $alumni = $array_groups[$i]['alumni'];
                $sponsors = $array_groups[$i]['sponsors'];

                if($account_type === "Alumni" and ($alumni >= $alumni_limit)){
                    $section_accessible =  false;
                }else if($account_type === "Teacher" and ($teachers >= $teachers_limit)){
                    $section_accessible =  false;
                }else if($account_type === "Parent" and ($parents >= $parents_limit)){
                    $section_accessible =  false;
                }else if($account_type === "Sponsor" and ($sponsors >= $sponsor_limit)){
                    $section_accessible =  false;
                }else{
                     $section_accessible =  true;
                }
    
                $totals = $parents + $teachers + $alumni + $sponsors;
              if((($totals) < $overal_limit) and $section_accessible){
                 $final_groups_array[$i] = $array_groups[$i];
              }
              
        $totals = null;
            }
        }
        
        $new_final_groups_array = array_values($final_groups_array);
        
        $i = null;
        
        for($i = 0; $i < sizeof($new_final_groups_array); $i++){

            $parents = $new_final_groups_array[$i]['parents'];
            $teachers = $new_final_groups_array[$i]['teachers'];
            $alumni = $new_final_groups_array[$i]['alumni'];
            $sponsors = $new_final_groups_array[$i]['sponsors'];
                
            $new_final_groups_array[$i]['group_name'] = "Group ".$new_final_groups_array[$i]['id'];
            
            $totals = $parents + $teachers + $alumni + $sponsors;
            $new_final_groups_array[$i]['group_info'] = "($parents)Parents ($teachers)Teachers ($alumni)Alumni ($sponsors)Sponsors ($totals)Total";
            $totals = null;
        }
        
        if(sizeof($new_final_groups_array) === 0){
            $val = 0;
            $six_digit_random_number = random_int(100000, 999999);
            
            $stmt = $conn->prepare("INSERT INTO `tblsalesinfo` (`parents`,`teachers`,`sponsors`,`alumni`,`members`,`group_code`,`school_code`) VALUES (?,?,?,?,?,?,?)");
            $stmt->bind_param("sssssss",$val,$val,$val,$val,$val,$six_digit_random_number,$institution);
            $stmt->execute();
            
            
            $stmt = $conn->prepare("SELECT * FROM `tblsalesinfo` WHERE `school_code` = ? ORDER BY `id` DESC LIMIT $starting_limit_number,$results_per_page");
            $stmt->bind_param("s",$institution);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $array_groups[] = $row;
            }
            $section_accessible =  false;
            if(1 === 1){
                for($i = 0; $i < sizeof($array_groups); $i++){
                    $parents = $array_groups[$i]['parents'];
                    $teachers = $array_groups[$i]['teachers'];
                    $alumni = $array_groups[$i]['alumni'];
                    $sponsors = $array_groups[$i]['sponsors'];
    
                    if($account_type === "Alumni" and ($alumni >= $alumni_limit)){
                        $section_accessible =  false;
                    }else if($account_type === "Teacher" and ($teachers >= $teachers_limit)){
                        $section_accessible =  false;
                    }else if($account_type === "Parent" and ($parents >= $parents_limit)){
                        $section_accessible =  false;
                    }else if($account_type === "Sponsor" and ($sponsors >= $sponsor_limit)){
                        $section_accessible =  false;
                    }else{
                         $section_accessible =  true;
                    }
                    
                    $totals = $parents + $teachers + $alumni + $sponsors;
                  if((($totals) < $overal_limit) and $section_accessible){
                     $final_groups_array[$i] = $array_groups[$i];
                  }
                  $totals = null;
            
                }
            }
            
            $new_final_groups_array = array_values($final_groups_array);
            
            $i = null;
            
            for($i = 0; $i < sizeof($new_final_groups_array); $i++){
    
                $parents = $new_final_groups_array[$i]['parents'];
                $teachers = $new_final_groups_array[$i]['teachers'];
                $alumni = $new_final_groups_array[$i]['alumni'];
                $sponsors = $new_final_groups_array[$i]['sponsors'];
                    
                $new_final_groups_array[$i]['group_name'] = "Group ".$new_final_groups_array[$i]['id'];
                $total = $parents + $teachers + $alumni + $sponsors;
                $new_final_groups_array[$i]['group_info'] = "($parents)Parents ($teachers)Teachers ($alumni)Alumni ($sponsors)Sponsors ($total)Totals";
                $total = null;
            }
        }
        
        for($j = 0; $j < sizeof($new_final_groups_array); $j++){
            if($new_final_groups_array[$j]['members'] < 1){
                $new_final_groups_array[$j]['directly_accessible'] = "yes";
            }else{
                $new_final_groups_array[$j]['directly_accessible'] = "no";
            }
        }

        echo json_encode($new_final_groups_array); 
    }elseif($service === "get_reports"){
        $institution = $_GET['institution'];
        
        
      //  echo "SELECT * FROM `tblreports` WHERE `school_code` = $institution ORDER BY `id` DESC LIMIT $starting_limit_number,$results_per_page";
        
        $stmt = $conn->prepare("SELECT * FROM `tblreports` WHERE `school_code` = ? ORDER BY `id` DESC LIMIT $starting_limit_number,$results_per_page");
        $stmt->bind_param("s",$institution);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $array_reports[] = $row;
        }
        
        
        for($i = 0; $i < sizeof($array_reports); $i++){
            //report_url,report_title,report_date_who,report_description
            $array_reports[$i]['report_url'] = "https://apps.soweaa.co.ke/android/v1/uploads/".str_replace("storm_","",$array_reports[$i]['attachment_pdf']);
            $array_reports[$i]['report_date_who'] = "Uploaded ".$array_reports[$i]['date_time']." by Group ".$array_reports[$i]['group_id'];
            
        }
        
        echo json_encode($array_reports);
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