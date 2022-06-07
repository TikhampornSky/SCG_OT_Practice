<?php
include("../dbconnect.php"); //connect to database
require_once("../test.php"); //convert date to decimal
// include("../notify.php");
session_start(); //calling session
$user_id = $_SESSION["user_id"]; //employee id
$line_id = $_SESSION['line_id'];
$sql = mysqli_query($con, "SELECT * FROM employee a , employeeInfo b WHERE a.employee_id = '$user_id' AND a.employee_id = b.employee_id "); 
$rs1 = $sql->fetch_object();
$name = $rs1->employee_name;
$lastname = $rs1->employee_lastname;

$transaction = $_GET['transaction'];
$title = "";
// echo $transaction;
$sql = mysqli_query($con, "SELECT * FROM transaction WHERE transaction_id = '$transaction'");
$rs = $sql->fetch_object();
if ($rs->approve_status == 'waiting'){
    $title = "รออนุมัติ";
}else if($rs->approve_status == 'approve' || $rs->approve_status == 'edit'){
    $title = "อนุมัติแล้ว";
}else if($rs->approve_status == 'reject'){
    $title = "ถูกปฏิเสธ";
}
// $total = ($rsweek->total)+$gap;
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
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
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
      
      <div class="col-md-12">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12">
              
                <div class="row">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body ">
                                                    <div class="card-body  text-dark " style="text-align: center; font-size: 150%;">รายละเอียด OT</div>

                                
                                <div class="form-group ">
                                    <label for="exampleInputEmail3">วันที่ขอ OT</label>
                                    <label name="date" class="form-control "><?php echo DateThai($rs->date); ?></label>
                                </div>
                                <center>
                                <div class="row">
                                    <div class="col-6">
                                        <label for="exampleInputPassword4">เวลาเริ่ม</label>
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <label name="date" class="form-control "><?php echo substr($rs->time_start,11,5); ?></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label for="exampleInputPassword4">เวลาสิ้นสุด</label>
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <label name="date" class="form-control "><?php echo substr($rs->time_end,11,5); ?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                if ($rs->approve_status == 'edit'){?>
                                    <div class="row">
                                    <div class="col-6">
                                        <label for="exampleInputPassword4">เวลาเริ่มเดิม</label>
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <label name="date" class="form-control "><?php echo ($rs->edit_start); ?></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label for="exampleInputPassword4">เวลาสิ้นสุดเดิม</label>
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <label name="date" class="form-control "><?php echo ($rs->edit_end); ?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="row">
                                    <div class="col-6">
                                        <label for="exampleInputPassword4">จำนวนชั่วโมง</label>
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <label name="date" class="form-control" style="text-align:center;"><?php echo decimalToHours($rs->hour_range); ?> </label>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                        if ($title == "รออนุมัติ"){
                                            $class = "form-control bg-inverse-secondary";
                                            $status = "form-control bg-inverse-warning";
                                        }else if($title == "อนุมัติแล้ว") {
                                            $class = "form-control";
                                            $status = "form-control bg-inverse-success";
                                        }else{
                                            $class = "form-control";
                                            $status = "form-control bg-inverse-danger";
                                        }
                                        
                                    ?>
                                    <div class="col-6">
                                        <label for="exampleInputPassword4">สถานะ</label>
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <label name="date" class="<?=$status?>" style="text-align:center;"><?= $title?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if($title == "ถูกปฏิเสธ"){?>
                                    <div class="row">
                                        <div class="col-12">
                                            <label for="exampleInputPassword4">เหตุผลที่ปฏิเสธ</label>
                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <label name="date" class="<?=$class?>" style="text-align:center;"><?php echo $rs->reject_msg; ?> </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="row">
                                    <div class="col-12">
                                        <label for="exampleInputPassword4">ผู้อนุมัติ</label>
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <label name="date" class="<?=$class?>" style="text-align:center;"><?php echo $rs->approver_name; ?> </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <label for="exampleInputPassword4">วัน/เวลาพิจารณา</label>
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <label name="date" class="<?=$class?>" style="text-align:center;"><?php echo $rs->consider_time;?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="exampleSelectGender">เหตุผลการขอ OT</label>
                                    <label name="msg" class="form-control bg-inverse-secondary"><?php echo $rs->request_msg; ?></label>
                                </div>
                                <div class="form-group">
                                    <label for="exampleSelectGender">รายละเอียดการขอ OT</label>
                                    <label name="msg" class="form-control bg-inverse-secondary"><?php echo $rs->request_detail; ?></label>
                                </div>
                                <?php if($title != "ถูกปฏิเสธ"){ ?>
                                    <div class="row">
                                        <div class="col-6">
                                            <input type="submit" value="แก้ไข" onclick="myFunction2(<?=$transaction?>)" class="btn btn-warning mr-2 col-12">
                                        </div>
                                        <div class="col-6">
                                            <input type="submit" value="ยกเลิกรายการนี้" onclick="myFunction(<?=$transaction?>)" class="btn btn-danger mr-2 col-12"><br><br>
                                        </div>
                                    </div>
                                <?php }?>
                                
                                <a href="loginLine1.html" class="btn btn-primary mr-2 col-12">เช็คสถานะการอนุมัติต่อ</a>
                                </center>
                                <script type="text/javascript">
                                    function myFunction(transaction){
                                    	swal({
                                          title: "ยืนยันการลบ",
                                          text: "จะไม่สามารถเรียกรายการกลับคืนมาได้",
                                          icon: "warning",
                                          buttons: true,
                                          dangerMode: true,
                                        })
                                        .then((willDelete) => {
                                          if (willDelete) {
                                            swal("รายการที่เลือกได้ทำการลบเรียบร้อย", {
                                              icon: "success",
                                            //   window.location.href = "delete.php",
            
                                            });
                                            window.location.href = "delete.php?transaction="+transaction;
                                          } else {
                                            swal("รายการที่เลือกยังคงอยู่");
                                          }
                                        });
                                    }
                                    function myFunction2(transaction){
                                    	swal({
                                          title: "ยืนยันการแก้ไข",
                                          icon: "warning",
                                          buttons: true,
                                          dangerMode: true,
                                        })
                                        .then((willDelete) => {
                                          if (willDelete) {
                                            window.location.href = "editDetail.php?transaction="+transaction;
                                          } else {
                                            swal("รายการที่เลือกไม่มีการเปลี่ยนแปลง");
                                          }
                                        });
                                    }
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
            <!-- partial:../../partials/_footer.html -->
            <!--<footer class="footer">-->
            <!--    <div class="d-sm-flex justify-content-center justify-content-sm-between">-->
            <!--        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2021. Premium <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from BootstrapDash. All rights reserved.</span>-->
            <!--        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="ti-heart text-danger ml-1"></i></span>-->
            <!--    </div>-->
            <!--</footer>-->
            <!-- partial -->
        </div>
        <!-- main-panel ends -->
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