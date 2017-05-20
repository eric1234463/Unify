<?php
require ("dbconn.php");
session_start();
$dbconn = dbconn();
$action = $_GET['action'];
$accountID = $_SESSION['AccountID'];
$accountName = $_SESSION['AccountName'];
$data = Array();
$disLiked = '';
$liked = '';
$edit ="";
date_default_timezone_set('Asia/Hong_Kong');
switch ($action) {
    case 'getData':
    	$StartLimitNotification = $_GET['startLimit'];
    	$EndLimitNotification = $_GET['endLimit'];
    	$Icon ="";
    	$AccountID="";
    	$AccountName="";
        $sql = "SELECT 	
        				b.OwnerAccountID,
        				b.FriendAccountName,
        				b.FriendAccountID,
        				b.CreateDt,
        				b.Read,
        				b.NotificationInformationID,
        				b.NotificationID,
        				c.NotificationDesc,
        				c.NotificationTypeID
        		FROM account a
				LEFT JOIN accountnotification b ON b.OwnerAccountID = a.AccountID 
				LEFT JOIN notification c ON c.NotificationTypeID = b.NotificationTypeID 
				WHERE b.OwnerAccountID = '$accountID' AND b.FriendAccountID <> '$accountID'
				ORDER BY b.CreateDt DESC
				LIMIT $StartLimitNotification,$EndLimitNotification";  

				$result = mysqli_query($dbconn, $sql);

				$sql8 = "SELECT COUNT(a.NotificationID) AS `Size`
						FROM accountnotification a 
						WHERE a.OwnerAccountID = '$accountID'";
						$result8 = mysqli_query($dbconn, $sql8);
						while($row1 = mysqli_fetch_assoc($result8)){
				 		$Size = $row8['Size'];}
				 		

				if($result!==''){
					while($row = mysqli_fetch_assoc($result)) {
						$NotificationInformationID = $row['NotificationInformationID'];
						switch ($row['NotificationTypeID']) {
							case 'UNI-NT-2016-00001':
							case 'UNI-NT-2016-00002':
								$sql1 = "SELECT
											Status ,
											StatusID
									FROM status 
									WHERE StatusID = '$NotificationInformationID'";
								$result1 = mysqli_query($dbconn, $sql1);
								while($row1 = mysqli_fetch_assoc($result1)){
									$StatusID =$row1['StatusID'];
									$Status = $row1['Status'];
									$CommentID = "N";
									$Comment = "N";
									$Photo = "N";
								}
							break;
							
							case 'UNI-NT-2016-00003':
							case 'UNI-NT-2016-00005':
								$sql3 =" SELECT
										Status,
										StatusID,
									FROM status
									WHERE StatusID = '$NotificationInformationID'";
									$result3 = mysqli_query($dbconn, $sql3);
									while ($row3 = mysqli_fetch_assoc($result3)) {
										$StatusID = $row3['StatusID'];
										$Status = $row3['Status'];
										$Photo = $row3['PhotoSrc'];
										$Comment = "N";
										$CommentID = "N";
									}

								break;
								
							case 'UNI-NT-2016-00006':
								$sql4 =" SELECT
										a.Comment,
										a.CommentID,
										b.Status,
										b.StatusID,
										b.PhotoSrc
									FROM comment a
									LEFT JOIN status b ON b.StatusID = a.StatusID
									WHERE a.CommentID = '$NotificationInformationID'";
									$result4 = mysqli_query($dbconn, $sql4);
									while ($row4 = mysqli_fetch_assoc($result4)) {
										$Status = $row4['Status'];
										$StatusID = $row4['StatusID'];
										$Photo = $row4['PhotoSrc'];
										$Comment = $row4['Comment'];
										$CommentID = $row4['CommentID'];
									}


								break;
						}

						$sql2 = "SELECT Icon ,AccountID,AccountName FROM account WHERE AccountID = '$row[FriendAccountID]'";
						$result2 = mysqli_query($dbconn, $sql2);
						while($row2 = mysqli_fetch_assoc($result2)) {
							$Icon = $row2['Icon'];
							$AccountName = $row2['AccountName'];
							$AccountID =$row2['AccountID'];
							
						}					

	
	                
						$data[] = array(	
								  'FriendAccountID' => $row['FriendAccountID'],
								  'FriendAccountName' => $row['FriendAccountName'],
								  'NotificationDesc' => $row['NotificationDesc'],
								  'NotificationTypeID' =>$row['NotificationTypeID'],
								  'NotificationInformationID' =>$row['NotificationInformationID'],
								  'NotificationID' => $row['NotificationID'],
								  'OwnerAccountID' =>$row['OwnerAccountID'],
								  'Read' =>$row['Read'],
								  'CreateDt' => $row['CreateDt'],
								  'Icon' => $Icon,
								  'AccountID' => $AccountID,
								  'AccountName' =>$AccountName,
								  'Status'=>$Status,
								  'StatusID' =>$StatusID,
								  'CommentID'=>$CommentID,
								  'Comment' =>$Comment,
								  'Photo'=>$Photo
								  ); 
					}}
				echo json_encode($data);		
				break;

	case 'getNotificationOwnerDetail':
		$OwnerAccountID = $_GET['OID'];
		$StatusID = $_GET['SID'];
		$NotificationInformationID = $_GET['NID'];
		$NotificationTypeID = $_GET['TYPEID'];
		$comment = 0;
		$commentID = '';
		$NotificationID = '';
		$ShowComment = 'N';
		$ShowMore ='N';
		$sql = "SELECT
						a.Icon,
						b.Status,
						b.OwnerAccountName,
						b.CreateDt,
						b.PhotoSrc,
						c.SchoolName,
						d.ProgramName,
						b.OwnerAccountID,
						COUNT(CASE WHEN e.Liked = 'Y'THEN 1 ELSE NULL END )AS Likes,
						COUNT(CASE WHEN e.Liked = 'N'THEN 1 ELSE NULL END )AS Dislikes
				FROM status b
				LEFT JOIN account a  on a.AccountID = '$OwnerAccountID'
				LEFT JOIN school c on c.SchoolID = a.SchoolID
				LEFT JOIN program d on d.ProgramID = a.ProgramID AND a.SchoolID = d.SchoolID
				LEFT JOIN emotion e on e.StatusID = b.StatusID
				WHERE b.StatusID = '$StatusID' "; 
				$result = mysqli_query($dbconn, $sql);
				
		$sql2 ="SELECT 
					COUNT(f.StatusID)  As Comments
			FROM comment f 
			WHERE f.StatusID = '$StatusID'"; 
			$result2 = mysqli_query($dbconn, $sql2);
			if ($result2) {
            while ($row2 = mysqli_fetch_assoc($result2)) {
                $comment = $row2['Comments'];
            }
        }

   		$sql3 = "SELECT 
                                COUNT(a.Liked) AS `YouLike`
                FROM emotion a
                WHERE  
                            a.StatusID = '$StatusID'AND a.Liked ='Y' AND a.LikeAccountID = '$OwnerAccountID' ";
	            $result3 = mysqli_query($dbconn, $sql3);
	            if ($result3) {
        	    while ($row3 = mysqli_fetch_assoc($result3)) {
                if ($row3['YouLike'] !== '0') {
                    $liked = "Y";
                } 
                else {
                    $liked = "N";
                }}}


    	$sql4 = "SELECT 
    	COUNT(a.Liked) AS `YouDisLike`
        FROM emotion a
        WHERE a.StatusID = '$StatusID' AND a.Liked ='N'  AND a.LikeAccountID = '$OwnerAccountID' ";
          $result4 = mysqli_query($dbconn, $sql4);
          if ($result4) {
                while ($row4 = mysqli_fetch_assoc($result4)) {
            if ($row4['YouDisLike'] !== '0') {
                    $disLiked = "Y";
                } 
           else {
                    $disLiked = "N";
                }
            }}
 

		$sql6 ="SELECT `CommentID` FROM `comment` WHERE `CommentID` = '$NotificationInformationID'";
		$result6 = mysqli_query($dbconn, $sql6);
        if ($result6) {
	    while ($row6 = mysqli_fetch_assoc($result6)) {
	    	$CommentID = $row6['CommentID'];}
		}


    	$sql7= "UPDATE accountnotification SET `Read` = 'Y'";
    		switch ('$NotificationTypeID') {
    			case 'UNI-NT-2016-00001':
    			case 'UNI-NT-2016-00002':
    				$sql7 .= "WHERE `NotificationID` = '$NotificationInformationID'";	
    				break;
    			case 'UNI-NT-2016-00006':
    				$sql7  .= "WHERE `NotificationInformationID` = '$CommentID'";	
    				break;
    		}
		$result7 = mysqli_query($dbconn, $sql7);
	 
        $commentList = Array();
        if($NotificationTypeID == 'UNI-NT-2016-00006'){
        $sql1 = "SELECT  a.commentAccountName, a.Comment, a.CommentID, c.Icon, c.AccountID
                FROM comment a
                LEFT JOIN status b on b.statusID = a.statusID 
                LEFT JOIN account c on a.CommentAccountID = c.AccountID
                WHERE a.StatusID = '$StatusID'
                ORDER BY a.CreateDt 
                ";}
        else{
        	$sql1 = "SELECT  a.commentAccountName, a.Comment, a.CommentID, c.Icon, c.AccountID
                FROM comment a
                LEFT JOIN status b on b.statusID = a.statusID 
                LEFT JOIN account c on a.CommentAccountID = c.AccountID
                WHERE a.StatusID = '$StatusID'
                ORDER BY a.CreateDt 
                LIMIT 5 
                ";
        }
        $result1 = mysqli_query($dbconn, $sql1);
        while ($row1 = mysqli_fetch_assoc($result1)) {
            $commentList[] = array(
            	'AccountName' => $row1['commentAccountName'],
            	'AccountID'=> $row1['AccountID'], 
            	'Comment' => $row1['Comment'], 
            	'CommentID'=>$row1['CommentID'],
            	'Icon'=>$row1['Icon']
            	);
        }

		while($row = mysqli_fetch_assoc($result)) {
			$ShowComment ='N';
			$ShowMore = 0;
			if($OwnerAccountID == $row['OwnerAccountID']){
				$edit = 'Y';
			}else{
				$edit = 'N';
			}

			if($NotificationTypeID == 'UNI-NT-2016-00006'){
				$ShowComment ='Y';
				$ShowMore = 0;
			}





			$data[] =array(	
								'Icon' => $row['Icon'],
								'Status' => $row['Status'],
								'SelectCommentID' =>$CommentID,
								'NotificationTypeID' =>$NotificationTypeID,
								'PhotoSrc' =>$row['PhotoSrc'],
								'AccountName' => $row['OwnerAccountName'],
								'CreateDt' => $row['CreateDt'],
								'SchoolName' =>$row['SchoolName'],
								'ProgramName' =>$row['ProgramName'],
								'Likes' =>$row['Likes'],
								'DisLikes' =>$row['Dislikes'],
								'Comment' =>$comment,
								'YouLike' =>$liked,
								'YouDisLike' =>$disLiked,
								'AccountID' => $OwnerAccountID,
								'StatusID' => $StatusID,
								'commentList'=>$commentList,
								'Edit'=> $edit,
								'ShowMore'=> $ShowMore,
								'showComment'=>$ShowComment

			);
		}
		echo json_encode($data);		

		break; 

	case 'unread':
			$sql= "SELECT 
					COUNT(a.Read) AS Unread
					FROM accountnotification a
					WHERE a.OwnerAccountID = '$accountID'
					AND a.Read ='N'
					AND a.FriendAccountID <> '$accountID'";

			$result = mysqli_query($dbconn, $sql);
		        
			while($row = mysqli_fetch_assoc($result)) {        
				$unread = ($row['Unread']);
			}
			echo $unread;
		break;
	
	case 'getRequest':
			$sql ="	SELECT a.OwnerAccountID, a.RequestID, b.AccountName ,a.RequestAccountID,b.Icon,a.Message,a.Location,a.FromTime,a.ToTime,a.Date,a.CreateDt
					FROM accountrequest a
					LEFT JOIN account b ON a.RequestAccountID = b.AccountID
					WHERE a.OwnerAccountID = '$accountID' AND a.Accept = 'N'";				
			$result = mysqli_query($dbconn, $sql);
			while($row = mysqli_fetch_assoc($result)) {        
				$data[] =array(	
							'RequestID' => $row['RequestID'],
							'Icon' => $row['Icon'],
							'OwnerAccountID' => $row['OwnerAccountID'],
							'RequestAccountID' => $row['RequestAccountID'],
							'Date' => $row['Date'],
							'FromTime' =>$row['FromTime'],
							'RequestID' =>$row['RequestID'],
							'RequestAccountName' =>$row['AccountName'],
							'Message' => $row['Message'],
							'Location' => $row['Location'],
							'FromTime' => $row['FromTime'],
							'ToTime' => $row['ToTime'],
							'Date' => $row['Date'],		
							'CreateDt' => $row['CreateDt']						
				);
			}
			echo json_encode($data);
			
		break;

	case 'finishRequest':
			$RequestID = $_GET['RequestID'];
			$RequestAccountID = $_GET['RequestAccountID'];
			$RequestAccountName = $_GET['RequestAccountName'];
			$sql = "UPDATE accountrequest SET Accept = 'Y' WHERE RequestID = '$RequestID'";
			$result = mysqli_query($dbconn, $sql);
	        $sql =  "SELECT * FROM chat WHERE SenderAccountID = '$accountID' AND ReceiverAccountID = '$RequestAccountID'";
	        $result = mysqli_query($dbconn, $sql);
	      	
	        if (mysqli_num_rows($result) == 0) 
	        {
		       	$sql = "UPDATE idlist
			  			SET `number` = `number`	+	1 
			  			WHERE YearOfEntity = YEAR(CURDATE()) AND datatype='C'
			  			";
				$result = mysqli_query($dbconn, $sql);
				$sql = "SELECT 
					CONCAT(systemName,'-',datatype,'-',YearOfEntity,'-',LPAD(`number`, 5, '0')) As ChatID 
			  		FROM idlist
			  		WHERE YearOfEntity = YEAR(CURDATE()) AND datatype='C'
			  		";
				$result = mysqli_query($dbconn, $sql);
				while ($row = mysqli_fetch_assoc($result)) {
		    	$chatID = $row['ChatID'];
				}
		        $sql = "INSERT INTO 
		                            `chat` (ChatID,SenderAccountID,ReceiverAccountID,ReceiverAccountName,CreateDt,CreateBy,UpdateDt,UpdateBy,IsGroupChat)
		                VALUES
		                            ('$chatID','$accountID','$RequestAccountID','$RequestAccountName',NOW(),'Admin',NOW(),'Admin','N')
		                        ";
		        $result = mysqli_query($dbconn, $sql);
		        $sql = "INSERT INTO 
		                            `chat` (ChatID,SenderAccountID,ReceiverAccountID,ReceiverAccountName,CreateDt,CreateBy,UpdateDt,UpdateBy,IsGroupChat)
		                VALUES
		                            ('$chatID','$RequestAccountID','$accountID','$accountName',NOW(),'Admin',NOW(),'Admin','N')
		                        ";
		        $result = mysqli_query($dbconn, $sql);
		        echo $sql;
	    	}

			$sql = "UPDATE idlist
				  			SET `number` = `number`	+	1 
				  			WHERE YearOfEntity = YEAR(CURDATE()) AND datatype='MS'
				  			";
					$result = mysqli_query($dbconn, $sql);
					$sql = "SELECT 
						CONCAT(systemName,'-',datatype,'-',YearOfEntity,'-',LPAD(`number`, 5, '0')) As MessageID 
				  		FROM idlist
				  		WHERE YearOfEntity = YEAR(CURDATE()) AND datatype='MS'
				  		";
					$result = mysqli_query($dbconn, $sql);
					while ($row = mysqli_fetch_assoc($result)) {
			    	$messageID = $row['MessageID'];
			}
	        $sql = "INSERT INTO `chatmessage` 
	        				(
	        					ChatID,
	        					MessageID,
	        					SenderAccountID,
	        					SenderAccountName,
	        					ReceiverAccountID,
	        					ReceiverAccountName,
	        					MessageDesc,
	        					CreateDt,
	        					CreateBy,
	        					UpdateDt,
	        					UpdateBy
	        				)
	                VALUES
	                        (
	                        	'$chatID',
	                        	'$messageID',
	                        	'$accountID',
	                        	'$accountName',
	                        	'$RequestAccountID',
	                        	'$RequestAccountName',
	                        	'Success Dating  Hope You Enjoy Your Dating Please Plan Your activity In Your Dating',
	                        	NOW(),
	                        	'Admin',
	                        	NOW(),
	                        	'Admin'
	                        )";
	        $result = mysqli_query($dbconn, $sql);
		break;
	case 'getQuestion':
		$QuestionID = $_GET['QuestionID'];
		$sql = "SELECT * FROM question WHERE QuestionID = $QuestionID";
		$result = mysqli_query($dbconn, $sql);
			while($row = mysqli_fetch_assoc($result)) {        
				$data[] =array(	
					'QuestionID' =>  $row['QuestionID'],
					'Question' =>  $row['Question'],
				);
			}
		echo json_encode($data);
		break;
	case 'viewAnswer':
		$QuestionID = $_GET['QuestionID'];
		$NotificationID = $_GET['NotificationID'];
		$sql = "UPDATE accountnotification SET `Read` = 'Y' WHERE NotificationID = '$NotificationID'";
		$result = mysqli_query($dbconn, $sql);
		$sql = "SELECT * FROM question WHERE QuestionID = $QuestionID";
		$result = mysqli_query($dbconn, $sql);
			while($row = mysqli_fetch_assoc($result)) {        
				$data[] =array(	
					'QuestionID' =>  $row['QuestionID'],
					'Question' =>  $row['Question'],
					'Answer' => $row['Answer']
				);
			}
		echo json_encode($data);
		break;
	case 'answerQuestion':
		$FriendaccountID = $_GET['FriendaccountID'];
		$QuestionID = $_GET['QuestionID'];
		$NotificationID = $_GET['NotificationID'];
		$sql = "UPDATE accountnotification SET `Read` = 'Y' WHERE NotificationID = '$NotificationID'";
		echo $sql;
		$result = mysqli_query($dbconn, $sql);
		$answer = $_GET['answer'];
		$sql = "UPDATE question SET answer = '$answer' WHERE QuestionID = $QuestionID";
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
                            ('$notificationID','$FriendaccountID','$accountID','$accountName','UNI-NT-2016-00008','$QuestionID',NOW(),'Admin',NOW(),'Admin')";
        $result = mysqli_query($dbconn, $sql);
        break;
}
mysqli_close($dbconn);

?>
