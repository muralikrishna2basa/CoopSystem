<?php
function errorMessages($messages)
{
//    echo "<pre>";var_dump($messages);echo "</pre>";
//    $len = 0;

    $array = [];
    foreach ($messages as $error) {
        if(is_array($error)){
            foreach ($error as $buf) if(mb_strlen($buf) > 0) $array[] = $buf;
        }else{
//            $len += mb_strlen($error);
            if(mb_strlen($error) > 0) $array[] = $error;
        }
    }


    if(count($array) > 0)
    {
        $cnt = 0;
        echo "<div>\n";
        echo "  <dl class=\"toggle-menu has-error\">\n";
        echo "      <dt><h3>エラーが発生しました。</h3></dt>\n";
        echo "      <dd>\n";
        foreach ($array as $buf) {
            echo "          <p>{$buf}</p>\n";
            if($cnt > 20){
                echo "<p>...その他、全部で".count($array)."件のエラーが発生しています。</p>";
                break;
            }
            $cnt++;
        }
        echo "      </dd>\n";
        echo "  </dl>\n";
        echo "</div>";
    }
}
?>