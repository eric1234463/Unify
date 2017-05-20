<?php
require("dbconn.php");
$dbconn = dbconn();

if (!$dbconn) {
    die("dbconnection failed: " . mysqli_dbconnect_error());
}
$datatype = $_GET['datatype'];

if (isset($datatype)) {
	$data = array();
	switch ($datatype) {
		case 'school':
			$sql = 
			"SELECT 
				SchoolName,
				SchoolID 
			FROM school";
			$result = mysqli_query($dbconn, $sql);
			while($row = mysqli_fetch_assoc($result)) {
				$data[] = array('SchoolID' => $row['SchoolID'],'SchoolName' => $row['SchoolName']);
			}
			echo json_encode($data); 

			break;
		case 'study':
			$SchoolID = $_GET['SchoolID'];
			$sql = 
			"SELECT 
				ProgramID,
				ProgramName 
			FROM program
			WHERE SchoolID = '$SchoolID'";
			$result = mysqli_query($dbconn, $sql);
			while($row = mysqli_fetch_assoc($result)) {
				$data[] = array('ProgramID' => $row['ProgramID'],'ProgramName' => $row['ProgramName']);
			}
			echo json_encode($data); 
			break;

		case 'email':
			$SchoolID = $_GET['SchoolID'];
			$sql = 
			"SELECT 
				Domain
			FROM `email`
			WHERE EmailID = '$SchoolID'";
			$result = mysqli_query($dbconn, $sql);
			while($row = mysqli_fetch_assoc($result)) {
				echo $row['Domain'];
			}
			break;
	}
}
mysqli_close($dbconn);
?>
