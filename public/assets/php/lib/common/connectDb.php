<?php
/**
 * [connectDb description]
 * DBとPDO接続をする関数
 * @param  [string] $dbName myweb or coop
 * @return [pdo]    Class Object
 */
function connectDb($dbName){

    if($dbName === 'myweb')
    {
        $srv  = '192.1.10.136';
        $db   = 'myweb10po';
        $user = 'sa';
        $pass = 'P@ssw0rd';
        $dns  = "sqlsrv:server={$srv};database={$db}";
    }
    elseif($dbName === 'test'){
        $srv  = '192.1.10.221';
        $db   = 'test_coopsystemdb';
        $user = 'sqluser';
        $pass = 'mysql';
        $dns  = "mysql:dbname={$db};host={$srv};charset=utf8";
    }
    else{
        $srv  = '192.1.10.221';
        $db   = 'coopsystemdb';
        $user = 'sqluser';
        $pass = 'mysql';
        $dns  = "mysql:dbname={$db};host={$srv};charset=utf8";
    }

    try{
        $pdo = new PDO($dns, $user, $pass);
    }catch(Exception $e){
        echo($e->Getmessage());
        return;
    }

    return $pdo;
}
?>