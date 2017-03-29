<?php 
require_once('../../auth.php');
require_once('../../connectDb.php');
//公開年月選択と全ユーザーの発注を作成する関数
function monthSelectionAndOrderCreation($monthlyId)
{
    try{
         $pdo = connectDb('cooopshinren');
         $sql ="SELECT COUNT(*) FROM ordering WHERE monthly_id =?";
          $stmt=$pdo->prepare($sql);
        $res= $stmt->execute(array($monthlyId));
while ($row = $stmt->fetch()) {
        $ren =$row[0];
    }
    }catch(Exception $e){

    }
  

    if($ren==0){
    try{
        $pdo = connectDb('cooopshinren');
        $sql = "UPDATE monthly SET public_flag = 1";
        $stmt=$pdo->prepare($sql);
        $res= $stmt->execute(null);
        $pdo = connectDb('cooopshinren');
        $sql = "UPDATE monthly SET public_flag = 0 WHERE monthly_id = ?";
        $stmt=$pdo->prepare($sql);
        $res= $stmt->execute(array($monthlyId));
       
    }catch(Exception $e){

    }
    try{

        $allUser = getAllUsers();
        $j=count($allUser);
        for($i=0;$i<$j;$i++){
         $pdo = connectDb('cooopshinren');
         $sql="INSERT INTO ordering VALUES (NULL, ?, ?, 0)";
         $stmt=$pdo->prepare($sql);
         $res= $stmt->execute(array($allUser[$i]["userid"],$monthlyId));
     }
 }catch(Exception $e){

 }
}
}
//在庫リストを作成する関数
//在庫リストを編集する関数
//商品リストを作成する関数
//商品リストを編集する関数
//発注リストを編集する関数
//カテゴリを作製、編集する関数
?>