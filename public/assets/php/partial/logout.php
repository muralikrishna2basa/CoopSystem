<?php
    include('./require_common.php');
    $_SESSION = [];
    session_destroy();
    header("location: {$URL}/login/");
?>