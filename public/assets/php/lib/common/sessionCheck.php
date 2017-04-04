<?php
if(count($_SESSION) === 0)
{
    echo "<h1><b>エラー:ログインされていません</h1>\n".
         "<p>セッションが開始されませんでした。再度ログインを行ってください。</p>\n";
    echo "<a href=\"{$URL}/login\">ログイン画面に戻る</a>\n";
    exit();
}

function isAdministrator()
{
    if($_SESSION['USERID'] !== -1)
    {
        echo "<h1><b>エラー:権限がありません</h1>\n".
             "<p>管理者以外アクセスすることはできません。再度ログインを行ってください。</p>\n";
        echo "<a href=\"{$URL}/login\">ログイン画面に戻る</a>\n";
        exit();
    }
}
?>