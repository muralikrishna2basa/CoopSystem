<?php
session_start();
require_once('../../public/assets/php/connectDb.php');
require_once('../../public/assets/php/errorMessages.php');
$url = 'http://localhost/sys/sam/CoopSystem';
if(count($_SESSION) === 0)
{
    echo "<h1><b>致命的なエラー:</b>セッションが開始されませんでした。再度ログインを行ってください。</h1>\n";
    echo "<a href=\"{$url}/login\">ログイン画面に戻る</a>\n";
    exit();
}
?>
