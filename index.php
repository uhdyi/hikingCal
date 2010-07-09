<?php


//Database connection details
$host = "localhost";
$mysql_user = "root";
$mysql_password = "hoana29!";
$mysql_db = "hikingCal";

//make connection with mysql and select the database
$mysql_connect = mysql_connect($host, $mysql_user, $mysql_password);
$db_select = mysql_select_db($mysql_db);

//check if time is set in the URL
if(isset($_GET['time']))
	$time = $_GET['time'];
else
	$time = time();
	
$today = date("Y/n/j", time());

$current_month = date("n", $time);

$current_year = date("Y", $time);

$current_month_text = date("F Y", $time);

$total_days_of_current_month = date("t", $time);

$hikes = array();

//query the database for events between the first date of the month and the last of date of month
$result = mysql_query("SELECT DATE_FORMAT(hikingDate,'%d') AS day,trail,location, buddy FROM hikingcal WHERE hikingDate BETWEEN  '$current_year/$current_month/01' AND '$current_year/$current_month/$total_days_of_current_month'");

while($row_hike = mysql_fetch_object($result))
{
	//loading the $events array with evenTitle and eventContent inside the <span> and <li>. We will add then inside <ul> in the calender
	$hikes[intval($row_hike->day)] .= '<li><span class="title">'.stripslashes($row_hike->trail).'</span><span class="desc">'.stripslashes($row_hike->location).'</span></li>';
}	

$first_day_of_month = mktime(0,0,0,$current_month,1,$current_year);

//geting Numeric representation of the day of the week for first day of the month. 0 (for Sunday) through 6 (for Saturday).
$first_w_of_month = date("w", $first_day_of_month);

//how many rows will be in the calendar to show the dates
$total_rows = ceil(($total_days_of_current_month + $first_w_of_month)/7);

//trick to show empty cell in the first row if the month doesn't start from Sunday
$day = -$first_w_of_month;


$next_month = mktime(0,0,0,$current_month+1,1,$current_year);
$next_month_text = date("F \'y", $next_month);

$previous_month = mktime(0,0,0,$current_month-1,1,$current_year);
$previous_month_text = date("F \'y", $previous_month);

$next_year = mktime(0,0,0,$current_month,1,$current_year+1);
$next_year_text = date("F \'y", $next_year);

$previous_year = mktime(0,0,0,$current_month,1,$current_year-1);
$previous_year_text = date("F \'y", $previous_year);

?>

<html>
<head>
<title><?php echo $current_month_text; ?></title>
<link rel="stylesheet" href="css/master.css" type="text/css" media="screen" charset="utf-8" />
<link rel="stylesheet" href="datepick/jquery.datepick.css" type="text/css" media="screen" charset="utf-8" />
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.pack.js"></script>
<script src="js/coda.js" type="text/javascript"> </script>
<script type="text/javascript" src="datepick/jquery.datepick.pack.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	
	//configure the date format to match mysql date
	$('#date').datepick({dateFormat: 'yy-mm-dd'});
});
</script>
</head>
<body>
	<div class="header">

	</div>
	<div class="content">
	<div class="left-column">
		<h2><div id="current_month"><?php echo $current_month_text; ?></div></h2>
	<table id="calendar" cellspacing="0">
		<thead>
		<tr>
			<th>Sun</th>
			<th>Mon</th>
			<th>Tue</th>
			<th>Wed</th>
			<th>Thu</th>
			<th>Fri</th>
			<th>Sat</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<?php
			for($i=0; $i< $total_rows; $i++)
			{
				for($j=0; $j<7;$j++)
				{
					$day++;					
					
					if($day>0 && $day<=$total_days_of_current_month)
					{
						//YYYY-MM-DD date format
						$date_form = "$current_year/$current_month/$day";
						
						echo '<td';
						
						//check if the date is today
						if($date_form == $today)
						{
							echo ' class="today"';
						}
						
						//check if any event stored for the date
						if(array_key_exists($day,$hikes))
						{
							//adding the date_has_event class to the <td> and close it
							echo ' class="date_has_event"> '.$day;
							
							//adding the eventTitle and eventContent wrapped inside <span> & <li> to <ul>
							//echo '<div class="events"><ul>'.$hikes[$day].'</ul></div>';
							//echo '<div class="events"><ul>wiliwilinui</ul></div>';
						}
						else 
						{
							//if there is not event on that date then just close the <td> tag
							echo '> '.$day;
						}
						
						echo "</td>";
					}
					else 
					{
						//showing empty cells in the first and last row
						echo '<td class="padding">&nbsp;</td>';
					}
				}
				echo "</tr><tr>";
			}
			
			?>
		</tr>
	</tbody>
		<tfoot>		
			<th>
				<a href="<?php echo $_SERVER['PHP_SELF']; ?>?time=<?php echo $previous_year; ?>" title="<?php echo $previous_year_text; ?>">&laquo;&laquo;</a>
			</th>
			<th>
				<a href="<?php echo $_SERVER['PHP_SELF']; ?>?time=<?php echo $previous_month; ?>" title="<?php echo $previous_month_text; ?>">&laquo;</a>
			</th>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			<th>
				<a href="<?php echo $_SERVER['PHP_SELF']; ?>?time=<?php echo $next_month; ?>" title="<?php echo $next_month_text; ?>">&raquo;</a>
			</th>
			<th>
				<a href="<?php echo $_SERVER['PHP_SELF']; ?>?time=<?php echo $next_year; ?>" title="<?php echo $next_year_text?>">&raquo;&raquo;</a>
			</th>		
		</tfoot>
	</table>
</div>
<!-- end of left-column -->

<div class="right-column">
	 <h1>Trail</h1>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
	  <div>
	  	<label for="trailname">Name</label><input type="text" id="trailname" name="trailname" size="30" />
	  </div>
	  <div>
	  	<label for="hikingDate">Date</label><input id="hikingDate" name="hikingDate" size="20" />
	  </div>
	  <div>
	  	<label for="location">Location</label><input id="location" name="location" size="30" />
	  </div>
	  <div>
	  	<label for="buddy">Buddy</label><input id="buddy" name="buddy" size="30" />
	  </div>
	  <div>
		<button type="button" id="add">Add</button>
		<button type="button" id="remove">Remove</button>	  	
		<button type="button" id="update">Update</button>	  
	  </div>
	</form>
</div>
<!-- end of right-column -->
</div>
<!-- end of content -->
<div class="footer"></div>
</body>
</html>