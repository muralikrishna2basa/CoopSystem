<?php
include     ('../../public/assets/php/partial/require_common.php');
include     ($PATH.'/public/assets/php/lib/common/sessionCheck.php');

require_once($PATH.'/public/assets/php/lib/administrator/administratorProcess.php');
require_once($PATH.'/public/assets/php/auth.php');

$errors = [];
try{
    deleteFaultList();
}catch (Exception $e) {
    $errors[] = $e->getMessage();
}

try {
    $rows = [];
    $publishMonth = '';
    $pdo  = connectDb('coop');

    // monthlyテーブルへの接続
    $sql  = "SELECT  * FROM monthly ORDER BY date DESC LIMIT 5;";
    $stmt = $pdo->prepare($sql);
    $res  = $stmt->execute(null);
    if(!$res)              throw new Exception("monthly_id取得中に予期しないエラーが発生しました。");
    if(count($stmt) === 0) throw new Exception("monthly_idが登録されていないようです。");
    foreach($stmt as $key => $row)
    {
        // monthly_goodsテーブルへの接続
        $sql  = "SELECT COUNT(*) FROM monthly_goods WHERE monthly_id=?;";
        $cnt  = $pdo->prepare($sql);
        $res  = $cnt->execute([$row['monthly_id'], ]);
        if(!$res) throw new Exception("monthly_goods取得中に予期しないエラーが発生しました。");
        $row['cnt'] = (intval($cnt->fetchColumn()));
        if(intval($row['public_flag']) === 1) $publishMonth = $row['date'];
        $rows[] = $row;
    }
} catch (Exception $e) {
    $errors[] = $e->getMessage();
    // echo $e->getMessage();
    // exit();
}

if(count($_POST) > 0 && isset($_POST['btn']))
{
    try {
        // 選択した月のリストを公開する処理
    //    echo 'publish';
        switch ($_POST['btn'])
        {
            case 'publish':
                monthSelectionAndOrderCreation($_POST['month_id']);
                break;
            case 'unfixed':
                unFixOrder($_POST['month_id']);
                break;
            case 'export':
                header("location: ../export/?id={$_POST['month_id']}");
                exit();
                break;
            case 'edit':
                header("location: ../productlist/?id={$_POST['month_id']}");
                exit();
                break;
        }
    header("location: ./");
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
    <div class="col-2 border-right scroll bg-glay" id="col-menu">
        <?php include($PATH."/public/assets/php/partial/menu_admin.php"); ?>
    </div>
    <div class="col-10 container scroll">
        <h2>月を選択して処理をする</h2>
        <form method="post" action="" enctype="multipart/form-data">
            <p class="form-group form-trans form-group-inline">
                <select name="month_id" id="month_id">
                    <?php foreach($rows as $row){ ?>
                    <option
                        value      ="<?php echo $row['monthly_id'] ?>"
                        data-public="<?php echo $row['public_flag'] ?>"
                        data-fixed ="<?php echo $row['fixed_flag'] ?>"
                        data-cnt   ="<?php echo $row['cnt'] ?>"
                        <?php if(intval($row['public_flag']) === 1) echo 'selected' ?>
                    >
                        <?php echo date('Y年n月', strtotime($row['date'])) ?>
                        <?php                              echo ' / '.$row['cnt'].'件' ?>
                        <?php if($row['public_flag'] == 1) echo ' [ 公開中 ]' ?>
                        <?php if($row['fixed_flag']  == 1) echo " [ 確定済 ]" ?>
                    </option>
                    <?php } ?>
                    <?php if(count($rows) === 0){ ?>
                    <option>公開できる月が存在しません。</option>
                    <?php } ?>
                </select>
            <div class="btn-group" style="margin-bottom: 10px">
                <button type="submit" name="btn" value="publish" id="publish" class="btn btn-blue tips-trigger">
                    <span>リストを公開する</span>
                    <span class="tips-target">選択した月のリストを公開します。確定済みのリストは公開しても注文できない点にご留意ください。</span>
                </button>
                <button type="submit" name="btn" value="edit" id="edit" class="btn btn-blue tips-trigger">
                    <span>リストの編集をする</span>
                    <span class="tips-target">リストの編集を行います。確定前・公開前のリストのみ修正が行えます。</span>
                </button>
                <button type="submit" name="btn" value="export" id="export" class="btn btn-blue tips-trigger">
                    <span>リストを出力する</span>
                    <span class="tips-target">ユーザーの注文リストをCSV形式で出力できます。</span>
                </button>
            </div>
            <div class="btn-group">
                <button type="submit" name="btn" value="unfixed" id="unfixed" class="btn btn-red tips-trigger">
                    <span>確定を解除する</span>
                    <span class="tips-target">確定を解除します。確定解除後はリストの編集が行えるようになります。</span>
                </button>
                <button type="submit" name="btn" value="delete" id="delete" class="btn btn-red tips-trigger">
                    <span>在庫が0のものを削除する</span>
                    <span class="tips-target">在庫が存在しない商品を削除します。一度削除したデータは復元できない点にご注意ください。</span>
                </button>
            </div>
            </p>
        </form>
        <?php errorMessages($errors) ?>
    </div>
    <p class="tips-btn"></p>
    <div class="tips-content"><h3 class="text-center">ここにヒントが表示されます。</h3></div>
</div>
<script type="text/javascript">
    $(function(){
        editBtn();
        $('#month_id').change(function(){ editBtn() });
    })

function editBtn()
{
    $('.btn').each(function(){ $(this).prop('disabled', false) });
    var public = $('#month_id option:selected').attr('data-public');
    var fixed  = $('#month_id option:selected').attr('data-fixed');
    var cnt    = $('#month_id option:selected').attr('data-cnt');

    if(cnt == 0)
    {
        $('#publish').prop('disabled', true);
        $('#edit').prop('disabled', true);
        $('#export').prop('disabled', true);
        $('#unfixed').prop('disabled', true);
        $('#unfixed').prop('disabled', true);
    }
    if(public == 1)
    {
        $('#publish').prop('disabled', true);
        $('#edit').prop('disabled', true);
    }
    if(fixed == 1)
    {
        $('#edit').prop('disabled', true);
    }
}
</script>
<?php include($PATH."/public/assets/php/partial/footer.php"); ?>
</body>
</html>