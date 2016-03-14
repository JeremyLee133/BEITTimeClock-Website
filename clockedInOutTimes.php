<?php
date_default_timezone_set('America/New_York');
include("dbcon.php");
// Check connection
if (mysqli_connect_errno()){
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

if (isset($_GET["sID"])){
	$sID = $_GET["sID"];
	$in = mysqli_query($con,"SELECT time, in_out, roomNumber FROM cohortlog WHERE studentID = $sID AND in_out = 'In' ORDER BY time DESC");
	$out = mysqli_query($con,"SELECT time, in_out, roomNumber FROM cohortlog WHERE studentID = $sID AND in_out = 'Out' ORDER BY time DESC");
} 

$row_count_in = $in->num_rows;
$row_count_out = $out->num_rows;

echo "<b>Clocked In and Out Times</b>";
echo "</br>";
echo "If Room is <font color='red'>000</font>, the student forgot to clock out </br>and the server automatically did so for them.";
echo "<table border='1'>
	 <thead>
	 <tr>
	 <th>Time In</th>
	 <th>Room</th>
	 <th>Time Out</th>
	 <th>Room</th>
	 </tr>
	 </thead>";
	
for ( $i = 0; $i < $row_count_in; ++$i ) {
	echo "<tr>";
	
	$inRow = mysqli_fetch_array($in);
	$milliseconds = $inRow['time'];
	$timestamp = $milliseconds/1000;
	echo "<td>" . date("m/d h:i A", $timestamp) . "</td>";
	
	if($inRow['roomNumber'] == "000"){
			echo "<td><font color='red'>" . $inRow['roomNumber'] . "</font></td>";
		} else {
			echo "<td>" . $inRow['roomNumber'] . "</td>";
		}
	
	if ($i > 0 && $row_count_out< $row_count_in){
		$outRow = mysqli_fetch_array($out);
		$milliseconds = $outRow['time'];
		$timestamp = $milliseconds/1000;
		echo "<td>" . date("m/d h:i A", $timestamp) . "</td>";
		
		if($outRow['roomNumber'] == "000"){
			echo "<td><font color='red'>" . $outRow['roomNumber'] . "</font></td>";
		} else {
			echo "<td>" . $outRow['roomNumber'] . "</td>";
		}
		
	 } else if ($row_count_in == $row_count_out){
		$outRow = mysqli_fetch_array($out);
		$milliseconds = $outRow['time'];
		$timestamp = $milliseconds/1000;
		echo "<td>" . date("m/d h:i A", $timestamp) . "</td>";
		
		if($outRow['roomNumber'] == "000"){
			echo "<td><font color='red'>" . $outRow['roomNumber'] . "</font></td>";
		} else {
			echo "<td>" . $outRow['roomNumber'] . "</td>";
		}
		
	}
	
	echo "</tr>";
}
echo "</table>";
mysqli_close($con);
?>