<?php
include     ('../../public/assets/php/partial/require_common.php');
include     ($PATH.'/public/assets/php/lib/common/sessionCheck.php');

require_once($PATH.'/public/assets/php/lib/user/userProcess.php');
$errors     = [];
$lists      = [];
$i          = 0;
$priceTotal = 0;
$fixed      = 0;
try {
    $lists = returnStockList($_SESSION['USERID']);
    $pdo        = connectDb('coop');
    $sql  = "SELECT * FROM monthly WHERE public_flag=1;";
    $stmt = $pdo->prepare($sql);
    $res  = $stmt->execute(null);
    if(!$res) throw new Exception("[order]:DB接続時にエラーが発生しました。");
    $tmp = $stmt->fetch();
    $date  = $tmp['date'];
    $fixed = intval($tmp['fixed_flag']);
} catch (Exception $e) {
    $errors[] = $e->getMessage();
}

if(count($_POST)>0){
    try {
        $errors = stockListFromOrderWhenNewlyDetermineWhether($_SESSION['USERID'], $_POST);
    } catch (Exception $e) {
        $errors[] = $e->getMessage();
    }
    if(count($errors) === 0) header('location: ./index.php');
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>CoopSystem</title>
    <?php include($PATH."/public/assets/php/partial/head.php"); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo $URL ?>/public/assets/stylesheets/users.css">
</head>
<body>
<?php include($PATH."/public/assets/php/partial/header.php"); ?>

<button class="col-btn" col-target="#col-menu"></button>
<div class="flex">
    <div class="col-2 border-right scroll bg-glay" id="col-menu">
        <?php include($PATH."/public/assets/php/partial/menu_user.php"); ?>
    </div>
    <div class="col-10 container scroll">
        <h1>在庫から注文する</h1>
        <?php if(count($lists) > 0){ ?>
        <form method="post" action="">
        <table class="table-hover border-bottom">
            <thead>
                <tr>
                    <th width="10%" class="text-center">カテゴリ</th>
                    <th width="30%">商品名</th>
                    <th width="10%" class="text-center">在庫数</th>
                    <th width="10%" class="text-center">単価</th>
                    <th width="15%" class="text-center">購入数</th>
                    <th width="15%" class="text-right">合計金額</th>
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
                            <span id="display_number_<?php echo $list['monthly_goods_id'] ?>"><?php echo intval($list['ordering_quantity']) ?></span>個
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
           <?php if($fixed !== 1){ ?>
            <button type="submit" name="order" value="1" class="btn btn-blue">注文します</button>
            <?php }else{ ?>
            <h2 class="text-red">今月の注文は確定されたため締め切られました。</h2>
            <?php } ?>
        </p>
        </form>

        <div class="flex">
            <div class="col-7"></div>
            <div class="draggable border-radius col-5 bg-white opacity-8">
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
        <?php }else{ ?>
        <p>現在、購入可能な在庫商品はありません。</p>
        <?php } ?>

        <?php errorMessages($errors) ?>

    </div>
</div>
<script src="<?php echo $URL ?>/public/assets/js/users.js"></script>
<?php include($PATH."/public/assets/php/partial/footer.php"); ?>
</body>
</html>