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
    if(count($_GET) <= 0)
    {
        echo '<script type="text/javascript">alert("不正なIDが指定されました。再度月を選択してください。"); window.location.href ="../month";</script>';
        exit();
    }
    $pdo   = connectDb('coop');
    $sql   = "SELECT date FROM monthly WHERE monthly_id=?;";
    $stmt  = $pdo->prepare($sql);
    $res   = $stmt->execute([$_GET['id'], ]);
    if(!$res) throw new Exception("DB接続時にエラーが発生しました。");

    $date  = date('Y年n月', strtotime($stmt->fetchColumn()));
    $lists = getOrderListBeforeFixed($_GET['id']);

    if(count($_POST) > 0 && isset($_POST['export']))
    {
        header('location: ./export.php?id='.$_GET['id']);
//        exportCsv($lists, $date, $PATH);
    }
}catch (Exception $e){
    $errors[] = $e->getMessage();
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
        <h2><?php echo $date ?>分のリストを出力する</h2>
        <?php if(count($lists) > 0){ ?>
        <table class="table-hover border-bottom">
            <thead>
                <th>カテゴリ名</th>
                <th>商品名</th>
                <th>購入者</th>
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
            <p class="text-right"><button type="submit" class="btn btn-green" name="export" value="1">リストを出力する</button></p>
        </form>
        <?php }else{ ?>
        <p>対象が存在しないようです。</p>
        <?php } ?>
    </div>
</div>
<?php include($PATH."/public/assets/php/partial/footer.php"); ?>
</body>
</html>