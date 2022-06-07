<?php
include("../dbconnect.php");
// include("delete.php");
require_once("../test.php");
session_start();
$user_id = $_SESSION["user_id"];
$line_id = $_SESSION["line_id"];
// echo $line_ld;
// $sql = mysqli_query($con, "SELECT * FROM employee a,approver b,employeeInfo c WHERE a.employee_id = '$user_id' AND a.department_id = b.department_id AND a.employee_id = c.employee_id");
$sql = mysqli_query($con, "SELECT * FROM employeeInfo a ,transaction b WHERE a.employee_id = '$user_id' AND b.user_id = a.employee_id");
$rs = $sql->fetch_object();
$name = $rs->employee_name;
$lastname = $rs->employee_lastname;
// $department = $rs->department_id;
// $manager = $rs->approver_name;

// echo $user_id;
$status = $_GET["status"];
$title = "";


if ($status == "waiting"){
    $title ="รออนุมัติ";
    $sql1 = mysqli_query($con, "SELECT * FROM transaction WHERE user_id = '$user_id' AND approve_status = 'waiting' ORDER BY date");
}else if ($status == "approve"){
    $title="อนุมัติแล้ว";
    $sql1 = mysqli_query($con, "SELECT * FROM transaction WHERE user_id = '$user_id' AND approve_status = 'approve' ORDER BY date");
}else if ($status == "reject"){
    $title = "ถูกปฏิเสธ";
    $sql1 = mysqli_query($con, "SELECT * FROM transaction WHERE user_id = '$user_id' AND approve_status = 'reject' ORDER BY date");
}else if ($status == "edit"){
    $title = "แก้ไขและอนุมัติ";
    $sql1 = mysqli_query($con, "SELECT * FROM transaction WHERE user_id = '$user_id' AND approve_status = 'edit' ORDER BY date");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>รายการ<?= $title;?></title>
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
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
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
                <div class="">
                    <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <p class="card-title">รายการที่<?=$title?></p><br>
                  <div class="table-responsive">
                      <form method="POST">
                    <table class="">
                      <thead>
                        <tr>
                          <th>ดูเพิ่มเติม</th>
                          <th>วันที่ขอ</th>
                          <th>เวลาเริ่ม</th>
                          <th>เวลาสิ้นสุด</th>
                          <th>จำนวนชั่วโมง</th>
                          <?php if($status != 'waiting'){?>
                              <th>ผู้อนุมัติ</th>
                          <?php }?>
                          <?php if($status != 'reject'){?>
                            <th>แก้ไข</th>
                            <th> ลบ </th>
                          <?php } ?>
                        </tr>  
                      </thead>
                      <tbody>
                      <?php
                      while($rs = $sql1->fetch_object()){
                          $var = $rs->date;
                          $datee = str_replace('-', '/', $var);
                          $dat=date_create($datee);
                      ?>
                        <tr>
                            <td><a href="statusDetail.php?transaction=<?=$rs->transaction_id?>">Click</a></td>
                            <td><?php echo date_format($dat,"d/m");?></td>
                            <td><?=substr($rs->time_start,11,5);?></td>
                            <td><?=substr($rs->time_end,11,5);?></td>
                            <td><?=decimalToHours($rs->hour_range);?></td>
                            <?php if($status != 'waiting'){?>
                                <td nowrap><?=$rs->approver_name?></td>
                            <?php }?>
                            <?php if($status != 'reject'){?>
                                <th><a href="editDetail.php?transaction=<?=$rs->transaction_id?>">  แก้ไข   </a></th>
                                <!--<th onclick="myFunction(<?=$rs->transaction_id?>)" style="color:red;">ยกเลิก</th>-->
                                <th onclick="myFunction('<?=$rs->transaction_id?>','<?=$rs->approve_status?>')" style="color:red;">ยกเลิก</th>
                            <?php } ?>
                        </tr>
                      <?php } ?>
                      </tbody>
                    </table><br>
                    <script type="text/javascript">
                        function myFunction(transaction,status){
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
                                window.location.href = "delete.php?transaction="+transaction + "&status=" + status;
                              } else {
                                swal("รายการที่เลือกยังคงอยู่");
                              }
                            });
                        }
                    </script>
                    </form>
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
    <!-- page-body-wrapper ends -->
  </div>
  

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
