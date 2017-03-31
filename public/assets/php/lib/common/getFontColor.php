<?php
    function getFontColor($rgb = "#999999")
    {
        $rgb  = mb_substr($rgb, 1, 9);
        $r    = hexdec( mb_substr( $rgb, 0, 2 ) );
        $g    = hexdec( mb_substr( $rgb, 2, 2 ) );
        $b    = hexdec( mb_substr( $rgb, 4, 2 ) );
        $stat = ( $r*0.299 + $g*0.587 + $b*0.114 ) / 2.55;
        if($stat > 60) return "#444444";
        return "#ffffff";
    }
?>