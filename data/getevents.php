<?php
ini_set("display_errors", 1);

$retval = array();

if(!isset($_REQUEST['selectDate'])) {
	$retval['status'] = 'FAILED';
	$retval['statusmsg'] = 'no select date is specified';
	echo json_encode($retval);
	exit;
}

// parse moth, year, day
$date = explode(" ", $_REQUEST['selectDate']);
$month_text = $date[0];
$year = $date[1];
$day = $date[2];

// translate month text to number 
$month = "";
switch($month_text) {
	case "January":
		$month = "01";
		break;
	case "Feburary":
		$month = "02";
		break;
	case "March":
		$month = "03";
		break;
	case "April":
	 	$month = "04";
	 	break;			
	case "May":
		$month = "05";
		break;
	case "June":
		$month = "06";
		break;
	case "July":
		$month = "07";
		break;
	case "August":
		$month = "08";
		break;
	case "September":
		$month = "09";
		break;
	case "October":
		$month = "10";
		break;
	case "Noverber":
		$month = "11";
		break;
	case "December":
		$month = "12";
		break;									 
}
$selectDate = $year . '-' . $month . '-' . $day;

$host = "localhost";
$mysql_user = "root";
$mysql_password = "hoana29!";
$mysql_db = "hikingCal";

//make connection with mysql and select the database
$mysql_connect = mysql_connect($host, $mysql_user, $mysql_password);
$db_select = mysql_select_db($mysql_db);

$query = "SELECT * FROM hikingcal WHERE hikingDate='" . mysql_real_escape_string($selectDate) ."'";
$result = mysql_query($query);


if(!$result) {
	$retval['status'] = 'FAILED';
	$retval['statusmsg']= 'Could not successfully run query from DB ' . mysql_error();
	echo json_encode($retval);
	exit;	
}

if(mysql_num_rows($result) == 0) {
	$retval['status'] = 'FAILED';
	$retval['statusmsg']= 'No rows found, nothing exist.';
	echo json_encode($retval);
	exit;		
}

$trail = array();
while($row = mysql_fetch_assoc($result)) {

		$trail['date'] = $row['hikingDate'];
		$trail['trail'] = $row['trail'];
		$trail['location'] = $row['location'];
		$trail['buddy'] = $row['buddy'];
}


$retval['status'] = 'OK';
$retval['statusmsg'] = 'OK';
$retval['trail'] = $trail;
echo json_encode($retval);

?>