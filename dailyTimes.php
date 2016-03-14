<?php
date_default_timezone_set('America/New_York');

include("dbcon.php");

// Check connection
if (mysqli_connect_errno()){
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

if (isset($_GET["sID"])){
	$sID = $_GET["sID"];
	$in = mysqli_query($con,"SELECT firstName, lastName, time FROM cohortlog WHERE studentID = $sID AND in_out = 'In' ORDER BY time ASC");
	$out = mysqli_query($con,"SELECT firstName, lastName, time FROM cohortlog WHERE studentID = $sID AND in_out = 'Out' ORDER BY time ASC");
	echo "<b>Daily Hours</b>";
	echo "</br>";

	$arrayA = array();
	$arrayB = array();
	while($res = mysqli_fetch_assoc($in)){
		$arrayA[] = $res['time'];
	}

	while($res = mysqli_fetch_assoc($out)){
		$arrayB[] = $res['time'];
	}

	function substract($b, $a) {
		return $b - $a;
	}

	$output = array();

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
}

mysqli_close($con);
?>