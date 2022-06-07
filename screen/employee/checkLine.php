<?php

//working
function checkLine(){
    include("../dbconnect.php"); // Using database connection file here
    $line_id = $_GET['w1'];
    $line_name = $_GET['name'];
    $line_email = $_GET['email'];

    $sql = mysqli_query($con, "SELECT * FROM employee WHERE line_id = '$line_id'");
    $rs = $sql->fetch_object();
    
    // Start the session
    session_start();
    // Set session variables
    $_SESSION["line_id"] = $line_id;
    $_SESSION["user_id"] = $rs->employee_id;
    
    if(mysqli_affected_rows($con) == '1'){
         $string="pass";
         return ($string);
    }else{
        $sql1 = mysqli_query($con, "SELECT * FROM approver WHERE line_id = '$line_id'");
        if(mysqli_affected_rows($con) == '1'){
            header( "Location: ../../permission.html");
           
        }else{
            header( "Location: ../../register.php?w1={$line_id}&name=$line_name&email=$line_email");
        }
    }
}

function userType($type){
    // Start the session
    session_start();
    // Set session variables
    $_SESSION["user_type"] = $type;
}

// woking now

?>