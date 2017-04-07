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
            <div class="btn-group">
                <button type="submit" name="btn" value="publish" id="publish" class="btn btn-blue">リストを公開する</button>
                <button type="submit" name="btn" value="edit" id="edit" class="btn btn-blue" >リストの編集をする</button>
                <button type="submit" name="btn" value="export" id="export" class="btn btn-blue" >リストを出力する</button>
                <button type="submit" name="btn" value="unfixed" id="unfixed" class="btn btn-red" >確定を解除する</button>
            </div>

            </p>

        </form>

        <?php errorMessages($errors) ?>

    </div>
</div>
<script type="text/javascript">
    $(function(){
        editBtn();
        $('#month_id').change(function(){ editBtn() });
    })

function editBtn()
{
    $('.btn').each(function(){ $(this).show() });
    var public = $('#month_id option:selected').attr('data-public');
    var fixed  = $('#month_id option:selected').attr('data-fixed');
    var cnt    = $('#month_id option:selected').attr('data-cnt');

    // if(cnt == 0)
    // {
    //     $('#publish').hide();
    //     $('#edit').hide();
    //     $('#export').hide();
    //     $('#unfixed').hide();
    // }
    // if(public == 1)
    // {
    //     $('#publish').hide();
    //     $('#edit').hide();
    // }
    // if(fixed == 1)
    // {
    //     $('#edit').hide();
    // }
}
</script>
<?php include($PATH."/public/assets/php/partial/footer.php"); ?>
</body>
</html>