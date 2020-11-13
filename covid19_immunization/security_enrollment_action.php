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



$question = strip_tags($_POST['question']);
$answer = strip_tags($_POST['answer']);
$email = strip_tags($_POST['email']);


//include quickbase token
include('quickbase_token.php');
include('quickbase_tables.php');



$url2="https://api.quickbase.com/v1/records";
$ch2 = curl_init();
curl_setopt($ch2,CURLOPT_URL, $url2);
$useragent2 ='Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1';


$email_field = 6;
$question_field =7;
$answer_field = 8;

$post_add='
{
  "to": "'.$table_security_questions.'",
  "data": [
    {


      "'.$email_field.'": {
        "value": "'.$email.'"
      },
      "'.$question_field.'": {
        "value": "'.$question.'"
      },
"'.$answer_field.'": {
        "value": "'.$answer.'"
      }



    }
  ],

 "fieldsToReturn": [
3,
    6,
    7,
    8

  ]

}

';


curl_setopt($ch2, CURLOPT_HTTPHEADER, array(
"QB-Realm-Hostname: $quickbase_domain",
"User-Agent: $useragent2",
"Authorization: QB-USER-TOKEN $access_token",
'Content-Type:application/json'
));  

//curl_setopt($ch2,CURLOPT_CUSTOMREQUEST,'GET');
curl_setopt($ch2,CURLOPT_CUSTOMREQUEST,'POST');

//curl_setopt($ch2,CURLOPT_CUSTOMREQUEST,'DELETE');
curl_setopt($ch2,CURLOPT_POSTFIELDS, $post_add);
curl_setopt($ch2,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($ch2,CURLOPT_SSL_VERIFYHOST,0);
curl_setopt($ch2,CURLOPT_RETURNTRANSFER, true);
$response2 = curl_exec($ch2);

curl_close($ch2);

//print_r($response2);
$json2 = json_decode($response2, true);
$statement= $json2["metadata"]["totalNumberOfRecordsProcessed"];


// get last inserted Id 
$lastId  = $json2['data'][0]['3']['value'];
$uID = $lastId;


        if($statement){

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



