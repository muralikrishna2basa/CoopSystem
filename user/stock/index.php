<?php
    session_start();
    require_once('../../public/assets/php/lib/user/userProcess.php');
    require_once('../../public/assets/php/connectDb.php');
    require_once('../../public/assets/php/getFontColor.php');
    $lists      = returnStockList(9,0);
    $i          = 0;
    $priceTotal = 0;
    if(count($_POST)>0){
            $error =stockListFromOrderWhenNewlyDetermineWhether(9,$_POST);
        if($error!=null)
        {
            echo $error;
        }
        else{
            header('location: ./index.php');
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>CoopSystem</title>
    <?php include("../../public/assets/php/partial/head.php"); ?>
    <link rel="stylesheet" type="text/css" href="../../public/assets/stylesheets/users.css">
</head>
<body>
<?php include("../../public/assets/php/partial/header.php"); ?>

<button class="col-btn" col-target="#col-menu"></button>
<div class="flex">
    <div class="col-2 border-right min-height" id="col-menu">
        <?php include("../../public/assets/php/partial/menu_user.php"); ?>
    </div>
    <div class="col-10 container">
        <h1>在庫から注文する</h1>
        <form method="post" action="">
        <table class="table-hover border-bottom">
            <thead>
                <tr>
                    <th class="text-center">カテゴリ</th>
                    <th>商品名</th>
                    <th class="text-center">内容量</th>
                    <th class="text-center">在庫数</th>
                    <th class="text-center">単価</th>
                    <th class="text-center">購入数</th>
                    <th class="text-right">合計金額</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lists as $list){ ?>
                <tr>
                    <td class="text-center">
                        <p class="label" style="background: <?php echo $list['color'] ?>; color: <?php echo getFontColor($list['color']) ?>;"><?php echo $list['category_name']; ?></p>
                        <input type="hidden" id="monthly_goods_id_<?php echo $list['monthly_goods_id'] ?>" name="monthly_goods_id[]" value="<?php echo $list['monthly_goods_id']; ?>">
                    </td>
                    <td>
                        <p><?php echo $list['goods_name'] ?></p>
                    </td>
                    <td class="text-center">
                        <p><?php echo $list['detail_amount_per_one'] ?></p>
                    </td>
                    <td class="text-center">
                        <p>
                            <span id="stock_quantity_<?php echo $list['monthly_goods_id'] ?>"><?php echo $list['stock_quantity'] ?></span>
                            <span>個</span>
                        </p>
                    </td>
                    <td class="text-center">
                        <p>
                            <span id="unit_price_<?php echo $list['monthly_goods_id'] ?>"><?php echo number_format(intval($list['unit_price'])) ?></span>
                            <span>円</span>
                        </p>
                    </td>
                    <td class="text-center">
                        <p
                            data-price  ="unit_price_<?php echo $list['monthly_goods_id'] ?>"
                            data-total  ="total_<?php echo $list['monthly_goods_id'] ?>"
                            data-number ="ordering_quantity_<?php echo $list['monthly_goods_id'] ?>"
                            data-display="display_number_<?php echo $list['monthly_goods_id'] ?>"
                            data-stock  ="stock_quantity_<?php echo $list['monthly_goods_id'] ?>"
                        >
                            <button class="ordering-minus">&minus;</button>
                            <span id="display_number_<?php echo $list['monthly_goods_id'] ?>"><?php echo intval($list['ordering_quantity']) ?></span>
                            <button class="ordering-plus">+</button>
                        </p>
                        <input type="hidden" id="initial_ordering_quantity_<?php echo $list['monthly_goods_id'] ?>" name="initial_ordering_quantity[]" value="<?php echo intval($list['ordering_quantity']) ?>">
                        <input type="hidden" id="ordering_quantity_<?php echo $list['monthly_goods_id'] ?>"         name="ordering_quantity[]"         value="<?php echo intval($list['ordering_quantity']) ?>">
                    </td>
                    <td class="text-right">
                        <p>
                            <?php $total = intval($list['ordering_quantity']) * intval($list['unit_price']) ?>
                            <span id="total_<?php echo $list['monthly_goods_id'] ?>"><?php echo number_format($total) ?></span>
                            <span>円</span>
                        </p>
                    </td>
                </tr>
                <?php $priceTotal += $total; $i++; } ?>
            </tbody>
        </table>
        <p class="text-right form-group">
            <button type="submit" class="btn btn-blue">送信する</button>
        </p>
        </form>

        <div class="flex">
            <div class="col-6"></div>
            <div class="draggable border-radius col-6 bg-white opacity-7">
                <table class="border-none">
                    <tr>
                        <td class="text-center">
                            <p>購入金額</p>
                        </td>
                        <td>
                            <p><span id="price_total"><?php echo number_format($priceTotal) ?></span>円</p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="../../public/assets/js/users.js"></script>
<?php include("../../public/assets/php/partial/footer.php"); ?>
</body>
</html>