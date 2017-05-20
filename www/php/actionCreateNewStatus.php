<?php
require ("dbconn.php");
session_start();
$dbconn = dbconn();
$accountID = $_SESSION["AccountID"];
$accountName = $_SESSION["AccountName"];
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$action = $request->action;
switch ($action) {
    case 'create_status':
        $newStatus = $request->newStatus;
        echo $newStatus;
        $newStatus = htmlentities($newStatus, ENT_QUOTES, "UTF-8");
        echo $newStatus;
        $sql = "UPDATE idlist
                SET `number` = `number`   +   1 
                WHERE YearOfEntity = YEAR(CURDATE()) AND datatype='S'
                ";
        $result = mysqli_query($dbconn, $sql);
        $sql = "SELECT 
                        CONCAT(systemName,'-',datatype,'-',YearOfEntity,'-',LPAD(`number`, 7, '0')) As StatusID 
                FROM idlist 
                WHERE YearOfEntity = YEAR(CURDATE()) AND datatype='S'
      ";
        $result = mysqli_query($dbconn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $statusID = $row['StatusID'];
        }
        $sql = "INSERT INTO 
                            `status` (StatusID,OwnerAccountID,OwnerAccountName,Status,CreateDt,CreateBy,UpdateDt,UpdateBy,PhotoSrc)
                VALUES
                            ('$statusID','$accountID','$accountName','$newStatus',NOW(),'Admin',NOW(),'Admin','N')";
        $result = mysqli_query($dbconn, $sql);
        if(isset($request->img)){
            $img = $request->img;
            $img = str_replace('data:image/jpeg;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file = "../../photo/status/".$statusID.".jpg";
            $success = file_put_contents($file, $data);
            $realFilaPath = "http://unicomhk.net/fyp/photo/status/".$statusID.".jpg";
            $sql = "UPDATE `status` SET PhotoSrc = '$realFilaPath' WHERE StatusID='$statusID'";
            $result = mysqli_query($dbconn, $sql);
        }
        break;
    case 'create_gossip':
        $gossipID =  $request->ID;
        $newStatus = $request->newStatus;
        $sql = "INSERT INTO 
                        secretmessage (SecretID,SecretMessage,CreateDt,CreateBy,UpdateDt,UpdateBy,PhotoSrc)
               VALUES
                        ('$gossipID','$newStatus',NOW(),'Admin',NOW(),'Admin','N')";
        $result = mysqli_query($dbconn, $sql);
        if(isset($request->img)){
            $sql = "SELECT * FROM secretmessage ORDER BY CreateDt DESC LIMIT 1";
            $result = mysqli_query($dbconn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                $SecretMessageID = $row['SecretMessageID'];
            }
            $img = $request->img;
            $img = str_replace('data:image/jpeg;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file = "../../photo/gossip/".$gossipID."_".$SecretMessageID."_status.jpg";
            $success = file_put_contents($file, $data);
            $realFilaPath = "http://unicomhk.net/fyp/photo/gossip/".$gossipID."_".$SecretMessageID."_status.jpg";
            $sql = "UPDATE `secretmessage` SET PhotoSrc = '$realFilaPath' WHERE SecretID='$gossipID' AND SecretMessageID = '$SecretMessageID'";
            $result = mysqli_query($dbconn, $sql);
        }
        break;
}
mysqli_close($dbconn);
?>
