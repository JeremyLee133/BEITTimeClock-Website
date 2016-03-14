<html>
<head>
	<link rel="stylesheet" type="text/css" href="./css/screen.css" media="screen">
</head>
<body>

<?php
date_default_timezone_set('America/New_York');

include("dbcon.php");

$cohort4ScheduledDates = mysqli_query($con, "SELECT cohort4schedule FROM cohortschedule");

echo "<b>Cohort 4</b></br>";
echo "<table class='fullSchedule' border ='1'>
	  <thead>
	  <tr>
	  <th>Student Name</th>";
	  
while($cohortSchedule = mysqli_fetch_assoc($cohort4ScheduledDates)){
	if ($cohortSchedule['cohort4schedule'] > date("m/d/y")){
		break;
	}else {
		echo "<th>". $cohortSchedule['cohort4schedule'] ."</th>";
	}
	
}
echo "</tr>
	  </thead>";
	  
$cohort4Students = mysqli_query($con, "SELECT studentID, firstName, lastName, time FROM cohortcurrent WHERE cohortNumber = '4'");

while($cohortStudents = mysqli_fetch_assoc($cohort4Students)){
	echo "<tr>";
	//echo "<td>". $res['firstName'] . " " . $res['lastName'] ."</td>";
	//studentNames($cohortStudents);
	echo "<td>". $cohortStudents['firstName'] . " " . $cohortStudents['lastName'] ."</td>";
	//studentAttendance();
	
	$clockedInTimes = array();
	//$sID = 2;
	$sID = $cohortStudents['studentID'];
	$studentInTimes = mysqli_query($con, "SELECT _id, time FROM cohortlog WHERE studentID = $sID AND in_out = 'In' ORDER BY time ASC");
	while($studentTimes = mysqli_fetch_assoc($studentInTimes)){
		$clockedInTimes[] = $studentTimes['time'];	
	} 
	
	for($x = 0; $x < count($clockedInTimes); $x++){
		if ($x > 0){
			if (date("m/d/y", $clockedInTimes[$x]/1000) == date("m/d/y", $clockedInTimes[$x - 1]/1000)){
				unset($clockedInTimes[$x]);
				$clockedInTimes = array_values($clockedInTimes);
			}
		}
	}
	
	$emptyRemoved = array_filter($clockedInTimes);
	
	for ($i = 0; $i < count($emptyRemoved); $i++){
		if ($i > 0){
			if (date("m/d/y", $emptyRemoved[$i]/1000) == date("m/d/y", $emptyRemoved[$i-1]/1000)){
				unset($emptyRemoved[$i]);
				$emptyRemoved = array_values($emptyRemoved);
			}
		}
	}
	
	$emptyRemoved2 = array_filter($emptyRemoved);
	
	for ($i = 0; $i < count($emptyRemoved2); $i++){
		echo "<td>".date("m/d/y h:i:s", $emptyRemoved2[$i]/1000)."</td>";
		//echo "</br>";
		
	}
echo "</tr>";
	
}
	  echo "</table>";	
	  




//function studentAttendance(){
	$clockedInTimes = array();
	$sID = 2;
	$studentInTimes = mysqli_query($con, "SELECT _id, time FROM cohortlog WHERE studentID = $sID AND in_out = 'In' ORDER BY time ASC");
	while($studentTimes = mysqli_fetch_assoc($studentInTimes)){
		$clockedInTimes[] = $studentTimes['time'];	
	} 
	
	for($x = 0; $x < count($clockedInTimes); $x++){
		if ($x > 0){
			if (date("m/d/y", $clockedInTimes[$x]/1000) == date("m/d/y", $clockedInTimes[$x - 1]/1000)){
				unset($clockedInTimes[$x]);
				$clockedInTimes = array_values($clockedInTimes);
			}
		}
	}
	
	$emptyRemoved = array_filter($clockedInTimes);
	
	for ($i = 0; $i < count($emptyRemoved); $i++){
		if ($i > 0){
			if (date("m/d/y", $emptyRemoved[$i]/1000) == date("m/d/y", $emptyRemoved[$i-1]/1000)){
				unset($emptyRemoved[$i]);
				$emptyRemoved = array_values($emptyRemoved);
			}
		}
	}
	
	$emptyRemoved2 = array_filter($emptyRemoved);
	
	for ($i = 0; $i < count($emptyRemoved2); $i++){
		echo "<td>".date("m/d/y h:i:s", $emptyRemoved2[$i]/1000)."</td>";
		//echo "</br>";
		echo "</tr>";
	}
echo "</table>";	
//}

function studentNames($cohortStudents){
	
	//studentAttendance();
}
?>

</body>
</html>

