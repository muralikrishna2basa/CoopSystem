<?php
/**
 * [displayHistory ユーザーの履歴を返す関数]
 * @param  [type]  $userId    [description]
 * @param  integer $monthlyId [optional]
 * @return array   [カテゴリ名（category_name）,色（color）,商品名（goods_name）,単価（unit_price,）,
 *                  発注数（ordering_quantity）,合計金額（(amount）]
 */
function displayHistory($userId,$monthlyId = 0){
    $functionName = 'displayHistory';
    $ren = []; // TODO: $renのリネーム kawanishi 2017/04/04
    $errorMessage = [];
    try{
        $pdo = connectDb('cooopshinren');
        if(intval($monthlyId) === 0){
            $sql = "SELECT category_name,color,goods_name,unit_price,
                    ordering_quantity,(unit_price*ordering_quantity) AS amount
                    FROM  ordering_list
                    INNER JOIN monthly_goods ON ordering_list.monthly_goods_id = monthly_goods.monthly_goods_id
                    INNER JOIN ordering ON ordering_list.ordering_id = ordering.ordering_id
                    INNER JOIN category ON monthly_goods.category_id = category.category_id
                    INNER JOIN monthly ON ordering.monthly_id =monthly.monthly_id
                    WHERE ordering.orderer = ?;"
            ;
            $stmt = $pdo->prepare($sql);
            $res  = $stmt->execute(array($userId));
            if(!$res) throw new Exception("[{$functionName}]:SELECT文実行時にエラーが発生しました。");
        }else{
            $sql = "SELECT category_name,color,goods_name,unit_price,
                    ordering_quantity,(unit_price*ordering_quantity)AS amount
                    FROM  ordering_list
                    INNER JOIN monthly_goods ON ordering_list.monthly_goods_id = monthly_goods.monthly_goods_id
                    INNER JOIN ordering ON ordering_list.ordering_id = ordering.ordering_id
                    INNER JOIN category ON monthly_goods.category_id = category.category_id
                    INNER JOIN monthly ON ordering.monthly_id =monthly.monthly_id
                    WHERE ordering.orderer = ? AND ordering.monthly_id=?;"
            ;
            $stmt = $pdo->prepare($sql);
            $res  = $stmt->execute(array($userId,$monthlyId));
            if(!$res) throw new Exception("[{$functionName}]:SELECT文実行時にエラーが発生しました。");

            if(!$res) throw new Exception("DB接続時にエラーが発生しました。");
        }
        while ($row = $stmt->fetch()) {
            $ren[] =$row; // TODO: $renのリネーム kawanishi 2017/04/04
        }
        return $ren; // TODO: $renのリネーム kawanishi 2017/04/04
    }catch(Exception $e){
        throw $e;
    }
}
/**
 * [returnCurrentMonthProductList 今月のリストを返す関数]
 * @param  [type] $userId [description]
 * @return array  [月別商品ID（monthly_goods_id）,商品名（goods_name）,単価（unit_price）,
 *                 一つ当たりの量（detail_amount_per_one）必要量（required_quantity),
 *                 カテゴリ名（category_name）,色（color）発注数（ordering_quantity）]
 */
function returnCurrentMonthProductList($userId){
    $functionName = 'returnCurrentMonthProductList';
    $ren   = [];// TODO: $renのリネーム kawanishi 2017/04/04
    $order = [];
    try{
        $pdo = connectDb('cooopshinren');
        $sql = "SELECT order_flag FROM ordering WHERE orderer= ?";
        $stmt=$pdo->prepare($sql);
        $res = $stmt->execute(array($userId));
        if(!$res) throw new Exception("[{$functionName}]:SELECT文実行時にエラーが発生しました。");
        $ren = $stmt->fetchColumn(); // TODO: $renのリネーム kawanishi 2017/04/04
    }catch(Exception $e){
        throw $e;
    }

    try{
        $sql = "SELECT monthly_goods_id,goods_name,unit_price,detail_amount_per_one, required_quantity,category_name,color
                FROM monthly_goods NATURAL JOIN monthly NATURAL JOIN category WHERE public_flag=1;"
        ;
        $stmt=$pdo->prepare($sql);
        $res= $stmt->execute(null);
        if(!$res) throw new Exception("[{$functionName}]:SELECT文実行時にエラーが発生しました。");

        while ($row = $stmt->fetch()) {
            $order[] =$row;
        }

    }catch(Exception $e){
        throw $e;
    }
    for ($i=0; $i <count($order) ; $i++) {
        $order[$i]['ordering_quantity'] = 0;
        $order[$i][7]                   = 0;
    }

    try{
        $sql = "SELECT
                ordering_list.monthly_goods_id,ordering_quantity
                FROM  ordering_list
                INNER JOIN monthly_goods ON ordering_list.monthly_goods_id = monthly_goods.monthly_goods_id
                INNER JOIN ordering ON ordering_list.ordering_id = ordering.ordering_id
                INNER JOIN category ON monthly_goods.category_id = category.category_id
                INNER JOIN monthly ON ordering.monthly_id =monthly.monthly_id
                WHERE ordering.orderer = ? AND monthly.public_flag=1;"
        ;
        $stmt= $pdo->prepare($sql);
        $res = $stmt->execute(array($userId));
        if(!$res) throw new Exception("[{$functionName}]:SELECT文実行時にエラーが発生しました。");

        while ($row = $stmt->fetch()) {
            $currentMonthList[] =$row;
        }


        for ($i=0; $i <count($order) ; $i++) {
            for ($j=0; $j < count($currentMonthList); $j++) {
                if($order[$i][0] == $currentMonthList[$j][0]){
                    $order[$i]['ordering_quantity'] = $currentMonthList[$j][1];
                    $order[$i][7]                   = $currentMonthList[$j][1];
                }
           }
        }
        return $order;
    }catch(Exception $e){
        throw $e;
    }
}

/**
 * [returnStockList 在庫リストを返す関数]
 * @param  [type] $userId    [description]
 * @param  [type] $monthlyId [description]
 * @return [array] [月別商品ID（monthly_goods_id）,商品名（goods_name）,単価（unit_price）,
 *                  一つ当たりの量(detail_amount_per_one）,在庫量（stock_quantity）,
 *                  カテゴリ名（category_name）,色（color）,発注数（ordering_quantity）]
 */
function returnStockList($userId,$monthlyId = 0){
    $functionName = 'returnStockList';
    $ren       = []; // TODO: $renのリネーム kawanishi 2017/04/04
    $stockList = [];
    try{
        $pdo = connectDb('cooopshinren');
        $sql = "SELECT order_flag FROM ordering WHERE orderer= ?";
        $stmt= $pdo->prepare($sql);
        $res = $stmt->execute(array($userId));
//        if(!$res) throw new Exception("DB接続時にエラーが発生しました。");
        if(!$res) throw new Exception("[{$functionName}]:SELECT文実行時にエラーが発生しました。");

        while ($row = $stmt->fetch()) {
            $ren =$row[0]; // TODO: $renのリネーム kawanishi 2017/04/04
        }
    }catch(Exception $e){
        throw $e;
    }
    try{
        if ($monthlyId == 0){
            $sql = "SELECT stock_list.monthly_goods_id,goods_name,unit_price,detail_amount_per_one,
                    stock_quantity,category_name,color
                    FROM stock_list
                    INNER JOIN monthly_goods ON stock_list.monthly_goods_id = monthly_goods.monthly_goods_id
                    INNER JOIN category ON monthly_goods.category_id = category.category_id;"
            ;
            $stmt = $pdo->prepare($sql);
            $res  = $stmt->execute(null);
            if(!$res) throw new Exception("[{$functionName}]:SELECT文実行時にエラーが発生しました。");

        }else{
            $sql = "SELECT stock_list.monthly_goods_id,goods_name,unit_price,detail_amount_per_one,
                    stock_quantity,category_name,color
                    FROM stock_list
                    INNER JOIN monthly_goods ON stock_list.monthly_goods_id = monthly_goods.monthly_goods_id
                    INNER JOIN category ON monthly_goods.category_id = category.category_id
                    WHERE stock_list.monthly_id =?;"
            ;
            $stmt = $pdo->prepare($sql);
            $res  = $stmt->execute(array($monthlyId)); // TODO: monthly_idの削除 kawanishi 2017/04/03
            if(!$res) throw new Exception("[{$functionName}]:SELECT文実行時にエラーが発生しました。");

        }

        while ($row = $stmt->fetch()) {
            $stockList[] =$row;
        }

    }catch(Exception $e){
        throw $e;
    }

    for ($i=0; $i <count($stockList) ; $i++) {
        $stockList[$i]['ordering_quantity'] = 0;
        $stockList[$i][7]                   = 0;
    }

    if($ren != 3) return $stockList; // TODO: $renのリネーム kawanishi 2017/04/04

    try{
        $sql = "SELECT
                ordering_list.monthly_goods_id,ordering_quantity
                FROM  ordering_list
                INNER JOIN monthly_goods ON ordering_list.monthly_goods_id = monthly_goods.monthly_goods_id
                INNER JOIN ordering ON ordering_list.ordering_id = ordering.ordering_id
                INNER JOIN category ON monthly_goods.category_id = category.category_id
                INNER JOIN monthly ON ordering.monthly_id =monthly.monthly_id
                WHERE ordering.orderer = ? AND monthly.public_flag=1;"
        ;
        $stmt=$pdo->prepare($sql);
        $res= $stmt->execute(array($userId));
        if(!$res) throw new Exception("[{$functionName}]:SELECT文実行時にエラーが発生しました。");

        while ($row = $stmt->fetch()) {
            $currentMonthList[] =$row;
        }


        for ($i=0; $i <count($stockList) ; $i++) {
            for ($j=0; $j < count($currentMonthList); $j++) {
                if($stockList[$i][0]==$currentMonthList[$j][0]){
                    $stockList[$i]['ordering_quantity'] = $currentMonthList[$j][1];
                    $stockList[$i][7]                   = $currentMonthList[$j][1];
                }
           }
        }
    }catch(Exception $e){
        throw $e;
    }
    return $stockList;
}

//受け取ったデータを基に発注を行う関数
//今月のリストから発注する
//ユーザーIDと配列（商品ＩＤと個数）を受け取り登録する
function doOrder($userId,$orderGoodsList){
    $functionName = 'doOrder';
    $ren = ''; // TODO: $renのリネーム kawanishi 2017/04/04
    $errorMessage = [];
    try{
        $pdo  = connectDb('cooopshinren');
        $sql  = "SELECT ordering_id FROM ordering NATURAL JOIN monthly WHERE public_flag = 1 AND orderer =?;";
        $stmt = $pdo->prepare($sql);
        $res  = $stmt->execute(array($userId));
        if(!$res) throw new Exception("[{$functionName}]:SELECT文実行時にエラーが発生しました。");

        while ($row = $stmt->fetch()) {
            $ren = $row[0]; // TODO: $renのリネーム kawanishi 2017/04/04
        }

        $sql  = "UPDATE ordering SET order_flag = 3 WHERE ordering.ordering_id=?;";
        $stmt = $pdo->prepare($sql);
        $res  = $stmt->execute(array($ren)); // TODO: $renのリネーム kawanishi 2017/04/04
        if(!$res) throw new Exception("[{$functionName}]:SELECT文実行時にエラーが発生しました。");

        for($i = 0; $i < count($orderGoodsList['monthly_goods_id']); $i++){
            $quantaity = intval($orderGoodsList["ordering_quantity"][$i]);
            if($quantaity > 0){
                $sql   = "INSERT INTO ordering_list VALUES (NULL,?,?,?);";
                $stmt  = $pdo->prepare($sql);
                $param = [
                    $orderGoodsList['monthly_goods_id'],     /* monthly_goods_id */
                    $ren,                                    /* ordering_id */ // TODO: エラー発生場所 ordering_idはどうやって取得してる？ teshima 2017/04/03
                                                             // TODO: $renのリネーム kawanishi 2017/04/04
                    $quantaity,                              /* ordering_quantity */
                ];
                $res   = $stmt->execute($param);
                if(!$res) $errorMessage[] = "[{$functionName}]:monthly_goods_id[{$orderGoodsList['monthly_goods_id'][$i]}]でエラーが発生しました。";
            }
        }
    }catch(Exception $e){
        throw $e;
    }
    return $errorMessage;
}

// 在庫から発注する関数
// $orderStockGoodsListは（商品ＩＤ、発注数）
function doOrderStock($userId,$newOrderGoodsList){
    $functionName = 'doOrderStock';
    $errorMessage = array();
    $ren = ''; // TODO: $renのリネーム kawanishi 2017/04/04
    try{
        $pdo  = connectDb('cooopshinren');
        $sql  = "SELECT ordering_id FROM ordering NATURAL JOIN monthly WHERE public_flag = 1 AND orderer =?;";
        $stmt = $pdo->prepare($sql);
        $res  = $stmt->execute(array($userId));
        $ren  = $stmt->fetchColumn(); // TODO: $renのリネーム kawanishi 2017/04/04

        for($i = 0; $i < count($newOrderGoodsList['monthly_goods_id']); $i++){
            $goodsId   = $newOrderGoodsList['monthly_goods_id'];
            $quantaity = intval($newOrderGoodsList["ordering_quantity"]);
            $sql       = "SELECT stock_quantity FROM stock_list WHERE monthly_goods_id=?;";
            $stmt      = $pdo->prepare($sql);
            $res       = $stmt->execute(array($goodsId));
            if(!$res) throw new Exception("[{$functionName}]:SELECT文実行時にエラーが発生しました。");

            $stock     = $stmt->fetchColumn();

            if($quantaity > 0 && $stock-$quantaity >= 0){
                $sql        = "INSERT INTO ordering_list VALUES (NULL,?,?,?);";
                $stmt       = $pdo->prepare($sql);
                $res        = $stmt->execute(array($goodsId,$ren,$quantaity)); // TODO: $renのリネーム kawanishi 2017/04/04
                $sql        = "UPDATE stock_list SET stock_quantity = ? WHERE stock_list.monthly_goods_id= ?;";
                $stmt       = $pdo->prepare($sql);
                $difference = $stock-$quantaity;
                $res        = $stmt->execute(array($difference,$goodsId));
                if(!$res) $errorMessage[] = "monthly_goods_id[{$goodsId}]でエラーが発生しました。";
            }

            if($stock-$quantaity < 0){
                $sql = "SELECT goods_name FROM monthly_goods WHERE monthly_goods_id = ?;";
                $stmt= $pdo->prepare($sql);
                $res = $stmt->execute(array($goodsId));
                while ($row = $stmt->fetch()) {
                    $str =$row[0]."は在庫以上の数を発注しようとしたためエラーが起きました";
                    $errorMessage[] = $str;
                }
            }
        }
    }catch(Exception $e){
        throw $e;
    }
    return $errorMessage;
}
//今月のリストから発注したものを編集削除する関数
//$editOrderGoodsList は 商品ＩＤ 変更後の発注数 変更前の発注数
function currentMonthListFromPlacedEdit($userId,$editOrderGoodsList){
    $functionName = 'currentMonthListFromPlacedEdit';
    $ren = ''; // TODO: $renのリネーム kawanishi 2017/04/04
    try{
        $pdo  = connectDb('cooopshinren');
        $sql  = "SELECT ordering_id FROM ordering NATURAL JOIN monthly WHERE public_flag = 1 AND orderer =?;";
        $stmt = $pdo->prepare($sql);
        $res  = $stmt->execute(array($userId));
        if(!$res) throw new Exception("[{$functionName}]:SELECT文実行時にエラーが発生しました。");

        $ren  = $stmt->fetchColumn(); // TODO: $renのリネーム kawanishi 2017/04/04
    }catch(Exception $e){
        throw $e;
    }
    for($i = 0; $i < count($editOrderGoodsList['monthly_goods_id']); $i++){
        if($editOrderGoodsList["ordering_quantity"]==0){
            try{
                $sql  = "DELETE FROM ordering_list WHERE monthly_goods_id =? AND ordering_id =?;";
                $stmt = $pdo->prepare($sql);
                $res  = $stmt->execute(array($editOrderGoodsList['monthly_goods_id'],$ren)); // TODO: $renのリネーム kawanishi 2017/04/04
                if(!$res) throw new Exception("[{$functionName}]:monthly_goods_id[{$$editOrderGoodsList['monthly_goods_id']}]削除時にエラーが発生しました。");
            }catch(Exception $e){
                throw $e;
            }
        }else{
            try{
                $sql  = "UPDATE ordering_list SET ordering_quantity=? WHERE monthly_goods_id =? AND ordering_id =?;";
                $stmt = $pdo->prepare($sql);
                $res  = $stmt->execute(array($editOrderGoodsList['ordering_quantity'],$editOrderGoodsList['monthly_goods_id'],$ren)); // TODO: $renのリネーム kawanishi 2017/04/04
                if(!$res) throw new Exception("[{$functionName}]:monthly_goods_id[{$editOrderGoodsList['monthly_goods_id']}]更新時にエラーが発生しました。");
            }catch(Exception $e){
                throw $e;
            }
        }
    }
}

//在庫リストから発注したものを編集削除する関数
//$editOrderGoodsList は 商品ＩＤ 変更後の発注数 変更前の発注数
function stockListFromPlacedEditDelete($userId,$editOrderGoodsList)
{
    $functionName = 'stockListFromPlacedEditDelete';
    $errorMessage = array();
    $ren = ''; // TODO: $renのリネーム kawanishi 2017/04/04
    try{
        $pdo = connectDb('cooopshinren');
        $sql = "SELECT ordering_id FROM ordering NATURAL JOIN monthly WHERE public_flag = 1 AND orderer =?;";
        $stmt= $pdo->prepare($sql);
        $res = $stmt->execute(array($userId));
        $ren = $stmt->fetchColumn(); // TODO: $renのリネーム kawanishi 2017/04/04
    }
    catch(Exception $e){
        throw $e;
    }
    for($i = 0; $i < count($editOrderGoodsList['monthly_goods_id']); $i++)
    {
        try{
            $sql = "SELECT stock_quantity FROM stock_list WHERE monthly_goods_id=?;";
            $stmt= $pdo->prepare($sql);
            $res = $stmt->execute(array($editOrderGoodsList['monthly_goods_id']));
            if(!$res) throw new Exception("[{$functionName}]:SELECT文実行時にエラーが発生しました。");

            $stock = $stmt->fetchColumn();
        } catch(Exception $e){
            throw $e;
        }
        if($editOrderGoodsList["ordering_quantity"] == 0)
        {
            try{
                $sql   = "DELETE FROM ordering_list WHERE monthly_goods_id =? AND ordering_id =?;";
                $stmt  = $pdo->prepare($sql);
                $res   = $stmt->execute(array($editOrderGoodsList['monthly_goods_id'],$ren)); // TODO: $renのリネーム kawanishi 2017/04/04
                if(!$res) throw new Exception("[{$functionName}]:ID[{$editOrderGoodsList['monthly_goods_id']}]削除時にエラーが発生しました。");
                $stock = $stock + $editOrderGoodsList['initial_ordering_quantity'][$i];
                $sql   = "UPDATE stock_list SET stock_quantity = ? WHERE stock_list.monthly_goods_id= ?;";
                $stmt  = $pdo->prepare($sql);
                $res   = $stmt->execute(array($stock,$editOrderGoodsList['monthly_goods_id']));
                if(!$res) throw new Exception("[{$functionName}]:ID[{$editOrderGoodsList['monthly_goods_id']}]更新時にエラーが発生しました。");
            }catch(Exception $e){
                throw $e;
            }
        }
        else{
            try {
                $sql  = "UPDATE ordering_list SET ordering_quantity=? WHERE monthly_goods_id =? AND ordering_id =?;";
                $stmt = $pdo->prepare($sql);
                $res  = $stmt->execute(array($editOrderGoodsList['ordering_quantity'][$i],$editOrderGoodsList['monthly_goods_id'][$i],$ren)); // TODO: $renのリネーム kawanishi 2017/04/04
                if(!$res) throw new Exception("[{$functionName}]:ID[{$editOrderGoodsList['monthly_goods_id'][$i]}]更新時にエラーが発生しました。");
                $stock = $stock + $editOrderGoodsList['initial_ordering_quantity'][$i] - $editOrderGoodsList['ordering_quantity'][$i];
            }catch(Exception $e){
                throw $e;
            }
            if($stock >= 0)
            {
                try {
                    $sql  = "UPDATE stock_list SET stock_quantity = ? WHERE stock_list.monthly_goods_id= ?;";
                    $stmt = $pdo->prepare($sql);
                    $res  = $stmt->execute(array($stock,$editOrderGoodsList['monthly_goods_id']));
                    if(!$res) throw new Exception("[{$functionName}]:ID[{$editOrderGoodsList['monthly_goods_id']}]更新時にエラーが発生しました。");
                }catch(Exception $e){
                    throw $e;
                }
            }
            else{
               try {
                    $sql  = "SELECT goods_name FROM `monthly_goods` WHERE monthly_goods_id = ?;";
                    $stmt = $pdo->prepare($sql);
                    $res  = $stmt->execute(array($editOrderGoodsList['monthly_goods_id']));
                    while ($row = $stmt->fetch()) {
                        $str = $row[0]."は在庫以上の数を発注しようとしたためエラーが起きました。";
                        $errorMessage[] = $str;
                    }
                }catch(Exception $e){
                    throw $e;
                }
                try {
                    $sql  = "SELECT goods_name FROM `monthly_goods` WHERE monthly_goods_id = ?;";
                    $stmt = $pdo->prepare($sql);
                    $res  = $stmt->execute(array($editOrderGoodsList['monthly_goods_id']));
                    while ($row = $stmt->fetch()) {
                        $str =$row[0]."は在庫以上の数を発注しようとしたためエラーが起きました。";
                        $errorMessage[] = $str;
                    }
                } catch (Exception $e) {
                    throw $e;
                }
            }
        }
    }
    return $errorMessage;
}
//今月のリストから発注するときに新規かどうか判断する関数
//$OrderGoodsList は 商品ＩＤ 変更後の発注数 変更前の発注数
 function currentMonthListFromOrderWhenNewlyDetermineWhether($userId,$orderGoodsList)
{
    $errorMessage = [];
    for($i = 0; $i < count($orderGoodsList['monthly_goods_id']); $i++){
        $array = [
            'monthly_goods_id'         => $orderGoodsList['monthly_goods_id'][$i],
            'initial_ordering_quantity'=> $orderGoodsList['initial_ordering_quantity'][$i],
            'ordering_quantity'        => $orderGoodsList['ordering_quantity'][$i],
        ];
        if($orderGoodsList['initial_ordering_quantity'][$i]==0){
            $error = doOrder($userId,$array);
            if(mb_strlen($error) > 0) $errorMessage[] = $error;
        }
        else{
            $error = currentMonthListFromPlacedEdit($userId,$array);
            if(mb_strlen($error) > 0) $errorMessage[] = $error;
        }
    }
    return $errorMessage;
}

//在庫リストから発注するときに新規かどうか判断する関数
function stockListFromOrderWhenNewlyDetermineWhether($userId,$orderGoodsList)
{
    $errorMessage = [];
    for($i = 0; $i < count($orderGoodsList['monthly_goods_id']); $i++){
        $array = [
            'monthly_goods_id'         => $orderGoodsList['monthly_goods_id'][$i],
            'initial_ordering_quantity'=> $orderGoodsList['initial_ordering_quantity'][$i],
            'ordering_quantity'        => $orderGoodsList['ordering_quantity'][$i],
        ];
        if($orderGoodsList['initial_ordering_quantity'][$i]==0){
            $error = doOrderStock($userId,$array);
            if(mb_strlen($error) > 0) $errorMessage[] = $error;
        }
        else{
            $error = stockListFromPlacedEditDelete($userId,$array);
            if(mb_strlen($error) > 0) $errorMessage[] = $error;
        }
    }
}