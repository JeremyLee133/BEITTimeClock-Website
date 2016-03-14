<html>
<head>
	<title>Cohort Student List</title>
	<link rel="stylesheet" type="text/css" href="./css/screen.css" media="screen">
	<link rel="stylesheet" type="text/css" href="./css/print.css" media="print">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<?php
if (isset($_REQUEST["cohortNumber"])){
	include("header.html");
}
echo "<div class='screen'>";
if (!isset($_REQUEST["cohortNumber"])){
	echo "Please Select a Cohort to View The Class List";
	echo "</br>";
	echo "</br>";
}

echo "<form id='cohortStudentList' action='get_cohort_student_list.php' method='post'>";
echo "<select id='cohortNumber' name='cohortNumber'>";

echo "<option value='4'>Cohort 4 - Carpentry</option>";
echo "<option value='5'>Cohort 5 - Precision Machining</option>";
echo "<option value='6'>Cohort 6 - Mechatronics</option>";
echo "<option value='7'>Cohort 7 - Welding</option>";

echo "</select>";
echo "<input type='submit' value='Submit'>";
echo "</form>";

date_default_timezone_set('America/New_York');

include("dbcon.php");

if (isset($_REQUEST["cohortNumber"])){

	$cohortNum = $_REQUEST["cohortNumber"];
	$result = mysqli_query($con,"SELECT studentID FROM cohortcurrent WHERE cohortNumber = $cohortNum ORDER BY in_out ASC");
	$studentIDs = array();
	
	while($row = mysqli_fetch_array($result)){
		$studentIDs[] = $row['studentID'];
	}
	
	switch ($cohortNum){
		case 4:
			$cohortNumName = "4 - Carpentry";
			break;
		case 5:
			$cohortNumName = "5 - Precision Machining";
			break;
		case 6:
			$cohortNumName = "6 - Mechatronics";
			break;	
		case 7:
			$cohortNumName = "7 - Welding";
			break;		
		default:
			$cohortNumName = $cohortNum;
	}
	
	echo '<a href="manual_clock_in_out.php?cohortNumber=' . $cohortNum . '">Clock a student in or out</a>';
	echo "</br>";
	
	echo "<b>Cohort: ", $cohortNumName, "</b>";
	echo "<table border='1'>
		 <thead>
		 <tr>
		 <th>Name</th>
		 <th>Time</th>
		 <th>In or Out</th>
		 <th>Room Number</th>
		 </tr>
		 </thead>";
	
	for ($i=0; $i < count($studentIDs); ++$i){
		$maxTime = mysqli_query($con, "SELECT * FROM cohortlog WHERE studentID ='".$studentIDs[$i]."' ORDER BY time DESC LIMIT 1");
		while($row1 = mysqli_fetch_array($maxTime)){
			echo "<tr>";
			echo '<td><a href="get_daily_times.php?sID=' . $row1['studentID'] . '">'. $row1['firstName'], " ", $row1['lastName'] .'</a></td>';
			echo "<td>" . date("m/d h:i A", $row1['time']/1000) . "</td>";
			echo "<td>" . $row1['in_out'] . "</td>";
			if ($row1['in_out'] == "In"){
				echo "<td>" . $row1['roomNumber'] . "</td>";
			}
			echo "</tr>";
		}
	}
	echo "</table>";

	mysqli_close($con);
	echo "</br>";
	echo '<td><a href="get_total_hours_cohort.php?cohortNumber=' . $cohortNum . '">Get total hours for each student</a></td>';
	echo "</br>";
	echo '<td><a href="get_total_hours_class.php?cohortNumber=' . $cohortNum . '">Get total hours for each student by class</a></td>';
}

?>
</div>
</body>
</html>