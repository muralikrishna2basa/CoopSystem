<?php
include     ('../../public/assets/php/partial/require_common.php');
include     ($PATH.'/public/assets/php/lib/common/sessionCheck.php');

require_once($PATH.'/public/assets/php/convertCsvFileToArray.php');
require_once($PATH."/public/assets/php/lib/administrator/administratorProcess.php");

$errors = [];
$lists  = [];
/**
 * 月別IDのチェック処理
 * idがセットされており、かつ月別IDが存在し、かつ確定されていないときのみ処理を通す
 */
try {
    $pdo    = connectDb('coop');
    if(count($_GET) <= 0){
        throw new Exception("月が選択されていないようです。再度月選択からやり直してください。");
    }else{
        $sql  = "SELECT fixed_flag FROM monthly WHERE monthly_id=?;";
        $stmt = $pdo->prepare($sql);
        $res  = $stmt->execute([$_GET['id'],]);
        $flag = $stmt->fetchColumn();
        if(!$res)           throw new Exception("monthlyDB接続時に予期しないエラーが発生しました。");
        if($flag === false) throw new Exception("指定したmonthly_idは存在しないようです。");
        $flag = intval($flag);
        if($flag === 1)     throw new Exception("指定した月は既に確定されているため、リストの修正は行えません。");

        $lists = productListDisplay($_GET['id']);

        $sql        = "SELECT * FROM category;";
        $categories = $pdo->prepare($sql);
        $res        = $categories->execute(null);
        if(!$res) throw new Exception("categoryDB接続時に予期しないエラーが発生しました。");
    }
}catch (Exception $e){
    $errors[] = $e->getMessage();
}

if(count($_POST) > 0){
    try {
        productListEdit($_POST);
        header("location: ./index.php?id={$_GET['id']}");
    } catch (Exception $e) {
        $errors[] = $e->getMessage();
    }
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
        <?php include("../../public/assets/php/partial/menu_admin.php"); ?>
    </div>
    <div class="col-10 container scroll bg-glay">
        <h2>生協商品リストを修正する</h2>
        <form method="post">
            <table class="border-bottom table-hover">
                <thead>
                    <tr>
                        <th>商品名</th>
                        <th>内容量</th>
                        <th>必要数</th>
                        <th>単価</th>
                        <th>カテゴリ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lists as $list){ ?>
                    <tr>
                        <td>
                            <input type="hidden" name="monthly_goods_id[]" value="<?php echo $list['monthly_goods_id'] ?>">
                            <input type="hidden" name="monthly_id[]"       value="<?php echo $list['monthly_id'] ?>">
                            <p class="form-group form-trans">
                                <input type="text" name="goods_name[]"     value="<?php echo $list['goods_name'] ?>">
                            </p>
                        </td>
                        <td>
                            <p class="form-group form-trans">
                                <input type="text" name="detail_amount_per_one[]" value="<?php echo $list['detail_amount_per_one'] ?>">
                            </p>
                        </td>
                        <td>
                            <p class="form-group form-trans">
                                <input type="text" name="required_quantity[]" value="<?php echo $list['required_quantity'] ?>">
                            </p>
                        </td>
                        <td>
                            <p class="form-group form-trans">
                                <input type="text" name="unit_price[]" value="<?php echo $list['unit_price'] ?>">
                            </p>

                        </td>
                        <td>
                            <p href="" class="btn btn-blue modal-btn" modal-target="#modal-category" data-target="#category_id_<?php echo $list['monthly_goods_id'] ?>">カテゴリを選択する</p>
                            <p class="form-group form-trans form-group-inline">
                                <input type="text" name="category_id[]" id="category_id_<?php echo $list['monthly_goods_id'] ?>" value="<?php echo $list['category_id'] ?>">
                            </p>
                        </td>

                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <p><button type="submit" class="btn btn-blue">送信する</button></p>
        </form>

        <div id="modal-category" class="modal-hide">
            <div class="modal-header bg-blue">
                <h2>modal window</h2>
            </div>
            <div class="modal-body">
                <h2>カテゴリを選択する</h2>
                <?php foreach ($categories as $category) { ?>
                <span class="label" style="background: <?php echo $category['color'] ?>; color: <?php echo getFontColor($category['color']); ?>"><?php echo $category['category_name'] ?></span>
                <?php } ?>
            </div>
            <div class="modal-footer">
                <h6>footer</h6>
            </div>
        </div>



        <?php errorMessages($errors) ?>
    </div>
</div>


<script type="text/javascript">
    
</script>
<?php include($PATH."/public/assets/php/partial/footer.php"); ?>
</body>
</html>