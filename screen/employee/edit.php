<?php
include("../dbconnect.php");
include("../test.php");
include("../notify.php");//send notify
$transaction_id = $_GET['transaction'];
$date = $_GET['date'];
$time_start = $_GET['time_start'];
$time_end = $_GET['time_end'];
$msg = $_GET['msg'];

$text = strval($time_start);
$text1 = strval($time_end);
if (decimalHours($text1) < 1){
    $midnight = decimalHours($text1)+24;
    $text1 = decimalToHours($midnight);
}
$dateTimeObject1 = date_create($text);
$dateTimeObject2 = date_create($text1);
$difference = date_diff($dateTimeObject2, $dateTimeObject1);
$time = ("$difference->h:$difference->i");
$hour = ("$difference->h");
$minute = ("$difference->i");
if (strlen($minute)==1){
        $minute = "0".$minute;
    };
$range = $hour.":".$minute;
$gap = decimalHours($range);
if(decimalToHours($text1)<decimalToHours($text)){
	$gap = 24-$gap;
}else{
    $gap = $gap;
}


$sql = mysqli_query($con, "UPDATE transaction SET date='$date', time_start='$date $time_start', time_end='$date $time_end', hour_range=$gap, request_msg='$msg', approve_status='waiting'  WHERE transaction_id = '$transaction_id'");
echo "UPDATE transaction SET date='$date', time_start='$date $time_start', time_end='$date $time_end', hour_range=$gap, request_msg='$msg', approve_status='waiting'  WHERE transaction_id = '$transaction_id'";
if (sql){
    request($date);
    include "../thisWeek.php";
    $time = new week();
    $time->set_day($date);
    $start_monday =  $time->get_start();
    $end_monday = $time->get_end();
    $datetime = new DateTime($date);
    $sm = new DateTime($start_monday);
    $em = new DateTime($end_monday);
    echo $start_monday;
    // echo $sm;
    echo "check($start_monday,$end_monday)";
    if ($datetime->format("Y-m-d H:i:s") >= $sm->format("Y-m-d 00:00:00") && $datetime->format("Y-m-d H:i:s")<= $em->format("Y-m-d 08:01:00") ){
        echo "inloop";
        check($start_monday,$end_monday);
    }
    header("Location: ../sendMail.php?user_id=$name");
}
?>