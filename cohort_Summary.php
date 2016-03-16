<html>
<head>
	<title>Cohort Student List</title>
	<link rel="stylesheet" type="text/css" href="./css/screen.css" media="screen">
	<link rel="stylesheet" type="text/css" href="./css/print.css" media="print">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<?php
date_default_timezone_set('America/New_York');

include("dbcon.php");
echo "<div class='no-print'>";
include("header.html");

if (!isset($_REQUEST["cohortNumber"])){
	echo "Please Select a Cohort to View The Class List";
	echo "</br>";
}

echo "<form id='cohortStudentList' action='cohort_Summary.php' method='post'>";
echo "<select id='cohortNumber' name='cohortNumber'>";

echo "<option value='4'>Cohort 4 - Carpentry</option>";
echo "<option value='5'>Cohort 5 - Precision Machining</option>";
echo "<option value='6'>Cohort 6 - Mechatronics</option>";
echo "<option value='7'>Cohort 7 - Welding</option>";
echo "<option value='9'>Cohort 9 - Mechatronics</option>";
echo "<option value='10'>Cohort 10 - Carpentry</option>";

echo "</select>";
echo "<input type='submit' value='Submit'>";
echo "</form>";
echo "</div>";

if (isset($_REQUEST["cohortNumber"])){
	$cohortNum = $_REQUEST["cohortNumber"];
	
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
		case 9:
			$cohortNumName = "9 - Mechatronics";
			break;	
		case 10:
			$cohortNumName = "10 - Carpentry";
			break;				
		default:
			$cohortNumName = $cohortNum;
	}
	echo "<b>Cohort: ", $cohortNumName, "</b>";


$cohortStudentList = mysqli_query($con, "SELECT studentID, firstName, lastName FROM cohortcurrent WHERE cohortNumber = '$cohortNum' ORDER BY lastName ASC");

$studentIDs = array();
$firstNames = array();
$lastNames = array();

while ($res = mysqli_fetch_assoc($cohortStudentList)){
	$studentIDs[] = $res['studentID'];
	$firstNames[] = $res['firstName'];
	$lastNames[] = $res['lastName'];
}

echo "<table border='1'>
		<thead>
		<tr>
		<th>Student Name</th>
		<th>Days On Time</th>
		<th>Days Late</th>
		<th>Days Attended</th>
		<th>Days Absent</th>
		</tr>
		</thead>";
}
for ($i = 0; $i < count($studentIDs); ++$i){
	echo "<tr>";
	echo '<td><a href="get_daily_times.php?sID=' . $studentIDs[$i] . '">' . $firstNames[$i] . " " . $lastNames[$i] . '</a></td>';
	test($studentIDs[$i]);
}
	echo "</table>";
	echo "</br>";
	include("cohort_SummaryAvg.php");

function test($studentIDtest){
	
	include("dbcon.php");
	$sID = $studentIDtest;
	
	$studentDates = array();
	$inDates = array();
	
	$studentAttendedDates = mysqli_query($con, "SELECT cohortNumber, time FROM cohortlog WHERE studentID = $sID AND in_out = 'In' ORDER BY time ASC");
	while($res = mysqli_fetch_assoc($studentAttendedDates)){
		$studentDates[] = $res['time'];
		$cohortNum = $res['cohortNumber'];
	}
	
	for ($i = 0; $i < count($studentDates); ++$i){
		$inDates[$i] = date("m/d/y", $studentDates[$i]/1000);
	}
	
	for ( $i = 0; $i < count($studentDates); ++$i ) {
		if ($i > 0){
			if (date("m/d", $studentDates[$i]/1000) == date("m/d", $studentDates[$i - 1]/1000)) {
				unset($studentDates[$i]);
				$studentDates = array_values($studentDates);
			}
		}
	}
	$emptyRemoved = array_filter($studentDates);
	
	for ( $i = 0; $i < count($emptyRemoved); ++$i ) {
		if ($i > 0){
			if (date("m/d", $emptyRemoved[$i]/1000) == date("m/d", $emptyRemoved[$i - 1]/1000)) {
				unset($emptyRemoved[$i]);
				$emptyRemoved = array_values($emptyRemoved);
			}
		}
	}
	
	$studentDaysAttended = array_filter($emptyRemoved);
	
	$scheduledDates = array();
	$scheduledStartTimes = array();
	
	$cohortClass = "cohort" . $cohortNum . "class";
	$cohortStartTime = "cohort" . $cohortNum . "starttime";
	
	$scheduledDates1 = mysqli_query($con, "SELECT date, " . $cohortClass . ", " . $cohortStartTime ." FROM cohortschedule WHERE ".$cohortClass." != ''");
	
	while ($res1 = mysqli_fetch_assoc($scheduledDates1)){
		$scheduledDates[] = $res1['date'];
		$scheduledStartTimes[] = $res1[$cohortStartTime];
	}
	
	$s = 0;
	$daysLate = 0;
	$daysAbsent = 0;
	$daysOnTime = 0;
	$daysAttended = 0;
	for ($i = 0; $i < count($scheduledDates); ++$i){
		
		$scheduledDay = $scheduledDates[$i];
		$scheduledTime = date("H:i:s", strtotime($scheduledStartTimes[$i]));
		$studentTimeMDY = date("m/d/y", $studentDaysAttended[$s]/1000);
		$studentTimeHMS = date("H:i:s", $studentDaysAttended[$s]/1000);
		
		if (strtotime($scheduledDay)*1000 > strtotime(date("m/d/y"))*1000){
			break;
		} else if ($scheduledDay == $studentTimeMDY){
			if ($scheduledTime < $studentTimeHMS){
				$daysLate += 1;
			} else {
				$daysOnTime += 1;
			}
			$s = $s + 1;
		} else {
			$daysAbsent += 1;
		}
	}
	
	$daysAttended = $daysOnTime + $daysLate;

	echo "<td><font color='green'><b>" . $daysOnTime . "</b></font></td> ";
	echo "<td><font color='orange'><b>" . $daysLate . "</b></font></td> ";
	echo "<td><font color='black'><b>" . $daysAttended . "</b></font></td> ";
	echo "<td><font color='red'><b>" . $daysAbsent . "</b></font></td> ";
	echo "</tr>";

}
?>
</body>
</html>