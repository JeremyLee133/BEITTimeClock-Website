<html>
<head>
	<title>Cohort Student List</title>
	<link rel="stylesheet" type="text/css" href="./css/screen.css" media="screen">
	<link rel="stylesheet" type="text/css" href="./css/print.css" media="print">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<?php include("header.html"); 
echo "<div class='screen'>";
if (isset($_GET["cohortNumber"])){
	echo "<a href='get_cohort_student_list.php?cohortNumber=".$_GET["cohortNumber"]."'>Return To Cohort Student List</a>";
	echo "</br>";
}
?>
<b>Total Time</b>
<form action="get_total_hours_cohort.php" method="get">
Start Date:  <input type="text" name="startDate" id="startDate" placeholder="mm/dd/yy" />
<!-- value="<?= isset($_GET['startDate']) ? $_GET['startDate'] : '' ?>" -->
</br>
End Date: &nbsp;<input type="text" name="endDate" id="endDate" placeholder="mm/dd/yy"/>
<!--  value="<?= isset($_GET['endDate']) ? $_GET['endDate'] : '' ?>"  -->
<input type="hidden" name="cohortNumber" value="<?php echo htmlspecialchars($_GET['cohortNumber']);?>">
<input type='submit'>
</br></br>

<?php
date_default_timezone_set('America/New_York');

if (isset($_GET["startDate"]) && isset($_GET["endDate"]) && isset($_GET["cohortNumber"])){
	$cohortNumber = $_GET["cohortNumber"];
	$miliFrom = strtotime($_GET['startDate']) * 1000;
	$miliTo = strtotime($_GET['endDate']) * 1000 + 82800000;
	
	
	include("dbcon.php");
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	echo "<b>Cohort ". $cohortNumber ."</b>";
	$getStudentIDs = mysqli_query($con, "SELECT studentID FROM cohortcurrent WHERE cohortNumber = $cohortNumber");

	$studentIDs = array();

	while ($res = mysqli_fetch_assoc($getStudentIDs)){
		$studentIDs[] = $res['studentID'];
	}
	
	echo "<table border='1'>
		 <thead>
		 <tr>
		 <th>Name</th>
		 <th>Total Hours</th>
		 </tr>
		 </thead>";

	for ( $i = 0; $i < count($studentIDs); ++$i ) {
		$sID =  $studentIDs[$i];
		
		$timesIn = mysqli_query($con,"SELECT firstName, lastName, time FROM cohortlog WHERE studentID = $sID AND in_out = 'In' AND time BETWEEN '" . $miliFrom . "' AND  '" . $miliTo . "' ORDER BY time ASC");
		$timesOut = mysqli_query($con,"SELECT time FROM cohortlog WHERE studentID = $sID AND in_out = 'Out' AND time BETWEEN '" . $miliFrom . "' AND  '" . $miliTo . "' ORDER BY time ASC");

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
		
		echo "<tr>";
		echo '<td><a href="get_daily_times.php?sID=' . $sID . '">'. $firstName, " ", $lastName .'</a></td>';
		
		echo "<td>" . str_pad($hours, 2, "0", STR_PAD_LEFT) . ":" . str_pad($minutes, 2, "0", STR_PAD_LEFT) . ":" . str_pad($seconds, 2, "0", STR_PAD_LEFT) . "</td>";
		echo "</tr>";
	}
	echo "</table>";
	
	mysqli_close($con); 
} else {
	echo "Please Enter a Start and End Date.";
}
?>
</div>
</body>
</html>