<?php 

function decimalHours($time)
{
    $hms = explode(":", $time);
    return ($hms[0] + round($hms[1]/60,3));
}

// function decimalToHours($number)
// {
//     $hms = explode(".", $number);
//     $minute = round(($hms[1]/100)*60);
//     // echo $minute."\n";
//     if (strlen($minute)==1){
//         $minute = "0".$minute;
//     };
//     // echo $minute;
//     return ($hms[0] .":".$minute);
// }
function decimalToHours($dec)
{
    // $number = substr($dec,2);
    // if ($number >= 50){
    //     $dec = round($dec,2);
    // }
    
    // start by converting to seconds
    $seconds = ($dec * 3600);
    // we're given hours, so let's get those the easy way
    $hours = floor($dec);
    // since we've "calculated" hours, let's remove them from the seconds variable
    $seconds -= $hours * 3600;
    // calculate minutes left
    $minutes = floor($seconds / 60);
    // remove those from seconds as well
    $seconds -= $minutes * 60;
    if ($seconds >= 50){
        $minutes = $minutes+1;
    }
    // return the time formatted HH:MM:SS
    return lz($hours).":".lz($minutes);
}

// lz = leading zero
function lz($num)
{
    return (strlen($num) < 2) ? "0{$num}" : $num;
}

function dateformat($date){
    $var = $rs->date;
    $datee = str_replace('-', '/', $date);
    $dat=date_create($datee);
    $date_format($dat,"d/m");
}

function DateThai($strDate){
    $strYear = date("Y",strtotime($strDate))+543;
    $strMonth= date("n",strtotime($strDate));
    $strDay= date("j",strtotime($strDate));
    $strHour= date("H",strtotime($strDate));
    $strMinute= date("i",strtotime($strDate));
    $strSeconds= date("s",strtotime($strDate));
    $strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธันวาคม");
    $strMonthThai=$strMonthCut[$strMonth];
    
    return "$strDay $strMonthThai $strYear";
}



?>