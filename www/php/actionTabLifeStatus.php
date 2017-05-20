<?php
require ("dbconn.php");
session_start();
$dbconn = dbconn();
$accountID = $_SESSION["AccountID"];
$accountName = $_SESSION["AccountName"];
$action = $_GET['action'];
date_default_timezone_set('Asia/Hong_Kong');
$data = Array();
switch ($action) {
    case 'getdata':
        $sql = "SELECT 
                            c.OwnerAccountID,
                            c.OwnerAccountName , 
                            c.CreateDt,
                            c.`Status`,
                            c.`StatusID`,
                            c.PhotoSrc
                FROM account a
                LEFT JOIN friends b ON a.AccountID = b.OwnerAccountID
                LEFT JOIN `status` c ON b.FriendAccountID = c.OwnerAccountID
                WHERE  
                    a.AccountID = '$accountID' AND c.`Status` <> ''
                UNION
                SELECT 
                            e.OwnerAccountID,
                            e.OwnerAccountName , 
                            e.CreateDt,
                            e.`Status`,
                            e.`StatusID`,
                            e.PhotoSrc
                FROM account d
                LEFT JOIN `status` e ON d.AccountID = e.OwnerAccountID 
                LEFT JOIN school f ON f.SchoolID = d.SchoolID
                LEFT JOIN program g ON g.SchoolID = f.SchoolID
                WHERE  
                    d.AccountID = '$accountID' AND e.`Status` <> ''
                ORDER BY CreateDt DESC";
        $result = mysqli_query($dbconn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $edit ="";
            $liked = 0;
            $disLiked = 0;
            $statusID = $row['StatusID'];
            $statusAccountID = $row['OwnerAccountID'];
            $comment = 0;
            $sql2  = "SELECT * 
                    FROM account a 
                    LEFT JOIN school b ON a.SchoolID = b.SchoolID
                    LEFT JOIN program c ON a.ProgramID = c.ProgramID AND a.SchoolID = c.SchoolID
                    WHERE a.AccountID = '$statusAccountID'";
            $result2 = mysqli_query($dbconn, $sql2);
            while ($row2 = mysqli_fetch_assoc($result2)) {
                $SchoolName = $row2['SchoolName'];
                $ProgramName = $row2['ProgramName'];
            }

            $sql2 = "SELECT 
                            a.Icon
                    FROM 
                            account a
                    WHERE  
                            a.AccountID = '$statusAccountID'";
            $result2 = mysqli_query($dbconn, $sql2);
            while ($row2 = mysqli_fetch_assoc($result2)) {
                $Icon = $row2['Icon'];
            }
        
            $sql2 = "SELECT 
                            COUNT(a.Liked) AS `Liked`
                    FROM 
                            emotion a
                    WHERE  
                            a.StatusID = '$statusID'
                    AND
                            a.Liked = 'Y'";
            $result2 = mysqli_query($dbconn, $sql2);
            if ($result2) {
                while ($row2 = mysqli_fetch_assoc($result2)) {
                    $liked = $row2['Liked'];
                }
            }
            $sql3 = "SELECT 
                            COUNT(a.Liked) AS `DisLiked`
                    FROM 
                            emotion a
                    WHERE  
                            a.StatusID = '$statusID'
                    AND
                            a.Liked = 'N'";
            $result3 = mysqli_query($dbconn, $sql3);
            if ($result3) {
                while ($row3 = mysqli_fetch_assoc($result3)) {
                    $disLiked = $row3['DisLiked'];
                }
            }
            $sql4 = "SELECT 
                                    COUNT(a.`Comment`) AS `Comment`
                    FROM comment a
                    WHERE  
                                a.StatusID = '$statusID'";
            $result4 = mysqli_query($dbconn, $sql4);
            if ($result4) {
                while ($row4 = mysqli_fetch_assoc($result4)) {
                    $comment = $row4['Comment'];
                }
            }
            $sql5 = "SELECT 
                                    COUNT(a.Liked) AS `YouLike`
                    FROM emotion a
                    WHERE  
                                a.StatusID = '$statusID' AND a.likeAccountID = '$accountID' AND a.Liked = 'Y'";
            $result5 = mysqli_query($dbconn, $sql5);
            if ($result5) {
                while ($row5 = mysqli_fetch_assoc($result5)) {
                    if ($row5['YouLike'] !== '0') {
                        $youLike = "Y";
                    } 
                    else {
                        $youLike = "N";
                    }
                }
            }
            $sql6 = "SELECT 
                                    COUNT(a.Liked) AS `YouDisLike`
                     FROM emotion a
                     WHERE  
                                a.StatusID = '$statusID' AND a.likeAccountID = '$accountID' AND a.Liked = 'N'";
            $result6 = mysqli_query($dbconn, $sql6);
            if ($result6) {
                while ($row6 = mysqli_fetch_assoc($result6)) {
                    if ($row6['YouDisLike'] !== '0') {
                        $youDislike = "Y";
                    } 
                    else {
                        $youDislike = "N";
                    }
                }
            }
            if($accountID == $row['OwnerAccountID']){
                $edit = 'Y';
            }else{
                $edit = 'N';
            }
            $statusID = $row['StatusID'];
            $commentList = Array();
            $sql1 = "SELECT  
                        a.CommentAccountName , 
                        a.`Comment`,
                        a.CommentAccountID
                    FROM comment a
                    LEFT JOIN status b on b.statusID = a.statusID 
                    WHERE a.StatusID = '$statusID'
                    LIMIT 5 
                    ";
            $result1 = mysqli_query($dbconn, $sql1);
                while ($row1 = mysqli_fetch_assoc($result1)) {
                    $CommentAccountID = $row1['CommentAccountID'];
                    $sql2 = "SELECT * FROM account WHERE AccountID = '$CommentAccountID'";
                    $result2 = mysqli_query($dbconn, $sql2);
                    while ($row2 = mysqli_fetch_assoc($result2)) {
                        $CommentIcon = $row2['Icon'];
                    }
                    $commentList[] = array('CommentAccountName' => $row1['CommentAccountName'], 'Comment' => $row1['Comment'],'Icon'=>$CommentIcon);
                }
            $data[] = array(
                        'StatusID' => $row['StatusID'],
                        'ICON'=>$Icon,
                        'Photo'=>$row['PhotoSrc'],
                        'showComment'=>'N',
                        'commentList'=>$commentList,
                        'ProgramName' => $ProgramName,
                        'SchoolName' => $SchoolName,
                        'AccountID' => $row['OwnerAccountID'], 
                        'AccountName' => $row['OwnerAccountName'], 
                        'Time' => $row['CreateDt'], 
                        'Status' => htmlspecialchars_decode($row['Status']), 
                        'Liked' => $liked, 
                        'DisLiked' => $disLiked, 
                        'Comment' => $comment, 
                        'YouLike' => $youLike, 
                        'YouDisLike' => $youDislike,
                        'Edit'=> $edit,
                        'ShowMore'=>'1'
                         );
        }
        echo json_encode($data);
        break;

    case 'add_like':
        $statusID = $_GET['statusID'];
        $statusAccountID = $_GET['statusAccountID'];
        $statusAccountName = $_GET['statusAccountName'];
        $sql = "INSERT INTO 
                            `emotion` (StatusID,likeAccountID,likeAccountName,Liked,CreateDt,CreateBy,UpdateDt,UpdateBy)
                VALUES
                            ('$statusID','$accountID','$accountName','Y',NOW(),'Admin',NOW(),'Admin')
                        ";
        $result = mysqli_query($dbconn, $sql);
        $sql = "UPDATE idlist
                SET `number` = `number`   +   1 
                WHERE YearOfEntity = YEAR(CURDATE()) AND datatype='NC'
                ";
        $result = mysqli_query($dbconn, $sql);
        $sql = "SELECT 
                        CONCAT(systemName,'-',datatype,'-',YearOfEntity,'-',LPAD(`number`, 7, '0')) As NotificationID 
                FROM idlist 
                WHERE YearOfEntity = YEAR(CURDATE()) AND datatype='NC'
        ";
        $result = mysqli_query($dbconn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $notificationID = $row['NotificationID'];
        }
        $result = mysqli_query($dbconn, $sql);
        $sql = "INSERT INTO 
                            `accountnotification` (NotificationID,OwnerAccountID,FriendAccountID,FriendAccountName,NotificationTypeID,NotificationInformationID,CreateDt,CreateBy,UpdateDt,UpdateBy)
                VALUES
                            ('$notificationID','$statusAccountID','$accountID','$accountName','UNI-NT-2016-00001','$statusID',NOW(),'Admin',NOW(),'Admin')
                        ";
        $result = mysqli_query($dbconn, $sql);
        break;

    case 'delete_like':
        $statusID = $_GET['statusID'];
        $statusAccountID = $_GET['statusAccountID'];
        $statusAccountName = $_GET['statusAccountName'];
        $sql = "DELETE  FROM `emotion`
                WHERE statusID = '$statusID' AND likeAccountID = '$accountID' AND Liked = 'Y'
                        ";
        $result = mysqli_query($dbconn, $sql);
        break;

    case 'add_dislike':
        $statusID = $_GET['statusID'];
        $statusAccountID = $_GET['statusAccountID'];
        $statusAccountName = $_GET['statusAccountName'];
        $sql = "INSERT INTO 
                            `emotion` (StatusID,likeAccountID,likeAccountName,Liked,CreateDt,CreateBy,UpdateDt,UpdateBy)
                VALUES
                            ('$statusID','$accountID','$accountName','N',NOW(),'Admin',NOW(),'Admin')
                        ";
        $result = mysqli_query($dbconn, $sql);
        $sql = "UPDATE idlist
                SET `number` = `number`   +   1 
                WHERE YearOfEntity = YEAR(CURDATE()) AND datatype='NC'
                ";
        $result = mysqli_query($dbconn, $sql);
        $sql = "SELECT 
                        CONCAT(systemName,'-',datatype,'-',YearOfEntity,'-',LPAD(`number`, 7, '0')) As NotificationID 
                FROM idlist 
                WHERE YearOfEntity = YEAR(CURDATE()) AND datatype='NC'
        ";
        $result = mysqli_query($dbconn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $notificationID = $row['NotificationID'];
        }
        $result = mysqli_query($dbconn, $sql);
        $sql = "INSERT INTO 
                            `accountnotification` (NotificationID,OwnerAccountID,FriendAccountID,FriendAccountName,NotificationTypeID,NotificationInformationID,CreateDt,CreateBy,UpdateDt,UpdateBy)
                VALUES
                            ('$notificationID','$statusAccountID','$accountID','$accountName','UNI-NT-2016-00002','$statusID',NOW(),'Admin',NOW(),'Admin')
                ";
        $result = mysqli_query($dbconn, $sql);
        break;

    case 'getStatusLike':
        $statusID = $_GET['statusID'];
        $sql ="SELECT 
                    a.LikeAccountID,
                    a.LikeAccountName,
                    b.Icon   
                From emotion a 
                LEFT JOIN account b ON a.LikeAccountID = b.AccountID
                WHERE a.StatusID = '$statusID' AND `Liked`='Y'";
        $result = mysqli_query($dbconn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
             $LikeAccountID = $row['LikeAccountID'];
            $sql2 ="SELECT * 
                    FROM account a
                    LEFT JOIN school b ON a.SchoolID = b.SchoolID
                    LEFT JOIN program c ON a.ProgramID = c.ProgramID AND a.SchoolID = c.SchoolID
                    WHERE a.AccountID = '$LikeAccountID'";
            $result2 = mysqli_query($dbconn, $sql2);
            while ($row2 = mysqli_fetch_assoc($result2)) {
                $SchoolName = $row2['SchoolName'];
                $ProgramName = $row2['ProgramName'];
            }
            $data[] = Array(
                    'LikeAccountID' => $row['LikeAccountID'],
                    'LikeAccountName'=>$row['LikeAccountName'],
                    'SchoolName' => $SchoolName,
                    'ProgramName' => $ProgramName,
                    'Icon' => $row['Icon']
                );
        }
        echo json_encode($data);
        break;

    case 'getStatusDisLike':
        $statusID = $_GET['statusID'];
        $sql ="SELECT 
                    a.LikeAccountID,
                    a.LikeAccountName,
                    b.Icon   
                From emotion a 
                LEFT JOIN account b ON a.LikeAccountID = b.AccountID
                WHERE a.StatusID = '$statusID' AND `Liked`='N'";
        $result = mysqli_query($dbconn, $sql);
         while ($row = mysqli_fetch_assoc($result)) {
            $LikeAccountID = $row['LikeAccountID'];
            $sql2 ="SELECT * 
                    FROM account a
                    LEFT JOIN school b ON a.SchoolID = b.SchoolID
                    LEFT JOIN program c ON a.ProgramID = c.ProgramID AND a.SchoolID = c.SchoolID
                    WHERE a.AccountID = '$LikeAccountID'";
            $result2 = mysqli_query($dbconn, $sql2);
            while ($row2 = mysqli_fetch_assoc($result2)) {
                $SchoolName = $row2['SchoolName'];
                $ProgramName = $row2['ProgramName'];
            }

            $data[] = Array(
                'LikeAccountID' => $row['LikeAccountID'],
                'LikeAccountName'=>$row['LikeAccountName'],
                'SchoolName' => $SchoolName,
                'ProgramName' => $ProgramName,
                'Icon' => $row['Icon']
                );
        }
        echo json_encode($data);
        break;

    case 'delete_dislike':
        $statusID = $_GET['statusID'];
        $statusAccountID = $_GET['statusAccountID'];
        $statusAccountName = $_GET['statusAccountName'];
        $sql = "DELETE  FROM `emotion`
                         WHERE statusID = '$statusID' AND likeAccountID = '$accountID' AND Liked = 'N'
                        ";
        $result = mysqli_query($dbconn, $sql);
        break;
    case 'updateStatus':
        $status = $_GET['Status'];
        $statusID = $_GET['StatusID'];
        $status = $dbconn ->real_escape_string($status);
        $sql = "UPDATE `status` SET Status = '$status',UpdateDt = NOW() WHERE StatusID = '$statusID'";
        $result = mysqli_query($dbconn, $sql);
        break;

      case 'removeStatus':
        $status = $_GET['Status'];
        $statusID = $_GET['StatusID'];
        $status = $dbconn ->real_escape_string($status);
        $sql = "DELETE FROM status WHERE StatusID = '$statusID'";
        $result = mysqli_query($dbconn, $sql);
        $sql = "DELETE FROM `emotion` WHERE StatusID = '$statusID'";
        $result = mysqli_query($dbconn, $sql);
        $sql = "DELETE FROM `comment` WHERE StatusID = '$statusID'";
        $result = mysqli_query($dbconn, $sql);
        break;



    case 'get_comment':
        $statusID = $_GET['statusID'];
        $sql = "SELECT  
                    a.CommentAccountName, 
                    a.`Comment`,
                    a.CommentAccountID,
                    a.CommentID
                FROM comment a
                LEFT JOIN status b on b.statusID = a.statusID 
                WHERE a.StatusID = '$statusID'
                ORDER BY a.CreateDt
                LIMIT 5 ";
        $result = mysqli_query($dbconn, $sql);
        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $sql2 = "SELECT * FROM account WHERE AccountID = '$row[CommentAccountID]'";
            $result2 = mysqli_query($dbconn, $sql2);
            while ($row2 = mysqli_fetch_assoc($result2)) {
                $Icon = $row2['Icon'];
            }
            $data[] = array('CommentAccountName' => $row['CommentAccountName'], 'Comment' => $row['Comment'],'Icon'=>$Icon, 'CommentID'=> $row['CommentID']);
        }
        echo json_encode($data);
        break;

     case 'get_fullcomment':
        $statusID = $_GET['statusID'];
        $sql = "SELECT  
                    a.CommentAccountName  , 
                    a.`Comment`,
                    a.CommentAccountID
                FROM comment a
                LEFT JOIN status b on b.statusID = a.statusID 
                WHERE a.StatusID = '$statusID'
                ORDER BY a.CreateDt
                ";
        $result = mysqli_query($dbconn, $sql);
        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $sql2 = "SELECT * FROM account WHERE AccountID = '$row[CommentAccountID]'";
            $result2 = mysqli_query($dbconn, $sql2);
            while ($row2 = mysqli_fetch_assoc($result2)) {
                $Icon = $row2['Icon'];
            }
            $data[] = array('CommentAccountName' => $row['CommentAccountName'], 'Comment' => $row['Comment'],'Icon'=>$Icon);
        }
        echo json_encode($data);
        break;

    case 'add_comment':
        $statusID = $_GET['statusID'];
        $newcomment = $_GET['newComment'];
        $sql = "UPDATE idlist
                SET `number` = `number`   +   1 
                WHERE YearOfEntity = YEAR(CURDATE()) AND datatype='NC'
                ";
        $result = mysqli_query($dbconn, $sql);
        $sql = "SELECT 
                        CONCAT(systemName,'-',datatype,'-',YearOfEntity,'-',LPAD(`number`, 7, '0')) As NotificationID 
                FROM idlist 
                WHERE YearOfEntity = YEAR(CURDATE()) AND datatype='NC'";
        $result = mysqli_query($dbconn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $notificationID = $row['NotificationID'];
        }
        $result = mysqli_query($dbconn, $sql);
        $sql = "UPDATE idlist
                SET `number` = `number`   +   1 
                WHERE YearOfEntity = YEAR(CURDATE()) AND datatype='CM'
                ";
        $result = mysqli_query($dbconn, $sql);
        $sql = "SELECT 
                        CONCAT(systemName,'-',datatype,'-',YearOfEntity,'-',LPAD(`number`, 7, '0')) As CommentID 
                FROM idlist 
                WHERE YearOfEntity = YEAR(CURDATE()) AND datatype='CM'";
        $result = mysqli_query($dbconn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $CommentID = $row['CommentID'];
        }

        $sql ="SELECT * FROM status WHERE StatusID = '$statusID'";
        $result = mysqli_query($dbconn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $OwnerAccountID = $row['OwnerAccountID'];
        }

        $sql = "INSERT INTO 
                            `accountnotification` (NotificationID,OwnerAccountID,FriendAccountID,FriendAccountName,NotificationTypeID,NotificationInformationID,CreateDt,CreateBy,UpdateDt,UpdateBy)
                VALUES
                            ('$notificationID','$OwnerAccountID','$accountID','$accountName','UNI-NT-2016-00006','$CommentID',NOW(),'Admin',NOW(),'Admin')
                ";
        $result = mysqli_query($dbconn, $sql);
        
        $sql = "INSERT INTO 
                            `comment` (StatusID,CommentID,notificationID,CommentAccountID,commentAccountName,`Comment`,CreateDt,CreateBy,UpdateDt,UpdateBy)
                VALUES
                            ('$statusID','$CommentID','$notificationID','$accountID','$accountName','$newcomment',NOW(),'Admin',NOW(),'Admin')
                        ";
        $result = mysqli_query($dbconn, $sql);
        break;

    case 'search_friend':
        $keyword = $_GET['keyword'];
        $sql = "SELECT * 
                FROM account a
                LEFT JOIN school b ON a.SchoolID = b.SchoolID
                LEFT JOIN program c ON a.ProgramID = c.ProgramID AND a.SchoolID = c.SchoolID
                WHERE a.AccountID <> '$accountID' AND ( a.AccountName LIKE '%$keyword%' 
                OR a.Height LIKE '%$keyword%' 
                OR a.Weight LIKE '%$keyword%' 
                OR b.SchoolName LIKE '%$keyword%' 
                OR c.ProgramName LIKE '%$keyword%')
                ";
        $result = mysqli_query($dbconn, $sql);
        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $friendChecker = 'N';
            $friendAccountID = $row['AccountID'];
            $sql2 = "SELECT * FROM friends 
                     WHERE FriendAccountID = '$friendAccountID' AND OwnerAccountID = '$accountID'";
            $result2 = mysqli_query($dbconn, $sql2);
            while ($row2 = mysqli_fetch_assoc($result2)) {
                if ($row2['FriendAccountID'] !== "") {
                    $friendChecker = 'Y';
                }

            }
            $sql2  = "SELECT * 
                    FROM account a 
                    LEFT JOIN school b ON a.SchoolID = b.SchoolID
                    LEFT JOIN program c ON a.ProgramID = c.ProgramID AND a.SchoolID = c.SchoolID
                    WHERE a.AccountID = '$friendAccountID'";
            $result2 = mysqli_query($dbconn, $sql2);
            while ($row2 = mysqli_fetch_assoc($result2)) {
                $SchoolName = $row2['SchoolName'];
                $ProgramName = $row2['ProgramName'];
            }
            $accountName = $row['AccountName'];
            $data[] = array(
                'AccountID' => $row['AccountID'], 
                'Icon'=>$row['Icon'],
                'AccountName' => $row['AccountName'], 
                'FriendChecker' => $friendChecker, 
                'SchoolName' => $SchoolName, 
                'ProgramName' => $ProgramName
                );
        }
        echo json_encode($data);
        break;

    case 'add_friend':
        $friendAccountID = $_GET['friendAccountID'];
        $friendAccountName = $_GET['friendAccountName'];
        $sql = "INSERT INTO 
                            friends (OwnerAccountID,friendAccountID,friendAccountName,CreateDt,CreateBy,UpdateDt,UpdateBy)
                 VALUES
                            ('$accountID','$friendAccountID','$friendAccountName',NOW(),'Admin',NOW(),'Admin')";
        $result = mysqli_query($dbconn, $sql);
        $sql = "INSERT INTO 
                            friends (OwnerAccountID,friendAccountID,friendAccountName,CreateDt,CreateBy,UpdateDt,UpdateBy)
                 VALUES
                            ('$friendAccountID','$accountID','$accountName',NOW(),'Admin',NOW(),'Admin')";
        $result = mysqli_query($dbconn, $sql);
        break;

    case 'remove_friend':
        $friendAccountID = $_GET['friendAccountID'];
        $sql = "DELETE FROM friends WHERE OwnerAccountID ='$friendAccountID' AND friendAccountID ='$accountID'";
        $result = mysqli_query($dbconn, $sql);
        $sql = "DELETE FROM friends WHERE OwnerAccountID ='$accountID' AND friendAccountID ='$friendAccountID'";
        $result = mysqli_query($dbconn, $sql);
        break;
        
        //                      //
        // Function For Gossip //
        //                    //
        
        
    case 'getOwnSecret':
        $sql = "SELECT 
                    a.SecretName,
                    a.SecretID,
                    a.Icon
                FROM
                    secret a
                LEFT JOIN accountsecret b ON a.SecretID = b.SecretID
                LEFT JOIN account c on c.AccountID = b.AccountID
                WHERE  c.AccountID ='$accountID'";
        $result = mysqli_query($dbconn, $sql);
        $data[] = array('GossipName' => 'Personal', 'GossipID' => 'Personal');
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = array('GossipName' => $row['SecretName'], 'GossipID' => $row['SecretID']);
        }
        echo json_encode($data);
        break;

    

    case 'search_gossip':
        $keyword = $_GET['keyword'];
        $sql = "SELECT * 
              FROM  secret
              WHERE SecretName LIKE '%$keyword%'";
        $result = mysqli_query($dbconn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $secretChecker = 'N';
            $secretID = $row['SecretID'];
            $sql2 = "SELECT * FROM accountsecret 
                     WHERE AccountID = '$accountID' AND SecretID = '$secretID'";
            $result2 = mysqli_query($dbconn, $sql2);
            while ($row2 = mysqli_fetch_assoc($result2)) {
                if ($row2['SecretID'] !== "") {
                    $secretChecker = 'Y';
                }
            }
            $data[] = array(
                'GossipID' => $row['SecretID'],
                'GossipName' => $row['SecretName'], 
                'GossipChecker' => $secretChecker,
                'Icon'=>$row['Icon'],
                'GossipDesc' => $row['SecretDesc'], 
            );
        }
        echo json_encode($data);
        break;

    case 'add_gossip':
        $gossipID = $_GET['gossipID'];
        $sql = "INSERT INTO 
                        accountsecret (AccountID,SecretID,CreateDt,CreateBy,UpdateDt,UpdateBy)
                VALUES
                        ('$accountID','$gossipID',NOW(),'Admin',NOW(),'Admin')";
        $result = mysqli_query($dbconn, $sql);
        break;

    case 'remove_gossip':
        $gossipID = $_GET['gossipID'];
        $sql = "DELETE FROM accountsecret WHERE AccountID ='$accountID' AND SecretID ='$gossipID'";
        $result = mysqli_query($dbconn, $sql);
        break;

    case 'logout':
        session_write_close();
        session_unset();
        setcookie('AccountID', "", time()-300, "/"); // 86400 = 1 day
        setcookie('AccountName', "", time()-300, "/"); // 86400 = 1 day
        setcookie('SchoolID', "", time()-300, "/"); // 86400 = 1 day
        break;

    case 'getserect':
        $sql = "SELECT
                    b.SecretID,
                    c.SecretMessage,
                    c.SecretMessageID,
                    d.Icon,
                    c.PhotoSrc,
                    d.SecretName,
                    c.CreateDt
              FROM account a
              LEFT JOIN accountsecret b ON b.AccountID = a.AccountID
              LEFT JOIN secretmessage c ON c.SecretID = b.SecretID
              LEFT JOIN secret d on d.SecretID = b.SecretID
              WHERE a.AccountID = '$accountID' AND c.SecretMessage <> ''
              ORDER By c.CreateDt DESC";
        $result = mysqli_query($dbconn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $liked = 0;
            $disLiked = 0;
            $comment = 0;
            $secretMessageID = $row['SecretMessageID'];
            $secretID = $row['SecretID'];
            $sql2 = "SELECT COUNT(a.Liked) AS `Liked`
              FROM secretemotion a
              WHERE a.SecretMessageID = '$secretMessageID' AND a.Liked ='Y' AND a.SecretID = '$secretID'";
            $result2 = mysqli_query($dbconn, $sql2);
            if ($result2) {
                while ($row2 = mysqli_fetch_assoc($result2)) {
                    $liked = $row2['Liked'];
                }
            }
            $sql3 = "SELECT COUNT(a.Liked) AS `YouLike`
              FROM secretemotion a
              WHERE a.SecretMessageID = '$secretMessageID' AND a.Liked ='Y'  AND a.SecretID = '$secretID' AND a.LikeAccountID ='$accountID'";
            $result3 = mysqli_query($dbconn, $sql3);
            if ($result3) {
                while ($row3 = mysqli_fetch_assoc($result3)) {
                    if ($row3['YouLike'] !== '0') {
                        $youLike = 'Y';
                    } 
                    else {
                        $youLike = 'N';
                    }
                }
            }
            
            $sql4 = "SELECT COUNT(a.Liked) AS `YouDisLike`
              FROM secretemotion a
              WHERE a.SecretMessageID = '$secretMessageID' AND a.Liked ='N'  AND a.SecretID = '$secretID' AND a.LikeAccountID ='$accountID'";
            $result4 = mysqli_query($dbconn, $sql4);
            if ($result4) {
                while ($row4 = mysqli_fetch_assoc($result4)) {
                    if ($row4['YouDisLike'] !== '0') {
                        $youDislike = 'Y';
                    } 
                    else {
                        $youDislike = 'N';
                    }
                }
            }
            
            $sql5 = "SELECT COUNT(a.Liked) AS `DisLiked`
              FROM secretemotion a
              WHERE a.SecretMessageID = '$secretMessageID' AND a.Liked ='N'  AND a.SecretID = '$secretID'";
            $result5 = mysqli_query($dbconn, $sql5);
            if ($result5) {
                while ($row5 = mysqli_fetch_assoc($result5)) {
                    $disLiked = $row5['DisLiked'];
                }
            }
            
            $sql6 = "SELECT COUNT(a.Comment) AS `Comment`
              FROM secretcomment a
              WHERE a.SecretMessageID = '$secretMessageID'  AND a.SecretID = '$secretID'";
            $result6 = mysqli_query($dbconn, $sql6);
            if ($result6) {
                while ($row6 = mysqli_fetch_assoc($result6)) {
                    $comment = $row6['Comment'];
                }
            }
            $commentList = Array();
            $sql1 = "SELECT 
                    b.SecretName,
                    a.`Comment`,
                    b.Icon
            FROM secretcomment a
            LEFT JOIN secret b ON a.SecretID = b.SecretID
            WHERE a.SecretID = '$secretID' AND a.SecretMessageID = '$secretMessageID'
            LIMIT 5 ";
            $result1 = mysqli_query($dbconn, $sql1);
            while ($row1 = mysqli_fetch_assoc($result1)) {
                $commentList[] = array('Comment' => $row1['Comment'], 'SecretName' => $row1['SecretName'],'Icon'=>$row1['Icon']);
            }
            $data[] = array('SecretID' => $row['SecretID'],'Icon'=>$row['Icon'],'Photo'=>$row['PhotoSrc'], 'CommentList'=>$commentList,'showComment'=>'N','SecretMessageID' => $row['SecretMessageID'], 'SecretName' => $row['SecretName'], 'Time' => $row['CreateDt'], 'SecretMessage' => $row['SecretMessage'], 'Liked' => $liked, 'DisLiked' => $disLiked, 'Comment' => $comment, 'YouLike' => $youLike, 'YouDisLike' => $youDislike,'ShowMore'=>'N');
        }
        echo json_encode($data);
        break;

    case 'addSecret_like':
        $secretID = $_GET['secretID'];
        $secretMessageID = $_GET['secretMessageID'];
        $sql = "INSERT INTO 
                        secretemotion (SecretID,SecretMessageID,LikeAccountID,`Liked`,CreateDt,CreateBy,UpdateDt,UpdateBy)
              VALUES
                        ('$secretID','$secretMessageID','$accountID','Y',NOW(),'Admin',NOW(),'Admin')";
        $result = mysqli_query($dbconn, $sql);
        break;

    case 'deleteSecret_like':
        $secretID = $_GET['secretID'];
        $secretMessageID = $_GET['secretMessageID'];
        $sql = "DELETE FROM secretemotion
              WHERE SecretID ='$secretID' AND SecretMessageID ='$secretMessageID' AND LikeAccountID = '$accountID' AND Liked='Y'";
        $result = mysqli_query($dbconn, $sql);
        break;

    case 'addSecret_dislike':
        $secretID = $_GET['secretID'];
        $secretMessageID = $_GET['secretMessageID'];
        $sql = "INSERT INTO 
                        secretemotion (SecretID,SecretMessageID,LikeAccountID,`Liked`,CreateDt,CreateBy,UpdateDt,UpdateBy)
              VALUES
                        ('$secretID','$secretMessageID','$accountID','N',NOW(),'Admin',NOW(),'Admin')";
        $result = mysqli_query($dbconn, $sql);
        break;

    case 'deleteSecret_dislike':
        $secretID = $_GET['secretID'];
        $secretMessageID = $_GET['secretMessageID'];
        $sql = "DELETE FROM secretemotion
              WHERE SecretID ='$secretID' AND SecretMessageID ='$secretMessageID' AND LikeAccountID = '$accountID' AND Liked='N'";
        $result = mysqli_query($dbconn, $sql);
        break;

    case 'add_secretcomment':
        $secretMessageID = $_GET['secretMessageID'];
        $newComment = $_GET['newComment'];
        $secretID = $_GET['secretID'];
        $sql = "INSERT INTO 
                        secretcomment (SecretID,SecretMessageID,`Comment`,CreateDt,CreateBy,UpdateDt,UpdateBy)
                VALUES
                        ('$secretID','$secretMessageID','$newComment',NOW(),'Admin',NOW(),'Admin')";
        $result = mysqli_query($dbconn, $sql);
        break;

    case 'get_secretcomment':
        $secretMessageID = $_GET['secretMessageID'];
        $secretID = $_GET['secretID'];
        $sql = "SELECT 
                        b.SecretName,
                        a.`Comment`,
                        b.Icon
                FROM secretcomment a
                LEFT JOIN secret b ON a.SecretID = b.SecretID
                WHERE a.SecretID = '$secretID' AND a.SecretMessageID = '$secretMessageID'
                LIMIT 5 ";
        $result = mysqli_query($dbconn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = array('Comment' => $row['Comment'], 'SecretName' => $row['SecretName'],'Icon'=>$row['Icon']);
        }
        echo json_encode($data);
        break;
    case 'get_secretfullcomment':
        $secretMessageID = $_GET['secretMessageID'];
        $secretID = $_GET['secretID'];
        $sql = "SELECT 
                        b.SecretName,
                        a.`Comment`,
                        b.Icon
                FROM secretcomment a
                LEFT JOIN secret b ON a.SecretID = b.SecretID
                WHERE a.SecretID = '$secretID' AND a.SecretMessageID = '$secretMessageID'";
        $result = mysqli_query($dbconn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = array('Comment' => $row['Comment'], 'SecretName' => $row['SecretName'],'Icon'=>$row['Icon']);
        }
        echo json_encode($data);
        break;
    case 'addDating':
        $SchoolID = $_GET['schoolID'];
        $ProgramID = $_GET['ProgramID'];
        $message = $_GET['message'];
        $location = $_GET['Location'];
        $date  = $_GET['Date'];
        $to = $_GET['to'];
        $to = str_replace("AM","",$to);
        $to = str_replace(" ","",$to);
        $to = str_replace("PM","",$to);
        $from = $_GET['from'];
        $from = str_replace("AM","",$from);
        $from = str_replace(" ","",$from);
        $from = str_replace("PM","",$from);
        $sql = "SELECT * FROM account WHERE SchoolID = '$SchoolID' AND ProgramID='$ProgramID'";
        $result = mysqli_query($dbconn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $DatingAccountID = $row['AccountID'];
            $sql2 = "SELECT * FROM event a
                    LEFT JOIN event_repeat b ON a.EventID = b.EventID AND a.OwnerAccountID = b.OwnerAccountID
                    WHERE a.OwnerAccountID = '$DatingAccountID' AND b.EventDate = '$date' AND FromTime='$from' AND ToTime='$to'";
            $result2 = mysqli_query($dbconn, $sql2);
            if (mysql_num_rows($result2)==0){
                $sql3 = "INSERT INTO accountrequest 
                            (OwnerAccountID,RequestAccountID,Location,Message,`Date`,FromTime,ToTime,CreateDt,CreateBy,UpdateDt,UpdateBy)
                         VALUES
                            ('$DatingAccountID','$accountID','$location','$message','$date','$from','$to',NOW(),'System',NOW(),'System')";
                $result3 = mysqli_query($dbconn, $sql3);
            }
        }
        break;

}
mysqli_close($dbconn);
?>
