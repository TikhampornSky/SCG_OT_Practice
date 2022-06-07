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

$sql = mysqli_query($con, "SELECT * FROM employee a, employeeInfo b WHERE a.line_id = '$line_id' AND a.employee_id = b.employee_id");
$rs = $sql->fetch_object();
$name = $rs->employee_name;
$lastname = $rs->employee_lastname;


// Set session variables
$_SESSION["user_id"] = $rs->employee_id;
$_SESSION['line_id'] = $line_id;


echo $user_id;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
    <meta
      name="viewport"
      content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=0,viewport-fit=cover"
    />
    <title>สรุปข้อมูลทั้งหมด</title>
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
                      <center>
                    <div class="card-body  text-dark " style="text-align: center; font-size: 150%;">สรุปข้อมูล OT ทั้งหมด</div>
                    <a href="../individualReport.php?time=week"><button type="submit" name="save" class="btn btn-success mr-2 col-12">ดูสรุปของสัปดาห์นี้</button></a><br><br>
                    <label class="btn btn-success mr-2 col-12">เลือกช่วงเวลาที่จะดูสรุป</label>
                    <form action="../individualReport.php?time=range" class="forms-sample" method="GET">
                        <input type="hidden" name="time" value="range">
                    <div class="row">
                      <div class="col-6">
                        <label for="exampleInputPassword4">วันเริ่ม</label>
                        <div class="form-group row">
                          <div class="col-12">
                            <input type="date" name="day_start" class="form-control" style="-webkit-appearance: none; -moz-appearance: none;" />
                          </div>
                        </div>
                      </div>
                      <div class="col-6">
                        <label for="exampleInputPassword4">วันสิ้นสุด</label>
                        <div class="form-group row">
                          <div class="col-12">
                            <input type="date" name="day_end" class="form-control" style="-webkit-appearance: none; -moz-appearance: none;"/>
                          </div>
                        </div>
                      </div>
                    </div>
                    </center>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
                <center>
                <button type="submit" name="save" class="btn btn-primary mr-2 col-12" >ค้นหา</button>
                </center>
              </form>
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
