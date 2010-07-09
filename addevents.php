<?php
//Database connection details
$host = "localhost";
$mysql_user = "dbusername";
$mysql_password = "dbpassword";
$mysql_db = "dbname";

//make connection with mysql and select the database
$mysql_connect = mysql_connect($host, $mysql_user, $mysql_password);
$db_select = mysql_select_db($mysql_db);

//will be used to show alert message for success or error
$alert = "";

//check if the form is submitted
if(isset($_POST['add']))
{
	//check for empty inputs
	if((isset($_POST['date']) && !empty($_POST['date'])) && (isset($_POST['eventTitle']) && !empty($_POST['eventTitle'])) && (isset($_POST['eventContent']) && !empty($_POST['eventContent'])))	
	{
		//add new event to the database
		$query = "INSERT INTO eventcal (`eventDate`,`eventTitle`,`eventContent`) VALUES('".$_POST['date']."','".addslashes($_POST['eventTitle'])."','".addslashes($_POST['eventContent'])."')";		
		$result = mysql_query($query);
		
		//check if the insertion is ok
		if($result)
			$alert = "New Event successfully added";
		else 
			$alert = "Something is wrong. Try Again.";
	}
	else 
	{
		//alert message for empty input
		$alert = "No empty input please";
	}
}
?>
<html>
<head>
<title>Add New Events</title>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.pack.js"></script>
<link rel="stylesheet" href="datepick/jquery.datepick.css" type="text/css" media="screen" charset="utf-8" />
<script type="text/javascript" src="datepick/jquery.datepick.pack.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	
	//configure the date format to match mysql date
	$('#date').datepick({dateFormat: 'yy-mm-dd'});
});
</script>
</head>
<body>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
	<table align="center">
		<tr>
			<td colspan="2">
				<h2>Add a New Event</h2>
			</td>
		</tr>
		<tr>
			<td>Date : </td>
			<td><input id="date" name="date" size="30"></td>
		</tr>
		<tr>
			<td>Event Title : </td>
			<td><input id="eventTitle" name="eventTitle" size="50"></td>
		</tr>
		<tr>
			<td>Event Details : </td>
			<td><textarea cols="40" rows="5" name="eventContent" id="eventContent"></textarea></td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" value="Add Event" name="add"></td>
		</tr>
	</table>
	</form>
<?php
//check if there is any alert message set
if(isset($alert) && !empty($alert))
{
	//message alert
	echo '<script type="text/javascript">alert("'.$alert.'");</script>';
}
?>
</body>
</html>