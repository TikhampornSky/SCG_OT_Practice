<?php
include("../dbconnect.php");
include("../test.php");
include("../notify.php");
$transaction_id = $_POST['transaction_id'];
$time_start = $_POST['time_start'];//กรณีมีการแ้ไขจากหัวหน้า
$time_end = $_POST['time_end'];//กรณีมีการแ้ไขจากหัวหน้า
$msg = $_POST['msg'];
$date = $_POST['date'];
$date1 = $_POST['date1'];//วันเวลาเริ่มที่ขอเดิม
$date2 = $_POST['date2'];//วันเวลาสิ้นสุดที่ขอเดิม
$employee_id = $_POST['employee_id'];
$ot_type = $_POST['ot_type'];

session_start();
$line_id = $_SESSION["user_id"];
$approver_id = $_SESSION["user_id"];
echo $approver_id;
// $id =  $_SESSION["id"];
// echo $id;
$sql = mysqli_query($con, "SELECT * FROM approverInfo WHERE approver_id = '$approver_id'");
$rs = $sql->fetch_object();
$department = $rs->department_id;
$name = $rs->approver_name;
$lastname = $rs->approver_lastname;

date_default_timezone_set('Asia/Bangkok');
$time_stamp = date("Y-m-d H:i:s");

if($time_start != "" && $time_end != ""){//กรณีมีการแ้ไขจากหัวหน้า
    $text = strval($time_start);
    $text1 = strval($time_end);
    if (decimalHours($text1) < 1){
        $midnight = decimalHours($text1)+24;
        $text1 = decimalToHours($midnight);
    }
    $t1 = decimalHours('12:01');
    $t2 = decimalHours('13:00');
    $x = decimalHours($text);
    $y = decimalHours($text1);
    if ($ot_type == "lunch"){
         if ($y > $t2) { //กรณีเวลาสิ้น <13:00
            $text1="13:00";
        }else if ($x < $t1){//กรณีเวลาเริ่มต้น >=12:01 และ <13:00
            $text="12:00";
        }
    }
    $dateTimeObject1 = date_create($text);
    $dateTimeObject2 = date_create($text1);
    $difference = date_diff($dateTimeObject2, $dateTimeObject1);
    $time = ("$difference->h:$difference->i");
    $hour = ("$difference->h");
    $minute = ("$difference->i");
    if ($ot_type == "normal"){
        if ($y >= $t1 && $y < $t2) { //กรณีเวลาสิ้นสุด >=12:01 และ <13:00
            $mm1=explode(":",$time_end);
            $minute = intval($minute)-intval($mm1[1]);
        }else if ($x >= $t1 && $x < $t2){//กรณีเวลาเริ่มต้น >=12:01 และ <13:00
            $mm1=explode(":",$time_start);
            $minute = intval($minute)-(60-intval($mm1[1]));
        }
    }
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
    if ($ot_type == "normal"){
        if ($y >= $t1) {
            if ($x < $t1 && $y >= $t2){
                $gap = $gap-1;
            }
        }
    }
}



// if (strlen($minute)==1){
//         $minute = "0".$minute;
//     };
// $range = $hour.":".$minute;
// $gap = decimalHours($range);

// $t1 = decimalHours('12:00');
// $t2 = decimalHours('13:00');
// $x = decimalHours($date1);
// $y = decimalHours($date2);


// if ($y >= $t1) {
//     if ($x>0 && $x<= $t2){
//         $gap = $gap-1;
//     }
// }
// if(decimalToHours($text1)<decimalToHours($text)){
// 	$gap = 24-$gap;
// }else{
//     $gap = $gap;
// }


if (isset($_POST["approve"])) {
    if ($time_start != "" && $time_end != ""){//กรณีมีการแ้ไขจากหัวหน้า
        $approve = mysqli_query($con,"UPDATE transaction SET hour_range ='$gap', approve_status='edit',consider_time = '$time_stamp',approver_name = '$name $lastname',time_start='$date $time_start',time_end='$date $time_end', edit_start='$date1',edit_end='$date2' WHERE transaction_id='$transaction_id'");
        if ($approve){
            edit($transaction_id);
            include "../thisWeek.php";
            $time = new week();
            $time->set_day($date);
            $start_monday =  $time->get_start();
            $end_monday = $time->get_end();
            $datetime = new DateTime($date);
            $sm = new DateTime($start_monday);
            $em = new DateTime($end_monday);
            if ($datetime->format("Y-m-d H:i:s") >= $sm->format("Y-m-d 00:00:00") && $datetime->format("Y-m-d H:i:s")<= $em->format("Y-m-d 08:01:00") ){
                echo "inloop";
                checkcheck($start_monday,$end_monday,$employee_id);
            }
            mysqli_close($con);
            header("Location: ../sendMailToEmployee.php?transaction=$transaction_id");
            exit;
        }
    }else{
        $approve = mysqli_query($con,"UPDATE transaction SET approve_status='approve',consider_time = '$time_stamp',approver_name = '$name $lastname' WHERE transaction_id='$transaction_id'");
        if ($approve){
            approve($transaction_id);
            header("Location: ../sendMailToEmployee.php?transaction=$transaction_id");
            mysqli_close($con);
            // header("Location: complete.php");
            exit;
        }
    }
}

if (isset($_POST["reject"])) {
    $reject = mysqli_query($con,"UPDATE transaction SET approve_status='reject', reject_msg='$msg',consider_time='$time_stamp',approver_name='$name $lastname' WHERE transaction_id='$transaction_id'");
    if ($reject){
        mysqli_close($con);
        reject($transaction_id);
        header("Location: ../sendMailToEmployee.php?transaction=$transaction_id");
        exit;
    }
}

?>