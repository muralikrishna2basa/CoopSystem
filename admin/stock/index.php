<?php
session_start();
require_once('../../public/assets/php/connectDb.php');
require_once('../../public/assets/php/lib/administrator/administratorProcess.php');
$lists = stockListTemporaryCreating(1);
?>

<!DOCTYPE html>
<html>
<head>
    <title>CoopSystem</title>
    <?php include("../../public/assets/php/partial/head.php"); ?>
</head>
<body>

<?php include("../../public/assets/php/partial/header.php"); ?>
<pre><?php var_dump($lists) ?></pre>

<button class="col-btn" col-target="#col-menu"></button>

<div class="flex">
    <div class="col-2 border-right min-height" id="col-menu">
        <?php include("../../public/assets/php/partial/menu_admin.php"); ?>
    </div>
    <div class="col-10 container">
        <h2></h2>
        <form method="post">
            <table class="border-none">
                <thead>
                    <tr>
                        <th>カテゴリ名</th>
                        <th>商品名</th>
                        <th>内容量</th>
                        <th>必要数</th>
                        <th>単価</th>
                        <th>在庫数</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($lists as $list){ ?>
                    <tr>
                        <td>
                            <input type="text" name="">
                            <p class="label" style="background: <?php echo $list['color'] ?>"><?php echo $list['category_name'] ?></p>
                        </td>
                        <td>
                            <p><?php echo $list['goods_name'] ?></p>
                        </td>
                        <td>
                            <p><?php echo $list['detail_amount_per_one'] ?></p>
                        </td>
                        <td>
                            <p></p>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </form>
    </div>
</div>

<?php include("../../public/assets/php/partial/footer.php"); ?>
</body>
</html>