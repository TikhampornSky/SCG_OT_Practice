<?php
include("../dbconnect.php");
include("checkLine.php");
include "../thisWeek.php";
$line_id = $_GET['w1'];
checkLine($line_id);
// require_once('class.thisWeek.php');// Start the session
session_start();
// Set session variables
$line_id = $_SESSION["line_id"];
$approver_id = $_SESSION["user_id"];
// echo $line_id;

$sql = mysqli_query($con,"SELECT * FROM approverInfo WHERE approver_id = '$approver_id'");
$rs = $sql->fetch_object();
$name = $rs->approver_name;
$lastname = $rs->approver_lastname;



date_default_timezone_set('Asia/Bangkok');
$time_stamp = date("Y-m-d H:i:s");
$time = new week();
$time->set_day($time_stamp);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta name="viewport"
        content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=0,viewport-fit=cover" />
    <title>สรุปข้อมูล OT ทั้งหมด</title>
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
        @import url('https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&subset=devanagari,latin-ext');

        :root {
            --white: #000000;
            --light: #f0eff3;
            --black: #000000;
            --dark-blue: #1f2029;
            --dark-light: #f0eff3;
            --red: #E6E6FA;
            --yellow: #E6E6FA;
            --grey: #ecedf3;
        }

        /* #Primary
        ================================================== */

        /*body {*/
        /*    width: 100%;*/
        /*    background: var(--dark-blue);*/
        /*    overflow-x: hidden;*/
        /*    font-family: 'Poppins', sans-serif;*/
        /*    font-size: 17px;*/
        /*    line-height: 30px;*/
        /*    -webkit-transition: all 300ms linear;*/
        /*    transition: all 300ms linear;*/
        /*}*/

        p {
            font-family: 'Poppins', sans-serif;
            font-size: 17px;
            line-height: 30px;
            color: var(--white);
            letter-spacing: 1px;
            font-weight: 500;
            -webkit-transition: all 300ms linear;
            transition: all 300ms linear;
        }

        ::selection {
            color: var(--white);
            background-color: var(--black);
        }

        ::-moz-selection {
            color: var(--white);
            background-color: var(--black);
        }

        mark {
            color: var(--white);
            background-color: var(--black);
        }

        .section {
            position: relative;
            width: 100%;
            display: block;
            text-align: center;
            margin: 0 auto;
        }

        .over-hide {
            overflow: hidden;
        }

        .z-bigger {
            z-index: 100 !important;
        }


        .background-color {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: var(--dark-blue);
            z-index: 1;
            -webkit-transition: all 300ms linear;
            transition: all 300ms linear;
        }

        .checkbox:checked~.background-color {
            background-color: var(--white);
        }


        [type="checkbox"]:checked,
        [type="checkbox"]:not(:checked),
        [type="radio"]:checked,
        [type="radio"]:not(:checked) {
            position: absolute;
            left: -9999px;
            width: 0;
            height: 0;
            visibility: hidden;
        }

        .checkbox:checked+label,
        .checkbox:not(:checked)+label {
            position: relative;
            width: 70px;
            display: inline-block;
            padding: 0;
            margin: 0 auto;
            text-align: center;
            margin: 17px 0;
            margin-top: 100px;
            height: 6px;
            border-radius: 4px;
            background-image: linear-gradient(298deg, var(--red), var(--yellow));
            z-index: 100 !important;
        }

        .checkbox:checked+label:before,
        .checkbox:not(:checked)+label:before {
            position: absolute;
            font-family: 'unicons';
            cursor: pointer;
            top: -17px;
            z-index: 2;
            font-size: 20px;
            line-height: 40px;
            text-align: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            -webkit-transition: all 300ms linear;
            transition: all 300ms linear;
        }

        .checkbox:not(:checked)+label:before {
            content: '\eac1';
            left: 0;
            color: var(--grey);
            background-color: var(--dark-light);
            box-shadow: 0 4px 4px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(26, 53, 71, 0.07);
        }

        .checkbox:checked+label:before {
            content: '\eb8f';
            left: 30px;
            color: var(--yellow);
            background-color: var(--dark-blue);
            box-shadow: 0 4px 4px rgba(26, 53, 71, 0.25), 0 0 0 1px rgba(26, 53, 71, 0.07);
        }

        .checkbox:checked~.section .container .row .col-12 p {
            color: var(--dark-blue);
        }


        .checkbox-tools:checked+label,
        .checkbox-tools:not(:checked)+label {
            position: relative;
            display: inline-block;
            padding: 20px;
            width: 110px;
            font-size: 14px;
            line-height: 20px;
            letter-spacing: 1px;
            margin: 0 auto;
            margin-left: 5px;
            margin-right: 5px;
            margin-bottom: 10px;
            text-align: center;
            border-radius: 4px;
            overflow: hidden;
            cursor: pointer;
            text-transform: uppercase;
            color: var(--white);
            -webkit-transition: all 300ms linear;
            transition: all 300ms linear;
        }

        .checkbox-tools:not(:checked)+label {
            background-color: var(--dark-light);
            box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
        }

        .checkbox-tools:checked+label {
            background-color: transparent;
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
        }

        .checkbox-tools:not(:checked)+label:hover {
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
        }

        .checkbox-tools:checked+label::before,
        .checkbox-tools:not(:checked)+label::before {
            position: absolute;
            content: '';
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 4px;
            background-image: linear-gradient(298deg, var(--red), var(--yellow));
            z-index: -1;
        }

        .checkbox-tools:checked+label .uil,
        .checkbox-tools:not(:checked)+label .uil {
            font-size: 24px;
            line-height: 24px;
            display: block;
            padding-bottom: 10px;
        }

        .checkbox:checked~.section .container .row .col-12 .checkbox-tools:not(:checked)+label {
            background-color: var(--light);
            color: var(--dark-blue);
            box-shadow: 0 1x 4px 0 rgba(0, 0, 0, 0.05);
        }

        .checkbox-budget:checked+label,
        .checkbox-budget:not(:checked)+label {
            position: relative;
            display: inline-block;
            padding: 0;
            padding-top: 20px;
            padding-bottom: 20px;
            width: 260px;
            /* font-size: 52px; */
            line-height: 52px;
            /* font-weight: 700; */
            letter-spacing: 1px;
            margin: 0 auto;
            margin-left: 5px;
            margin-right: 5px;
            margin-bottom: 10px;
            text-align: center;
            border-radius: 4px;
            overflow: hidden;
            cursor: pointer;
            /* text-transform: uppercase; */
            -webkit-transition: all 300ms linear;
            transition: all 300ms linear;
            -webkit-text-stroke: 1px var(--white);
            /* text-stroke: 1px var(--white); */
            -webkit-text-fill-color: transparent;
            /* text-fill-color: transparent; */
            color: transparent;
        }

        .checkbox-budget:not(:checked)+label {
            background-color: var(--dark-light);
            box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
        }

        .checkbox-budget:checked+label {
            background-color: transparent;
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
        }

        .checkbox-budget:not(:checked)+label:hover {
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
        }

        .checkbox-budget:checked+label::before,
        .checkbox-budget:not(:checked)+label::before {
            position: absolute;
            content: '';
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 4px;
            background-image: linear-gradient(138deg, var(--red), var(--yellow));
            z-index: -1;
        }

        .checkbox-budget:checked+label span,
        .checkbox-budget:not(:checked)+label span {
            position: relative;
            display: block;
        }

        .checkbox-budget:checked+label span::before,
        .checkbox-budget:not(:checked)+label span::before {
            position: absolute;
            content: attr(data-hover);
            top: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            -webkit-text-stroke: transparent;
            text-stroke: transparent;
            -webkit-text-fill-color: var(--white);
            text-fill-color: var(--white);
            color: var(--white);
            -webkit-transition: max-height 0.3s;
            -moz-transition: max-height 0.3s;
            transition: max-height 0.3s;
        }

        .checkbox-budget:not(:checked)+label span::before {
            max-height: 0;
        }

        .checkbox-budget:checked+label span::before {
            max-height: 100%;
        }

        .checkbox:checked~.section .container .row .col-xl-10 .checkbox-budget:not(:checked)+label {
            background-color: var(--light);
            -webkit-text-stroke: 1px var(--dark-blue);
            text-stroke: 1px var(--dark-blue);
            box-shadow: 0 1x 4px 0 rgba(0, 0, 0, 0.05);
        }

        .checkbox-booking:checked+label,
        .checkbox-booking:not(:checked)+label {
            position: relative;
            display: -webkit-inline-flex;
            display: -ms-inline-flexbox;
            display: inline-flex;
            -webkit-align-items: center;
            -moz-align-items: center;
            -ms-align-items: center;
            align-items: center;
            -webkit-justify-content: center;
            -moz-justify-content: center;
            -ms-justify-content: center;
            justify-content: center;
            -ms-flex-pack: center;
            text-align: center;
            padding: 0;
            padding: 6px 25px;
            font-size: 14px;
            line-height: 30px;
            letter-spacing: 1px;
            margin: 0 auto;
            margin-left: 6px;
            margin-right: 6px;
            margin-bottom: 16px;
            text-align: center;
            border-radius: 4px;
            cursor: pointer;
            color: var(--white);
            text-transform: uppercase;
            background-color: var(--dark-light);
            -webkit-transition: all 300ms linear;
            transition: all 300ms linear;
        }

        .checkbox-booking:not(:checked)+label::before {
            box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
        }

        .checkbox-booking:checked+label::before {
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
        }

        .checkbox-booking:not(:checked)+label:hover::before {
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
        }

        .checkbox-booking:checked+label::before,
        .checkbox-booking:not(:checked)+label::before {
            position: absolute;
            content: '';
            top: -2px;
            left: -2px;
            width: calc(100% + 4px);
            height: calc(100% + 4px);
            border-radius: 4px;
            z-index: -2;
            background-image: linear-gradient(138deg, var(--red), var(--yellow));
            -webkit-transition: all 300ms linear;
            transition: all 300ms linear;
        }

        .checkbox-booking:not(:checked)+label::before {
            top: -1px;
            left: -1px;
            width: calc(100% + 2px);
            height: calc(100% + 2px);
        }

        .checkbox-booking:checked+label::after,
        .checkbox-booking:not(:checked)+label::after {
            position: absolute;
            content: '';
            top: -2px;
            left: -2px;
            width: calc(100% + 4px);
            height: calc(100% + 4px);
            border-radius: 4px;
            z-index: -2;
            background-color: var(--dark-light);
            -webkit-transition: all 300ms linear;
            transition: all 300ms linear;
        }

        .checkbox-booking:checked+label::after {
            opacity: 0;
        }

        .checkbox-booking:checked+label .uil,
        .checkbox-booking:not(:checked)+label .uil {
            font-size: 20px;
        }

        .checkbox-booking:checked+label .text,
        .checkbox-booking:not(:checked)+label .text {
            position: relative;
            display: inline-block;
            -webkit-transition: opacity 300ms linear;
            transition: opacity 300ms linear;
        }

        .checkbox-booking:checked+label .text {
            opacity: 0.6;
        }

        .checkbox-booking:checked+label .text::after,
        .checkbox-booking:not(:checked)+label .text::after {
            position: absolute;
            content: '';
            width: 0;
            left: 0;
            top: 50%;
            margin-top: -1px;
            height: 2px;
            background-image: linear-gradient(138deg, var(--red), var(--yellow));
            z-index: 1;
            -webkit-transition: all 300ms linear;
            transition: all 300ms linear;
        }

        .checkbox-booking:not(:checked)+label .text::after {
            width: 0;
        }

        .checkbox-booking:checked+label .text::after {
            width: 100%;
        }

        .checkbox:checked~.section .container .row .col-12 .checkbox-booking:not(:checked)+label,
        .checkbox:checked~.section .container .row .col-12 .checkbox-booking:checked+label {
            background-color: var(--light);
            color: var(--dark-blue);
        }

        .checkbox:checked~.section .container .row .col-12 .checkbox-booking:checked+label::after,
        .checkbox:checked~.section .container .row .col-12 .checkbox-booking:not(:checked)+label::after {
            background-color: var(--light);
        }




        .link-to-page {
            position: fixed;
            top: 30px;
            right: 30px;
            z-index: 20000;
            cursor: pointer;
            width: 50px;
        }

        .link-to-page img {
            width: 100%;
            height: auto;
            display: block;
        }
    </style>

    <script>
        function myFunction() {
            // var x = document.getElementById("tool-1").checked;
            var cont = document.getElementById('cont');

            if (cont.style.display == 'none') {
                cont.style.display = 'block';
            } else {
                cont.style.display = 'block';
            }
        }
        function myFunction2() {
            // var x = document.getElementById("tool-2").checked;
            var cont = document.getElementById('cont');

            if (cont.style.display == 'block') {
                cont.style.display = 'none';
                // document.getElementById("myText").value = "Johnny Bravo";
            }
        }
        function mytime() {
            var cont = document.getElementById('time');
            if (cont.style.display == 'none') {
                cont.style.display = 'block';
            } else {
                cont.style.display = 'block';
            }

        }
        function mytime2() {
            var cont = document.getElementById('time');
            if (cont.style.display == 'block') {
                cont.style.display = 'none';
            }
        }
    </script>
</head>

<body>
    <div class="container-scroller">
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center" style="width: 80px;">
                <a class="navbar-brand brand-logo mr-5"><img src="../../images/logo.jpeg" class="mr-2" alt="logo" /></a>
                <a class="navbar-brand brand-logo-mini"><img src="../../images/img.png" alt="logo" class="mr-2" alt="logo" style="width: 75px; height: 50px;" /></a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end" style="width: calc(100% - 80px);">
                <span class="text-dark">คุณ <?php echo $name;?> <?php echo $lastname;?></span>
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
                        <div class="row">
                            <div class="col-md-12  stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 grid-margin stretch-card">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="card-body" style="text-align: center; font-size: 140%; font-weight: bold; color: black;">สรุปข้อมูล OT ทั้งหมด</div>
                                                        <h5 style="text-align:center; color: blue;">กรุณาโปรดเลือกรายชื่อและช่วงเวลา</h5><br>
                                                            <form action="reportDetail.php" method="GET">
                                                            <div class="row">
                                                                <div class="section over-hide z-bigger">
                                                                    <div class="row justify-content-center pb-5">
                                                                        <p class="" style="color: var(--dark); text-align:center">เลือกรายชื่อ</p>
                                                                        <div class="col-12 pb-5">
                                                                            <input class="checkbox-tools" type="radio" name="tools" id="tool-1"
                                                                                onclick="myFunction()">
                                                                            <label class="for-checkbox-tools" for="tool-1"
                                                                                style="width: 100%;">เลือกรายชื่อพนักงานในสังกัด
                                                                                <div id="cont" style="color: var(--dark); display:none;">
                                                                                    <select style="width: 100%;" name="id">
                                                                                        <option value="">เลือกทั้งหมด</option>
                                                                                        <?php
                                                                                        $sql1 = ("SELECT * FROM employeeInfo a , approverInfo b WHERE a.approver_id = b.approver_id AND b.approver_id = '$approver_id'");
                                                                                        $rs1 = $con->query($sql1);
                                                                                        foreach($rs1 as $rs1){?>
                                                                                            <option value="<?php echo $rs1["employee_id"];?>">
                                                                                            <?php echo $rs1["employee_name"]; echo " "; echo $rs1["employee_lastname"]; ?>
                                                                                            </option>
                                                                                        <?php } ?>
                                                                                    </select>
                                                                                </div>
                                                                            </label><br>
                                            
                                                                            <!--input class="checkbox-tools" type="radio" name="tools" id="tool-2"
                                                                                onclick="myFunction2()">
                                                                            <label class="for-checkbox-tools" for="tool-2" style="width: 100%; ">
                                                                                <input type="hidden" name="all" value="mm" id="myText">ดูทั้งหมด
                                                                            </label-->
                                                                        </div>
                                            
                                                                        <div class="col-12">
                                                                            <p class="" style="color: var(--dark);">เลือกช่วงเวลา</p>
                                                                        </div>
                                                                        <div class="col-xl-12 pb-5">
                                                                            <input class="checkbox-tools" type="radio" name="budget" id="budget-3" value="week">
                                                                            <label class="" for="budget-3" style="width: 100%;" onclick="mytime2()">
                                                                                <span>สัปดาห์นี้</span>
                                                                            </label><br>
                                            
                                                                            <input class="checkbox-tools" type="radio" name="budget" id="budget-4" value="month">
                                                                            <label class="" for="budget-4" style="width: 100%;" onclick="mytime2()">
                                                                                <span>เดือนนี้</span>
                                                                            </label><br>
                                            
                                                                            <input class="checkbox-tools" type="radio" name="budget" id="budget-5" value="range">
                                                                            <label class="for-checkbox-budget" for="budget-5" style="width: 100%;"
                                                                                onclick="mytime()">ระบุช่วงเวลาเอง
                                                                                <div id="time" style="color: var(--dark); display:none;">
                                                                                    <div class="row">
                                                                                        <div class="col-6">
                                                                                            <p data-hover="week">วันเริ่ม</p>
                                                                                            <input type="date" name="day_start" class="form-control"
                                                                                                style="-webkit-appearance: none; -moz-appearance: none; color: var(--dark);" />
                                                                                        </div>
                                                                                        <div class="col-6">
                                                                                            <p data-hover="month">วันเริ่ม</p>
                                                                                            <input type="date" name="day_end" class="form-control"
                                                                                                style="-webkit-appearance: none; -moz-appearance: none; color: var(--dark);" />
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </label>
                                                                        </div>
                                            
                                                                        <div class="col-12">
                                                                            <button class="btn btn-success form-control" style="width: 100%;">ค้นหา</button>
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
                            </div>
                        </div>
                    </div>
                </div>
                
                 
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