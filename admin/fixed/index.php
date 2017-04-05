<?php
include     ('../../public/assets/php/partial/require_common.php');
include     ($PATH.'/public/assets/php/lib/common/sessionCheck.php');

require_once($PATH.'/public/assets/php/lib/administrator/category.php');
require_once($PATH.'/public/assets/php/auth.php');
$lists  = [];
$errors = [];
$monthlyId = '';
$date      = '';
try{
    $pdo  = connectDb('coop');
    $sql  = "SELECT MIN(monthly_id) AS monthly_id, date FROM monthly WHERE fixed_flag = 0;";
    $stmt = $pdo->prepare($sql);
    $res  = $stmt->execute(null);
    if(!$res) throw new Exception("monthly_id取得時にエラーが発生しました。");

    $row       = $stmt->fetch();
    $monthlyId = $row['monthly_id'];
    $date      = $row['date'];

    $lists = getOrderListBeforeFixed($monthlyId);

    if(count($_POST) > 0) fixOrder();
}catch (Exception $e){
    $errors[] = $e->getMessage();
}

function getOrderListBeforeFixed($monthlyId)
{
    $lists = [];
    try {
        $pdo  = connectDb('coop');
        $sql  = "SELECT *, (monthly_goods.unit_price * ordering_list.ordering_quantity) AS total FROM ordering
                 INNER JOIN ordering_list ON ordering.ordering_id           = ordering_list.ordering_id
                 INNER JOIN monthly_goods ON ordering_list.monthly_goods_id = monthly_goods.monthly_goods_id
                 INNER JOIN category      ON monthly_goods.category_id      = category.category_id
                 WHERE ordering.monthly_id = ? ORDER BY monthly_goods.category_id ASC, monthly_goods.monthly_goods_id ASC;"
        ;
        $stmt = $pdo->prepare($sql);
        $res  = $stmt->execute([$monthlyId, ]);
        if(!$res) throw new Exception("Error Processing Request", 1);

        $users = getAllUsers();
        foreach ($stmt as $row)
        {
            $row['user_name'] = '';
            foreach ($users as $user){
                if($row['orderer'] == $user['userid']){
                    $row['user_name'] = $user['userName'];
                    break;
                }
            }
            $lists[] = $row;
        }
    } catch (Exception $e) {
        throw $e;
    }
    return $lists;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>CoopSystem</title>
    <?php include($PATH."/public/assets/php/partial/head.php"); ?>
</head>
<body>
<?php include($PATH."/public/assets/php/partial/header.php"); ?>

<button class="col-btn" col-target="#col-menu"></button>

<div class="flex">
    <div class="col-2 border-right  scroll bg-glay" id="col-menu">
        <?php include($PATH."/public/assets/php/partial/menu_admin.php"); ?>
    </div>
    <div class="col-10 container scroll">
        <h2><?php echo date('Y年n月', strtotime($date)) ?>分の発注を確定する</h2>
        <table class="table-hover border-bottom">
            <thead>
                <th>カテゴリ名</th>
                <th>商品名</th>
                <th>購入者</th>
                <th>内容量</th>
                <th>必要数</th>
                <th>単価</th>
                <th>購入数</th>
                <th>合計金額</th>
            </thead>
            <tbody>
                <?php foreach($lists as $list){ ?>
                <tr>
                    <td class="text-center"><p class="label" style="background: <?php echo $list['color'] ?>; color: <?php echo getFontColor($list['color']) ?>"><?php echo $list['category_name'] ?></p></td>
                    <td class="text-left"  ><?php echo $list['goods_name'] ?></td>
                    <td class="text-center"><?php echo $list['user_name'] ?></td>
                    <td class="text-center"><?php echo $list['detail_amount_per_one'] ?></td>
                    <td class="text-center"><?php echo $list['required_quantity'] ?>個</td>
                    <td class="text-right" ><?php echo number_format(intval($list['unit_price'])) ?>円</td>
                    <td class="text-center"><?php echo $list['ordering_quantity'] ?>個</td>
                    <td class="text-right" ><?php echo number_format(intval($list['total'])) ?>円</td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <form method="post">
            <input type="hidden" name="monthly_id" value="<?php echo $monthlyId ?>">
            <p class="text-right"><button type="submit" class="btn btn-green" name="submit" value="1">確定処理を実行する</button></p>
        </form>
    </div>
</div>
<?php include($PATH."/public/assets/php/partial/footer.php"); ?>
</body>
</html>