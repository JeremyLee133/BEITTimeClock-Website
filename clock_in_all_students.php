<?php
date_default_timezone_set('America/New_York');

include("dbcon.php");

$clockedOutStudents = mysqli_query($con,"SELECT * FROM cohortcurrent WHERE in_out = 'Out'");
while ($row = mysqli_fetch_array($clockedOutStudents)){
	
	$sID = $row['studentID'];
	$firstName = $row['firstName'];
	$lastName = $row['lastName'];
	$cohortNumber = $row['cohortNumber'];
	$time = round(microtime(true) * 1000);
	$in_out = "In";
	$roomNumber = "001";
	
	$clockStudentsInCurrent = mysqli_query($con, "UPDATE cohortcurrent SET time = '$time', in_out = '$in_out', roomNumber = '$roomNumber' WHERE studentID = '$sID'");
	$clockStudentsInLog = mysqli_query($con, "INSERT INTO cohortlog(studentID, firstName, lastName, cohortNumber, time, in_out, roomNumber) VALUES('$sID', '$firstName', '$lastName', '$cohortNumber', '$time', '$in_out', '$roomNumber')");
}
mysqli_close($con);
?>