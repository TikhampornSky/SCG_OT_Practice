<?php
include("../dbconnect.php");
require_once("../test.php");
require_once("../notify.php");
session_start();
$approver_id = $_SESSION["user_id"];

$transaction_id = $_GET['transaction_id'];
$user_id = $_GET['user_id'];

$sql = mysqli_query($con,"SELECT * FROM transaction a,employee b, employeeInfo d WHERE a.transaction_id = '$transaction_id' AND a.user_id = b.employee_id AND d.employee_id = b.employee_id");
$rs = $sql->fetch_object();

include "../thisWeek.php";
$time_stamp = ($rs->date);
if($rs->ot_type == 'lunch'){
    $ot_desc="(OT พักเที่ยง)";
    $ot_type="lunch";
}else if($rs->ot_type == 'normal'){
    $ot_desc="(OT ปกติ)";
    $ot_type="normal";
}else if($rs->ot_type == 'round'){
    $ot_desc="(OT กะ)";
    $ot_type="round";
}


if (date('l', strtotime($rs->date))=="Monday"){
    $ended = substr($rs->time_end,11);
    $ended = decimalHours($ended);
    
    if ($ended >=0 AND $ended <1){
        $endd = 24;
    }else {
        $endd = $ended;
    }
    
    // echo $ended;
    if ($endd < decimalHours("08:00")){
        $time_stamp = date('Y-m-d',(strtotime ( '-7 day' , strtotime ($rs->date) ) ));
    }
}

$time = new week();
$time->set_dayy($time_stamp);
$start_monday1 =  $time->get_start();
$end_monday1 = $time->get_end();

$sql1 = mysqli_query($con,"SELECT SUM(hour_range) AS total FROM transaction WHERE user_id= '$user_id' AND time_start BETWEEN '$start_monday1 08:00:00' AND '$end_monday1 07:59:00' AND approve_status = 'waiting'");
$sql2 = mysqli_query($con,"SELECT SUM(hour_range) AS total FROM transaction WHERE user_id= '$user_id' AND time_start BETWEEN '$start_monday1 08:00:00' AND '$end_monday1 07:59:00' AND approve_status = 'approve'");
$sql3 = mysqli_query($con,"SELECT SUM(hour_range) AS total FROM transaction WHERE user_id= '$user_id' AND time_start BETWEEN '$start_monday1 08:00:00' AND '$end_monday1 07:59:00' AND approve_status = 'edit'");
$rs1 = $sql1->fetch_object();
$rs2 = $sql2->fetch_object();
$rs3 = $sql3->fetch_object();
$total =  $rs1->total+ $rs2->total+ $rs3->total;

$msg = $_POST['msg'];

$range = decimalHours($rs->hour_range);
$hour = substr("$range",0,2);
$minute = substr("$range",3,2);


$name = mysqli_query($con, "SELECT * FROM approverInfo WHERE approver_id = '$approver_id'");
$rsname = $name->fetch_object();
$namee = $rsname->approver_name;

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>รายละเอียดข้อมูล</title>
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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

  <script>
    $(document).ready(function() {
        $('#tbName').on('input change', function() {
            if($(this).val() != '') {
                $('#submit').prop('disabled', false);
            } else {
                $('#submit').prop('disabled', true);
            }
        });
    });
  </script>
  <script type="text/javascript">
    function ShowHideDiv(btnPassport) {
        var dvPassport = document.getElementById("dvPassport");
        var dvPassport2 = document.getElementById("dvPassport2");
        dvPassport.style.display = btnPassport.value == "ผู้บังคับบัญชาแก้ไขเวลา" ? "block" : "none";
        dvPassport2.style.display = btnPassport.value == "ผู้บังคับบัญชาแก้ไขเวลา" ? "block" : "none";
    }
  </script>
</head>
<body>
  <div class="container-scroller">
      <!-- partial -->
        <div class="content-wrapper" style="min-height: calc(100vh - 60px);">
            <div class="">
                <div class="row">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <p class="card-title">ข้อมูลการขออนุมัติ<?=$ot_desc?></p>
                                <form action="approve.php" method="POST">
                                    <input type="hidden" name="transaction_id" value="<?=$transaction_id;?>">
                                    <input type="hidden" name="employee_id" value="<?=$rs->employee_id;?>">
                                    <input type="hidden" name="ot_type" value="<?=$rs->ot_type;?>">
                                    <div class="row">
                                        <div class="col-12">
                                            <label class="" for="exampleInputEmail3">ชื่อพนักงาน</label>
                                            <div class="row">
                                                <div class="col-12">
                                                    <label name="date" class="form-control"><?=$rs->employee_name;?> <?=$rs->employee_lastname;?></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <label class="" for="exampleInputEmail3">หน่วยงาน</label>
                                            <div class="row">
                                                <div class="col-12">
                                                    <label name="date" class="form-control"><?=$rs->employee_department;?></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <label for="exampleInputPassword4">วันที่จะทำ OT</label>
                                            <div class="row">
                                                <div class="col-12">
                                                    <input type="hidden" name="date" value="<?=($rs->date);?>">
                                                    <label name="date" class="form-control"><?=DateThai($rs->date);?></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="exampleInputPassword4">เวลาเริ่ม</label>
                                            <div class="row">
                                                <div class="col-12">
                                                    <input name="date1" class="form-control" value="<?=substr($rs->time_start,11,5);?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <label for="exampleInputPassword4">เวลาสิ้นสุด</label>
                                            <div class="row">
                                                <div class="col-12">
                                                    <input name="date2" class="form-control" value="<?=substr($rs->time_end,11,5);?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="exampleInputPassword4">จำนวนชั่วโมง</label>
                                            <div class="row">
                                                <div class="col-12">
                                                    <label name="date" class="form-control"><?=decimalToHours($rs->hour_range);?></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <label for="exampleInputPassword4">ชั่วโมงทั้งสัปดาห์</label>
                                            <div class="row">
                                                <div class="col-12">
                                                    <label name="date" class="form-control card card-inverse-warning"><?=decimalToHours($total);?></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <label for="exampleSelectGender">เหตุผลการขอ<?=$ot_desc?></label>
                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <label name="date" class="form-control"><?=$rs->request_msg;?></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <label for="exampleSelectGender">เหตุผลรายละเอียด</label>
                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <label name="date" class="form-control"><?=$rs->request_detail;?></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="button" value="ผู้บังคับบัญชาแก้ไขเวลา" onclick="ShowHideDiv(this)" class="btn btn-warning form-control"/>
                                    <hr />
                                    <div id="dvPassport" style="display: none">
                                        <div class="row" >
                                            <div class="col-6">
                                                <label>เวลาเริ่ม</label><br>
                                                <input type="time" name="time_start" id="Text1" class="form-control" style="-webkit-appearance: none; -moz-appearance: none;"/>
                                            </div>
                                            <div class="col-6">
                                                <label>เวลาสิ้นสุด</label><br>
                                                <input type="time" name="time_end" id="Text2" class="form-control" style="-webkit-appearance: none; -moz-appearance: none;"/>
                                            </div>
                                        </div>
                                    </div><br>
                        
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <!--<a href="approve.php?transaction_id=<?=$rs->transaction_id;?>&amp;user_id=<?=$rs->user_id;?>&amp;range=<?=$rs->hour_range;?>" class="btn btn-success form-control">อนุมัติ</a>-->
                                                    <button class="btn btn-success form-control" name="approve" type="submit">อนุมัติ</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <button class="btn btn-danger form-control" name="reject" type="submit" id="submit" disabled="disabled" >ปฏิเสธ</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <label for="exampleSelectGender" style="color: red;">กรณีปฏิเสธกรุณาระบุเหตุผล</label>
                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <input type="text" id="tbName" name="msg" class="form-control" placeholder="โปรดใส่เหตุผลกรณีปฏิเสธ" oninput="myFunction()">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
      </div>
      <!-- main-panel ends -->
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

