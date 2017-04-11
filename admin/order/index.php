<?php
include     ('../../public/assets/php/partial/require_common.php');
include     ($PATH.'/public/assets/php/lib/common/sessionCheck.php');

require_once($PATH.'/public/assets/php/convertCsvFileToArray.php');
require_once($PATH."/public/assets/php/lib/administrator/administratorProcess.php");
require_once($PATH.'/public/assets/php/auth.php');
$errors = [];
try {
    $lists = orderListDisplay();
} catch (Exception $e) {
    $errors[] = $e->getMessage();
}

// TODO: stock_quantity の取得 teshima -> kawanishi 2017/04/03
?>

<!DOCTYPE html>
<html>
<head>
    <?php include($PATH."/public/assets/php/partial/head.php"); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo $URL ?>/public/assets/stylesheets/users.css">
    <script src="<?php echo $URL ?>/public/assets/js/loading.js"></script>
</head>
<body>

<?php include($PATH."/public/assets/php/partial/header.php"); ?>

<pre><?php var_dump($lists) ?></pre>


<button class="col-btn" col-target="#col-menu"></button>

<div class="flex">
    <div class="col-2 border-right scroll bg-glay" id="col-menu">
        <?php include($PATH."/public/assets/php/partial/menu_admin.php"); ?>
    </div>
    <div class="col-10 container scroll">
        <h2>発注リストを修正する</h2>
        <form method="post">
            <table class="border-bottom table-hover">
                <thead>
                    <tr>
                        <th>カテゴリ名</th>
                        <th>商品名</th>
                        <th class="text-center">内容量</th>
                        <th class="text-center">必要数</th>
                        <th class="text-right">単価</th>
                        <th class="text-center">在庫数</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($lists as $list){ ?>
                    <tr>
                        <td>
                            <input type="hidden" name="monthly_goods_id[]" value="<?php echo $list['monthly_goods_id'] ?>">
                            <p class="label" style="background: <?php echo $list['color'] ?>; color: <?php echo getFontColor($list['color']) ?>"><?php echo $list['category_name'] ?></p>
                        </td>
                        <td>
                            <p><?php echo $list['goods_name'] ?></p>
                        </td>
                        <td class="text-center">
                            <p><?php //echo $list['detail_amount_per_one'] ?></p>
                        </td>
                        <td class="text-center">
                            <p><?php echo $list['ordering_quantity'] ?>個</p>
                        </td>
                        <td class="text-right">
                            <p><?php echo number_format($list['unit_price']) ?>円</p>
                        </td>
                        <td>
                            <p
                                data-number ="stock_quantity_<?php echo $list['monthly_goods_id'] ?>"
                                data-display="display_number_<?php echo $list['monthly_goods_id'] ?>"
                            >
                                <button class="ordering-minus">&minus;</button>
<!--
TODO: stock_quantityとinitial_stock_quantityのセット teshima 2017/04/03
                                <span id="display_number_<?php echo $list['monthly_goods_id'] ?>"><?php echo intval($list['monthly_goods_id']) ?></span>
-->
                                <span id="display_number_<?php echo $list['monthly_goods_id'] ?>">0</span>個
                                <button class="ordering-plus">+</button>
                            </p>
<!--
                            <input type="text" id="initial_stock_quantity_<?php echo $list['monthly_goods_id'] ?>" name="initial_stock_quantity[]" value="<?php echo intval($list['stock_quantity']) ?>">
                            <input type="text" id="stock_quantity_<?php echo $list['monthly_goods_id'] ?>"         name="stock_quantity[]"         value="<?php echo intval($list['stock_quantity']) ?>">
-->
                            <input type="text" id="initial_stock_quantity_<?php echo $list['monthly_goods_id'] ?>" name="initial_stock_quantity[]" value="0">
                            <input type="text" id="stock_quantity_<?php echo $list['monthly_goods_id'] ?>"         name="stock_quantity[]"         value="0">

                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </form>
    </div>
</div>
<script src="<?php echo $URL ?>/public/assets/js/users.js"></script>
<?php include($PATH."/public/assets/php/partial/footer.php"); ?>
</body>
</html>