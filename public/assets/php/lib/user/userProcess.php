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
    $history = []; 
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
                    WHERE ordering.orderer = ? ORDER BY category.category_id;"
            ;
            $stmt = $pdo->prepare($sql);
            $res  = $stmt->execute(array($userId));
            if(!$res) throw new Exception("[{$functionName}]:SELECT文実行時にエラーが発生しました。");
        }else{
            $sql = "SELECT category_name,color,goods_name,unit_price,
                           ordering_quantity,(unit_price*ordering_quantity) AS amount
                    FROM  ordering_list
                    INNER JOIN monthly_goods ON ordering_list.monthly_goods_id = monthly_goods.monthly_goods_id
                    INNER JOIN ordering      ON ordering_list.ordering_id      = ordering.ordering_id
                    INNER JOIN category      ON monthly_goods.category_id      = category.category_id
                    INNER JOIN monthly       ON ordering.monthly_id            = monthly.monthly_id
                    WHERE ordering.orderer = ? AND ordering.monthly_id = ? ORDER BY category.category_id;"
            ;
            $stmt = $pdo->prepare($sql);
            $res  = $stmt->execute(array($userId,$monthlyId));
            if(!$res) throw new Exception("[{$functionName}]:SELECT文実行時にエラーが発生しました。");
        }
        while ($row = $stmt->fetch()) {
            $history[] =$row; 
        }
        return $history; 
    }catch(Exception $e){
        throw $e;
    }
}

function getHistoryMonthlyList($userId){
// TODO : sql
/*
select sum((unit_price*ordering_quantity)) as total, date from ordering
inner join ordering_list on ordering_list.ordering_id = ordering.ordering_id
inner join monthly_goods on ordering_list.monthly_goods_id = monthly_goods.monthly_goods_id
inner join monthly on ordering.monthly_id = monthly.monthly_id
where ordering.order_flag = 3 and ordering.orderer = 163 and monthly_goods.monthly_id= 24 ここを副問合せにする;
*/
    $historyList  = [];
    $functionName = 'getHistoryMonthlyList';
    try {
        $pdo  = connectDb('coop');
        $sql  = "SELECT monthly_id, date FROM monthly ORDER BY date DESC;";
        $stmt = $pdo->prepare($sql);
        $res  = $stmt->execute(null);
        if(!$res) throw new Exception("[{$functionName}]:月別ID取得時にエラーが発生しました。");
        foreach ($stmt as $monthly)
        {
            $tmp          = $monthly;
            $tmp['total'] = 0;

            $sql = "SELECT SUM((unit_price * ordering_quantity)) AS total FROM ordering
                    INNER JOIN ordering_list ON ordering_list.ordering_id      = ordering.ordering_id
                    INNER JOIN monthly_goods ON ordering_list.monthly_goods_id = monthly_goods.monthly_goods_id
                    INNER JOIN monthly       ON ordering.monthly_id            = monthly.monthly_id
                    WHERE  ordering.orderer         = ?
                    AND    ordering.monthly_id = ?;"
            ;
            $stmt = $pdo->prepare($sql);
            $res  = $stmt->execute([$userId, $monthly['monthly_id'],]);
            if(!$res) throw new Exception("[{$functionName}]:合計金額取得時にエラーが発生しました。");
            $total = $stmt->fetchColumn();
            if($total) $tmp['total'] = $total;
            $historyList[] = $tmp;
        }
    } catch (Exception $e) {
        throw $e;
    }
    return $historyList;
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
    $order_flag   = [];
    $order = [];
    $currentMonthList=[];
    try{
        $pdo = connectDb('cooopshinren');
        $sql = "SELECT order_flag FROM ordering WHERE orderer= ?";
        $stmt=$pdo->prepare($sql);
        $res = $stmt->execute(array($userId));
        if(!$res) throw new Exception("[{$functionName}]:SELECT文実行時にエラーが発生しました。");
        $order_flag = $stmt->fetchColumn(); 
    }catch(Exception $e){
        throw $e;
    }

    try{
        $sql = "SELECT monthly_goods_id,goods_name,unit_price,required_quantity,category_name,color
                FROM monthly_goods NATURAL JOIN monthly NATURAL JOIN category 
                WHERE public_flag=1 AND fixed_flag = 0 ORDER BY category.category_id;"
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
                WHERE ordering.orderer = ? AND monthly.public_flag=1 ORDER BY category.category_id;"
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
    $order_flag   = []; 
    $stockList = [];
    try{
        $pdo = connectDb('cooopshinren');
        $sql = "SELECT order_flag FROM ordering WHERE orderer= ?";
        $stmt= $pdo->prepare($sql);
        $res = $stmt->execute(array($userId));
//        if(!$res) throw new Exception("DB接続時にエラーが発生しました。");
        if(!$res) throw new Exception("[{$functionName}]:SELECT文実行時にエラーが発生しました。");

        while ($row = $stmt->fetch()) {
            $order_flag =$row[0]; 
        }
    }catch(Exception $e){
        throw $e;
    }
    try{
        if ($monthlyId == 0){
            $sql = "SELECT stock_list.monthly_goods_id,goods_name,unit_price,
                    stock_quantity,category_name,color
                    FROM stock_list
                    INNER JOIN monthly_goods ON stock_list.monthly_goods_id = monthly_goods.monthly_goods_id
                    INNER JOIN category ON monthly_goods.category_id = category.category_id;"
            ;
            $stmt = $pdo->prepare($sql);
            $res  = $stmt->execute(null);
            if(!$res) throw new Exception("[{$functionName}]:SELECT文実行時にエラーが発生しました。");

        }else{
            $sql = "SELECT stock_list.monthly_goods_id,goods_name,unit_price,
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

    if($order_flag != 3) return $stockList; 

    try{
        $sql = "SELECT
                ordering_list.monthly_goods_id,ordering_quantity
                FROM  ordering_list
                INNER JOIN monthly_goods ON ordering_list.monthly_goods_id = monthly_goods.monthly_goods_id
                INNER JOIN ordering ON ordering_list.ordering_id = ordering.ordering_id
                INNER JOIN category ON monthly_goods.category_id = category.category_id
                INNER JOIN monthly ON ordering.monthly_id =monthly.monthly_id
                WHERE ordering.orderer = ?;"
//                WHERE ordering.orderer = ? AND monthly.public_flag=1;"
        ;
        $stmt=$pdo->prepare($sql);
        $res= $stmt->execute(array($userId));
        if(!$res) throw new Exception("[{$functionName}]:SELECT文実行時にエラーが発生しました。");

        while ($row = $stmt->fetch()) {
            $currentMonthList[] =$row;
        }


        for ($i=0; $i < count($stockList); $i++) {
            for ($j=0; $j < count($currentMonthList); $j++) {
                if($stockList[$i]['monthly_goods_id']==$currentMonthList[$j]['monthly_goods_id']){
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
    $ordering_id = '';
    $errorMessage = [];
    try{
        $pdo  = connectDb('cooopshinren');
        $sql  = "SELECT ordering_id FROM ordering NATURAL JOIN monthly WHERE public_flag = 1 AND orderer =?;";
        $stmt = $pdo->prepare($sql);
        $res  = $stmt->execute(array($userId));
        if(!$res) throw new Exception("[{$functionName}]:SELECT文実行時にエラーが発生しました。");

        while ($row = $stmt->fetch()) {
            $ordering_id = $row[0]; 
        }

        $sql  = "UPDATE ordering SET order_flag = 3 WHERE ordering.ordering_id=?;";
        $stmt = $pdo->prepare($sql);
        $res  = $stmt->execute(array($ordering_id));
        if(!$res) throw new Exception("[{$functionName}]:UPDATE文実行時にエラーが発生しました。");

        for($i = 0; $i < count($orderGoodsList['monthly_goods_id']); $i++){
            $quantaity = intval($orderGoodsList["ordering_quantity"][$i]);
            if($quantaity > 0){
                $sql   = "INSERT INTO ordering_list VALUES (NULL,?,?,?);";
                $stmt  = $pdo->prepare($sql);
                $param = [
                    $orderGoodsList['monthly_goods_id'],     /* monthly_goods_id */
                    $ordering_id,                                    /* ordering_id */ // TODO: エラー発生場所 ordering_idはどうやって取得してる？ teshima 2017/04/03
                                                            
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
    $ordering_id = ''; // TODO: $renのリネーム kawanishi 2017/04/04
    try{
        $pdo  = connectDb('cooopshinren');
        $sql  = "SELECT ordering_id FROM ordering NATURAL JOIN monthly WHERE public_flag = 1 AND orderer =?;";
        $stmt = $pdo->prepare($sql);
        $res  = $stmt->execute(array($userId));
        $ordering_id  = $stmt->fetchColumn();

        for($i = 0; $i < count($newOrderGoodsList['monthly_goods_id']); $i++){
            $goodsId   = $newOrderGoodsList['monthly_goods_id'];
            $quantaity = intval($newOrderGoodsList["ordering_quantity"]);
            $sql       = "SELECT stock_quantity FROM stock_list WHERE monthly_goods_id=?;";
            $stmt      = $pdo->prepare($sql);
            $res       = $stmt->execute(array($goodsId));
            if(!$res) $errorMessage[] = ("[{$functionName}]:SELECT文実行時にエラーが発生しました。");

            $stock     = $stmt->fetchColumn();

            if($quantaity > 0 && $stock-$quantaity >= 0){
                $sql        = "INSERT INTO ordering_list VALUES (NULL,?,?,?);";
                $stmt       = $pdo->prepare($sql);
                $res        = $stmt->execute(array($goodsId,$ordering_id,$quantaity));
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
    $ordering_id = '';
    try{
        $pdo  = connectDb('cooopshinren');
        $sql  = "SELECT ordering_id FROM ordering NATURAL JOIN monthly WHERE public_flag = 1 AND orderer =?;";
        $stmt = $pdo->prepare($sql);
        $res  = $stmt->execute(array($userId));
        if(!$res) throw new Exception("[{$functionName}]:SELECT文実行時にエラーが発生しました。");

        $ordering_id  = $stmt->fetchColumn(); 
    }catch(Exception $e){
        throw $e;
    }
    for($i = 0; $i < count($editOrderGoodsList['monthly_goods_id']); $i++){
        if($editOrderGoodsList["ordering_quantity"]==0){
            try{
                $sql  = "DELETE FROM ordering_list WHERE monthly_goods_id =? AND ordering_id =?;";
                $stmt = $pdo->prepare($sql);
                $res  = $stmt->execute(array($editOrderGoodsList['monthly_goods_id'],$ordering_id)); 
                if(!$res) throw new Exception("[{$functionName}]:monthly_goods_id[{$$editOrderGoodsList['monthly_goods_id']}]削除時にエラーが発生しました。");
            }catch(Exception $e){
                throw $e;
            }
        }else{
            try{
                $sql  = "UPDATE ordering_list SET ordering_quantity=? WHERE monthly_goods_id =? AND ordering_id =?;";
                $stmt = $pdo->prepare($sql);
                $res  = $stmt->execute(array($editOrderGoodsList['ordering_quantity'],$editOrderGoodsList['monthly_goods_id'],$ordering_id)); 
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
    $ordering_id = ''; // TODO: $renのリネーム kawanishi 2017/04/04
    try{
        $pdo = connectDb('cooopshinren');
        $sql = "SELECT ordering_id FROM ordering NATURAL JOIN monthly WHERE public_flag = 1 AND orderer =?;";
        $stmt= $pdo->prepare($sql);
        $res = $stmt->execute(array($userId));
        if(!$res) throw new Exception("[{$functionName}]:SELECT文実行時にエラーが発生しました。");
        $ordering_id = $stmt->fetchColumn(); // TODO: $renのリネーム kawanishi 2017/04/04
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
                $res   = $stmt->execute(array($editOrderGoodsList['monthly_goods_id'],$ordering_id)); 
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
                $sql   = "UPDATE ordering_list SET ordering_quantity=? WHERE monthly_goods_id =? AND ordering_id =?;";
                $stmt  = $pdo->prepare($sql);
                $param = [
                    $editOrderGoodsList['ordering_quantity'],   /* ordering_quantity */
                    $editOrderGoodsList['monthly_goods_id'],    /* monthly_goods_id */
                    $ordering_id,                                       /* ordering_id */
                ];
                $res   = $stmt->execute($param); 
                if(!$res) throw new Exception("[{$functionName}]:ID[{$editOrderGoodsList['monthly_goods_id'][$i]}]更新時にエラーが発生しました。");
                $stock = $stock + $editOrderGoodsList['initial_ordering_quantity'][$i] - $editOrderGoodsList['ordering_quantity'];
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
            if(!empty($error)) $errorMessage[] = $error;
        }
        else{
            $error = currentMonthListFromPlacedEdit($userId,$array);
            if(!empty($error)) $errorMessage[] = $error;
        }
    }
    return $errorMessage;
}

//在庫リストから発注するときに新規かどうか判断する関数
function stockListFromOrderWhenNewlyDetermineWhether($userId,$orderGoodsList)
{
    $errorMessage = [];
    $bufLen       = 0;
    var_dump($userId);
    try {
        for($i = 0; $i < count($orderGoodsList['monthly_goods_id']); $i++){
            $array = [
                'monthly_goods_id'         => $orderGoodsList['monthly_goods_id'][$i],
                'initial_ordering_quantity'=> $orderGoodsList['initial_ordering_quantity'][$i],
                'ordering_quantity'        => $orderGoodsList['ordering_quantity'][$i],
            ];
            if($orderGoodsList['initial_ordering_quantity'][$i]==0){
                $errors = doOrderStock($userId,$array);
                foreach ($errors as $error) $bufLen += mb_strlen($error);
                if($bufLen > 0) $errorMessage[] = $error;
            }
            else{
                $errors = stockListFromPlacedEditDelete($userId,$array);
                foreach ($errors as $error) $bufLen += mb_strlen($error);
                if($bufLen > 0) $errorMessage[] = $error;
            }
        }
    } catch (Exception $e) {
        throw $e;
    }
    return $errorMessage;
}
//今月は注文しないフラグを立てる関数
function noOrder($userId){
    $pdo = connectDb('cooopshinren');
    $functionName = 'noOrder';
    try{
       $pdo  = connectDb('cooopshinren');
       $sql  = "SELECT ordering_id FROM ordering NATURAL JOIN monthly WHERE public_flag = 1 AND orderer =?;";
       $stmt = $pdo->prepare($sql);
       $res  = $stmt->execute(array($userId));
       if(!$res) throw new Exception("[{$functionName}]:SELECT文実行時にエラーが発生しました。");

       while ($row = $stmt->fetch()) {
        $ordering_id = $row[0]; 
    }

    $sql  = "UPDATE ordering SET order_flag = 2 WHERE ordering.ordering_id=? AND order_flag != 3;";
    $stmt = $pdo->prepare($sql);
    $res  = $stmt->execute(array($ordering_id));
    if(!$res) throw new Exception("[{$functionName}]:UPDATE文実行時にエラーが発生しました。");
    }catch (Exception $e) {
        throw $e;
    }
}