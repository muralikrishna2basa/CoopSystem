<?php
include     ('../../public/assets/php/partial/require_common.php');
include     ($PATH.'/public/assets/php/lib/common/sessionCheck.php');

require_once($PATH.'/public/assets/php/convertCsvFileToArray.php');
require_once($PATH."/public/assets/php/lib/administrator/administratorProcess.php");
set_time_limit(180);

$extension    = 'nofiles';
$displayMonth = '月を選択する';
$displayEdit  = '月のリストを編集する';
$monthlyId    = '';
//$editFlag     = true;
$csvFlag      = true;
$csv          = [];
$errors       = [];
$rows         = [];

try {
    $pdo   = connectDb('coop');
    deleteFaultList();
    $sql   = "SELECT MAX(date) FROM monthly WHERE fixed_flag!=1 AND public_flag!=1;";
    $stmt  = $pdo->prepare($sql);
    $res   = $stmt->execute(null);
    if(!$res) throw new Exception("DB接続時にエラーが発生しました。");
    $date  = (!$tmp = $stmt->fetchColumn()) ? date('Y-m-01') : $tmp;
    for($i = 0; $i < 3; $i++)
    {
        $serial = ($i === 0) ? strtotime($date) : strtotime($date."+{$i} month");
        $sql    = "SELECT COUNT(*) FROM monthly_goods
                   INNER JOIN monthly ON monthly_goods.monthly_id = monthly.monthly_id
                   WHERE monthly.date = ?;"
        ;
        $stmt    = $pdo->prepare($sql);
        $res     = $stmt->execute([date('Y-m-d', $serial)]);
        if(!$res) throw new Exception("DB接続時にエラーが発生しました。");
        $cnt     = intval($stmt->fetchColumn());
        $lists[] = ['date'=>$serial, 'cnt'=>$cnt];
    }

} catch (Exception $e) {
    $errors[] = $e->getMessage();
}


// option=selectedの調整

$compareDate = date('Ym');
if(isset($_POST['month'])) $compareDate = date('Ym', strtotime($_POST['month']));

/*
if(count($_POST) > 0 && isset($_POST['month']))
{
    try {
        // monthlyにデータを作成して、月別IDを取得する
        $monthlyId    = monthlyIdGeneration($_POST['month']);
        // [取込ボタン]月別IDが既に確定済みであれば処理中止
//        $sql  = "SELECT fixed_flag FROM monthly WHERE date=? LIMIT 1;";
        $sql  = "SELECT (fixed_flag + public_flag) FROM monthly WHERE monthly_id=?;";
        $stmt = $pdo->prepare($sql);
        $res  = $stmt->execute([$monthlyId,]);
//        var_dump($stmt->fetch());
        if(!$res) throw new Exception("monthlyDB接続時にエラーが発生しました。");
        if(intval($stmt->fetchColumn()) > 0) $csvFlag = false;
        $displayMonth = date('Y年n月', strtotime($_POST['month']))."が選択されています";
    } catch (Exception $e) {
        $errors[] = $e->getMessage();
//        exit();
    }
}
*/

if(count($_POST) > 0 && isset($_POST['month']))
        $displayMonth = date('Y年n月', strtotime($_POST['month']))."が選択されています";

//if(isset($_POST['monthlyId'])) $monthlyId = intval($_POST['monthlyId']);

// ファイルがセットされたかの判定
if(count($_FILES) > 0 && is_uploaded_file($_FILES['csv']['tmp_name']) && isset($_POST['month']))
{
    try
    {

        // monthly_idの取得（既に存在していた場合DBのから取得）
        $monthlyId = monthlyIdGeneration($_POST['month']);
       // var_dump($monthlyId);
//        exit();
        // $sql  = "SELECT monthly_id FROM monthly WHERE date=?;";
        // $stmt = $pdo->prepare($sql);
        // $res  = $stmt->execute([$_POST['month'], ]);
        // if(!$res) throw new Exception("monthlyDB接続時にエラーが発生しました。");
        // if(mb_strlen($monthlyId) == 0) $monthlyId = $stmt->fetchColumn();

        $sql  = "SELECT (fixed_flag + public_flag) FROM monthly WHERE monthly_id=?;";
        $stmt = $pdo->prepare($sql);
        $res  = $stmt->execute([$monthlyId,]);
        if(!$res) throw new Exception("monthlyDB接続時にエラーが発生しました。");
        if(intval($stmt->fetchColumn()) > 0) throw new Exception("確定済みであるか公開中であるため処理を中止しました。");


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
        $filePath = $PATH.'/public/assets/files/upload.csv';
        move_uploaded_file($_FILES['csv']['tmp_name'], $filePath);
        chmod($filePath, 0644);
        // CSVファイルを配列に変換
        $csvArray = convertCsvFileToArray($PATH.'/public/assets/files/upload.csv');
        $tmp = [];
        // var_dump($csvArray);
        $s=0;
        foreach ($csvArray as $row)
        {
            if(count($row)==5){
            $row[2] = preg_replace('/,/', '', $row[2]);
            $row[1] = preg_replace('/,/', '', $row[1]);
            $row[0] = mb_convert_kana($row[0], "KV");
            $tmp[]  = $row;
            }
        }
        $csvArray = $tmp;
        // ファイル内容のチェック
        $errorMessage=csvFileCheck($csvArray);
        // 結果をDBに格納
        if($errorMessage==null){
            productListCreation($csvArray, $monthlyId);
        }
        // アップロードしたファイルを削除
        if(file_exists($filePath)) unlink($filePath);
        // ページ遷移
        if($errorMessage==null){
            header('location: ../productlist/?id='.$monthlyId);
        }
         $errors[]=$errorMessage;
    }catch (Exception $e)
    {
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
        <?php include($PATH."/public/assets/php/partial/menu_admin.php"); ?>
    </div>
    <div class="col-10 container scroll">
        <h2>生協商品リストを取り込む</h2>
        <form method="post" enctype="multipart/form-data">
            <span class="tips">
                <span class=" tips-trigger">
                    <select name="month">
                        <?php foreach ($lists as $val){ ?>
                        <option value="<?php echo date('Ymd', $val['date']); ?>"
                         <?php if($compareDate === date('Ym', $val['date'])) echo 'selected' ?>>
                         <?php echo date('Y年n月分', $val['date']) ?>
                         <?php echo " / {$val['cnt']}件" ?>
                         </option>
                        <?php } ?>
                    </select>
                    <button type="submit" name='submit_month' class="btn btn-green"><?php echo $displayMonth ?></button>
                </span>
                <div class="tips-target">
                    <p>公開したい月を選択してクリックしてください。</p>
                    <p>既に選択した月のリストが存在している場合、リストに追加されます。</p>
                </div>
            </span>

        <?php if(isset($_POST['submit_month'])){ ?>
            <div class="tips">
                <span class="tips-trigger">
                    <input type="file"    name="csv" id="csv">
                    <input type="hidden"  name="monthlyId" value="<?php echo $monthlyId ?>">
                    <button type="submit" name="submit_csv" class="btn btn-blue" onclick="return checkFile();" >商品リストを取り込む</button>
                </span>

                <div class="tips-target">
                    <p>ファイルを選択して取り込んでください。</p>
                    <p>CSV形式以外は対応しておりません。</p>
                </div>
            </div>
        <?php } ?>
        </form>
        <?php errorMessages($errors) ?>
    </div>

    <p class="tips-btn"></p>
    <div class="tips-content"><h3 class="text-center">ここにヒントが表示されます。</h3></div>

</div>
<script type="text/javascript">
    function checkFile(){
        if($('#csv')[0].files[0]) return true;
        alert('ファイルが選択されていません。');
        return false;
    }
</script>
<?php include($PATH."/public/assets/php/partial/footer.php"); ?>
</body>
</html>