<?php
    include("../dbconnect.php");
    include("../notify.php");
    $transaction =$_GET['transaction'];
    $status = $_GET['status'];

    
    //cancle
    //cancleEmp
	//$sql = mysqli_query($con, "UPDATE `transaction` Set approve_status='waiting' WHERE transaction_id = '$transaction'");
    if ($status == "approve"){
        cancle($transaction);
        cancleEmp($transaction);
    }
    if ($status == "waiting"){
        cancleEmp($transaction);
    }
    $sql = mysqli_query($con, "DELETE FROM `transaction` WHERE transaction_id = '$transaction'");
    if (sql){
        header("Location: loginLine1.html");
    }
?>