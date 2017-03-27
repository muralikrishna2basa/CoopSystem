<?php

function connectMywebDb(){
    $srv  = '192.1.10.136';
    $db   = 'myweb10po';
    $user = 'sa';
    $pass = 'P@ssw0rd';
    $dns  = "sqlsrv:server={$srv};database={$db}";
    try{
        $pdo = new PDO($dns, $user, $pass);
        $e = 'データベース接続に成功しました。';
    }catch(Exception $e){
        $e->Getmessage();
        exit();
    }
    return $pdo;
}

/**
 * [getSingleUser]
 * ユーザーID（orderer）から単一ユーザーの情報を返す関数
 * @param  [number] $userid  [ユーザーID（orderer）]
 * @return [array]  userid   [ユーザーID（orderer）]
 *                  username [ユーザー表示名]
 */
function getSingleUser($userid, $loginid = null)
{
    $array = [];
    $pdo   = connectMywebDb();
    $mySQL = "SELECT * FROM dbo.T_PER_USER WHERE PER_id=?;";
    if(mb_strlen($loginid) > 0)
    {
        $mySQL = "SELECT * FROM dbo.T_PER_USER WHERE PER_usrid=?;";
        $stmt  = $pdo->prepare($mySQL);
        $res   = $stmt->execute([$loginid,]);
    }else{
        $stmt  = $pdo->prepare($mySQL);
        $res   = $stmt->execute([$userid,]);
    }
    if($res === false) throw new Exception("データベース接続時にエラーが発生しました。");

    foreach($stmt as $key => $value)
    {
        $array['userid']   = $value['PER_id'];
        $array['loginid']  = $value['PER_usrid'];
        $array['userName'] = $value['PER_name'];
    }
    if(count($array) === 0) throw new Exception("ユーザーが見つかりませんでした。");
    return $array;
}

/**
 * [getMultiUsers description]
 * ユーザーマスタの全ユーザー情報を返す関数
 * @return [array][cnt]
 *                     [userid]   [ユーザーID（orderer）]
 *                     [loginid]  [ログインID（n-teshima）]
 *                     [username] [ユーザー名（手島尚人）]
 */
function getAllUsers()
{
    $array = [];
    $mySQL = "SELECT * FROM dbo.T_PER_USER;";
    $pdo   = connectMywebDb();
    $stmt  = $pdo->prepare($mySQL);
    $res   = $stmt->execute(null);
    if($res === false) throw new Exception("データベース接続時にエラーが発生しました。");
    $i = 0;
    foreach($stmt as $key => $value)
    {
        $array[$i]['userid']   = $value['PER_id'];
        $array[$i]['loginid']  = $value['PER_usrid'];
        $array[$i]['userName'] = $value['PER_name'];
        $i++;
    }
    if(count($array) === 0) throw new Exception("ユーザーが見つかりませんでした。");
    return $array;

}

/**
 * [authentificateUser description]
 * ログインIDとパスワードの整合性をチェックする関数
 * @param  [string] $loginid  [ログインID]
 * @param  [string] $password [パスワード（md5）]
 * @return [array]
 *                [userid]   [ユーザーID]
 *                [loginid]  [ログインID]
 *                [userName] [ユーザー名]
 */
function authentificateUser($loginid, $password)
{
    $user  = [];
    $msg   = ['loginid'=>'', 'password'=>''];
    $pass  = '';
    $pdo   = connectMywebDb();

    if(mb_strlen($loginid) == 0)
    {
        $msg['loginid'] = 'ログインIDが入力されていません。';
        return $msg;
    }

    if(mb_strlen($password) == 0)
    {
        $msg['password'] = 'パスワードが入力されていません。';
        return $msg;
    }

    try {
        $user = getSingleUser(null, $loginid);
    } catch (Exception $e) {
        $msg['loginid'] = $e->Getmessage();
        return $msg;
    }
    $mySQL = "SELECT PER_pass FROM dbo.T_PER_USER WHERE PER_id=?;";
    $stmt  = $pdo->prepare($mySQL);
    $res   = $stmt->execute([$user['userid'],]);
    if($res === false) throw new Exception("データベース接続時にエラーが発生しました。");
    $pass = $stmt->fetchColumn();

    if (md5($password) !== $pass)
    {
        $msg['password'] = 'パスワードが間違っているようです。';
        return $msg;
    }
    return true;
}

?>