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
//一つ当たりの量（detail_amount_per_one）必要量（required_quantity),カテゴリ名（category_name）,色（color）
//の配列を返す
function returnCurrentMonthProductList(){
//    $ren=array();
    $ren = [];
    try{
        $pdo = connectDb('cooopshinren');
        $sql="SELECT monthly_goods_id,goods_name,unit_price,detail_amount_per_one, required_quantity,category_name,color
        FROM monthly_goods NATURAL JOIN monthly NATURAL JOIN category WHERE public_flag=0;";
        $stmt=$pdo->prepare($sql);
        $res= $stmt->execute(null);
//        foreach ($stmt as $key => $value) {
//            var_dump($value);
//        }

        while ($row = $stmt->fetch()) {
            $ren[] =$row;
//                $ren.=array ($row[0],$row[1],$row[3],$row[4],$row[5],$row[7]);
        }

        return $ren;
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
            INNER JOIN category ON monthly_goods.category_id = category.category_id;";
            $stmt=$pdo->prepare($sql);
            $res= $stmt->execute(null);
        }else{
            $sql = "SELECT stock_list.monthly_goods_id,goods_name,unit_price,detail_amount_per_one,
            stock_quantity,category_name,color
            FROM stock_list
            INNER JOIN monthly_goods ON stock_list.monthly_goods_id = monthly_goods.monthly_goods_id
            INNER JOIN category ON monthly_goods.category_id = category.category_id WHERE stock_list.monthly_id =?;";
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
    foreach ($orderGoodsList as $key1 => $value1) {

        $pdo = connectDb('cooopshinren');
        $sql ="INSERT INTO ordering_list VALUES (NULL,?,?,?);";
        $stmt=$pdo->prepare($sql);
        $res= $stmt->execute(array($key1,$ren,$value1));
    }
    return 5;
}catch(Exception $e){
    echo $e->getMessage;
}

}
function fixedFlagChange($userId,$orderGoodsList){
    try{
       $pdo = connectDb('cooopshinren');
       $sql="SELECT MAX(ordering_id) FROM ordering WHERE orderer = ?;";
       $stmt=$pdo->prepare($sql);
       $res= $stmt->execute(array($userId));
       while ($row = $stmt->fetch()) {
        $ren =$row[0];
    }
    $pdo = connectDb('cooopshinren');
    $sql = "UPDATE ordering SET fixed_flag = 0 WHERE ordering.orderer =?;";
    $stmt=$pdo->prepare($sql);
    $res= $stmt->execute(array($userId));
    $pdo = connectDb('cooopshinren');
    $sql = "UPDATE ordering SET fixed_flag = 1 WHERE ordering.ordering_id=?;";
    $stmt=$pdo->prepare($sql);
    $res= $stmt->execute(array($ren));
}catch(Exception $e){
    echo $e->getMessage;
}

}
