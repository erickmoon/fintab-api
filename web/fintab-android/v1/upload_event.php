<?php
header('Content-type: text/html; charset=utf-8');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include("../includes/dbconn.php");
/*
include("includes/encdec.php");
require "includes/class/class.phpmailer.php";
include("includes/settings.php");
include("includes/AfricasTalkingGateway.php");
*/

$allowed_file_types = array(".jpg",".raw",".jpeg",".png","jpg","raw","jpeg","png");

// Path to move uploaded files
$location   = 'uploads/';
$prefix = "storm_";
$images = "";
$date_time = date('D, M j, Y \a\t g:ia');

$apiKEY = "3948a6fecbcc219f0910616013733edaa2fd06caaa7706118f2db62e6";
$consumerKEY = "0ea47d09371b981b30b2f3d061183cc219f091f2db62e619f8f395fb";

$ip_address = $_SERVER['REMOTE_ADDR'];

function resizeImage($resourceType, $image_width, $image_height, $resizeWidth, $resizeHeight)
{
    /*
          $resizeWidth = 300;
          $resizeHeight = 250;
    */
    /*
          $resizeWidth = 850;
          $resizeHeight = 570;
    */
    //$resizeWidth = 850;
    //$resizeHeight = 570;
    $imageLayer = imagecreatetruecolor($resizeWidth, $resizeHeight);
    imagecopyresampled($imageLayer, $resourceType, 0, 0, 0, 0, $resizeWidth, $resizeHeight, $image_width, $image_height);
    return $imageLayer;
}
function convertImage($originalImage, $outputImage, $quality)
{
    // jpg, png, gif or bmp?
    $exploded = explode('.', $originalImage);
    $ext = $exploded[count($exploded) - 1];

    if (preg_match('/jpg|jpeg/i', $ext)) $imageTmp = imagecreatefromjpeg($originalImage);
    else if (preg_match('/png/i', $ext)) $imageTmp = imagecreatefrompng($originalImage);
    else if (preg_match('/gif/i', $ext)) $imageTmp = imagecreatefromgif($originalImage);
    else if (preg_match('/bmp/i', $ext)) $imageTmp = imagecreatefrombmp($originalImage);
    else return 0;

    // quality is a value from 0 (worst) to 100 (best)
    imagejpeg($imageTmp, $outputImage, $quality);
    imagedestroy($imageTmp);

    return 1;
}
function writeFile($name, $string) {
    $filename = $name.".txt"; 
    $fp = fopen($filename,"a+");  
    fputs($fp,$string); 
    fclose($fp);  
}

if($apiKEY === $_POST['apiKey'] and $consumerKEY === $_POST['apiConsumerKey']){

    $car_upload_date = date('D, M j, Y \a\t g:ia');
    
    $user_id = $_POST['user_id'];
    $group_id = $_POST['group_id'];
    
    
    
    $title = $_POST['title'];
    $description = $_POST['description'];
    $scheduled_date_time = $_POST['scheduled_date_time'];
    $expiry_date_time = $_POST['expiry_date_time'];
    $club = $_POST['club'];
    $size = $_POST['size'];
    
    $stmt = $conn->prepare("SELECT `school_code` FROM `tblsalesinfo` WHERE `id` = ?");
    $stmt->bind_param("i",$group_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($school_code);
    $stmt->fetch();  
    
    $customer_email = $product_email;
    
    if($size > 0){
        for($i = 0; $i < $size; $i++){
            
            $fileName = $_FILES["document$i"]['tmp_name'];
            $fileExt = pathinfo($_FILES["document$i"]['name'], PATHINFO_EXTENSION);
            $resizeFileName = hash('sha256', time() . rand(000, 999));
            $uploadPath = "uploads/";
            $fileExt = pathinfo($_FILES["document$i"]['name'], PATHINFO_EXTENSION);
                
            $sourceProperties = getimagesize($fileName);
            $uploadImageType = $sourceProperties[2];
            $sourceImageWidth = $sourceProperties[0];
            $sourceImageHeight = $sourceProperties[1];
            $target_width = 700;
            $target_height = $target_width/$sourceImageWidth * $sourceImageHeight;

            if($fileExt != "" and !in_array(strtolower($fileExt), $allowed_file_types) ){
                
            }else{
                switch ($uploadImageType)
                {
                    case IMAGETYPE_JPEG:
                        $resourceType = imagecreatefromjpeg($fileName);
                        $imageLayer = resizeImage($resourceType, $sourceImageWidth, $sourceImageHeight, $target_width, $target_height);
                        imagejpeg($imageLayer, $uploadPath . "storm_" . $resizeFileName . '.' . $fileExt);
                    break;
                    case IMAGETYPE_GIF:
                        $resourceType = imagecreatefromgif($fileName);
                        $imageLayer = resizeImage($resourceType, $sourceImageWidth, $sourceImageHeight, $target_width, $target_height);
                        imagegif($imageLayer, $uploadPath . "storm_" . $resizeFileName . '.' . $fileExt);
                    break;
                    case IMAGETYPE_PNG:
                        $resourceType = imagecreatefrompng($fileName);
                        $imageLayer = resizeImage($resourceType, $sourceImageWidth, $sourceImageHeight, $target_width, $target_height);
                        imagepng($imageLayer, $uploadPath . "storm_" . $resizeFileName . '.' . $fileExt);
                    break;
                    default:
                        $imageProcess = 0;
                    break;
                }
                
                if (move_uploaded_file($fileName, $uploadPath . $resizeFileName . "." . $fileExt))
                {
                    $attachments[$i] = "storm_".$resizeFileName . "." . $fileExt;
                    unlink("uploads/" . $resizeFileName . "." . $fileExt);
                }
            }
 

        }
    }
    

    

    $attachments = implode(",",$attachments);
    $date_submitted = date('d-m-Y H:i:s'); 
    $date_uploaded = $date_submitted;
    //3,06-02-2022 12:31:14,1950,Benz,model2,Diesel,Trans Nzoia,Manual,Sedan,Trim custom,Blue,6000,2500,6,6700000,storm_cf0ac210ef2c9e6525fc4ab54bfcaa97bf765ea8814272507e47797de00566fe.jpg
    //YearOfManufacture Make    Model   Trim    Color   Mileage FuelType    EngineSize  BodyType    Transmission    NoOfUsers   ExpectedPrice   Region  user_id images  panoramas   date_posted is_active   is_sponsored    sponsorship_start   sponsorship_end
    //file_put_contents("upload.txt","$user_id,$date_uploaded,$yearOfManufacture,$make,$model,$fuelType,$region,$transmission,$bodyType,$trim,$color,$mileage,$engineSize,$noOfOwners,$expectedPrice,$vehicle_image_attachments");
    $stmt = $conn->prepare("INSERT INTO `tblevents`(`user_id`,`event_title`,`event_description`,`scheduled_date`,`expiry_date`,`club`,`institution`,`photo_url`)VALUES(?,?,?,?,?,?,?,?)");
    $stmt->bind_param("ssssssss",$user_id,$title,$description,$scheduled_date_time,$expiry_date_time,$club,$school_code,$attachments);
    if($stmt->execute()){
        $stmt->insert_id;
        $array_result=array();
         foreach ($stmt as $key => $value) {
             $array_result[$key]=$stmt->$key;
        }
    }

    $insert_id =  $array_result['insert_id'];

/*
    $date_time_notif = date('yy-m-d h:i:s');
    $not_title = "New Product";
    $not_message = "New item uploaded - https://www.shuklo.com/product/?id=$insert_id";
    $to_user = "eivuto@gmail.com";
    
    $stmt = $conn->prepare("INSERT INTO `tblnotifications`(`to_user`,`notification_type`,`notification_message`,`notification_date`)VALUES(?,?,?,?)");
    $stmt->bind_param("ssss",$to_user,$not_title,$not_message,$date_time_notif);
    $stmt->execute();
    
    $to_user = "jhnmugambi@gmail.com";
    
    $stmt = $conn->prepare("INSERT INTO `tblnotifications`(`to_user`,`notification_type`,`notification_message`,`notification_date`)VALUES(?,?,?,?)");
    $stmt->bind_param("ssss",$to_user,$not_title,$not_message,$date_time_notif);
    $stmt->execute();
      
    
    $description = "User $customer_email uploaded an item with id:$insert_id and phone $contact_phone titled $title";
    $stmt = $conn->prepare("INSERT INTO `logs`(ip_address,description,date_time) VALUES(?,?,?)");
    $stmt->bind_param("sss", $ip_address, $description, $date_time);
    $stmt->execute();
    */
    /*
    
    $message = "New Item uploaded";
    $mail_to = "eivuto@gmail.com";
    $mail = new PHPMailer;
    $mail->IsSMTP(); //Sets Mailer to send message using SMTP
    $mail->Host = $email_notification_mail_host; //Sets the SMTP hosts of your Email hosting, this for Godaddy
    $mail->Port = $email_notification_mail_host_port; //Sets the default SMTP server port
    $mail->SMTPAuth = true; //Sets SMTP authentication. Utilizes the Username and Password variables
    $mail->Username = $email_notification_mail_username; //Sets SMTP username
    $mail->Password = $email_notification_mail_password; //Sets SMTP password
    $mail->SMTPSecure = "ssl"; //Sets connection prefix. Options are "", "ssl" or "tls"
    $mail->From = $email_notification_mail_username; //Sets the From email address for the message
    $mail->FromName = "SHUKLO";
    //Sets Vch)5@T6gi8Q4Ythe From name of the message
    $mail->AddAddress($mail_to, $mail_to);
    //Adds a "To" address
    //$mail->WordWrap = 50;             //Sets word wrapping on the body of the message to a given number of characters
    $mail->IsHTML(true); //Sets message type to HTML
    $mail->Subject = "NEW ITEM"; //Sets the Subject of the message
    //An HTML or plain text message body
    $mail->Body = $message;
    $mail->AltBody = "";
    $result = $mail->Send();
    */
    
    $json['status'] = "success";
    $json['message'] = "File Uploaded";
    $json['product_id'] = $insert_id;
    
    echo json_encode($insert_id);
  
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
