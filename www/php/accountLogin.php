<?php
require ("dbconn.php");
session_start();
$dbconn = dbconn();
if (!$dbconn) {
    die("dbconnection failed: " . mysqli_dbconnect_error());
} 
else {
    if (isset($_GET['getAccountinfo'])) {
        $data=array(
                "AccountID" => $_SESSION["AccountID"],
                "AccountName" => $_SESSION["AccountName"],
                "SchoolID" => $_SESSION["SchoolID"],
                "Icon" => $_SESSION["Icon"],
                "Private" => $_SESSION["Private"]

        );
        echo json_encode($data);
    } 
    else {
        $postdata = file_get_contents("php://input");
        if (isset($postdata)) {
            $request = json_decode($postdata);
            $accountName = $request->accountName;
            $password = $request->password;
            //$device = $request->device;
            if ($accountName != "") {
                $sql = "SELECT 
								AccountName,
								AccountPassword,
								AccountID,
                                SchoolID,
                                Icon,
                                Private
						FROM account 
						WHERE AccountName = '$accountName' AND AccountPassword = '$password'";
                $result = mysqli_query($dbconn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    $AccountID = $row["AccountID"];
                    $_SESSION["AccountID"] = $row["AccountID"];
                    $_SESSION["AccountName"] = $row["AccountName"];
                    $_SESSION["SchoolID"] = $row["SchoolID"];
                    $_SESSION["Icon"] = $row["Icon"];
                    $_SESSION["Private"] = $row["Private"];
                    //$sql2 = "UPDATE account SET Device ='$device' WHERE AccountID = '$AccountID'";
                    //$result2 = mysqli_query($dbconn, $sql2);
                    echo "Pass:" . $row['AccountID'];
                }
            } 
            else {
                echo "Error";
            }
        };
    }
}
mysqli_close($dbconn);
?>
