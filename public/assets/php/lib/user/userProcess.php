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
//一つ当たりの量（detail_amount_per_one）必要量（required_quantity),カテゴリ名（category_name）,色（color）発注数（）
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
            for ($j=0; $j < count($currentMonthList); $j++) { 
                if($order[$i][0]==$currentMonthList[$j][0]){
                    $order[$i]['ordering_quantity']=$currentMonthList[$j][1];
                    $order[$i][7]=$currentMonthList[$j][1];
                }else{
                     $order[$i]['ordering_quantity']=0;
                    $order[$i][7]=0;
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
//一つ当たりの量(detail_amount_per_one）,在庫量（stock_quantity）,カテゴリ名（category_name）,色（color）の配列を返す

function returnStockList($monthlyId){
    $ren = [];
    try{
        $pdo = connectDb('cooopshinren');
        if ($monthlyId==0){
            $sql = "SELECT stock_list.monthly_goods_id,goods_name,unit_price,detail_amount_per_one,
            stock_quantity,category_name,color
            FROM stock_list
            INNER JOIN monthly_goods ON stock_list.monthly_goods_id = monthly_goods.monthly_goods_id
            INNER JOIN category ON monthly_goods.category_id = category.category_id WHERE stock_quantity>0;";
            $stmt=$pdo->prepare($sql);
            $res= $stmt->execute(null);
        }else{
            $sql = "SELECT stock_list.monthly_goods_id,goods_name,unit_price,detail_amount_per_one,
            stock_quantity,category_name,color
            FROM stock_list
            INNER JOIN monthly_goods ON stock_list.monthly_goods_id = monthly_goods.monthly_goods_id
            INNER JOIN category ON monthly_goods.category_id = category.category_id 
            WHERE stock_list.monthly_id =? AND stock_quantity>0;";
            $stmt=$pdo->prepare($sql);
            $res= $stmt->execute(array($monthlyId));
        }

        while ($row = $stmt->fetch()) {
            $ren[] =$row;
        }
        return $ren;
    }catch(Exception $e){
        echo $e->getMessage;
    }
}

//受け取ったデータを基に発注を行う関数
//今月のリストから発注する
//ユーザーIDと配列（商品ＩＤと個数）を受け取り登録する
function doOrder($userId,$orderGoodsList){
    try{
     $pdo = connectDb('cooopshinren');
     $sql="SELECT MAX(ordering_id) FROM ordering WHERE orderer = ?";
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
function doOrderStock($userId,$orderGoodsList)
{
    $errorMessage = array();
    try{
     $pdo = connectDb('cooopshinren');
     $sql="SELECT MAX(ordering_id) FROM ordering WHERE orderer = ?";
     $stmt=$pdo->prepare($sql);
     $res= $stmt->execute(array($userId));
     while ($row = $stmt->fetch()) {
        $ren =$row[0];
    }

    for($i = 0; $i < count($orderGoodsList['monthly_goods_id']); $i++){
        $goodsId = $orderGoodsList['monthly_goods_id'][$i];
        $quantaity = intval($orderGoodsList["ordering_quantity"][$i]);
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
