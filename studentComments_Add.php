<html>
<head>
	<title>Student Time Information</title>
	<link rel="stylesheet" type="text/css" href="./css/screen.css" media="screen">
	<link rel="stylesheet" type="text/css" href="./css/print.css" media="print">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<?php
date_default_timezone_set('America/New_York');

include("dbcon.php");

include("header.html");

if (isset($_GET["sID"])){
	

	$sID = $_GET["sID"];
	
	echo "<a href='get_daily_times.php?sID=".$sID."'>Return To Student Information</a>";
	echo "</br>";
	echo "</br>";
	
	$getStudentName = mysqli_query($con, "SELECT firstName, lastName FROM cohortcurrent WHERE studentID = $sID");
	while($res = mysqli_fetch_assoc($getStudentName)){
		$studentName = $res['firstName'] . " " . $res['lastName'];
	}
	echo $studentName;
	
	echo "<form id='addStudentComments' action='studentComments_Add.php?sID=".$sID."' method='post'>";
	echo "<input type='text' name='date' placeholder='date: mm/dd/yy'/>";
	echo "</br>";
	echo "<textarea name='comments' id='comments' cols='60' rows='8' placeholder='Comments'></textarea>";
	echo "</br>";
	echo "<input type='hidden' name='studentID' value='" .$sID."'/>";
	echo "<input type='submit' value='Submit'>";
	echo "</form>";
	echo "<hr>";
	
	$getStudentComments = mysqli_query($con, "SELECT id, date, comments FROM studentcomments WHERE studentID = $sID ORDER BY date ASC");
	echo "<table border='1'>
		<thead>
		<tr>
		<th>Date</th>
		<th>Comments</th>
		</thead>";
	while($res1 = mysqli_fetch_assoc($getStudentComments)){
		echo "<tr>";
		
		echo '<td><a href="studentComments_Edit.php?id='.$res1['id'].'">'.date("m/d/y", strtotime($res1['date'])).'</a></td>';
		echo "<td>".$res1['comments']."</td>";
		echo "</tr>";
	}
	echo "</table>";
}

if ($_POST["date"] != "" && $_POST["comments"] != ""){
	$studentID = $_REQUEST['studentID'];
	$date = $_REQUEST['date'];
	$studentComments = mysqli_real_escape_string($con, $_REQUEST['comments']);
	
	
	$reversedDate = date("Y-m-d", strtotime($date));
	echo $studentComments;
	echo $reversedDate;
	$addStudentComment = mysqli_query($con, "INSERT INTO studentcomments(studentID, date, comments) VALUES('$studentID', '$reversedDate', '$studentComments')");
	header("Location: studentComments_Add.php?sID=$studentID");
}

?>
</body>
</html>