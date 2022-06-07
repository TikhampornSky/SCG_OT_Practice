<?php
include("../dbconnect.php"); //connect to database
require_once("../test.php"); //convert date to decimal
include("../notify.php"); //send notify

session_start(); //calling session
$user_id = $_SESSION["user_id"]; //employee id
$transaction_id = $_GET['transaction'];

//to get employee name
$sql = mysqli_query($con, "SELECT * FROM employee a , employeeInfo b  WHERE a.employee_id = '$user_id' AND a.employee_id = b.employee_id");
$rs = $sql->fetch_object();
$name = $rs->employee_name;
$lastname = $rs->employee_lastname;
//done

//get transaction detail
$sql1 = mysqli_query($con, "SELECT * FROM transaction WHERE transaction_id = '$transaction_id'");
$rs1 = $sql1->fetch_object();
$title = "";
$class = "";
if ($rs1->approve_status == 'waiting') {
  $title = "รออนุมัติ";
  $class = "form-control bg-inverse-warning";
} else if ($rs1->approve_status == 'approve') {
  $title = "อนุมัติแล้ว";
  $class = "form-control bg-inverse-success";
} else if ($rs1->approve_status == 'reject') {
  $title = "ถูกปฏิเสธ";
  $class = "form-control bg-inverse-danger";
}
if($rs1->ot_type == 'lunch'){
    $ot_desc="(OT พักเที่ยง)";
    $ot_type="lunch";
}else if($rs1->ot_type == 'normal'){
    $ot_desc="(OT ปกติ)";
    $ot_type="normal";
}else if($rs1->ot_type == 'round'){
    $ot_desc="(OT กะ)";
    $ot_type="round";
}
//done

//set select default
$n1 = "";
$n2 = "";
$n3 = "";
$n4 = "";
$n5 = "";
$n6 = "";
$n7 = "";
$n8 = "";

if ($rs1->request_msg == 'งานต่อเนื่อง'){
    $n1 = "selected";
}else if ($rs1->request_msg == 'Project'){
    $n2 = "selected";
}else if ($rs1->request_msg == 'งานเร่งด่วน'){
    $n3 = "selected";
}else if ($rs1->request_msg == 'งาน PM'){
    $n4 = "selected";
}else if ($rs1->request_msg == 'TPM/AM'){
    $n5 = "selected";
}else if ($rs1->request_msg == 'ปฏิบัติงานแทนเพื่อนร่วมงาน'){
    $n6 = "selected";
}else if ($rs1->request_msg == 'OT ช่วงพัก/พักเที่ยง'){
    $n8 = "selected";
}else if ($rs1->request_msg == 'วันหยุดประเพณี'){
    $n9 = "selected";
}else {
    $n7 = "selected";
}
//done

$number = 0;
$date = $_POST['date'];
$time_start = $_POST['time_start'];
$time_end =$_POST['time_end'];
$msg = $_POST['msg'];
// echo $number;
echo $date;
echo $time_start;
echo $time_end;
$result=0;
$rows=0;

if (isset($_POST["save"])) {
    $sql2 = mysqli_query($con, "SELECT transaction_id,time_start,time_end,(time_start<time_end) as YN FROM transaction WHERE user_id = '$rs->employee_id' AND transaction_id != '$transaction_id' AND date = '$date' AND approve_status not in('cancle','reject')");
        while($row = mysqli_fetch_row($sql2)){
            //printf('%s (%s)\n',$row[0],$row[1]);
            $YN = $row[3];
            echo "<br><br>".$row[3];
        }    
    
    if (mysqli_affected_rows($con) >= '1') {
        
        $x = decimalHours($time_start); //วันเวลาเริ่มใหม่
        $y = decimalHours($time_end); //วันเวลาสิ้นสุดใหม่
        //$xx =decimalHours($rs2->time_start) ; //วันเวลาเริ่มเก่า
        //$yy =decimalHours($rs2->time_end) ; //วันเวลาสิ้นสุดเก่า
        //อาจจะแยก query str ออกเป็น 2 เคส
        if($y<$x){//กรณีที่ เวลาสิ้นสุด น้อยกว่าเวลาเริ่มต้น เคสข้ามคืน
            $date1 = str_replace('-', '/', $date);
            $tomorrow = date('Y-m-d',strtotime($date1 . "+1 days"));
            $testcase=$tomorrow."เคสข้ามคืน";
            if($yy<$xx){//กรณีของเก่าข้ามวัน บวกเพิ่มทั้งเก่าทั้งใหม่
                $strSQL="SELECT * FROM transaction WHERE user_id = '$rs->employee_id' AND transaction_id != '$transaction_id' AND approve_status not in('cancle','reject') AND date='$date' AND not(('$date $time_start'<time_start AND '$tomorrow $time_end'<=time_start) or ('$date $time_start'>=CONCAT(STR_TO_DATE(CONVERT(time_end,DATE)+1,'%Y%m%d'),' ',CONVERT(time_end,TIME)) AND '$tomorrow $time_end'>CONCAT(STR_TO_DATE(CONVERT(time_end,DATE)+1,'%Y%m%d'),' ',CONVERT(time_end,TIME))))";
                $testcase=$testcase."ทั้งใหม่และเก่า";
                $rows1=1;
            }else{//บวกเพิ่มตัวใหม่
                $strSQL="SELECT * FROM transaction WHERE user_id = '$rs->employee_id' AND transaction_id != '$transaction_id' AND approve_status not in('cancle','reject') AND date='$date' AND not(('$date $time_start'<time_start AND '$tomorrow $time_end'<=time_start) or ('$date $time_start'>=CONCAT(STR_TO_DATE(CONVERT(time_end,DATE),'%Y%m%d'),' ',CONVERT(time_end,TIME)) AND '$tomorrow $time_end'>CONCAT(STR_TO_DATE(CONVERT(time_end,DATE),'%Y%m%d'),' ',CONVERT(time_end,TIME))))";
                $testcase=$testcase."เฉพาะของใหม่";
                $rows2=1;
            }
        }else{//กรณีที่ เวลาสิ้นสุด มากว่าเวลาเริ่มต้น เคสปกติ
            if($yy<$xx){//กรณีของเก่าข้ามวัน
                $strSQL="SELECT * FROM transaction WHERE user_id = '$rs->employee_id' AND transaction_id != '$transaction_id' AND approve_status not in('cancle','reject') AND date='$date' AND not(('$date $time_start'<time_start AND '$date $time_end'<=time_start) or ('$date $time_start'>=CONCAT(STR_TO_DATE(CONVERT(time_end,DATE)+1,'%Y%m%d'),' ',CONVERT(time_end,TIME)) AND '$date $time_end'>CONCAT(STR_TO_DATE(CONVERT(time_end,DATE)+1,'%Y%m%d'),' ',CONVERT(time_end,TIME))))";
                $testcase=$testcase."เฉพาะของเก่า";
                $rows3=1;
                $checkSTime = mysqli_query($con, $strSQL);
                if (mysqli_affected_rows($con) >= '1') {
                    $result=$result+1;
                }else{
                    $result=$result+0;
                }
             
            }else{
                $strSQL="SELECT * FROM transaction WHERE user_id = '$rs->employee_id' AND transaction_id != '$transaction_id' AND approve_status not in('cancle','reject') AND date='$date' AND not(('$date $time_start'<time_start AND '$date $time_end'<=time_start) or ('$date $time_start'>=time_end AND '$date $time_end'>time_end))";
                $testcase=$testcase."ไม่ข้ามคืนทั้งคู่";
                
                $checkSTime = mysqli_query($con, $strSQL);
                if (mysqli_affected_rows($con) >= '1') {
                    $result=$result+1;//แก้ไขไม่ได้
                    $rows4=1;
                }else{
                    $result=$result+0;//ต้องแก้ไขได้
                    $rows4=1;
                }
            }

             //$testcase="เคสปกติ";
        }

        
        // $checkSTime = mysqli_query($con, $strSQL);
        // if (mysqli_affected_rows($con) >= '1') {
        //     echo "same time";
        //     $number = 1; //หากมีรายการเดิมอยู่แล้ว
        //     $testcase=$testcase." แก้ไขไม่ได้";
        // } else {
        //     //echo "only same date";
        //     header("Location: edit.php?transaction=$transaction_id&date=$date&time_start=$time_start&time_end=$time_end&msg=$msg");
        //     $testcase=$testcase." แก้ไขได้";
        // }
    } else {
       // echo "same date";
//            header("Location: edit.php?transaction=$transaction_id&date=$date&time_start=$time_start&time_end=$time_end&msg=$msg");
    
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>แก้ไขข้อมูล</title>
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
      function yesnoCheck(that) {
        if (that.value == "other") {
          document.getElementById("ifYes").style.display = "block";
        } else {
          document.getElementById("ifYes").style.display = "none";
        }
      }
    </script>

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
            <div class="row">
              <div class="col-md-12  stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="card-body  text-dark " style="text-align: center; font-size: 150%;">แก้ไขข้อมูล<?=$ot_desc?></div>
   <?=$YN?><br>
   yy=<?=$yy?>xx=<?=$xx?><br>
   row1=<?=$rows1?><br> row2=<?=$rows2?><br> row3=<?=$rows3?><br> row4=<?=$rows4?><br>
   <?="(".$result."/".$rows.")".$testcase.$strSQL?>
                    <div class="row">
                      <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                          <form method="POST">
                            <div class="card-body ">
                                <input type="hidden" value="<?=$transaction_id;?>">
                              <div class="form-group">
                                <label for="exampleInputPassword4">วันที่ขอ OT: </label><br>
                                <label style="color: blue;"><?php echo DateThai($rs1->date); ?></label>
                                <input type="date" name="date" id=e value="<?= (($rs1->date)); ?>" class="form-control" style="-webkit-appearance: none; -moz-appearance: none;">
                    
                              </div>
                              
                              <div class="row">
                                <div class="col-6 form-group">
                                  <label for="exampleInputPassword4">เวลาเริ่ม</label><br>
                                  <label style="color: blue;"><?= substr($rs1->time_start,11,5); ?></label>
                                  <div class="form-group row">
                                    <div class="col-12">
                                      <input type="hidden" id="ot_type" name='ot_type' value="<?=$ot_type?>" />
                                      <input type="time" id="Text1" oninput="add_number()" name="time_start" value="<?= substr(($rs1->time_start),11,5); ?>" class="form-control" style="-webkit-appearance: none; -moz-appearance: none;" />
                                    </div>
                                  </div>
                                  <p id="demo1" style="color: blue; text-align:center;"></p>
                                  <?php
                                    if ($number > 0) {
                                        echo '<script>',
                                        'document.getElementById("demo1").innerHTML = "ช่วงเวลานี้คุณได้มีการขอแล้ว กรุณาเลือกช่วงเวลาใหม่";',
                                        '</script>';
                                    }
                                  ?>

                                </div>

                                <div class="col-6 form-group">
                                  <label for="exampleInputPassword4">เวลาสิ้นสุด</label><br>
                                  <label style="color: blue;"><?= substr($rs1->time_end,11,5); ?></label>
                                  <div class="form-group row">
                                    <div class="col-12">
                                      <input type="time" id="Text2" oninput="add_number()" name="time_end" value="<?= substr(($rs1->time_end),11,5); ?>" class="form-control" style="-webkit-appearance: none; -moz-appearance: none;" />
                                    </div>
                                  </div>
                                </div>

                              </div>


                              <script>
                                
                                var ot_type = document.getElementById("ot_type").value;

                                function add_number() {
                                  var text1 = document.getElementById("Text1");
                                  var text2 = document.getElementById("Text2");
                                  var bla ="";
                                  document.getElementById("txtresult9").value = "";
                                  var t1 = text1.value;
                                  var hours = t1.split(":")[0];
                                  var minutes = t1.split(":")[1];
                                  

                                  var t2 = text2.value;
                                  var hours2 = t2.split(":")[0];
                                  if(hours2<hours){
									  var hours2 = 24+parseInt(hours2);
								  }else{
									   var hours2 = hours2;
								  }
                                  var minutes2 = t2.split(":")[1];
                                  // $t1 = decimalHours('12:01');
                                    // $t2 = decimalHours('13:00');
                                    // $x = decimalHours($time_start);
                                    // $y = decimalHours($time_end);
                                    //(parseFloat(t2) >= parseFloat('12:01') && parseFloat(t2) < parseFloat('13:00') && parseFloat(t1) >= parseFloat('12:01') && parseFloat(t1) < parseFloat('13:00'))
                                    if (ot_type == "normal"){
                                        if(parseFloat(hours+"."+minutes) >= parseFloat("12.00") && parseFloat(hours2+"."+minutes2) <= parseFloat("13.00")){
                                            hours  ="12";
                                            minutes ="00";
                                            hours2  ="12";
                                            minutes2="00";
                                            bla = "ตรงเที่ยง";
                                            document.getElementById("txtresult9").value = parseFloat(hours+"."+minutes) +">="+ parseFloat("12.00")+"&&"+parseFloat(hours2+"."+minutes2)+"<="+parseFloat("13.00");
                                            alert("ช่วงเวลาที่คุณระบุเป็นเวลาพักเที่ยง กรุณาตรวจสอบ");
                                        }else if(parseFloat(hours+"."+minutes) < parseFloat("12.00") && parseFloat(hours2+"."+minutes2) > parseFloat("13.00")){
                                            bla = "ไม่ตรงเที่ยง";
                                            document.getElementById("txtresult9").value = bla;
                                        }
                                        else if (parseFloat(hours2+"."+minutes2) >= parseFloat("12.00") && parseFloat(hours2+"."+minutes2) <= parseFloat("13.00")) { //กรณีเวลาสิ้นสุด >=12:01 และ <13:00
                                            bla = "เลิกเที่ยง";
                                            document.getElementById("txtresult9").value = bla;
                                            hours2  ="12";//ให้นับถึง 12:00 เป็นต้นไป
                                            minutes2="00";//ให้ตัดนาทีที่เกินออกไป นับจนถึง 12:00
                                        }else if (parseFloat(hours+"."+minutes) >= parseFloat("12.00") && parseFloat(hours+"."+minutes) < parseFloat("13.00")){//กรณีเวลาเริ่มต้น >=12:01 และ <13:00
                                            bla = "เริ่มเที่ยง";
                                            document.getElementById("txtresult9").value = bla;
                                            hours  ="13";//ให้เริ่มนับจาก 13:00 เป้นต้นไป
                                            minutes ="00";
                                        }
                                    }else if (ot_type == "lunch"){
                                        var mm = "เที่ยง";
                                        if(parseFloat(hours+"."+minutes) <= parseFloat("12.00") && parseFloat(hours2+"."+minutes2) >= parseFloat("13.00")){
                                            hours  ="12";
                                            minutes ="00";
                                            hours2  ="13";
                                            minutes2="00";
                                            bla = "ให้แค่ 1 ชม. นะ";
                                        }else if(parseFloat(hours+"."+minutes) >= parseFloat("13.00") || parseFloat(hours2+"."+minutes2) <= parseFloat("12.00")){
                                            hours  ="12";
                                            minutes ="00";
                                            hours2  ="12";
                                            minutes2="00";
                                            bla = "ให้แค่ 1 ชม. นะ";
                                        }else if(parseFloat(hours+"."+minutes) <= parseFloat("12.00")){
                                            hours  ="12";
                                            minutes ="00";
                                            bla = "ให้แค่เที่ยง";
                                        }else if(parseFloat(hours2+"."+minutes2) >= parseFloat("13.00")){
                                            hours2  ="13";
                                            minutes2="00";
                                            bla = "ให้แค่บ่ายโมง";
                                        }
                                        document.getElementById("txtresult9").value = bla;
                                    }
                                    
                                  var displayTime = hours + "." + minutes;
                                  var first_number = parseFloat(displayTime); //txt to float
                                  if (isNaN(first_number)) first_number = 0;
                                  var displayTime2 = hours2 + "." + minutes2;
                                  var second_number = parseFloat(displayTime2);
                                  if (isNaN(second_number)) second_number = 0;
                                  
                                    
                                    
                                  var result = (second_number - first_number).toFixed(2); 
                                  
                                  var Hrs = parseInt(result); 
                                  var Mnt = result.toString(2);  
                                  var Mnt_n = Mnt.split(".")[1];
                                  var Mnt_nn = parseInt(Mnt_n);
                                
                                
                                if (ot_type == "normal"){
                                    if(parseFloat(hours2+"."+minutes2) >= parseFloat("12.00") && parseFloat(hours2+"."+minutes2) <= parseFloat("13.00")){//เลิกเที่ยง
                                        if(Mnt_nn==70){
                                            Mnt_nn =100-parseInt(Mnt_nn);
                                        }else if(Mnt_nn==00){
                                            Mnt_nn=0;
                                        }else if(Mnt_nn<=99){
                                            Mnt_nn = parseInt(Mnt_nn)-40;
                                        }
                                    }
                                    if(parseFloat(hours+"."+minutes) >= parseFloat("13.00") && parseFloat(hours2+"."+minutes2) >= parseFloat("13.01")){//เข้าและออกหลังเที่ยง
                                        if(Mnt_nn==70){
                                            Mnt_nn =100-parseInt(Mnt_nn);
                                        }else if(Mnt_nn==00){
                                            Mnt_nn=0;
                                        }else if(Mnt_nn<60){
                                            Mnt_nn =parseInt(Mnt_nn);
                                        }else if(Mnt_nn<=99){
                                            Mnt_nn =parseInt(Mnt_nn)-40;
                                        }
                                    }
                                    if(parseFloat(hours+"."+minutes) < parseFloat("12.00") && parseFloat(hours2+"."+minutes2) <= parseFloat("12.00")){//เข้าและออกก่อนเที่ยง
                                        if(Mnt_nn==70){
                                            Mnt_nn =100-parseInt(Mnt_nn);
                                        }else if(Mnt_nn==00){
                                            Mnt_nn=0;
                                        }else if(Mnt_nn<60){
                                            Mnt_nn =parseInt(Mnt_nn);
                                        }else if(Mnt_nn<=99){
                                            Mnt_nn =parseInt(Mnt_nn)-40;
                                        }
                                    }
                                       
                                   if(parseFloat(hours+"."+minutes) <= parseFloat("12.00") && parseFloat(hours2+"."+minutes2) >= parseFloat("13.00")){//ไม่ตรงเที่ยง
                                        Hrs=Hrs-1;
                                        if(minutes>minutes2){
                                            Mnt_nn = parseInt(Mnt_n-40);
                                        }
                                    }
                                }else if(ot_type == "lunch"){
                                    if(minutes>minutes2){
                                            Mnt_nn = parseInt(Mnt_n-40);
                                    }
                                }else if(ot_type == "round"){
                                    if(minutes>minutes2){
                                            Mnt_nn = parseInt(Mnt_n-40);
                                    }
                                }
                                
                                 if(Mnt_nn<10){
                                     Mnt_nn = "0"+Mnt_nn;
                                 }
                                 
                                 if(Hrs<10){
                                     Hrs = "0"+Hrs;
                                 }
                                 
                                  document.getElementById("txtresult3").value = Hrs+":"+Mnt_nn;

                                }
                              </script>
                              
                              <div class="row">
                                <div class="col-6 form-group">
                                  <label for="exampleInputPassword4">จำนวนชั่วโมง<input type="hidden" id="txtresult9" value="<?=$minute?>" /></label><br>
                                  <label style="color: blue;"><?= decimalToHours($rs1->hour_range); ?></label>
                                  <div class="form-group row">
                                    <div class="col-12">
                                      <input type="text" id="txtresult3" oninput="add_number()" value="<?= decimalToHours(($rs1->hour_range)); ?>" class="form-control" style="-webkit-appearance: none; -moz-appearance: none;" />
                                    </div>
                                  </div>
                                </div>
                                <div class="col-6 form-group">
                                  <label for="exampleInputPassword4">สถานะ</label><br>
                                  <label style="color: blue;">.</label>
                                  <div class="form-group row">
                                    <div class="col-12">
                                      <label name="date" class="<?= $class; ?>" style="text-align:center;"><?= $title; ?></label>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <div class="form-group">
                                <label for="exampleSelectGender">เหตุผล: </label>
                                <label style="color: blue;"><?= $rs1->request_msg ?></label><br>
                                    <select name="msg" class="form-control" onchange="yesnoCheck(this);" id="exampleSelectGender">
                                  <option value="none" <?=$n7;?> >เลือก</option>
                                  <option value="งานต่อเนื่อง" <?=$n1;?> >งานต่อเนื่อง</option>
                                  <option value="Project" <?=$n2;?> >Project</option>
                                  <option value="วันหยุดประเพณี" <?=$n9;?> >วันหยุดประเพณี</option>
                                  <option value="งานเร่งด่วน" <?=$n3;?> >งานเร่งด่วน</option>
                                  <option value="งาน PM" <?=$n4;?> >งาน PM</option>
                                  <option value="TPM/AM" <?=$n5;?> >TPM/AM</option>
                                  <option value="ปฏิบัติงานแทนเพื่อนร่วมงาน" <?=$n6;?> >ปฏิบัติงานแทนเพื่อนร่วมงาน</option>
                                  <option value="OT ช่วงพัก/พักเที่ยง" <?=$n8?> >OT ช่วงพัก/พักเที่ยง</option>
                                  <option value="other" <??>>อื่น</option>
                                </select><br>
                                <div id="ifYes" style="display: none;">
                                  <label for="reason">เหตุผลอื่นๆ</label>
                                  <input type="text" id="car" name="reason" class="form-control" /><br />
                                </div>
                                
                              </div>
                              
                              <div class="form-group">
                                <button type="submit" name="save" class="btn btn-primary col-12">ยืนยันการแก้ไข</button>
                                <br><br>
                                <input type="reset" value="ล้างข้อมูล" class="btn btn-warning col-12">
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