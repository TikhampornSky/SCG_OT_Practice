<?php
include("../screen/dbconnect.php");

define('CLIENT_ID', 'NbIj4ngBk4yM4uyEjCOFTp');
define('CLIENT_SECRET', 'bu2CcNraz7sBmswrBjQKn4zgaK8DrzH40kkkDVrDqT1');
define('LINE_API_URI', 'https://notify-bot.line.me/oauth/token');
define('CALLBACK_URI', 'https://scgot.online/line/callback.php');

parse_str($_SERVER['QUERY_STRING'], $queries);

$fields = [
    'grant_type' => 'authorization_code',
    'code' => $queries['code'],
    'redirect_uri' => CALLBACK_URI,
    'client_id' => CLIENT_ID,
    'client_secret' => CLIENT_SECRET
];

try {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, LINE_API_URI);
    curl_setopt($ch, CURLOPT_POST, count($fields));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $res = curl_exec($ch);
    curl_close($ch);

    if ($res == false)
        throw new Exception(curl_error($ch), curl_errno($ch));

    $json = json_decode($res);
    $token = $json->access_token;
    // echo ($token);

    // var_dump($json);
    // var_dump($json.access_token);
} catch (Exception $e) {
    var_dump($e);
}

$employee_id = $_POST['user_id'];
$line_token = $_POST['line_token'];

$booking = "b$user_id";
if (isset($_POST["submit"])) {
    // $sql = mysqli_query($con, "INSERT INTO `users`(`employee_id`,`line_token`, `booking_id`) VALUES ('$employee_id','$line_token','$booking')");
    echo ("INSERT INTO `users`(`employee_id`,`line_token`, `booking_id`) VALUES ('$employee_id','$line_token','$booking')"); 
    // $sql = mysqli_query($con, "INSERT INTO `booking`(`booking_id`) VALUES ('$booking')");
    echo ("INSERT INTO `booking`(`booking_id`) VALUES ('$booking')");
    // if ($sql) {
        // header("Location: lineId.php?user_id={$user_id}");
        // header("Location: https://lin.ee/RPd2a0c");
    // }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>แบบฟอร์มกรอก</title>
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

    <script>
        //   inputBox.onkeyup = function(){
        //       document.getElementById('printhour').innerHTML = <?= $difference ?>;
        //   }
    </script>
</head>

<body>
    <div class="container-scroller">
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo mr-5" href="../../index.html"><img src="../../images/logo.svg" class="mr-2" alt="logo" /></a>
                <a class="navbar-brand brand-logo-mini" href="../../index.html"><img src="../../images/logo-mini.svg" alt="logo" /></a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                <!--<span class="text-dark">คุณ <?= $rs->employee_name ?></span>-->
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

            <div class="col-12 p-0">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12  stretch-card">
                                    <div class="card data-icon-card-light">
                                        <center>
                                            <label class="card-body  text-dark " for="exampleTextarea1">ขอขอบคุณสำหรับการอนุญาติการแจ้งเตือนผ่านไลน์ กรุณากรอกข้อมูลที่จะเป็นในการเริ่มใช้งาน LINE OT
                                            </label>
                                        </center>
                                    </div>
                                </div>
                                <div class="col-md-12  stretch-card">
                                    <div class="card data-icon-card-primary">
                                        <div class="card-body  text-white " style="text-align: center; font-size: 150%;">แบบฟอร์มข้อมูลส่วนตัว </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div><br>
                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <form class="pt-3" method="POST">
                                        <input name="line_token" type="hidden" value="<?php echo $token; ?>" />
                                        <div class="form-group">
                                            <input type="text" name="user_id" class="form-control form-control-lg" id="exampleInputUsername1" placeholder="รหัสพนักงาน 0150-xxxxxx" />
                                        </div>
                                        <!-- <div class="form-group">
                                            <input type="text" name="username" class="form-control form-control-lg" id="exampleInputEmail1" placeholder="ชื่อ สมชาย ใจงาม" />
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="pwd" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="รหัสผ่าน" />
                                        </div> -->
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
                                            <button type="submit" name="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">บันทึกข้อมูล</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:../../partials/_footer.html -->
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2021. Premium <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from BootstrapDash. All rights reserved.</span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="ti-heart text-danger ml-1"></i></span>
                    </div>
                </footer>
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