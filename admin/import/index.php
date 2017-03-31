<?php
include     ('../../public/assets/php/partial/require_common.php');
include     ($PATH.'/public/assets/php/lib/common/sessionCheck.php');

require_once('../../public/assets/php/convertCsvFileToArray.php');
require_once("../../public/assets/php/lib/administrator/administratorProcess.php");

$extension    = 'nofiles';
$displayMonth = '月を選択する';
$monthlyId    = '';
$csv          = [];
$errorMessage = [];
$rows         = [];
for($i = -3; $i < 3; $i++)
{
    $date = date('Y-m-01');
    if($i <  0) $dateSerial = strtotime($date."{$i} month");
    if($i == 0) $dateSerial = strtotime($date);
    if($i >  0) $dateSerial = strtotime($date."+{$i} month");
    $rows[] = $dateSerial;
}

if(count($_POST) > 0 && isset($_POST['month']))
{
    try {
        // monthlyにデータを作成して、月別IDを取得する
        $monthlyId    = monthlyIdGeneration($_POST['month']);
        $displayMonth = date('Y年n月が選択されています', strtotime($_POST['month']));
    } catch (Exception $e) {
        $errorMessage[] = $e->getMessage();
        exit();
    }
}

if(isset($_POST['monthlyId'])) $monthlyId = intval($_POST['monthlyId']);

// ファイルがセットされたかの判定
if(count($_FILES) > 0 && is_uploaded_file($_FILES['csv']['tmp_name']))
{
    try
    {
        // ファイル拡張子がCSVであるか判定
        $extension = mb_substr($_FILES['csv']['name'], -3);
        if(strtolower($extension) !== 'csv')
        {
            echo '<script type="text/javascript">alert("CSV形式以外のファイルがアップロードされたようです。");</script>';
            throw new Exception("ファイル形式が誤っています。");
        }
        // 月別IDがPOSTにされているか判定
        if(!isset($_POST['monthlyId'])) throw new Exception("月が選択されていないようです。");

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
            productListCreation($csvArray, $_POST['monthlyId']);
        }
        // アップロードしたファイルを削除
        if(file_exists($filePath)) unlink($filePath);
        // ページ遷移
        header('location: ../productlist/index.php?id='.$monthlyId);
    }catch (Exception $e)
    {
        $errorMessage[] = $e->getMessage();
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
        <?php include("../../public/assets/php/partial/menu_admin.php"); ?>
    </div>
    <div class="col-10 container">
        <h2>生協商品リストを取り込む</h2>
        <form method="post">
            <select name="month">
                <?php foreach ($rows as $val){ ?>
                <option value="<?php echo date('Ymd', $val); ?>"
                 <?php if(date('Ym') === date('Ym', $val)) echo 'selected' ?>>
                 <?php echo date('Y年n月分', $val) ?></option>
                <?php } ?>
            </select>
            <button type="submit" name='submit_month' class="btn btn-green"><?php echo $displayMonth ?></button>
        </form>

        <?php if(isset($_POST['submit_month'])){ ?>
        <form method="post" action="" enctype="multipart/form-data">
            <input type="file"    name="csv" id="csv">
            <input type="hidden"  name="monthlyId" value="<?php echo $monthlyId ?>">
            <button type="submit" name="submit_csv" class="btn btn-blue" onclick="return checkFile();" >商品リストを取り込む</button>
        </form>
        <?php } ?>

        <?php errorMessages($errorMessage) ?>
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