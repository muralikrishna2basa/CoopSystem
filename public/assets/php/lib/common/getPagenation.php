<?php
function getPagenation($lists, $pageNumber = 1, $num = 50)
{
    try {
        $page    = ($pageNumber-1) * $num + 1;
        $maxPage = $page + $num;
        if($maxPage > count($lists))
        {
            $page    = (floor(count($lists) / $num) * $num);
            $maxPage = count($lists);
        }
    } catch (Exception $e) {
        $errors[] = $e->getMessage();
    }
    return ['page'=>$page, 'maxPage'=>$maxPage];
}

function setPages($url, $max, $now = 1){
    if($max == 1) return;
    echo "<div class=\"paging btn-group\">\n";
    for($i = 1; $i <= $max; $i++){
        if($now === $i){
            echo "   <a href=\"{$url}&page={$i}\" class=\"page btn btn-yellow page-selected\ id=\"page-{$i}\">{$i}</a>\n";
        }else{
            echo "   <a href=\"{$url}&page={$i}\" class=\"page btn btn-yellow\" id=\"page-{$i}\">{$i}</a>\n";
        }
    }
    echo "</div>\n";

}
?>