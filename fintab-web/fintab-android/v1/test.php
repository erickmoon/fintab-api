<?php
include("../includes/dbconn.php");

$user_id = 1;
$duration_of_sales_days_for_loan_calculation = 61;
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
