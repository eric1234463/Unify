<?php
require ("dbconn.php");
$dbconn = dbconn();
session_start();
if (!$dbconn) {
    die("dbconnection failed: " . mysqli_dbconnect_error());
}
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$action = $request->action;
switch ($action) {
  case 'createNewUser':
    $password = $request->password;
    $username = $request->username;
    $year = $request->year;
    $school = $request->school;
    $gender = $request->gender;
    $program = $request->program;
    $telephone = $request->telephone;
    $email = $request->email;
    $ownPin = $request->pin;
    $pin = $_SESSION['pin'];
    $sql = "SELECT * FROM account WHERE AccountName ='$username'";
    $result = mysqli_query($dbconn, $sql);
    $num_rows = mysqli_num_rows($result);
    if ($num_rows > 0) {
       echo "failed";
    } 
    else if($pin != $ownPin){
       echo "failed";
    }
    else{
        $sql = "UPDATE idlist
              SET `number` = `number`   +   1 
              WHERE YearOfEntity = YEAR(CURDATE()) AND datatype='AC'
              ";
        $result = mysqli_query($dbconn, $sql);
        $sql = "SELECT 
                    CONCAT(systemName,'-',datatype,'-',YearOfEntity,'-',LPAD(`number`, 7, '0')) As AccountID 
              FROM idlist 
              WHERE YearOfEntity = YEAR(CURDATE()) AND datatype='AC'
              ";
        $result = mysqli_query($dbconn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $accountID = $row['AccountID'];
        }
        
        $sql = "INSERT INTO 
                account(AccountID,AccountName,AccountPassword,Gender,SchoolID,ProgramID,YearOfEntry,telephone,createDt,createBy,UpdateDt,UpdateBy,`Email`,Icon,Banner)
             VALUES
                ('$accountID','$username','$password','$gender','$school','$program','$year','$telephone',NOW(),'System',NOW(),'System','$email','img/icon.png','img/background.jpg')";
        $result = mysqli_query($dbconn, $sql);
        echo $sql;
        $sql = "INSERT INTO 
                        accountsecret(AccountID,SecretID,createDt,createBy,UpdateDt,UpdateBy)
                VALUES
                        ('$accountID','$school',NOW(),'System',NOW(),'System')";
        $result = mysqli_query($dbconn, $sql);
        $_SESSION["AccountID"] = $accountID;
        echo "success";
    }
    break;
  
  case 'sendEmail':
    $pin = mt_rand(1000, 9999);
    $email = $request->email;
    $message = 
    "
          Dear User,

                    We are Unfiy and here are your pin for your new account :  ".$pin."
                    
          Best Regards,
          Unfiy
    ";
    echo $message;
    mail($email, 'Verify of New User From Unfiy Apps Company', $message);
    $_SESSION['pin'] = $pin;
    break;
}

mysqli_close($dbconn);
?>
