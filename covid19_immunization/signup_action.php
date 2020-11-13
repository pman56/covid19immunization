<?php
error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


ini_set('max_execution_time', 300); //300 seconds = 5 minutes
// temporarly extend time limit
set_time_limit(300);


if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {

$file_content = strip_tags($_POST['file_fname']);
$username1 = strip_tags($_POST['username']);
$username = str_replace(' ', '', $username1);


$user_rank = strip_tags($_POST['user_rank']);
$password = strip_tags($_POST['password']);
$fullname = strip_tags($_POST['fullname']);
$email = strip_tags($_POST['email']);
$mt_id=rand(0000,9999);
$dt2=date("Y-m-d H:i:s");
$ipaddress = strip_tags($_SERVER['REMOTE_ADDR']);
$timer = time();

// honey pot spambots
$emailaddress_pot =$_POST['emailaddress_pot'];
if($emailaddress_pot !=''){
//spamboot detected.
//Redirect the user to google site

echo "<script>
window.setTimeout(function() {
    window.location.href = 'https://google.com';
}, 1000);
</script><br><br>";

exit();
}


if ($file_content == ''){
echo "<div class='alert alert-danger' id='alerts_reg'><font color=red>Files Upload is empty</font></div>";
exit();
}



if ($username == ''){
echo "<div class='alert alert-danger' id='alerts_reg'><font color=red>Username is empty</font></div>";
exit();
}


//ensure that userid is not less than 6 characters


if (strlen($username)<6) {
   echo "<div class='alert alert-danger' id='alerts_reg'><font color=red>Username cannot be less than 6 characters</font></div>";
exit();
}


if ($password == ''){
echo "<div class='alert alert-danger' id='alerts_reg'><font color=red>password is empty</font></div>";
exit();
}

if ($fullname == ''){
echo "<div class='alert alert-danger' id='alerts_reg'><font color=red>fullname Name is empty</font></div>";
exit();
}

if ($email == ''){
echo "<div class='alert alert-danger' id='alerts_reg'><font color=red>Email Address is empty</font></div>";
exit();
}

$em= filter_var($email, FILTER_VALIDATE_EMAIL);
if (!$em){
echo "<div class='alert alert-danger' id='alerts_reg'><font color=red>Email Address is Invalid</font></div>";
exit();
}

$ip= filter_var($ipaddress, FILTER_VALIDATE_IP);
if (!$ip){
echo "<div class='alert alert-danger' id='alerts_reg'><font color=red>IP Address is Invalid</font></div>";
exit();
}


if ($user_rank== ''){
echo "<div class='alert alert-danger' id='alerts_reg'><font color=red>Your Profession cannot be Empty</font></div>";
exit();
}




$filename_string = strip_tags($_FILES['file_content']['name']);

// thus check files extension names before major validations

$allowed_formats = array("PNG", "png", "gif", "GIF", "jpeg", "JPEG", "BMP", "bmp","JPG","jpg");
$exts = explode(".",$filename_string);
$ext = end($exts);

if (!in_array($ext, $allowed_formats)) { 
echo "<div id='alertdata_uploadfiles' class='alerts alert-danger'>File Formats not allowed. Only Images are allowed.<br></div>";
exit();
}



 //validate file names, ensures directory tranversal attack is not possible.
//thus replace and allowe filenames with alphanumeric dash and hy

//allow alphanumeric,underscore and dash

$fname_1= preg_replace("/[^\w-]/", "", $filename_string);

// add a new extension name to the uploaded files after stripping out its dots extension name
$new_extension = ".png";
$fname = $fname_1.$new_extension;





// for security reasons, you migh want to avoid files with more than one dot extension name
//file like fred.exe.png might contain virus. only ask the user to rename files to eg fred.png before uploads

$fname_dot_count = substr_count($fname,".");
if($fname_dot_count >1){
echo "<div id='alertdata_uploadfiles2' class='alerts alert-danger'>
Your files <b>$filename_string</b> has <b>($fname_dot_count dot extension names)</b>
File with more than one <b>dot(.) extension name are not allowed.
you can rename and ensure it has only one dot extension eg: <b>example.png</b>
</b></div>";
exit();

}


$fsize = $_FILES['file_content']['size']; 
$ftmp = $_FILES['file_content']['tmp_name'];

//give file a random names
$filecontent_name = $username.time();
//$filecontent_name = 'fred1';


if ($fsize > 5 * 1024 * 1024) { // allow file of less than 5 mb
echo "<div id='alertdata' class='alerts alert-danger'>File greater than 5mb not allowed<br></div>";
exit();
}



$allowed_types=array(
'application/json',
'application/octet-stream',
'text/plain',
'image/gif',
    'image/jpeg',
    'image/png',
'image/jpg',

'image/GIF',
    'image/JPEG',
    'image/PNG',
'image/JPG'
);



if ( ! ( in_array($_FILES["file_content"]["type"], $allowed_types) ) ) {
  echo "<div id='alertdata_uploadfiles' class='alerts alert-danger'>Only Images are allowed bro..<br><br></div>";
exit();
}



// Calling getimagesize() function 
//$image_info = getimagesize("team1.png"); 
//print_r($image_info); 

$image_info =getimagesize($_FILES['file_content']['tmp_name']);

    $width = $image_info[0];
    $height = $image_info[1];
    $mime_image = $image_info['mime'];



// check file validation using getimagesizes
 if ($image_info === FALSE) {
           echo "<div id='alertdata_uploadfiles' class='alerts alert-danger'>cannot determine the image type</div>";
exit();
        }



if ( ! ( in_array($mime_image, $allowed_types) ) ) {
  echo "<div id='alertdata_uploadfiles' class='alerts alert-danger'>Only Image types are allowed..<br><br></div>";
exit();
}



if (($image_info[2] !== IMAGETYPE_GIF) && ($image_info[2] !== IMAGETYPE_JPEG) && ($image_info[2] !== IMAGETYPE_PNG)) {
           echo "<div id='alertdata_uploadfiles' class='alerts alert-danger'>only image format gif,jpg, png are allowed..</div>";
exit();
        }





//validate image using file info  method
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $_FILES['file_content']['tmp_name']);

if ( ! ( in_array($mime, $allowed_types) ) ) {
  echo "<div id='alertdata_uploadfiles' class='alerts alert-danger'>Only Images are allowed...<br></div>";
exit();
}
finfo_close($finfo);





//include quickbase token
include('quickbase_token.php');
include('quickbase_tables.php');

//$table_users
//$access_token
//$quickbase_domain


// check if username already exist in users table in quickbase.

$url = "https://api.quickbase.com/v1/records/query";
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $url);
$useragent ='Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1';


$username_field= 6;


$post ='{
  "from": "'.$table_users.'",
  "select": [
3,
    6
  ],

  "where": "{'.$username_field.'.CT.'.$username.'}"

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




if($json == ''){
//if($num_rec == ''){
echo "
<div style='background:red;color:white;padding:10px;'>No Network. Ensure there is Internet Connection and Try Again</div>";
exit();
}


        if($num_rec > 0){

// echo "Username exist.";
echo "<br><div class='alert alert-danger'  id='alertdata_uploadfiles'><b><font color=red><b></b>This username is already taken</font></b><br>";
exit();



}else{
 //echo "username is available";

}





// check if Email Address already exist in users table in quickbase.

$url1 = "https://api.quickbase.com/v1/records/query";
$ch1 = curl_init();
curl_setopt($ch1,CURLOPT_URL, $url1);
$useragent1 ='Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1';


$email_field= 9;


$post1 ='{
  "from": "'.$table_users.'",
  "select": [
3,
    9
  ],

  "where": "{'.$email_field.'.CT.'.$email.'}"

}
';


curl_setopt($ch1, CURLOPT_HTTPHEADER, array(
"QB-Realm-Hostname: $quickbase_domain",
"User-Agent: $useragent1",
"Authorization: QB-USER-TOKEN $access_token",
'Content-Type:application/json'
));  

//curl_setopt($ch1,CURLOPT_CUSTOMREQUEST,'GET');
curl_setopt($ch1,CURLOPT_CUSTOMREQUEST,'POST');

//curl_setopt($ch1,CURLOPT_CUSTOMREQUEST,'DELETE');
curl_setopt($ch1,CURLOPT_POSTFIELDS, $post1);
curl_setopt($ch1,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($ch1,CURLOPT_SSL_VERIFYHOST,0);
curl_setopt($ch1,CURLOPT_RETURNTRANSFER, true);
$response1 = curl_exec($ch1);

curl_close($ch1);

//print_r($response1);
$json = json_decode($response1, true);

$num_field1 = $json["metadata"]["numFields"];
$num_rec1 = $json["metadata"]["numRecords"];

        if($num_rec1 > 0){

// echo "email exist.";
echo "<br><div class='alert alert-danger'  id='alertdata_uploadfiles'><b><font color=red><b></b>This Email is already taken</font></b><br>";
exit();

}else{
// echo "Email is available";

}






//hash password before sending it to Quickbase...
$options = array("cost"=>4);
$hashpass = password_hash($password,PASSWORD_BCRYPT,$options);



//upload and convert image to base64 as required by Quickbase
move_uploaded_file($_FILES['file_content']['tmp_name'], $_FILES["file_content"]["name"]);
$img_file = file_get_contents($_FILES["file_content"]["name"]);
$img_encode = base64_encode($img_file);

$fname = $_FILES["file_content"]["name"];

// display image
//$photo_decode = 'data: '.mime_content_type($img_file).';base64,'.$img_encode;
//echo '<img src="'.$photo_decode.'">';



// Make Quickbase XML API Call to Upload Files and other Form parameters to Users Table at Quickbase

//$auth_ticket
//$udata_from_ticket;
//$app_token;

$users_table_db='bqyvt42rp';

$url4="https://hackathon20-ngwacham.quickbase.com/db/$users_table_db";
$ch4 = curl_init();
curl_setopt($ch4,CURLOPT_URL, $url4);
$useragent4 ='Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1';


$post_add4="
<qdbapi>
<ticket>$auth_ticket</ticket>
<apptoken>$app_token</apptoken>
<udata>$udata_from_ticket</udata>
<field name='username'>$username</field>
<field name='password'>$hashpass</field>
<field name='fullname'>$fullname</field>
<field name='email'>$email</field>
<field name='photourl'>png</field>
<field name='timing'>$timer</field>
<field name='user_rank'>$user_rank</field>
<field name='typingDNA_Verified'>0</field>


<field fid='13' filename='$fname'>$img_encode</field>
</qdbapi>
";

$clength = strlen($post_add4);


curl_setopt($ch4, CURLOPT_HTTPHEADER, array(
"Content-Length: $clength",
"QUICKBASE-ACTION: API_AddRecord",
'Content-Type:application/xml'
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
//$json4 = json_decode($response4, true);
//$statement4= $json4["metadata"]["totalNumberOfRecordsProcessed"];

$xml_string = $response4;
$xml = simplexml_load_string($xml_string);

$error_code =$xml->errcode;
$error_text = $xml->errtext;
$update_id =$xml->update_id; 
$udata = $xml->udata; 
$rid = $xml->rid; 
$image_field =13;



// construct the uploaded file url so that it can be accessible
//Quickbase Sample 
//https://target_domain.quickbase.com/up/DBID/a/rRID/eFID/vVID
//https://hackathon20-fesedo.quickbase.com/up/bqxfc48m5/a/r21/e10/v0


$final_url_file ="$target_domain_url/up/$table_users/a/r$rid/e$image_field/v0";

if($error_code == 0){
// echo no error hence success data uploads
//Query the above inserted record via users email to get ID to update photo url



$url1 = "https://api.quickbase.com/v1/records/query";
$ch1 = curl_init();
curl_setopt($ch1,CURLOPT_URL, $url1);
$useragent1 ='Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1';


$email_field= 9;


$post1 ='{
  "from": "'.$table_users.'",
  "select": [
3,
9
  ],

  "where": "{'.$email_field.'.CT.'.$email.'}"

}
';


curl_setopt($ch1, CURLOPT_HTTPHEADER, array(
"QB-Realm-Hostname: $quickbase_domain",
"User-Agent: $useragent1",
"Authorization: QB-USER-TOKEN $access_token",
'Content-Type:application/json'
));  

//curl_setopt($ch1,CURLOPT_CUSTOMREQUEST,'GET');
curl_setopt($ch1,CURLOPT_CUSTOMREQUEST,'POST');

//curl_setopt($ch1,CURLOPT_CUSTOMREQUEST,'DELETE');
curl_setopt($ch1,CURLOPT_POSTFIELDS, $post1);
curl_setopt($ch1,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($ch1,CURLOPT_SSL_VERIFYHOST,0);
curl_setopt($ch1,CURLOPT_RETURNTRANSFER, true);
$response1 = curl_exec($ch1);

curl_close($ch1);

//print_r($response1);
$json = json_decode($response1, true);

$num_field1 = $json["metadata"]["numFields"];
$num_rec1 = $json["metadata"]["numRecords"];

// get ID for users table
$userid_to_be_updated  = $json['data'][0]['3']['value'];





// now perform updates to send configured photourl

$users_photourl_field =10;
// update table users with the Points starts here


$url_update = "https://api.quickbase.com/v1/records";
$ch_update = curl_init();
curl_setopt($ch_update,CURLOPT_URL, $url_update);
$useragent_update ='Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1';

$post_updates='

{
  "to": "'.$table_users.'",
  "data": [
    {


      "'.$users_photourl_field.'": {
        "value": "'.$final_url_file.'"
      },

 "3": {
        "value": "'.$userid_to_be_updated.'"
      }

    }
  ],

 "fieldsToReturn": [
3,
    6,
    7,
    8,
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

// initialize session if things where ok via html5 local storage for TypingDNA Enrollments.
echo "<script>
localStorage.setItem('secfullname', '$fullname');
localStorage.setItem('secuserid', '$username');
localStorage.setItem('secemail', '$email');
</script>";


//echo "success";

echo "<div id='alertdata_uploadfiles_o' class='well alerts alert-success'>Data Created Successfully.
.Redirecting in a second to Login Section.....<i class='fa fa-spinner fa-spin' style='font-size:20px'></i> <br></div>";

echo "<script>
window.setTimeout(function() {
    window.location.href = 'security_enrollment.html';
//window.location.href = 'security_enrollment.html?username=$username&userid=$userid_to_be_updated';
}, 1000);
</script><br><br>";



}else{
echo "<div id='alertdata_uploadfiles' class='alerts alert-danger'>Your Data cannot be submitted to Quickbase.<br></div>";
}




}



?>



