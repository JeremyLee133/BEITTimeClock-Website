<?php
 
/*
 * Following code will create a new product row
 * All product details are read from HTTP Post Request
 */
 
// array for JSON response
$response = array();
 
// check for required fields
//if (isset($_POST['name']) && isset($_POST['price']) && isset($_POST['description'])) {
	if (isset($_POST['studentID']) && isset($_POST['firstName']) && isset($_POST['lastName']) && isset($_POST['cohortNumber']) && isset($_POST['time']) && isset($_POST['in_out']) && isset($_POST['roomNumber'])) {
 
    $studentID = $_POST['studentID'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
	$cohortNumber = $_POST['cohortNumber'];
	$time = $_POST['time'];
    $in_out = $_POST['in_out'];
	$roomNumber = $_POST['roomNumber'];
 
    // include db connect class
    //require_once __DIR__ . '/db_connect.php';
 
    // connecting to db
    //$db = new DB_CONNECT();
	include("dbcon.php");
//	or die(mysql_error());
 
    // mysql inserting a new row
    $result = mysqli_query($con, "INSERT INTO cohortlog(studentID, firstName, lastName, cohortNumber, time, in_out, roomNumber) VALUES('$studentID', '$firstName', '$lastName', '$cohortNumber', '$time', '$in_out', '$roomNumber')");
 
    // check if row inserted or not
    if ($result) {
        // successfully inserted into database
        $response["success"] = 1;
        $response["message"] = "Log entry successfully created.";
 
        // echoing JSON response
        echo json_encode($response);
    } else {
        // failed to insert row
        $response["success"] = 0;
        $response["message"] = "Oops! An error occurred.";
 
        // echoing JSON response
        echo json_encode($response);
    }
} else {
    // required field is missing
    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing";
 
    // echoing JSON response
    echo json_encode($response);
}
?>