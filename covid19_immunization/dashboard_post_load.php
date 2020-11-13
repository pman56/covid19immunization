﻿

<?php
error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

ini_set('max_execution_time', 300); //300 seconds = 5 minutes
// temporarly extend time limit
set_time_limit(300);


?>
        <script src="publishing_post.js" type="text/javascript"></script>
       
       <script>



$(document).ready(function(){


    $('.loadPost').click(function(){
        var postRow = Number($('#postRow').val());
        var postCount = Number($('#pCounter').val());
        postRow = postRow + 3;

        if(postRow <= postCount){
            $("#postRow").val(postRow);

            $.ajax({
                url: 'post_loadmoreData.php',
                type: 'post',
                data: {postRow:postRow},
                beforeSend:function(){
                    //$(".loadPost").text("Loading Data...");
$(".loadPost").html("<span class='loader_post'></span> Loading Data...");
                    $('.loader_post').fadeIn(400).html('<span><i class="fa fa-spinner fa-spin" style="font-size:20px"></i></span>');

                },
                success: function(response){
                    setTimeout(function() {
                        $(".post:last").after(response).show().fadeIn("slow");
 
                        var rowno = postRow + 3;

//check number of row loaded
if(rowno > postCount){

var pRow = Number($('#postRow').val());
var pCount = Number($('#pCounter').val());

var remaining_row = pCount - pRow;

var pRow1 = pRow + remaining_row;
$(".no_of_row_loaded").text(pRow1);

}else{

$(".no_of_row_loaded").text(rowno);
}

                   
                        if(rowno > postCount){
                            $('.loadPost').text("No More Content to Load");
                              $('.loader_post').hide();
                        }else{
                            $(".loadPost").text("Load more");
                           $('.loader_post').hide();
                        }
                    }, 2000);
                   


                }
            });
        }

    });

});




$(document).ready(function(){
var userid_sess_data = localStorage.getItem('useridsessdata2');
var fullname_sess_data = localStorage.getItem('fullnamesessdata2');
var photo_sess_data = localStorage.getItem('photosessdata2');
$('#myd_userid_sess_value').val(userid_sess_data).value;
$('#myd_userid_sess_id').html(userid_sess_data);

$('#myd_fullname_sess_value').val(fullname_sess_data).value;
$('#myd_photo_sess_value').val(photo_sess_data).value;
});





</script>




<style>
.point_count { color: #FFF; display: block; float: right; border-radius: 12px; border: 1px solid #2E8E12; background: #ec5574; padding: 2px 6px;font-size:20px; }
.point_count1 { color:#FFF; display: block; float: right; border-radius: 12px; border: 1px solid #2E8E12; background: purple; padding: 2px 6px;font-size:20px; }


</style>






<!--start loading post-->



<!--input type='text' id='myd_userid_sess_value' class='userid_send1' value='' -->
<!--input type='text' id='myd_fullname_sess_value' class='fullname_send1' value=''-->
<!--input type='text' id='myd_photo_sess_value' class='photo_send1' value=''-->

<div style='display:none;' id='myd_userid_sess_id' data-useridsend2='myd_userid_sess_id'></div>






        <div class="content">


<?php


//include quickbase token
include('quickbase_token.php');
include('quickbase_tables.php');

?>



            <?php

$row_per_page1 = 3;


$post_type_field= 18;
$post_type_value= 'vaccination';

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
19


  ],

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

  
  "where": "{'.$post_type_field.'.CT.'.$post_type_value.'}",



"options": {
    "skip": 0,
    "top": '.$row_per_page1.',
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

if($json == ''){
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


if($total_count == 0){
echo "<div style='background:red;color:white;padding:10px;'>No Post/Covid19 Vaccination Center  has been Created Yet</div>";
}

 foreach($json['data'] as $v1){


          //echo $v1['3']['value'];

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

 //$total_comments =$v1['16']['value'];


$vaccine_appointment_request= $v1['16']['value'];
$vaccine_available= $v1['17']['value'];
$post_type = $v1['18']['value'];
$vaccination_city=$v1['19']['value'];
                

   // }





            ?>
                
                <div class="post well" id="post_<?php echo $postid; ?>">


<style>
.post_css1{
background:#ddd;
color:black;
border:none;
padding:10px;
border-radius:20%;
}


.post_css1:hover{
background:orange;
color:black;


}



.help_css{
background:#ddd;
color:black;
border:none;
padding:10px;
border-radius:20%;
font-size:20px;
}


.help_css:hover{
background:orange;
color:black;


}




</style>

<div>

<?php
if($title){
?>
<img class='' style='border-style: solid; border-width:3px; border-color:#8B008B; width:80px;height:80px; 
max-width:80px;max-height:80px;border-radius: 50%;' src='<?php echo $photo; ?>' title='Image'><br>
<b style='color:#8B008B;font-size:18px;' >Name: <?php echo $fullname; ?> </b><br><br>

<?php } ?>

</div>


<div style='float:right;top:0px;right:0;margin-top:-150px;right:0px;'>
<button class='post_css1'>
<a title='Click to access users Profile page' style='color:black;' href='profile2.html?id=<?php echo $userid; ?>'>
<span style='font-size:20px;color:#8B008B;' class='fa fa-user'></span> View Users Profile</a></button><br>

</div>



<button title='View Only this Vaccination Center on Map' class="map_css"><a target = "_blank" style="color:white;" href="map_private_vaccination.html?identity=<?php echo $timing; ?>">
<i  style="color:white;font-size:30px;" class="fa fa-map-marker" aria-hidden="true"></i>
View Only this Vaccination Center on Map </a></button>&nbsp;&nbsp;

<button title='View All Vaccination Center on Map' class="map_css1"><a target = "_blank" style="color:white;" href="map_vaccination.html">
<i  style="color:white;font-size:30px;" class="fa fa-map-marker" aria-hidden="true"></i>
View All Vaccination Center on Map</a></button><br><br>


<b class='title_css'>Vaccination Center Title/Name:<?php echo $title; ?></b><br><br>


<b >Vaccination Center Descriptions:</b><br><?php echo $post_shortened; ?> ....<br>
<b>Location:</b> <?php echo $address; ?> &nbsp; &nbsp; &nbsp;<br>
<b>City Name:</b> <?php echo $vaccination_city; ?> &nbsp; &nbsp; &nbsp;<br>


<div style='background:white;color:black;padding:10px;font-size:20px;font-family:comic sans ms;'>
<center>
<b >Total Available Vaccine Supplied to this Center:</b>&nbsp; &nbsp; &nbsp;<span style='color:purple'><?php echo $vaccine_available; ?></span>
 <br>vs<br>
<b >Total Vaccine Appointment Booked at This Center:</b>&nbsp; &nbsp; &nbsp;<span style='color:purple'><?php echo $vaccine_appointment_request; ?></span>
</center>
</div>

<br><br>
<span><b> <span style='color:#8B008B;' class='fa fa-calendar'></span>Time:</b>  <span data-livestamp="<?php echo $timing;?>"></span></span>



                        <div class="pc2">

<br>
<button class='readmore_btn btn btn-warning'><a title='Click to Book Vaccination Appointments' style='color:white;' 
href='next_vaccination.html?title=<?php echo $title_seo; ?>&pid=<?php echo $postid; ?>&notifyId='>Click to Book Vaccination Appointments</a></button>
</div>



</div>




                </div>

            <?php
            }
            ?>

            <h1 class="loadPost  category_post" title='Load More Post!'> Load More Posts</h1>


<?php
if($total_count < 3 || $total_count == 3){
?>
(<span class="no_of_row_loaded"><?php echo $total_count; ?></span> out of <span class="p"><?php echo $total_count; ?></span>)
 <?php } ?>

<?php
if($total_count > 3){
?>
(<span class="no_of_row_loaded">3</span> out of <span class="p"><?php echo $total_count; ?></span>)
 <?php } ?>

            <input type="hidden" id="postRow" value="0">
            <input type="hidden" id="pCounter" value="<?php echo $total_count; ?>">

        </div>




<!--End loading posts-->

















