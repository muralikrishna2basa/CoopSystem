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
    $pdo  = connectDb('coop');
    $sql  = "SELECT MIN(monthly_id) AS monthly_id, date FROM monthly WHERE fixed_flag = 0;";
    $stmt = $pdo->prepare($sql);
    $res  = $stmt->execute(null);
    if(!$res) throw new Exception("monthly_id取得時にエラーが発生しました。");

    $row       = $stmt->fetch();
    $monthlyId = $row['monthly_id'];
    $date      = $row['date'];

    $lists = orderAggregate($monthlyId);
    

    if(count($_POST) > 0)
    {
        fixOrder($_POST['monthly_id']);
        echo '<script type="text/javascript">alert("確定が完了しました。"); window.location.href ="./?page='.$_GET['page'].'";</script>';
    }
}catch (Exception $e){
    $errors[] = $e->getMessage();
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
        <h2><?php echo date('Y年n月', strtotime($date)) ?>分の発注を確定する</h2>
        <?php if(count($lists) > 0){ ?>
        <table class="table-hover border-bottom">
            <thead>
                <th class="text-center">No</th>
                <th class="text-center">カテゴリ名</th>
                <th class="text-left">商品名</th>
                <th class="text-center">必要数</th>
                <th class="text-right">購入数</th>
                <th class="text-right">単価</th>
                <th class="text-right">合計金額</th>
            </thead>
            <tbody>
                <?php for($i = $page; $i < ($maxPage); $i++){ ?>
                <?php //foreach($lists as $list){ ?>
                <tr>
                    <td class="text-center"><?php echo $i+1 ?></td>
                    <td class="text-center"><p class="label" style="background: <?php echo $lists[$i]['color'] ?>; color: <?php echo getFontColor($lists[$i]['color']) ?>"><?php echo $lists[$i]['category_name'] ?></p></td>
                    <td class="text-left"  ><?php echo $lists[$i]['goods_name'] ?></td>
                    <td class="text-center"><?php echo $lists[$i]['required_quantity'] ?>個</td>
                    <td class="text-right" ><?php echo $lists[$i]['total_ordering_quantity'] ?>個</td>
                    <td class="text-right" ><?php echo number_format(intval($lists[$i]['unit_price'])) ?>円</td>
                    <td class="text-right" ><?php echo number_format(intval($lists[$i]['total_amount'])) ?>円</td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php setPages('./?', floor(count($lists) / $num), $nowPage) ?>

        <form method="post">
            <input type="hidden" name="monthly_id" value="<?php echo $monthlyId ?>">
            <p class="text-right">
                <button type="submit" class="btn btn-green tips-trigger" name="submit" value="1">
                    <span>確定処理を実行する</span>
                    <span class="tips-target">確定します。確定後は商品の発注・編集を行うことはできなくなります。</span>
                </button>
            </p>
        </form>
        <?php }else{ ?>
        <p>確定する対象が存在しないようです。</p>
        <?php } ?>
        <?php errorMessages($errors) ?>
    </div>
    <?php include($PATH."/public/assets/php/partial/tips.php"); ?>
</div>
<?php include($PATH."/public/assets/php/partial/footer.php"); ?>
</body>
</html>