<?php
include     ('../../../public/assets/php/partial/require_common.php');
include     ($PATH.'/public/assets/php/lib/common/sessionCheck.php');

require_once($PATH.'/public/assets/php/convertCsvFileToArray.php');
require_once($PATH."/public/assets/php/lib/administrator/administratorProcess.php");

$errors = [];
$lists  = [];
try {
    $lists = administratorReturnStockList();
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
try {
    $nowPage   = (isset($_GET['page'])) ? $_GET['page'] : 1;
    $num       = 50;
    $pages     = getPagenation($lists, $nowPage);
    $page      = $pages['page'];
    $maxPage   = $pages['maxPage'];
} catch (Exception $e) {
    $errors[] = $e->getMessage();
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
        <?php if(count($lists) > 0){ ?>
        <form method="post">
            <table class="border-bottom table-hover">
                <thead>
                    <tr>
                        <th class="text-center">カテゴリ名</th>
                        <th                    >商品名</th>
                        <th class="text-right" >単価</th>
                        <th class="text-center">在庫数</th>
                    </tr>
                </thead>
                <tbody>
                    <?php //foreach($lists as $list){ ?>
                    <?php for($i = $page; $i < ($maxPage); $i++){ ?>
                    <tr>
                        <td class="text-center">
                            <input type="hidden" name="monthly_goods_id[]" value="<?php echo $lists[$i]['monthly_goods_id'] ?>">
                            <p class="label" style="background: <?php echo $lists[$i]['color'] ?>; color: <?php echo getFontColor($lists[$i]['color']) ?>"><?php echo $lists[$i]['category_name'] ?></p>
                        </td>
                        <td>
                            <p><?php echo $lists[$i]['goods_name'] ?></p>
                        </td>
                        <td class="text-right">
                            <p><?php echo number_format($lists[$i]['unit_price']) ?>円</p>
                        </td>
                        <td class="text-center">
                            <p
                                data-number ="stock_quantity_<?php echo $lists[$i]['monthly_goods_id'] ?>"
                                data-display="display_number_<?php echo $lists[$i]['monthly_goods_id'] ?>"
                            >
                                <button class="ordering-minus">&minus;</button>
                                <span id="display_number_<?php echo $lists[$i]['monthly_goods_id'] ?>"><?php echo $lists[$i]['stock_quantity'] ?></span>個
                                <button class="ordering-plus">+</button>
                            </p>
                            <input type="hidden" id="initial_stock_quantity_<?php echo $lists[$i]['monthly_goods_id'] ?>" name="initial_stock_quantity[]" value="<?php echo $lists[$i]['stock_quantity'] ?>">
                            <input type="hidden" id="stock_quantity_<?php echo $lists[$i]['monthly_goods_id'] ?>"         name="stock_quantity[]"         value="<?php echo $lists[$i]['stock_quantity'] ?>">

                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <p class="text-right"><button type="submit" class="btn btn-blue">在庫を更新する</button></p>
        </form>
        <?php }else{ ?>
        <p>在庫が登録されていないようです。</p>
        <?php } ?>

        <?php errorMessages($errors) ?>

    </div>
</div>
<script src="<?php echo $URL ?>/public/assets/js/users.js"></script>
<?php include($PATH."/public/assets/php/partial/footer.php"); ?>
</body>
</html>