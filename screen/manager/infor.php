<?php
include("../dbconnect.php");
include("checkLine.php");
include("../test.php");
include("../notify.php");

$line_id = $_GET['w1'];
checkLine($line_id);
session_start();
$line_id = $_SESSION["line_id"];
$user_id = $_SESSION["user_id"];


$sql = mysqli_query($con, "SELECT * FROM approverInfo WHERE approver_id = '$user_id'");
$rs = $sql->fetch_object();
$approver_id = $rs->approver_id;
$name = $rs->approver_name;
$lastname = $rs->approver_lastname;

// $sql = "SELECT * FROM transaction a, employee b, employeeInfo c WHERE a.user_id = b.employee_id AND approve_status='waiting' AND c.approver_id = '$user_id' AND c.employee_id = b.employee_id ORDER BY date";
$sql = "SELECT * FROM transaction a, employeeInfo c WHERE a.user_id = c.employee_id AND approve_status='waiting' AND c.approver_id = '$user_id' ORDER BY date";

$result = $con -> query($sql);

date_default_timezone_set('Asia/Bangkok');
$time_stamp = date("Y-m-d H:i:s");

if (isset($_POST["all"])){
    $checked_arr = $_POST['select'];
    $count = count($checked_arr);
    $i = 0;
    while($rs = $result->fetch_object()){
        $userid = $_POST['userid'][$i];
        $select = $_POST['select'][$i];
        $approve = mysqli_query($con,"UPDATE transaction SET approve_status='approve',consider_time = '$time_stamp' , approver_name = '$name $lastname' WHERE transaction_id='$select'");
        
        if(mysqli_affected_rows($con) == '1'){
            $transaction = mysqli_query($con,"SELECT user_id, hour_range, SUBSTRING(`hour_range`, 1, 2) AS hour,SUBSTRING(`hour_range`, 4, 2) AS minute FROM transaction WHERE transaction_id ='$select'");
            $rs1 = $transaction->fetch_object();

            $booking = mysqli_query($con,"UPDATE booking SET 
                        `booking_approve`= `booking_approve`+ (($rs1->hour_range)),
                        `booking_waiting`= `booking_waiting`- (($rs1->hour_range))
                        WHERE booking_id='b$rs1->user_id'");
                        
            approve($select);
            header("Location: ../sendMailToEmployee.php?transaction=$rs->transaction_id");

        }
        $i = $i+1;
    }
    if ($approve && $booking){
    mysqli_close($con);
    // header("Location: complete.php");
    exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>รายการขออนุมัติ</title>
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
    <div class="content-wrapper" style="min-height: calc(100vh - 10px);">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <p class="card-title mb-0">รายการรออนุมัติ</p><br>
                  <div class="table-responsive">
                      <form method="POST">
                    <table class="">
                      <thead>
                        <tr>
                          <th>เลือก</th>
                          <th>พิจารณา</th>
                          <th>พนักงานในสังกัด</th>
                          <th>วันที่ทำ</th>
                          <th>เวลาเริ่ม</th>
                          <th>เวลาสิ้นสุด</th>
                          <th>จำนวนชั่วโมง</th>
                        </tr>  
                      </thead>
                      <tbody>
                      <?php
                      while($rs = $result->fetch_object()){
                          $var = $rs->date;
                          $datee = str_replace('-', '/', $var);
                          $dat=date_create($datee);
                      ?>
                        <tr>
                            <input type="hidden" name="userid[]" value="<?=$rs->user_id;?>" />
                            <td><input type="checkbox" name="select[]" value="<?=$rs->transaction_id;?>"></td>
                            <td><a href="consider.php?transaction_id=<?=$rs->transaction_id;?>&amp;user_id=<?=$rs->user_id;?>">Click</td></a>  
                            <td nowrap><p><?=$rs->employee_name;?></p></td>
                            <td><?php echo date_format($dat,"d/m");?></td>
                            <td><?=substr($rs->time_start,11,5);?></td>
                            <td><?=substr($rs->time_end,11,5);?></td>
                            <td><?=decimalToHours($rs->hour_range);?></td>
                        </tr>
                      <?php } ?>
                      </tbody>
                    </table><br>
                    <button class="btn btn-success btn-rounded" name="all">อนุมัติที่เลือก</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
        </div>
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
