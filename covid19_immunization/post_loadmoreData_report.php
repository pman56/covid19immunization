
        <script src="publishing_post.js" type="text/javascript"></script>


<?php
error_reporting(0);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

 include('header_title.php');

$row  = $_POST['postRow'];
$row_per_page = 3;


//include quickbase token
include('quickbase_token.php');
include('quickbase_tables.php');

//$table_users
//$access_token
//$quickbase_domain




$post_type_field= 18;
$post_type_value= 'reports';

$url = "https://api.quickbase.com/v1/records/query";
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $url);
$useragent ='Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1';
// query Posts record

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
19,
20,
21


  ],

  "where": "{'.$post_type_field.'.CT.'.$post_type_value.'}",

 "sortBy": [
    {
      "fieldId": 4,
      "order": "DESC"
    },
    {
      "fieldId": 5,
      "order": "DESC"
    }
  ],

  



"options": {
    "skip": '.$row.',
    "top": 3,
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



$output = '';

 foreach($json['data'] as $v1){
  
$id = $v1['3']['value'];
                $postid = $v1['3']['value'];
                $title = $v1['6']['value'];
                $title_seo = $v1['7']['value'];
                $content = $v1['8']['value'];
//$content = html_entity_decode(html_entity_decode($v1['8']['value']));
                $timing = $v1['9']['value'];
                $fullname = $v1['10']['value'];
                $photo = $v1['11']['value'];
                $userid = $v1['12']['value'];
                $address = $v1['13']['value'];
             
                $post_shortened = substr($content, 0, 90)."...";

$post_type= $v1['18']['value'];

$vaccine_name= $v1['20']['value'];

 $total_comments =$v1['21']['value'];


    $output .= '<div id="post_'.$id.'" class="post well">';


if($title){

$output .= "<div class=''>
<img class='' style='border-style: solid; border-width:3px; border-color:#8B008B; width:80px;height:80px; 
max-width:80px;max-height:80px;border-radius: 50%;' src='$photo' title='Image'><br>
<b style='color:#8B008B;font-size:18px;' >Name: $fullname </b><br><br>
</div>";

}



$output .= "<div style='float:right;top:0px;right:0;margin-top:-150px;right:0px;'>
<button class='post_css1'>
<a title='Click to access users Profile page' style='color:black;' href='profile2.html?id=$userid'>
<span style='font-size:20px;color:#8B008B;' class='fa fa-user'></span> View Users Profile</a></button><br>
</div>";




$output .= "<b class='title_css'>Reports Title/Name: $title</b><br><br>";



$output .= "<b>Vaccine Name:</b> $vaccine_name &nbsp; &nbsp; &nbsp;<br>";

$output .= "<b >Descriptions:</b><br> $post_shortened
<br>";


$output .= "<br>
<span><b> <span style='color:#8B008B;' class='fa fa-calendar'></span>Time:</b>  
<span data-livestamp='$timing'></span></span>";


                        $output .= "<div class='pc2'>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<span style='font-size:26px;color:#8B008B;' class='fa fa-comments'></span> 
&nbsp;<span id='$postid' style='cursor:pointer;' title='Comments' />
<a title='Comments' style='color:black' href='next_report.html?title=$title_seo&pid=$postid&uid=$userid&tit=$title'>Comments</a></span>
(<span id='comment_$postid'>$total_comments</span>)


<br>
<br>
<button class='readmore_btn btn btn-warning'><a title='Click to Read More and Comments' style='color:white;' 
href='next_report.html?title=$title_seo&pid=$postid'>Click to Read More and Comments</a></button>
</div>";

  
    $output .= '</div>';

}

echo $output;

















