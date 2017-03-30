<?php

//ユーザーの履歴を返す関数
// カテゴリ名（category_name）,色（color）,商品名（goods_name）,単価（unit_price,）
//発注数（ordering_quantity）,合計金額（(amount）の配列を返す
function displayHistory($userId,$monthlyId){

    $ren = [];
    try{
        $pdo = connectDb('cooopshinren');
        if($monthlyId==0){
            $sql = "SELECT category_name,color,goods_name,unit_price,
            ordering_quantity,(unit_price*ordering_quantity) AS amount
            FROM  ordering_list
            INNER JOIN monthly_goods ON ordering_list.monthly_goods_id = monthly_goods.monthly_goods_id
            INNER JOIN ordering ON ordering_list.ordering_id = ordering.ordering_id
            INNER JOIN category ON monthly_goods.category_id = category.category_id
            INNER JOIN monthly ON ordering.monthly_id =monthly.monthly_id
            WHERE ordering.orderer = ?;";
            $stmt=$pdo->prepare($sql);
            $res= $stmt->execute(array($userId));
        }else{
            $sql = "SELECT category_name,color,goods_name,unit_price,
            ordering_quantity,(unit_price*ordering_quantity)as amount
            FROM  ordering_list
            INNER JOIN monthly_goods ON ordering_list.monthly_goods_id = monthly_goods.monthly_goods_id
            INNER JOIN ordering ON ordering_list.ordering_id = ordering.ordering_id
            INNER JOIN category ON monthly_goods.category_id = category.category_id
            INNER JOIN monthly ON ordering.monthly_id =monthly.monthly_id
            WHERE ordering.orderer = ? AND ordering.monthly_id=?;";
            $stmt=$pdo->prepare($sql);
            $res= $stmt->execute(array($userId,$monthlyId));
        }
        while ($row = $stmt->fetch()) {
            $ren[] =$row;
        }

        return $ren;

    }catch(Exception $e){
        echo $e->getMessage;
    }
}
//今月のリストを返す関数
//月別商品ID（monthly_goods_id）,商品名（goods_name）,単価（unit_price）,
//一つ当たりの量（detail_amount_per_one）必要量（required_quantity),カテゴリ名（category_name）,色（color）発注数（ordering_quantity）
//の配列を返す
function returnCurrentMonthProductList($userId){
//    $ren=array();
    $ren = [];
    try{
        $pdo = connectDb('cooopshinren');
        $sql = "SELECT fixed_flag FROM ordering WHERE orderer= ?";
        $stmt=$pdo->prepare($sql);
        $res= $stmt->execute(array($userId));
        while ($row = $stmt->fetch()) {
            $ren =$row[0];
        }
    }catch(Exception $e){
        echo $e->getMessage();
    }

    try{
        $pdo = connectDb('cooopshinren');
        $sql="SELECT monthly_goods_id,goods_name,unit_price,detail_amount_per_one, required_quantity,category_name,color
        FROM monthly_goods NATURAL JOIN monthly NATURAL JOIN category WHERE public_flag=0;";
        $stmt=$pdo->prepare($sql);
        $res= $stmt->execute(null);

        while ($row = $stmt->fetch()) {
            $order[] =$row;
        }

    }catch(Exception $e){
        echo $e->getMessage();
    }
    if($ren==0){
        for ($i=0; $i <count($order) ; $i++) { 
            $order[$i]['ordering_quantity']=0;
            $order[$i][7]=0;

        }
        return $order;
    }
    try{
        $pdo = connectDb('cooopshinren');
        $sql = "SELECT 
        ordering_list.monthly_goods_id,ordering_quantity
        FROM  ordering_list
        INNER JOIN monthly_goods ON ordering_list.monthly_goods_id = monthly_goods.monthly_goods_id
        INNER JOIN ordering ON ordering_list.ordering_id = ordering.ordering_id
        INNER JOIN category ON monthly_goods.category_id = category.category_id
        INNER JOIN monthly ON ordering.monthly_id =monthly.monthly_id
        WHERE ordering.orderer = ? AND monthly.public_flag=0";
        $stmt=$pdo->prepare($sql);
        $res= $stmt->execute(array($userId));

        while ($row = $stmt->fetch()) {
            $currentMonthList[] =$row;
//                $ren.=array ($row[0],$row[1],$row[3],$row[4],$row[5],$row[7]);
        }


        for ($i=0; $i <count($order) ; $i++) { 
            $order[$i]['ordering_quantity']=0;
                   $order[$i][7]=0;
            for ($j=0; $j < count($currentMonthList); $j++) { 
                
                if($order[$i][0]==$currentMonthList[$j][0]){
                    $order[$i]['ordering_quantity']=$currentMonthList[$j][1];
                    $order[$i][7]=$currentMonthList[$j][1];
                }
           }
       }
       return $order;
   }catch(Exception $e){
    echo $e->getMessage();
}

}

//在庫リストを返す関数
//月別商品ID（monthly_goods_id）,商品名（goods_name）,単価（unit_price）,
//一つ当たりの量(detail_amount_per_one）,在庫量（stock_quantity）,カテゴリ名（category_name）,色（color）,発注数（ordering_quantity）の配列を返す

function returnStockList($userId,$monthlyId){
    $ren = [];
    try{
        $pdo = connectDb('cooopshinren');
        $sql = "SELECT fixed_flag FROM ordering WHERE orderer= ?";
        $stmt=$pdo->prepare($sql);
        $res= $stmt->execute(array($userId));
        while ($row = $stmt->fetch()) {
            $ren =$row[0];
        }
    }catch(Exception $e){
        echo $e->getMessage();
    }
    try{
        $pdo = connectDb('cooopshinren');
        if ($monthlyId==0){
            $sql = "SELECT stock_list.monthly_goods_id,goods_name,unit_price,detail_amount_per_one,
            stock_quantity,category_name,color
            FROM stock_list
            INNER JOIN monthly_goods ON stock_list.monthly_goods_id = monthly_goods.monthly_goods_id
            INNER JOIN category ON monthly_goods.category_id = category.category_id;";
            $stmt=$pdo->prepare($sql);
            $res= $stmt->execute(null);
        }else{
            $sql = "SELECT stock_list.monthly_goods_id,goods_name,unit_price,detail_amount_per_one,
            stock_quantity,category_name,color
            FROM stock_list
            INNER JOIN monthly_goods ON stock_list.monthly_goods_id = monthly_goods.monthly_goods_id
            INNER JOIN category ON monthly_goods.category_id = category.category_id 
            WHERE stock_list.monthly_id =?";
            $stmt=$pdo->prepare($sql);
            $res= $stmt->execute(array($monthlyId));
        }

        while ($row = $stmt->fetch()) {
            $stockList[] =$row;
        }

    }catch(Exception $e){
        echo $e->getMessage;
    }
    if($ren==0){
        for ($i=0; $i <count($stockList) ; $i++) { 
            $stockList[$i]['ordering_quantity']=0;
            $stockList[$i][7]=0;

        }
        return $stockList;
    }
    try{
        $pdo = connectDb('cooopshinren');
        $sql = "SELECT 
        ordering_list.monthly_goods_id,ordering_quantity
        FROM  ordering_list
        INNER JOIN monthly_goods ON ordering_list.monthly_goods_id = monthly_goods.monthly_goods_id
        INNER JOIN ordering ON ordering_list.ordering_id = ordering.ordering_id
        INNER JOIN category ON monthly_goods.category_id = category.category_id
        INNER JOIN monthly ON ordering.monthly_id =monthly.monthly_id
        WHERE ordering.orderer = ? AND monthly.public_flag=0";
        $stmt=$pdo->prepare($sql);
        $res= $stmt->execute(array($userId));

        while ($row = $stmt->fetch()) {
            $currentMonthList[] =$row;
//                $ren.=array ($row[0],$row[1],$row[3],$row[4],$row[5],$row[7]);
        }


        for ($i=0; $i <count($stockList) ; $i++) { 
            $stockList[$i]['ordering_quantity']=0;
                   $stockList[$i][7]=0;
            for ($j=0; $j < count($currentMonthList); $j++) { 
                if($stockList[$i][0]==$currentMonthList[$j][0]){
                    $stockList[$i]['ordering_quantity']=$currentMonthList[$j][1];
                    $stockList[$i][7]=$currentMonthList[$j][1];
                }
           }
       }
       return $stockList;
   }catch(Exception $e){
    echo $e->getMessage();
}

}

//受け取ったデータを基に発注を行う関数
//今月のリストから発注する
//ユーザーIDと配列（商品ＩＤと個数）を受け取り登録する
function doOrder($userId,$orderGoodsList){
    try{
       $pdo = connectDb('cooopshinren');
       $sql="SELECT ordering_id FROM ordering NATURAL JOIN monthly WHERE public_flag = 0 AND orderer =?";
       $stmt=$pdo->prepare($sql);
       $res= $stmt->execute(array($userId));
       while ($row = $stmt->fetch()) {
        $ren =$row[0];
    }

    $pdo = connectDb('cooopshinren');
    $sql = "UPDATE ordering SET fixed_flag = 0 WHERE ordering.orderer = ?;";
    $stmt=$pdo->prepare($sql);
    $res= $stmt->execute(array($userId));
    $pdo = connectDb('cooopshinren');
    $sql = "UPDATE ordering SET fixed_flag = 1 WHERE ordering.ordering_id=?;";
    $stmt=$pdo->prepare($sql);
    $res= $stmt->execute(array($ren));
    for($i = 0; $i < count($orderGoodsList['monthly_goods_id']); $i++){
// echo '<pre>';

// echo $orderGoodsList['monthly_goods_id'][$i];
// echo $orderGoodsList['ordering_quantity'][$i];
// echo '</pre>';
        $quantaity = intval($orderGoodsList["ordering_quantity"][$i]);
        if($quantaity>0){
            $pdo = connectDb('cooopshinren');
            $sql ="INSERT INTO ordering_list VALUES (NULL,?,?,?);";
            $stmt=$pdo->prepare($sql);
            $res= $stmt->execute(array($orderGoodsList['monthly_goods_id'][$i],$ren,$quantaity));
        }
    }
}catch(Exception $e){
    echo $e->getMessage;
}

}

// 在庫から発注する関数
// $orderStockGoodsListは（商品ＩＤ、発注数）
function doOrderStock($userId,$newOrderGoodsList){
    $errorMessage = array();
    try{
       $pdo = connectDb('cooopshinren');
       $sql="SELECT ordering_id FROM ordering NATURAL JOIN monthly WHERE public_flag = 0 AND orderer =?";
       $stmt=$pdo->prepare($sql);
       $res= $stmt->execute(array($userId));
       while ($row = $stmt->fetch()){
        $ren =$row[0];
    }

    for($i = 0; $i < count($newOrderGoodsList['monthly_goods_id']); $i++){
        $goodsId = $newOrderGoodsList['monthly_goods_id'];
        $quantaity = intval($newOrderGoodsList["ordering_quantity"]);
        $pdo = connectDb('cooopshinren');
        $sql ="SELECT stock_quantity FROM stock_list WHERE monthly_goods_id=?";
        $stmt=$pdo->prepare($sql);
        $res= $stmt->execute(array($goodsId));
        while ($row = $stmt->fetch()) {
            $stock =$row[0];
        }
        if($quantaity>0&&$stock-$quantaity>=0){
            $pdo = connectDb('cooopshinren');
            $sql ="INSERT INTO ordering_list VALUES (NULL,?,?,?);";
            $stmt=$pdo->prepare($sql);
            $res= $stmt->execute(array($goodsId,$ren,$quantaity));
            $pdo = connectDb('cooopshinren');
            $sql ="UPDATE stock_list SET stock_quantity = ? WHERE stock_list.monthly_goods_id= ?";
            $stmt=$pdo->prepare($sql);
            $difference=$stock-$quantaity;
            $res= $stmt->execute(array($difference,$goodsId));
        }
        if($stock-$quantaity<0){
            $pdo = connectDb('cooopshinren');
            $sql="SELECT goods_name FROM `monthly_goods` WHERE monthly_goods_id = ?";
            $stmt=$pdo->prepare($sql);
            $res= $stmt->execute(array($goodsId));
            while ($row = $stmt->fetch()) {
                $str =$row[0]."は在庫以上の数を発注しようとしたためエラーが起きました";
            }
            $errorMessage[$str];
        }

    }
    return $errorMessage;
}catch(Exception $e){
    echo $e->getMessage;
}

}
//今月のリストから発注したものを編集削除する関数
//$editOrderGoodsList は　商品ＩＤ　変更後の発注数　変更前の発注数　
function currentMonthListFromPlacedEdit($userId,$editOrderGoodsList){
    try{
     $pdo = connectDb('cooopshinren');
     $sql="SELECT ordering_id FROM ordering NATURAL JOIN monthly WHERE public_flag = 0 AND orderer =?";
     $stmt=$pdo->prepare($sql);
     $res= $stmt->execute(array($userId));
     while ($row = $stmt->fetch()){
        $ren =$row[0];
    }
}catch(Exception $e){
    echo $e->getMessage;
}
for($i = 0; $i < count($editOrderGoodsList['monthly_goods_id']); $i++){
    if($editOrderGoodsList["ordering_quantity"]==0){
        try{ $pdo = connectDb('cooopshinren');
        $sql="DELETE FROM ordering_list WHERE monthly_goods_id =? AND ordering_id =?";
        $stmt=$pdo->prepare($sql);
        $res= $stmt->execute(array($editOrderGoodsList['monthly_goods_id'],$ren));
    }catch(Exception $e){
        echo $e->getMessage;
    }

}else{
    try{
        $pdo = connectDb('cooopshinren');
        $sql="UPDATE ordering_list SET ordering_quantity=? WHERE monthly_goods_id =? AND ordering_id =?";
        $stmt=$pdo->prepare($sql);
        $res= $stmt->execute(array($editOrderGoodsList['ordering_quantity'],$editOrderGoodsList['monthly_goods_id'],$ren));
    }catch(Exception $e){
        echo $e->getMessage;
    }

}
}
}

//在庫リストから発注したものを編集削除する関数
//$editOrderGoodsList は　商品ＩＤ　変更後の発注数　変更前の発注数　
function stockListFromPlacedEditDelete($userId,$editOrderGoodsList)
{
    $errorMessage = array();
    try{
     $pdo = connectDb('cooopshinren');
     $sql="SELECT ordering_id FROM ordering NATURAL JOIN monthly WHERE public_flag = 0 AND orderer =?";
     $stmt=$pdo->prepare($sql);
     $res= $stmt->execute(array($userId));
     while ($row = $stmt->fetch()) 
     {
        $ren =$row[0];
    }
}
catch(Exception $e)
{
    echo $e->getMessage;
}
for($i = 0; $i < count($editOrderGoodsList['monthly_goods_id']); $i++){
    $pdo = connectDb('cooopshinren');
    $sql ="SELECT stock_quantity FROM stock_list WHERE monthly_goods_id=?";
    $stmt=$pdo->prepare($sql);
    $res= $stmt->execute(array($editOrderGoodsList['monthly_goods_id']));
    while ($row = $stmt->fetch()) {
        $stock =$row[0];
    }
    try{

    } catch(Exception $e){
        echo $e->getMessage;
    } 
    if($editOrderGoodsList["ordering_quantity"]==0) {
        try{ 
            $pdo = connectDb('cooopshinren');
            $sql="DELETE FROM ordering_list WHERE monthly_goods_id =? AND ordering_id =?";
            $stmt=$pdo->prepare($sql);
            $res= $stmt->execute(array($editOrderGoodsList['monthly_goods_id'],$ren));
        }catch(Exception $e){
            echo $e->getMessage;
        }
        $stock=$stock+$editOrderGoodsList['initial_ordering_quantity'][$i];
        try{ 
            $pdo = connectDb('cooopshinren');
            $sql ="UPDATE stock_list SET stock_quantity = ? WHERE stock_list.monthly_goods_id= ?";
            $stmt=$pdo->prepare($sql);
            $res= $stmt->execute(array($stock,$editOrderGoodsList['monthly_goods_id']));
        }catch(Exception $e){
            echo $e->getMessage;
        }
    }else {
        try {
            $pdo = connectDb('cooopshinren');
            $sql="UPDATE ordering_list SET ordering_quantity=? WHERE monthly_goods_id =? AND ordering_id =?";
            $stmt=$pdo->prepare($sql);
            $res= $stmt->execute(array($editOrderGoodsList['ordering_quantity'][$i],$editOrderGoodsList['monthly_goods_id'][$i],$ren));
        }catch(Exception $e){
            echo $e->getMessage;
        }
        $stock=$stock+$editOrderGoodsList['initial_ordering_quantity'][$i]-$editOrderGoodsList['ordering_quantity'][$i];
        if($stock>=0){
            try { 
                $pdo = connectDb('cooopshinren');
                $sql ="UPDATE stock_list SET stock_quantity = ? WHERE stock_list.monthly_goods_id= ?";
                $stmt=$pdo->prepare($sql);
                $res= $stmt->execute(array($stock,$editOrderGoodsList['monthly_goods_id']));
            }catch(Exception $e){
                echo $e->getMessage;
            }
        }
        else {
           try { 
            $pdo = connectDb('cooopshinren');
            $sql="SELECT goods_name FROM `monthly_goods` WHERE monthly_goods_id = ?";
            $stmt=$pdo->prepare($sql);
            $res= $stmt->execute(array($editOrderGoodsList['monthly_goods_id']));
            while ($row = $stmt->fetch()) {
                $str =$row[0]."は在庫以上の数を発注しようとしたためエラーが起きました";
            }
            $errorMessage[$str];
        }catch(Exception $e){
            echo $e->getMessage;
        }
        $pdo = connectDb('cooopshinren');
        $sql="SELECT goods_name FROM `monthly_goods` WHERE monthly_goods_id = ?";
        $stmt=$pdo->prepare($sql);
        $res= $stmt->execute(array($editOrderGoodsList['monthly_goods_id']));
        while ($row = $stmt->fetch()) {
            $str =$row[0]."は在庫以上の数を発注しようとしたためエラーが起きました";
        }
        $errorMessage[$str];
        
    }
}

}
}
//今月のリストから発注するときに新規かどうか判断する関数
//$OrderGoodsList は　商品ＩＤ　変更後の発注数　変更前の発注数　
 function currentMonthListFromOrderWhenNewlyDetermineWhether($userId,$orderGoodsList)
{
    for($i = 0; $i < count($orderGoodsList['monthly_goods_id']); $i++){
        $array['monthly_goods_id'] = $orderGoodsList['monthly_goods_id'][$i];
        $array['initial_ordering_quantity']=$orderGoodsList['initial_ordering_quantity'][$i];
        $array['ordering_quantity'] = $orderGoodsList['ordering_quantity'][$i];
if($orderGoodsList['initial_ordering_quantity'][$i]==0){
    doOrder($userId,$array);
    }
    else{
        
        currentMonthListFromPlacedEdit($userId,$array);
    }
}
}

//在庫リストから発注するときに新規かどうか判断する関数
function stockListFromOrderWhenNewlyDetermineWhether($userId,$orderGoodsList)
{
    for($i = 0; $i < count($orderGoodsList['monthly_goods_id']); $i++){
        $array['monthly_goods_id'] = $orderGoodsList['monthly_goods_id'][$i];
        $array['initial_ordering_quantity']=$orderGoodsList['initial_ordering_quantity'][$i];
        $array['ordering_quantity'] = $orderGoodsList['ordering_quantity'][$i];
if($orderGoodsList['initial_ordering_quantity'][$i]==0){
    doOrderStock($userId,$array);
    }
    else{
        stockListFromPlacedEditDelete($userId,$array);
    }
}
}