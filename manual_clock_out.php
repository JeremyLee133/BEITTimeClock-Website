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
	
	//Form to select a class
	echo "<form id='cohortStudentList' action='manual_clock_out.php?cohortNumber=".$cohortNumber."' method='post'>";
	echo "<select id='test' name='cohortStudents'>";
	while ($row = mysqli_fetch_assoc($getStudents)) {
			$studentID = $row["studentID"];
			$firstName = $row["firstName"];
			$lastName = $row["lastName"];
			echo "<option value='" . $studentID . "_" . $firstName . "_" . $lastName . "'>" . $row["firstName"] . " " . $row["lastName"] ."</option>";
		 
	}
	echo "</select>";
	echo "</br>";
	echo "<input type='text' name='date' id='date' placeholder='date: mm/dd/yy'/>";
		echo "</br>";

		echo "<input type='text' name='timeOut' id='timeOut' placeholder='Time Out: hh:mm' />";
		echo "</br>";
		echo "<input type='text' name='roomNumber' id='roomNumber' placeholder='Room Number'/>";
		echo " ";
	echo "<input type='hidden' name='cohortNumber' value='" . $cohortNumber . "'>";
	echo "<input type='submit' value='Submit'>";
	echo "</form>";


}
	if (isset($_POST["cohortStudents"]) && $_POST["date"] != "" && $_POST["timeOut"] != "" && $_POST["roomNumber"] != ""){
		$value = $_REQUEST['cohortStudents'];
		$explode = explode("_",$value,3);
		$studentID = $explode[0];
		$firstName = $explode[1];
		$lastName = $explode[2];
		
		$cohortNumber = $_REQUEST['cohortNumber'];
		$date = $_REQUEST['date'];
		$timeOut = $_REQUEST['timeOut'];
		$roomNumber = $_REQUEST['roomNumber'];
		
		//echo $studentID . " " . $firstName . " " . $lastName . " " . $date . " " . $timeOut . " " . $roomNumber;
		
		$time = strtotime($date . " " . $timeOut) * 1000;

		//$clockStudentsOutCurrent = mysqli_query($con, "UPDATE cohortcurrent SET time = '$time', in_out = 'Out', roomNumber = '$roomNumber' WHERE studentID = '$studentID'");
		$clockStudentsOutLog = mysqli_query($con, "INSERT INTO cohortlog(studentID, firstName, lastName, cohortNumber, time, in_out, roomNumber) VALUES('$studentID', '$firstName', '$lastName', '$cohortNumber', '$time', 'Out', '$roomNumber')");
		
	}
?>
</div>
</body>
</html>