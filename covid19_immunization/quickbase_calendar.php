<script src="jquery.min.js"></script>
<script src="moment.js"></script>
<script src="livestamp.js"></script>
<style>
.tooltip1 {
    position: relative;
    display: inline-block;
    border-bottom: 1px dotted black;
}

.tooltip1 .tooltiptext {
    visibility: hidden;
//height:120px;
    width: 300px;

    background-color: black;
    color: #fff;
    text-align: center;
    border-radius: 6px;
    padding: 5px 0;
font-size:12px;
    
    /* Position the tooltip */
    position: absolute;
    z-index: 1;
    bottom: 100%;
    left: 50%;
    margin-left: -60px;
}

.tooltip1:hover .tooltiptext {
    visibility: visible;
}
</style>

<?php
error_reporting(0);
//include quickbase token
include('quickbase_token.php');
include('quickbase_tables.php');



$mymonth_field= 12;
$monthing = date('m');

$tbb='bqyzda7xy';

$url = "https://api.quickbase.com/v1/records/query";
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $url);
$useragent ='Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1';
// query Posts record

$post ='{
  "from": "'.$tbb.'",
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

 "where": "{'.$mymonth_field.'.CT.'.$monthing.'}"
 
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

$data = $response;
curl_close($ch);

//print_r($response);
$json = json_decode($response, true);


$total_count = $json["metadata"]["totalRecords"];
//$id_for_updates  = $json['data'][0]['3']['value'];


 $uid = 'fredo';
//$month = 11;

$month = date('m');
$year = date('y');
if(!empty($_GET['month'])) $month = $_GET['month'];
if(!empty($_GET['year'])) $year = $_GET['year'];
if(!empty($_GET['uid']))  $uid = strip_tags($_GET['uid']);


 //$uid = strip_tags($_GET['uid']);
	$calendar = '';
	if($month == null || $year == null) {
		$month = date('m');
		$year = date('Y');
	}
	$date = mktime(12, 0, 0, $month, 1, $year);
	$daysInMonth = date("t", $date);
	$offset = date("w", $date);
	$rows = 1;
	$prev_month = $month - 1;
	$prev_year = $year;
	if ($month == 1) {
		$prev_month = 12;
		$prev_year = $year-1;
	}
	
	$next_month = $month + 1;
	$next_year = $year;
	if ($month == 12) {
		$next_month = 1;
		$next_year = $year + 1;
	}



	$calendar .= "<div class='panel-heading text-center'><div class='row'><div class='col-md-3 col-xs-4'><a class='ajax-navigation1 btn btn-default btn-sm' href='calendar_depature.php?month=".$prev_month."&year=".$prev_year."&uid=".$uid."'><span class='glyphicon glyphicon-arrow-left'></span></a></div><div class='col-md-6 col-xs-4'><strong>" . date("F Y", $date) . "</strong></div>";
	$calendar .= "<div class='col-md-3 col-xs-4 '><a class='ajax-navigation1 btn btn-default btn-sm' href='calendar_depature.php?month=".$next_month."&year=".$next_year."&uid=".$uid."'><span class='glyphicon glyphicon-arrow-right'></span></a></div></div></div>"; 

        $calendar .= "<table class='table table-bordered'>";
	$calendar .= "<tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr>";
	$calendar .= "<tr>";

	for($i = 1; $i <= $offset; $i++) {
		$calendar .= "<td></td>";
	}
	for($day = 1; $day <= $daysInMonth; $day++) {
		if( ($day + $offset - 1) % 7 == 0 && $day != 1) {
			$calendar .= "</tr><tr>";
			$rows++;
		}



		$mycalendar_res = '';
if(!empty($data)) {
 foreach($json['data'] as $key => $c_row){
$day_convert = strtotime($c_row['9']['value']);


//$day_convert = strtotime($c_row['tm']);
$c_day = date("d", $day_convert);



	if($c_day == $day) {

$mycalendar_res .= '
<div class="tooltip1 img-responsive">
<img src="'.$c_row['11']['value'].'" class="img-thumbnail img-circle" width="30px" height="30px"/>
<div class="tooltiptext">
<span  style="color:pink;font-size:18px"><b>Genetics:</b> '.$c_row['7']['value'].' </span><br>
<img src="'.$c_row['11']['value'].'" class="img-thumbnail img-circle" width="50px" height="50px"/><br>
<span ><b>Username:</b> '.$birthday['6']['value'].'</span><br>
<span  ><b style="color:orange;font-size:18px">Events Note:</b> '.$c_row['8']['value'].' </span><br>

<span style="color:pink;font-size:16px"><b>created:</b> <span  data-livestamp="'.$c_row['10']['value'].'" ></span> </span><br>
<a style="display:none" class="btn btn-info btn-sm" href="profile.php?id='.$c_row['3']['value'].'">Visit Profile to <br>Send Message</a>
</div> 
</div></a>';


}
			}
		}
 		$calendar .= "<td>" . $day . "<br>".$mycalendar_res."</td>";
	}
	while( ($day + $offset) <= $rows * 7)
	{
		$calendar .= "<td></td>";
		$day++;
	}
	$calendar .= "</tr>";
	$calendar .= "</table><hr>";
	echo $calendar;
?>






