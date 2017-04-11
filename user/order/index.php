<?php
include     ('../../public/assets/php/partial/require_common.php');
include     ($PATH.'/public/assets/php/lib/common/sessionCheck.php');

require_once($PATH.'/public/assets/php/lib/user/userProcess.php');
$errors     = [];
$lists      = [];
$i          = 0;
$priceTotal = 0;
$date       = '';
$fixed      = 0;
$orderBtn   = '';
$orderState = '';
try {
    $pdo        = connectDb('coop');
    // monthlyテーブルに接続
    $sql  = "SELECT * FROM monthly WHERE public_flag=1;";
    $stmt = $pdo->prepare($sql);
    $res  = $stmt->execute(null);
    if(!$res) throw new Exception("[order]:DB接続時にエラーが発生しました。");
    $tmp = $stmt->fetch();
    $date  = $tmp['date'];
    $fixed = intval($tmp['fixed_flag']);

    // 表示用リスト作成
    $lists = returnCurrentMonthProductList($_SESSION['USERID']);

    // orderingテーブルに接続して今月の注文状況を取得
    $sql = "SELECT order_flag FROM ordering
            WHERE monthly_id=(SELECT monthly_id FROM monthly WHERE public_flag=1 LIMIT 1)
            AND orderer=?;"
    ;
    $stmt = $pdo->prepare($sql);
    $res  = $stmt->execute([$_SESSION['USERID']]);
    if(!$res) throw new Exception("[order]:DB接続時にエラーが発生しました。");
    switch(intval($stmt->fetchColumn()))
    {
        case 0:
        case 1:
            $orderBtn   = "<button type=\"submit\" name=\"order\" value=\"0\" class=\"tips-trigger btn btn-red\">
            <span>いいえ、今月は注文しません</span>
            <span class=\"tips-target\">今月注文されない方はこちらを押してください。</span></button>";
            $orderState = "<p class=\"label bg-yellow\">今月の注文はしていません</p>";
            break;
        case 2:
            $orderState = "<p class=\"label bg-red\">今月は注文をしません</p>";
            break;
        case 3:
            $orderState = "<p class=\"label bg-green\">今月の注文をしました</p>";
            break;
    }
    //priceTotalにＤＢ上にあるユーザーの注文金額の合計を表示
    $sql = "SELECT SUM( unit_price * ordering_quantity )
            FROM ordering_list
            INNER JOIN monthly_goods ON ordering_list.monthly_goods_id = monthly_goods.monthly_goods_id
            INNER JOIN ordering ON ordering_list.ordering_id = ordering.ordering_id
            INNER JOIN category ON monthly_goods.category_id = category.category_id
            INNER JOIN monthly ON ordering.monthly_id = monthly.monthly_id
            WHERE ordering.orderer =?
            AND monthly.monthly_id =(SELECT monthly_id FROM monthly WHERE public_flag=1 LIMIT 1)";
    $stmt = $pdo->prepare($sql);
    $res  = $stmt->execute([$_SESSION['USERID']]);
    $priceTotal = intval($stmt->fetchColumn());
} catch (Exception $e) {
    $errors[] = $e->getMessage();
}

if(count($_POST) > 0){
    try {
        if($_POST['order'] == 1) $errors = currentMonthListFromOrderWhenNewlyDetermineWhether($_SESSION['USERID'], $_POST);
        if($_POST['order'] == 0) noOrder($_SESSION['USERID']);
        if(count($errors) === 0) header('location: ./?page='.$_GET['page']);
    } catch (Exception $e) {
        $errors[] = $e->getMessage();
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
    <?php include($PATH."/public/assets/php/partial/head.php"); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo $URL ?>/public/assets/stylesheets/users.css">
    <script src="<?php echo $URL ?>/public/assets/js/loading.js"></script>
</head>
<body>
<?php include($PATH."/public/assets/php/partial/header.php"); ?>
<button class="col-btn" col-target="#col-menu"></button>

<div class="flex">
    <div class="col-2 border-right scroll bg-glay" id="col-menu">
        <?php include($PATH."/public/assets/php/partial/menu_user.php"); ?>
    </div>
    <div class="col-10 container scroll">
        <h1><?php echo date('Y年n月', strtotime($date)) ?>分の生協商品を注文する</h1>
        <form method="post" action="">
        <table class="table-hover border-bottom">
            <thead>
                <tr>
                    <th width="10%" class="text-center">No</th>
                    <th width="10%" class="text-center">カテゴリ</th>
                    <th width="30%"                    >商品名</th>
                    <th width="10%" class="text-center">必要数</th>
                    <th width="10%" class="text-right" >単価</th>
                    <th width="15%" class="text-center">購入数</th>
                    <th width="15%" class="text-right" >合計金額</th>
                </tr>
            </thead>
            <tbody>
                <?php //foreach ($lists as $list){ ?>
                <?php for($i = $page; $i < ($maxPage); $i++){ ?>
                <tr>
                    <td class="text-center"><?php echo $i+1 ?></td>
                    <td class="text-center">
                        <p class="label" style="background: <?php echo $lists[$i]['color'] ?>; color: <?php echo getFontColor($lists[$i]['color']) ?>;">
                            <?php echo $lists[$i]['category_name'] ?>
                        </p>
                        <input type="hidden" id="monthly_goods_id_<?php echo $lists[$i]['monthly_goods_id'] ?>" name="monthly_goods_id[]" value="<?php echo $lists[$i]['monthly_goods_id']; ?>">
                    </td>
                    <td>
                        <p><?php echo $lists[$i]['goods_name'] ?></p>
                    </td>
                    <td class="text-center">
                        <p><?php echo $lists[$i]['required_quantity'] ?>個</p>
                    </td>
                    <td class="text-right">
                        <p>
                            <span id="unit_price_<?php echo $lists[$i]['monthly_goods_id'] ?>"><?php echo number_format(intval($lists[$i]['unit_price'])) ?></span>
                            <span>円</span>
                        </p>
                    </td>
                    <td class="text-center">

                        <p
                            data-price  ="unit_price_<?php echo $lists[$i]['monthly_goods_id'] ?>"
                            data-total  ="total_<?php echo $lists[$i]['monthly_goods_id'] ?>"
                            data-number ="ordering_quantity_<?php echo $lists[$i]['monthly_goods_id'] ?>"
                            data-display="display_number_<?php echo $lists[$i]['monthly_goods_id'] ?>"
                        >
                            <?php if($fixed !== 1){ ?>
                            <button class="ordering-minus">&minus;</button>
                            <?php } ?>

                            <span id="display_number_<?php echo $lists[$i]['monthly_goods_id'] ?>"><?php echo intval($lists[$i]['ordering_quantity']) ?></span>個

                            <?php if($fixed !== 1){ ?>
                            <button class="ordering-plus">+</button>
                            <?php } ?>
                        </p>
                        <input type="hidden" id="initial_ordering_quantity_<?php echo $lists[$i]['monthly_goods_id'] ?>" name="initial_ordering_quantity[]" value="<?php echo $lists[$i]['ordering_quantity'] ?>">
                        <input type="hidden" id="ordering_quantity_<?php echo $lists[$i]['monthly_goods_id'] ?>"         name="ordering_quantity[]"         value="<?php echo $lists[$i]['ordering_quantity'] ?>">
                    </td>
                    <td class="text-right">
                        <p>
                            <?php $total = intval($lists[$i]['ordering_quantity']) * intval($lists[$i]['unit_price']) ?>
                            <span id="total_<?php echo $lists[$i]['monthly_goods_id'] ?>"><?php echo number_format($total) ?></span>
                            <span>円</span>
                        </p>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php setPages('./?', floor(count($lists) / $num), $nowPage) ?>

        <p class="text-right form-group btn-group">
            <?php if($fixed !== 1){ ?>
            <button type="submit" name="order" value="1" class="tips-trigger btn btn-blue">
            <span>注文します</span>
            <span class="tips-target">現在のページで選択した商品を注文します。ページごとに注文してください。</span>
            </button>
            <?php echo $orderBtn //今月は注文しませんボタンを表示 ?>
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
                            <?php echo $orderState ?>
                        </td>
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

        <?php errorMessages($errors) ?>

    </div>
   
</div>
<?php include($PATH."/public/assets/php/partial/tips.php"); ?>
</div>
<script src="<?php echo $URL ?>/public/assets/js/users.js"></script>
<?php include($PATH."/public/assets/php/partial/footer.php"); ?>
</body>
</html>