<?php
include("../dbconnect.php");
session_start();
$line_id = $_SESSION["line_id"]; //line id 
$type = $_SESSION["user_type"];

$in = "";
$out = "";
$link = "";
if ($type == "normal") {
    $type = "(พนักงานปกติ)";
    $link = "otType.php";
    $in = ' 07:30';
    $out = ' 07:29';
} else {
    $type = "(พนักงานกะ)";
    $link = "booking.php";
    $in = ' 08:00';
    $out = ' 07:59';
}
require_once("../test.php");


include "../thisWeek.php";
date_default_timezone_set('Asia/Bangkok');
$time_stamp = date("Y-m-d H:i:s");
$time = new week();
$time->set_day($time_stamp);
$start_mon =  $time->get_start();
$end_mon = $time->get_end();
$time = "week";
$date1 = "";
$date2 = "";

if ($time == 'week') {
    $date1 = $start_mon;
    $date2 = $end_mon;
    $date11 = DateThai($start_mon);
    //$arr = explode(" ", $date11, 2);
    //$date11 = $arr[0].$arr[1];
    $date22 = DateThai($end_mon);
} else if ($time == 'month') {
    $date1 = date("Y-m-01", strtotime($time_stamp));
    $date2 = date("Y-m-t", strtotime($time_stamp));
} else if ($time == 'range') {
    // echo ("range");
    $date1 = $start;
    $date2 = $end;
}


$sql = "SELECT * FROM employee a , employeeInfo b WHERE a.line_id= '$line_id' AND a.employee_id = b.employee_id ";
$result = $con->query($sql);
while ($rs = $result->fetch_object()) {
    $sql0 = mysqli_query(
        $con,
        "SELECT SUM(hour_range) AS request FROM transaction WHERE user_id = '$rs->employee_id' AND time_start BETWEEN '$date1 $in' AND '$date2 $out'" //ค้นหา OT ที่ขอไปแล้ว
    );
    $sql1 = mysqli_query(
        $con,
        "SELECT SUM(hour_range) AS approve FROM transaction WHERE user_id = '$rs->employee_id' AND time_start BETWEEN '$date1 $in' AND '$date2 $out' AND approve_status = 'approve'" //ค้นหา OT ที่อนุมัติแล้ว
    );
    $sql2 = mysqli_query(
        $con,
        "SELECT SUM(hour_range) AS waiting FROM transaction WHERE user_id = '$rs->employee_id'  AND time_start BETWEEN '$date1 $in' AND '$date2 $out' AND approve_status = 'waiting'" //ค้นหา OT ที่รออนุมัติ
    );
    $sql3 = mysqli_query(
        $con,
        "SELECT SUM(hour_range) AS reject FROM transaction WHERE user_id = '$rs->employee_id' AND time_start BETWEEN '$date1 $in' AND '$date2 $out' AND approve_status = 'reject'"
    );
    $sql4 = mysqli_query(
        $con,
        "SELECT SUM(hour_range) AS edit FROM transaction WHERE user_id = '$rs->employee_id' AND time_start BETWEEN '$date1 $in' AND '$date2 $out' AND approve_status = 'edit'"
    );
	
    
    $rs0 = $sql0->fetch_object();
    $rs1 = $sql1->fetch_object();
    $rs2 = $sql2->fetch_object();
    $rs3 = $sql3->fetch_object();
    $rs4 = $sql4->fetch_object();
    $name = $rs->employee_name;
    $lastname = $rs->employee_lastname;
    $edit = $rs4->edit;
    if ($edit == "") {
        $edit = 0;
    }
    $approve = $rs1->approve + $edit;
    $waiting = $rs2->waiting;
    $reject = $rs3->reject;
    
    $requst = $approve + $waiting;


    if ($requst == "") {
        $requst = 0;
    }
    if ($approve == "") {
        $approve = 0;
    }
    if ($waiting == "") {
        $waiting = 0;
    }
    if ($reject == "") {
        $reject = 0;
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>ขออนุมัติ OT</title>
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
            
            <div class="content-wrapper" style="min-height: calc(100vh - 60px);">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12  stretch-card">
                                <div class="card" style="background: #B03A2E;">
                                    <div class="card-body">
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card-body ">
                                                    <div class="col-md-12 card card-body data-icon-card-light" style="text-align: center; ">
                                                        <p class="card-title text-dark">ข้อมูลประจำสัปดาห์</p>
                                                        <p class="card-title text-dark"><?php echo $date11 . " - " . $date22; ?></p>
                                                        <span class="text-dark"><?= $type ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group row">
                                                    <div class="col-md-12">
                                                        <div class="card data-icon-card-dark" style="background: #B03A2E;">
                                                            <center>
                                                            <div class="row">
                                                                <p class="card card-body data-icon-card-warning text-dark" style="float: left;width: 120px;">OT ที่ขอไปแล้ว</p>
                                                                <p class="card card-body data-icon-card-light" style="color: green; float: right; "><?= decimalToHours($requst); ?> ชั่วโมง</p>
                                                            </div>
                                                            <div class="row">
                                                                <p class="card card-body data-icon-card-warning text-dark" style="float: left;width: 120px;">OT ที่อนุมัติแล้ว</p>
                                                                <p class="card card-body data-icon-card-light" style="color: green; float: right;"><?= decimalToHours($approve) ?> ชั่วโมง</p>
                                                            </div>
                                                            <div class="row">
                                                                <p class="card card-body data-icon-card-warning text-dark" style="float: left;width: 120px;">OT ที่รออนุมัติ</p>
                                                                <p class="card card-body data-icon-card-light" style="color: green; float: right;"><?= decimalToHours($waiting) ?> ชั่วโมง</p>
                                                            </div>
                                                            <div class="row">
                                                            <?php if ($time == 'week') { ?>
                                                                <p class="card card-body  data-icon-card-warning text-dark" style="float: left; width: 120px;">OT คงเหลือ</p>
                                                                <p class="card card-body data-icon-card-danger" style="color: white; float: right;"><?= decimalToHours(36 - $requst) ?> ชั่วโมง</p>
                                                            <?php } ?>
                                                            </div>
                                                            <p id="demo" style="color: yellow;"></p>
                                                            <p id="demo1" style="color: white;"></p>
                                                            <?php
                                                            if ((36 - $requst) <= 0) {
                                                                echo '<script>',
                                                                'document.getElementById("demo").innerHTML = "เตือน!";',
                                                                'document.getElementById("demo1").innerHTML = "OT ครบ 36 ชม.แล้ว";',
                                                                '</script>';
                                                            } ?>
                                                            </center>
                                                        </div><br>
                                                        <a href='<?= $link; ?>'><button class="col-md-12 btn btn-info card-title text-dark">ยืนยันการขอ</button></a>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>    
                </div>
            </div>
        </div>
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
?>;'
'