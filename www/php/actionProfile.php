<?php
require ("dbconn.php");
session_start();
$dbconn = dbconn();
$accountID = $_SESSION["AccountID"];
$accountName = $_SESSION["AccountName"];
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
date_default_timezone_set('Asia/Hong_Kong');
$action = $request->action;
switch ($action) {
    case 'editProfile':

            /* COVER PHOTO*/
        $time = date("h:i:s");
        $uploadPhoto = $request->uploadPhoto;
        $uploadIcon = $request->uploadIcon;
        if($uploadPhoto=='Y'){
            $coverPhoto = $request->CoverPhoto;
            $coverPhoto = str_replace('data:image/jpeg;base64,', '', $coverPhoto);
            $coverPhoto = str_replace(' ', '+', $coverPhoto);
            $data = base64_decode($coverPhoto);
            $file = "../../photo/coverphoto/".$accountID."_".$time.".jpg";
            $success = file_put_contents($file, $data);
            $coverPhotoFilaPath = "http://unicomhk.net/fyp/photo/coverphoto/".$accountID."_".$time.".jpg";
            $sql = "UPDATE account SET Banner = '$coverPhotoFilaPath' WHERE accountID = '$accountID'";
            $result = mysqli_query($dbconn, $sql);
        }

        /* ICON*/
        if($uploadIcon=='Y'){
            $icon = $request->icon;
            $icon = str_replace('data:image/jpeg;base64,', '', $icon);
            $icon = str_replace(' ', '+', $icon);
            $data = base64_decode($icon);
            $file = "../../photo/icon/".$accountID."_".$time.".jpg";
            $success = file_put_contents($file, $data);
            $iconFilaPath = "http://unicomhk.net/fyp/photo/icon/".$accountID."_".$time.".jpg";
            $sql = "UPDATE account SET Icon = '$iconFilaPath' WHERE accountID = '$accountID'";
            $result = mysqli_query($dbconn, $sql);
        }

        $intro = $request->intro;
        $intro = $dbconn ->real_escape_string($intro);
        $birthday = $request->birthday;
        $Height = $request->Height;
        $Weight = $request->Weight;
        $Telephone = $request->Telephone;
        $Experience = $request->Experience;
        $Relationship = $request->Relationship;
        $Sexuality = $request->Sexuality;

        $sql = 
                "UPDATE account 
                SET SelfIntroduction = '$intro', 
                    Birthday = '$birthday',
                    Height  = $Height,
                    Weight = $Weight,
                    Telephone = '$Telephone',
                    Relationship = '$Relationship',
                    Experience = '$Experience',
                    Sexuality = '$Sexuality'
                WHERE accountID = '$accountID'";
        echo $sql;
        $result = mysqli_query($dbconn, $sql);
        break;

    case 'createStory':
        $photoDiary = $request->photoDiary;
        $title = $request->title;
        $sql = "SELECT MAX(StoryID) As StoryID FROM `storydiary`";
            $result = mysqli_query($dbconn, $sql);
            while($row = mysqli_fetch_assoc($result)) {
                $StoryID = $row['StoryID'] + 1;
        }
        $i = 0;
        foreach ($photoDiary as $key => $photo) {
            foreach ($photo as $attr => $value){
                if($attr =='Photo'){
                    $i = $i + 1;
                    $time = date("Y-m-d");
                    $value = str_replace('data:image/jpeg;base64,', '', $value);
                    $value = str_replace(' ', '+', $value);
                    $data = base64_decode($value);
                    $file = "../../photo/storydiary/".$accountID."_".$i."_".$time.".jpg";  
                    $success = file_put_contents($file, $data);
                    $photoPath = "http://unicomhk.net/fyp/photo/storydiary/".$accountID."_".$i."_".$time.".jpg";  
                }
                if($attr =='Status'){
                    $status = $value;
                    $status = $dbconn ->real_escape_string($status);
                }
            }
            $sql = "INSERT INTO `storydiary`
                        (AccountID,StoryID,StoryTitle,StoryDesc,PhotoSrc,CreateDt,CreateBy,UpdateDt,UpdateBy)
                    VALUES
                        ('$accountID','$StoryID','$title','$status','$photoPath',NOW(),'$accountName',NOW(),'$accountName')";
            echo $sql;
            $result = mysqli_query($dbconn, $sql);
        }
        break;
    case 'createGossip':
        $title = $request->title;
        $desc = $request->desc;
        $icon = $request->icon;
        $time = date("Y-m-d");
        $icon = str_replace('data:image/jpeg;base64,', '', $icon);
        $icon = str_replace(' ', '+', $icon);
        $data = base64_decode($icon);
        $file = "../../photo/school/".$accountID."_".$time.".jpg";
        $success = file_put_contents($file, $data);
        $iconFilaPath = "http://unicomhk.net/fyp/photo/school/".$accountID."_".$time.".jpg";
        $sql = "INSERT INTO `secret`
                        (SecretDesc,SecretName,Icon,CreateDt,CreateBy,UpdateDt,UpdateBy)
                VALUES
                    ('$desc','$title','$iconFilaPath',NOW(),'$accountName',NOW(),'$accountName')";        
        $result = mysqli_query($dbconn, $sql);

        break;
     case 'createbook':
        $name = $request->name;
        $desc = $request->desc;
        $icon = $request->icon;
        $price = $request->price;
        $time = date("Y-m-d");
        $icon = str_replace('data:image/jpeg;base64,', '', $icon);
        $icon = str_replace(' ', '+', $icon);
        $data = base64_decode($icon);
        $file = "../../photo/book/".$accountID."_".$time.$name.".jpg";
        $success = file_put_contents($file, $data);
        $iconFilaPath = "http://unicomhk.net/fyp/photo/book/".$accountID."_".$time.$name.".jpg";
        $sql = "INSERT INTO `secondhandbook`
                        (OwnerAccountID,Name,`Desc`,Price,PhotoSrc,CreateDt,CreateBy)
                VALUES
                    ('$accountID','$name','$desc','$price','$iconFilaPath',NOW(),'System')";        
        $result = mysqli_query($dbconn, $sql);
        break;

}
mysqli_close($dbconn);
?>
