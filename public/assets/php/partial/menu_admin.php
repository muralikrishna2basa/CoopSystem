<div class="menu">
    <h2 class="text-center">管理者メニュー</h2>
    <nav>
        <ul>
            <li><a href="<?php echo $URL ?>/admin/import" onclick="return checkEdit();">商品リストを登録する</a></li>   <!-- 月を選択して取込 -->
            <li><a href="<?php echo $URL ?>/admin/month" onclick="return checkEdit();">月を選択して処理を行う</a></li>      <!-- 月を選択して公開 -->
            <li><a href="<?php echo $URL ?>/admin/fixed" onclick="return checkEdit();">発注を確定する</a></li>       <!-- 公開フラグが立っているものを確定 -->
            <li><a href="<?php echo $URL ?>/admin/stock/add" onclick="return checkEdit();">在庫を登録する</a></li>       <!-- 確定済みかつ一番若い月を抽出 -->
            <li><a href="<?php echo $URL ?>/admin/stock/edit" onclick="return checkEdit();">在庫を編集する</a></li>       <!-- 確定済みかつ一番若い月を抽出 -->
<!--
            <li><a href="<?php echo $URL ?>/admin/export">確定リストを出力する</a></li>
            <li><a href="<?php echo $URL ?>/admin/unfixed">確定を解除する</a></li>
            <li><a href="<?php echo $URL ?>/admin/order">注文リストを修正する</a></li>
-->
            <li><a href="<?php echo $URL ?>/admin/category" onclick="return checkEdit();">カテゴリを編集する</a></li>
            <li><a href="" onclick="return checkEdit();" class="logout-menu">ログアウトする</a></li>
            <div class="logout-content arrow-box">
                <a href="<?php echo $URL ?>/public/assets/php/partial/logout.php" onclick="return checkEdit();">ログアウト</a>
            </div>
        </ul>
    </nav>
</div>
