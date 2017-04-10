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
?>