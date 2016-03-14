<html>
<head>
	<title>Cohort Student List</title>
	<link rel="stylesheet" type="text/css" href="./css/screen.css" media="screen">
	<link rel="stylesheet" type="text/css" href="./css/print.css" media="print">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<?php include("header.html"); 
date_default_timezone_set('America/New_York');

include("dbcon.php");

echo "<div class='screen'>";
if (isset($_GET["cohortNumber"])){
	$cohortNumber = $_GET["cohortNumber"];
	$getStudents = mysqli_query($con, "SELECT studentID, firstName, lastName FROM cohortcurrent WHERE cohortNumber = $cohortNumber");
	
	echo "<a href='get_cohort_student_list.php?cohortNumber=".$cohortNumber."'>Return To Cohort Student List</a>";
	echo "</br></br>";
	
	echo "<form id='cohortStudentList' action='manual_clock_in_out.php?cohortNumber=".$cohortNumber."' method='post'>";
	echo "<select id='test' name='cohortStudents'>";
	while ($row = mysqli_fetch_assoc($getStudents)) {
			$studentID = $row["studentID"];
			$firstName = $row["firstName"];
			$lastName = $row["lastName"];
			echo "<option value='" . $studentID . "_" . $firstName . "_" . $lastName . "'>" . $row["firstName"] . " " . $row["lastName"] ."</option>"; 
	}
	echo "</select>";
	echo "</br></br>";
	
	echo "<input type='text' name='date' id='date' placeholder='date: mm/dd/yy'/>";
	echo "</br></br>";
	
	echo "<input type='text' name='timeIn' id='timeIn' placeholder='Time: hh:mm' />";
	echo " Please use 24H time, or add AM/PM to all times you type in.  For example, either type 1:00 PM or 13:00.  If you type 1:00, it will be 1:00 AM.";
	echo "</br></br>";
	
	echo "Clock student In or Out?: <select id='inOrOut' name='inOrOut'>";
		echo "<option value='In'>In</option>"; 
		echo "<option value='Out'>Out</option>";
	echo "</select>";
	echo "</br></br>";
	
	echo "<input type='text' name='roomNumber' id='roomNumber' placeholder='Room Number'/>";
	echo " Please use 000 as the room number unless the student has a good reason for not clocking themselves in, then use what room they are supposed to be in.";
	echo "</br></br>";
	
	echo "Is this the current status of the student?: <select id='currentStatus' name='currentStatus'>";
		echo "<option value='No'>No</option>";
		echo "<option value='Yes'>Yes</option>"; 
	echo "</select>";
	echo "</br></br>";
	
	echo "<input type='hidden' name='cohortNumber' value='" . $cohortNumber . "'>";
	echo "Please double check everything before submitting.";
	echo "</br>";
	
	echo "<input type='submit' value='Submit'>";
	echo "</form>";
}
	if (isset($_POST["cohortStudents"]) && $_POST["date"] != "" && $_POST["timeIn"] != "" && $_POST["roomNumber"] != ""){
		$value = $_REQUEST['cohortStudents'];
		$explode = explode("_",$value,3);
		$studentID = $explode[0];
		$firstName = $explode[1];
		$lastName = $explode[2];
		
		$cohortNumber = $_REQUEST['cohortNumber'];
		$date = $_REQUEST['date'];
		$timeIn = $_REQUEST['timeIn'];
		$inOut = $_REQUEST['inOrOut'];
		$roomNumber = $_REQUEST['roomNumber'];
		$currentStatus = $_REQUEST['currentStatus'];
		
		$time = strtotime($date . " " . $timeIn) * 1000;

		if($currentStatus == "Yes"){
			$clockStudentsInCurrent = mysqli_query($con, "UPDATE cohortcurrent SET time = '$time', in_out = '$inOut', roomNumber = '$roomNumber' WHERE studentID = '$studentID'");
			$clockStudentsInLog = mysqli_query($con, "INSERT INTO cohortlog(studentID, firstName, lastName, cohortNumber, time, in_out, roomNumber) VALUES('$studentID', '$firstName', '$lastName', '$cohortNumber', '$time', '$inOut', '$roomNumber')");
		} else if ($currentStatus == "No") {
			$clockStudentsInLog = mysqli_query($con, "INSERT INTO cohortlog(studentID, firstName, lastName, cohortNumber, time, in_out, roomNumber) VALUES('$studentID', '$firstName', '$lastName', '$cohortNumber', '$time', '$inOut', '$roomNumber')");
		}
	}
?>
</div>
</body>
</html>