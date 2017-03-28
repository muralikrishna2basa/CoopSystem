<?php
    require_once('../../public/assets/php/lib/user/userProcess.php');
    require_once('../../public/assets/php/connectDb.php');
    $lists = returnCurrentMonthProductList();
    $i     = 0;
    if(count($_POST)>0){
            $error =doOrderStock(1,$_POST);
        if($error!=null)echo $error;
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>CoopSystem</title>
    <?php include("../../public/assets/php/partial/head.php"); ?>
</head>
<body>
<?php include("../../public/assets/php/partial/header.php"); ?>

<div class="container flex">
    <div class="col-2">
        <h2>MENU</h2>
    </div>
    <div class="col-10">
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
                <?php foreach ($lists as $list){ ?>
                <tr>
                    <td>
                        <p class="form-group">
                            <label>monthly_goods_id</label>
                            <input type="text" id="monthly_goods_id_<?php echo $list['monthly_goods_id'] ?>" name="monthly_goods_id[]" value="<?php echo $list['monthly_goods_id']; ?>">
                        </p>
                    </td>
                    <td>
                        <p><?php echo $list['goods_name'] ?></p>
                    </td>
                    <td>
                        <p><?php echo $list['detail_amount_per_one'] ?></p>
                    </td>
                    <td>
                        <p><?php echo $list['required_quantity'] ?>個</p>
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
                </tr>
                <?php $i++; } ?>
            </tbody>
        </table>
        <p class="text-right form-group">
            <button type="submit" class="btn btn-blue">送信する</button>
        </p>
        </form>

        <!-- debug -->
        <h2>returnCurrentMonthProductList</h2>
        <pre><?php var_dump($lists) ?></pre>
        <h2>POST</h2>
        <pre><?php var_dump($_POST) ?></pre>

    </div>
</div>

<?php include("../../public/assets/php/partial/footer.php"); ?>
</body>
</html>