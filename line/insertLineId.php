<?php
include("../screen/dbconnect.php"); // Using database connection file here
$line_id = $_GET['w1'];
$user_id = $_GET['user_id'];
echo $line_id;
echo $user_id;

$sql = mysqli_query($con, "UPDATE `users` SET `line_id`='$line_id' WHERE `user_id`='$user_id'");
if($sql){
    header("Location: complete.php");
}

?>