<?php 
$servername = "localhost";
$username = "u662141035_sts";
$password = "Hellothailand123-";
$db = "u662141035_OT";
//create connection
$con = new mysqli($servername,$username,$password,$db);

//check connection
if($con->connect_error){
    die("Connect failed: ".$con->connect_error);
}


?>