<?php
function errorMessages($messages)
{
//    echo "<pre>";var_dump($messages);echo "</pre>";
    $len = 0;
    foreach ($messages as $error) {
        if(is_array($error)){
            foreach ($error as $buf) $len += mb_strlen($buf);
        }else{
            $len += mb_strlen($buf);
        }
    }


    if($len > 0)
    {
        echo "<div>\n";
        echo "  <dl class=\"toggle-menu has-error\">\n";
        echo "      <dt><h3>エラーが発生しました。</h3></dt>\n";
        echo "      <dd>\n";
        foreach ($messages as $error) {
            if(is_array($error)){
                foreach ($error as $buf) echo "          <p>{$buf}</p>\n";
            }else{
                echo "          <p>{$error}</p>\n";
            }
        }
        echo "      </dd>\n";
        echo "  </dl>\n";
        echo "</div>";
    }
}
?>