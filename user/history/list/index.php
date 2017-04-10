<?php
include     ('../../../public/assets/php/partial/require_common.php');
include     ($PATH.'/public/assets/php/lib/common/sessionCheck.php');

require_once($PATH.'/public/assets/php/lib/user/userProcess.php');
$errors = [];
$lists  = [];
$total  = 0;
$date   = 0;
//var_dump($_GET);
if(count($_GET) <= 0)
{
    echo '
        <script type="text/javascript">
            alert("エラーが発生したため明細を取得できませんでした。\n再度、一覧から選択してください。");
            window.location.href = "../";
        </script>
    ';
    exit();
}
try {
    $pdo   = connectDb('coop');
    $lists = displayHistory($_SESSION['USERID'], $_GET['id']);
    $sql   = "SELECT date FROM monthly WHERE monthly_id = ?;";
    $stmt  = $pdo->prepare($sql);
    $res   = $stmt->execute([$_GET['id'],]);
    $date  = date('Y年n月', strtotime($stmt->fetchColumn()));
    if(!$res) throw new Exception("[index]:SELECT文実行時にエラーが発生しました。");
} catch (Exception $e) {
    $errors[] = $e->getMessage();
}
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
        <?php include($PATH."/public/assets/php/partial/menu_user.php"); ?>
    </div>
    <div class="col-10 container scroll">
        <h1><?php echo $date ?>分の購入商品を見る</h1>
        <?php if(count($lists) > 0){ ?>
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
            </tbody>
        </table>
        <div class="flex">
            <div class="col-7"></div>
            <div class="draggable border-radius col-5 bg-white opacity-8">
                <table class="border-none">
                    <tr>
                        <td class="text-center">
                            <p>総合計金額</p>
                        </td>
                        <td>
                            <p><span><?php echo number_format($total) ?></span>円</p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <?php }else{ ?>
        <p>履歴はありません。</p>
        <?php } ?>

        <?php errorMessages($errors) ?>

    </div>
</div>

<?php include($PATH."/public/assets/php/partial/footer.php"); ?>
</body>
</html>