<?php
include     ('../../public/assets/php/partial/require_common.php');
include     ($PATH.'/public/assets/php/lib/common/sessionCheck.php');

require_once($PATH.'/public/assets/php/lib/administrator/category.php');
$categories = [];
$errors     = [];
try{
    $pdo  = connectDb('coop');
    $sql  = "SELECT * FROM category;";
    $stmt = $pdo->prepare($sql);
    $res  = $stmt->execute(null);
    if(!$res) throw new Exception("DB接続時にエラーが発生しました。");
    $i = 0;
    foreach ($stmt as $key => $value)
    {
        $categories[$i] = $value;
        $i++;
    }
}catch (Exception $e){
    $errors[] = $e->getMessage();
}
if(count($_POST) > 0 && isset($_POST['update']))
{
//    echo "update";
    $errors = updateCategory($_POST);
    header('location: ./index.php');
    exit();
}
if(count($_POST) > 0 && isset($_POST['insert']))
{
//    echo "insert";
    $errors = insertCategory($_POST);
    header('location: ./index.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>CoopSystem</title>
    <?php include($PATH."/public/assets/php/partial/head.php"); ?>
</head>
<body>
<?php include($PATH."/public/assets/php/partial/header.php"); ?>

<button class="col-btn" col-target="#col-menu"></button>

<div class="flex">
    <div class="col-2 border-right  scroll bg-glay" id="col-menu">
        <?php include($PATH."/public/assets/php/partial/menu_admin.php"); ?>
    </div>
    <div class="col-10 container scroll">
        <h2>発注を確定する</h2>
        <form method="post">
        </form>
    </div>
</div>
<?php include($PATH."/public/assets/php/partial/footer.php"); ?>
</body>
</html>