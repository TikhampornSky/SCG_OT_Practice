<?php
    include("dbconnect.php");
    session_start();
    $user_id = $_SESSION["user_id"];
    // echo $user_id;
    
    // employee id
    $user_name = $_GET['user_id'];
    session_start();

    
    function alertToApprover($number,$hour){
        include("dbconnect.php");
        require_once("test.php"); //convert date to decimal
        session_start();
        $user_id = $_SESSION["user_id"];
        $sql = mysqli_query($con, "SELECT a.employee_name, a.employee_lastname, b.line_token FROM employeeInfo a , approver b WHERE a.employee_id = '$user_id' AND a.approver_id = b.approver_id");
        $rs = $sql->fetch_object();
        $balance = decimalToHours(36-$hour);
        $hour = decimalToHours($hour);
        if ($number<36){
            $msg = 'แจ้งเตือน'."\n".'ขณะนี้ OT ของคุณ '.$rs->employee_name." ".$rs->employee_lastname."\n".' '.$hour.' ชม / สัปดาห์แล้ว'."\n".'เหลืออีก '.$balance.' ชม. จะครบจำนวนตามที่กฎหมายกำหนด';
        }else{
            $msg = 'แจ้งเตือน'."\n".'ขณะนี้ OT ของคุณ '.$rs->employee_name." ".$rs->employee_lastname."\n".'ครบ 36 ชม. ตามที่กฎหมายกำหนด'."\n".'ซึ่งในสัปดาห์นี้ ขอรวมไป'.$hour."\n".'กรณีต้องการขออนุมัติ OT ที่เกิน 36 ชม.ให้พนักงาน โปรดขอความเห็นชอบระดับ ผจส. และส่งต่อเพื่อขออนุมัติไปยัง MD/ผร.ต่อไป โดยพนักงานสามารถเข้าไปดำเนินการที่ https://bit.ly/3LiJndC';
        }

        if($sql){

            $token = $rs->line_token;
            echo $token;
            
            $url        = 'https://notify-api.line.me/api/notify';
            $token      = $token;
            $headers    = [
                            'Content-Type: application/x-www-form-urlencoded',
                            'Authorization: Bearer '.$token
                        ];
            $message = $msg;



            $fields     = 'message='.$message;

            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $url);
            curl_setopt( $ch, CURLOPT_POST, 1);
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec( $ch );
            curl_close( $ch );
            
            var_dump($result);
            $result = json_decode($result,TRUE);

        }
    }
    function alertToEmployee($number,$hour){
        include("dbconnect.php");
        require_once("test.php"); //convert date to decimal
        session_start();
        $user_id = $_SESSION["user_id"];
        $sql = mysqli_query($con, "SELECT * FROM employee a WHERE a.employee_id = '$user_id'");
        $rs = $sql->fetch_object();
        $balance = decimalToHours(36-$hour);
        $hour = decimalToHours($hour);
        if ($number<36){
            $msg = 'แจ้งเตือน'."\n".'ขณะนี้ OT ของท่าน  '.$hour.' ชม / สัปดาห์แล้ว'."\n".'เหลืออีก '.$balance.' ชม. จะครบจำนวนตามที่กฎหมายกำหนด';

        }else{
            $msg = 'แจ้งเตือน'."\n".'ขณะนี้ OT ของท่าน ครบ 36 ชม. ตามที่กฎหมายกำหนด'."\n".'ซึ่ง OT ที่ท่านขอรวมไป '.$hour.' ชม.'."\n".'กรณีต้องการขออนุมัติ OT ส่วนที่เกิน 36 ชม. ขอให้หารือผู้บังคับบัญชา เพื่อขอความเห็นชอบระดับ ผจส. และส่งต่อเพื่อขออนุมัติไปยัง MD/ผร.ต่อไป  โดยท่านสามารถเข้าไปดำเนินการที่ https://bit.ly/3LiJndC';
        }

        if($sql){

            $token = $rs->line_token;
            echo $token;
            
            $url        = 'https://notify-api.line.me/api/notify';
            $token      = $token;
            $headers    = [
                            'Content-Type: application/x-www-form-urlencoded',
                            'Authorization: Bearer '.$token
                        ];
            $message = $msg;



            $fields     = 'message='.$message;

            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $url);
            curl_setopt( $ch, CURLOPT_POST, 1);
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec( $ch );
            curl_close( $ch );
            
            var_dump($result);
            $result = json_decode($result,TRUE);

        }
    }

    function request($date){
        include("dbconnect.php");
        session_start();
        $user_id = $_SESSION["user_id"];
        $sql = mysqli_query($con, "SELECT a.employee_name, a.employee_lastname, b.line_token FROM employeeInfo a , approver b WHERE a.employee_id = '$user_id' AND a.approver_id = b.approver_id");
        $rs = $sql->fetch_object();

        if($sql){

            $token = $rs->line_token;
            echo $token;
            
            $url        = 'https://notify-api.line.me/api/notify';
            $token      = $token;
            $headers    = [
                            'Content-Type: application/x-www-form-urlencoded',
                            'Authorization: Bearer '.$token
                        ];
            $message = 'โปรดอนุมัติ'."\n".'เพื่อพิจารณาการขออนุมัติ OT'."\n".'ของคุณ'.$rs->employee_name." ".$rs->employee_lastname."\n".'เมื่อวันที่ '.$date."\n".'ดูข้อมูลเพิ่มเติม'."\n".'https://liff.line.me/1656632478-5e2DRPWq';



            $fields     = 'message='.$message;

            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $url);
            curl_setopt( $ch, CURLOPT_POST, 1);
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec( $ch );
            curl_close( $ch );
            
            var_dump($result);
            $result = json_decode($result,TRUE);
            echo "gocheck";
            // include "thisWeek.php";
            // date_default_timezone_set('Asia/Bangkok');
            // $time_stamp = date("Y-m-d H:i:s");
            // $time = new week();
            // $time->set_day($time_stamp);
            // $start_monday =  $time->get_start();
            // $end_monday = $time->get_end();
            // check($start_monday,$end_monday);
            // check('2022-01-10','2022-01-17');

        }
    }
    function check($start_monday,$end_monday){
        include("dbconnect.php"); //connect to database
        require_once("test.php");
        echo "check";
        $user_id = $_SESSION["user_id"];
        $type = $_SESSION["user_type"];
        echo $type;
        if ($type == "normal"){
            $type = "(พนักงานปกติ)";
            $in = '07:30';
            $out = '07:30';
        }else {
            $type = "(พนักงานกะ)";
            $in = '08:00';
            $out = '08:00';
        }
        $week = mysqli_query($con, "SELECT SUM(hour_range) AS approve FROM transaction WHERE user_id='$user_id' AND time_start BETWEEN '$start_monday $in' AND '$end_monday $out' AND approve_status = 'approve'");
        $week2 = mysqli_query($con, "SELECT SUM(hour_range) AS waiting FROM transaction WHERE user_id ='$user_id' AND time_start BETWEEN '$start_monday $in' AND '$end_monday $out' AND approve_status = 'waiting'");
        $week3 = mysqli_query($con, "SELECT SUM(hour_range) AS edit FROM transaction WHERE user_id ='$user_id' AND time_start BETWEEN '$start_monday $in' AND '$end_monday $out' AND approve_status = 'edit'");
        $rsweek = $week->fetch_object();
        $rsweek2 = $week2->fetch_object();
        $rsweek3 = $week3->fetch_object();
        $approve = $rsweek->approve;
        $waiting = $rsweek2->waiting;
        $edit = $rsweek3->edit;
        echo "SELECT SUM(hour_range) AS approve FROM transaction WHERE user_id='$user_id' AND time_start BETWEEN '$start_monday $in' AND '$end_monday $out' AND approve_status = 'approve'";
        
        if ($approve == "") {$approve = 0;}
        if ($waiting == "") {$waiting = 0;}
        if ($edit == "") {$edit = 0;}
        $total = $approve+$waiting+$edit;
        // $total = 35;
        echo $total;
        if ($total>=30 && $total<36){
            alertToApprover(30,$total);
            alertToEmployee(30,$total);
        }else if($total>=36){
            alertToApprover(36,$total);
            alertToEmployee(36,$total);
        }
        echo "done";
    }
    
    function checkcheck($start_monday,$end_monday,$user_id){
        include("dbconnect.php"); //connect to database
        require_once("test.php");
        echo "checkฯ";
        $in = '00:00';
        $out = '08:01';
        
        $week = mysqli_query($con, "SELECT SUM(hour_range) AS approve FROM transaction WHERE user_id='$user_id' AND time_start BETWEEN '$start_monday $in' AND '$end_monday $out' AND approve_status = 'approve'");
        $week2 = mysqli_query($con, "SELECT SUM(hour_range) AS waiting FROM transaction WHERE user_id ='$user_id' AND time_start BETWEEN '$start_monday $in' AND '$end_monday $out' AND approve_status = 'waiting'");
        $week3 = mysqli_query($con, "SELECT SUM(hour_range) AS edit FROM transaction WHERE user_id ='$user_id' AND time_start BETWEEN '$start_monday $in' AND '$end_monday $out' AND approve_status = 'edit'");
        $rsweek = $week->fetch_object();
        $rsweek2 = $week2->fetch_object();
        $rsweek3 = $week3->fetch_object();
        $approve = $rsweek->approve;
        $waiting = $rsweek2->waiting;
        $edit = $rsweek3->edit;
        echo "SELECT SUM(hour_range) AS approve FROM transaction WHERE user_id='$user_id' AND time_start BETWEEN '$start_monday $in' AND '$end_monday $out' AND approve_status = 'approve'";
        
        if ($approve == "") {$approve = 0;}
        if ($waiting == "") {$waiting = 0;}
        if ($edit == "") {$edit = 0;}
        $total = $approve+$waiting+$edit;
        // $total = 35;
        echo $total;
        if ($total>=30 && $total<36){
            alertToApprover1(30,$total,$user_id);
            alertToEmployee1(30,$total,$user_id);
        }else if($total>=36){
            alertToApprover1(36,$total,$user_id);
            alertToEmployee1(36,$total,$user_id);
        }
        echo "done";
    }
    
    function alertToApprover1($number,$hour,$user_id){
        include("dbconnect.php");
        require_once("test.php"); //convert date to decimal
        $sql = mysqli_query($con, "SELECT a.employee_name, a.employee_lastname, b.line_token FROM employeeInfo a , approver b WHERE a.employee_id = '$user_id' AND a.approver_id = b.approver_id");
        $rs = $sql->fetch_object();
        $balance = decimalToHours(36-$hour);
        $hour = decimalToHours($hour);
        if ($number<36){
            $msg = 'แจ้งเตือน'."\n".'ขณะนี้ OT ของคุณ '.$rs->employee_name." ".$rs->employee_lastname."\n".' '.$hour.' ชม / สัปดาห์แล้ว'."\n".'เหลืออีก '.$balance.' ชม. จะครบจำนวนตามที่กฎหมายกำหนด';
        }else{
            $msg = 'แจ้งเตือน'."\n".'ขณะนี้ OT ของคุณ '.$rs->employee_name." ".$rs->employee_lastname."\n".'ครบ 36 ชม. ตามที่กฎหมายกำหนด'."\n".'ซึ่งในสัปดาห์นี้ ขอรวมไป'.$hour."\n".'กรณีต้องการขออนุมัติ OT ที่เกิน 36 ชม.ให้พนักงาน โปรดขอความเห็นชอบระดับ ผจส. และส่งต่อเพื่อขออนุมัติไปยัง MD/ผร.ต่อไป  โดยพนักงานสามารถเข้าไปดำเนินการที่ https://bit.ly/3LiJndC';
        }

        if($sql){

            $token = $rs->line_token;
            echo $token;
            
            $url        = 'https://notify-api.line.me/api/notify';
            $token      = $token;
            $headers    = [
                            'Content-Type: application/x-www-form-urlencoded',
                            'Authorization: Bearer '.$token
                        ];
            $message = $msg;



            $fields     = 'message='.$message;

            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $url);
            curl_setopt( $ch, CURLOPT_POST, 1);
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec( $ch );
            curl_close( $ch );
            
            var_dump($result);
            $result = json_decode($result,TRUE);

        }
    }
    function alertToEmployee1($number,$hour,$user_id){
        include("dbconnect.php");
        require_once("test.php"); //convert date to decimal
        $sql = mysqli_query($con, "SELECT * FROM employee a WHERE a.employee_id = '$user_id'");
        $rs = $sql->fetch_object();
        $balance = decimalToHours(36-$hour);
        $hour = decimalToHours($hour);
        if ($number<36){
            $msg = 'แจ้งเตือน'."\n".'ขณะนี้ OT ของท่าน  '.$hour.' ชม / สัปดาห์แล้ว'."\n".'เหลืออีก '.$balance.' ชม. จะครบจำนวนตามที่กฎหมายกำหนด';

        }else{
            $msg = 'แจ้งเตือน'."\n".'ขณะนี้ OT ของท่าน ครบ 36 ชม. ตามที่กฎหมายกำหนด'."\n".'ซึ่ง OT ที่ท่านขอรวมไป '.$hour.' ชม.'."\n".'กรณีต้องการขออนุมัติ OT ส่วนที่เกิน 36 ชม. ขอให้หารือผู้บังคับบัญชา เพื่อขอความเห็นชอบระดับ ผจส. และส่งต่อเพื่อขออนุมัติไปยัง MD/ผร.ต่อไป  โดยท่านสามารถเข้าไปดำเนินการที่ https://bit.ly/3LiJndC';
        }

        if($sql){

            $token = $rs->line_token;
            echo $token;
            
            $url        = 'https://notify-api.line.me/api/notify';
            $token      = $token;
            $headers    = [
                            'Content-Type: application/x-www-form-urlencoded',
                            'Authorization: Bearer '.$token
                        ];
            $message = $msg;



            $fields     = 'message='.$message;

            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $url);
            curl_setopt( $ch, CURLOPT_POST, 1);
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec( $ch );
            curl_close( $ch );
            
            var_dump($result);
            $result = json_decode($result,TRUE);

        }
    }
    
    function approved($employee_id,$date,$status){
        echo "in notify";
        include("dbconnect.php");
        session_start();
        $user_id = $_SESSION["user_id"];
        
        $sql = mysqli_query($con, "SELECT a.employee_name, a.employee_lastname, b.line_token FROM employeeInfo a, approver b WHERE a.approver_id = b.approver_id AND a.employee_id = '$employee_id'");
        $rs = $sql->fetch_object();
        if($sql){
            echo "infi";
            
            $token = $rs->line_token;
            echo $token;
            
            $url        = 'https://notify-api.line.me/api/notify';
            $token      = $token;
            $headers    = [
                            'Content-Type: application/x-www-form-urlencoded',
                            'Authorization: Bearer '.$token
                        ];
            $message = 'ได้'.$status.'แล้ว'."\n".'ท่านได้'.$status.' OT'."\n".'ของคุณ'.$rs->employee_name." ".$rs->employee_lastname."\n".'เมื่อวันที่ '.$date."\n".'ดูข้อมูลเพิ่มเติม'."\n".'https://liff.line.me/1656632478-pRlO1WKj';


            $fields     = 'message='.$message;

            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $url);
            curl_setopt( $ch, CURLOPT_POST, 1);
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec( $ch );
            curl_close( $ch );
            
            var_dump($result);
            $result = json_decode($result,TRUE);
        }
    }
    function approve($transaction_id){
        include("dbconnect.php");
        echo "in notify";
        $sql = mysqli_query($con, "SELECT * FROM transaction a , employeeInfo b, employee c WHERE transaction_id = '$transaction_id' AND a.user_id = b.employee_id AND b.employee_id = c.employee_id");
        if($sql){
            $rs = $sql->fetch_object();
            $token = $rs->line_token;
            // $department_id = $rs->department_id;
            // echo $token.$department;
            
            $url        = 'https://notify-api.line.me/api/notify';
            $token      = $token;
            $headers    = [
                            'Content-Type: application/x-www-form-urlencoded',
                            'Authorization: Bearer '.$token
                        ];
            $message = 'อนุมัติแล้ว'."\n".'การขออนุมัติ OT ของคุณ: '.$rs->employee_name." ".$rs->employee_lastname."\n".'เมื่อวันที่ '.$rs-> date."\n".'เวลา '.substr($rs-> time_start,11).' ถึง '.substr($rs-> time_end,11)."\n".'ได้รับการอนุมัติแล้ว'."\n".'ดูข้อมูลเพิ่มเติม'."\n".'https://liff.line.me/1656632478-5pZdAwQ4';

            $fields     = 'message='.$message;

            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $url);
            curl_setopt( $ch, CURLOPT_POST, 1);
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec( $ch );
            curl_close( $ch );
            
            var_dump($result);
            $result = json_decode($result,TRUE);
            echo "finish";
            
            approved($rs->employee_id,$rs->date,"อนุมัติ");
            
            header("Location: complete.php");

        }
    }
    function reject($transaction_id){
        include("dbconnect.php");
        echo "in notify";
        $sql = mysqli_query($con, "SELECT * FROM transaction a , employeeInfo b, employee c WHERE transaction_id = '$transaction_id' AND a.user_id = b.employee_id AND b.employee_id = c.employee_id");
        if($sql){
            $rs = $sql->fetch_object();
            $token = $rs->line_token;
            $department_id = $rs->department_id;
            echo $token.$department;
            
            $url        = 'https://notify-api.line.me/api/notify';
            $token      = $token;
            $headers    = [
                            'Content-Type: application/x-www-form-urlencoded',
                            'Authorization: Bearer '.$token
                        ];
            $message = 'ถูกปฏิเสธ'."\n".'การขออนุมัติ OT ของคุณ: '.$rs->employee_name." ".$rs->employee_lastname."\n".'เมื่อวันที่ '.$rs-> date."\n".'เวลา '.substr($rs-> time_start,11).' ถึง '.substr($rs-> time_end,11)."\n".'ได้รับการปฏิเสธ'."\n".'ดูข้อมูลเพิ่มเติม'."\n".'https://liff.line.me/1656632478-5pZdAwQ4';

            $fields     = 'message='.$message;

            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $url);
            curl_setopt( $ch, CURLOPT_POST, 1);
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec( $ch );
            curl_close( $ch );
            
            var_dump($result);
            $result = json_decode($result,TRUE);
            approved($rs->employee_id,$rs->date,"ปฏิเสธ");
            header("Location: complete.php");

        }
    }
    function edited($employee_id,$date,$status){
        echo "in notify";
        include("dbconnect.php");
        session_start();
        $user_id = $_SESSION["user_id"];
        
        $sql = mysqli_query($con, "SELECT a.employee_name, a.employee_lastname, b.line_token FROM employeeInfo a, approver b WHERE a.approver_id = b.approver_id AND a.employee_id = '$employee_id'");
        $rs = $sql->fetch_object();
        if($sql){
            echo "infi";
            
            $token = $rs->line_token;
            echo $token;
            
            $url        = 'https://notify-api.line.me/api/notify';
            $token      = $token;
            $headers    = [
                            'Content-Type: application/x-www-form-urlencoded',
                            'Authorization: Bearer '.$token
                        ];
            $message = 'ได้'.$status.'แล้ว'."\n".'ท่านได้'.$status.' OT และแก้ไขเวลา'."\n".'ของคุณ'.$rs->employee_name." ".$rs->employee_lastname."\n".'เมื่อวันที่ '.$date."\n".'ดูข้อมูลเพิ่มเติม'."\n".'https://liff.line.me/1656632478-pRlO1WKj';


            $fields     = 'message='.$message;

            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $url);
            curl_setopt( $ch, CURLOPT_POST, 1);
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec( $ch );
            curl_close( $ch );
            
            var_dump($result);
            $result = json_decode($result,TRUE);
        }
    }
    function edit($transaction_id){
        include("dbconnect.php");
        echo "in notify";
        echo "SELECT * FROM transaction a , employeeInfo b , employee c WHERE transaction_id = '$transaction_id' AND a.user_id = b.employee_id AND b.employee_id = c.employee_id";
        $sql = mysqli_query($con, "SELECT * FROM transaction a , employeeInfo b , employee c WHERE transaction_id = '$transaction_id' AND a.user_id = b.employee_id AND b.employee_id = c.employee_id");
        if($sql){
            $rs = $sql->fetch_object();
            $token = $rs->line_token;
            $department_id = $rs->department_id;
            echo $token.$department;
            
            $url        = 'https://notify-api.line.me/api/notify';
            $token      = $token;
            $headers    = [
                            'Content-Type: application/x-www-form-urlencoded',
                            'Authorization: Bearer '.$token
                        ];
            $message = 'อนุมัติแล้ว'."\n".'การขออนุมัติ OT ของคุณ: '.$rs->employee_name." ".$rs->employee_lastname."\n".'เมื่อวันที่ '.$rs-> date."\n".'ได้รับการอนุมัติแล้ว และมีการแก้ไขเวลาจาก'."\n".'เวลา '.($rs->edit_start).' ถึง '.($rs->edit_end)."\n".'เป็นเวลา '.substr($rs->time_start,11).' ถึง '.substr($rs->time_end,11)."\n".'ดูข้อมูลเพิ่มเติม'."\n".'https://liff.line.me/1656632478-5pZdAwQ4';



            $fields     = 'message='.$message;

            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $url);
            curl_setopt( $ch, CURLOPT_POST, 1);
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec( $ch );
            curl_close( $ch );
            
            var_dump($result);
            $result = json_decode($result,TRUE);
            echo "finish";
            
            edited($rs->employee_id,$rs->date,"อนุมัติ");
            
            // header("Location: complete.php");

        }
    }
    
    function cancle($transaction){
        echo "in notify";
        include("dbconnect.php");
        session_start();
        $user_id = $_SESSION["user_id"];
        $sql = mysqli_query($con, "SELECT a.employee_name, a.employee_lastname, b.line_token FROM employeeInfo a , approver b WHERE a.employee_id = '$user_id' AND a.approver_id = b.approver_id");
        $rs = $sql->fetch_object();
        
        $sql1 = mysqli_query($con, "SELECT * FROM transaction WHERE transaction_id = '$transaction'");
        $rs1 = $sql1->fetch_object();
        echo $rs1->date;
        $date = $rs1->date;
        echo "";
        if($sql){
            echo "infi";
            
            $token = $rs->line_token;
            echo $token;
            
            $url        = 'https://notify-api.line.me/api/notify';
            $token      = $token;
            $headers    = [
                            'Content-Type: application/x-www-form-urlencoded',
                            'Authorization: Bearer '.$token
                        ];
            $message = 'มีการยกเลิก'."\n".'คุณ'.$rs->employee_name." ".$rs->employee_lastname."\n".'ได้ทำการยกเลิก OT ที่ท่านได้อนุมัติแล้วเมื่อวันที่: '.$date."\n".'เวลาเริ่ม: '.substr($rs1->time_start,11)."\n".'เวลาสิ้นสุด: '.substr($rs1->time_end,11)."\n".'เหตุผลในการขอ: '.$rs1->request_msg."\n";

            $fields     = 'message='.$message;

            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $url);
            curl_setopt( $ch, CURLOPT_POST, 1);
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec( $ch );
            curl_close( $ch );
            
            var_dump($result);
            $result = json_decode($result,TRUE);
            
            // header("Location: sendMail.php");

        }
    }
    
    function cancleEmp($transaction){
        echo "in notify";
        include("dbconnect.php");
        session_start();
        $user_id = $_SESSION["user_id"];
        $sql = mysqli_query($con, "SELECT a.employee_name, a.employee_lastname, b.line_token FROM employeeInfo a , employee b WHERE a.employee_id = '$user_id' AND a.employee_id = b.employee_id");
        $rs = $sql->fetch_object();
        
        $sql1 = mysqli_query($con, "SELECT * FROM transaction WHERE transaction_id = '$transaction'");
        $rs1 = $sql1->fetch_object();
        echo $rs1->date;
        $date = $rs1->date;
        echo "";
        if($sql){
            echo "infi";
            
            $token = $rs->line_token;
            echo $token;
            
            $url        = 'https://notify-api.line.me/api/notify';
            $token      = $token;
            $headers    = [
                            'Content-Type: application/x-www-form-urlencoded',
                            'Authorization: Bearer '.$token
                        ];
            $message = 'มีการยกเลิก'."\n".'คุณ'.$rs->employee_name." ".$rs->employee_lastname."\n".'ได้ทำการยกเลิก OT ที่ท่านได้อนุมัติแล้วเมื่อวันที่: '.$date."\n".'เวลาเริ่ม: '.substr($rs1->time_start,11)."\n".'เวลาสิ้นสุด: '.substr($rs1->time_end,11)."\n".'เหตุผลในการขอ: '.$rs1->request_msg."\n";

            $fields     = 'message='.$message;

            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $url);
            curl_setopt( $ch, CURLOPT_POST, 1);
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec( $ch );
            curl_close( $ch );
            
            var_dump($result);
            $result = json_decode($result,TRUE);
            
            // header("Location: sendMail.php");

        }
    }
