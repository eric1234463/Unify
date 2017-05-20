<?php
 ini_set("mbstring.internal_encoding","UTF-8");
    ini_set("mbstring.func_overload",7);
require ("dbconn.php");

session_start();
$dbconn = dbconn();
$accountID = $_SESSION["AccountID"];
$accountName = $_SESSION["AccountName"];
$action = $_GET['action'];
$data = Array();
switch ($action) {
    case 'getData':
	$sql = 
	"SELECT
		D.CreateDt,
		D.ChatID,
		D.SenderAccountID,
		D.SenderAccountName,
		D.MessageDesc,
		D.ReceiverAccountID,
		D.ReceiverAccountName,
		D.GroupChatID,
		e.IsGroupChat,
		f.Icon,
		g.Icon as GroupIcon,
		(SELECT GROUP_CONCAT(i.AccountName SEPARATOR ', ') FROM account i, groupchat h where h.ChatID = D.ChatID AND i.AccountID=h.AccountID AND h.AccountID!='$accountID')AS GroupMember,
		COUNT(CASE WHEN c.CreateDt > e.LastInChat AND c.SenderAccountID=D.ReceiverAccountID THEN 1
			WHEN c.CreateDt > e.LastInChat AND g.AccountID = '$accountID' AND c.ReceiverAccountID=D.GroupChatID THEN 0
			ELSE NULL END )
			AS Unread
		FROM(
				SELECT 
    				a.CreateDt,
    				a.ChatID,
    				a.SenderAccountID,
    				a.SenderAccountName,
    				a.MessageDesc,
    				f.GroupChatID,
					CASE a.ReceiverAccountID WHEN '$accountID' THEN a.SenderAccountID
					ELSE a.ReceiverAccountID
					END AS ReceiverAccountID,
					CASE a.ReceiverAccountName WHEN '$accountName' THEN a.SenderAccountName
					ELSE a.ReceiverAccountName
					END AS ReceiverAccountName
					FROM chatmessage a
					LEFT JOIN groupchat f on a.ChatID = f.ChatID AND f.AccountID = '$accountID'
					WHERE
					a.CreateDt IN ( SELECT MAX(b.CreateDt) FROM chatmessage b WHERE (b.ReceiverAccountID = '$accountID' OR b.SenderAccountID = '$accountID' OR b.ReceiverAccountID = f.GroupChatID) GROUP BY ChatID)
					AND (a.ReceiverAccountID = '$accountID' OR a.SenderAccountID = '$accountID' OR a.ReceiverAccountID = f.GroupChatID)
					ORDER BY a.CreateDt DESC) AS D
		LEFT JOIN chatmessage c on D.ChatID = c.ChatID
		LEFT JOIN chat e on D.ChatID = e.ChatID AND e.SenderAccountID = '$accountID'
		LEFT JOIN account f on D.ReceiverAccountID = f.AccountID
		LEFT JOIN groupchat g on D.ReceiverAccountID = g.GroupChatID
		GROUP BY D.ChatID
		ORDER BY D.CreateDt DESC
	";
	$result = mysqli_query($dbconn, $sql); 
	while($row = mysqli_fetch_assoc($result)) {
		$data[] = array(
			'UserName' => $accountName,
			'Time' => $row['CreateDt'],
			'ChatID' => $row['ChatID'],
			'AccountName' => $row['ReceiverAccountName'],
			'AccountID' => $row['ReceiverAccountID'],
			'Detail' => htmlspecialchars_decode($row['MessageDesc']),
			'Unread' => $row['Unread'],
			'IsGroupChat' => $row['IsGroupChat'],
			'SenderName' => $row['SenderAccountName'],
			'CreateDt' => $row['CreateDt'],
			'Icon' => $row['Icon'],
			'GroupIcon' => $row['GroupIcon'],
			'GroupMember' => $row['GroupMember']
		);	
	}
	
	break;

	case 'passChatID':
		$_SESSION["ChatID"] = $_GET['chatID'];
		$_SESSION["ReceiverID"] = $_GET['receiverID'];
		$_SESSION["ReceiverName"] = $_GET['receiverName'];
	break;

	case 'leaveChatRoom':
		$_SESSION["ChatID"] ="";
	break;

	case 'getMessage':
	$chatID=$_SESSION["ChatID"];
	$receiverID=$_SESSION["ReceiverID"];
	$sql = "UPDATE chat
	  		SET `LastInChat` = NOW() 
	  		WHERE SenderAccountID='$accountID' AND ReceiverAccountID='$receiverID' AND ChatID = '$chatID'";
		$result = mysqli_query($dbconn, $sql);
	$sql = 
	"SELECT 
			a.ChatID , 
			a.MessageID,
			a.ReceiverAccountName,
			a.SenderAccountID,
			a.CreateDt,
			a.MessageDesc,
			a.SenderAccountName,
			b.IsGroupChat
	FROM chatmessage a
	LEFT JOIN chat b ON a.ChatID = b.ChatID AND b.SenderAccountID = '$accountID'
	WHERE  
		a.ChatID='$chatID'
	ORDER BY a.CreateDt";
	$result = mysqli_query($dbconn, $sql);
	$UserColor = "color:#000";
	while($row = mysqli_fetch_assoc($result)) {
		$SenderAccountID = $row['SenderAccountID'];
		if($row['IsGroupChat'] == 'Y'){
			$sql2= "SELECT UserColor 
					FROM groupchat a 
					WHERE a.ChatID = '$chatID' AND a.AccountID = '$SenderAccountID'";
			$result2 = mysqli_query($dbconn, $sql2);
			while($row2 = mysqli_fetch_assoc($result2)) {
				$UserColor = $row2['UserColor'];
			}
		}
		$data[] = array(
				  'AccountName' => $accountName,
				  'Time' => $row['CreateDt'],
				  'Detail' => htmlspecialchars_decode($row['MessageDesc']),
				  'MessageID' => $row['MessageID'],
				  'FriendAccountName' => $row['SenderAccountName'],
				  'IsGroupChat' => $row['IsGroupChat'],
				  'UserColor' => $UserColor
				  );
		}
	break;
	case 'match':
        $ChatAccountID = $_GET['ChatAccountID'];
		$sql = "UPDATE chat
       			SET Matchup = 'Y'
	  			WHERE SenderAccountID = '$ChatAccountID' AND ReceiverAccountID='$accountID'";
		$result = mysqli_query($dbconn, $sql);
		$sql = "SELECT * FROM chat
	  			WHERE SenderAccountID = '$accountID' AND ReceiverAccountID='$ChatAccountID' AND Matchup = 'Y'";
		$result = mysqli_query($dbconn, $sql);
		if (mysqli_num_rows($result)==0){			
			$data[] = array('relationship' =>'fail');
		}else{
			$data[] = array('relationship' =>'success');
		}
		
	break;
 	case 'sendMessage':
       	$chatID=$_SESSION["ChatID"];
       	$receiverAccountID = $_SESSION["ReceiverID"];
        $receiverAccountName = $_SESSION["ReceiverName"];
        $detail = $_GET['Message'];
        $detail = $dbconn ->real_escape_string($detail);
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
        $sql = "INSERT INTO 
                            `chatmessage` (ChatID,MessageID,SenderAccountID,SenderAccountName,ReceiverAccountID,ReceiverAccountName,MessageDesc,CreateDt,CreateBy,UpdateDt,UpdateBy)
                VALUES
                            ('$chatID','$messageID','$accountID','$accountName','$receiverAccountID','$receiverAccountName','$detail',NOW(),'Admin',NOW(),'Admin')
                        ";
        $result = mysqli_query($dbconn, $sql);
        break;


case 'findChat':
	$receiverAccountID = $_GET['receiverID'];
	$receiverAccountName = $_GET['receiverName'];
	$sql = 
	"SELECT 
			c.CreateDt,
			b.ChatID,
			b.ReceiverAccountName,
			b.ReceiverAccountID,
			b.SenderAccountID,
			c.MessageDesc
	FROM account a
	LEFT JOIN chat b on a.AccountID = b.SenderAccountID
	LEFT JOIN chatmessage c on b.SenderAccountID = a.AccountID
	WHERE a.AccountID = '$accountID'
	AND b.receiverAccountID = '$receiverAccountID'
	AND b.receiverAccountName = '$receiverAccountName'
	ORDER BY c.CreateDt DESC
	LIMIT 1";
	$result = mysqli_query($dbconn, $sql);
	while($row = mysqli_fetch_assoc($result)) {
		$data[] = array(
			  'AccountName' => $accountName,
			  'Time' => $row['CreateDt'],
			  'ChatID' => $row['ChatID'],
			  'FriendAccountName' => $row['ReceiverAccountName'],
			  'ReceiverID' => $row['ReceiverAccountID'],
			  'Detail' => $row['MessageDesc']
			  );
	}
	
	break;



case 'addChat':
        $receiverAccountID = $_GET['receiverID'];
        $receiverAccountName = $_GET['receiverName'];
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
                            ('$chatID','$accountID','$receiverAccountID','$receiverAccountName',NOW(),'Admin',NOW(),'Admin','N')
                        ";
        $result = mysqli_query($dbconn, $sql);
        $sql = "INSERT INTO 
                            `chat` (ChatID,SenderAccountID,ReceiverAccountID,ReceiverAccountName,CreateDt,CreateBy,UpdateDt,UpdateBy,IsGroupChat)
                VALUES
                            ('$chatID','$receiverAccountID','$accountID','$accountName',NOW(),'Admin',NOW(),'Admin','N')
                        ";
        $result = mysqli_query($dbconn, $sql);
		$data[] = array(
			  'AccountName' => $accountName,
			  'ChatID' => $chatID,
			  'FriendAccountName' => $receiverAccountName,
			  'ReceiverID' => $receiverAccountID
			  );
        break;

case 'getFriendList':
        $sql = "SELECT 
                        b.FriendAccountID,
                        b.FriendAccountName,
                        b.OwnerAccountID
                FROM  account a
                LEFT JOIN friends b ON a.AccountID = b.OwnerAccountID 
                WHERE a.AccountID= '$accountID'
                ORDER BY b.FriendAccountName";
        $result = mysqli_query($dbconn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $friendAccountID = $row['FriendAccountID'];
            $sql2 = "SELECT
                        c.ProgramName,
                        b.SchoolName,
                        a.Icon
                     FROM account a
                     LEFT JOIN school b ON a.SchoolID = b.SchoolID
                     LEFT JOIN program c ON a.ProgramID = c.ProgramID AND a.SchoolID = c.SchoolID
                     WHERE a.AccountID = '$friendAccountID'";
            $result2 = mysqli_query($dbconn, $sql2);
            while ($row2 = mysqli_fetch_assoc($result2)) {
                $schoolName = $row2['SchoolName'];
                $programName = $row2['ProgramName'];
                $Icon = $row2['Icon'];
            }
            $data[] = array('OwnerAccountID' => $row['OwnerAccountID'],
        					'AccountID' => $row['FriendAccountID'], 
        					'AccountName' => $row['FriendAccountName'], 
        					'SchoolName' => $schoolName, 
        					'ProgramName' => $programName, 
        					'Icon' => $Icon, 
        					'Checked' => 'false', 
        					'IsGroupChat' => 'N');
        }

        break;

case 'getChatIDAndGroupChatID':
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
		$sql = "UPDATE idlist
	  			SET `number` = `number`	+	1 
	  			WHERE YearOfEntity = YEAR(CURDATE()) AND datatype='GC'
	  			";
		$result = mysqli_query($dbconn, $sql);
		$sql = "SELECT 
			CONCAT(systemName,'-',datatype,'-',YearOfEntity,'-',LPAD(`number`, 5, '0')) As GroupChatID 
	  		FROM idlist
	  		WHERE YearOfEntity = YEAR(CURDATE()) AND datatype='GC'
	  		";
		$result = mysqli_query($dbconn, $sql);
		while ($row = mysqli_fetch_assoc($result)) {
    	$groupID = $row['GroupChatID'];
		}
		$data[]=array('ChatID' => $chatID, 'GroupID' => $groupID);
		break;

case 'addGroupChat':
		$chatID = $_GET['chatID'];
        $receiverAccountID = $_GET['receiverID'];
        $receiverAccountName = $_GET['receiverName'];
        $senderAccountID = $_GET['senderID'];
        $sql = "INSERT INTO 
                            `chat` (ChatID,SenderAccountID,ReceiverAccountID,ReceiverAccountName,CreateDt,CreateBy,UpdateDt,UpdateBy,IsGroupChat)
                VALUES
                            ('$chatID','$senderAccountID','$receiverAccountID','$receiverAccountName',NOW(),'Admin',NOW(),'Admin','Y')
                        ";
        $result = mysqli_query($dbconn, $sql);
        $sql = "INSERT INTO 
                            `groupchat` (ChatID,GroupChatID,GroupChatName,AccountID,CreateDt,CreateBy,UpdateDt,UpdateBy)
                VALUES
                            ('$chatID','$receiverAccountID','$receiverAccountName','$senderAccountID',NOW(),'Admin',NOW(),'Admin')
                        ";
        $result = mysqli_query($dbconn, $sql);
		$data[] = array(
			  'AccountName' => $accountName,
			  'ChatID' => $chatID,
			  'FriendAccountName' => $receiverAccountName,
			  'ReceiverID' => $receiverAccountID
			  );
        break;

}
	echo json_encode($data); 
	mysqli_close($dbconn);
?>
