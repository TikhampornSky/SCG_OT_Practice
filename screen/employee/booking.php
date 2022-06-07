<?php
include("../dbconnect.php");
include("../test.php");
session_start();
$user_id = $_SESSION["user_id"];
$typepe = $_SESSION["user_type"];


$sql = mysqli_query($con, "SELECT * FROM employee a , employeeInfo b WHERE a.employee_id = '$user_id' AND a.employee_id = b.employee_id");
$rs = $sql->fetch_object();
$name = $rs->employee_name;
$lastname = $rs->employee_lastname;

if ($typepe == "normal"){
    $type = "(พนักงานปกติ)";
    $in = '07:30';
    $out = '07:29';
}else {
    $type = "(พนักงานกะ)";
    $in = '08:00';
    $out = '08:00';
}

$number = 0;
$normal = 0;
$lunch = 0;
$range = "";

if (isset($_POST["save"])) {
    $date = $_POST['date'];
    $time_start = $_POST['time_start'];
    $time_end = $_POST['time_end'];
    $request_msg = $_POST['msg'];
    $reason = $_POST['reason'];
    $detail = $_POST['detail'];
    $range = $time_end - $time_start;
    
    $stamp = strtotime($date);
    $ot = $_GET['ot'];
    if($type == "(พนักงานกะ)"){
        $ot = "normal";
    }
    
    if($ot == "lunch"){
        if((decimalHours($time_start) >= decimalHours('12:00')) && (decimalHours($time_start) <= decimalHours('13:00'))){
            if((decimalHours($time_end) >= decimalHours('12:00')) && (decimalHours($time_end) <= decimalHours('13:00'))){
                header("Location: temp.php?ot=lunch&date=$date&time_start=$time_start&time_end=$time_end&msg=$request_msg&reason=$reason&detail=$detail");
            }
            else {
                $lunch = 1;
            }
        }else{
            $lunch = 1;
        }
    }if ($ot == "normal"){
        $checkDate = mysqli_query($con, "SELECT * FROM transaction WHERE user_id = '$user_id' AND date = '$date' AND approve_status != 'reject' AND approve_status != 'cancle'");
        if (mysqli_affected_rows($con) >= '1') {
            $checkSTime = mysqli_query($con, "SELECT * FROM transaction WHERE user_id = '$user_id' AND '$date' = date AND approve_status != 'reject' AND approve_status != 'cancle' AND '$time_start' BETWEEN time_start AND time_end");
            if (mysqli_affected_rows($con) >= '1') {
                $number = 1;
            } else {
                if((decimalHours($time_start) >= decimalHours('12:00')) && (decimalHours($time_start) <= decimalHours('13:00')) && (decimalHours($time_end) >= decimalHours('12:00')) && (decimalHours($time_end) <= decimalHours('13:00'))){
                    $normal = 1;
                }else{
                    header("Location: temp.php?ot=$typepe&date=$date&time_start=$time_start&time_end=$time_end&msg=$request_msg&reason=$reason&detail=$detail");
                }
            }
        } else {
            if((decimalHours($time_start) >= decimalHours('12:00')) && (decimalHours($time_start) <= decimalHours('13:00')) && (decimalHours($time_end) >= decimalHours('12:00')) && (decimalHours($time_end) <= decimalHours('13:00'))){
                    $normal = 1;
            }else{
            header("Location: temp.php?ot=$typepe&date=$date&time_start=$time_start&time_end=$time_end&msg=$request_msg&reason=$reason&detail=$detail");
            }
        }
        
    }
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
</head>

<body>
    <div class="container-scroller">
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row" >
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center" style="width: 80px;" >
                <a class="navbar-brand brand-logo mr-5"><img src="../../images/1.jpeg" class="mr-2" alt="logo" /></a>
                <a class="navbar-brand brand-logo-mini"><img src="../../images/img.png" class="mr-2" style="width: 75px; height: 50px;" /></a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end" style="width: calc(100% - 80px);">
            <span class="text-dark">คุณ <?=$name ?> <?=$lastname?><?=$ot?></span>
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
            <!--<div class="main-panel">-->
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12  stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="row">
                                                    <div class="col-12  stretch-card">
                                                        <div class="card">
                                                            <div class="text-dark " style="text-align: center; font-size: 150%; ">แบบฟอร์มขออนุมัติ OT</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><br>
                                        <div class="row">
                                            <div class="col-12 grid-margin stretch-card">
                                                <div class="card">
                                                    <p style="color: blue;">หมายเหตุ :</p>
                                                            <p style="color: blue; font-size: 85%">1. กรณีพนักงานทำ OT ควบ 2 กะ ห้ามขอ OT ควบกะ ให้ขอแยกเป็นกะ 2 ครั้ง</p>
                                                            <p style="color: blue; font-size: 85%">2. กรณีทำ OT ช่วงพักเที่ยง ให้ขอแยกอีกครั้งหนึ่ง โดยเลือกประเภทเป็น OT พักเที่ยง</p>
                                                    <div class="card-body">
                                                        <form action="" class="forms-sample" method="POST">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <label for="exampleInputPassword4">วันที่ขอ OT</label>
                                                                    <div class="form-group row">
                                                                        <div class="col-12">
                                                                            <input type="date" name="date" class="form-control col-12" placeholder="dd/mm/YYYY" style="-webkit-appearance: none; -moz-appearance: none;" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                        
                        
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <label for="exampleInputPassword4">เวลาเริ่ม</label>
                                                                    <div class="form-group row">
                                                                        <div class="col-12">
                                                                            <input type="time" id="Text1" oninput="add_number()" name="time_start" class="form-control" style="-webkit-appearance: none; -moz-appearance: none;" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                        
                                                                <div class="col-6">
                                                                    <label for="exampleInputPassword4">เวลาสิ้นสุด</label>
                                                                    <div class="form-group row">
                                                                        <div class="col-12">
                                                                            <input type="time" id="Text2" oninput="add_number()" name="time_end" class="form-control" style="-webkit-appearance: none; -moz-appearance: none;" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <p id="demo2" style="color: red;"></p>
                                                                    <center>
                                                                    <a href="otType.php"><span id="demo3" style="color: blue; display:none;">คลิ๊กที่นี้</span></a>
                                                                    </center>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <p id="demo1" style="color: red;"></p>
                                                                
                                                                <!--<a href="otType.php" style="color: red;"><p id="demo2"><span>คลิ๊ก</span></p></a>-->
                                                                
                                                                <?php
                                                                if($normal>0){
																	echo '<script>',
                                                                    'document.getElementById("demo2").innerHTML = "แบบฟอร์มนี้เป็นแบบฟอร์มขอ OT ปกติ กรณีขอ OT คาบเกี่ยวในช่วงเวลา 12:00-13:00 ให้ขออนุมัติในแบบฟอร์มขอ OT พักเที่ยงอีกครั้งหนึ่ง";',
                                                                    '</script>';
                                                                    
                                                                    echo '<script>',
                                                                    'document.getElementById("demo3").style.display = "block";',
                                                                    '</script>';
																}
                                                                if ($number > 0) {
                                                                    echo '<script>',
                                                                    'document.getElementById("demo1").innerHTML = "ช่วงเวลานี้คุณได้มีการขอแล้ว กรุณาเลือกช่วงเวลาใหม่";',
                                                                    '</script>';
                                                                }
                                                                if ($lunch > 0){
                                                                    echo '<script>',
                                                                    'document.getElementById("demo2").innerHTML = "แบบฟอร์มนี้เป็นแบบฟอร์มขอ OT ช่วงพักเที่ยง กรณีขอ OT นอกเหนือจากช่วงเวลา 12:00-13:00 ให้ขออนุมัติในแบบฟอร์มขอ OT ปกติ";',
                                                                    '</script>';
                                                                    
                                                                    echo '<script>',
                                                                    'document.getElementById("demo3").style.display = "block";',
                                                                    '</script>';
                                                                }
                                                                ?>
                                                            </div>
                                                            <!--<div class="row">-->
                                                            <!--    <div class="col-6">-->
                                                            <!--        <label for="exampleInputPassword4">จำนวนชั่วโมง</label>-->
                                                            <!--        <div class="form-group row">-->
                                                            <!--            <div class="col-12">-->
                                                            <!--                <input type="text" id="txtresult3" oninput="add_number()" class="form-control" style="-webkit-appearance: none; -moz-appearance: none;" />-->
                                                            <!--            </div>-->
                                                            <!--        </div>-->
                                                            <!--    </div>-->
                                                            <!--</div>-->
                                                            <script>
                                                                var text1 = document.getElementById("Text1");
                                                                var text2 = document.getElementById("Text2");
                        
                                                                function add_number() {
                                                                    var t1 = text1.value;
                                                                    var hours = t1.split(":")[0];
                                                                    var minutes = t1.split(":")[1];
                                                                    var displayTime = hours + "." + minutes;
                                                                    var first_number = parseFloat(displayTime); //txt to float
                                                                    if (isNaN(first_number)) first_number = 0;
                        
                                                                    var t2 = text2.value;
                                                                    var hours2 = t2.split(":")[0];
                                                                    var minutes2 = t2.split(":")[1];
                                                                    var displayTime2 = hours2 + "." + minutes2;
                                                                    var second_number = parseFloat(displayTime2);
                                                                    if (isNaN(second_number)) second_number = 0;
                                                                    
                        
                                                                    var result = (second_number - first_number).toFixed(2);
                                                                    document.getElementById("txtresult3").value = result;
                        
                                                                }
                                                            </script>
                                                            <div class="form-group">
                                                                <label for="exampleSelectGender">เหตุผล</label>
                                                                <select name="msg" class="form-control" onchange="yesnoCheck(this);" id="exampleSelectGender">
                                                                    <option value="งานต่อเนื่อง">งานต่อเนื่อง</option>
                                                                    <option value="Project">Project</option>
                                                                    <option value="งานเร่งด่วน">งานเร่งด่วน</option>
                                                                    <option value="วันหยุดประเพณี">วันหยุดประเพณี</option>
                                                                    <option value="งาน PM">งาน PM</option>
                                                                    <option value="TPM/AM">TPM/AM</option>
                                                                    <option value="ปฏิบัติงานแทนเพื่อนร่วมงาน">ปฏิบัติงานแทนเพื่อนร่วมงาน</option>
                                                                    <option value="OT ช่วงพัก/พักเที่ยง">OT ช่วงพัก/พักเที่ยง</option>
                                                                    <option value="other">อื่นๆ</option>
                                                                </select>
                                                                <div id="ifYes" style="display: none;">
                                                                    <label for="reason">เหตุผลอื่นๆ</label>
                                                                    <input type="text" id="car" name="reason" class="form-control" /><br />
                                                                </div>
                                                                <script>
                                                                    function yesnoCheck(that) {
                                                                        if (that.value == "other") {
                                                                            document.getElementById("ifYes").style.display = "block";
                                                                        } else {
                                                                            document.getElementById("ifYes").style.display = "none";
                                                                        }
                                                                    }
                                                                </script>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <label for="exampleInputPassword4">รายละเอียดเพิ่มเติม</label>
                                                                    <div class="form-group row">
                                                                        <div class="col-12">
                                                                            <input type="text" name="detail" oninput="add_number()" class="form-control" style="-webkit-appearance: none; -moz-appearance: none;" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <center>
                                                                <button type="submit" name="save" class="btn btn-primary mr-2 col-12">สรุปข้อมูล</button>
                                                            </center>
                        
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
            <!--    <footer class="footer">-->
            <!--    <div class="d-sm-flex justify-content-center justify-content-sm-between">-->
            <!--        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2021. Premium <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from BootstrapDash. All rights reserved.</span>-->
            <!--        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="ti-heart text-danger ml-1"></i></span>-->
            <!--    </div>-->
            <!--</footer>-->
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