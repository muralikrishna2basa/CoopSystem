<?php
session_start();
require_once('../../public/assets/php/connectDb.php');
if(count($_POST) > 0)
{
    if(isset($_POST['btn']) && $_POST['btn'] === 'publish')
    {
        // TODO: 選択した月のリストを公開する処理 teshima -> kawanishi 2017/03/30
        echo 'publish';
    }
    if(isset($_POST['btn']) && $_POST['btn'] === 'edit_list')
    {
        // 商品リスト編集ページへ遷移
        echo 'edit_list';
    }
    if(isset($_POST['btn']) && $_POST['btn'] === 'edit_admin')
    {
        // 商品リストの注文状況編集ページへ遷移
        echo 'edit_admin';
    }

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
    <div class="col-2 border-right min-height" id="col-menu">
        <h2 class="text-center">** admin menu **</h2>
    </div>
    <div class="col-10 container">
        <pre><?php var_dump($_POST) ?></pre>
        <h2>月を選択して編集する</h2>
        <form method="post" action="" enctype="multipart/form-data">
            <table>
                <tr>
                    <td>
                        <p class="form-group form-trans">
                            <select name="month_id">
                                <?php for($i = -3; $i < 3; $i++){ ?>
                                <?php $date = date('Y-m-1'); $int  = strtotime($date."{$i} month"); if($i === 0) $int = strtotime($date); if($i > 0) $int = strtotime($date."+{$i} month"); ?>
                                <option value="<?php echo date('Ym', $int) ?>" <?php if(date('Ym', $int) === date('Ym')) echo 'selected' ?>><?php echo date('Y年n月', $int) ?></option>
                                <?php } ?>
                            </select>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <button type="submit" name="btn" value="publish" class="btn btn-blue block">選択した月の生協商品リストを公開する</button>
                    </td>
                </tr>
                <tr>
                    <td>
                        <button type="submit" name="btn" value="edit_list" class="btn btn-blue block">選択した月の生協商品リストを編集する</button>
                    </td>
                </tr>
                <tr>
                    <td>
                        <button type="submit" name="btn" value="edit_admin" class="btn btn-red block">選択した月の注文状況を編集する</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<?php include("../../public/assets/php/partial/footer.php"); ?>
</body>
</html>