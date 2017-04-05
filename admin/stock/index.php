<?php
include     ('../../public/assets/php/partial/require_common.php');
include     ($PATH.'/public/assets/php/lib/common/sessionCheck.php');

require_once($PATH.'/public/assets/php/convertCsvFileToArray.php');
require_once($PATH."/public/assets/php/lib/administrator/administratorProcess.php");

$errors = [];
$lists  = [];
try {
    $lists = stockListTemporaryCreating();
} catch (Exception $e) {
    $errors [] = $e->getMessage();
}
if(count($_POST) > 0)
{
    try {
        isInventoryListNewly($_POST);
        header('location: ./');
    } catch (Exception $e) {
        $errors[] = $e->getMessage();
//        echo $e->getMessage();
    }
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
        <?php include($PATH."/public/assets/php/partial/menu_admin.php"); ?>
    </div>
    <div class="col-10 container scroll">
        <h2>在庫を編集する</h2>
        <form method="post">
            <table class="border-bottom table-hover">
                <thead>
                    <tr>
                        <th class="text-center">カテゴリ名</th>
                        <th                    >商品名</th>
                        <th class="text-center">内容量</th>
                        <th class="text-center">必要数</th>
                        <th class="text-right" >単価</th>
                        <th class="text-center">在庫数</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($lists as $list){ ?>
                    <tr>
                        <td class="text-center">
                            <input type="text" name="monthly_goods_id[]" value="<?php echo $list['monthly_goods_id'] ?>">
                            <p class="label" style="background: <?php echo $list['color'] ?>; color: <?php echo getFontColor($list['color']) ?>"><?php echo $list['category_name'] ?></p>
                        </td>
                        <td>
                            <p><?php echo $list['goods_name'] ?></p>
                        </td>
                        <td class="text-center">
                            <p><?php echo $list['detail_amount_per_one'] ?></p>
                        </td>
                        <td class="text-center">
                            <p><?php echo $list['required_quantity'] ?>個</p>
                        </td>
                        <td class="text-right">
                            <p><?php echo number_format($list['unit_price']) ?>円</p>
                        </td>
                        <td class="text-center">
                            <p
                                data-number ="stock_quantity_<?php echo $list['monthly_goods_id'] ?>"
                                data-display="display_number_<?php echo $list['monthly_goods_id'] ?>"
                            >
                                <button class="ordering-minus">&minus;</button>
                                <span id="display_number_<?php echo $list['monthly_goods_id'] ?>"><?php echo $list['initial_stock_quantity'] ?></span>個
                                <button class="ordering-plus">+</button>
                            </p>
                            <input type="hidden" id="initial_stock_quantity_<?php echo $list['monthly_goods_id'] ?>" name="initial_stock_quantity[]" value="<?php echo $list['initial_stock_quantity'] ?>">
                            <input type="hidden" id="stock_quantity_<?php echo $list['monthly_goods_id'] ?>"         name="stock_quantity[]"         value="<?php echo $list['initial_stock_quantity'] ?>">

                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <p class="text-right"><button type="submit" class="btn btn-blue">在庫を登録する</button></p>
        </form>

        <?php errorMessages($errors) ?>

    </div>
</div>
<script src="<?php echo $URL ?>/public/assets/js/users.js"></script>
<?php include($PATH."/public/assets/php/partial/footer.php"); ?>
</body>
</html>