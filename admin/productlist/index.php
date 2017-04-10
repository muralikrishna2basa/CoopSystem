<?php
include     ('../../public/assets/php/partial/require_common.php');
include     ($PATH.'/public/assets/php/lib/common/sessionCheck.php');

require_once($PATH.'/public/assets/php/convertCsvFileToArray.php');
require_once($PATH."/public/assets/php/lib/administrator/administratorProcess.php");

$errors     = [];
$lists      = [];
$categories = [];
/**
 * 月別IDのチェック処理
 * idがセットされており、かつ月別IDが存在し、かつ確定されていないときのみ処理を通す
 */
try {
    $pdo    = connectDb('coop');
//    deleteFaultList();
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
        if(isset($_POST['delete']))    productListOneDeleting($_POST['delete']);
        if(isset($_POST['deleteAll'])) productListAllDeleting($_GET['id']);
        if(isset($_POST['update']))    productListEdit($_POST);

        header("location: ./index.php?id={$_GET['id']}");
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
    <div class="col-10 container scroll">
        <h2>生協商品リストを修正する</h2>
        <?php if(count($lists) > 0){ ?>
        <form method="post">
            <table class="border-bottom table-hover">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>商品名</th>
                        <th>必要数</th>
                        <th>単価</th>
                        <th class="text-center">カテゴリ</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php //foreach ($lists as $list){ ?>
                    <?php for($i = $page; $i < ($maxPage); $i++){ ?>
                    <tr>
                        <td class="text-center"><?php echo $i ?></td>
                        <td>
                            <input type="hidden" name="monthly_goods_id[]" value="<?php echo $lists[$i]['monthly_goods_id'] ?>">
                            <input type="hidden" name="monthly_id[]"       value="<?php echo $lists[$i]['monthly_id'] ?>">
                            <p class="form-group form-trans">
                                <input type="text" name="goods_name[]"     value="<?php echo $lists[$i]['goods_name'] ?>">
                            </p>
                        </td>
                        <td>
                            <p class="form-group form-trans">
                                <input type="text" name="required_quantity[]" value="<?php echo $lists[$i]['required_quantity'] ?>">
                            </p>
                        </td>
                        <td>
                            <p class="form-group form-trans">
                                <input type="text" name="unit_price[]" value="<?php echo $lists[$i]['unit_price'] ?>">
                            </p>

                        </td>
                        <td class="text-center">
                            <p>
                                <span class="label" id="label_<?php echo $lists[$i]['monthly_goods_id'] ?>" style="background: <?php echo $lists[$i]['color'] ?>; color: <?php echo getFontColor($lists[$i]['color']) ?>"><?php echo $lists[$i]['category_name'] ?></span>
                            </p>
                            <a  href        =""
                                class       ="modal-btn btn-sm tips-trigger"
                                modal-target="#modal-category"
                                data-target ="#category_id_<?php echo $lists[$i]['monthly_goods_id'] ?>"
                                label-target="#label_<?php echo $lists[$i]['monthly_goods_id'] ?>"
                            >
                                <span>カテゴリを選択する</span>
                                <span class="tips-target">登録されているカテゴリから該当するものを選択してください。</span>
                            </a>
                            <input type="hidden" name="category_id[]" id="category_id_<?php echo $lists[$i]['monthly_goods_id'] ?>" value="<?php echo $lists[$i]['category_id'] ?>">
                        </td>
                        <td>
                            <button type="submit" name="delete" value="<?php echo $lists[$i]['monthly_goods_id'] ?>" class="btn btn-red tips-trigger"  onclick="return confirm('データを削除してよろしいですか？');">
                                <span>削除する</span>
                                <span class="tips-target">選択した商品をリスト上から削除します。削除した商品は復元できないので注意してください。</span>
                            </button>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php if(count($lists) > 0){ ?>
            <?php setPages('./?id=1', floor(count($lists) / $num), $nowPage) ?>
            <p class="text-right btn-group">
                <button type="submit" name="update" class="btn btn-blue tips-trigger">
                    <span>更新する</span>
                    <span class="tips-target">一括で商品リストを更新します。更新はページ単位で行ってください。</span>
                </button>
                <button type="submit" name="deleteAll" class="btn btn-red tips-trigger" onclick="return confirm('表示されている全てのデータが削除されますが本当によろしいですか？');">
                    <span>月のリストを全て削除する</span>
                    <span class="tips-target">表示されているリストを一括で削除します。削除したデータは復元できませんのでご注意ください。</span>
                </button>
            </p>
            <?php } ?>
        </form>
        <?php }else{ ?>
        <p>対象が存在しないようです。</p>
        <?php } ?>

        <?php //setPages('./?id=1', floor(count($lists) / $num), $nowPage) ?>

        <?php errorMessages($errors) ?>
    </div>
 <?php include($PATH."/public/assets/php/partial/tips.php"); ?></div>

<div id="modal-category" class="modal-hide">
    <div class="modal-header bg-blue">
        <h2>カテゴリ選択画面</h2>
    </div>
    <div class="modal-body">
        <h2>カテゴリを選択する</h2>
        <?php foreach ($categories as $category) { ?>
        <span
            class  ="label select modal-close-btn"
            style  ="background: <?php echo $category['color'] ?>; color: <?php echo getFontColor($category['color']); ?>"
            data-id="<?php echo $category['category_id'] ?>"
        ><?php echo $category['category_name'] ?></span>
        <?php } ?>
    </div>
    <div class="modal-footer">
        <h6>　</h6>
    </div>
</div>

<script type="text/javascript">
$(function(){
var dataTrg = '';
var labelTrg= '';
    $('.modal-btn').click(function(){ dataTrg = $(this).attr('data-target'); labelTrg = $(this).attr('label-target') });
    $(document).on('click', '.select', function(){
        var style = $(this).attr('style');
        var id    = $(this).attr('data-id');
        var name  = $(this).html();
        $(labelTrg).attr('style', style).html(name);
        $(dataTrg).attr('value', id);
    });
})
</script>
<?php include($PATH."/public/assets/php/partial/footer.php"); ?>
</body>
</html>