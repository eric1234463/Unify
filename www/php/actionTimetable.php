<?php
require ("dbconn.php");
session_start();
date_default_timezone_set('Asia/Hong_Kong');
$dbconn = dbconn();
$accountID = $_SESSION["AccountID"];
$accountName = $_SESSION["AccountName"];
$action = $_GET['action'];
$inputDate = $_GET['inputDate'];
if (isset($_GET['inputID'])) {
    $inputID = $_GET['inputID'];
};


$data = Array();
switch ($action) {
    case 'getData':
    	$sql = "SELECT 
    					*
    			FROM 
    					account a 
    			LEFT JOIN event b ON a.AccountID = b.OwnerAccountID
                LEFT JOIN event_repeat c ON b.EventID = c.EventID
    			WHERE a.AccountID = '$accountID'
    			AND  c.EventDate = '$inputDate'";
    	$result = mysqli_query($dbconn, $sql);
    	while ($row = mysqli_fetch_assoc($result)) {
            $fromTime = date("h:i a", strtotime($row['FromTime']));
            $toTime = date("h:i a", strtotime($row['ToTime']));
    		$data[] = array(
                'eventID' => $row['EventID'], 
                'eventRepeatID' => $row['EventRepeatID'],
                'isPersonal' => $row['Personal'], 
                'isAllDay' => $row['AllDayEvent'],
                'eventTitle' => $row['EventTitle'], 
                'eventDescription' => $row['Description'], 
                'eventLocation' => $row['Location'], 
                'fromTime' => $fromTime, 
                'toTime' => $toTime, 
                'date' => $row['EventDate'],
                'fromDate' => $row['FromDateRepeat'],
                'toDate' => $row['ToDateRepeat'],
                'repeatType' => $row['RepeatType']
            );
    	}

        echo json_encode($data);
        break;

    case 'getDataByID':
        $sql = "SELECT
                    *
                FROM
                    account a
                LEFT JOIN event b ON a.AccountID = b.OwnerAccountID
                WHERE a.AccountID = '$accountID'
                AND b.EventID = '$inputID'";

        $result = mysqli_query($dbconn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $fromTime = date("h:i a", strtotime($row['FromTime']));
            $toTime = date("h:i a", strtotime($row['ToTime']));
            $data[] = array('eventID' => $row['EventID'], 'isPersonal' => $row['Personal'], 'eventTitle' => $row['EventTitle'], 'eventDescription' => $row['Description'], 'eventLocation' => $row['Location'], 'fromTime' => $fromTime, 'toTime' => $toTime, 'date' => $row['EventDate']);
        }
        echo json_encode($data);
        break;

    case 'deleteEvent':
        $deleteEventType = $_GET['deleteEventType'];
        $eventID = $_GET['eventID'];
        switch ($deleteEventType) {
            case 'deleteAll':
                $sql = "DELETE FROM `event_repeat` 
                        WHERE OwnerAccountID = '$accountID' 
                        AND EventID = '$eventID'
                         ";
                         echo $sql;
                mysqli_query($dbconn, $sql);
                $sql = "DELETE FROM `event`
                        WHERE OwnerAccountID = '$accountID'
                        AND EventID = '$eventID'";
                mysqli_query($dbconn, $sql);
                //$sql = "DELETE FROM event WHERE EventID = '$eventID'";
                //$result = mysqli_query($dbconn, $sql); 
                break;
            
            case 'deleteSingle':
                $eventRepeatID = $_GET['deleteEventRepeatID'];
                $sql = "DELETE FROM event_repeat WHERE OwnerAccountID = '$accountID' AND EventID = '$eventID' AND EventRepeatID = '$eventRepeatID' ";
                echo $sql;
                mysqli_query($dbconn, $sql);
                break;
        }
        

}

mysqli_close($dbconn);
?>