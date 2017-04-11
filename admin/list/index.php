<?php
include     ('../../public/assets/php/partial/require_common.php');
include     ($PATH.'/public/assets/php/lib/common/sessionCheck.php');

require_once($PATH.'/public/assets/php/lib/administrator/administratorProcess.php');
require_once($PATH.'/public/assets/php/lib/administrator/category.php');
require_once($PATH.'/public/assets/php/auth.php');
$lists  = [];
$errors = [];
$monthlyId = '';
$date      = '';
try{

    $lists = monthlySum();

}catch (Exception $e){
    $errors[] = $e->getMessage();
}

//var_dump($lists);
?>

<!DOCTYPE html>
<html>
<head>
    <title>CoopSystem</title>
    <?php include($PATH."/public/assets/php/partial/head.php"); ?>
    <script src="<?php echo $URL ?>/public/assets/js/loading.js"></script>
</head>
<body>
<?php include($PATH."/public/assets/php/partial/header.php"); ?>

<button class="col-btn" col-target="#col-menu"></button>

<div class="flex">
    <div class="col-2 border-right  scroll bg-glay" id="col-menu">
        <?php include($PATH."/public/assets/php/partial/menu_admin.php"); ?>
    </div>
    <div class="col-10 container scroll">
        <h2>月ごとの明細を確認する</h2>
        <?php if(count($lists) > 0){ ?>
        <table class="table-hover border-bottom">
            <thead>
                <th class="text-center">対象月</th>
                <th class="text-center">購入数</th>
                <th class="text-right">合計金額</th>
                <th class="text-center"></th>
            </thead>
            <tbody>
                <?php foreach($lists as $list){ ?>
                <?php //for($i = $page; $i < ($maxPage); $i++){ ?>
                <tr>
                    <td class="text-center"><?php echo date('Y年n月', strtotime($list['date'])) ?></td>
                    <td class="text-center"><?php echo $list['total_monthly_ordering_quantity'] ?>個</td>
                    <td class="text-right"><?php echo number_format(intval($list['total_monthly_amount'])) ?>円</td>
                    <td class="text-center">
                        <a href="./detail/?page=1&list=0&id=<?php echo $list['monthly_id'] ?>">詳細を見る</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php //var_dump(floor(count($lists) / $num)) ?>
        <?php //setPages('./?id=1', floor(count($lists) / $num), $nowPage) ?>

        <?php }else{ ?>
        <p>対象が存在しないようです。</p>
        <?php } ?>
        <?php errorMessages($errors) ?>
    </div>
    <?php include($PATH."/public/assets/php/partial/tips.php"); ?>
</div>
<?php include($PATH."/public/assets/php/partial/footer.php"); ?>
</body>
</html>