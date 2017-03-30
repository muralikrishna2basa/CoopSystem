<?php
require_once('../../public/assets/php/connectDb.php');
require_once('../../public/assets/php/lib/administrator/category.php');
require_once('../../public/assets/php/getFontColor.php');
$categories = [];
try{
    $pdo  = connectDb('coop');
    $sql  = "SELECT * FROM category;";
    $stmt = $pdo->prepare($sql);
    $res  = $stmt->execute(null);
    $i = 0;
    foreach ($stmt as $key => $value)
    {
        $categories[$i] = $value;
        $i++;
    }
}catch (Exception $e){
    echo $e->getMessage();
}
if(count($_POST) > 0 && isset($_POST['update']))
{
    echo "update";
    updateCategory($_POST);
    header('location: ./index.php');
    exit();
}
if(count($_POST) > 0 && isset($_POST['insert']))
{
    echo "insert";
    insertCategory($_POST);
    header('location: ./index.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>CoopSystem</title>
    <?php include("../../public/assets/php/partial/head.php"); ?>
</head>
<body>
<?php include("../../public/assets/php/partial/header.php"); ?>

<button class="col-btn" col-target="#col-menu"></button>

<div class="flex">
    <div class="col-2 border-right min-height" id="col-menu">
        <?php include("../../public/assets/php/partial/menu_admin.php"); ?>
    </div>
    <div class="col-10 container">
        <h2>
            <span>カテゴリを編集する</span>
            <button class="btn btn-yellow modal-btn" modal-target="#modal-category">カテゴリ追加</button>
        </h2>
        <form method="post">
            <table class="border-bottom table-hover">
                <thead>
                    <tr>
                        <th width="15%" class="text-center">ID</th>
                        <th width="45%" class="text-center">カテゴリ名</th>
                        <th width="10%" class="text-center">色</th>
                        <th width="15%" class="text-center">サンプル</th>
                        <th width="15%" class="text-center">削除</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($categories as $category){ ?>
                    <tr>
                        <td class="text-center"><p><?php echo $category['category_id'] ?></p></td>
                        <td>
                            <p class="form-group form-trans">
                                <input type="hidden" name="category_id[]" value="<?php echo $category['category_id'] ?>">
                                <input type="text" name="category_name[]" value="<?php echo $category['category_name'] ?>">
                            </p>
                        </td>
                        <td>
                            <p class="form-group">
                                <input type="color" name="color[]" sample-target="#sample_<?php echo $category['category_id'] ?>" value="<?php echo $category['color'] ?>">
                            </p>
                        </td>
                        <td class="text-center">
                            <p class="label" id="sample_<?php echo $category['category_id'] ?>" style="background: <?php echo $category['color'] ?>; color: <?php echo getFontColor($category['color']) ?>"><?php echo $category['category_name'] ?></p>
                        </td>
                        <td class="text-center">
                            <p class="form-group">
                                <input type="hidden"   id="delete_<?php echo $category['category_id'] ?>" name="delete_<?php echo $category['category_id'] ?>" value="off">
                                <input type="checkbox" id="delete_<?php echo $category['category_id'] ?>" name="delete_<?php echo $category['category_id'] ?>">
                            </p>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <p class="text-right">
                <button type="submit" name="update" class="btn btn-blue">更新する</button>
            </p>
        </form>
    </div>
</div>


<div id="modal-category" class="modal-hide">
    <form method="post">
        <div class="modal-header bg-blue">
            <h2>カテゴリを新規登録する</h2>
        </div>
        <div class="modal-body">
            <table class="border-none">
                <thead>
                    <tr>
                        <th width="70%">カテゴリ名</th>
                        <th width="30%">色</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <p class="form-group">
                                <input type="text" name="category_name" placeholder="カテゴリ名を入力してください">
                            </p>
                        </td>
                        <td>
                            <p class="form-group">
                                <input type="color" name="color" value="#999999">
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="modal-footer text-right">
            <button type="submit" name="insert" class="btn btn-green">カテゴリを追加する</button>
        </div>
    </form>
</div>
<script type="text/javascript">
$(function(){
    $('input[name*="color"]').change(function(){
        var bg   = $(this).val();
        var text = '#ffffff';
        var trg  = $(this).attr('sample-target');
        var mod  = { r: 0.9, g: 0.8, b: 0.4 };
        if(getBright(bg, mod) > 0.5) text = '#444444';
//        console.log(bg+', '+text+', '+getBright(bg, mod)*1);
        $(trg).css({background: bg, color: text});
    })
})

// 補正付きの明度取得
var getBright = function (colorcode, mod) {
    // 先頭の#は、あってもなくてもOK
    if (colorcode.match(/^#/)) {
        colorcode = colorcode.slice(1);
    }

    // 無駄に、ケタを動的に判断してるので、
    // 3の倍数ケタの16進数表現ならOK etc) #ff0000 #f00 #fff000000
    var keta = Math.floor(colorcode.length / 3);

    if (keta < 1) {
        return false;
    }

    // 16進数をparseして、RGBそれぞれに割り当て
    var rgb = [];
    for (var i = 0; i < 3; i++) {
        rgb.push(parseInt(colorcode.slice(keta * i, keta * (i + 1)), 16));
    }

    // 青は暗めに見えるなど、見え方はRGBそれぞれで違うので、
    // それぞれ補正値を付けて、人間の感覚に寄せられるようにした
    var rmod = mod.r || 1;
    var gmod = mod.g || 1;
    var bmod = mod.b || 1;

    // 明度 = RGBの最大値
    var bright = Math.max(rgb[0] * rmod, rgb[1] * gmod, rgb[2] * bmod) / 255;

    // 明度を返す
    return bright;
};

</script>
<?php include("../../public/assets/php/partial/footer.php"); ?>
</body>
</html>