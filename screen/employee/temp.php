<?php
include("../dbconnect.php"); //connect to database
require_once("../test.php"); //convert date to decimal
include("../notify.php");//send notify

session_start(); //calling session
$user_id = $_SESSION["user_id"]; //employee id
$type = $_SESSION["user_type"];

//get id from url
$ot = $_GET['ot'];
$date = $_GET['date'];
$time_start = $_GET['time_start'];
$time_end = $_GET['time_end'];
$request_msg = $_GET['msg'];
$request_detail = $_GET['detail'];
if ($request_msg == "other"){
    $request_msg = $_GET['reason'];
}
//done

// new variable
$in = "";
$out = "";
// done

if ($type == "normal"){
    $type = "(พนักงานปกติ)";
    $in = '07:30';
    $out = '07:29';
}else {
    $type = "(พนักงานกะ)";
    $in = '08:00';
    $out = '07:59';
}

//to get employee name
$sql = mysqli_query($con, "SELECT * FROM employee a , employeeInfo b WHERE a.employee_id = '$user_id' AND a.employee_id = b.employee_id ");
$rs = $sql->fetch_object();
$name = $rs->employee_name;
$lastname = $rs->employee_lastname;
//done

//get timezone to fine a date of 1stmonday and last monday
include "../thisWeek.php";
date_default_timezone_set('Asia/Bangkok');
$time_stamp = date("Y-m-d H:i:s");
$time = new week();
$time->set_day($time_stamp);
$start_monday =  $time->get_start();
$end_monday = $time->get_end();

$start_monday1 =  "";
$end_monday1 = "";




//find hour_range from time Start and End
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
$mm = ("$difference->i");
$t1 = decimalHours('12:01');
$t2 = decimalHours('13:00');
$x = decimalHours($time_start);
$y = decimalHours($time_end);
if ($ot == "normal"){
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

// echo $range;
// echo $gap;
// done


//get total hour of that weeek
$datetime = new DateTime($date);
$sm = new DateTime($start_monday);
$em = new DateTime($end_monday);
if (($datetime->format("Y-m-d H:i:s") >= $sm->format("Y-m-d 08:00:00")) && ($datetime->format("Y-m-d H:i:s")<= $em->format("Y-m-d 08:01:00")) ){
    // echo "1";
    
    // delete this one
    if (date('l', strtotime($date))=="Monday"){
        if (decimalHours($time_end) > decimalHours("08:00")){
            $time_stamp = ($date);
            $time = new week();
            $time->set_dayy($time_stamp);
            $start_monday =  $time->get_start();
            $end_monday = $time->get_end();
        }
    }
    // delete this one
    
    $week = mysqli_query($con, "SELECT SUM(hour_range) AS approve FROM transaction WHERE user_id='$user_id' AND time_start BETWEEN '$start_monday $in' AND '$end_monday $out' AND approve_status = 'approve'");
    $week2 = mysqli_query($con, "SELECT SUM(hour_range) AS waiting FROM transaction WHERE user_id ='$user_id' AND time_start BETWEEN '$start_monday $in' AND '$end_monday $out' AND approve_status = 'waiting'");
    $week3 = mysqli_query($con, "SELECT SUM(hour_range) AS edit FROM transaction WHERE user_id ='$user_id' AND time_start BETWEEN '$start_monday $in' AND '$end_monday $out' AND approve_status = 'edit'");
    $rsweek = $week->fetch_object();
    $rsweek2 = $week2->fetch_object();
    $rsweek3 = $week3->fetch_object();
    $approve = $rsweek->approve;
    $waiting = $rsweek2->waiting;
    $edit = $rsweek3->edit;
    
    if ($approve == "") {$approve = 0;}
    if ($waiting == "") {$waiting = 0;}
    if ($edit == "") {$edit = 0;}
    $start_monday1 =  $start_monday;
    $end_monday1 = $end_monday;
}else{
    // echo "2";
    $time_stamp = ($date);
    $time = new week();
    
    if (decimalHours($time_end)>=0 AND decimalHours($time_end)<1){
        $endd = 24;
    }else {
        $endd = decimalHours($time_end);
    }
    
    if (date('l', strtotime($date))=="Monday"){
        if ($endd < decimalHours("08:01")){
            $time_stamp = date('Y-m-d',(strtotime ( '-7 day' , strtotime ($date) ) ));
        }else{
            $time_stamp = $date;
        }
        
    }
    
    $time->set_dayy($time_stamp);

    $start_monday1 =  $time->get_start();
    $end_monday1 = $time->get_end();
    $week = mysqli_query($con, "SELECT SUM(hour_range) AS approve FROM transaction WHERE user_id='$user_id' AND time_start BETWEEN '$start_monday1 $in' AND '$end_monday1 $out' AND approve_status = 'approve'");
    $week2 = mysqli_query($con, "SELECT SUM(hour_range) AS waiting FROM transaction WHERE user_id ='$user_id' AND time_start BETWEEN '$start_monday1 $in' AND '$end_monday1 $out' AND approve_status = 'waiting'");
    $week3 = mysqli_query($con, "SELECT SUM(hour_range) AS edit FROM transaction WHERE user_id ='$user_id' AND time_start BETWEEN '$start_monday1 $in' AND '$end_monday1 $out' AND approve_status = 'edit'");
    $rsweek = $week->fetch_object();
    $rsweek2 = $week2->fetch_object();
    $rsweek3 = $week3->fetch_object();
    $approve = $rsweek->approve;
    $waiting = $rsweek2->waiting;
    $edit = $rsweek3->edit;
    
    if ($approve == "") {$approve = 0;}
    if ($waiting == "") {$waiting = 0;}
    if ($edit == "") {$edit = 0;}
    // $approve = 0;
    // $waiting = 0;
    // $edit = 0;
}


// $t1 = decimalHours('12:01');
// $t2 = decimalHours('13:00');
// $x = decimalHours($time_start);
// $y = decimalHours($time_end);
// if ($ot == "normal"){
//     if ($y >= $t1 && $y < $t2) { //กรณีเวลาสิ้นสุด >=12:01 และ <13:00
//         $mm1=explode(":",$time_end);
//         $gap = $gap-(decimalHours($mm1[1])/60);
//     }else if ($x >= $t1 && $x < $t2){//กรณีเวลาเริ่มต้น >=12:01 และ <13:00
//         $mm1=explode(":",$time_start);
//         $gap = $gap-((60-decimalHours($mm1[1]))/60);
//     }else if($x < $t1 && $y >= $t2){ //กรณีที่เวลาเริ่มต้น <12:01 และ เวลาสิ้นสุด >=13:00
//         $gap = $gap-1;
//     }else{
//         $gap = $gap;
//     }
// }

if ($ot == "normal"){
    if ($y >= $t1) {
        if ($x < $t1 && $y >= $t2){
            $gap = $gap-1;
        }
    }
}
$total = $approve+$waiting+$edit+$gap;

// echo "check($start_monday1,$end_monday1)";

date_default_timezone_set('Asia/Bangkok');
$time_stamped = date("Y-m-d H:i:s");
// insert to transaction table and check for exist value
$sql = "INSERT INTO `transaction`(`user_id`, `date`, `time_start`, `time_end`, `time_stamp`, `hour_range`, `approve_status`, `request_msg`,`employee_type`,`request_detail`,`ot_type`) VALUES ('$user_id','$date','$date $time_start','$date $time_end','$time_stamped',hour_range+$gap,'waiting','$request_msg','$type','$request_detail','$ot')";
if (isset($_POST["save"])) {
    $check = mysqli_query($con, "SELECT * FROM transaction WHERE date ='$date' AND time_start = '$date $time_start' AND time_end = '$date $time_end' AND user_id = '$user_id' AND approve_status = 'waiting'");
    if(mysqli_affected_rows($con) >= 1){
        echo "<script>
        alert('คุณได้ทำการขออนุมัติช่วงเวลานี้แล้ว กรุณาทำรายการใหม่');
        window.location.href='https://liff.line.me/1656632478-Wl457ZM8';
        </script>";
    }else{
        $result = mysqli_query($con, $sql);
        if ($result) {
        request($date);
        // echo "check($start_monday1,$end_monday1)";
            if ($datetime->format("Y-m-d H:i:s") >= $sm->format("Y-m-d 00:00:00") && $datetime->format("Y-m-d H:i:s")<= $em->format("Y-m-d 08:01:00") ){
                check($start_monday1,$end_monday1);
                header("Location: ../sendMail.php?hour=$total&week=thisweek&date=$date");
            }else{
                header("Location: ../sendMail.php?hour=$total&week=nextweek&date=$date");
            }
        }
    }
}
// done
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>สรุปข้อมูล</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../../vendors/feather/feather.css">
    <link rel="stylesheet" href="../../vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../../vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="../../vendors/select2/select2.min.css">
    <link rel="stylesheet" href="../../vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="../../css/vertical-layout-light/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="../../images/favicon.png" />

    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</head>

<body>
    <div class="container-scroller">
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row" >
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center" style="width: 80px;" >
                <a class="navbar-brand brand-logo mr-5"><img src="../../images/1.jpeg" class="mr-2" alt="logo" /></a>
                <a class="navbar-brand brand-logo-mini"><img src="../../images/img.png" class="mr-2" style="width: 75px; height: 50px;" /></a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end" style="width: calc(100% - 80px);">
            <span class="text-dark">คุณ <?=$name ?> <?=$lastname?></span>
            </div>
        </nav>
        <div class="container-fluid page-body-wrapper">
            <div class="theme-setting-wrapper">
                <div id="settings-trigger"><i class="ti-settings"></i></div>
                <div id="theme-settings" class="settings-panel">
                    <i class="settings-close ti-close"></i>
                    <p class="settings-heading">SIDEBAR SKINS</p>
                    <div class="sidebar-bg-options selected" id="sidebar-light-theme">
                        <div class="img-ss rounded-circle bg-light border mr-3"></div>Light
                    </div>
                    <div class="sidebar-bg-options" id="sidebar-dark-theme">
                        <div class="img-ss rounded-circle bg-dark border mr-3"></div>Dark
                    </div>
                    <p class="settings-heading mt-2">HEADER SKINS</p>
                    <div class="color-tiles mx-0 px-4">
                        <div class="tiles success"></div>
                        <div class="tiles warning"></div>
                        <div class="tiles danger"></div>
                        <div class="tiles info"></div>
                        <div class="tiles dark"></div>
                        <div class="tiles default"></div>
                    </div>
                </div>
            </div>
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12  stretch-card">
                                    <div class="card">
                                        <div class="card-body ">
                                                            <div class="card-body  text-dark " style="text-align: center; font-size: 150%;">สรุปข้อมูล</div>
                                                            <div class="form-group ">
                                                                <label for="exampleInputEmail3">วันที่จะขอ OT</label>
                                                                <label name="date" class="form-control bg-inverse-secondary"><?php echo DateThai($date); ?></label>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <label for="exampleInputPassword4">เวลาเริ่ม</label>
                                                                    <div class="form-group row">
                                                                        <div class="col-12">
                                                                            <label name="date" class="form-control bg-inverse-secondary"><?php echo $time_start; ?></label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6">
                                                                    <label for="exampleInputPassword4">เวลาสิ้นสุด</label>
                                                                    <div class="form-group row">
                                                                        <div class="col-12">
                                                                            <label name="date" class="form-control bg-inverse-secondary"><?php echo $time_end; ?></label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <label for="exampleInputPassword4">จำนวนชั่วโมง</label>
                                                                    <div class="form-group row">
                                                                        <div class="col-12">
                                                                        <label name="date" class="form-control bg-inverse-secondary" style="text-align:center;"><?php echo decimalToHours($gap); ?> ชม.</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6">
                                                                    <label for="exampleInputPassword4">รวมทั้งสัปดาห์</label>
                                                                    <div class="form-group row">
                                                                        <div class="col-12">
                                                                        <label name="date" class="form-control bg-inverse-warning" style="text-align:center;"><?php echo decimalToHours($total); ?> ชม.</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <p id="demo1" style="color: red; text-align:center;"></p>
                                                                <?php
                                                                if ($ot == "normal"){
                                                                    if ($y >= $t1) {
                                                                        if ($x>0 && $x< $t2){
                                                                            //$gap = $gap-1;
                                                                            echo '<script>',
                                                                            'document.getElementById("demo1").innerHTML = "กรณีทำ OT ในช่วงพักเที่ยง</br> พนักงานต้องไปดำเนินการขออนุมัติใหม่อีกครั้งหนึ่ง โดยเลือก OT ช่วงพัก/พักเที่ยง (เวลา 12:00-13:00 ระบบจะไม่นับเวลาให้)";',
                                                                            '</script>';
                                                                        }
                                                                    }
                                                                }
                                                                ?>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="exampleSelectGender">เหตุผล</label>
                                                                <label name="msg" class="form-control bg-inverse-secondary"><?php echo $request_msg; ?></label>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="exampleSelectGender">รายละเอียดเพิ่ม</label>
                                                                <label name="msg" class="form-control bg-inverse-secondary"><?php echo $request_detail; ?></label>
                                                            </div>
                                                            <p style="color: blue;">หมายเหตุ :</p>
                                                            <p style="color: blue; font-size: 85%">1. กรณีพนักงานทำ OT ควบ 2 กะ ห้ามขอ OT ควบกะ ให้ขอแยกเป็นกะ 2 ครั้ง</p>
                                                            <p style="color: blue; font-size: 85%">2. กรณีทำ OT ช่วงพักเที่ยง ให้ขอแยกอีกครั้งหนึ่ง โดยเลือกประเภทเป็น
                                                            <div class="form-group">
                                                                <form method="POST">
                                                                    <button type="submit" name="save" class="btn btn-primary col-12">ยืนยัน</button>
                                                                </form>
                                                                <br>
                                                                <button class="btn btn-warning col-12" onclick="goBack()">แก้ไข</button>
                                                            </div>
                                                            
                                                            
                                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <!--    <footer class="footer">-->
                <!--    <div class="d-sm-flex justify-content-center justify-content-sm-between">-->
                <!--        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2021. Premium <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from BootstrapDash. All rights reserved.</span>-->
                <!--        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="ti-heart text-danger ml-1"></i></span>-->
                <!--    </div>-->
                <!--</footer>-->
                </div>
            </div>
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="../../vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="../../vendors/typeahead.js/typeahead.bundle.min.js"></script>
    <script src="../../vendors/select2/select2.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="../../js/off-canvas.js"></script>
    <script src="../../js/hoverable-collapse.js"></script>
    <script src="../../js/template.js"></script>
    <script src="../../js/settings.js"></script>
    <script src="../../js/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="../../js/file-upload.js"></script>
    <script src="../../js/typeahead.js"></script>
    <script src="../../js/select2.js"></script>
    <!-- End custom js for this page-->
</body>

</html>

<?php
mysqli_close($con);
?>