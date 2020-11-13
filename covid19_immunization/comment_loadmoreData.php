
        <script src="publishing_post.js" type="text/javascript"></script>


<?php

error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


 include('header_title.php');

$row  = $_POST['postRow'];
$pid  = $_POST['pid'];

$row_per_page = 5;


//include quickbase token
include('quickbase_token.php');
include('quickbase_tables.php');

//$table_users
//$access_token
//$quickbase_domain


$postid_field= 6;


$url = "https://api.quickbase.com/v1/records/query";
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $url);
$useragent ='Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1';
// query comments record

$post ='{
  "from": "'.$table_comments.'",
  "select": [
3,
6,
7,
8,
9,
10,
11


  ],

 "where": "{'.$postid_field.'.CT.'.$pid.'}",

 "sortBy": [
    {
      "fieldId": 3,
      "order": "ASC"
    },
    {
      "fieldId": 4,
      "order": "ASC"
    }
  ],

  



"options": {
    "skip": '.$row.',
    "top": 5,
    "compareWithAppLocalTime": false
  }

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


//echo "<br><br>num field: "  .$json["metadata"]["numFields"]. "<BR><br>";
//echo "<br><br>num rec: "  .$json["metadata"]["numRecords"]. "<BR><br>";

$total_count = $json["metadata"]["totalRecords"];
$color_alerts ='alerts alert-warning';


$output = '';

 foreach($json['data'] as $v2){
  

 $id = $v2['3']['value'];
$comment_id = $id;
                $postid = $v2['6']['value'];
                $comment = $v2['7']['value'];
                $timing = $v2['8']['value'];
                $userid = $v2['9']['value'];
                $fullname = $v2['10']['value'];
                $photo = $v2['11']['value'];
                





    $output .= '<div id="post_'.$id.'" class="post '.$color_alerts.' comments_hovering">';

$output .= "<div class=''>
<img class='' style='border-style: solid; border-width:3px; border-color:$color6; width:60px;height:60px; 
max-width:60px;max-height:60px;border-radius: 50%;' src='$photo' alt='User Image'><br>
<b style='color:$color6;font-size:18px;' >Name: $fullname </b><br><br>
</div>";





$output .= "<div style='float:right;top:0px;right:0;margin-top:-60px;right:0px;'>
<button class='post_css1'>
<a title='Click to access users Profile page' style='color:black;' href='profile2.html?id=$userid'>
<span style='font-size:20px;color:#8B008B;' class='fa fa-user'></span> View Users Profile</a></button><br>

</div>";



$output .= "
<b>Comments:</b> $comment &nbsp; &nbsp; &nbsp;
<br>";





$output .= "<span><b> <span style='color:$color6;' class='fa fa-calendar'></span>Time:</b>  
<span data-livestamp='$timing'></span></span>";


                     




  
    $output .= '</div><p></p>';

}

echo $output;
