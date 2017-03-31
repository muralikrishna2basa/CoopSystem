<?php
require_once('../../public/assets/php/connectDb.php');
require_once('../../public/assets/php/convertCsvFileToArray.php');
$extension = 'nofiles';
$csv       = [];
try
{
    // ファイルがセットされたかの判定
    if(count($_FILES) > 0 && is_uploaded_file($_FILES['csv']['tmp_name']))
    {
        // ファイル拡張子がCSVであるか判定
        $extension = mb_substr($_FILES['csv']['name'], -3);
        if(strtolower($extension) === 'csv')
        {
            // tmpファイルを正式にアップロード
            $filePath = '../../public/assets/files/upload.csv';
            move_uploaded_file($_FILES['csv']['tmp_name'], $filePath);
            chmod($filePath, 0644);
        }
        else{
            echo '<script type="text/javascript">alert("CSV形式以外のファイルがアップロードされたようです。");</script>';
        }
        // CSVファイルを配列に変換
        $csv = convertCsvFileToArray('../../public/assets/files/upload.csv');
        // ファイル内容のチェック
        $errorMessage=csvFileCheck($csvArray);
        // 結果をDBに格納
        if($errorMessage==null){
            productListCreation($csvArray,2);
        }
        // アップロードしたファイルを削除
        if(file_exists($filePath)) unlink($filePath);
        // ページ遷移
    }
}catch (Exception $e)
{
    echo 'エラー：'.$e->getMessage();
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
        <?php include("../../public/assets/php/partial/menu_admin.php"); ?>
    </div>
    <div class="col-10 container">
        <pre><?php var_dump($csv) ?></pre>
        <form method="post" action="" enctype="multipart/form-data">
            <input type="file" name="csv">
            <button type="submit" class="btn btn-blue">send</button>
        </form>
    </div>
</div>

<?php include("../../public/assets/php/partial/footer.php"); ?>
</body>
</html>