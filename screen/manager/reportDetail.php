<?php
include("../dbconnect.php");
require_once("../test.php");
session_start();
// Set session variables
$line_id = $_SESSION["line_id"]; //approver line id
$approver_id = $_SESSION["user_id"]; //approver line id

include "../thisWeek.php";
date_default_timezone_set('Asia/Bangkok');
$time_stamp = date("Y-m-d H:i:s");
$time = new week();
$time->set_day($time_stamp);
$start_mon =  $time->get_start();
$end_mon = $time->get_end();

$employee_id = $_GET['id'];
$all = $_GET['all'];
$time = $_GET['budget'];
$start = $_GET['day_start'];
$end = $_GET['day_end'];


$sql = "";
$approve = "";
$waiting = "";
$reject = "";
$date1 = "";
$date2 = "";
if ($time == 'week') {
    $date1 = $start_mon;
    $date2 = $end_mon;
    $in = "07:30";
    $out = "07:59";
} else if ($time == 'month') {
    $date1 = date("Y-m-01", strtotime($time_stamp));
    $date2 = date("Y-m-t", strtotime($time_stamp));
    $in = "07:30";
    $out = "07:59";
} else if ($time == 'range') {
    // echo ("range");
    $date1 = $start;
    $date2 = $end;
    $in = "07:30";
    $out = "07:59";
}
// echo $date1;
// echo $date2;

$id = "";

if ($employee_id != "") {
    $id = $employee_id;
    // $sql = "SELECT * FROM employee b, approver c, users d WHERE b.employee_id= '$id' AND c.line_id = '$line_id' AND b.department_id=c.department_id AND d.user_id =b.employee_id";
    $sql = "SELECT * FROM employeeInfo a, approverInfo b WHERE a.employee_id = '$id' AND a.approver_id = b.approver_id ";
    // echo "SELECT * FROM employeeInfo a, approverInfo b WHERE a.employee_id = '$id' AND a.approver_id = b.approver_id ";
} else {
    $id = $approver_id;
    // $sql = "SELECT * FROM employee b, approver c, users d WHERE c.line_id = '$id' AND b.department_id=c.department_id AND d.user_id = b.employee_id";
    $sql = "SELECT * FROM employeeInfo a, approverInfo b WHERE b.approver_id = '$id' AND a.approver_id = b.approver_id ";
    // echo "SELECT * FROM employeeInfo a, approverInfo b WHERE b.approver_id = '$id' AND a.approver_id = b.approver_id ";
    
}

$result = $con->query($sql);

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
                        
    <div class="content-wrapper" style="min-height: calc(100vh - 60px);">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title mb-0">สรุปข้อมูล OT ของพนักงาน</p><br>
                        <div class="table-responsive">
                            <form action="reportIndividual.php" method="GET">
                                <table class="">
                                    <thead>
                                        <tr>
                                            <th>พิจารณา</th>
                                            <th>ชื่อลูกทีม</th>
                                            <th>เริ่มต้น</th>
                                            <th>สิ้นสุด</th>
                                            <th>ขอไปแล้ว</th>
                                            <th>อนุมัติแล้ว</th>
                                            <th>รออนุมัติ</th>
                                            <th>ถูกปฏิเสธ</th>
                                            <?php if($time=='week'){?>
                                                <th>คงเหลือ</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $result = $con->query($sql);
                                        while ($rs = $result->fetch_object()) {
                                            $sql1 = mysqli_query($con,
"SELECT SUM(hour_range) AS approve FROM transaction WHERE user_id = '$rs->employee_id' AND time_start BETWEEN '$date1 $in' AND '$date2 $out' AND approve_status = 'approve'");
                                            $sql2 = mysqli_query($con, 
"SELECT SUM(hour_range) AS waiting FROM transaction WHERE user_id = '$rs->employee_id' AND time_start BETWEEN '$date1 $in' AND '$date2 $out' AND approve_status = 'waiting'");

                                            $sql3 = mysqli_query($con,
"SELECT SUM(hour_range) AS reject FROM transaction WHERE user_id = '$rs->employee_id' AND time_start BETWEEN '$date1 $in' AND '$date2 $out' AND approve_status = 'reject'");

                                            $sql4 = mysqli_query($con,
"SELECT SUM(hour_range) AS edit FROM transaction WHERE user_id = '$rs->employee_id' AND time_start BETWEEN '$date1 $in' AND '$date2 $out' AND approve_status = 'edit'");

                                            $rs1 = $sql1->fetch_object();
                                            $rs2 = $sql2->fetch_object();
                                            $rs3 = $sql3->fetch_object();
                                            $rs4 = $sql4->fetch_object();

                                            
                                            $approve = $rs1->approve;
                                            $waiting = $rs2->waiting;
                                            $reject = $rs3->reject;
                                            $edit = $rs4->edit;

                                            if ($requst == "") {$requst = 0;}
                                            if ($approve == "") {$approve = 0;}
                                            if ($waiting == "") {$waiting = 0;}
                                            if ($reject == "") {$reject = 0;}
                                            if ($edit == "") {$edit = 0;}
                                            $requst = $approve+$waiting+$edit;
                                            $approve= $approve+$edit;
                                        ?>
                                            <tr>
                                                <input type="hidden" name="userid[]" value="<?= $rs->employee_id; ?>" />
                                                <td><a href="reportIndividual.php?id=<?=$rs->employee_id;?>&amp;name=<?=$rs->user_name;?>&amp;lastname=<?=$rs->user_lastname;?>&amp;time=<?=$time;?>&amp;date1=<?=$date1;?>&amp;date2=<?=$date2;?>&amp;request=<?=$requst;?>&amp;approve=<?=$approve?>&amp;waitng=<?=$waiting?>&amp;reject=<?=$reject?>">Click</td></a>
                                                <td><?= $rs->employee_name; ?></td>
                                                <td nowrap><?php echo $date1; ?></td>
                                                <td nowrap><?php echo $date2; ?></td>
                                                <td><?php echo decimalToHours($requst); ?></td>
                                                <td><?php echo decimalToHours($approve); ?></td>
                                                <td><?php echo decimalToHours($waiting); ?></td>
                                                <td><?php echo decimalToHours($reject); ?></td>
                                                <?php if($time=='week'){?>
                                                    <td><?php echo decimalToHours((36 - $requst)); ?></td>
                                                <?php } ?>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <!--<footer class="footer">-->
        <!--    <div class="d-sm-flex justify-content-center justify-content-sm-between">-->
        <!--        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2021. Premium <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from BootstrapDash. All rights reserved.</span>-->
        <!--        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="ti-heart text-danger ml-1"></i></span>-->
        <!--    </div>-->
        <!--    <div class="d-sm-flex justify-content-center justify-content-sm-between">-->
        <!--        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Distributed by <a href="https://www.themewagon.com/" target="_blank">Themewagon</a></span>-->
        <!--    </div>-->
        <!--</footer>-->
        <!-- partial -->
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