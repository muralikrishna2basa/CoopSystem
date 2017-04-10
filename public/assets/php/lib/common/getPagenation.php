<?php
function getPagenation($lists, $pageNumber = 1, $num = 50)
{
    try {
        $tmp     = ($pageNumber-1);
        if($tmp < 0) $tmp = 0;
        $page    = $tmp * $num + 1;
        $maxPage = $page + $num;
        if($maxPage > count($lists))
        {
            $page    = (floor(count($lists) / $num) * $num);
            if($page <= 0) $page = 1;
            $maxPage = count($lists);
        }
    } catch (Exception $e) {
        $errors[] = $e->getMessage();
    }
    return ['page'=>$page, 'maxPage'=>$maxPage];
}

function setPages($url, $max, $now = 1){
    $max += 1;
    if($max == 1) return;
    echo "<div class=\"paging btn-group\">\n";
    for($i = 1; $i <= $max; $i++){
        if($now === $i){
            echo "   <a href=\"{$url}&page={$i}\" class=\"page btn btn-yellow page-selected\ id=\"page-{$i}\" onclick=\"return checkEdit();\">{$i}</a>\n";
        }else{
            echo "   <a href=\"{$url}&page={$i}\" class=\"page btn btn-yellow\" id=\"page-{$i}\" onclick=\"return checkEdit();\">{$i}</a>\n";
        }
    }
    echo "</div>\n";

}
?>