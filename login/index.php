<?php
    require_once('../public/assets/php/auth.php');
    session_start();
    $msg = ['loginid'=>'', 'password'=>''];
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
            echo $e->Getmessage();
            exit();
        }

        if($msg === true)
        {
            // 認証成功 非管理者ページへ遷移
//            echo "success:goto users";
            try {
                $user = getSingleUser(0, $_POST['loginid']);
            } catch (Exception $e) {
                echo $e->Getmessage();
                exit();
            }

            $_SESSION['USERID']    = intval($user['userid']);
            $_SESSION['USER_NAME'] = $user['userName'];
            var_dump($_SESSION);
            header('location: ../user/order');
        }else{
            // 認証失敗 バリデーション実行
//            echo 'failed';
        }
    }
//    var_dump($msg);

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
    </div>

    <div class="col-3"></div>

</div>

<?php include("../public/assets/php/partial/footer.php"); ?>
</body>
</html>