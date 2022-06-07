<?php
include("../dbconnect.php");
include("checkLine.php");

$line_id = $_GET['w1'];

$sql = mysqli_query($con, "SELECT * FROM employee a , employeeInfo b WHERE a.line_id = '$line_id' AND a.employee_id = b.employee_id");
$rs = $sql->fetch_object();
$department = $rs->department_id;
$name = $rs->employee_name;
$lastname = $rs->employee_lastname;

checkLine($line);

if (isset($_POST["normal"])) {
    userType("normal");
    header( "Location: information.php");
}

if (isset($_POST["round"])) {
    userType("round");
    header( "Location: information.php");
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
                        <form method="POST">
                            <div class="card-body ">
                                <div class="" style="text-align: center; ">
                                  <b class="card-title text-dark">เลือกรูปแบบการทำงาน</b>
                                </div>
                            </div><br>
                            <div class="row">
                                <div class="col-md-12  stretch-card">
                                      <div class="card">
                                            <button name="normal" class="col-md-12 btn text-light" style="background:#2980B9; min-height: calc(30vh); text-align: center; font-size: 150%;">พนักงานปกติ</button>
                                      </div>
                                </div>
                             </div>
                             <br><br>
                             <div class="row">
                                <div class="col-md-12  stretch-card">
                                      <div class="card">
                                            <button name="round" class="col-md-12 btn text-light" style="background:#16A085; min-height: calc(30vh); text-align: center; font-size: 150%;">พนักงานกะ</button>
                                      </div>
                                </div>
                             </div>
                         </form>
                    </div>
              </div>
            </div>
        </div>
         <!--page-body-wrapper ends -->
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
?>