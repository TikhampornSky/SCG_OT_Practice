<?php

define('ACCESS_TOKEN', 'P0ku2DjIlLItSPfQhCOtNj0OJpEA7qOLH2YfaC...');
define('LINE_API_URI', 'https://notify-api.line.me/api/notify');

$headers = [
    'Authorization: Bearer ' . ACCESS_TOKEN
];
$fields = [
    'message' => 'Your order #12345 has been delivered'
];

try {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, LINE_API_URI);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, count($fields));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $res = curl_exec($ch);
    curl_close($ch);

    if ($res == false)
        throw new Exception(curl_error($ch), curl_errno($ch));

    $json = json_decode($res);
    $status = $json->status;

    var_dump($status);
} catch (Exception $e) {
    var_dump($e);
}