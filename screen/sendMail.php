<?php 
include("dbconnect.php");
require_once("test.php"); //convert date to decimal

session_start();
$user_id = $_SESSION["user_id"];

$sql = mysqli_query($con, "SELECT b.line_id, c.approver_email, a.employee_name,a.employee_lastname FROM employeeInfo a, approver b, approverInfo c WHERE a.employee_id = '$user_id' AND a.approver_id = b.approver_id AND b.approver_id = c.approver_id");
$rs = $sql->fetch_object();

$mail = $rs->approver_email;
$name = $rs->employee_name;
$lastname = $rs->employee_lastname;

$hour = $_GET['hour'];
$week =$_GET['week'];
$date =$_GET['date'];
//echo $week;
$balance = decimalToHours(36-$hour);
if ($hour < 30){
    $msg = '
    <html>
    <head>
    <title>HTML email</title>
    </head>
    <body>
    <p>มีการขออนุมัติ OT จากคุณ '.$name.' '.$lastname.' '.เมื่อวันที่.' '.$date.'</p>
    <p>กรุณาพิจารณารายการผ่านทางลิ้งด้านล้าง</p>
    <a href="https://liff.line.me/1656632478-5e2DRPWq">คลิ๊ก</a>
    </body>
    </html>
    ';
}else if ($hour>= 30 && $hour <36 && $week == "thisweek"){
    echo "morethan30";
    $hour = decimalToHours($hour);
    $msg ='
    <html>
    <head>
    <title>HTML email</title>
    </head>
    <body>
    <p>มีการขออนุมัติ OT จากคุณ '.$name.' '.$lastname.' '.เมื่อวันที่.' '.$date.'</p>
    <p style="color: blue;">หมายเหตุ: </p>
    <p>ขณะนี้ OT ของคุณ '.$name.' '.$lastname.' ครบ '.$hour.' ชม. / สัปดาห์แล้ว เหลืออีก '.$balance.' ชม จะครบจำนวนตามที่กฎหมายกำหนด</p>
    <p>กรณีต้องการอนุมัติ ขอให้หารือกับผู้บังคับบัญชาระดับ ผจส. เป็นกรณีไป</p>
    <p>กรุณาพิจารณารายการผ่านทางลิ้งด้านล้าง</p>
    <p>https://liff.line.me/1656632478-5e2DRPWq</p>
    </body>
    </html>
    ';
}else if ($hour>=36 && $week == "thisweek"){
    $hour = decimalToHours($hour);
    $msg = '
    <html>
    <head>
    <title>HTML email</title>
    </head>
    <body>
    <p>มีการขออนุมัติ OT จากคุณ '.$name.' '.$lastname.' '.เมื่อวันที่.' '.$date.'</p>
    <p style="color: blue;">หมายเหตุ: </p>
    <p>ขณะนี้ OT ของคุณ '.$name.' '.$lastname.' ครบ 36 ชม. ตามที่กฎหมายกำหนด</p>
    <p>ซึ่งในสัปดาห์นี้ ขอรวมไป '.$hour.'ชม.</p>
    <p>กรณีต้องการขออนุมัติ OT ที่เกิน 36 ชม.ให้พนักงาน โปรดขอความเห็นชอบระดับ ผจส. และส่งต่อเพื่อขออนุมัติไปยัง MD/ผร.ต่อไป</p>
    <p>กรุณาพิจารณารายการผ่านทางลิ้งด้านล้าง</p>
    <p>https://liff.line.me/1656632478-5e2DRPWq</p>
    </body>
    </html>
    ';
}else{
    $msg = '
    <html>
    <head>
    <title>HTML email</title>
    </head>
    <body>
    <p>มีการขออนุมัติ OT จากคุณ '.$name.' '.$lastname.' '.เมื่อวันที่.' '.$date.'</p>
    <p>กรุณาพิจารณารายการผ่านทางลิ้งด้านล้าง</p>
    <p>https://liff.line.me/1656632478-5e2DRPWq</p>
    </body>
    </html>
    ';
}



$mailto = "$mail";
//echo $mailto;
$mailSub = "มีการขออนุมัติ OT ใหม่";
$mailMsg = $msg;
 
require '../PHPMailer/PHPMailerAutoload.php';
//require_once('../PHPMailer/class.phpmailer.php');

$mail = new PHPMailer();
$mail->IsSmtp();
$mail->SMTPAuth = true;
//$mail->SMTPDebug = true;
$mail->SMTPSecure = 'ssl';
//$mail->SMTPAutoTLS = false;
//$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
//$mail->SMTPDebug = SMTP::DEBUG_SERVER;
//$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Host = 'smtp.gmail.com';
$mail->Port = 465; // 465 or 587
$mail->IsHTML(true);
$mail->CharSet="utf-8";
$mail->ContentType="text/html";
$mail->Username = "stsotrequest@gmail.com"; //username gmail accound stsotrequest@scg.com
$mail->Password = "stslover00-"; //password gmail accound stslover00-
$mail->SetFrom("stsotrequest@gmail.com", "STS OT Request");
// $mail->AddReplyTo("yourmail@gmail.com", "Company name");
$mail->Subject = $mailSub;
$mail ->Body = $mailMsg;
$mail ->AddAddress($mailto);
 
if(!$mail->Send()){
    echo $mailto."<br>";
    echo "Mail Not Sent<br>" . $mail->ErrorInfo;
}
else{
  echo "Mail Sent";
  header("Location: employee/complete.php");
}

?>