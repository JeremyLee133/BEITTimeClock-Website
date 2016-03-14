<?php
date_default_timezone_set('America/New_York');

include("dbcon.php");

if (isset($_GET["sID"])){
	$sID = $_GET["sID"];
	
	$studentDates = array();
	$inDates = array();
	
	$studentAttendedDates = mysqli_query($con, "SELECT cohortNumber, time FROM cohortlog WHERE studentID = $sID AND in_out = 'In' ORDER BY time ASC");
	while($res = mysqli_fetch_assoc($studentAttendedDates)){
		$studentDates[] = $res['time'];
		$cohortNum = $res['cohortNumber'];
	}
	
	for ($i = 0; $i < count($studentDates); ++$i){
		$inDates[$i] = date("m/d/y", $studentDates[$i]/1000);
	}
	
	for ( $i = 0; $i < count($studentDates); ++$i ) {
		if ($i > 0){
			if (date("m/d", $studentDates[$i]/1000) == date("m/d", $studentDates[$i - 1]/1000)) {
				unset($studentDates[$i]);
				$studentDates = array_values($studentDates);
			}
		}
	}
	$emptyRemoved = array_filter($studentDates);
	
	for ( $i = 0; $i < count($emptyRemoved); ++$i ) {
		if ($i > 0){
			if (date("m/d", $emptyRemoved[$i]/1000) == date("m/d", $emptyRemoved[$i - 1]/1000)) {
				unset($emptyRemoved[$i]);
				$emptyRemoved = array_values($emptyRemoved);
			}
		}
	}
	
	$studentDaysAttended = array_filter($emptyRemoved);
	
	$scheduledDates = array();
	$scheduledStartTimes = array();
	
	$cohortClass = "cohort" . $cohortNum . "class";
	$cohortStartTime = "cohort" . $cohortNum . "starttime";
	
	$scheduledDates1 = mysqli_query($con, "SELECT date, " . $cohortClass . ", " . $cohortStartTime ." FROM cohortschedule WHERE ".$cohortClass." != ''");
	
	while ($res1 = mysqli_fetch_assoc($scheduledDates1)){
		$scheduledDates[] = $res1['date'];
		$scheduledStartTimes[] = $res1[$cohortStartTime];
	}
	
	$s = 0;
	$daysLate = 0;
	$daysAbsent = 0;
	$daysOnTime = 0;
	$daysAttended = 0;
	for ($i = 0; $i < count($scheduledDates); ++$i){
		
		$scheduledDay = $scheduledDates[$i];
		$scheduledTime = date("H:i:s", strtotime($scheduledStartTimes[$i]));
		$studentTimeMDY = date("m/d/y", $studentDaysAttended[$s]/1000);
		$studentTimeHMS = date("H:i:s", $studentDaysAttended[$s]/1000);
		
		if (strtotime($scheduledDay)*1000 > strtotime(date("m/d/y"))*1000){
			break;
		} else if ($scheduledDay == $studentTimeMDY){
			if ($scheduledTime < $studentTimeHMS){
				$daysLate += 1;
			} else {
				$daysOnTime += 1;
			}
			$s = $s + 1;
		} else {
			$daysAbsent += 1;
		}
	}
	
	$daysAttended = $daysOnTime + $daysLate;
	
	echo "<b>Daily Attendance</b>";
	echo "</br>";
	echo "<div style='float: left;'>";
	echo "<font color='green'><b>Days On Time: " . $daysOnTime . "</b></font></br>";
	echo "<font color='orange'><b>Days Late: " . $daysLate . "</b></font></br>";
	echo "<font color='black'>Days Attended: " . $daysAttended . "</b></font></br>";
	echo "<font color='red'><b>Days Absent: " . $daysAbsent . "</b></font></br>";
	
	echo "<table border='1'>
		<thead>
		<tr>
		<th>Scheduled Dates</th>
		<th>Clocked In</th>
		<th>Comments</th>
		</tr>
		</thead>";
		
	$studentComments = array();
	$getStudentComments = mysqli_query($con, "SELECT id, date, comments FROM studentcomments WHERE studentID = $sID ORDER BY date ASC");
	while($test = mysqli_fetch_assoc($getStudentComments)){
		$values = Array();
		
		array_push($values, $test['id']);
		array_push($values, $test['date']);
		array_push($values, $test['comments']);
		array_push($studentComments, $values);
	}

	

	
	
		
	$s = 0;	
	for ($i = 0; $i < count($scheduledDates); ++$i){
		
		$scheduledDay = $scheduledDates[$i];
		$scheduledTime = date("H:i:s", strtotime($scheduledStartTimes[$i]));
		$studentTimeMDY = date("m/d/y", $studentDaysAttended[$s]/1000);
		$studentTimeHMS = date("H:i:s", $studentDaysAttended[$s]/1000);
		$studentTimeHMSA = date("h:i:s A", $studentDaysAttended[$s]/1000);
		
		if (strtotime($scheduledDay)*1000 > strtotime(date("m/d/y"))*1000){
			break;
		} else if ($scheduledDay == $studentTimeMDY){
			if ($scheduledTime < $studentTimeHMS){
				echo "<tr>";
				echo "<td class='late'><font color='orange'>". date("D, m/d/y", strtotime($scheduledDates[$i])) . "</font></td>";
				echo "<td>" . $studentTimeHMSA . "</td>";
				
				$reversedDate = date("Y-m-d", strtotime($scheduledDay));
				for ($row = 0; $row < count($studentComments); $row++) {
					if ($reversedDate == $studentComments[$row][1]){
						echo '<td><a href="studentComments_Edit.php?id='. $studentComments[$row][0] .'" title="'.  $studentComments[$row][2] .'">Edit Comment</a></td>';
					}	
				}

				echo "</tr>";
			} else {
				echo "<tr>";
				echo "<td><font color='green'>". date("D, m/d/y", strtotime($scheduledDates[$i])) . "</font></td>";
				echo "<td>" . $studentTimeHMSA . "</td>";
				
				$reversedDate = date("Y-m-d", strtotime($scheduledDay));
				for ($row = 0; $row < count($studentComments); $row++) {
					if ($reversedDate == $studentComments[$row][1]){
						echo '<td><a href="studentComments_Edit.php?id='. $studentComments[$row][0] .'" title="'.  $studentComments[$row][2] .'">Edit Comment</a></td>';
					} 	
				}
				
				echo "</tr>";
			}
			$s = $s + 1;
		} else {
			echo "<tr>";
			echo "<td class='absent'><font color='red'>". date("D, m/d/y", strtotime($scheduledDates[$i])) . "</font></td>";
			echo "<td>Absent</td>";
			
			$reversedDate = date("Y-m-d", strtotime($scheduledDay));
			for ($row = 0; $row < count($studentComments); $row++) {
				if ($reversedDate == $studentComments[$row][1]){
					echo '<td><a href="studentComments_Edit.php?id='. $studentComments[$row][0] .'" title="'.  $studentComments[$row][2] .'">Edit Comment</a></td>';
				} 		
			}
			
			echo "</tr>";
		}
	}
	echo "</table>";
	echo "</div>";
	echo "</br>";
}
mysqli_close($con);
?>