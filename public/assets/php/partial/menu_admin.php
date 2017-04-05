<div class="menu">
    <h2 class="text-center">管理者メニュー</h2>
    <nav>
        <ul>
            <li><a href="<?php echo $URL ?>/admin/import">商品リストを編集する</a></li>   <!-- 月を選択して取込 -->
            <li><a href="<?php echo $URL ?>/admin/month">公開する月を選ぶ</a></li>      <!-- 月を選択して公開 -->
            <li><a href="<?php echo $URL ?>/admin/fixed">発注を確定する</a></li>       <!-- 公開フラグが立っているものを確定 -->
            <li><a href="<?php echo $URL ?>/admin/export">確定リストを出力する</a></li>   <!-- 公開フラグが立っているものを確定 -->
            <li><a href="<?php echo $URL ?>/admin/unfixed">確定を解除する</a></li>     <!-- 公開フラグが立っているものを確定 -->
            <li><a href="<?php echo $URL ?>/admin/stock">在庫を登録する</a></li>       <!-- 確定済みかつ一番若い月を抽出 -->
<!--
            <li><a href="<?php echo $URL ?>/admin/order">注文リストを修正する</a></li>
-->
            <li><a href="<?php echo $URL ?>/admin/category">カテゴリを編集する</a></li>
            <li><a href="<?php echo $URL ?>/public/assets/php/partial/logout.php">ログアウトする</a></li>
        </ul>
    </nav>
</div>
