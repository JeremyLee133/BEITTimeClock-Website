<?php
 
/*
 * Following code will update a product information
 * A product is identified by product id (pid)
 */
 
// array for JSON response
$response = array();
 
// check for required fields
if (isset($_POST['studentID']) && isset($_POST['time']) && isset($_POST['in_out']) && isset($_POST['roomNumber'])) {
 
    $studentID = $_POST['studentID'];
	$time = $_POST['time'];
	$in_out = $_POST['in_out'];
	$roomNumber = $_POST['roomNumber'];
 
    // include db connect class
    //require_once __DIR__ . '/db_connect.php';
 
    // connecting to db
    //$db = new DB_CONNECT();
	include("dbcon.php");
//	or die(mysql_error());
 
    // mysql update row with matched pid
    $result = mysqli_query($con, "UPDATE cohortcurrent SET time = '$time', in_out = '$in_out', roomNumber = '$roomNumber' WHERE studentID = $studentID");
 
    // check if row inserted or not
    if ($result) {
        // successfully updated
        $response["success"] = 1;
        $response["message"] = "Product successfully updated.";
 
        // echoing JSON response
        echo json_encode($response);
    } else {
 
    }
} else {
    // required field is missing
    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing";
 
    // echoing JSON response
    echo json_encode($response);
}
?>