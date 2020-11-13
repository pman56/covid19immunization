<?php
error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

ini_set('max_execution_time', 300); //300 seconds = 5 minutes
// temporarly extend time limit
set_time_limit(300);


if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {

$nounce = intval( $_POST['nounce'] );
if ( $nounce == '' ) {
        echo "nounce protection cannot be empty";
        exit();
    }



$answer = strip_tags($_POST['answer']);
$email = strip_tags($_POST['email']);


//include quickbase token
include('quickbase_token.php');
include('quickbase_tables.php');




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

  "where": "{'.$email_field.'.CT.'.$email.'}"

}
';


//"where": "{'.$email_field.'.EX.'.$email.'}AND{'.$answer_field.'.EX.'.$answer.'}"


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

$em = $json_npt['data'][0]['6']['value'];
$ques = $json_npt['data'][0]['7']['value'];
$ans  = $json_npt['data'][0]['8']['value'];

//if($count_npt > 0){

if($ans == $answer){
//echo "susccess";
echo 1;
exit();

}

else{
//echo "failed";
echo 2;
exit(); 

}





	
}



?>



