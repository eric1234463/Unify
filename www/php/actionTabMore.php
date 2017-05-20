<?php
require ("dbconn.php");
session_start();
$dbconn = dbconn();
$accountID = $_SESSION["AccountID"];
$action = $_GET['action'];
$data = array();
switch ($action) {
    case 'getFriendList':
        $sql = "SELECT 
                        b.FriendAccountID,
                        b.FriendAccountName
                FROM  account a
                LEFT JOIN friends b ON a.AccountID = b.OwnerAccountID 
                WHERE a.AccountID= '$accountID'
                ORDER BY b.FriendAccountName";
        $result = mysqli_query($dbconn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $friendAccountID = $row['FriendAccountID'];
            $sql2 = "SELECT * FROM account a WHERE  a.AccountID = '$friendAccountID'";
            $result2 = mysqli_query($dbconn, $sql2);
            while ($row2 = mysqli_fetch_assoc($result2)) {
                $Icon = $row2['Icon'];
            }
            $sql2 = "SELECT
                        c.ProgramName,
                        b.SchoolName
                     FROM account a
                     LEFT JOIN school b ON b.SchoolID = a.SchoolID
                     LEFT JOIN program c ON a.ProgramID = c.ProgramID AND a.SchoolID = c.SchoolID
                     WHERE a.AccountID = '$friendAccountID'";
            $result2 = mysqli_query($dbconn, $sql2);
            while ($row2 = mysqli_fetch_assoc($result2)) {
                $schoolName = $row2['SchoolName'];
                $programName = $row2['ProgramName'];
            }
            $data[] = array(
                    'AccountID' => $row['FriendAccountID'], 
                    'AccountName' => $row['FriendAccountName'], 
                    'SchoolName' => $schoolName, 
                    'ProgramName' => $programName,
                    'Icon' => $Icon
                    );
        }
        echo json_encode($data);
        break;

    case 'removeFriend':
        $friendAccountID = $_GET['friendAccountID'];
        $sql = "DELETE FROM friends WHERE OwnerAccountID ='$friendAccountID' AND friendAccountID ='$accountID'";
        $result = mysqli_query($dbconn, $sql);
        $sql = "DELETE FROM friends WHERE OwnerAccountID ='$accountID' AND friendAccountID ='$friendAccountID'";
        $result = mysqli_query($dbconn, $sql);
        break;

    case 'searchFriend':
        $keyword = $_GET['keyword'];
        $sql = "SELECT 
                        b.FriendAccountID,
                        b.FriendAccountName 
                FROM  account a
                LEFT JOIN friends b ON a.AccountID = b.OwnerAccountID
                WHERE a.AccountID= '$accountID' AND b.FriendAccountName LIKE '%$keyword%'";
        $result = mysqli_query($dbconn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $friendAccountID = $row['FriendAccountID'];
            $sql2 = "SELECT * FROM account a WHERE  a.AccountID = '$friendAccountID'";
            $result2 = mysqli_query($dbconn, $sql2);
            while ($row2 = mysqli_fetch_assoc($result2)) {
                $Icon = $row2['Icon'];
            }
            $sql2 = "SELECT
                        c.ProgramName,
                        b.SchoolName
                     FROM account a
                     LEFT JOIN school b ON a.SchoolID = b.SchoolID
                     LEFT JOIN program c ON a.ProgramID = c.ProgramID
                     WHERE a.AccountID = '$friendAccountID'";
            $result2 = mysqli_query($dbconn, $sql2);
            while ($row2 = mysqli_fetch_assoc($result2)) {
                $schoolName = $row2['SchoolName'];
                $programName = $row2['ProgramName'];
            }
            $data[] = array(
                    'AccountID' => $row['FriendAccountID'], 
                    'AccountName' => $row['FriendAccountName'], 
                    'SchoolName' => $schoolName, 
                    'ProgramName' => $programName,
                    'Icon' => $Icon
                    );
        }
        echo json_encode($data);
        break;

    case 'getGossip':
        $sql = "SELECT 
                    b.SecretID,
                    b.SecretName,
                    b.Icon
               FROM accountsecret a
               LEFT JOIN secret b ON a.SecretID = b.SecretID
               WHERE a.AccountID ='$accountID'";
        $result = mysqli_query($dbconn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $data[]=array('GossipID'=> $row['SecretID'],'GossipName'=>$row['SecretName'],'Icon'=>$row['Icon']);
        }
        echo json_encode($data);
        break;
    case 'deleteGossip':
        $gossipID = $_GET['gossipID'];
        $sql = "DELETE FROM accountsecret WHERE SecretID='$gossipID' AND AccountID ='$accountID'";
        $result = mysqli_query($dbconn, $sql);
        break;

     case 'setting':
        $private = $_GET['private'];
        $sql = "UPDATE account SET Private= '$private' WHERE AccountID = '$accountID'";
        $result = mysqli_query($dbconn, $sql);
        break;

    case 'getNote':
        $sql = "SELECT * FROM accountnote a WHERE a.AccountID = '$accountID' ORDER BY a.NoteType";
        $result = mysqli_query($dbconn, $sql);
        $i = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            if($i == 0){
                $NoteType = $row['NoteType'];
                $data[]=array(
                    'AccountID'=> $row['AccountID'],
                    'NoteTitle'=>$row['NoteTitle'],
                    'NoteID'=>$row['NoteID'],
                    'NoteType'=>$row['NoteType'],
                    'TypeChecker'=> '1',
                );
            }else{
                if($NoteType !== $row['NoteType']){
                    $TypeChecker = '1';
                    $NoteType = $row['NoteType'];
                    $data[]=array(
                        'AccountID'=> $row['AccountID'],
                        'NoteTitle'=>$row['NoteTitle'],
                        'NoteID'=>$row['NoteID'],
                        'NoteType'=>$row['NoteType'],
                        'TypeChecker'=> '1'
                    );
                }
            }
            $i++;
            $data[]=array(
                'AccountID'=> $row['AccountID'],
                'NoteTitle'=>$row['NoteTitle'],
                'NoteID'=>$row['NoteID'],
                'NoteType'=>$row['NoteType'],
                'TypeChecker'=> '0'
                );
        }
        echo json_encode($data);
        break;
    case 'getNoteDetail':
        $NoteID = $_GET['NoteID'];
        $sql = "SELECT * FROM note a WHERE a.NoteID = '$NoteID'";
        $result = mysqli_query($dbconn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $data[]=array('NoteTitle'=>$row['NoteTitle'],'NoteID'=>$row['NoteID'],'NoteType'=>$row['NoteType'],'NoteDesc'=>$row['NoteDesc']);
        }
        echo json_encode($data);
        break;
    case 'UpdateNote':
        $NoteID = $_GET['NoteID'];
        $NoteDesc = $_GET['NoteDesc'];
        $sql = "UPDATE note SET NoteDesc = '$NoteDesc' WHERE NoteID = '$NoteID'";
        $result = mysqli_query($dbconn, $sql);
        break;
    case 'CreateNote':
        $NoteTitle = $_GET['NoteTitle'];
        $NoteDesc = $_GET['NoteDesc'];
        $NoteType = $_GET['NoteType'];
        $sql = "INSERT INTO note (NoteTitle,NoteType,NoteDesc) VALUES ('$NoteTitle','$NoteType','$NoteDesc')";
        $result = mysqli_query($dbconn, $sql);
        $sql = "SELECT * FROM note a WHERE NoteTitle = '$NoteTitle' AND NoteType = '$NoteType'";
        $result = mysqli_query($dbconn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $NoteID = $row['NoteID'];
        };
        $sql = "INSERT INTO accountnote (AccountID,NoteID,NoteTitle,NoteType,CreateDt,CreateBy) VALUES ('$accountID','$NoteID','$NoteTitle','$NoteType',NOW(),'Admin')";
        echo $sql;
        $result = mysqli_query($dbconn, $sql);
        break;
    case 'shareNote':
        $NoteID = $_GET['NoteID'];
        $NoteTitle = $_GET['NoteTitle'];
        $NoteType = $_GET['NoteType'];
        $AccountID = $_GET['AccountID'];
        $sql = "INSERT INTO accountnote (AccountID,NoteID,NoteTitle,NoteType,CreateDt,CreateBy) VALUES ('$AccountID','$NoteID','$NoteTitle','$NoteType',NOW(),'Admin')";
        $result = mysqli_query($dbconn, $sql);
        break;
    case 'getJob':
        $sql = "SELECT * FROM job ORDER BY CreateDt DESC";
        $result = mysqli_query($dbconn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $data[]=array(
                    'JobID'=>$row['JobID'],
                    'JobDesc'=>$row['JobDesc'],
                    'JobTitle'=>$row['JobTitle'],
                    'JobType'=>$row['JobType'],
                    'CreateDt'=>$row['CreateDt'],
                    'Email'=>$row['Email']
                );
        }
        echo json_encode($data);
        break;
    case 'searchJob':
        $keyword = $_GET['keyword'];
        $sql = "SELECT * FROM job  WHERE JobTitle LIKE '%$keyword%' OR JobType LIKE '%$keyword%' ORDER BY CreateDt DESC";
        $result = mysqli_query($dbconn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $data[]=array(
                    'JobID'=>$row['JobID'],
                    'JobDesc'=>$row['JobDesc'],
                    'JobTitle'=>$row['JobTitle'],
                    'JobType'=>$row['JobType'],
                    'CreateDt'=>$row['CreateDt'],
                    'Email'=>$row['Email']
                );
        }
        echo json_encode($data);
        break;
    case 'showSecondHandBook':
        $sql = "SELECT 
                    a.OwnerAccountID,
                    b.AccountName,
                    a.Desc,
                    a.Name,
                    a.CreateDt,
                    a.Price,
                    a.PhotoSrc
                 FROM secondhandbook a
                LEFT JOIN account b ON a.OwnerAccountID = b.AccountID  
                ORDER BY a.CreateDt DESC";
        $result = mysqli_query($dbconn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $data[]=array(
                    'OwnerAccountID'=>$row['OwnerAccountID'],
                    'AccountName'=>$row['AccountName'],
                    'Desc'=>$row['Desc'],
                    'Name'=>$row['Name'],
                    'Time'=>$row['CreateDt'],
                    'Price'=>$row['Price'],
                    'PhotoSrc'=>$row['PhotoSrc']
                );
        }
        echo json_encode($data);
        break;

}

mysqli_close($dbconn);
?>
