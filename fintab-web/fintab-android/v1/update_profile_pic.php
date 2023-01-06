<?php
header('Content-type: text/html; charset=utf-8');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include("../includes/dbconn.php");
$allowed_file_types = array(".jpg",".raw",".jpeg",".png","jpg","raw","jpeg","png");


// Path to move uploaded files
$location   = 'http://apps.soweaa.co.ke/android/v1/uploads/';
$prefix = "storm_";
$images = "";
$date_time = date('D, M j, Y \a\t g:ia');


$apiKEY = "3948a6fecbcc219f0910616013733edaa2fd06caaa7706118f2db62e6";
$consumerKEY = "0ea47d09371b981b30b2f3d061183cc219f091f2db62e619f8f395fb";

$ip_address = $_SERVER['REMOTE_ADDR'];
function writeFile($name, $string) {
    $filename = $name.".txt"; 
    $fp = fopen($filename,"a+");  
    fputs($fp,$string); 
    fclose($fp);  
}
//writeFile("test1","Reached here");
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





if($apiKEY === $_POST['apiKEY'] and $consumerKEY === $_POST['consumerKEY']){

//cv_citizenship,cv_name,cv_gender,cv_education,cv_experience,cv_location,cv_date_of_birth

    $user_id = $_POST['user_id']; 
    
    
    //writeFile("test2","Reached here");

    $fileName = $_FILES["photo"]['tmp_name'];
    $fileExt = pathinfo($_FILES["photo"]['name'], PATHINFO_EXTENSION);
    $resizeFileName = hash('sha256', time() . rand(000, 999));
    $uploadPath = "uploads/";
    $fileExt = pathinfo($_FILES["photo"]['name'], PATHINFO_EXTENSION);
                
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
            $attachment = "storm_".$resizeFileName . "." . $fileExt;
            unlink("uploads/" . $resizeFileName . "." . $fileExt);
        }
    
    }
    
  
    $attachment = "http://apps.soweaa.co.ke/android/v1/uploads/$attachment";

    
    $stmt = $conn->prepare("UPDATE `tblmembers` SET `photo_url` = ? WHERE `id` = ?");
    $stmt->bind_param("si",$attachment,$user_id);
    $stmt->execute();
        
    echo json_encode(array('status'=>'success', 'message'=>'File Uploaded'));
     
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