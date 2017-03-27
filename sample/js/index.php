<!DOCTYPE html>
<html>
<head>
    <title>CoopSystem</title>
    <?php include("../../public/assets/php/partial/head.php"); ?>
    <style type="text/css">
        h2.sample{
            border-top: 3px solid #999;
            border-bottom: 3px solid #999;
            padding-left: 20px;
        }
    </style>
</head>
<body>

    <?php include("../../public/assets/php/partial/header.php"); ?>

    <div class="container">
        <div class="container">
            <div class="flex">
                <div class="col-2">
                    <div class="container" style="border: 1px solid #ddd; border-radius: 5px;">
                        <h2>Menu</h2>
                    </div>
                </div>
                <div class="col-10">
                    <div class="container">
                        <h2 class="sample">Draggable</h2>
                        <div class="draggable">
                            <p>sample</p>
                        </div>
                    </div>

                    <div class="container">
                        <h2 class="sample">Toggle</h2>
                        <dl class="toggle-menu">
                            <dt><h3>Title1</h3></dt>
                            <dd>content</dd>
                            <dt>title2</dt>
                            <dd>content</dd>
                            <dt>title3</dt>
                            <dd>content</dd>
                        </dl>
                    </div>

                    <div class="container">
                        <h2 class="sample">Tips</h2>
                    </div>

                    <div class="container">
                        <h2 class="sample">Modal</h2>
                        <button class="btn btn-blue modal-btn" modal-target="#modal-sample">open modal window</button>
                        <div id="modal-sample" class="modal-hide">
                            <div class="modal-header">
                                <h2>modal window</h2>
                                <p class="modal-close">&times;</p>
                            </div>
                            <div class="modal-body"></div>
                            <div class="modal-footer"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

<?php include("../../public/assets/php/partial/footer.php"); ?>
</body>
</html>