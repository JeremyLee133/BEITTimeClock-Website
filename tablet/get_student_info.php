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

// get all products from products table
$result = mysqli_query($con, "SELECT * FROM cohortcurrent WHERE studentID = $studentID");

// or die(mysql_error());
 	
if (!empty($result)) {
        // check for empty result
        if (mysqli_num_rows($result) > 0) {
 
            $result = mysqli_fetch_array($result);
 
            $cohort = array();
            $cohort["_id"] = $result["_id"];
            $cohort["studentID"] = $result["studentID"];
            $cohort["firstName"] = $result["firstName"];
            $cohort["lastName"] = $result["lastName"];
            $cohort["cohortNumber"] = $result["cohortNumber"];
            $cohort["time"] = $result["time"];
			$cohort["in_out"] = $result["in_out"];
			$cohort["roomNumber"] = $result["roomNumber"];
            // success
            $response["success"] = 1;
 
            // user node
            $response["cohort"] = array();
 
            array_push($response["cohort"], $cohort);
 
            // echoing JSON response
            echo json_encode($response);
        } else {
            // no cohort found
            $response["success"] = 0;
            $response["message"] = "No cohort found";
 
            // echo no users JSON
            echo json_encode($response);
        }
    } else {
        // no cohort found
        $response["success"] = 0;
        $response["message"] = "No cohort found";
 
        // echo no users JSON
        echo json_encode($response);
    }
//} else {
    // required field is missing
 //   $response["success"] = 0;
  //  $response["message"] = "Required field(s) is missing";
 
    // echoing JSON response
  //  echo json_encode($response);
//}

?>