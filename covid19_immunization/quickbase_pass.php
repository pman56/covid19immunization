<?php
error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// for Compost Share App
$url4="https://hackathon20-ngwacham.quickbase.com/db/main";
$ch4 = curl_init();
curl_setopt($ch4,CURLOPT_URL, $url4);
$useragent4 ='Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1';
//4380 hours is 6 months auth ticket life
$post_add4='
<qdbapi>
   <username>Your quickbase email</username>
   <password>quickbase password</password>
   <hours>4380</hours>
   <udata>my data</udata>
</qdbapi>
';

$clength = strlen($post_add4);


curl_setopt($ch4, CURLOPT_HTTPHEADER, array(
"Content-Length: $clength",
"QUICKBASE-ACTION: API_Authenticate",
'Content-Type:application/xml'
));  

//curl_setopt($ch4,CURLOPT_CUSTOMREQUEST,'GET');
curl_setopt($ch4,CURLOPT_CUSTOMREQUEST,'POST');

//curl_setopt($ch4,CURLOPT_CUSTOMREQUEST,'DELETE');
curl_setopt($ch4,CURLOPT_POSTFIELDS, $post_add4);
curl_setopt($ch4,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($ch4,CURLOPT_SSL_VERIFYHOST,0);
curl_setopt($ch4,CURLOPT_RETURNTRANSFER, true);
$response4 = curl_exec($ch4);

curl_close($ch4);

print_r($response4);
//$json4 = json_decode($response4, true);
//$statement4= $json4["metadata"]["totalNumberOfRecordsProcessed"];


?>
