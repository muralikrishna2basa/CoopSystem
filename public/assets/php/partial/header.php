<?php
    $userName = '<span class="honorific">ログインしていません</span>';
//    $_SESSION['USER_NAME'] = '手島尚人';
    if(isset($_SESSION['USER_NAME'])) $userName = $_SESSION['USER_NAME'].'<span class="honorific">さん</span>';
?>


<header>
    <div class="container">
        <a href="" class="logo"></a>
    </div>

    <div class="user-name">
        <p><?php echo $userName; ?></p>
    </div>
</header>