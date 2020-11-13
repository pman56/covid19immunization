
<?php
error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


ini_set('max_execution_time', 300); //300 seconds = 5 minutes
// temporarly extend time limit
set_time_limit(300);



//include quickbase token
include('quickbase_token.php');
include('quickbase_tables.php');
$request_userid= intval($_POST['r_id']);

// query users table

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
    10,
	11,
	12,
	13,
	14,
	15,
	16,
	17
  ],

  "where": "{'.$userid_field_query.'.CT.'.$request_userid.'}"

}
';





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
$user_rec_count = $json3["metadata"]["numRecords"];

$u_rec_id = $json3["data"][0]["6"]["value"];
$fullname = $json3["data"][0]["8"]["value"];;
$photo =    $json3["data"][0]["10"]["value"];
$created_time = $json3["data"][0]["11"]["value"];
$user_rank = $json3["data"][0]["12"]["value"];



if($json3 == ''){
//if($total_count == ''){
echo "
<script>
function reloadPage() {
location.reload();
}
</script>
<div style='background:red;color:white;padding:10px;'>No Network. Refresh page and ensure there is Internet Connection <br><br> <center><button class='readmore_btn' style='' title='Refresh Page' onclick='reloadPage()'>Refresh Page</button></center> </div>";
exit();
}


if($user_rec_count == 0){
echo "<div style='background:red;color:white;padding:10px;'>No Record Found for the Queried User..</div>";
exit();
}







//npt is notification post table

$npt_userid_field =12;
$url_npt = "https://api.quickbase.com/v1/records/query";
$ch_npt = curl_init();
curl_setopt($ch_npt,CURLOPT_URL, $url_npt);
$useragent_npt ='Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1';

$data_params_npt ='{
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

  "where": "{'.$npt_userid_field.'.CT.'.$request_userid.'}"

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


?>




<!--create profile form START here-->

<div  class='col-sm-12' style='border-style: dashed; border-width:2px; border-color: orange;color:black;padding:10px;background:#eeeeee'>

<h3><center>Members Profiles/Posts</center></h3>
<div class='col-sm-6'>
<img style='max-height:200px;max-width:200px;' class='img-rounded' width='200px' height='200px' src='<?php echo $photo; ?>'>
<br>
</div>
<div class='col-sm-6'>
<b>Fullname:</b> <?php echo htmlentities(htmlentities($fullname, ENT_QUOTES, "UTF-8")); ?>
<br>
<b style='font-size:14px;'> Rank:</b> <?php echo htmlentities(htmlentities($user_rank, ENT_QUOTES, "UTF-8")); ?><br>
<b style='font-size:14px;'> Status:</b> Verified Member<br>
<b style='font-size:16px;color:#8B008B'> This Member has : <?php echo $count_npt; ?> Posts</b><br>


<b style='font-size:16px;'> Member Since:</b> <span data-livestamp='<?php echo $created_time; ?>'></span><br>
</div>



<div  class='col-sm-12' style='width:100%;'><br><br></div>






<!--create profile form ENDS-->
