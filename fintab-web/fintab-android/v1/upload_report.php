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

$allowed_file_types = array("pdf");

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
    
    $stmt = $conn->prepare("SELECT `school_code` FROM `tblsalesinfo` WHERE `id` = ?");
    $stmt->bind_param("i",$group_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($school_code);
    $stmt->fetch();
    
    
    
    $title = $_POST['title'];
    $description = $_POST['description'];
    $size = $_POST['size'];
    

    
    if(1 == 1){
    if(1 == 1){
            $i = 0;
            $fileName = $_FILES["document$i"]['tmp_name'];
/*
           // writeFile("my_pdf",$_FILES);
ob_flush();
ob_start();

    var_dump($_FILES["document$i"]);

file_put_contents("dump.txt", ob_get_flush());
*/

            $fileExt = pathinfo($_FILES["document$i"]['name'], PATHINFO_EXTENSION);
            $resizeFileName = hash('sha256', time() . rand(000, 999));
            $uploadPath = "uploads/";
            $fileExt = pathinfo($_FILES["document$i"]['name'], PATHINFO_EXTENSION);
            
            $fileExt = "pdf";

            if($fileExt != "" and !in_array(strtolower($fileExt), $allowed_file_types) ){
                
            }else{

                if (move_uploaded_file($fileName, $uploadPath . $resizeFileName . "." . $fileExt))
                {
                    $attachments[$i] = "storm_".$resizeFileName . "." . $fileExt;
                    //unlink("uploads/" . $resizeFileName . "." . $fileExt);
                }else{
                    writeFile("my_pdf_raw",$_POST['document0']);
                }
            }
 

        }
    }
    

    

    $attachments = implode(",",$attachments);
    $date_submitted = date('d-m-Y H:i:s'); 
    $date_uploaded = $date_submitted;
    $date_time = date('D, M j, Y \a\t g:ia');
    //3,06-02-2022 12:31:14,1950,Benz,model2,Diesel,Trans Nzoia,Manual,Sedan,Trim custom,Blue,6000,2500,6,6700000,storm_cf0ac210ef2c9e6525fc4ab54bfcaa97bf765ea8814272507e47797de00566fe.jpg
    //YearOfManufacture Make    Model   Trim    Color   Mileage FuelType    EngineSize  BodyType    Transmission    NoOfUsers   ExpectedPrice   Region  user_id images  panoramas   date_posted is_active   is_sponsored    sponsorship_start   sponsorship_end
    //file_put_contents("upload.txt","$user_id,$date_uploaded,$yearOfManufacture,$make,$model,$fuelType,$region,$transmission,$bodyType,$trim,$color,$mileage,$engineSize,$noOfOwners,$expectedPrice,$vehicle_image_attachments");
    $stmt = $conn->prepare("INSERT INTO `tblreports`(`user_id`,`report_title`,`report_description`,`group_id`,`school_code`,`attachment_pdf`,`date_time`)VALUES(?,?,?,?,?,?,?)");
    $stmt->bind_param("sssssss",$user_id,$title,$description,$group_id,$school_code,$attachments,$date_time);
    if($stmt->execute()){
        $stmt->insert_id;
        $array_result=array();
         foreach ($stmt as $key => $value) {
             $array_result[$key]=$stmt->$key;
        }
    }

    $insert_id =  $array_result['insert_id'];

    
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
