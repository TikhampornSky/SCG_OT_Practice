<?php
include("screen/dbconnect.php"); // Using database connection file here
$line_id = $_GET['w1'];
$line_name = $_GET['name'];
$line_email = $_GET['email'];

$user_id = $_POST['user_id'];



// echo "UPDATE `employee` SET `employee_name`='$line_name', `line_id`='$line_id',`mail`=$line_email WHERE `employee_id` = '$user_id'";

$booking = "02$user_id";
if (isset($_POST["login"])) {
    // echo "UPDATE `employee` SET `employee_pwd`='$pwd',`employee_name`='$line_name', `line_id`='$line_id' WHERE `employee_id` = '$user_id'";
    // $sql = mysqli_query($con, "UPDATE `employee` SET `employee_name`='$line_name', `line_id`='$line_id',`mail`='$line_email' WHERE `employee_id` = '$user_id'");
    $sql = mysqli_query($con, "UPDATE `employee` SET `line_id`='$line_id' WHERE `employee_id` = '$user_id'");
    if (mysqli_affected_rows($con) == '1'){
    header("Location: index.html");
    }else{
        echo "<script>
        alert('คุณยังไม่ได้ยินยอมการแจ้งเตือนกรุณาทำรายการยินยอมรับการแจ้งเตือนก่อน');
        window.location.href='https://scgot.online/screen/employee/line/lineToken.php';
        </script>";
    }
}

// Start the session
session_start();
// Set session variables
$_SESSION["user_id"] = $user_id;


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>สร้างบัญชี</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../vendors/feather/feather.css">
    <link rel="stylesheet" href="../vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="../css/vertical-layout-light/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="../images/favicon.png" />
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex  auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                            <div class="brand-logo">
                                <img src="../images/logo.svg" alt="logo">
                            </div>
                            <h6 class="font-weight-light">ยืนยันตัวตนอีกครั้ง</h6>

                            <form class="pt-3" method="POST">
                                <div class="form-group">
                                    <input type="text" name="user_id" class="form-control form-control-lg" id="exampleInputUsername1" placeholder="รหัสพนักงาน 0150-xxxxxx" />
                                </div>
                                <!-- <div class="form-group"> -->
                                
                                <!-- </div> -->
                                <div class="mt-3">
                                    <a href="index.php"><button type="submit" name="login" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">ยืนยันการใช้งาน</button></a>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="js/off-canvas.js"></script>
    <script src="js/hoverable-collapse.js"></script>
    <script src="js/template.js"></script>
    <script src="js/settings.js"></script>
    <script src="js/todolist.js"></script>
    <!-- endinject -->
</body>

</html>
<?php
mysqli_close($con);
?>