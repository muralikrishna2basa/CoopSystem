<?php
include     ('./public/assets/php/partial/require_common.php');

require_once($PATH.'/public/assets/php/auth.php');
$msg    = ['loginid'=>'', 'password'=>''];
$errors = [];
//    var_dump($_POST);
if(isset($_POST) && count($_POST) > 0){
    if($_POST['loginid'] === 'coop' && $_POST['password'] === 'coop')
    {
        // 認証成功 管理者ページへ遷移
        echo 'success:goto admin';
        $_SESSION['USERID']    = -1;
        $_SESSION['USER_NAME'] = 'システム管理者';
        header('location: ./admin/month');
        exit();
    }
    try {
        $msg = authentificateUser($_POST['loginid'], $_POST['password']);
    } catch (Exception $e) {
        $errors[] = $e->Getmessage();
    }
    if($msg === true)
    {
        // 認証成功 非管理者ページへ遷移
//        echo "success:goto users";
        try {
            $user       = getSingleUser(0, $_POST['loginid']);

            // 公開されている月別IDを取得する
            $pdo       = connectDb('coop');
            $sql       = "SELECT monthly_id FROM monthly WHERE public_flag=1;";
            $stmt      = $pdo->prepare($sql);
            $res       = $stmt->execute(null);
            $monthlyId = $stmt->fetchColumn();
            if(!$res || !$monthlyId) throw new Exception("monthly_idの取得時にエラーが発生しました。");

            // ユーザーの発注フラグの状態を取得する
            $sql       = "SELECT order_flag FROM ordering WHERE monthly_id=? AND orderer=?;";
            $param     = [$monthlyId, $user['userid'],];
            $stmt      = $pdo->prepare($sql);
            $res       = $stmt->execute($param);
            $orderFlag = intval($stmt->fetchColumn());
            var_dump($sql);
            var_dump($param);
            if(!$res) throw new Exception("order_flag読み込み時にエラーが発生しました。");
            $orderFlag = intval($orderFlag);

            // フラグが0の場合、1に書き換える
            if($orderFlag === 0)
            {
                $sql       = "UPDATE ordering SET order_flag=1 WHERE monthly_id=? AND orderer=?;";
                $param     = [$monthlyId, $user['userid'],];
                $stmt      = $pdo->prepare($sql);
                $res       = $stmt->execute($param);
                if(!$res) throw new Exception("order_flag書き込み時にエラーが発生しました。");
            }
            // セッション変数にユーザー情報を格納する
            $_SESSION['USERID']    = intval($user['userid']);
            $_SESSION['USER_NAME'] = $user['userName'];
            header('location: ./user/order/?page=1');

        } catch (Exception $e) {
            $errors[] = $e->Getmessage();
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <?php include("{$PATH}/public/assets/php/partial/head.php"); ?>

<link rel="stylesheet" type="text/css" href="<?php echo $URL ?>/public/assets/stylesheets/login.css">


<script type="text/javascript">
var date   = new Date();
var month  = date.getMonth() + 1;
var sec    = date.getSeconds();
var bgPath = '<?php echo $URL ?>/public/assets/imgs/bg/';

month = ('0' + month).slice(-2);
bgPath += month+"_";
if(sec % 2 === 0){
    bgPath += '1.jpg';
}else{
    bgPath += '2.jpg';
}
//console.log(bgPath);
$(function(){
    $('#body-bg').css({backgroundImage: 'url('+bgPath+')'});
})
</script>
</head>
<body id="body-bg">
<?php include("{$PATH}/public/assets/php/partial/header.php"); ?>

<div class="container flex" id="login-content">
    <section>
        <h2 class="text-center"><span class="icon"></span>ログイン</h2>




        <form method="post">
            <p id="name" class="form-group <?php if(mb_strlen($msg['loginid']) > 0) echo 'danger' ?>">
                <label>ユーザー名</label>
                <input type="text" name="loginid" placeholder="ユーザー名を入力してください" value="<?php if(isset($_POST['loginid'])) echo $_POST['loginid'] ?>">
                <span class="msg"><?php echo $msg['loginid']; ?></span>
            </p>

            <p id="pass" class="form-group <?php if(mb_strlen($msg['password']) > 0) echo 'danger' ?>">
                <label>パスワード</label>
                <input type="password" name="password" placeholder="パスワードを入力してください">
                <span class="msg"><?php echo $msg['password']; ?></span>
            </p>

            <p class="form-group text-right">
                <button type="submit" id="login" name="submit" id="login" class="btn btn-blue">ログイン</button>
            </p>
        </form>
    </section>
    <?php errorMessages($errors) ?>
</div>

<?php include("{$PATH}/public/assets/php/partial/footer.php"); ?>
</body>
</html>