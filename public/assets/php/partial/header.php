<?php
    $userName = 'ゲスト';
//    $_SESSION['USER_NAME'] = '手島尚人';
    if(isset($_SESSION['USER_NAME'])) $userName = $_SESSION['USER_NAME'];
?>


<header>
    <div class="container">
        <a href="" class="logo"></a>
    </div>

    <div class="user-name">
        <p>
            <span><?php echo $userName; ?></span>
            <span class="honorific">さん</span>
        </p>
    </div>
</header>