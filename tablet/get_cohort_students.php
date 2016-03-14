<?php
$response = array();

include("dbcon.php");

if (isset($_GET["strCohortNumber"])){
	$strCohortNumber = $_GET['strCohortNumber'];
}

$result = mysqli_query($con, "SELECT * FROM cohortcurrent WHERE cohortNumber = $strCohortNumber AND in_out = 'In' ORDER BY time ASC");
 	
if (mysqli_num_rows($result) > 0) {
    $response["cohortcurrent"] = array();
    while ($row = mysqli_fetch_array($result)) {
        $cohort = array();
        $cohort["_id"] = $row["_id"];
        $cohort["studentID"] = $row["studentID"];
        $cohort["firstName"] = $row["firstName"];
        $cohort["lastName"] = $row["lastName"];
        $cohort["cohortNumber"] = $row["cohortNumber"];
		$cohort["time"] = $row["time"];
		$cohort["in_out"] = $row["in_out"];
		$cohort["roomNumber"] = $row["roomNumber"];
 
        array_push($response["cohortcurrent"], $cohort);
    }
    $response["success"] = 1;
 
    echo json_encode($response);
} else {
    $response["success"] = 0;
    $response["message"] = "No students found";

    echo json_encode($response);
}
?>