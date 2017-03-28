<?php
    require_once('../../public/assets/php/lib/user/userProcess.php');
    require_once('../../public/assets/php/connectDb.php');
    $lists = returnStockList(0);
    $i     = 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>CoopSystem</title>
    <?php include("../../public/assets/php/partial/head.php"); ?>
</head>
<body>
<?php include("../../public/assets/php/partial/header.php"); ?>

<button class="col-btn" col-target="#col-menu"></button>

<div class="flex">
    <div class="col-3 border-right" id="col-menu">
        <?php include("../../public/assets/php/partial/menu_user.php"); ?>
    </div>
    <div class="col-9 container">
        <form method="post" action="">
        <table class="table-hover border-bottom">
            <thead>
                <tr>
                    <th class="text-center">商品ID/カテゴリ</th>
                    <th>商品名</th>
                    <th>内容量</th>
                    <th>在庫数</th>
                    <th>単価</th>
                    <th>購入数</th>
                    <th>合計金額</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lists as $list){ ?>
                <tr>
                    <td class="text-center">
                        <p class="form-group">
                            <input type="hidden" id="monthly_goods_id_<?php echo $list['monthly_goods_id'] ?>" name="monthly_goods_id[]" value="<?php echo $list['monthly_goods_id']; ?>">
                        </p>
                        <p class="label" style="background: <?php echo $list['color'] ?>"><?php echo $list['category_name']; ?></p>
                    </td>
                    <td>
                        <p><?php echo $list['goods_name'] ?></p>
                    </td>
                    <td>
                        <p><?php echo $list['detail_amount_per_one'] ?></p>
                    </td>
                    <td>
                        <p><?php echo $list['stock_quantity'] ?>個</p>
                    </td>
                    <td>
                        <p><?php echo number_format(intval($list['unit_price'])) ?>円</p>
                    </td>
                    <td>
                        <p class="form-group">
                            <label>ordering_quantity</label>
                            <input type="text" id="ordering_quantity_<?php echo $list['monthly_goods_id'] ?>" name="ordering_quantity[]">
                        </p>
                    </td>
                    <td>
                        <p></p>
                    </td>
                </tr>
                <?php $i++; } ?>
            </tbody>
        </table>
        <p class="text-right form-group">
            <button type="submit" class="btn btn-blue">送信する</button>
        </p>
        </form>

        <!-- debug -->
        <h2>returnStockList</h2>
        <pre><?php var_dump($lists) ?></pre>
        <h2>POST</h2>
        <pre><?php var_dump($_POST) ?></pre>

    </div>
</div>

<?php include("../../public/assets/php/partial/footer.php"); ?>
</body>
</html>