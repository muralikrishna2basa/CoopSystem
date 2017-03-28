<!DOCTYPE html>
<html>
<head>
    <title>CoopSystem</title>
    <?php include("../../public/assets/php/partial/head.php"); ?>
</head>
<body>
<?php include("../../public/assets/php/partial/header.php"); ?>

<div class="container flex">
    <div class="col-2"></div>
    <div class="col-10">
        <pre><?php var_dump($_POST); ?></pre>
        <form method="post" action="">
        <table class="table-stripe border-bottom">
            <thead>
                <tr>
                    <th>商品ID</th>
                    <th>商品名</th>
                    <th>内容量</th>
                    <th>必要数</th>
                    <th>単価</th>
                    <th>購入数</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <p class="form-group">
                            <label>monthly_goods_id</label>
                            <input type="text" id="monthly_goods_id_1" name="monthly_goods_id_1" value="1">
                        </p>
                    </td>
                    <td>
                        <p>素敵な鉛筆</p>
                    </td>
                    <td>
                        <p>12本入り</p>
                    </td>
                    <td>
                        <p>24個</p>
                    </td>
                    <td>
                        <p>200円</p>
                    </td>
                    <td>
                        <p class="form-group">
                            <label>ordering_quantity</label>
                            <input type="text" id="ordering_quantity_1" name="ordering_quantity_1">
                        </p>
                    </td>
                </tr>

                <tr>
                    <td>
                        <p class="form-group">
                            <label>monthly_goods_id</label>
                            <input type="text" id="monthly_goods_id_2" name="monthly_goods_id_2" value="2">
                        </p>
                    </td>
                    <td>
                        <p>よく消える消しゴム</p>
                    </td>
                    <td>
                        <p>1個</p>
                    </td>
                    <td>
                        <p>12個</p>
                    </td>
                    <td>
                        <p>150円</p>
                    </td>
                    <td>
                        <p class="form-group">
                            <label>ordering_quantity</label>
                            <input type="text" id="ordering_quantity_2" name="ordering_quantity_2">
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="text-right form-group">
            <button type="submit">送信する</button>
        </p>
        </form>

    </div>
</div>

<?php include("../../public/assets/php/partial/footer.php"); ?>
</body>
</html>