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
19,
20,
21



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


$post_type= $v1['18']['value'];

$vaccine_name= $v1['20']['value'];

 $total_comments =$v1['21']['value'];

                

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



$(document).ready(function(){


    $('.loadComment').click(function(){

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
                url: 'comment_loadmoreData.php',
                type: 'post',
                data: {postRow:postRow, pid:pid},
                beforeSend:function(){
                    //$(".loadComment").text("Loading More Comments...");
$(".loadComment").html("<span class='loader_comment'></span> Loading More Comments...");
                    $('.loader_comment').fadeIn(400).html('<span><i class="fa fa-spinner fa-spin" style="font-size:24px"></i></span>');

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
                            $('.loadComment').text("No More Content to Load");
                              $('.loader_comment').hide();
                        }else{
                            $(".loadComment").text("Load more");
                           $('.loader_comment').hide();
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



<b class='title_css'>Reports Title/Name:   <?php echo $title; ?></b><br><br>

<b>Vaccine Name:</b> <?php echo $vaccine_name; ?> &nbsp; &nbsp; &nbsp;<br>
<b >Descriptions:</b><br><?php echo $post_shortened; ?><br>

<?php } ?>



<br><br>
<span><b> <span style='color:#8B008B;' class='fa fa-calendar'></span>Time:</b>  <span data-livestamp="<?php echo $timing;?>"></span></span>



                        <div class="pc2">



&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<span style="font-size:26px;color:#8B008B;" class="fa fa-comments"></span> 
&nbsp;<span id="<?php echo $postid; ?>" style="cursor:pointer;" title="Comments" /><a title='Comments' style='color:black' href='next_report.html?title=<?php echo $title_seo; ?>&pid=<?php echo $postid; ?>&uid=<?php echo $userid; ?>&tit=<?php echo $title; ?>'>Comments</a></span>
(<span id="comment_<?php echo $postid; ?>"><?php echo $total_comments; ?></span>)


<br>
<br>
</div>




                </div>

            <?php
            }
            ?>







<!--start comments -->





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
// query commnts record

$post_c ='{
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
echo "<div style='background:red;color:white;padding:10px;'>No Comments has been Posted Yet here</div>";
}

 foreach($json_c['data'] as $v2){

                $id2 = $v2['3']['value'];
$comment_id = $id2;
                $postid2 = $v2['6']['value'];
                $comment2 = $v2['7']['value'];
                $timing2 = $v2['8']['value'];
                $userid2 = $v2['9']['value'];
                $fullname2 = $v2['10']['value'];
                $photo2 = $v2['11']['value'];
                

	
	

                

   // }





            ?>
                
                <div class="post alerts alert-warning comments_hovering" id="post_<?php echo $comment_id; ?>">


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
<b style='color:#8B008B;font-size:18px;' >Name: <?php echo $fullname2; ?> </b><br><br>

</div>


<div style='float:right;top:0px;right:0;margin-top:-60px;right:0px;'>
<button class='post_css1'>
<a title='Click to access users Profile page' style='color:black;' href='profile2.html?id=<?php echo $userid2; ?>'>
<span style='font-size:20px;color:#8B008B;' class='fa fa-user'></span> View Users Profile</a></button><br>

</div>






<b>Comments:</b> <?php echo $comment2; ?> &nbsp; &nbsp; &nbsp;

<br>
<span><b> <span style='color:#8B008B;' class='fa fa-calendar'></span>Time:</b>  <span data-livestamp="<?php echo $timing2;?>"></span></span>






                </div><p></p>

            <?php
            }
            ?>

<!--START comment result form-->

<div id="commentsubmissionResult_<?php echo $postid; ?>"></div>

<!--end comment result form-->


            <h1 class="loadComment  category_post" title='Load More Comments!'> Load More Comments</h1>


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



<!--START comment form-->

<div id="commentsubmissionResult_<?php echo $postid; ?>"></div>


<div class="col-sm-12 form-group">
 <textarea  id="comdesc<?php echo $postID_call; ?>"  class="form-control" style="color:black;"  placeholder="Enter Comments"></textarea>
<div class='loader_comments'></div>

<br>
 <input data-color='' data-color1='' data-pe='' data-title='<?php echo $title; ?>' data-titleseo='<?php echo $title_seo; ?>' type="button" value="comment Now" id="<?php echo $postID_call; ?>" class="comment category_post2 pull-left" />


</div>
<br><br><p class='col-sm-12'></p>





<!--end comment form -->




<!--end comments -->






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











