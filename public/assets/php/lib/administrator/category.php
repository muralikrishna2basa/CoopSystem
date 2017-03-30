<?php
function insertCategory($post)
{
    try{
        if(!isset($post['category_name']) || mb_strlen($post['category_name']) === 0)   throw new Exception("カテゴリ名が入力されていません。");
        if(!isset($post['color'])         || !ereg("#[a-fA-F0-9]{6}$", $post['color'])) throw new Exception("カラーコードが入力されていないか、カラーコード形式ではないようです。");
        $pdo   = connectDb('coop');
        $sql   = "INSERT INTO category (category_name, color) VALUES(?, ?);";
        $stmt  = $pdo->prepare($sql);
        $array = [$post['category_name'], $post['color']];
        $res   = $stmt->execute($array);
        if($res === false) throw new Exception("追加処理に失敗しました。");
    }catch (Exception $e){
        echo $e->getMessage();
    }
}

function updateCategory($post)
{

    try{
        $pdo   = connectDb('coop');
        $error = [];
        for ($i = 0; $i < count($post['category_id']); $i++)
        {

            $flag = true;
//            if(!isset($post['category_id'][$i]))                                                    throw new Exception("カテゴリIDが存在しません。");
//            if(!isset($post['category_name'][$i]) || mb_strlen($post['category_name'][$i]) === 0)   throw new Exception("カテゴリ名が入力されていません。");
//            if(!isset($post['color'][$i])         || !ereg("#[a-fA-F0-9]{6}$", $post['color'][$i])) throw new Exception("カラーコードが入力されていないか、カラーコード形式ではないようです。");

            if(!isset($post['category_id'][$i])) $flag = false;
            if(!isset($post['category_name'][$i]) || mb_strlen($post['category_name'][$i]) === 0) $flag = false;
            if(!isset($post['color'][$i])         || !ereg("#[a-fA-F0-9]{6}$", $post['color'][$i])) $flag = false;

            if($flag)
            {
                $id    = $post['category_id'][$i];
                $name  = $post['category_name'][$i];
                $color = $post['color'][$i];

                $sql   = "UPDATE category SET category_name=?, color=? WHERE category_id=?;";
                $array = [$name, $color, $id];
                if(isset($post['delete_'.$id]) && $post['delete_'.$id] === 'on')
                {
                    $sql   = "DELETE FROM category WHERE category_id=?;";
                    $array = [$id];
                }
                $stmt  = $pdo->prepare($sql);
                $res = true;
                $res   = $stmt->execute($array);
                if(!$res) $error[] = "{$id}の処理に失敗しました。";
//                var_dump($sql);
            }
            else{
                $error[] = "ID:".($i+1)."は処理に失敗しました。";
            }
        }
        var_dump($error);

    }catch (Exception $e){
        echo $e->getMessage();
    }
}

?>