<?php
include     ('../../public/assets/php/partial/require_common.php');
include     ($PATH.'/public/assets/php/lib/common/sessionCheck.php');
require_once($PATH.'/public/assets/php/lib/administrator/administratorProcess.php');
require_once($PATH.'/public/assets/php/lib/administrator/category.php');
require_once($PATH.'/public/assets/php/auth.php');

try {
    if(count($_GET) <= 0) throw new Exception("不正な操作です。再度月選択画面からやり直してください。");
    $lists = getOrderListBeforeFixed($_GET['id']);

    $pdo   = connectDb('coop');
    $sql   = "SELECT date FROM monthly WHERE monthly_id=?;";
    $stmt  = $pdo->prepare($sql);
    $res   = $stmt->execute([$_GET['id'], ]);
    if(!$res) throw new Exception("DB接続時にエラーが発生しました。");
    $date  = date('Y年n月', strtotime($stmt->fetchColumn()));
    exportCsv($lists, $date, $PATH);
} catch (Exception $e) {
    echo '<script type="text/javascript"> alert("'.$e->getMessage().'"); window.location.href = "../month";</script>';
}

function exportCsv($lists, $date, $path){
    $filePath = $path.'/public/assets/files/';
    $fileName = mb_convert_encoding("{$date}分発注リスト.csv", 'sjis', 'utf8');
    $fullPath = $filePath.$fileName;
    $csvTitle = ["生協商品番号", "カテゴリID", "カテゴリ名", "商品名", "購入者", "購入数", "単価", "合計金額"];
    $header   = [];
    try {
        if(touch($fullPath))
        {
            $file = new SplFileObject($fullPath, 'w');
            foreach ($csvTitle as $buf) $header[] = mb_convert_encoding($buf, 'sjis', 'utf8');

            $file->fputcsv($header);
            foreach ($lists as $list)
            {
                $content   = [];
                $content[] = mb_convert_encoding($list['coop_product_id'],   'sjis', 'utf8');
                $content[] = mb_convert_encoding($list['category_id'],       'sjis', 'utf8');
                $content[] = mb_convert_encoding($list['category_name'],     'sjis', 'utf8');
                $content[] = mb_convert_encoding($list['goods_name'],        'sjis', 'utf8');
                $content[] = mb_convert_encoding($list['user_name'],         'sjis', 'utf8');
                $content[] = mb_convert_encoding($list['ordering_quantity'], 'sjis', 'utf8');
                $content[] = mb_convert_encoding($list['unit_price'],        'sjis', 'utf8');
                $content[] = mb_convert_encoding($list['total'],             'sjis', 'utf8');
                $file->fputcsv($content);
            }
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: private",false);
            header("Content-Type: application/force-download");
            header('Content-Disposition: attachment; filename="'.$fileName.'"');
            header("Content-Transfer-Encoding: binary");
            readfile($fullPath);
            unset($file); // 削除前にunsetしておかないとPermissionエラーが発生する
            unlink($fullPath);

        }
    } catch (Exception $e) {
        throw $e;
    }
}
?>