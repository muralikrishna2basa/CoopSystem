<?php
    $userName = '';
//    $_SESSION['USER_NAME'] = '手島尚人';
    if(isset($_SESSION['USER_NAME'])) $userName = $_SESSION['USER_NAME'].'<span class="honorific">さん</span>';
?>


<header>
    <div class="container">
        <a href="" class="logo"></a>
    </div>

    <div class="user-name">
        <p class="<?php if(mb_strlen($userName) > 0) echo 'logout-menu' ?>"><?php echo $userName; ?></p>
    </div>

    <div class="logout-content arrow-box">
        <a href="<?php echo $URL ?>/public/assets/php/partial/logout.php">ログアウト</a>
    </div>
</header>