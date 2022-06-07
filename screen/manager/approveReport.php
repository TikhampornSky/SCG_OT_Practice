<?php
include("../dbconnect.php");
include("checkLine.php");
$line_id = $_GET['w1'];
checkLine($line_id);
session_start();
$line_id = $_SESSION["line_id"];
$approver_id = $_SESSION["user_id"];

$sql = mysqli_query($con, "SELECT * FROM approverInfo WHERE approver_id = '$approver_id'");
$rs = $sql->fetch_object();
$department = $rs->department_id;
$name = $rs->approver_name;
$lastname = $rs->approver_lastname;


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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=0,viewport-fit=cover" />
    <title>รายการที่พิจารณาแล้ว</title>
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
    tr:nth-child(even){background-color: #f2f2f2}
    
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
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center" style="width: 80px;">
                <a class="navbar-brand brand-logo mr-5"><img src="../../images/1.jpeg" class="mr-2" alt="logo" /></a>
                <a class="navbar-brand brand-logo-mini"><img src="../../images/img.png" class="mr-2" alt="logo" style="width: 75px; height: 50px;"/></a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end" style="width: calc(100% - 80px);">
                <span class="text-dark">คุณ <?php echo $name; ?> <?php echo $lastname; ?></span>
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
                                    <div class="card-body  text-dark " style="text-align: center; font-size: 150%;"> รายการที่พิจารณาแล้ว</div>
                                    <form  class="forms-sample" method="POST">
                                        <button type="submit" name="find" value="week" class="btn btn-success mr-2 col-12">ดูสรุปของสัปดาห์นี้</button><br><br>
                                        <label class="btn btn-success mr-2 col-12">เลือกช่วงเวลาที่จะดูสรุป</label>
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
                                                        <input type="date" name="day_end" class="form-control" style="-webkit-appearance: none; -moz-appearance: none;" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" name="find" value="range" class="btn btn-primary mr-2 col-12">ค้นหา</button>
                                    </form>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
                <?php 
                if (isset($_POST["find"])){
                    $date =  $_POST["find"];
                    $time1= "";
                    $time2= "";
                    if ($date == 'week'){
                        $time1= $start_mon;
                        $time2= $end_mon;
                        $in = "00:00";
                        $out = "23:59";
                    }else if ($date == 'range'){
                        $time1= $_POST["day_start"];
                        $time2= $_POST["day_end"];
                        $in = "00:00";
                        $out = "23:59";
                    }
                    $sql = "SELECT * FROM transaction a, employeeInfo b, approverInfo c WHERE c.approver_id = '$approver_id' AND a.approve_status != 'cancle' AND c.approver_id = b.approver_id AND b.employee_id = a.user_id AND time_start >= '$time1 $in' AND time_end <= '$time2 $out' AND a.approve_status != 'waiting' ORDER BY date";
                    $result = $con -> query($sql);
                    
                ?>
                <div class="row">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                              <p class="card-title mb-0">รายการที่พิจารณาแล้ว</p><br>
                              <div class="table-responsive">
                                  <form method="POST">
                                <table class="">
                                  <thead>
                                    <tr>
                                      <th>พิจารณา</th>
                                      <th>พนักงานในสังกัด</th>
                                      <th>วันที่ทำ</th>
                                      <th>เวลาเริ่ม</th>
                                      <th>เวลาสิ้นสุด</th>
                                      <th>จำนวนชั่วโมง</th
                                    </tr>  
                                  </thead>
                                  <tbody>
                                  <?php
                                  $title = "";
                                  while($rs = $result->fetch_object()){
                                      $font_title="black";
                                      $var = $rs->date;
                                      $datee = str_replace('-', '/', $var);
                                      $dat=date_create($datee);
                                      
                                    if ($rs->approve_status == "edit"){
                                        $title ="แก้ไขและอนุมัติ";
                                    }else if ($rs->approve_status == "approve"){
                                        $title="อนุมัติ";
                                    }else if ($rs->approve_status == "reject"){
                                        $title = "ปฏิเสธ";
                                        $font_title="red";
                                    }
                                  ?>
                                    <tr>
                                        <input type="hidden" name="userid[]" value="<?=$rs->user_id;?>" />
                                        <td nowrap><font color="<?=$font_title?>"><?=$title;?></font></td>                    
                                        <td><?=$rs->employee_name;?></td>
                                        <td><?php echo date_format($dat,"d/m");?></td>
                                        <td><?=substr($rs->time_start,11,5);?></td>
                                        <td><?=substr($rs->time_end,11,5);?></td>
                                        <td><?=decimalToHours($rs->hour_range);?></td>
                                    </tr>
                                  <?php } ?>
                                  </tbody>
                                </table><br>
                                </form>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
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