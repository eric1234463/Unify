<?php
require ("dbconn.php");
$dbconn = dbconn();
session_start();
if (!$dbconn) {
    die("dbconnection failed: " . mysqli_dbconnect_error());
}
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
date_default_timezone_set('Asia/Hong_Kong');
$action = $request->action;
switch ($action) {
  case 'createGroupChat':
    $chatID = $request->chatID;
    $receiverAccountID = $request->receiverID;
    $receiverAccountName = $request->receiverName;
    $senderAccountID = $request->senderID;
    $time = date("h:i:s");
    $icon = $request->icon;
    $icon = str_replace('data:image/jpeg;base64,', '', $icon);
    $icon = str_replace(' ', '+', $icon);
    $data = base64_decode($icon);
    $file = "../../photo/groupchat/".$chatID."_".$time.".jpg";
    $color = rand(1,5);
    switch ($color) {
        case 1:
            $usercolor = 'color:#D50000';
            break;
        case 2:
            $usercolor = 'color:#C51162';
            break;
        case 3:
            $usercolor = 'color:#AA00FF';
            break;
        case 4:
            $usercolor = 'color:#304FFE';
            break;
        case 5:
            $usercolor = 'color:#FFD600';
            break;
        default:
            break;
    }
    $success = file_put_contents($file, $data);
    $iconFilaPath = "http://unicomhk.net/fyp/photo/groupchat/".$chatID."_".$time.".jpg";
        $sql = "INSERT INTO 
                            `chat` (ChatID,SenderAccountID,ReceiverAccountID,ReceiverAccountName,CreateDt,CreateBy,UpdateDt,UpdateBy,IsGroupChat)
                VALUES
                            ('$chatID','$senderAccountID','$receiverAccountID','$receiverAccountName',NOW(),'Admin',NOW(),'Admin','Y')
                        ";
        $result = mysqli_query($dbconn, $sql);
        $sql = "INSERT INTO 
                            `groupchat` (ChatID,GroupChatID,GroupChatName,AccountID,CreateDt,CreateBy,UpdateDt,UpdateBy,Icon,UserColor)
                VALUES
                            (
                            '$chatID',
                            '$receiverAccountID',
                            '$receiverAccountName',
                            '$senderAccountID',
                            NOW(),
                            'Admin',
                            NOW(),
                            'Admin',
                            '$iconFilaPath',
                            '$usercolor')
                        ";
        $result = mysqli_query($dbconn, $sql);
 
    break;
}

mysqli_close($dbconn);
?>
