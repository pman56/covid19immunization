<?php
//error_reporting(0);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


ini_set('max_execution_time', 300); //300 seconds = 5 minutes
// temporarly extend time limit
set_time_limit(300);


//include quickbase token
include('quickbase_token.php');
include('quickbase_tables.php');


$url = "https://api.quickbase.com/v1/records/query";
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $url);
$useragent ='Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1';

$post ='{
  "from": "'.$table_posts.'",
  "select": [
3,
6,
7,
8,
9,
10,
11,
12,
13,
14,
15,
16,
17,
18,
19


  ]
 
}
';




curl_setopt($ch, CURLOPT_HTTPHEADER, array(
"QB-Realm-Hostname: $quickbase_domain",
"User-Agent: $useragent",
"Authorization: QB-USER-TOKEN $access_token",
'Content-Type:application/json'
));  

//curl_setopt($ch,CURLOPT_CUSTOMREQUEST,'GET');
curl_setopt($ch,CURLOPT_CUSTOMREQUEST,'POST');

//curl_setopt($ch,CURLOPT_CUSTOMREQUEST,'DELETE');
curl_setopt($ch,CURLOPT_POSTFIELDS, $post);
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);


curl_close($ch);

//print_r($response);
$json = json_decode($response, true);
$total_count = $json["metadata"]["totalRecords"];

$data[] = array('Vaccination Centers','Vaccine Supplied', 'Vaccine Demands');


foreach($json['data'] as $v1){
$vaccine_demand_via_appointment = $v1['16']['value'];
$vaccine_supplied = $v1['17']['value'];
$vaccine_cityname = $v1['19']['value'];

$data[] = array($vaccine_cityname,(int)$vaccine_supplied,(int)$vaccine_demand_via_appointment);

}


/*
$data[] = array('Employee','Boys', 'Girls');
$query = mysqli_query($db, "SELECT 0 FROM population");
while($row = mysqli_fetch_array($query)){
$data[] = array($row['locality'],(int)$row['boys'],(int)$row['girls']);

}
*/
echo json_encode($data);
