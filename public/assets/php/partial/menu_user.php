<div class="menu">
    <h2 class="text-center">メニュー</h2>
    <nav>
        <ul>
            <li><a href="<?php echo $URL ?>/user/order" onclick="return checkEdit();">商品を注文する</a></li>
            <li><a href="<?php echo $URL ?>/user/stock" onclick="return checkEdit();">在庫から注文する</a></li>
            <li><a href="<?php echo $URL ?>/user/history" onclick="return checkEdit();">履歴を確認する</a></li>
            <li><a href="" onclick="return checkEdit();" class="logout-menu">ログアウトする</a></li>
            <div class="logout-content arrow-box">
                <a href="<?php echo $URL ?>/public/assets/php/partial/logout.php" onclick="return checkEdit();">ログアウト</a>
            </div>
        </ul>
    </nav>
</div>
