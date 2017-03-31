<?php
if(count($_SESSION) === 0)
{
    echo "<h1><b>致命的なエラー:</h1>\n".
         "<p>セッションが開始されませんでした。再度ログインを行ってください。</p>\n";
    echo "<a href=\"{$URL}/login\">ログイン画面に戻る</a>\n";
    exit();
}
?>