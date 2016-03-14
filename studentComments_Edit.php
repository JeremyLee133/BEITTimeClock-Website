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

if (isset($_GET["id"])){
	$id = $_GET["id"];
	
	
	
	$getStudentComments = mysqli_query($con, "SELECT studentID, date, comments FROM studentcomments WHERE id = $id");
	while($res = mysqli_fetch_assoc($getStudentComments)){
		$sID = $res['studentID'];
		$date = date("m/d/y", strtotime($res['date']));
		$comments = $res['comments'];
	}
	
	echo "<a href='studentComments_Add.php?sID=".$sID."'>Return To Student Comments</a>";
	echo "</br>";
	echo "</br>";
	
	echo "Edit Comment";
	echo "<form id='studentComments_Edit' action='studentComments_Edit.php?id=".$id."' method='post'>";
	echo "<input type='text' name='date' value= $date />";
	echo "</br>";
	echo "<textarea name='comments' id='comments' cols='60' rows='8'>".$comments."</textarea>";
	echo "</br>";
	echo "<input type='hidden' name='studentID' value='" .$sID."'/>";
	echo "<input type='hidden' name='id' value='" .$id."'/>";
	echo "<input type='submit' name='edit' value='Save Edit'>";
	echo "</br></br>";
	echo "<input type='submit' name='delete' value='Delete'>";
	
	echo "</form>";
}

if ((isset($_POST['edit'])) && $_POST["date"] != "" && $_POST["comments"] != ""){
	$id = $_REQUEST['id'];
	$studentID = $_REQUEST['studentID'];
	$date = date("Y-m-d", strtotime($_REQUEST['date']));
	$studentComment = $_REQUEST['comments'];
	
	$editStudentComment = mysqli_query($con, "UPDATE studentcomments SET studentID = '$studentID', date = '$date', comments = '$studentComment' WHERE id = '$id'");
	header("Location: studentComments_Add.php?sID=$studentID");
} else if(isset($_POST['delete'])){
	$id = $_REQUEST['id'];
	$studentID = $_REQUEST['studentID'];
	
	$deleteStudentComment = mysqli_query($con, "DELETE FROM studentcomments WHERE id = '$id'");
	header("Location: studentComments_Add.php?sID=$studentID");
}

?>
</body>
</html>