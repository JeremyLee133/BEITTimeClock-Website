<?php
date_default_timezone_set('America/New_York');
include("dbcon.php");

if (isset($_GET["cohortNumber"])){
$cohortNum = $_GET["cohortNumber"];
}


if($cohortNum == 4){
	$clockedOutStudents = mysqli_query($con,"SELECT * FROM cohortcurrent WHERE in_out = 'Out' AND cohortNumber = 4");
} 
if($cohortNum == 5){
	$clockedOutStudents = mysqli_query($con,"SELECT * FROM cohortcurrent WHERE in_out = 'Out' AND cohortNumber = 5");
}
if($cohortNum == 6){
	$clockedOutStudents = mysqli_query($con,"SELECT * FROM cohortcurrent WHERE in_out = 'Out' AND cohortNumber = 6");
}
if($cohortNum == 7){
	$clockedOutStudents = mysqli_query($con,"SELECT * FROM cohortcurrent WHERE in_out = 'Out' AND cohortNumber = 7");
}

$clockedOutTimes = array();
while ($row = mysqli_fetch_array($clockedOutStudents)){
	$clockedOutTimes[] = $row['time'];
	
}

if (date("m/d/y", max($clockedOutTimes)/1000) != date ("m/d/y")){
	if ($cohortNum == 4){
		$newestTime = strtotime("22:30:00") *1000;
	} elseif ($cohortNum == 5){
		$newestTime = strtotime("16:30:00") *1000;
	} elseif ($cohortNum == 6){
		$newestTime = strtotime("16:30:00") *1000;
	} elseif ($cohortNum == 7){
		$newestTime = strtotime("16:30:00") *1000;
	}
} else {
	$newestTime = max($clockedOutTimes);
}


if($cohortNum == 4){
	$clockedInStudents = mysqli_query($con,"SELECT * FROM cohortcurrent WHERE in_out = 'In' AND cohortNumber = 4");
} 
if($cohortNum == 5){
	$clockedInStudents = mysqli_query($con,"SELECT * FROM cohortcurrent WHERE in_out = 'In' AND cohortNumber = 5");
}
if($cohortNum == 6){
	$clockedInStudents = mysqli_query($con,"SELECT * FROM cohortcurrent WHERE in_out = 'In' AND cohortNumber = 6");
}
if($cohortNum == 7){
	$clockedInStudents = mysqli_query($con,"SELECT * FROM cohortcurrent WHERE in_out = 'In' AND cohortNumber = 7");
}

while ($row = mysqli_fetch_array($clockedInStudents)){
	
	$sID = $row['studentID'];
	$firstName = $row['firstName'];
	$lastName = $row['lastName'];
	$cohortNumber = $row['cohortNumber'];
	$time = $newestTime;
	$in_out = "Out";
	$roomNumber = "000";
	
	$clockStudentsOutCurrent = mysqli_query($con, "UPDATE cohortcurrent SET time = '$time', in_out = '$in_out', roomNumber = '$roomNumber' WHERE studentID = '$sID'");
	$clockStudentsOutLog = mysqli_query($con, "INSERT INTO cohortlog(studentID, firstName, lastName, cohortNumber, time, in_out, roomNumber) VALUES('$sID', '$firstName', '$lastName', '$cohortNumber', '$time', '$in_out', '$roomNumber')");
}

mysqli_close($con);
?>