<!DOCTYPE html>
<html>
<head>
    <title>CoopSystem</title>
    <?php include("../public/assets/php/partial/head.php"); ?>
</head>
<body>

    <?php include("../public/assets/php/partial/header.php"); ?>


    <div class="container">
        <div class="flex">
            <div class="col-2 menu">
                <div class="container">
                    <h2>Menu</h2>
                    <a href="#" class="block">sample</a>
                </div>
            </div>
            <article class="col-10 content">
                <div class="container">
                    <h2>MainContents</h2>
                    <table class="border-bottom table-hover table-stripe">
                        <thead>
                            <tr>
                                <th>head1</th>
                                <th>head2</th>
                                <th>head3</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>サンプルテキスト</td>
                                <td>123</td>
                                <td>123</td>
                            </tr>
                            <tr>
                                <td>123</td>
                                <td>123</td>
                                <td>123</td>
                            </tr>
                            <tr>
                                <td>123</td>
                                <td>123</td>
                                <td>123</td>
                            </tr>
                            <tr>
                                <td>123</td>
                                <td>123</td>
                                <td>123</td>
                            </tr>
                        </tbody>
                    </table>
                    <form>
                        <p class="form-group form-inline">
                            <input type="text">
                            <input type="button" value="button" class="btn btn-blue">
                            <input type="button" value="button" class="btn btn-red">
                            <input type="button" value="button" class="btn btn-yellow">
                            <input type="button" value="button" class="btn btn-green">
                            <input type="button" value="button" class="btn btn-purple">
                        </p>
                        <p class="form-group">
                            <label>sample</label>
                            <select>
                                <option>123</option>
                                <option>123</option>
                                <option>123</option>
                                <option>123</option>
                            </select>
                            <span class="msg">Warning:</span>
                        </p>
                        <p class="form-group warning">
                            <label>sample</label>
                            <select>
                                <option>123</option>
                                <option>123</option>
                                <option>123</option>
                                <option>123</option>
                            </select>
                            <span class="msg">Warning:</span>
                        </p>
                        <p class="form-group success">
                            <label>sample</label>
                            <select>
                                <option>123</option>
                                <option>123</option>
                                <option>123</option>
                                <option>123</option>
                            </select>
                            <span class="msg">Infomation:</span>
                        </p>
                        <p class="form-group danger">
                            <label>sample</label>
                            <select>
                                <option>123</option>
                                <option>123</option>
                                <option>123</option>
                                <option>123</option>
                            </select>
                            <span class="msg">Error:</span>
                        </p>
                    </form>
                </div>
            </article>
        </div>
    </div>

    <footer>
        end
    </footer>
</body>
</html>