<html>
<head>
	<title>Cohort Student List</title>
	<link rel="stylesheet" type="text/css" href="./css/screen.css" media="screen">
	<link rel="stylesheet" type="text/css" href="./css/print.css" media="print">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<b>Total Time</b>
<form action="get_daily_times.php" method="get">
Start Date:  <input type="text" name="startDate" id="startDate" placeholder="mm/dd/yy"/>
<!--  value="<?= isset($_GET['startDate']) ? $_GET['startDate'] : '' ?>" -->
</br>
End Date: &nbsp;<input type="text" name="endDate" id="endDate" placeholder="mm/dd/yy"/>
<!--  value="<?= isset($_GET['endDate']) ? $_GET['endDate'] : '' ?>" -->
<input type="hidden" name="sID" value="<?php echo htmlspecialchars($_GET['sID']);?>">
<input type='submit'>
</br></br>

<?php
date_default_timezone_set('America/New_York');

if (isset($_GET["startDate"]) && isset($_GET["endDate"]) && isset($_GET["sID"])){
	$sID = $_GET["sID"];
	
	$miliFrom = strtotime($_GET['startDate']) * 1000;
	$miliTo = strtotime($_GET['endDate']) * 1000 + 82800000;
	
	
	include("dbcon.php");
	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	$timesIn = mysqli_query($con,"SELECT time FROM cohortlog WHERE studentID = $sID AND in_out = 'In' AND time BETWEEN '" . $miliFrom . "' AND  '" . $miliTo . "' ORDER BY time ASC");
	$timesOut = mysqli_query($con,"SELECT time FROM cohortlog WHERE studentID = $sID AND in_out = 'Out' AND time BETWEEN '" . $miliFrom . "' AND  '" . $miliTo . "' ORDER BY time ASC");

	$arrayA = array();
	$arrayB = array();
	while($res = mysqli_fetch_assoc($timesIn)){
		$arrayA[] = $res['time'];
	}

	while($res = mysqli_fetch_assoc($timesOut)){
		$arrayB[] = $res['time'];
	}

	$output = array();
	
	$totsecond = 0;
	for ( $i = 0; $i < count($arrayB); ++$i ) {
		$output[] = $arrayB[$i] - $arrayA[$i];
		$seconds = $output[$i]/1000;
		$totsecond = $totsecond + $output[$i];
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

	echo "Total Time: " . str_pad($hours, 2, "0", STR_PAD_LEFT) . ":" . str_pad($minutes, 2, "0", STR_PAD_LEFT) . ":" . str_pad($seconds, 2, "0", STR_PAD_LEFT);

	echo "</br>";
	echo "<b>Daily Hours</b>";
	echo "</br>";

	echo "<table border='1'>
		 <thead>
		 <tr>
		 <th>Date</th>
		 <th>Hours</th>
		 </tr>
		 </thead>";

	for ( $i = 0; $i < count($arrayB); ++$i ) {
		$output[] = $arrayB[$i] - $arrayA[$i];
 
		echo "<tr>";
		echo "<td>" . date("m/d", $arrayA[$i]/1000), " " . "</td>";
		$seconds = $output[$i]/1000;
		echo "<td>" . gmdate("H:i:s", $seconds) . "</td>";
		echo "</tr>";
	}

	echo "</table>";
	
	mysqli_close($con); 
} else {
	echo "Please Enter a Start and End Date.";
}

?>

</body>
</html>