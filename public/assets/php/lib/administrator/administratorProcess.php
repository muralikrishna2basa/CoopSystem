<?php
//公開年月選択と全ユーザーの発注を作成する関数
function monthSelectionAndOrderCreation($monthlyId)
{
    try{
       $pdo = connectDb('cooopshinren');
       $sql ="SELECT COUNT(*) FROM ordering WHERE monthly_id =?";
       $stmt=$pdo->prepare($sql);
       $res = $stmt->execute(array($monthlyId));
        if(!$res) throw new Exception("関数monthSelectionAndOrderCreationでmonthly_id取得時にエラーが発生しました。");
       $monthlyIdCount= $stmt->fetchColumn();
//       var_dump($ren);
//       while ($row = $stmt->fetch()[0])
//       {
//        $ren =$row[0];
//    }
    }
    catch(Exception $e){
        echo $e->getMessage();
    }
    if(intval($monthlyIdCount)!==0){
        try{
            $pdo = connectDb('cooopshinren');
            $sql = "UPDATE monthly SET public_flag = 0";
            $stmt=$pdo->prepare($sql);
            $res= $stmt->execute(null);
            if(!$res) throw new Exception("関数monthSelectionAndOrderCreationでUPDATE実行時にエラーが発生しました。");
            $pdo = connectDb('cooopshinren');
            $sql = "UPDATE monthly SET public_flag = 1 WHERE monthly_id = ?";
            $stmt=$pdo->prepare($sql);
            $res= $stmt->execute(array($monthlyId));
            if(!$res) throw new Exception("関数monthSelectionAndOrderCreationでUPDATE実行時にエラーが発生しました。");
        }
        catch(Exception $e){
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
               $param=array($allUser[$i]["userid"],$monthlyId);
               $res= $stmt->execute($param);
               if(!$res) throw new Exception("関数monthSelectionAndOrderCreationでINSERT実行時にエラーが発生しました。");
           }
       }
        catch(Exception $e){
            echo $e->getMessage();
        }
    }
}
//在庫リストを仮作成する関数
//
function stockListTemporaryCreating(){
    try{
         $stock=[];
         $returnList=[];
        $pdo = connectDb('cooopshinren');
        $sql="SELECT * FROM monthly_goods NATURAL JOIN category WHERE monthly_id = (SELECT MAX(monthly_id) FROM monthly WHERE fixed_flag =1)";
        $stmt=$pdo->prepare($sql);
        $res= $stmt->execute();
        if(!$res) throw new Exception("関数stockListTemporaryCreatingでSELECT実行時にエラーが発生しました。");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $monthlyGoods[] =$row;
        }
       
        for ($i=0; $i <count($monthlyGoods) ; $i++) {
            $monthlyGoods[$i]['initial_stock_quantity'] = 0;
            $monthlyGoods[$i][9]                   = 0;
        }
        $sql="SELECT stock_quantity,monthly_goods_id FROM stock_list WHERE monthly_id = (SELECT MAX(monthly_id) FROM monthly WHERE fixed_flag =1)";
        $stmt=$pdo->prepare($sql);
        $res= $stmt->execute(array());
        if(!$res) throw new Exception("関数stockListTemporaryCreatingでstock_quantity,monthly_goods_id取得時にエラーが発生しました。");
        while ($row = $stmt->fetch()) {
            $stock[] =$row;
        }
        for($i=0;$i<count($monthlyGoods);$i++){
            $flag=true;
             for($j=0;$j<count($stock);$j++){
                if($monthlyGoods[$i]['monthly_goods_id']==$stock[$j]['monthly_goods_id']){
                  $flag=false;
                  break;
                }
                }
                if($flag){
                    $returnList[]=$monthlyGoods[$i];
             }
        }
        return $returnList;
    }
    catch(Exception $e){
     return ['result'=>false, 'return'=>$e->getMessage()];
 }
}

//在庫リストを登録する関数
//stockListは商品ＩＤと個数の配列
function stockListRegistration($monthlyId,$stockList)
{
        $quantaity = intval($stockList['stock_quantity']);

        if($quantaity!=0){ // != -> !==
            try{
                var_dump($stockList);
                var_dump($monthlyId);
                $pdo = connectDb('cooopshinren');
                $sql="INSERT INTO stock_list VALUES (NULL,?,?,?)";
                $stmt=$pdo->prepare($sql);
                $param=array($stockList['monthly_goods_id'],$monthlyId,intval($stockList['stock_quantity']));
                $res= $stmt->execute($param);
                if(!$res) throw new Exception("関数stockListRegistrationでINSERT文実行時にエラーが発生しました。");
            }
            catch(Exception $e){
                throw $e;
        }
    }
}
//在庫リストを編集する関数
//stockListは商品ＩＤと個数の配列
function stockListEdit($stockList){
    try{
        $pdo = connectDb('cooopshinren');
        $sql = "UPDATE stock_list SET stock_quantity = ? WHERE monthly_goods_id = ?";
        $stmt= $pdo->prepare($sql);
        $res = $stmt->execute(array(intval($stockList['stock_quantity']),$stockList['monthly_goods_id']));
        if(!$res) throw new Exception("関数stockListEditでSELECT文実行時にエラーが発生しました。");
    }
    catch(Exception $e){
        throw $e;
    }
}
//受け取ったCSVファイルが正しいかどうかチェックする関数
function csvFileCheck($csvArray){
    $errorMessage=[];
    for($i=1;$i<count($csvArray);$i++){
        $errorflag=0;
        $number=$i-1;
        $str = $number."番目の商品の";
        if(count($csvArray[$i])===5){ // == -> ===
            if(mb_strlen($csvArray[$i][0])===0){
                $errorflag=1;
                $str=$str."商品名が空白です,";
            }
            if(mb_strlen($csvArray[$i][1])===0){
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
        }else{
            $str=$str."情報が不足しています";
        }
    }
    return $errorMessage;
}


//商品リストを作成する関数
function productListCreation($csvArray,$monthlyId){
    $pdo = connectDb('cooopshinren');
    for($i=1;$i<count($csvArray);$i++){
        try{
            $sql="INSERT INTO  coopsystemdb.monthly_goods
            VALUES (NULL,?,?,?,?,?,?);";
            $param = [
                $csvArray[$i][0], /* goods_name */
                $csvArray[$i][3], /* unit_price */
                $csvArray[$i][1], /* detail_amount_per_one */
                $csvArray[$i][2], /* required_quantity */
                $monthlyId,       /* monthly_id */
                $csvArray[$i][4], /* category_id */
            ];
            $stmt = $pdo->prepare($sql);
            $res  = $stmt->execute($param);
            if(!$res) throw new Exception("関数productListCreationで".$i."回目のINSERT文実行時にエラーが発生しました。");
//            var_dump($param);
//            var_dump($sql);
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
    }
}
//商品リストを表示する関数
function productListDisplay($monthlyId){
    $order = array();
    try{
        $pdo = connectDb('cooopshinren');
        $sql="SELECT * FROM monthly_goods WHERE monthly_id = ?";
        $stmt=$pdo->prepare($sql);
        $res= $stmt->execute(array($monthlyId));
        if(!$res) throw new Exception("関数productListDisplayでSELECT文実行時にエラーが発生しました。");
        while ($row = $stmt->fetch()) {
            $order[] =$row;
        }
        return $order;
    }
    catch(Exception $e)
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
            $sql="UPDATE monthly_goods SET
            goods_name=?,
            unit_price=?,
            detail_amount_per_one=?,
            required_quantity=?,
            category_id=?
            WHERE monthly_goods_id=?";
            $stmt=$pdo->prepare($sql);
            $param=[
                $productList['goods_name'][$i],
                $productList['unit_price'][$i],
                $productList['detail_amount_per_one'][$i],
                $productList['required_quantity'][$i],
                $productList['category_id'][$i],
                $productList['monthly_goods_id'][$i]];
            $res= $stmt->execute($param);
            if(!$res) throw new Exception("関数productListEditで".$i."回目のUPDATE文実行時にエラーが発生しました。");
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
    }
}
//発注リストを表示する関数
function orderListDisplay($monthlyId = 0){
    $orderList = [];
    $pdo = connectDb('cooopshinren');
    try{
        $sql = "SELECT category_name,color,goods_name,unit_price,
        ordering_quantity,(unit_price*ordering_quantity) AS amount,order_list_id,orderer
        FROM  ordering_list
        INNER JOIN monthly_goods ON ordering_list.monthly_goods_id = monthly_goods.monthly_goods_id
        INNER JOIN ordering ON ordering_list.ordering_id = ordering.ordering_id
        INNER JOIN category ON monthly_goods.category_id = category.category_id
        INNER JOIN monthly ON ordering.monthly_id =monthly.monthly_id
        WHERE ordering.monthly_id = (SELECT MIN(monthly_id) FROM monthly WHERE fixed_flag =0)
        ORDER BY category.category_id;";
        $stmt=$pdo->prepare($sql);
        $res= $stmt->execute();
        if(!$res) throw new Exception("関数orderListDisplayでSELECT文実行時にエラーが発生しました。");
        while ($row = $stmt->fetch()) {
            $orderList[] =$row;
        }
        $users=getAllUsers();
        for ($i=0; $i <count($orderList); $i++) { 
            $orderList[$i]['name'] = "";
            $orderList[$i][8] = "";
            for ($j=0; $j <count($users) ; $j++) { 
                if($orderList[$i]['orderer']==$users[$j]['userid']){
                    $orderList[$i]['name'] = $users[$j]['userName'];
                    $orderList[$i][8] = $users[$j]['userName'];
                }
            }
        }
        return $orderList;
    }catch(Exception $e){
        echo $e->getMessage();
    }
}

//発注リストを編集する関数
function orderListEdit($orderList){
    for($i = 0; $i < count($orderList['order_list_id']); $i++){
        try{
            $sql="UPDATE ordering_list SET ordering_quantity=?
            WHERE order_list_id=?";
            $stmt=$pdo->prepare($sql);
            $param = [
                $orderList['ordering_quantity'][$i],
                $orderList['order_list_id'][$i]
            ];
            $res= $stmt->execute();
             if(!$res) throw new Exception("関数orderListEditでUPDATE実行時にエラーが発生しました。");
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
    }
}
//月別ＩＤを作成する関数
function monthlyIdGeneration($date){
    $pdo = connectDb('cooopshinren');
    try{
        $sql  = "SELECT monthly_id FROM monthly WHERE date =?";
        $stmt = $pdo->prepare($sql);
        $res  = $stmt->execute(array($date));
       if(!$res) throw new Exception("関数monthlyIdGenerationでmonthly_id取得時にエラーが発生しました。");
        $monthlyId = $stmt->fetchColumn();
        if($monthlyId==false){ // == -> ===
            $sql="INSERT INTO monthly VALUES (NULL,?,0,0)";
            $stmt=$pdo->prepare($sql);
            $res= $stmt->execute(array($date));
            if(!$res) throw new Exception("関数monthlyIdGenerationでINSERT文実行時にエラーが発生しました。");
            $monthlyId=monthlyIdGeneration($date);
        }
        return $monthlyId;
    }
    catch(Exception $e){
        echo $e->getMessage();
    }
}
//在庫リストが新規かどうか
function isInventoryListNewly($stockList){
    $pdo = connectDb('cooopshinren');
    
    try{
        $sql ="SELECT MAX(monthly_id) FROM monthly WHERE fixed_flag =1";
        $stmt=$pdo->prepare($sql);
        $res= $stmt->execute();
        if(!$res) throw new Exception("関数isInventoryListNewlyでmonthly_id取得時にエラーが発生しました。");
        $monthlyId = $stmt->fetchColumn();
        $sql ="SELECT COUNT(*) FROM stock_list WHERE monthly_goods_id = ?";
        for($i=0;$i<count($stockList['monthly_goods_id']);$i++){
            $stmt=$pdo->prepare($sql);
            $res= $stmt->execute(array($stockList['monthly_goods_id'][$i]));
            if(!$res) throw new Exception("関数isInventoryListNewlyでproductCount取得時にエラーが発生しました。");
            $productCount = $stmt->fetchColumn();
            $sL['monthly_goods_id']=$stockList['monthly_goods_id'][$i];
            $sL['stock_quantity']=$stockList['stock_quantity'][$i];
            if($productCount!=0){
                stockListEdit($sL);
            }
            else{
            stockListRegistration($monthlyId,$sL);
            }
        }
    }catch(Exception $e){
        throw $e;
    }

    
}
//発注を確定する関数
function fixOrder($monthlyId ){
    $pdo = connectDb('cooopshinren');
    try{
        $sql= "UPDATE monthly SET fixed_flag = 1 WHERE monthly_id = ?";
        $stmt=$pdo->prepare($sql);
        $res= $stmt->execute(array($monthlyId));
        if(!$res) throw new Exception("関数fixOrderでUPDATE文実行時にエラーが発生しました。");

    }catch(Exception $e){
        throw $e;
    }
}
//在庫リストを表示する管理者用関数
function administratorReturnStockList(){
 $pdo = connectDb('cooopshinren');
  try{
        $sql="SELECT stock_list.monthly_goods_id,goods_name,unit_price,detail_amount_per_one,
                    stock_quantity,category_name,color
                    FROM stock_list
                    INNER JOIN monthly_goods ON stock_list.monthly_goods_id = monthly_goods.monthly_goods_id
                    INNER JOIN category ON monthly_goods.category_id = category.category_id";
        $stmt=$pdo->prepare($sql);
        $res= $stmt->execute();
        while ($row = $stmt->fetch()) {
            $stockList[] =$row;
        }
        if(!$res) throw new Exception("関数fixOrderでmonthlyId取得時にエラーが発生しました。");
        return $stockList;
    }catch(Exception $e){
        throw $e;
    }
}
//発注の確定をなかったことにする関数
function unFixOrder($monthlyId ){
    $pdo = connectDb('cooopshinren');
    try{
        $sql= "UPDATE monthly SET fixed_flag = 0 WHERE monthly_id = ?";
        $stmt=$pdo->prepare($sql);
        $res= $stmt->execute(array($monthlyId));
        if(!$res) throw new Exception("関数fixOrderでUPDATE文実行時にエラーが発生しました。");

    }catch(Exception $e){
        throw $e;
    }
}
?>