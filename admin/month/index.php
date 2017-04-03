<?php
include     ('../../public/assets/php/partial/require_common.php');
include     ($PATH.'/public/assets/php/lib/common/sessionCheck.php');

require_once($PATH.'/public/assets/php/lib/administrator/administratorProcess.php');
require_once($PATH.'/public/assets/php/auth.php');

try {
    $pdo  = connectDb('coop');
    $sql  = "SELECT  * FROM monthly ORDER BY date DESC LIMIT 5;";
    $stmt = $pdo->prepare($sql);
    $res  = $stmt->execute(null);
    $rows = [];
    $publishMonth = '';
    if(!$res)              throw new Exception("monthly_id取得中に予期しないエラーが発生しました。");
    if(count($stmt) === 0) throw new Exception("monthly_idが登録されていないようです。");
    foreach($stmt as $key => $row)
    {
        $rows[] = $row;
        if(intval($row['public_flag']) === 1) $publishMonth = $row['date'];
    }
} catch (Exception $e) {
    echo $e->getMessage();
    exit();
}

if(count($_POST) > 0 && isset($_POST['btn']) && $_POST['btn'] === 'publish')
{
    // 選択した月のリストを公開する処理
    echo 'publish';
    monthSelectionAndOrderCreation($_POST['month_id']);
    header('location: ./index.php');
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
    <div class="col-2 border-right min-height" id="col-menu">
        <?php include($PATH."/public/assets/php/partial/menu_admin.php"); ?>
    </div>
    <div class="col-10 container">
        <h2>月を選択して公開する</h2>
        <form method="post" action="" enctype="multipart/form-data">
            <table class="border-none">
                <tr>
                    <td class="text-center"><p>現在公開されている月</p></td>
                    <td class="text-center"><h2><?php echo mb_strlen($publishMonth) > 0 ? date('Y年n月', strtotime($publishMonth)) : '公開されていません' ?></h2></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-center">
                        <p>公開する月</p>
                    </td>
                    <td class="text-center">
                        <p class="form-group form-trans">
                            <select name="month_id">
                                <?php foreach($rows as $row){ ?>
                                <option value="<?php echo $row['monthly_id'] ?>" <?php if(intval($row['public_flag']) === 1) echo 'selected' ?>>
                                    <?php echo date('Y年n月', strtotime($row['date'])) ?>
                                </option>
                                <?php } ?>
                            </select>
                        </p>
                    </td>
                    <td>
                        <button type="submit" name="btn" value="publish" class="btn btn-blue block">選択した月の生協商品リストを公開する</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<?php include($PATH."/public/assets/php/partial/footer.php"); ?>
</body>
</html>