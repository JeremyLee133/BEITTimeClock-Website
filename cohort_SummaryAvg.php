<?php
date_default_timezone_set('America/New_York');

include("dbcon.php");
if (isset($_REQUEST['cohortNumber'])){
	

$cohortNum = $_REQUEST['cohortNumber'];

$scheduledDates = array();
$cohortClass = "cohort" . $cohortNum . "class";

$scheduledDatesQuery = mysqli_query($con, "SELECT date FROM cohortschedule WHERE ".$cohortClass." != ''");
while ($res = mysqli_fetch_assoc($scheduledDatesQuery)){
	$scheduledDate = $res['date'];
	$todaysDate = date("m/d/y");
	if (strtotime($scheduledDate)*1000 > strtotime($todaysDate)*1000){
		break;
	} else {
		$scheduledDates[] = $res['date'];
	}
}

for ($i = 0; $i < count($scheduledDates); ++$i){
	$startTime = strtotime($scheduledDates[$i])*1000;
	$endTime = ((strtotime($scheduledDates[$i])*1000) + 86399000);
	$testQuery = mysqli_query($con, "SELECT DISTINCT studentID FROM cohortlog WHERE cohortNumber = $cohortNum AND time > $startTime AND time < $endTime AND in_out = 'In'");
	$studentCount = array();
	while ($res1 = mysqli_fetch_assoc($testQuery)){
		$studentCount[] = $res1['studentID'];
	}
	$totalcount = $totalcount + count($studentCount);
}

echo "Average number of students in attendance: " . floor($totalcount / count($scheduledDates));
}
?>