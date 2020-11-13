<?php 
error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


// For Json Quickbase API Call for COMPOST SHARE.
$access_token = 'your access token';
$quickbase_domain = 'hackathon20-ngwacham.quickbase.com';

//For XML Quickbase API Call for COMPOST SHARE.
$auth_ticket ='your auth ticket';
$udata_from_ticket ='62394240.wvv4';
$app_token ='your app token';
$target_domain_url ='https://hackathon20-ngwacham.quickbase.com';
$appID ='';

?>