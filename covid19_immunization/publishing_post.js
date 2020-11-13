$(document).ready(function(){


//appointment starts

$(".appointment").click(function(){


var postid = this.id; 
var type = 1;
var appointment_type=1;


var userid_sess_data = localStorage.getItem('useridsessdata2');
//var fullname_sess_data = localStorage.getItem('fullnamesessdata2');
var photo_sess_data = localStorage.getItem('photosessdata2');

var comdesc = $('#comdesc'+postid).val();
var fullname_sess_data = $('#v_fullname').val();
var v_date = $('#v_date').val();
var v_address = $('#v_address').val();
var v_age = $('#v_age').val();
var v_sex = $('#v_sex').val();
var v_cityname =  $('#v_cityname').val();



if(fullname_sess_data == ''){
alert('Fullname cannot be empty');
return false;
}

if(comdesc == ''){
alert('Additional comment cannot be empty');
return false;
}

if(v_date == ''){
alert('Appointment Date cannot be empty');
return false;
}
if(v_address == ''){
alert('Your Address cannot be empty');
return false;
}
if(v_age == ''){
alert('Age cannot be empty');
return false;
}
if(v_sex == ''){
alert('Your Gender/Sex cannot be empty');
return false;
}

 //dataType: 'html',


$(".loader_appointments").fadeIn(400).html('<br><div style="color:black;background:white;padding:10px;"><i class="fa fa-spinner fa-spin" style="font-size:20px"></i>&nbsp;Please Wait, Your Appointments is being Published...</div>');

        // AJAX Request
        $.ajax({
            url: 'appointment.php',
            type: 'post',
           
            data: {postid:postid,type:type,appointment_type:appointment_type,comdesc:comdesc, 'userid_sess_data':userid_sess_data, 'fullname_sess_data':fullname_sess_data, 'photo_sess_data':photo_sess_data, 'v_date':v_date, 'v_address':v_address, 'v_age':v_age, 'v_sex':v_sex, 'v_cityname':v_cityname},          
            crossDomain: true,
	    cache:false,
            success: function(data){

                var appointment = data['appointment'];

if(data.trim() ==1){

alert('Appointment Success');
location.reload();
}
                var comdesc = data['comdesc'];
                var appointment_username = data['appointment_username'];
                 var appointment_fullname = data['appointment_fullname'];
var appointment_photo = data['appointment_photo'];
var appointment_time = data['appointment_time'];
var appointment_userid = data['userid'];
var appointment_id = data['appointment_id'];


              $("#appointment_"+postid).text(appointment);    
              

  var appointment_json = "<div class='alerts alert-warning comments_hovering'>" +

"<div class=''>"+   
"<img style='border-style: solid; border-width:3px; border-color:#ec5574; width:60px;height:60px; max-width:60px;max-height:60px;border-radius: 50%;' src=" + appointment_photo +" /><br>" +
              
 //"<img style='border-style: solid; border-width:3px; border-color:#ec5574; width:60px;height:60px; max-width:60px;max-height:60px;border-radius: 50%;' src=" + appointment_photo +" /><br>" +
"<b style='color:#8B008B;font-size:18px;' >Name:" + appointment_fullname + "</b><br><br></div>"+

"<div style='float:right;top:0px;right:0;right:0px;margin-top:-90px;'><button class='post_css1'>"+
"<a title='Click to access users Profile page' style='color:black;' href='profile2.html?id=" + appointment_userid +"' title='Click to access users page'>"+
"<span style='font-size:20px;color:#8B008B;' class='fa fa-user'></span> View Your Profile </a></button><br>"+
//"<div style='display:none;' class='loader-request_'+postid></div><div class='result-request_'+postid></div>"+
"</div>"+

"<b style='font-size:12px;text-align:left;'>Covid19 Immunization Status: </b><b style='color:red;font-size:16px;font-family:comic sans ms'>" + Not-Immunized-Yet + "</b><br>" +
"<b style='font-size:12px;text-align:left;'>Additional Message: </b>" + comdesc + "<br>" +
"<span><b> <span class='fa fa-calendar'></span>Time:</b></span>" +
"<span data-livestamp='" + appointment_time + "'></span></span>"+



                    "</div><p></p>";

$("#commentsubmissionResult_"+postid).append(appointment_json);

$(".loader_appointments").hide();

            //$('#comdesc').val('');
$('#comdesc'+postid).val('');

alert('Appointments success');

            }
        });

    });


//appointments ends








//comment

$(".comment").click(function(){

var postid = this.id; 
var type = 1;
var comment_type=1;


var userid_sess_data = localStorage.getItem('useridsessdata2');
var fullname_sess_data = localStorage.getItem('fullnamesessdata2');
var photo_sess_data = localStorage.getItem('photosessdata2');

//alert(userid_sess_data);

//var comdesc = $('#comdesc'+postid).val();
var comdesc = $('#comdesc'+postid).val();
//var userid = $('#uid').val();
//var title = $(this).data('title');
//var title_seo = $(this).data('titleseo'); 


if(comdesc == ''){
alert('comment cannot be empty');
return false;
}
$(".loader_comments").fadeIn(400).html('<br><div style="color:black;background:white;padding:10px;"><i class="fa fa-spinner fa-spin" style="font-size:20px"></i>&nbsp;Please Wait, Your Comments is being Published...</div>');

        // AJAX Request
        $.ajax({
            url: 'comment.php',
            type: 'post',
            dataType: 'json',
            data: {postid:postid,type:type,comment_type:comment_type,comdesc:comdesc, 'userid_sess_data':userid_sess_data, 'fullname_sess_data':fullname_sess_data, 'photo_sess_data':photo_sess_data},          
            crossDomain: true,
	    cache:false,
            success: function(data){

                var comment = data['comment'];
                var comdesc = data['comdesc'];
                var comment_username = data['comment_username'];
                 var comment_fullname = data['comment_fullname'];
var comment_photo = data['comment_photo'];
var comment_time = data['comment_time'];
var comment_userid = data['userid'];
var comment_id = data['comment_id'];


//alert(comment_photo);
              $("#comment_"+postid).text(comment);    // setting comment
               //$("#comdesc_"+postid).prepend(comdesc);    // setting comment
              // $("#commentusername_"+postid).prepend(comment_username);    // setting comment


  var comment_json = "<div class='alerts alert-warning comments_hovering'>" +

"<div class=''>"+   
"<img style='border-style: solid; border-width:3px; border-color:#ec5574; width:60px;height:60px; max-width:60px;max-height:60px;border-radius: 50%;' src=" + comment_photo +" /><br>" +
              
 //"<img style='border-style: solid; border-width:3px; border-color:#ec5574; width:60px;height:60px; max-width:60px;max-height:60px;border-radius: 50%;' src=" + comment_photo +" /><br>" +
"<b style='color:$color6;font-size:18px;' >Name:" + comment_fullname + "</b><br><br></div>"+

"<div style='float:right;top:0px;right:0;right:0px;margin-top:-90px;'><button class='post_css1'>"+
"<a title='Click to access users Profile page' style='color:black;' href='profile2.html?id=" + comment_userid +"' title='Click to access users page'>"+
"<span style='font-size:20px;color:#8B008B;' class='fa fa-user'></span> View Your Profile </a></button><br>"+
//"<div style='display:none;' class='loader-request_'+postid></div><div class='result-request_'+postid></div>"+
"</div>"+
"<b style='font-size:12px;text-align:left;'>Comment: </b>" + comdesc + "<br>" +
"<span><b> <span class='fa fa-calendar'></span>Time:</b></span>" +
"<span data-livestamp='" + comment_time + "'></span></span>"+



                    "</div><p></p>";

                //$("#comdesc_"+postid).append(comment_json);

$("#commentsubmissionResult_"+postid).append(comment_json);

$(".loader_comments").hide();

            //$('#comdesc').val('');
$('#comdesc'+postid).val('');

alert('comments success');

            }
        });

    });

// comments ends







});