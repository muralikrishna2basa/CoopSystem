<?php
require_once('../../public/assets/php/connectDb.php');
require_once('../../public/assets/php/convertCsvFileToArray.php');
require_once("../../public/assets/php/lib/administrator/administratorProcess.php");

$extension    = 'nofiles';
$csv          = [];
$errorMessage = [];
$rows = [];
for($i = -3; $i < 3; $i++)
{
    $date = date('Y-m-01');
    if($i <  0) $dateSerial = strtotime($date."{$i} month");
    if($i == 0) $dateSerial = strtotime($date);
    if($i >  0) $dateSerial = strtotime($date."+{$i} month");
    $rows[] = $dateSerial;
}

try {
    if(count($_POST) > 0 && isset($_POST['month']))
    {
        // TODO: monthlyにデータを作成する teshima -> kawanishi 2017/03/31
        // TODO: 生成したデータから月別IDを取得する teshima 2017/03/31
    }
} catch (Exception $e) {
    echo $e->getMessage();
    exit();
}

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
            // CSVファイルを配列に変換
            $csvArray = convertCsvFileToArray('../../public/assets/files/upload.csv');
            // ファイル内容のチェック
            $errorMessage=csvFileCheck($csvArray);
            // 結果をDBに格納
            if($errorMessage==null){
                productListCreation($csvArray,2);
            }
            // アップロードしたファイルを削除
            if(file_exists($filePath)) unlink($filePath);
            // ページ遷移
            header('../productlist/index.php?id=');
        }
        else{
            echo '<script type="text/javascript">alert("CSV形式以外のファイルがアップロードされたようです。");</script>';
            header('./index.php');
        }
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

        <form method="post">
            <select name="month">
                <?php foreach ($rows as $val){ ?>
                <option value="<?php echo date('Ymd', $val); ?>" <?php if(date('Ym') === date('Ym', $val)) echo 'selected' ?>><?php echo date('Y年n月分', $val) ?></option>
                <?php } ?>
            </select>
            <button type="submit" name='submit_month'>月を選択する</button>
        </form>

        <?php if(isset($_POST['submit_month'])){ ?>
        <form method="post" action="" enctype="multipart/form-data">
            <input type="file" name="csv" id="csv">
            <input type="text" name="month" value="<?php echo $_POST['month'] // TODO: 月別IDをセットする teshima 2017/03/31 ?>">
            <button type="submit"
             name="submit_csv"
             class="btn btn-blue"
             onclick="return checkFile();"
             >send</button>
        </form>
        <?php } ?>

        <?php if(count($errorMessage) > 0){ ?>
        <div>
            <?php foreach ($errorMessage as $msg) { ?>
            <p><?php echo $msg; ?></p>
            <?php } ?>
        </div>
        <?php } ?>
    </div>
</div>
<script type="text/javascript">
    function checkFile(){
        if($('#csv')[0].files[0]) return true;
        alert('ファイルが選択されていません。');
        return false;
    }
</script>
<?php include("../../public/assets/php/partial/footer.php"); ?>
</body>
</html>