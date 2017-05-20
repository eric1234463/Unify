<?php
	require ("dbconn.php");
	session_start();
	$dbconn = dbconn();
	$action = $_GET['action'];  
	$data = Array();

	switch($action){
		case "checkMentor":
			$sql = "SELECT * FROM `mentorship` WHERE AccountID = '$_SESSION[AccountID]'";
			$result = mysqli_query($dbconn, $sql);
			if(mysqli_num_rows($result) > 0)
				echo TRUE;
			else
				echo FALSE;
		break;
		case "mentor":
			$sql = "SELECT 
						AccountID, AccountName, Gender, YearOfEntry, SelfIntroduction, SchoolName, ProgramName 
					FROM `account`
					LEFT JOIN `school` ON `school`.SchoolID = `account`.SchoolID
					LEFT JOIN `program` ON `program`.ProgramID = `account`.ProgramID
					WHERE 
						Mentor = 'Y' AND 
						`account`.ProgramID = (SELECT ProgramID FROM `account` WHERE AccountID = '$_SESSION[AccountID]') AND
						`account`.AccountID NOT IN (SELECT DISTINCT MentorID FROM `mentorship`) 
					LIMIT 3 ";
			$result = mysqli_query($dbconn, $sql);
			
	        while ($row = mysqli_fetch_assoc($result)) {
        		$row['SelfIntroduction'] = ">> ".$row['SelfIntroduction'] ;
    			$row['YearOfEntry'] = date("Y") - $row['YearOfEntry'];
        		if(date("m")>=9)
        			$row['YearOfEntry'] =  1;
        		if($row['YearOfEntry'] > 3)
        			$row['YearOfEntry'] = "UnderGrad";
        		else
        			$row['YearOfEntry'] = "Year ".$row['YearOfEntry'];

	            $data[] = array(
	            	'id' => $row['AccountID'],
	            	'name' => $row['AccountName'],
	            	'gender' => $row['Gender'],
	            	'school' => $row['SchoolName'],
	            	'progam' => $row['ProgramName'],
	            	'year' => $row['YearOfEntry'],
	            	'intro' => $row['SelfIntroduction']
	            	);
	        }
	        if(mysqli_num_rows($result) < 3){
	        	$tmp_count = 3 - mysqli_num_rows($result);
	        	mysqli_free_result($result);

        		$sql = "SELECT 
							AccountID, AccountName, Gender, YearOfEntry, SelfIntroduction, SchoolName, ProgramName 
						FROM `account`
						LEFT JOIN `school` ON `school`.SchoolID = `account`.SchoolID
						LEFT JOIN `program` ON `program`.ProgramID = `account`.ProgramID
						WHERE 
							Mentor = 'Y' AND 
							`account`.ProgramID = (SELECT ProgramID FROM `account` WHERE AccountID = '$_SESSION[AccountID]') AND
							`account`.AccountID IN (SELECT MentorID as AccountID FROM `mentorship` GROUP BY MentorID ORDER BY count(*) ASC) 
						LIMIT ".$tmp_count;
				
				$result = mysqli_query($dbconn, $sql);
				
				while ($row = mysqli_fetch_assoc($result)) {
	        		$row['SelfIntroduction'] = ">> ".$row['SelfIntroduction'] ;
	    			$row['YearOfEntry'] = date("Y") - $row['YearOfEntry'];
	        		if(date("m")>=9)
	        			$row['YearOfEntry'] =  1;
	        		if($row['YearOfEntry'] > 3)
	        			$row['YearOfEntry'] = "UnderGrad";
	        		else
	        			$row['YearOfEntry'] = "Year ".$row['YearOfEntry'];

		            $data[] = array(
		            	'id' => $row['AccountID'],
		            	'name' => $row['AccountName'],
		            	'gender' => $row['Gender'],
		            	'school' => $row['SchoolName'],
		            	'progam' => $row['ProgramName'],
		            	'year' => $row['YearOfEntry'],
		            	'intro' => $row['SelfIntroduction']
	            	);
		        }
	        }
	        echo json_encode($data);
		break;
		case "addmentor":
			$mentor_ac = $_GET['mentor'];
			echo $mentor_ac;
			$sql = "INSERT INTO `mentorship`
					(MentorID, AccountID)
					VALUES
					('$mentor_ac','$_SESSION[AccountID]')";


			if(mysqli_query($dbconn, $sql))
				echo "success";
		break;

	}
	mysqli_close($dbconn);
?>