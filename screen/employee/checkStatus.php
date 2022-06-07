<?php
include("../dbconnect.php");
include("../dbconnect.php");
include("checkLine.php");
$line_id = $_GET['w1'];
checkLine($line_id);
require_once("../test.php");
session_start();


$line_id = $_GET['w1'];
$line_name = $_GET['name'];
$_SESSION['line_id'] = $line_id;
// echo $line_id;

$sql = mysqli_query($con, "SELECT * FROM employee a,employeeInfo b WHERE a.line_id = '$line_id' AND a.employee_id = b.employee_id  ");
$rs = $sql->fetch_object();
$name = $rs->employee_name;
$lastname = $rs->employee_lastname;
$id = $rs->employee_id;

$waiting = 0;
$approve = 0;
$reject = 0;
$edit = 0;
$sql1 = mysqli_query($con, "SELECT COUNT(approve_status) AS waiting, SUM(hour_range) AS hour FROM `transaction` WHERE `user_id`='$rs->employee_id' AND approve_status = 'waiting'");
$sql2 = mysqli_query($con, "SELECT COUNT(approve_status) AS approve, SUM(hour_range) AS hour FROM `transaction` WHERE `user_id`='$rs->employee_id' AND approve_status = 'approve'");
$sql3 = mysqli_query($con, "SELECT COUNT(approve_status) AS reject, SUM(hour_range) AS hour FROM `transaction` WHERE `user_id`='$rs->employee_id' AND approve_status = 'reject'");
$sql4 = mysqli_query($con, "SELECT COUNT(approve_status) AS edit, SUM(hour_range) AS hour FROM `transaction` WHERE `user_id`='$rs->employee_id' AND approve_status = 'edit'");
$rs1 = $sql1->fetch_object();
$rs2 = $sql2->fetch_object();
$rs3 = $sql3->fetch_object();
$rs4 = $sql4->fetch_object();
if($rs1->waiting != ""){
    $waiting = $rs1->waiting;
}
if($rs2->approve != ""){
    $approve = $rs2->approve;
}
if($rs3->reject != ""){
    $reject = $rs3->reject;
}
if($rs4->edit != ""){
    $edit = $rs4->edit;
}

$_SESSION["user_id"] = $rs->employee_id;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
    <meta
      name="viewport"
      content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=0,viewport-fit=cover"
    />
    <title>เช็คสถานะการอนุมัติ</title>
    <script src="https://static.line-scdn.net/liff/edge/2/sdk.js"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- plugins:css -->
    <link rel="stylesheet" href="../../vendors/feather/feather.css">
    <link rel="stylesheet" href="../../vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../../vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <link rel="stylesheet" href="../template/vendors/codemirror/codemirror.css">
    <link rel="stylesheet" href="../template/vendors/codemirror/ambiance.css">
    <link rel="stylesheet" href="../template/vendors/pwstabs/jquery.pwstabs.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="../../css/vertical-layout-light/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="../../images/favicon.png" />
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
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                    <label>สถานะ</label>
                    <form action="statusList.php" class="forms-sample" method="GET">
                    <div class="row">
                      <div class="col-12">
                        <div class="form-group row">
                          <div class="col-12">
                                <button type="submit" name="status" value="waiting" class="btn btn-warning mr-2 col-12" >รออนุมัติ <?= $waiting?> รายการ (<?= decimalToHours($rs1->hour)?> ชั่วโมง)</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-12">
                        <div class="form-group row">
                          <div class="col-12">
                                <button type="submit" name="status" value="approve" class="btn btn-success mr-2 col-12" >อนุมัติแล้ว  <?= $approve?> รายการ (<?= decimalToHours($rs2->hour)?> ชั่วโมง)</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-12">
                        <div class="form-group row">
                          <div class="col-12">
                                <button type="submit" name="status" value="edit" class="btn btn-primary mr-2 col-12" >แก้ไขและอนุมัติแล้ว  <?= $edit?> รายการ (<?= decimalToHours($rs4->hour)?> ชั่วโมง)</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-12">
                        <div class="form-group row">
                          <div class="col-12">
                                <button type="submit" name="status" value="reject" class="btn btn-danger mr-2 col-12" >ถูกปฏิเสธ  <?= $reject?> รายการ (<?= decimalToHours($rs3->hour)?> ชั่วโมง)</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    </form>
                </div>
              </div>
            </div>
          </div>
        <!--</div>-->
        <!-- content-wrapper ends -->
        <!-- partial:../../partials/_footer.html -->
        <!--<footer class="footer">-->
        <!--  <div class="d-sm-flex justify-content-center justify-content-sm-between">-->
        <!--    <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2021. Premium <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from BootstrapDash. All rights reserved.</span>-->
        <!--    <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="ti-heart text-danger ml-1"></i></span>-->
        <!--  </div>-->
        <!--</footer>-->
        <!-- partial -->
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
