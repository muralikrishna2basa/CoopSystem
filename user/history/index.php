<?php
    session_start();
    require_once('../../public/assets/php/lib/user/userProcess.php');
    require_once('../../public/assets/php/connectDb.php');
    require_once('../../public/assets/php/getFontColor.php');
    $lists = displayHistory(1, 1);
    $total = 0;
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
    <div class="col-2 border-right min-height" id="col-menu">
        <?php include("../../public/assets/php/partial/menu_user.php"); ?>
    </div>
    <div class="col-10 container">
        <h1>注文した履歴を確認する</h1>
        <table class="table-hover border-bottom">
            <thead>
                <tr>
                    <th width="15%" class="text-center">カテゴリ</th>
                    <th width="30%" class="text-left">商品名</th>
                    <th width="20%" class="text-center">単価</th>
                    <th width="20%" class="text-center">購入数</th>
                    <th width="15%" class="text-right">合計金額</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lists as $list){ ?>
                <tr>
                    <td class="text-center">
                        <p class="label" style="background: <?php echo $list['color'] ?>; color: <?php echo getFontColor($list['color']) ?>;">
                            <?php echo $list['category_name'] ?>
                        </p>
                    </td>
                    <td class="text-left">
                        <p><?php echo $list['goods_name'] ?></p>
                    </td>
                    <td class="text-center">
                        <p><?php echo number_format(intval($list['unit_price'])) ?>円</p>
                    </td>

                    <td class="text-center">
                        <p><?php echo $list['ordering_quantity'] ?>個</p>
                    </td>
                    <td class="text-right">
                        <p><?php echo number_format(intval($list['amount'])) ?>円</p>
                        <?php $total += intval($list['amount']) ?>
                    </td>
                </tr>
                <?php } ?>
                <tr>
                    <td colspan="3"></td>
                    <td class="text-center">合計</td>
                    <td class="text-right"><?php echo number_format($total) ?>円</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php include("../../public/assets/php/partial/footer.php"); ?>
</body>
</html>