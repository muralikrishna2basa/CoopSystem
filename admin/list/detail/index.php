<?php
include     ('../../../public/assets/php/partial/require_common.php');
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
    $sql  = "SELECT monthly_id,date FROM monthly WHERE monthly_id = ?;";
    $stmt = $pdo->prepare($sql);
    $res  = $stmt->execute([$_GET['id']]);
    if(!$res) throw new Exception("monthly_id取得時にエラーが発生しました。");

    $row       = $stmt->fetch();
    $monthlyId = $row['monthly_id'];
    $date      = $row['date'];
    if($_GET['list']==0){
        $lists = orderAggregate($monthlyId);
    }
    if($_GET['list']==1){
        $lists = getOrderListBeforeFixed($monthlyId);
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
?>
<!DOCTYPE html>
<html>
<head>
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
        <h2><?php echo date('Y年n月', strtotime($date)) ?>分の詳細</h2>
        <form action="" method="get">
            <input type="hidden" name="page" value="1">
            <input type="hidden" name="id" value=<?php echo $monthlyId ?>>
            <div class="btn-group">
                <button type="submit" name="list" value="1" class="btn btn-green tips-trigger">
                    <span>ユーザー名でソート</span>
                    <span class="tips-target">ユーザー名でソートします。</span>
                </button>
                <button type="submit" name="list" value="0" class="btn btn-green tips-trigger">
                    <span>商品名でソート</span>
                    <span class="tips-target">商品名でソートします。</span>
                </button>
            </div>
        </form>
        <?php if(count($lists) > 0){ ?>
        <table class="table-hover border-bottom">
            <thead>
            <?php if($_GET['list']==0) {?>
                <th class="text-center">No</th>
                <th class="text-center">カテゴリ名</th>
                <th class="text-left">商品名</th>
                <th class="text-center">必要数</th>
                <th class="text-right">購入数</th>
                <th class="text-right">単価</th>
                <th class="text-right">合計金額</th>
                <?php } ?>
                <?php if($_GET['list']==1) {?>
                <th class="text-center">No</th>
                <th class="text-center">カテゴリ名</th>
                <th class="text-left">商品名</th>
                <th class="text-center">必要数</th>
                <th class="text-right">購入数</th>
                <th class="text-right">単価</th>
                <th class="text-right">合計金額</th>
                <th class="text-right">ユーザー名</th>
                <?php } ?>
            </thead>
            <tbody>
            <?php if($_GET['list']==0) {?>
                <?php for($i = $page; $i < ($maxPage); $i++){ ?>
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
                <?php } ?>
                <?php if($_GET['list']==1) {?>
                <?php for($i = $page; $i < ($maxPage); $i++){ ?>
                <tr>
                    <td class="text-center"><?php echo $i+1 ?></td>
                    <td class="text-center"><p class="label" style="background: <?php echo $lists[$i]['color'] ?>; color: <?php echo getFontColor($lists[$i]['color']) ?>"><?php echo $lists[$i]['category_name'] ?></p></td>
                    <td class="text-left"  ><?php echo $lists[$i]['goods_name'] ?></td>
                    <td class="text-center"><?php echo $lists[$i]['required_quantity'] ?>個</td>
                    <td class="text-right" ><?php echo $lists[$i]['ordering_quantity'] ?>個</td>
                    <td class="text-right" ><?php echo number_format(intval($lists[$i]['unit_price'])) ?>円</td>
                    <td class="text-right" ><?php echo number_format(intval($lists[$i]['total'])) ?>円</td>
                     <td class="text-right" ><?php echo $lists[$i]['user_name'] ?></td>
                </tr>
                <?php } ?>
                <?php } ?>
            </tbody>
        </table>
        <?php setPages("./?list={$_GET['list']}&id={$monthlyId}&", floor(count($lists) / $num), $nowPage) ?>
        <?php }else{ ?>
        <p>リストが存在しないようです。</p>
        <?php } ?>
        <?php errorMessages($errors) ?>
    </div>
    <?php include($PATH."/public/assets/php/partial/tips.php"); ?>
</div>
<?php include($PATH."/public/assets/php/partial/footer.php"); ?>
</body>
</html>