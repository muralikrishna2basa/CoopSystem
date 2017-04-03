<?php
include     ('../public/assets/php/partial/require_common.php');

require_once('../public/assets/php/auth.php');
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
        header('location: ../admin/month');
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
            if(!$res || $orderFlag !== 0) throw new Exception("order_flag読み込み時にエラーが発生しました。");
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
            header('location: ../user/order');

        } catch (Exception $e) {
            $errors[] = $e->Getmessage();
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>ログイン画面</title>
    <?php include("../public/assets/php/partial/head.php"); ?>
</head>
<body>
<?php include("../public/assets/php/partial/header.php"); ?>

<div class="container flex">
    <div class="col-3"></div>
    <div class="col-6">
        <section class="container">
            <h2>ログイン画面</h2>
            <form method="post">
                <p class="form-group <?php if(mb_strlen($msg['loginid']) > 0) echo 'danger' ?>">
                    <label>UserName</label>
                    <input type="text" name="loginid" placeholder="ユーザー名を入力してください" value="<?php if(isset($_POST['loginid'])) echo $_POST['loginid'] ?>">
                    <span class="msg"><?php echo $msg['loginid']; ?></span>
                </p>

                <p class="form-group <?php if(mb_strlen($msg['password']) > 0) echo 'danger' ?>">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="パスワードを入力してください">
                    <span class="msg"><?php echo $msg['password']; ?></span>
                </p>

                <p class="form-group text-right">
                    <button type="submit" name="submit" class="btn btn-blue">ログイン</button>
                </p>
            </form>
        </section>
        <?php errorMessages($errors) ?>
    </div>

    <div class="col-3"></div>

</div>

<?php include("../public/assets/php/partial/footer.php"); ?>
</body>
</html>