<?php


error_reporting(0);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//include quickbase token
include('quickbase_token.php');
include('quickbase_tables.php');

$email_sess_sec = $_POST['email_sess_sec'];



$email_field = 6;
$question_field =7;
$answer_field = 8;


$url_npt = "https://api.quickbase.com/v1/records/query";
$ch_npt = curl_init();
curl_setopt($ch_npt,CURLOPT_URL, $url_npt);
$useragent_npt ='Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1';

$data_params_npt ='{
  "from": "'.$table_security_questions.'",
  "select": [
3,
    6,
    7,
    8

  ],

  "where": "{'.$email_field.'.CT.'.$email_sess_sec.'}"

}
';





curl_setopt($ch_npt, CURLOPT_HTTPHEADER, array(
"QB-Realm-Hostname: $quickbase_domain",
"User-Agent: $useragent_npt",
"Authorization: QB-USER-TOKEN $access_token",
'Content-Type:application/json'
));  

//curl_setopt($ch_npt,CURLOPT_CUSTOMREQUEST,'GET');
curl_setopt($ch_npt,CURLOPT_CUSTOMREQUEST,'POST');

//curl_setopt($ch_npt,CURLOPT_CUSTOMREQUEST,'DELETE');
curl_setopt($ch_npt,CURLOPT_POSTFIELDS, $data_params_npt);
curl_setopt($ch_npt,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($ch_npt,CURLOPT_SSL_VERIFYHOST,0);
curl_setopt($ch_npt,CURLOPT_RETURNTRANSFER, true);
$response_npt = curl_exec($ch_npt);

curl_close($ch_npt);

//print_r($response_npt);
$json_npt = json_decode($response_npt, true);
$count_npt = $json_npt["metadata"]["numRecords"];

$question  = $json_npt['data'][0]['7']['value'];

echo "<div style='background:white;color:black;padding:10px;font-size:20px;'><b>Question: </b> $question </div>";

?>

