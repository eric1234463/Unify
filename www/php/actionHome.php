<?php
require ("dbconn.php");
session_start();
$dbconn = dbconn();
$action = $_GET['action'];  
date_default_timezone_set('Asia/Hong_Kong');
$data = Array();
switch ($action) {
    case 'getselfdata':
    $accountID = $_GET["AccountID"];
    $accountName = $_GET["AccountName"];
        $sql = "SELECT
                            d.Icon,
                            e.OwnerAccountID,
                            e.OwnerAccountName , 
                            e.CreateDt,
                            e.`Status`,
                            e.`StatusID`,
                            e.PhotoSrc

                FROM account d
                LEFT JOIN `status` e ON d.AccountID = e.OwnerAccountID 
                WHERE  
                    d.AccountID = '$accountID' AND e.`Status` <> ''
                ORDER BY CreateDt DESC";
        $result = mysqli_query($dbconn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $youLiked = '';
            $youDisLiked = '';
            $statusID = $row['StatusID'];
            $comment = 0;
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
            $sql2 = "SELECT * 
                    FROM account a 
                    LEFT JOIN school b ON a.SchoolID = b.SchoolID
                    LEFT JOIN program c ON a.ProgramID = c.ProgramID AND a.SchoolID = c.SchoolID
                    WHERE  
                        a.AccountID = '$accountID'";
            $result2 = mysqli_query($dbconn, $sql2);
            while ($row2 = mysqli_fetch_assoc($result2)) {
                $ProgramName = $row2['ProgramName'];
                $SchoolName = $row2['SchoolName'];

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
                        $youLiked = "Y";
                    } 
                    else {
                        $youLiked = "N";
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
                        $youDisLiked = "Y";
                    } 
                    else {
                        $youDisLiked = "N";
                    }
                }
            }
            $commentList = Array();
            $sql1 = "SELECT  
                        a.CommentAccountName , 
                        a.`Comment`,
                        a.CommentAccountID
                    FROM comment a
                    LEFT JOIN status b on b.statusID = a.statusID 
                    WHERE a.StatusID = '$statusID'
                    LIMIT 5 ";
            $result1 = mysqli_query($dbconn, $sql1);
            while ($row1 = mysqli_fetch_assoc($result1)) {
                $CommentAccountID = $row1['CommentAccountID'];
                $sql2 = "SELECT * FROM account WHERE AccountID = '$CommentAccountID'";
                $result2 = mysqli_query($dbconn, $sql2);
                while ($row2 = mysqli_fetch_assoc($result2)) {
                    $Icon = $row2['Icon'];
                }
                $commentList[] = array('CommentAccountName' => $row1['CommentAccountName'], 'Comment' => $row1['Comment'],'Icon'=>$Icon);
            }
            $data[] = array(
                'ShowMore'=>'1',
                'commentList'=>$commentList,
                'StatusID' => $row['StatusID'],
                'ProgramName' => $ProgramName,
                'SchoolName' => $SchoolName, 
                'AccountID' => $row['OwnerAccountID'], 
                'AccountName' => $row['OwnerAccountName'], 
                'Time' => $row['CreateDt'], 
                'Status' => $row['Status'], 
                'Liked' => $liked, 
                'DisLiked' => $disLiked, 
                'Comment' => $comment, 
                'YouLike' => $youLiked, 
                'YouDisLike' => $youDisLiked,
                'Icon' => $row['Icon'],
                'Photo' => $row['PhotoSrc']
                );
        }
        echo json_encode($data);
    break;
    case 'getAccountInfo':
        $accountID = $_GET["AccountID"];
        $accountName = $_GET["AccountName"];
        $likedCount=0;
        $disLikedCount =0;
        $statusAmount = 0;
        $sql=   "SELECT 
                    COUNT(StatusID) as StatusAmount
                 FROM account a
                 LEFT JOIN status b ON a.AccountID = b.OwnerAccountID
                 WHERE a.AccountID ='$accountID'";
        $result = mysqli_query($dbconn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $statusAmount = $row['StatusAmount'];
        }
        $sql=   "SELECT 
                    COUNT(FriendAccountID) as FriendAmount
                 FROM account a
                 LEFT JOIN friends b ON a.AccountID = b.OwnerAccountID
                 WHERE a.AccountID ='$accountID'";
        $result = mysqli_query($dbconn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $friendAmount = $row['FriendAmount'];
        }
        $sql=   "SELECT 
                    COUNT(Liked) as LikedCount
                 FROM account a
                 LEFT JOIN status b ON a.AccountID = b.OwnerAccountID
                 LEFT JOIN `emotion` c ON b.StatusID = c.StatusID AND c.likeAccountID <> '$accountID'
                 WHERE a.AccountID ='$accountID' AND c.Liked ='Y'";
        $result = mysqli_query($dbconn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $likedCount = $row['LikedCount'];
        }
        $sql=   "SELECT 
                    COUNT(Liked) as disLikedCount
                 FROM account a
                 LEFT JOIN status b ON a.AccountID = b.OwnerAccountID
                 LEFT JOIN `emotion` c ON b.StatusID = c.StatusID AND c.likeAccountID <> '$accountID'
                 WHERE a.AccountID ='$accountID' AND c.Liked ='N'";
        $result = mysqli_query($dbconn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $disLikedCount = $row['disLikedCount'];
        }
        $friendchecker = 'N';
        $sql = "SELECT * FROM friends WHERE OwnerAccountID = '$_SESSION[AccountID]' AND FriendAccountID = '$accountID'";
        $result = mysqli_query($dbconn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            if($row['FriendAccountID']!==''){
                $friendchecker ='Y';
            }
        }
        $sql = "SELECT 
                        c.ProgramName,
                        b.SchoolName,
                        a.Birthday,
                        a.SelfIntroduction,
                        a.Gender,
                        a.Icon,
                        a.YearOfEntry,
                        a.Banner,
                        a.Height,
                        a.Telephone,
                        a.Weight,
                        a.Private,
                        a.Relationship,
                        a.Sexuality,
                        a.Experience
                    FROM account a
                    LEFT JOIN school b On a.SchoolID = b.SchoolID
                    LEFT JOIN program c ON a.ProgramID = c.ProgramID AND a.SchoolID = c.SchoolID
                    WHERE AccountID ='$accountID'";
                $result = mysqli_query($dbconn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {

                    $data[] = array(
                                'AccountID' => $accountID,
                                'StatusCount'=>$statusAmount,
                                'AccountName'=>$accountName,
                                'FriendAmount'=>$friendAmount,
                                'Relationship' => $row['Relationship'],
                                'Experience' => $row['Experience'],
                                'Sexuality' => $row['Sexuality'],
                                'LikedCount'=>$likedCount,
                                'disLikedCount'=>$disLikedCount,
                                'ProgramName' => $row['ProgramName'],
                                'SchoolName'=>$row['SchoolName'], 
                                'Birthday' => $row['Birthday'],
                                'Gender' => $row['Gender'],
                                'SelfIntroduction' => $row['SelfIntroduction'],
                                'Icon' => $row['Icon'],
                                'YearOfEntry' => $row['YearOfEntry'],
                                'Height' => $row['Height'],
                                'Weight' => $row['Weight'],
                                'Banner' => $row['Banner'],
                                'Private' => $row['Private'],
                                'Telephone' => $row['Telephone'],
                                'friendchecker' => $friendchecker
                            ); 

                }
        echo json_encode($data);
        break;
        case 'getLikeRank':
            $accountID = $_GET['AccountID'];
            $rank = 0;
            $sql = "SELECT 
                        COUNT(c.Liked) as LikedCount, 
                        b.FriendAccountID, 
                        b.FriendAccountName
                    FROM account a 
                    LEFT JOIN friends b ON a.AccountID = b.OwnerAccountID
                    LEFT JOIN status d ON a.AccountID = d.OwnerAccountID
                    LEFT JOIN `emotion` c ON c.likeAccountID = b.FriendAccountID AND c.StatusID = d.StatusID
                    WHERE a.AccountID = '$accountID' AND c.`Liked` = 'Y'
                    GROUP BY b.FriendAccountID
                    ORDER BY LikedCount DESC";
            $result = mysqli_query($dbconn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                        $FriendAccountID = $row['FriendAccountID'];
                        $sql2 = "SELECT Icon FROM account WHERE accountID = '$FriendAccountID'";
                        $result2 = mysqli_query($dbconn, $sql2);
                        while ($row2 = mysqli_fetch_assoc($result2)) {
                            $Icon = $row2['Icon'];
                        }
                        $sql2 ="SELECT 
                                        SchoolName,ProgramName
                                FROM account a
                                LEFT JOIN school b ON a.SchoolID = b.SchoolID
                                LEFT JOIN program c ON a.ProgramID = c.ProgramID AND a.SchoolID = b.SchoolID
                                WHERE a.AccountID = '$FriendAccountID'";
                        $result2 = mysqli_query($dbconn, $sql2);
                        while ($row2 = mysqli_fetch_assoc($result2)) {
                            $SchoolName = $row2['SchoolName'];
                            $ProgramName = $row2['ProgramName'];
                        }
                        $rank += 1;
                        $data[] = array(
                                'Rank' => $rank,
                                'SchoolName'=> $SchoolName,
                                'ProgramName'=> $ProgramName,
                                'AccountName'=> $row['FriendAccountName'],
                                'AccountID' => $row['FriendAccountID'],
                                'LikedCount' => $row['LikedCount'],
                                'Icon' => $Icon
                        );
            }
            echo json_encode($data);
        break;
        case 'getDisLikeRank':
            $accountID = $_GET['AccountID'];
            $rank = 0;
            $sql = "SELECT 
                        COUNT(c.Liked) as LikedCount, 
                        b.FriendAccountID, 
                        b.FriendAccountName
                    FROM account a 
                    LEFT JOIN friends b ON a.AccountID = b.OwnerAccountID
                    LEFT JOIN status d ON a.AccountID = d.OwnerAccountID
                    LEFT JOIN `emotion` c ON c.likeAccountID = b.FriendAccountID AND c.StatusID = d.StatusID
                    WHERE a.AccountID = '$accountID' AND c.`Liked` = 'N' 
                    GROUP BY b.FriendAccountID
                    ORDER BY LikedCount DESC";
            $result = mysqli_query($dbconn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                        $FriendAccountID = $row['FriendAccountID'];
                        $sql2 = "SELECT Icon FROM account WHERE accountID = '$FriendAccountID'";
                        $result2 = mysqli_query($dbconn, $sql2);
                        while ($row2 = mysqli_fetch_assoc($result2)) {
                            $Icon = $row2['Icon'];
                        }
                         $sql2 ="SELECT 
                                        SchoolName,ProgramName
                                FROM account a
                                LEFT JOIN school b ON a.SchoolID = b.SchoolID
                                LEFT JOIN program c ON a.ProgramID = c.ProgramID AND a.SchoolID = c.SchoolID
                                WHERE a.AccountID = '$FriendAccountID'";
                        $result2 = mysqli_query($dbconn, $sql2);
                        while ($row2 = mysqli_fetch_assoc($result2)) {
                            $SchoolName = $row2['SchoolName'];
                            $ProgramName = $row2['ProgramName'];
                        }
                        $rank += 1;
                        $data[] = array(
                                'Rank' => $rank,
                                'SchoolName'=> $SchoolName,
                                'ProgramName'=> $ProgramName,
                                'AccountName'=> $row['FriendAccountName'],
                                'AccountID' => $row['FriendAccountID'],
                                'LikedCount' => $row['LikedCount'],
                                'Icon' => $Icon

                        );
            }
            echo json_encode($data);
        break;
        case 'getPhotoDiary':
            $AccountID = $_GET['AccountID'];
            $date = $_GET['date'];
            $sql = "SELECT 
                        b.StoryID,
                        b.StoryTitle
                    FROM account a
                    LEFT JOIN storydiary b ON a.AccountID = b.AccountID
                    WHERE a.AccountID = '$AccountID' AND b.CreateDt = '$date'
                    GROUP BY b.StoryID";
            $result = mysqli_query($dbconn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                $storyList =  Array();
                $storyID = $row['StoryID'];
                $sql2 = "SELECT 
                        b.PhotoSrc,
                        b.StoryDesc,
                        b.CreateDt
                        FROM account a
                        LEFT JOIN storydiary b ON a.AccountID = b.AccountID
                        WHERE a.AccountID = '$AccountID' AND b.StoryID = '$storyID'";
                $result2 = mysqli_query($dbconn, $sql2);
                while ($row2 = mysqli_fetch_assoc($result2)) {
                    $storyList[] = array(
                                'PhotoSrc' => "background-image:url(".$row2['PhotoSrc'].")",
                                'StoryDesc' => $row2['StoryDesc'],
                                'Time' => $row2['CreateDt'],
                                );
                }
                $data[] = array(
                                'StoryTitle' => $row['StoryTitle'],
                                'StoryDiaryList' => $storyList
                        );
            }
            echo json_encode($data);
            break;
        case 'getTimetable':
            $accountID = $_GET['AccountID'];
            $inputDate = $_GET['inputDate'];
            $sql = "SELECT  *
                    FROM event_repeat a
                    WHERE a.OwnerAccountID = '$accountID' 
                    AND  a.EventDate = '$inputDate'";
            $result = mysqli_query($dbconn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                $eventID = $row['EventID'];
                $sql2="SELECT * FROM event WHERE EventID = '$eventID' AND OwnerAccountID = '$accountID'";
                $result2 = mysqli_query($dbconn, $sql2);
                while ($row2 = mysqli_fetch_assoc($result2)) {
                    $fromTime = date("h:i a", strtotime($row2['FromTime']));
                    $toTime = date("h:i a", strtotime($row2['ToTime']));
                    $AllDayEvent = $row2['AllDayEvent'];
                    $Personal = $row2['Personal'];
                    $eventTitle = $row2['EventTitle'];
                    $Description = $row2['Description'];
                    $Location = $row2['Location'];
                }
                $data[] = array(
                    'eventID' => $row['EventID'], 
                    'eventRepeatID' => $row['EventRepeatID'],
                    'isPersonal' => $Personal,
                    'isAllDay' => $AllDayEvent,
                    'eventTitle' => $eventTitle, 
                    'eventDescription' => $Description, 
                    'eventLocation' => $Location, 
                    'fromTime' => $fromTime, 
                    'toTime' => $toTime, 
                    'date' => $row['EventDate']
                );
            }
            echo json_encode($data);
        break;
    case 'AskQuestion':
        $OwneraccountID = $_SESSION['AccountID'];
        $FriendaccountID = $_GET['AccountID'];
        $AccountName = $_SESSION['AccountName'];
        $question = $_GET['question'];
        $sql = "INSERT INTO question 
                        (AccountID,Question,CreateDt,CreateBy)
                VALUES
                        ('$OwneraccountID','$question',NOW(),'$AccountName')";
        $result = mysqli_query($dbconn, $sql);
        $sql = "SELECT * FROM question WHERE AccountID = '$OwneraccountID' ORDER BY CreateDt LIMIT 1";
        $result = mysqli_query($dbconn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $QuestionID = $row['QuestionID'];
        }
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
                            ('$notificationID','$FriendaccountID','$OwneraccountID','$AccountName','UNI-NT-2016-00007','$QuestionID',NOW(),'Admin',NOW(),'Admin')";
        $result = mysqli_query($dbconn, $sql);             
}
mysqli_close($dbconn);
?>
