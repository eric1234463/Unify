<?php
require ("dbconn.php");
session_start();
date_default_timezone_set('Asia/Hong_Kong');
$dbconn = dbconn();
$accountID = $_SESSION["AccountID"];
$accountName = $_SESSION["AccountName"];
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$eventID = $request -> eventID;
$eventRepeatID = $request -> eventRepeatID;
#$editType = $request -> editType;
$eventTitle = $request -> eventTitle;
$eventDescription = $request -> eventDescription;
$eventLocation = $request -> eventLocation;
$fromTime = $request -> fromTime;
$toTime = $request -> toTime;
$fromDate = $request -> fromDate;
$toDate = $request -> toDate;
$personal = $request -> personal;
$repeatType = $request -> repeatType;
$allDay = $request -> allDay;
$repeatUntil = $request -> repeatUntil;
$needSanitizing = array($eventTitle, $eventDescription, $eventLocation);
foreach($needSanitizing as $item) {
    $item = $dbconn -> real_escape_string($item);
    $item = htmlspecialchars($item);
};

$sql = "UPDATE `event`
        SET EventTitle = '$eventTitle',
            Location = '$eventLocation',
            Description = '$eventDescription',
            AllDayEvent = '$allDay',
            FromTime = '$fromTime',
            ToTime = '$toTime',
            RepeatType = '$repeatType',
            FromDateRepeat = '$fromDate',
            ToDateRepeat = '$toDate',
            RepeatUntil = '$toDate'
        WHERE OwnerAccountID = '$accountID' AND
            EventID = '$eventID'
       ";
echo $sql;
mysqli_query($dbconn, $sql);
$sql = "DELETE FROM `event_repeat` 
        WHERE OwnerAccountID = '$accountID' 
        AND EventID = '$eventID'";
mysqli_query($dbconn, $sql);
if (strcmp($repeatType, 'none') != 0) {
    switch($repeatType) {
        case 'daily': 
            $unit = 'D';
            $step = 1;
            break;
        case 'weekly':
            $unit = 'D';
            $step = 7;
            break;
        case 'biweekly':
            $unit = 'D';
            $step = 14;
            break;
        case 'monthly':
            $unit = 'M';
            $step = 1;
            break;
        case 'yearly':
            $unit = 'Y';
            $step = 1;
            break; 
    };

    echo $accountID;
    echo $fromDate;
    echo $repeatUntil;
    $repeatStart = new DateTime($fromDate);
    $repeatEnd   = new DateTime($repeatUntil);


    $interval = new DateInterval("P{$step}{$unit}");
    $period   = new DatePeriod($repeatStart, $interval, $repeatEnd);
    $sql = "SELECT MAX(`EventID`) AS maxID FROM `event`  WHERE OwnerAccountID = '$accountID'";
    $result = mysqli_query($dbconn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $eventID = $row['maxID'];
    }
    foreach ($period as $value ) {echo "running\n";
        $valueFormat = $value->format('Y-m-d');
        echo $valueFormat;
        $sql = "INSERT INTO
                event_repeat
                (
                    OwnerAccountID,
                    EventID,
                    EventDate,
                    UpdateDt,
                    UpdateBy
                )
            VALUES 
                (
                    '$accountID',
                    '$eventID',
                    '$valueFormat',
                    NOW(),
                    '$accountName'
                )";
        echo $sql;
        $result = mysqli_query($dbconn, $sql);
    }
} else {
    // $sql = "SELECT MAX(`EventID`) AS maxID FROM event WHERE OwnerAccountID = '$accountID'";
    // echo $sql;
    // $result = mysqli_query($dbconn, $sql);
    // while ($row = mysqli_fetch_assoc($result)) {
    //     $eventID = $row['maxID'];
    // }
    $sql = "INSERT INTO
                event_repeat
                (
                    OwnerAccountID,
                    EventID,
                    EventDate,
                    UpdateDt,
                    UpdateBy
                )
            VALUES 
                (
                    '$accountID',
                    '$eventID',
                    '$repeatUntil',
                    NOW(),
                    '$accountName'
                )";
    $result = mysqli_query($dbconn, $sql);
    echo $sql;
}

echo 'success?';
mysqli_close($dbconn);

?>
