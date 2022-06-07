<?php
include("dbconnect.php"); // Using database connection file here
$user_id = $_POST['user_id'];
$username = $_POST['username'];
$pwd = $_POST['pwd'];
$department = $_POST['department'];

$booking = "02$user_id";
if (isset($_POST["login"])) {
    $sql = mysqli_query($con, "INSERT INTO `users`(`user_id`, `user_pwd`, `user_type`) VALUES ('$user_id','$pwd','employee')");
    $sql = mysqli_query($con, "INSERT INTO `booking`(`booking_id`) VALUES ('$booking')");
    $sql = mysqli_query($con, "INSERT INTO `employee`(`user_id`, `employee_name`, `department_id`, `booking_id`) VALUES ('$user_id','$username','$department','$booking')");
    header("Location: ../index.php");
}


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
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                            <div class="brand-logo">
                                <img src="../images/logo.svg" alt="logo">
                            </div>
                            <h6 class="font-weight-light">การสร้างบัญชีง่ายๆเพียงไม่กี่ขั้นตอน</h6>

                            <form class="pt-3" method="POST">
                                <div class="form-group">
                                    <input type="text" name="user_id" class="form-control form-control-lg" id="exampleInputUsername1" placeholder="รหัสพนักงาน 0150-xxxxxx" />
                                </div>
                                <div class="form-group">
                                    <input type="text" name="username" class="form-control form-control-lg" id="exampleInputEmail1" placeholder="ชื่อ สมชาย ใจงาม" />
                                </div>
                                <div class="form-group">
                                    <input type="password" name="pwd" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="รหัสผ่าน" />
                                </div>
                                <!-- <div class="form-group"> -->
                                <select name="department" class="form-control form-control-lg" id="exampleFormControlSelect2" placeholder="แผนก">
                                    <?php
                                    $sql = "SELECT * FROM department";
                                    $result = $con->query($sql);
                                    foreach ($result as $result) { ?>
                                        <option value="<?php echo $result["department_id"]; ?>">
                                            <?php echo $result["department_name"]; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <!-- </div> -->
                                <div class="mt-3">
                                    <a href="index.php"><button type="submit" name="login" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">สร้างบัญชี</button></a>
                                </div>
                                <div class="text-center mt-4 font-weight-light">
                                    หากมีบัญชีอยู่แล้ว? <a href="login.php" class="text-primary">ล็อคอิน</a>
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