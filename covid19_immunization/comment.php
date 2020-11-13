<?php
//error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


ini_set('max_execution_time', 300); //300 seconds = 5 minutes
// temporarly extend time limit
set_time_limit(300);


include('quickbase_token1.php');
include('quickbase_tables.php');


$postid = strip_tags($_POST['postid']);
$comment_type = strip_tags($_POST['type']);
$type = strip_tags($_POST['type']);
$comdesc = strip_tags($_POST['comdesc']);

$userid_sess_data = $_POST['userid_sess_data'];
$fullname_sess_data = $_POST['fullname_sess_data'];
$photo_sess_data = $_POST['photo_sess_data'];

if ($comdesc == ''){
exit();
}









// insert into comments table
$url2="https://api.quickbase.com/v1/records";
$ch2 = curl_init();
curl_setopt($ch2,CURLOPT_URL, $url2);
$useragent2 ='Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1';


$postid_field = 6;
$comment_field =7;
$timing_field =8;
$userid_field = 9;
$fullname_field = 10;
$userphoto_field = 11;


$token= md5(uniqid());
$timer = time();
include("time/now.fn");
$created_time=strip_tags($now);
$dt2=date("Y-m-d H:i:s");
$pa = 0;


$post_add='

{
  "to": "'.$table_comments.'",
  "data": [
    {


      "'.$postid_field.'": {
        "value": "'.$postid.'"
      },
      "'.$comment_field.'": {
        "value": "'.$comdesc.'"
      },
"'.$timing_field.'": {
        "value": "'.$timer.'"
      },
 "'.$userid_field.'": {
        "value": "'.$userid_sess_data.'"
      },
 "'.$fullname_field.'": {
        "value": "'.$fullname_sess_data.'"
      },
 "'.$userphoto_field.'": {
        "value": "'.$photo_sess_data.'"
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
11

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


// get last inserted Id for comment table
$lastId  = $json2['data'][0]['3']['value'];
$commentID = $lastId;





// query table posts to get data

$postid_field2 =  3;
$postid_userid_field2 =  12;

$url3a = "https://api.quickbase.com/v1/records/query";
$ch3a = curl_init();
curl_setopt($ch3a,CURLOPT_URL, $url3a);
$useragent3a ='Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1';

$data_params3a ='{
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
16

  ],

  "where": "{'.$postid_field2.'.CT.'.$postid.'}"

}
';

curl_setopt($ch3a, CURLOPT_HTTPHEADER, array(
"QB-Realm-Hostname: $quickbase_domain",
"User-Agent: $useragent3a",
"Authorization: QB-USER-TOKEN $access_token",
'Content-Type:application/json'
));  

//curl_setopt($ch3a,CURLOPT_CUSTOMREQUEST,'GET');
curl_setopt($ch3a,CURLOPT_CUSTOMREQUEST,'POST');

//curl_setopt($ch3a,CURLOPT_CUSTOMREQUEST,'DELETE');
curl_setopt($ch3a,CURLOPT_POSTFIELDS, $data_params3a);
curl_setopt($ch3a,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($ch3a,CURLOPT_SSL_VERIFYHOST,0);
curl_setopt($ch3a,CURLOPT_RETURNTRANSFER, true);
$response3a = curl_exec($ch3a);

curl_close($ch3a);

//print_r($response3a);
$json3a = json_decode($response3a, true);
$counta = $json3a["metadata"]["numRecords"];

$post_userid= $json3a["data"][0]["12"]["value"];
$reciever_userid = $post_userid;
$title= $json3a["data"][0]["6"]["value"];
$title_seo= $json3a["data"][0]["7"]["value"];
$t_comments= $json3a["data"][0]["21"]["value"];
$totalcomment = $t_comments + 1;

//if($post_userid != $userid_sess_data){

// insert into notification post table




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
        "value": "'.$postid.'"
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
        "value": "'.$user_rank_sess_data.'"
      },
 "'.$reciever_id_field.'": {
        "value": "'.$reciever_userid.'"
      },
 "'.$status_field.'": {
        "value": "unread"
      },
 "'.$type_field.'": {
        "value": "comment"
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


$total_comment_field = 21;


// update table posts with the comments count starts here

$url_update2 = "https://api.quickbase.com/v1/records";
$ch_update2 = curl_init();
curl_setopt($ch_update2,CURLOPT_URL, $url_update2);
$useragent_update2 ='Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1';

$post_update2='

{
  "to": "'.$table_posts.'",
  "data": [
    {

      "'.$total_comment_field.'": {
        "value": "'.$totalcomment.'"
      },

 "3": {
        "value": "'.$postid.'"
      }

    }
  ],

 "fieldsToReturn": [
3,
    6,
    7,
    8,
10,
16
  ]

}

';


curl_setopt($ch_update2, CURLOPT_HTTPHEADER, array(
"QB-Realm-Hostname: $quickbase_domain",
"User-Agent: $useragent_update2",
"Authorization: QB-USER-TOKEN $access_token",
'Content-Type:application/json'
));  

//curl_setopt($ch_update2,CURLOPT_CUSTOMREQUEST,'GET');
curl_setopt($ch_update2,CURLOPT_CUSTOMREQUEST,'POST');

//curl_setopt($ch_update2,CURLOPT_CUSTOMREQUEST,'DELETE');
curl_setopt($ch_update2,CURLOPT_POSTFIELDS, $post_update2);
curl_setopt($ch_update2,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($ch_update2,CURLOPT_SSL_VERIFYHOST,0);
curl_setopt($ch_update2,CURLOPT_RETURNTRANSFER, true);
$response_update2 = curl_exec($ch_update2);

curl_close($ch_update2);

//print_r($response_update2);
$json_update2 = json_decode($response_update2, true);

$updated_rec_id2 = $json_update2["data"][0]["3"]["value"];

// update table posts with the comments counts ends here



$array_comment = array("comment"=>$totalcomment,"comdesc"=>$comdesc,"comment_username"=>$username_sess_data,"comment_fullname"=>$fullname_sess_data,"comment_photo"=>$photo_sess_data,"comment_time"=>$timer, "userid"=>$userid_sess_data, "comment_id"=>$commentID);
echo json_encode($array_comment);

?>





