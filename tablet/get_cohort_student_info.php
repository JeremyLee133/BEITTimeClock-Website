<?php
 
/*
 * Following code will list all the products
 */

// array for JSON response
$response = array();

// include db connect class
//require_once __DIR__ . '/db_connect.php';
 
// connecting to db
//$db = new DB_CONNECT();

include("dbcon.php");
//or die(mysql_error());

if (isset($_GET["studentID"])){
	$studentID = $_GET['studentID'];
}

//$strCohortNumber = "2";
    
// get all products from products table
$result = mysqli_query($con, "SELECT * FROM cohortlog WHERE studentID = $studentID ORDER BY time ASC");
// or die(mysql_error());
 	
// check for empty result
if (mysqli_num_rows($result) > 0) {

    // looping through all results
    // products node
    $response["cohortcurrent"] = array();
 
    while ($row = mysqli_fetch_array($result)) {
        // temp user array
        $cohort = array();
        $cohort["_id"] = $row["_id"];
        $cohort["studentID"] = $row["studentID"];
        $cohort["firstName"] = $row["firstName"];
        $cohort["lastName"] = $row["lastName"];
        $cohort["cohortNumber"] = $row["cohortNumber"];
		$cohort["time"] = $row["time"];
		$cohort["in_out"] = $row["in_out"];
		$cohort["roomNumber"] = $row["roomNumber"];
 
        // push single product into final response array
        array_push($response["cohortcurrent"], $cohort);
    }
    // success
    $response["success"] = 1;
 
    // echoing JSON response
    echo json_encode($response);

} else {

    // no products found
    $response["success"] = 0;
    $response["message"] = "No students found";
 
    // echo no users JSON
    echo json_encode($response);
}


?>