<?php
	die("you must enable it: create_admin.php");
    ini_set('display_errors', 1);
    include "./config.php";
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $link = new mysqli($DB_Host, $DB_User, $DB_Password, $DB_Name);
    if (($link->connect_error) || (mysqli_connect_error())) {
        echo $DB_Host."   ".$DB_User."    ".$DB_Password. "    ".$DB_Name."    ";
        echo "Can\'t connect to MySQL server, wrong data.";
        die;
    }
    $username=$_GET['username'];
    $password=$_GET['password'];
    $email=$_GET['email'];
    $ip=$_SERVER['REMOTE_ADDR'];

    $Salt="0x".md5($username.$password);
    $query="call adduser('$username', {$Salt}, '0', '0', '', '{$ip}', '{$email}', '0', '0', '0', '0', '0', '0', '0', '1970-01-01 08:00:00', ' ', {$Salt})";
    $rs=$link->query($query);

    echo($query);
    echo "------------".$rs."----------";

    $query="SELECT * FROM users WHERE name=?";
    $statement=$link->prepare($query);
    $statement->bind_param('s', $username);
    $statement->bind_result($userId);
    $statement->execute();
    $statement->store_result();
    $rowCount=$statement->num_rows;
    echo($query);
    if ($rowCount > 0) {
        echo $Salt.",".$userId;
        $link -> close();
        die;
    }else{
        echo $link->error;
    }

    $link -> close();
    echo "====".$rowCount;
    die;
?>