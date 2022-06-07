<?php 
include("dbconnect.php");

session_start();
$user_id = $_SESSION["user_id"];
$transaction = $_GET['transaction'];
// $employee_id = $_GET['id'];
// $status = $_GET['status'];
// $date = $_GET['date'];

$sql = mysqli_query($con, "SELECT * FROM transaction a , employeeInfo b  WHERE transaction_id = '$transaction' AND b.employee_id = a.user_id");
$rs = $sql->fetch_object();


$mail = $rs->employee_email;
$name = $rs->employee_name;
$title = $rs->approve_status;
$date = $rs->date;
$status = "";
if ($title == "approve"){
    $status = "ได้รับการอนุมัติ";
}else if ($title == "reject"){
    $status = "ได้ถูกปฏิเสธ";
}else if ($title == "edit"){
    $status = "ได้รับการอนุมัติและมีการแก้ไขเวลา";
}



$mailto = "$mail";
echo $mailto+"-";
$mailSub = "OT $status แล้ว";
$mailMsg = "
  การขออนุมัติ OT ของคุณ".$name."\n\r เมื่อวันที่ ".$date." ได้".$status."แล้ว สามารถดูรายละเอียดเพิ่มเติมได้ที่ลิ้งด้านล้าง  \n\r https://liff.line.me/1656632478-5pZdAwQ4";
  
require '../PHPMailer/PHPMailerAutoload.php';
$mail = new PHPMailer();
$mail->IsSmtp();
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'tls';
$mail->Host = "smtp.gmail.com";
$mail->Port = 587; // or 587
$mail->IsHTML(true);
$mail->CharSet="utf-8";
$mail->ContentType="text/html";
$mail->Username = "stsotrequest@gmail.com"; //username gmail accound stsotrequest@gmail.com
$mail->Password = "stslover00-"; //password gmail accound stslover00-
$mail->SetFrom("stsotrequest@gmail.com", "STS OT Request");
// $mail->AddReplyTo("yourmail@gmail.com", "Company name");
$mail->Subject = $mailSub;
$mail ->Body = $mailMsg;
$mail ->AddAddress($mailto);
 
if(!$mail->Send()){
    //echo $mailto;
  echo "Mail Not Sent";
}
else{
  echo "Mail Sent";
  header("Location: manager/complete.php");
}

?>