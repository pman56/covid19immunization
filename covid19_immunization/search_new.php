
<?php
error_reporting(0);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


//include quickbase token
include('quickbase_token.php');
include('quickbase_tables.php');



if(isset($_POST['token']) && $_POST['token'] == '101201')
    {
$search = strip_tags($_POST['search_data_m']);


$url = "https://api.quickbase.com/v1/records/query";
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $url);
$useragent ='Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1';


$address_field= 13;


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
16


  ],

  "where": "{'.$address_field.'.CT.'.$search.'}"

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

$num_field = $json["metadata"]["numFields"];
$num_rec = $json["metadata"]["numRecords"];

if($num_rec == 0){

echo "<div id='alerts_search' class='alerts alert-danger searching_res_p1 search_hide'>Searched Result not Found... 
<span class='search_hide_btn1 btn btn-sm btn-warning pull-right'>close</span>
</div>";

}
elseif($num_rec > 0){

$id  = $json['data'][0]['3']['value'];
$pid = $id;
$title  = $json['data'][0]['6']['value'];
$title_seo  = $json['data'][0]['7']['value'];
$address  = $json['data'][0]['13']['value'];
$photo  =   $json['data'][0]['11']['value'];





 echo "
<div class='searching_res_p search_hide'>
<a href='next_vaccination.html?title=$title_seo&pid=$pid&notifyId='>
<img class='img-circle' src='$photo' style='width:40px;height:40p; float:left; margin-right:6px' />
<span style='font-size:16px; color:white'>Title: $title</span><br>
<span style='font-size:16px; color:white'>Vaccination Center Address: $address</span><br>
<span class='search_hide_btn1 btn btn-sm btn-warning pull-right'>close</span>
</a>
</div>";

}
else{

echo " <div style='background:red;padding:10px;color:white;'>There is problem with Queries</div>";
}



}


?>