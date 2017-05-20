<?php
session_start();
require ("dbconn.php");
$dbconn = dbconn();
$deivce = $_GET['uuid'];
$sql = "SELECT * FROM account WHERE Device = '$deivce'";
$result = mysqli_query($dbconn, $sql);
 while ($row = mysqli_fetch_assoc($result)) {
    $_SESSION["AccountID"] = $row["AccountID"];
    $_SESSION["AccountName"] = $row["AccountName"];
    $_SESSION["SchoolID"] = $row["SchoolID"];
    echo "success";
}

mysqli_close($dbconn);

?>
