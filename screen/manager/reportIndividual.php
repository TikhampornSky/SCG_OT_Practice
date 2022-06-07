<?php
include("../dbconnect.php");
require_once("../test.php");
$id = $_GET['id'];
$name = $_GET['name'];
$lastname = $_GET['lastname'];
$time = $_GET['time'];
$date1 = $_GET['date1'];
$date2 = $_GET['date2'];
$request= $_GET['request'];
$approve = $_GET['approve'];
$waiting = $_GET['waitng'];
$reject = $_GET['reject'];


session_start();
// Set session variables
$line_id = $_SESSION["line_id"]; //approver line id
$approver_id = $_SESSION["user_id"]; //approver line id

$sql = mysqli_query($con, "SELECT * FROM approverInfo a WHERE a.approver_id = '$approver_id'");
$rs =  $sql->fetch_object();

// $sql0 = mysqli_query($con, "SELECT * FROM users a,department b WHERE a.user_id='$id' AND a.user_department = b.department_id");
// $rs0 = $sql0->fetch_object();
// $department = $rs0->department_name;

// echo $id;
$sql1 = mysqli_query($con, "SELECT * FROM employeeInfo a WHERE a.employee_id = '$id'");
$rs1 =  $sql1->fetch_object();


$date11 = DateThai($date1);
//$arr = explode(" ", $date11, 2);
//$date11 = $arr[0];
$date22 = DateThai($date2);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>ข้อมูล</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../../vendors/feather/feather.css">
    <link rel="stylesheet" href="../../vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../../vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="../../vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="../../vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="../../js/select.dataTables.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="../../css/vertical-layout-light/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="../../images/favicon.png" />

</head>

<body>
    <div class="container-scroller">
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center" style="width: 80px;">
                <a class="navbar-brand brand-logo mr-5" href="../../index.html"><img src="../../images/logo.svg" class="mr-2" alt="logo" /></a>
                <a class="navbar-brand brand-logo-mini" href="../../index.html"><img src="../../images/img.png" alt="logo" class="mr-2" style="width: 75px; height: 50px;"/></a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end" style="width: calc(100% - 80px);">
                <span class="text-dark">คุณ <?= $rs->approver_name ?> <?= $rs->approver_lastname ?></span>
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
                                <div class="card data-icon-card-dark">
                                    <div class="card-body  text-white " style="text-align: center; font-size: 150%;">สรุปข้อมูล OT ของพนักงาน</div><br>
                                    <div class="col-md-12" style="text-align: center;">
                                            <h5 class="text-light" style="text-align: center;">ชื่อพนักงาน / หน่วยงาน</h5>
                                            <label><?php echo $rs1->employee_name;?> <?php echo $rs1->employee_lastname;?></label><br>
                                            <label><?php echo $rs1->employee_department;?></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12  stretch-card">
                                <div class="card data-icon-card-dark">
                                    <center>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card-body ">
                                                    <div class="col-md-12 card card-body data-icon-card-light" style="text-align: center;">
                                                        <p class="card-title text-dark">ข้อมูลประจำวันที่</p>
                                                        <p class="card-title text-dark"><?php echo $date11." ถึง ".$date22;?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group row">
                                                    <div class="col-md-12">
                                                        <div class="card data-icon-card-dark">
                                                            <center>
                                                            <div class="row">
                                                                <p class="card card-body data-icon-card-warning text-dark" style="float: left;width: 120px;">OT ที่ขอไปแล้ว</p>
                                                                <p class="card card-body data-icon-card-light" style="color: green; float: right; "><?= decimalToHours($request); ?> ชั่วโมง</p>
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
                                                                <p class="card card-body  data-icon-card-warning text-dark" style="float: left; width: 120px;">OT คงเหลือสัปดาห์นี้</p>
                                                                <p class="card card-body data-icon-card-danger" style="color: white; float: right;"><?= decimalToHours(36 - $request) ?> ชั่วโมง</p>
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

                                                    </div>
                                                </div>
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
                

            <!-- page-body-wrapper ends -->
        </div>
        <!-- container-scroller -->

        <!-- plugins:js -->
        <script src="vendors/js/vendor.bundle.base.js"></script>
        <!-- endinject -->
        <!-- Plugin js for this page -->
        <script src="vendors/chart.js/Chart.min.js"></script>
        <script src="vendors/datatables.net/jquery.dataTables.js"></script>
        <script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
        <script src="js/dataTables.select.min.js"></script>

        <!-- End plugin js for this page -->
        <!-- inject:js -->
        <script src="js/off-canvas.js"></script>
        <script src="js/hoverable-collapse.js"></script>
        <script src="js/template.js"></script>
        <script src="js/settings.js"></script>
        <script src="js/todolist.js"></script>
        <!-- endinject -->
        <!-- Custom js for this page-->
        <script src="js/dashboard.js"></script>
        <script src="js/Chart.roundedBarCharts.js"></script>
        <!-- End custom js for this page-->
</body>

</html>
<?php
mysqli_close($con);
?>