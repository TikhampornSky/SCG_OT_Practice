<?php
define('CLIENT_ID', 'NbIj4ngBk4yM4uyEjCOFTp');
define('LINE_API_URI', 'https://notify-bot.line.me/oauth/authorize?');
define('CALLBACK_URI', 'https://scgot.online/screen/manager/notify/callback.php');

$queryStrings = [
    'response_type' => 'code',
    'client_id' => CLIENT_ID,
    'redirect_uri' => CALLBACK_URI,
    'scope' => 'notify',
    'state' => 'abcdef123456'
];

$queryString = LINE_API_URI . http_build_query($queryStrings);
header("Location: $queryString");
?>