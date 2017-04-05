<?php
include     ('../../public/assets/php/partial/require_common.php');
include     ($PATH.'/public/assets/php/lib/common/sessionCheck.php');

require_once('../../public/assets/php/lib/user/userProcess.php');
$errors = [];
$lists  = [];
$total  = 0;
try {
    $lists = getHistoryMonthlyList($_SESSION['USERID']);
} catch (Exception $e) {
    $errors[] = $e->getMessage();
}
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
    <div class="col-2 border-right  scroll bg-glay" id="col-menu">
        <?php include("../../public/assets/php/partial/menu_user.php"); ?>
    </div>
    <div class="col-10 container scroll">
        <h1>注文した履歴を確認する</h1>
        <table class="table-stripe border-bottom">
            <thead>
                <tr>
                    <th width="35%" class="text-center">購入した月</th>
                    <th width="35%" class="text-right" >合計金額</th>
                    <th width="30%" class="text-center"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lists as $list){ ?>
                <tr>
                    <td class="text-center"><?php echo date('Y年n月', strtotime($list['date'])) ?></td>
                    <td class="text-right" ><?php echo number_format(intval($list['total'])) ?>円</td>
                    <td class="text-center">
                        <?php if(intval($list['total']) > 0){ ?>
                        <a href="./list?id=<?php echo $list['monthly_id'] ?>">内訳を見る</a>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php errorMessages($errors) ?>

    </div>
</div>

<?php include("../../public/assets/php/partial/footer.php"); ?>
</body>
</html>