<?php
header('Content-type: text/html; charset=utf-8');

include("../includes/dbconn.php");

$settings_id = 1;

$stmt = $conn->prepare("SELECT savings_interest_rate,savings_interest_days,loans_interest_rate_per_day,goods_interest_rate_per_day,minimum_withdrawable,maximum_withdrawable,b2c_remarks_withdraw_from_savings,maximum_loan_amount,max_days_to_end_date,maxium_loan_days,maxium_goods_loan_days,loan_sales_factor,minumum_loan_amount,duration_of_sales_days_for_loan_calculation,agrovets_user_id,b2c_remarks,savings_enabled,base_app_name,paybill_shorcode_user,paybill_shorcode,deposit_paybill_shortcode FROM app_settings WHERE id = ? LIMIT 1");
$stmt->bind_param("i",$settings_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($savings_interest_rate,$savings_interest_days,$loans_interest_rate_per_day,$goods_interest_rate_per_day,$minimum_withdrawable,$maximum_withdrawable,$b2c_remarks_withdraw_from_savings,$maximum_loan_amount,$max_days_to_end_date,$maxium_loan_days,$maxium_goods_loan_days,$loan_sales_factor,$minumum_loan_amount,$duration_of_sales_days_for_loan_calculation,$agrovets_user_id,$b2c_remarks,$savings_enabled,$base_app_name,$paybill_shorcode_user,$paybill_shorcode,$deposit_paybill_shortcode);
$stmt->fetch(); 

echo "$savings_interest_rate,$savings_interest_days,$loans_interest_rate_per_day,$goods_interest_rate_per_day,$minimum_withdrawable,$maximum_withdrawable,$b2c_remarks_withdraw_from_savings,$maximum_loan_amount,$max_days_to_end_date,$maxium_loan_days,$maxium_goods_loan_days,$loan_sales_factor,$minumum_loan_amount,$duration_of_sales_days_for_loan_calculation,$agrovets_user_id,$b2c_remarks,$savings_enabled,$base_app_name,$paybill_shorcode_user,$paybill_shorcode,$deposit_paybill_shortcode";