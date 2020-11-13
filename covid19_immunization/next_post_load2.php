 <?php
error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


$title_seo = strip_tags($_POST['title']);
$postID_call = strip_tags($_POST['pid']);
$postid= $postID_call;



//include quickbase token
include('quickbase_token.php');
include('quickbase_tables.php');



$post_title_seo_field= 7;
$post_value= 'post';

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

 "where": "{'.$post_title_seo_field.'.CT.'.$title_seo.'}"

 
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
//$id_for_updates  = $json['data'][0]['3']['value'];


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
echo "<div style='background:red;color:white;padding:10px;'>Searched Post does not exist</div>";
exit();
}

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
             
                $post_shortened = $content;

 //$total_comments =$v1['16']['value'];

$vaccine_appointment_request= $v1['16']['value'];
$vaccine_available= $v1['17']['value'];
$post_type = $v1['18']['value'];
$vaccination_city=$v1['19']['value'];
                

   // }





            ?>


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



        <script src="publishing_post.js" type="text/javascript"></script>


       
       <script>

var fullname_sess_data = localStorage.getItem('fullnamesessdata2');
$('.myd_fname_sess_value').val(fullname_sess_data).value;

$(document).ready(function(){


    $('.loadAppointment').click(function(){

var post_url = window.location.href;
var urlData = new URL(post_url);
var title = urlData.searchParams.get("title");
var pid = urlData.searchParams.get("pid");


        var postRow = Number($('#postRow').val());
        var postCount = Number($('#pCounter').val());
        var pid =pid;
        postRow = postRow + 5;

//alert('my pid:' +pid);

        if(postRow <= postCount){
            $("#postRow").val(postRow);

            $.ajax({
                url: 'appointment_loadmoreData.php',
                type: 'post',
                data: {postRow:postRow, pid:pid},
                beforeSend:function(){
                    //$(".loadAppointment").text("Loading More Appointments...");
$(".loadAppointment").html("<span class='loader_appointment'></span> Loading More Appointments...");
                    $('.loader_appointment').fadeIn(400).html('<span><i class="fa fa-spinner fa-spin" style="font-size:24px"></i></span>');

                },
                success: function(response){
                    setTimeout(function() {
                        $(".post:last").after(response).show().fadeIn("slow");
 
                        var rowno = postRow + 5;

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
                            $('.loadAppointment').text("No More Content to Load");
                              $('.loader_appointment').hide();
                        }else{
                            $(".loadAppointment").text("Load more");
                           $('.loader_appointment').hide();
                        }
                    }, 2000);
                   


                }
            });
        }

    });

});



</script>








<div class='well'>



<div>

<?php
if($title){
?>
<img class='' style='border-style: solid; border-width:3px; border-color:#8B008B; width:80px;height:80px; 
max-width:80px;max-height:80px;border-radius: 50%;' src='<?php echo $photo; ?>'  title='User Image'><br>
<b style='color:#8B008B;font-size:18px;' >Name: <?php echo $fullname; ?> </b><br><br>

<?php } ?>

</div>


<div style='float:right;top:0px;right:0;margin-top:-150px;right:0px;'>

<button class='post_css1'>
<a title='Click to access users Profile page' style='color:black;' href='profile2.html?id=<?php echo $userid; ?>'>
<span style='font-size:20px;color:#8B008B;' class='fa fa-user'></span> View Users Profile</a></button><br>
</div>




<?php
if($title){
?>





<button title='View Only this Vaccination Center on Map' class="map_css"><a target = "_blank" style="color:white;" href="map_private_vaccination.html?identity=<?php echo $timing; ?>">
<i  style="color:white;font-size:30px;" class="fa fa-map-marker" aria-hidden="true"></i>
View Only this Vaccination Center on Map </a></button>&nbsp;&nbsp;

<button title='View All Vaccination Center on Map' class="map_css1"><a target = "_blank" style="color:white;" href="map_vaccination.html">
<i  style="color:white;font-size:30px;" class="fa fa-map-marker" aria-hidden="true"></i>
View All Vaccination Center on Map</a></button><br><br>


<b class='title_css'>Vaccination Center Title/Name:   <?php echo $title; ?></b><br><br>


<b >Vaccination Center Descriptions:</b><br><?php echo $post_shortened; ?> ....<br>
<b>Location:</b> <?php echo $address; ?> &nbsp; &nbsp; &nbsp;<br>
<b>City Name:</b> <?php echo $vaccination_city; ?> &nbsp; &nbsp; &nbsp;<br>


<div style='background:white;color:black;padding:10px;font-size:20px;font-family:comic sans ms;'>
<center>
<b >Total Available Vaccine Supplied to this Center:</b>&nbsp; &nbsp; &nbsp;<span style='color:purple'><?php echo $vaccine_available; ?></span>
 <br>vs<br>
<b >Total Vaccine Appointment Booked at This Center:</b>&nbsp; &nbsp; &nbsp;<span style='color:purple'>
(<span id="appointment_<?php echo $postid; ?>"><?php echo $vaccine_appointment_request; ?></span>)</span>

</center>
</div>

<?php } ?>



<br><br>
<span><b> <span style='color:#8B008B;' class='fa fa-calendar'></span>Time:</b>  <span data-livestamp="<?php echo $timing;?>"></span></span>



                        <div class="pc2">

<br>
</div>




                </div>

            <?php
            }
            ?>




<!--start apointments -->





        <div class="content">




            <?php

error_reporting(0);

//include quickbase token
include('quickbase_token.php');
include('quickbase_tables.php');


$row_per_page = 5;
$post_field_c= 6;
//$postid = strip_tags($_POST['pid']);
//$postID_call = $postid;

$url_c = "https://api.quickbase.com/v1/records/query";
$ch_c = curl_init();
curl_setopt($ch_c,CURLOPT_URL, $url_c);
$useragent_c ='Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1';


$post_c ='{
  "from": "'.$table_vaccination_appointment.'",
  "select": [
3,
6,
7,
8,
9,
10,
11,
12


  ],

 
  
"where": "{'.$post_field_c.'.CT.'.$postid.'}",

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
    "skip": 0,
    "top": '.$row_per_page.',
    "compareWithAppLocalTime": false
  }

}
';




curl_setopt($ch_c, CURLOPT_HTTPHEADER, array(
"QB-Realm-Hostname: $quickbase_domain",
"User-Agent: $useragent_c",
"Authorization: QB-USER-TOKEN $access_token",
'Content-Type:application/json'
));  

//curl_setopt($ch_c,CURLOPT_CUSTOMREQUEST,'GET');
curl_setopt($ch_c,CURLOPT_CUSTOMREQUEST,'POST');

//curl_setopt($ch_c,CURLOPT_CUSTOMREQUEST,'DELETE');
curl_setopt($ch_c,CURLOPT_POSTFIELDS, $post_c);
curl_setopt($ch_c,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($ch_c,CURLOPT_SSL_VERIFYHOST,0);
curl_setopt($ch_c,CURLOPT_RETURNTRANSFER, true);
$response_c = curl_exec($ch_c);

curl_close($ch_c);

//print_r($response_c);
$json_c = json_decode($response_c, true);


//echo "<br><br>num field: "  .$json_c["metadata"]["numFields"]. "<BR><br>";
//echo "<br><br>num rec: "  .$json_c["metadata"]["numRecords"]. "<BR><br>";

$total_count_c = $json_c["metadata"]["totalRecords"];

if($total_count_c == 0){
echo "<div style='background:red;color:white;padding:10px;'>No Vaccination Appointments has been Booked for this Center</div>";
}

 foreach($json_c['data'] as $v2){

                $id2 = $v2['3']['value'];
$appointment_id = $id2;
                $postid2 = $v2['6']['value'];
                $comment2 = $v2['7']['value'];
                $timing2 = $v2['8']['value'];
                $userid2 = $v2['9']['value'];
                $fullname2 = $v2['10']['value'];
                $photo2 = $v2['11']['value'];
                $status = $v2['12']['value'];
                

	
	

                

   // }





            ?>
                
                <div class="post alerts alert-warning comments_hovering" id="post_<?php echo $appointment_id; ?>">


<style>

.comments_hovering:hover{
background: pink;
color:black;


}


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


<img class='' style='border-style: solid; border-width:3px; border-color:#ec5574; width:60px;height:60px; 
max-width:60px;max-height:60px;border-radius: 50%;' src='<?php echo $photo2; ?>' alt='User Image'><br>
<b style='color:#8B008B;font-size:18px;' >Name: <?php echo $fullname2; ?> </b><br>

<b style='' >Covid19 Immunization Status: <span style='color:black;'>(<?php echo $status; ?>)<span> </b><br>
</div>


<div style='float:right;top:0px;right:0;margin-top:-60px;right:0px;'>
<button class='post_css1'>
<a title='Click to access users Profile page' style='color:black;' href='profile2.html?id=<?php echo $userid2; ?>'>
<span style='font-size:20px;color:#8B008B;' class='fa fa-user'></span> View Users Profile</a></button><br>

</div>






<b>Additional Message:</b> <?php echo $comment2; ?> &nbsp; &nbsp; &nbsp;

<br>
<span><b> <span style='color:#8B008B;' class='fa fa-calendar'></span>Time:</b>  <span data-livestamp="<?php echo $timing2;?>"></span></span>






                </div><p></p>

            <?php
            }
            ?>

<!--START Appointment result form-->

<div id="commentsubmissionResult_<?php echo $postid; ?>"></div>

<!--end Appointment result form-->


            <h1 class="loadAppointment  category_post" title='Load More Appointments!'> Load More Appointment</h1>


<?php
if($total_count_c < 5 || $total_count_c == 5){
?>
(<span class="no_of_row_loaded"><?php echo $total_count_c; ?></span> out of <span class="p"><?php echo $total_count_c; ?></span>)
 <?php } ?>

<?php
if($total_count_c > 5){
?>
(<span class="no_of_row_loaded">5</span> out of <span class="p"><?php echo $total_count_c; ?></span>)
 <?php } ?>

            <input type="hidden" id="postRow" value="0">
            <input type="hidden" id="pCounter" value="<?php echo $total_count_c; ?>">

        </div>



<!--START Appointment form-->

<div id="commentsubmissionResult_<?php echo $postid; ?>"></div>


<div class="col-sm-12 form-group">
<h3><center>Book Covid19 Vaccination Appointments</center></h3> 
<div class='row' style='background:#c1c1c1;'>


<div class="form-group col-sm-12">
              <label>FullName</label>
              <input type="text" class="myd_fname_sess_value col-sm-12 form-control" id="v_fullname" name="v_fullname" value="" placeholder="Enter Full Name">
            </div>


<div class="form-group col-sm-6">
              <label>Pick Appointment Date</label>
              <input type="date" class="col-sm-12 form-control" id="v_date" name="v_date" placeholder="Pick Appointment Date">
            </div>


<div class="form-group col-sm-6">
              <label>Enter Your Address</label>
              <input type="text" class="col-sm-12 form-control" id="v_address" name="v_address" placeholder="Enter Address">
            </div>

<div class="form-group col-sm-6">
              <label>Age</label>
              <input type="text" class="col-sm-12 form-control" id="v_age" name="v_age" placeholder="Enter Your Age">
            </div>


<div class="form-group col-sm-6">
              <label>Sex</label>
              <select class="col-sm-12 form-control" id="v_sex" name="v_sex" placeholder="Enter Your Gender">
<option value=''>--Select Gender/Sex--</option>

<option value='Male'>Male</option>
<option value='Female'>Female</option>

</select>
            </div>

<input type='hidden' name='v_cityname' id='v_cityname' value='<?php echo $vaccination_city; ?>'>
 <textarea  id="comdesc<?php echo $postID_call; ?>"  class="form-control" style="color:black;"  placeholder="Enter Additional Message"></textarea>

</div>

<div class='loader_appointments'></div>

<br>
 <input data-color='' data-color1='' data-pe='' data-title='<?php echo $title; ?>' data-titleseo='<?php echo $title_seo; ?>' type="button" value="Book Appointment Now" id="<?php echo $postID_call; ?>" class="appointment category_post2 pull-left" />


</div>
<br><br><p class='col-sm-12'></p>





<!--end Appointment form -->




<!--end Appointment -->






<?php


// update table notification_posts with Unread for read Updates starts

$notifyId = intval($_POST['notifyId']);
if($notifyId != ''){


$status_field = 12;

$url_update = "https://api.quickbase.com/v1/records";
$ch_update = curl_init();
curl_setopt($ch_update,CURLOPT_URL, $url_update);
$useragent_update ='Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1';

$post_updates='

{
  "to": "'.$table_notification_post.'",
  "data": [
    {


      "'.$status_field.'": {
        "value": "read"
      },

 "3": {
        "value": "'.$notifyId.'"
      }

    }
  ],

 "fieldsToReturn": [
3,
    6,
    8,
12,
14
  ]

}

';


curl_setopt($ch_update, CURLOPT_HTTPHEADER, array(
"QB-Realm-Hostname: $quickbase_domain",
"User-Agent: $useragent_update",
"Authorization: QB-USER-TOKEN $access_token",
'Content-Type:application/json'
));  

//curl_setopt($ch_update,CURLOPT_CUSTOMREQUEST,'GET');
curl_setopt($ch_update,CURLOPT_CUSTOMREQUEST,'POST');

//curl_setopt($ch_update,CURLOPT_CUSTOMREQUEST,'DELETE');
curl_setopt($ch_update,CURLOPT_POSTFIELDS, $post_updates);
curl_setopt($ch_update,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($ch_update,CURLOPT_SSL_VERIFYHOST,0);
curl_setopt($ch_update,CURLOPT_RETURNTRANSFER, true);
$response_update = curl_exec($ch_update);

curl_close($ch_update);

//print_r($response_update);
$json_update = json_decode($response_update, true);

$updated_rec_id = $json_update["data"][0]["3"]["value"];



}


// update table notification_posts with Unread for read Updates starts

?>











