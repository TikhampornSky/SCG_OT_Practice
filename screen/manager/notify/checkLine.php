<?php

//working
include("screen/dbconnect.php"); // Using database connection file here
// Start the session
session_start();

$line_id = $_GET['w1'];
echo $line_id;
$line_name = $_GET['name'];
$line_email = $_GET['email'];
echo $line_name;
echo $line_email;
echo 
$sql = mysqli_query($con, "SELECT * FROM approver WHERE line_id = '$line_id'");


if(mysqli_affected_rows($con) == '1'){
    $rs = $sql->fetch_object();
    $_SESSION["user_id"] = $rs->approver_id;
    // header("Location: ../manager/information.php");
}else{
    header( "Location: register.php?w1={$line_id}&name=$line_name&email=$line_email");
}
// woking now
?>