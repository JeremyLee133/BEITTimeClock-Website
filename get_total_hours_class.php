<html>
<head>
	<title>Cohort Student List</title>
	<link rel="stylesheet" type="text/css" href="./css/screen.css" media="screen">
	<link rel="stylesheet" type="text/css" href="./css/print.css" media="print">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<?php include("header.html"); ?>
<div class="screen">
<?php
date_default_timezone_set('America/New_York');

include("dbcon.php");

//Only display form with classes if a cohortNumber has been set
if (isset($_GET["cohortNumber"])){
	echo "<a href='get_cohort_student_list.php?cohortNumber=".$_GET["cohortNumber"]."'>Return To Cohort Student List</a>";
	echo "</br>";
	echo "</br>";
	
	//Gets cohort number and sets up some variables
	$cohortNumber = $_GET["cohortNumber"];
	$scheduleNumber = "cohort" . $cohortNumber . "schedule";
	$classesNumber = "cohort" . $cohortNumber . "class";
	
	//query to return all scheduled classes from the cohort schedules
	$getClasses = mysqli_query($con, "SELECT ". $classesNumber .", date FROM cohortschedule WHERE ".$classesNumber." != '' GROUP BY ". $classesNumber);

	//Form to select a class
	echo "<form id='cohortClassesList' action='get_total_hours_class.php?cohortNumber=".$cohortNumber."' method='post'>";
	echo "<select id='test' name='cohortClass'>";
	while ($row = mysqli_fetch_assoc($getClasses)) {
		if((strtotime($row["date"]) * 1000) < (time() * 1000)){
			echo "<option value='" . $row["$classesNumber"] . "'>" . $row["$classesNumber"] . "</option>";
		}
	}
	echo "</select>";
	
	//Some variables to pass after selecting a class
	echo "<input type='hidden' name='scheduleNumber' value='" . $scheduleNumber . "'>";
	echo "<input type='hidden' name='classesNumber' value='" . $classesNumber . "'>";
	echo "<input type='hidden' name='cohortNumber' value='" . $cohortNumber . "'>";
	echo "<input type='submit' value='Submit'>";
	echo "</form>";
}

//Only display the total hours after a class has been selected and submitted
if (isset($_POST["cohortClass"]) && isset($_POST["scheduleNumber"]) && isset($_POST["classesNumber"])){
	
	//Pull variables from POST after submitting the form
	$scheduleNumber = $_POST["scheduleNumber"];
	$classesNumber = $_POST["classesNumber"];
	$cohortNumber = $_POST["cohortNumber"];
	$className = $_POST["cohortClass"];
	
	//Query to retrieve the scheduled dates of the previously selected class and writed it to an array
	$getClassDates = mysqli_query($con, "SELECT date FROM cohortschedule WHERE ". $classesNumber ." = '$className'");
	$classDates = array();
	while ($res = mysqli_fetch_assoc($getClassDates)){
		$classDates[] = $res["date"];
	}
	
	//Sets up an array that has all of the from and to dates of the selected class
	$betweenDates = array();
	for ($i=0; $i < count($classDates); ++$i){
		$miliFrom = strtotime($classDates[$i]) * 1000;
		$miliTo = strtotime($classDates[$i]) * 1000 + 82800000;
		
		$betweenDates[] = $miliFrom;
		$betweenDates[] = $miliTo;
	}
	
	//Sets up a dynamic WHERE clause of start and end dates for a SQL Query
	$sqlTimeStr = "AND (time BETWEEN '";
	for ($i = 0; $i < count($betweenDates); $i += 2){
		if ($i > 1){
			$sqlTimeStr .= "OR time BETWEEN '" .$betweenDates[$i] . "' AND '" . $betweenDates[$i + 1] . "' ";
		} else {
			$sqlTimeStr .= $betweenDates[$i] . "' AND '" . $betweenDates[$i + 1] . "' ";
		}
	}
	$sqlTimeStr .= ")";
	
	//Sets up an array of all the studentIDs from the cohort
	$getStudentIDs = mysqli_query($con, "SELECT studentID FROM cohortcurrent WHERE cohortNumber = $cohortNumber");
	$studentIDs = array();
	while ($res = mysqli_fetch_assoc($getStudentIDs)){
		$studentIDs[] = $res['studentID'];
	}
	//Start of the table
	echo "<b>Cohort:</b> " . $cohortNumber . "</br><b>Class:</b> " . $className;
	echo "<table border='1'>
		 <thead>
		 <tr>
		 <th>Name</th>
		 <th>Total Hours</th>
		 </tr>
		 </thead>";

	for ( $i = 0; $i < count($studentIDs); ++$i ) {
		$sID =  $studentIDs[$i];
		
		//Query that selects all of a students Clocked In and Clocked Out times that occur on the dates of the selected class
		$timesIn = mysqli_query($con,"SELECT firstName, lastName, time FROM cohortlog WHERE studentID = $sID AND in_out = 'In' ". $sqlTimeStr ." ORDER BY time ASC");
		$timesOut = mysqli_query($con,"SELECT time FROM cohortlog WHERE studentID = $sID AND in_out = 'Out' ". $sqlTimeStr ." ORDER BY time ASC");
		
		$arrayA = array();
		$arrayB = array();

		while($res = mysqli_fetch_assoc($timesIn)){
			$firstName = $res['firstName'];
			$lastName = $res['lastName'];
			$arrayA[] = $res['time'];
		}

		while($res = mysqli_fetch_assoc($timesOut)){
			$arrayB[] = $res['time'];
		}
	
		$output = array();
	
		$totsecond = 0;
		for ( $s = 0; $s < count($arrayB); ++$s ) {
			$output[] = $arrayB[$s] - $arrayA[$s];
			$seconds = $output[$s]/1000;
			$totsecond = $totsecond + $output[$s];
		}
	
		$input = $totsecond;

		$uSec = $input % 1000;
		$input = floor($input / 1000);

		$seconds = $input % 60;
		$input = floor($input / 60);

		$minutes = $input % 60;
		$input = floor($input / 60); 

		$hours = $input;
		$input = floor($input / 60); 
		
		if($hours == 0 && $minutes == 0 && $seconds == 0){

		} else {
			echo "<tr>";
			echo '<td><a href="get_daily_times.php?sID=' . $sID . '">'. $firstName, " ", $lastName .'</a></td>';
		
			echo "<td>" . str_pad($hours, 2, "0", STR_PAD_LEFT) . ":" . str_pad($minutes, 2, "0", STR_PAD_LEFT) . ":" . str_pad($seconds, 2, "0", STR_PAD_LEFT) . "</td>";
			echo "</tr>";
		}
		
	}
	echo "</table>";
	
	mysqli_close($con); 
}
?>
</div>
</body>
</html>