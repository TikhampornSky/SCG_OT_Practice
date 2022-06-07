<?php

function checkLine($line_id){
    include("../dbconnect.php"); // Using database connection file here
    $line_id = $_GET['w1'];
    // echo $line_id;
    $line_name = $_GET['name'];
    // echo $line_name;
    
    $sql = mysqli_query($con, "SELECT * FROM approver WHERE line_id = '$line_id'");
    $rs = $sql->fetch_object();
    
    // Start the session
    session_start();
    // Set session variables
    $_SESSION["line_id"] = $line_id;
    $_SESSION["user_id"] = $rs->approver_id;
    
    
    if(mysqli_affected_rows($con) == '1'){
        $string = "pass";
        return($string);
    }else{
        header("Location: ../../permission.html");
    }
}


// woking now

?>