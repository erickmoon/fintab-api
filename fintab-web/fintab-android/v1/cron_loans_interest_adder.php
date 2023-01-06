<?php

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
    "service":"cron_loans_interest_adder"
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);