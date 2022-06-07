<?php
include("dbconnect.php");
require_once("test.php");
session_start();
$line_id = $_SESSION["line_id"]; //line id 
// echo$line_id;
$type = $_SESSION["user_type"];

$in = "";
$out = "";
if ($type == "normal"){
    $type = "(พนักงานปกติ)";
    $in = '07:30';
    $out = '07:29';
}else {
    $type = "(พนักงานกะ)";
    $in = '08:00';
    $out = '07:59';
}

include "thisWeek.php";
date_default_timezone_set('Asia/Bangkok');
$time_stamp = date("Y-m-d H:i:s");
$time = new week();
$time->set_day($time_stamp);
$start_mon =  $time->get_start();
$end_mon = $time->get_end();
$time = $_GET['time'];

// $sql = "";
// $approve = "";
// $waiting = "";
// $reject = "";
$date1 = "";
$date2 = "";

// $date = "04-15-2013";
// $date1 = str_replace('-', '/', $date);
// $tomorrow = date('m-d-Y',strtotime($date1 . "+1 days"));

if ($time == 'week') {
    $date1 = $start_mon;
    $date2 = $end_mon;
    $in = '07:30';
    $out = '07:59';


} else if ($time == 'month') {
    $date1 = date("Y-m-01", strtotime($time_stamp));
    $date2 = date("Y-m-t", strtotime($time_stamp));
} else if ($time == 'range') {
    // echo ("range");
    $date1 = $_GET['day_start'];
    $date2 = $_GET['day_end'];
}
$date11 = DateThai($date1);
//$arr = explode(" ", $date11, 2);
//$date11 = $arr[0];
$date22 = DateThai($date2);

$sql = "SELECT * FROM employee a,employeeInfo b WHERE a.line_id= '$line_id' AND a.employee_id = b.employee_id ";
$result = $con->query($sql);
$name = "";
while ($rs = $result->fetch_object()) {
    $name = $rs->employee_name;
    $lastname = $rs->employee_lastname;
    $sql0 = mysqli_query($con, "SELECT SUM(hour_range) AS request FROM transaction a WHERE a.user_id = '$rs->employee_id' AND date BETWEEN '$date1' AND '$date2'");
    $sql1 = mysqli_query($con, "SELECT SUM(hour_range) AS approve FROM transaction a WHERE a.user_id = '$rs->employee_id' AND time_start BETWEEN '$date1 $in' AND '$date2 $out' AND a.approve_status = 'approve'");
    $sql2 = mysqli_query($con, "SELECT SUM(hour_range) AS waiting FROM transaction a WHERE a.user_id = '$rs->employee_id' AND time_start BETWEEN '$date1 $in' AND '$date2 $out' AND a.approve_status = 'waiting'");
    $sql3 = mysqli_query($con, "SELECT SUM(hour_range) AS reject FROM transaction a WHERE a.user_id = '$rs->employee_id' AND time_start BETWEEN '$date1 $in' AND '$date2 $out' AND a.approve_status = 'reject'");
    $sql4 = mysqli_query($con, "SELECT SUM(hour_range) AS edit FROM transaction a WHERE a.user_id = '$rs->employee_id' AND time_start BETWEEN '$date1 $in' AND '$date2 $out' AND a.approve_status = 'edit'");


    $rs0 = $sql0->fetch_object();
    $rs1 = $sql1->fetch_object();
    $rs2 = $sql2->fetch_object();
    $rs3 = $sql3->fetch_object();
    $rs4 = $sql4->fetch_object();

    $requst = $rs0->request;
    $approve = $rs1->approve;
    $waiting = $rs2->waiting;
    $reject = $rs3->reject;
    $edit = $rs4->edit;
    
    if ($sql0 & $sql1){
        if ($requst == "") {$requst = 0;}
        if ($approve == "") {$approve = 0;}
        if ($waiting == "") {$waiting = 0;}
        if ($reject == "") {$reject = 0;}
        if ($edit == "") {$edit = 0;}
    }
    
    $requst = $approve + $waiting + $edit;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>รายการขอ</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../../vendors/feather/feather.css">
    <link rel="stylesheet" href="../../vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../../vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="../../vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="../../vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" type="../../text/css" href="../../js/select.dataTables.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="../../css/vertical-layout-light/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="../../images/favicon.png" />
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        td {
            text-align: center;
            padding: 12px;
            /*width: 100%;*/
        }

        tr:nth-child(even) {
            background-color: #f2f2f2
        }

        th {
            font-size: 0.875rem;
            /*font-weight: 400;*/
            /*line-height: 1;*/
            text-align: center;
            /*width:100%;*/
            /*background-color: #04AA6D;*/
            /*color: white;*/
        }
    </style>
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
                      <div class="card data-icon-card-dark" style="background: #B03A2E;">
                        <div class="card-body">
                          <div class="row">
                            <div class="col-md-12">
                              <div class="card-body ">
                                <div class="text-white " style="text-align: center; font-size: 150%;">สรุปข้อมูลการขอ OT</div><br>
                                <div class="col-md-12 card card-body data-icon-card-light" style="text-align: center;">
                                  <p class="card-title text-dark">ข้อมูลประจำสัปดาห์</p>
                                  <p class="card-title text-dark"><?php echo $date11." - ".$date22;?></p>
                                </div>
                              </div>
                            </div>
                          </div>
                          <center>
                          <div class="row">
                              <div style = "margin: 0 auto;">
                                  <div class="card data-icon-card-dark" style="background: #B03A2E;">
                                    <p class="card card-body data-icon-card-warning text-dark" style="float: left;">OT ที่ขอไปแล้ว</p>
                                    <p class="card card-body data-icon-card-warning text-dark" style="float: left;">OT ที่อนุมัติแล้ว</p>
                                    <p class="card card-body data-icon-card-warning text-dark" style="float: left;">OT ที่รออนุมัติ</p>
                                    <?php if($time=='week'){?>
                                        <p class="card card-body  data-icon-card-warning text-dark" style="float: left;">OT คงเหลือสัปดาห์นี้</p>
                                    <?php } ?>
                                  </div>
                              </div>
                              
                              <div style = "margin: 0 auto;">
                                  <div class="card data-icon-card-dark" style="background: #B03A2E;">
                                    <p class="card card-body data-icon-card-light" style="color: green; float: right; "><?= decimalToHours($requst); ?> ชั่วโมง</p>
                                    <p class="card card-body data-icon-card-light" style="color: green; float: right;"><?= decimalToHours($approve+$edit) ?> ชั่วโมง</p>
                                    <p class="card card-body data-icon-card-light" style="color: green; float: right;"><?= decimalToHours($waiting) ?> ชั่วโมง</p>
                                    <?php if($time=='week'){?>
                                        <p class="card card-body data-icon-card-danger" style="color: white; float: right;"><?= decimalToHours(36-$requst) ?> ชั่วโมง</p>
                                        <!--<p id="demo" style="color: white; float: right;"></p>-->
                                        <p id="demo" style="color: red;"></p>
                                        <p id="demo1" style="color: white;"></p>
                                    <?php } ?>
                                    <?php 
                                    if ((36-$requst) <= 0){
                                     echo '<script>',
                                             'document.getElementById("demo").innerHTML = "เตือน!";',
                                             'document.getElementById("demo1").innerHTML = "OT ครบ 36 ชม.แล้ว";',
                                          '</script>';
                                    }
                                    ?>
                                    <script>
                                        // document.getElementById("demo").innerHTML = "hyy";
                                    </script>
                                  </div>
                              </div>
                          </div>
                          </center>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
                 <!--content-wrapper ends -->
                 <!--partial:partials/_footer.html -->
                <!--<footer class="footer">-->
                <!--    <div class="d-sm-flex justify-content-center justify-content-sm-between">-->
                <!--        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2021. Premium <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from BootstrapDash. All rights reserved.</span>-->
                <!--        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="ti-heart text-danger ml-1"></i></span>-->
                <!--    </div>-->
                <!--    <div class="d-sm-flex justify-content-center justify-content-sm-between">-->
                <!--        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Distributed by <a href="https://www.themewagon.com/" target="_blank">Themewagon</a></span>-->
                <!--    </div>-->
                <!--</footer>-->
                 <!--partial -->
            </div>
        </div>
         <!--page-body-wrapper ends -->
    </div>
     <!--container-scroller -->

     <!--plugins:js -->
    <script src="vendors/js/vendor.bundle.base.js"></script>
     <!--endinject -->
     <!--Plugin js for this page -->
    <script src="vendors/chart.js/Chart.min.js"></script>
    <script src="vendors/datatables.net/jquery.dataTables.js"></script>
    <script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
    <script src="js/dataTables.select.min.js"></script>

     <!--End plugin js for this page -->
     <!--inject:js -->
    <script src="js/off-canvas.js"></script>
    <script src="js/hoverable-collapse.js"></script>
    <script src="js/template.js"></script>
    <script src="js/settings.js"></script>
    <script src="js/todolist.js"></script>
     <!--endinject -->
     <!--Custom js for this page-->
    <script src="js/dashboard.js"></script>
    <script src="js/Chart.roundedBarCharts.js"></script>
     <!--End custom js for this page-->
</body>

</html>
<?php
mysqli_close($con);
?>