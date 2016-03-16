<html>
<head>
	<title>Student Time Information</title>
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

if (isset($_GET["sID"])){
	$sID = $_GET["sID"];
	$name = mysqli_query($con, "SELECT firstName, lastName, studentID, cohortNumber FROM cohortcurrent WHERE studentID = $sID");
	$fullName = mysqli_fetch_array($name);
	
	echo "<a href='get_cohort_student_list.php?cohortNumber=".$fullName['cohortNumber']."'>Return To Cohort Student List</a>";
	echo "</br>";
		echo "</br>";
	echo "Name: ", $fullName['firstName'], " ", $fullName['lastName'], "</br>";
	
	switch ($fullName['cohortNumber']){
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
			$cohortNumName = $fullName['cohortNumber'];
	}
	
	echo "Cohort: ", $cohortNumName, "</br>";
	echo "<a href='studentComments_Add.php?sID=".$sID."'>Add/Edit Student Comments</a>";
	echo "</br>";
	echo "</br>";
	
	echo "<div style='float: left; padding-right: 15px'>";
	include 'scheduledTimes.php';
	echo "</div>";
	echo "<div style='float: left; padding-right: 15px'>";
	include 'clockedInOutTimes.php';
	echo "</div>";
	echo "<div style='float: left'>";
	include_once 'get_total_hours_student.php';
	echo "</div>";
	
}
?>
</div>

</body>
</html>