<?php
error_reporting(0);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");



ini_set('max_execution_time', 300); //300 seconds = 5 minutes
// temporarly extend time limit
set_time_limit(300);


$timer = time();
include("time/now.fn");
$created_time=strip_tags($now);
$dt2=date("Y-m-d H:i:s");

$title = strip_tags($_POST['title']);
//replace any space with hyphen
$sp ='-';
$tt = time();
$title_seo = str_replace(' ', '-', $title.$sp.$tt);






$geo_address = trim($_POST['geo_address']);
$offering = strip_tags($_POST['offering']);
$messaging = strip_tags($_POST['messaging']);
$vaccine_name = strip_tags($_POST['vaccine_name']);



$userid_sess_data = $_POST['userid_sess_data'];
$fullname_sess_data = $_POST['fullname_sess_data'];
$photo_sess_data = $_POST['photo_sess_data'];






//include quickbase token
include('quickbase_token.php');
include('quickbase_tables.php');



$url2="https://api.quickbase.com/v1/records";
$ch2 = curl_init();
curl_setopt($ch2,CURLOPT_URL, $url2);
$useragent2 ='Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1';


$title_field = 6;
$title_seo_field =7;
$content_field = 8;
$timing_field =9;
$fullname_field = 10;
$photourl_field = 11;
$userid_field = 12;

$vaccine_name_field =20;
$total_comments_field =21;

$post_type_field =18;
$post_type_value= 'reports';


$post_add='
{
  "to": "'.$table_posts.'",
  "data": [
    {


      "'.$title_field.'": {
        "value": "'.$title.'"
      },
      "'.$title_seo_field.'": {
        "value": "'.$title_seo.'"
      },
"'.$content_field.'": {
        "value": "'.$messaging.'"
      },
"'.$timing_field.'": {
        "value": "'.$timer.'"
      },
 "'.$fullname_field.'": {
        "value": "'.$fullname_sess_data.'"
      },
 "'.$photourl_field.'": {
        "value": "'.$photo_sess_data.'"
      },
 "'.$userid_field.'": {
        "value": "'.$userid_sess_data.'"
      },
 "'.$vaccine_name_field.'": {
        "value": "'.$vaccine_name.'"
      },
 "'.$total_comments_field.'": {
        "value": "0"
      },

"'.$post_type_field.'": {
        "value": "reports"
      }




    }
  ],

 "fieldsToReturn": [
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
19,
20,
21

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


// get last inserted Id for post table
$lastId  = $json2['data'][0]['3']['value'];
$postID = $lastId;



if($json2 == ''){
echo 4;
exit();
}






// query users table to update notification_post table

$userid_field_query = 3;
$url3 = "https://api.quickbase.com/v1/records/query";
$ch3 = curl_init();
curl_setopt($ch3,CURLOPT_URL, $url3);
$useragent3 ='Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1';

$data_params3 ='{
  "from": "'.$table_users.'",
  "select": [
3,
    6,
    7,
    8,
    9,
    10
  ],

  "where": "{'.$userid_field_query.'.XCT.'.$userid_sess_data.'}"

}
';

//"where": "{'.$userid_field_query.'.XCT.'.$userid_sess_data.'}"

curl_setopt($ch3, CURLOPT_HTTPHEADER, array(
"QB-Realm-Hostname: $quickbase_domain",
"User-Agent: $useragent3",
"Authorization: QB-USER-TOKEN $access_token",
'Content-Type:application/json'
));  

//curl_setopt($ch3,CURLOPT_CUSTOMREQUEST,'GET');
curl_setopt($ch3,CURLOPT_CUSTOMREQUEST,'POST');

//curl_setopt($ch3,CURLOPT_CUSTOMREQUEST,'DELETE');
curl_setopt($ch3,CURLOPT_POSTFIELDS, $data_params3);
curl_setopt($ch3,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($ch3,CURLOPT_SSL_VERIFYHOST,0);
curl_setopt($ch3,CURLOPT_RETURNTRANSFER, true);
$response3 = curl_exec($ch3);

curl_close($ch3);

//print_r($response3);
$json3 = json_decode($response3, true);

$rec_List1 = $json3["metadata"]["numRecords"];


if($rec_List1 > 0){
//$count = 1;

foreach($json3['data'] as $v1){
$reciever_userid = $v1['3']['value'];
$reciever_username = $v1['6']['value'];


	    
//insert into notification_post table
//insert starts




$url4="https://api.quickbase.com/v1/records";
$ch4 = curl_init();
curl_setopt($ch4,CURLOPT_URL, $url4);
$useragent4 ='Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1';


$post_id_field = 6;
$userid_field =7;
$fullname_field = 8;
$photo_field = 9;
$user_rank_field = 10;
$reciever_id_field =11;
$status_field = 12;
$type_field = 13;
$timing_field = 14;
$title_field = 15;
$title1_field = 16;


$post_add4='

{
  "to": "'.$table_notification_post.'",
  "data": [
    {


      "'.$post_id_field.'": {
        "value": "'.$lastId.'"
      },
      "'.$userid_field.'": {
        "value": "'.$userid_sess_data.'"
      },
"'.$fullname_field.'": {
        "value": "'.$fullname_sess_data.'"
      },
"'.$photo_field.'": {
        "value": "'.$photo_sess_data.'"
      },
 "'.$user_rank_field.'": {
        "value": "Member"
      },
 "'.$reciever_id_field.'": {
        "value": "'.$reciever_userid.'"
      },
 "'.$status_field.'": {
        "value": "unread"
      },
 "'.$type_field.'": {
        "value": "post"
      },
 "'.$timing_field.'": {
        "value": "'.$timer.'"
      },
 "'.$title_field.'": {
        "value": "'.$title.'"
      },
 "'.$title1_field.'": {
        "value": "'.$title_seo.'"
      }



    }
  ],

 "fieldsToReturn": [
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
16


  ]

}

';


curl_setopt($ch4, CURLOPT_HTTPHEADER, array(
"QB-Realm-Hostname: $quickbase_domain",
"User-Agent: $useragent4",
"Authorization: QB-USER-TOKEN $access_token",
'Content-Type:application/json'
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

//print_r($response4);
$json4 = json_decode($response4, true);
$statement4= $json4["metadata"]["totalNumberOfRecordsProcessed"];






//insert ends
		  

		    //$count++;
		}
	}else{
		//echo "<div>No Users found.</div>";
	}




if($statement){
echo 1;	

}
else{
//echo "post could not be submitted";
echo 2;
}






?>