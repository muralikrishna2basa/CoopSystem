<?php 
//公開年月選択と全ユーザーの発注を作成する関数
function monthSelectionAndOrderCreation($monthlyId)
{
    try{
       $pdo = connectDb('cooopshinren');
       $sql ="SELECT COUNT(*) FROM ordering WHERE monthly_id =?";
       $stmt=$pdo->prepare($sql);
       $res = $stmt->execute(array($monthlyId));
       $ren = $stmt->fetchColumn();
//       var_dump($ren);
//       while ($row = $stmt->fetch()[0])
//       {
//        $ren =$row[0];
//    }
    }catch(Exception $e){
        echo $e->getMessage();
    }
    if(intval($ren)!==0){
        try{
            $pdo = connectDb('cooopshinren');
            $sql = "UPDATE monthly SET public_flag = 0";
            $stmt=$pdo->prepare($sql);
            $res= $stmt->execute(null);

            $pdo = connectDb('cooopshinren');
            $sql = "UPDATE monthly SET public_flag = 1 WHERE monthly_id = ?";
            $stmt=$pdo->prepare($sql);
            $res= $stmt->execute(array($monthlyId));

        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    else{
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
            echo $e->getMessage();
        }
    }
}
//在庫リストを仮作成する関数
//
function stockListTemporaryCreating(){
    try{
        $pdo = connectDb('cooopshinren');
        $sql="SELECT * FROM monthly_goods NATURAL JOIN category WHERE monthly_id = (SELECT MAX(monthly_id) FROM monthly WHERE fixed_flag =1)";
        $stmt=$pdo->prepare($sql);
        $res= $stmt->execute(array());
        while ($row = $stmt->fetch()) {
            $ren[] =$row;
        }
        return $ren;
    }catch(Exception $e){
        echo $e->getMessage();
    }
}

//在庫リストを登録する関数
//stockListは商品ＩＤと個数の配列
function stockListRegistration($monthlyId,$stockList)
{

    for($i=0;$i<count($stockList['monthly_goods_id']);$i++){
        $quantaity = intval($stockList['stock_quantity'][$i]);
        if($quantaity!=0){
            try{
               $pdo = connectDb('cooopshinren');
               $sql="INSERT INTO stock_list VALUES (NULL, '?', '?', '?');";
               $stmt=$pdo->prepare($sql);
               $res= $stmt->execute(array($stockList['monthly_goods_id'][$i],$monthlyId,intval($stockList['stock_quantity'][$i])));
           }catch(Exception $e){
            echo $e->getMessage();
        }
    }
}
}
//在庫リストを編集する関数
//stockListは商品ＩＤと個数の配列
function stockListEdit($stockList){
  for($i=0;$i<count($stockList['monthly_goods_id']);$i++){
    $quantaity = intval($stockList['stock_quantity'][$i]);
    if($quantaity!=0){
        try{
           $pdo = connectDb('cooopshinren');
           $sql="UPDATE stock_list SET stock_quantit` = ? WHERE monthly_goods_id = ?";
           $stmt=$pdo->prepare($sql);
           $res= $stmt->execute(array(intval($stockList['stock_quantity'][$i])),$stockList['monthly_goods_id'][$i]);
       }catch(Exception $e){
        echo $e->getMessage();
    }
}else{
   try{
       $pdo = connectDb('cooopshinren');
       $sql="INSERT INTO stock_list VALUES (NULL, '?', '?', '?');";
       $stmt=$pdo->prepare($sql);
       $res= $stmt->execute(array($stockList['monthly_goods_id'][$i],$monthlyId,intval($stockList['stock_quantity'][$i])));
   }catch(Exception $e){
    echo $e->getMessage();
}
}
}
}
//受け取ったCSVファイルが正しいかどうかチェックする関数
function csvFileCheck($csvArray){
    $errorMessage=[];
    for($i=1;$i<count($csvArray);$i++){
        $errorflag=0;
        $number=$i-1;
        $str = $number."番目の商品の";
        if($csvArray[$i][0]==null){
            $errorflag=1;
            $str=$str."商品名が空白です,";
        }
        if($csvArray[$i][1]==null){
            $errorflag=1;
            $str=$str."内容量が空白です,";
        }
        if(preg_match("[^0-9]",$csvArray[$i][2])==1){
            $errorflag=1;
            $str=$str."必要数は数字しか使えません,";
        }

        if(preg_match("[^0-9]",$csvArray[$i][3])==1){
            $errorflag=1;
            $str=$str."単価は数字しか使えません,";
        }
        if(preg_match("[^0-9]",$csvArray[$i][4])==1){
            $errorflag=1;
            $str=$str."カテゴリは数字しか使えません,";
        }
        if($errorflag==1){
            $errorMessage[$str];

        } 
    }
    return $errorMessage;
}


//商品リストを作成する関数
function productListCreation($csvArray,$monthlyId){

    for($i=1;$i<count($csvArray);$i++){
        try{
            $pdo = connectDb('cooopshinren');
            $sql="INSERT INTO  coopsystemdb.monthly_goods (
            monthly_goods_id,
            goods_name,
            unit_price,
            detail_amount_per_one,
            required_quantity,
            monthly_id,
            category_id)
            VALUES (NULL,?,?,?,?,?,?);";
            $stmt=$pdo->prepare($sql);
            $res= $stmt->execute(array(
                $csvArray[$i][0],
                $csvArray[$i][1],
                $csvArray[$i][2],
                $csvArray[$i][3],
                $monthlyId,
                $csvArray[$i][4],));
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
}
//商品リストを表示する関数
function productListDisplay($monthlyId){
    try{
        $pdo = connectDb('cooopshinren');
        $sql="SELECT * FROM monthly_goods WHERE monthly_id = ?";
        $stmt=$pdo->prepare($sql);
        $res= $stmt->execute($monthlyId);
        while ($row = $stmt->fetch()) {
            $order[] =$row;
        }
        return $order;
    }catch(Exception $e)
    {
      echo $e->getMessage();
  }
}

//商品リストを編集する関数
 //productListは　商品ＩＤ、商品名、単価、内容量、必要量、カテゴリＩＤの配列　
function productListEdit($productList){
   $pdo = connectDb('cooopshinren');
   for($i=0;$i<count($productList['monthly_goods_id']);$i++){
    try{
        $sql="UPDATE monthly_goods SET monthly_goods_id=?,
        goods_name=?,
        unit_price=?,
        detail_amount_per_one=?,
        required_quantity=?,
        monthly_id=?,
        category_id=?
        WHERE monthly_goods_id=?";
        $stmt=$pdo->prepare($sql);
        $res= $stmt->execute(array($productList['monthly_goods_id'][$i],
            $productList['goods_name'][$i],
            $productList['unit_price'][$i],
            $productList['detail_amount_per_one'][$i],
            $productList['required_quantity'][$i],
            $productList['monthly_id'][$i],
            $productList['category_id'][$i],
            $productList['monthly_goods_id'][$i]));
    }
    catch(Exception $e){
        echo $e->getMessage();
    }
}
}
//発注リストを編集する関数
function orderListEdit()
{
    # code...
}
//月別ＩＤを作成する関数
//function 
?>