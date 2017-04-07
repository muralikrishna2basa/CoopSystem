<?php
include     ('../../public/assets/php/partial/require_common.php');
?>
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
    <h2 class="sample">Head</h2>
    <div class="container">
<pre><code>
    &lt;head&gt;
        &lt;title&gt;CoopSystem&lt;/title&gt;
        &lt;?php include("../public/assets/php/partial/head.php"); ?&gt;
    &lt;/head&gt;

</code></pre>
</div>

<h2 class="sample">Layout</h2>
<div class="container">

    <div class="container">
        <div class="flex" style="margin-bottom: 20px">
            <div class="col-2" style="background: #2d579a; color: #fff;">
                <!-- contents -->
                <p>Article.</p>
            </div>
            <div class="col-6" style="background: #b32034; color: #fff;">
                <!-- contents -->
                <p>Article.</p>
            </div>
            <div class="col-4" style="background: #f2bc09; color: #fff;">
                <!-- contents -->
                <p>Article.</p>
            </div>
            <div class="col-12" style="background: #93b11d; color: #fff;">
                <!-- contents -->
                <p>Article.</p>
                <p>col = 1 ~ 12.</p>
            </div>
        </div>
<pre><code>
    &lt;div class="container"&gt;
        &lt;div class="flex"&gt;
            &lt;div class="col-2"&gt;
                &lt;!-- contents --&gt;
            &lt;/div&gt;
            &lt;div class="col-6"&gt;
                &lt;!-- contents --&gt;
            &lt;/div&gt;
            &lt;div class="col-4"&gt;
                &lt;!-- contents --&gt;
            &lt;/div&gt;
            &lt;div class="col-12"&gt;
                &lt;!-- contents --&gt;
            &lt;/div&gt;
    &lt;/div&gt;

</code></pre>
    </div>
</div>


<h2 class="sample">Paragraph</h2>
<div class="container">
    <h1>This text is header-1.</h1>
    <h2>This text is header-2.</h2>
    <h3>This text is header-3.</h3>
    <h4>This text is header-4.</h4>
    <h5>This text is header-5.</h5>
    <h6>This text is header-6.</h6>
    <p>This text is paragraph.</p>

    <p class="text-red">This text is paragraph.</p>
    <p class="text-blue">This text is paragraph.</p>
    <p class="text-green">This text is paragraph.</p>
    <p class="text-yellow">This text is paragraph.</p>
    <p class="text-purple">This text is paragraph.</p>

<pre><code>
    &lt;h1&gt;This text is header-1.&lt;/h1&gt;
    &lt;h2&gt;This text is header-2.&lt;/h2&gt;
    &lt;h3&gt;This text is header-3.&lt;/h3&gt;
    &lt;h4&gt;This text is header-4.&lt;/h4&gt;
    &lt;h2&gt;This text is header-5.&lt;/h2&gt;
    &lt;h6&gt;This text is header-6.&lt;/h6&gt;
    &lt;p&gt;This text is paragraph.&lt;/p&gt;

</code></pre>

</div>



<h2 class="sample">Tables</h2>
<div class="container">

<h3>Plane</h3>
<table>
    <thead>
        <tr>
            <th>Head1</th>
            <th>Head2</th>
            <th>Head3</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Row-1 Col-1</td>
            <td>Row-1 Col-2</td>
            <td>Row-1 Col-3</td>
        </tr>
        <tr>
            <td>Row-2 Col-1</td>
            <td>Row-2 Col-2</td>
            <td>Row-2 Col-3</td>
        </tr>
        <tr>
            <td>Row-3 Col-1</td>
            <td>Row-3 Col-2</td>
            <td>Row-3 Col-3</td>
        </tr>
    </tbody>
</table>

<pre><code>
    &lt;table&gt;
        &lt;thead&gt;
            &lt;tr&gt;
                &lt;th&gt;Head1&lt;/th&gt;
                &lt;th&gt;Head2&lt;/th&gt;
                &lt;th&gt;Head3&lt;/th&gt;
            &lt;/tr&gt;
        &lt;/thead&gt;
        &lt;tbody&gt;
            &lt;tr&gt;
                &lt;td&gt;Row-1 Col-1&lt;/td&gt;
                &lt;td&gt;Row-1 Col-2&lt;/td&gt;
                &lt;td&gt;Row-1 Col-3&lt;/td&gt;
            &lt;/tr&gt;
            &lt;tr&gt;
                &lt;td&gt;Row-2 Col-1&lt;/td&gt;
                &lt;td&gt;Row-2 Col-2&lt;/td&gt;
                &lt;td&gt;Row-2 Col-3&lt;/td&gt;
            &lt;/tr&gt;
            &lt;tr&gt;
                &lt;td&gt;Row-3 Col-1&lt;/td&gt;
                &lt;td&gt;Row-3 Col-2&lt;/td&gt;
                &lt;td&gt;Row-3 Col-3&lt;/td&gt;
            &lt;/tr&gt;
        &lt;/tbody&gt;
    &lt;/table&gt;

</code></pre>

<h3>Class:border-none</h3>
<table class="border-none">
    <thead>
        <tr>
            <th>Head1</th>
            <th>Head2</th>
            <th>Head3</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Row-1 Col-1</td>
            <td>Row-1 Col-2</td>
            <td>Row-1 Col-3</td>
        </tr>
        <tr>
            <td>Row-2 Col-1</td>
            <td>Row-2 Col-2</td>
            <td>Row-2 Col-3</td>
        </tr>
        <tr>
            <td>Row-3 Col-1</td>
            <td>Row-3 Col-2</td>
            <td>Row-3 Col-3</td>
        </tr>
    </tbody>
</table>

<pre><code>
    &lt;table class="border-none"&gt;
        &lt;thead&gt;
            &lt;tr&gt;
                &lt;th&gt;Head1&lt;/th&gt;
                &lt;th&gt;Head2&lt;/th&gt;
                &lt;th&gt;Head3&lt;/th&gt;
            &lt;/tr&gt;
        &lt;/thead&gt;
        &lt;tbody&gt;
            &lt;tr&gt;
                &lt;td&gt;Row-1 Col-1&lt;/td&gt;
                &lt;td&gt;Row-1 Col-2&lt;/td&gt;
                &lt;td&gt;Row-1 Col-3&lt;/td&gt;
            &lt;/tr&gt;
            &lt;tr&gt;
                &lt;td&gt;Row-2 Col-1&lt;/td&gt;
                &lt;td&gt;Row-2 Col-2&lt;/td&gt;
                &lt;td&gt;Row-2 Col-3&lt;/td&gt;
            &lt;/tr&gt;
            &lt;tr&gt;
                &lt;td&gt;Row-3 Col-1&lt;/td&gt;
                &lt;td&gt;Row-3 Col-2&lt;/td&gt;
                &lt;td&gt;Row-3 Col-3&lt;/td&gt;
            &lt;/tr&gt;
        &lt;/tbody&gt;
    &lt;/table&gt;

</code></pre>
<h3>Class:border-bottom</h3>
<table class="border-bottom">
    <thead>
        <tr>
            <th>Head1</th>
            <th>Head2</th>
            <th>Head3</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Row-1 Col-1</td>
            <td>Row-1 Col-2</td>
            <td>Row-1 Col-3</td>
        </tr>
        <tr>
            <td>Row-2 Col-1</td>
            <td>Row-2 Col-2</td>
            <td>Row-2 Col-3</td>
        </tr>
        <tr>
            <td>Row-3 Col-1</td>
            <td>Row-3 Col-2</td>
            <td>Row-3 Col-3</td>
        </tr>
    </tbody>
</table>
<pre><code>
    &lt;table class="border-bottom"&gt;
        &lt;thead&gt;
            &lt;tr&gt;
                &lt;th&gt;Head1&lt;/th&gt;
                &lt;th&gt;Head2&lt;/th&gt;
                &lt;th&gt;Head3&lt;/th&gt;
            &lt;/tr&gt;
        &lt;/thead&gt;
        &lt;tbody&gt;
            &lt;tr&gt;
                &lt;td&gt;Row-1 Col-1&lt;/td&gt;
                &lt;td&gt;Row-1 Col-2&lt;/td&gt;
                &lt;td&gt;Row-1 Col-3&lt;/td&gt;
            &lt;/tr&gt;
            &lt;tr&gt;
                &lt;td&gt;Row-2 Col-1&lt;/td&gt;
                &lt;td&gt;Row-2 Col-2&lt;/td&gt;
                &lt;td&gt;Row-2 Col-3&lt;/td&gt;
            &lt;/tr&gt;
            &lt;tr&gt;
                &lt;td&gt;Row-3 Col-1&lt;/td&gt;
                &lt;td&gt;Row-3 Col-2&lt;/td&gt;
                &lt;td&gt;Row-3 Col-3&lt;/td&gt;
            &lt;/tr&gt;
        &lt;/tbody&gt;
    &lt;/table&gt;

</code></pre>

<h3>Class:table-stripe</h3>
<table class="table-stripe">
    <thead>
        <tr>
            <th>Head1</th>
            <th>Head2</th>
            <th>Head3</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Row-1 Col-1</td>
            <td>Row-1 Col-2</td>
            <td>Row-1 Col-3</td>
        </tr>
        <tr>
            <td>Row-2 Col-1</td>
            <td>Row-2 Col-2</td>
            <td>Row-2 Col-3</td>
        </tr>
        <tr>
            <td>Row-3 Col-1</td>
            <td>Row-3 Col-2</td>
            <td>Row-3 Col-3</td>
        </tr>
    </tbody>
</table>
<pre><code>
    &lt;table class="table-stripe"&gt;
        &lt;thead&gt;
            &lt;tr&gt;
                &lt;th&gt;Head1&lt;/th&gt;
                &lt;th&gt;Head2&lt;/th&gt;
                &lt;th&gt;Head3&lt;/th&gt;
            &lt;/tr&gt;
        &lt;/thead&gt;
        &lt;tbody&gt;
            &lt;tr&gt;
                &lt;td&gt;Row-1 Col-1&lt;/td&gt;
                &lt;td&gt;Row-1 Col-2&lt;/td&gt;
                &lt;td&gt;Row-1 Col-3&lt;/td&gt;
            &lt;/tr&gt;
            &lt;tr&gt;
                &lt;td&gt;Row-2 Col-1&lt;/td&gt;
                &lt;td&gt;Row-2 Col-2&lt;/td&gt;
                &lt;td&gt;Row-2 Col-3&lt;/td&gt;
            &lt;/tr&gt;
            &lt;tr&gt;
                &lt;td&gt;Row-3 Col-1&lt;/td&gt;
                &lt;td&gt;Row-3 Col-2&lt;/td&gt;
                &lt;td&gt;Row-3 Col-3&lt;/td&gt;
            &lt;/tr&gt;
        &lt;/tbody&gt;
    &lt;/table&gt;

</code></pre>

<h3>Class:table-hover</h3>
<table class="table-hover">
    <thead>
        <tr>
            <th>Head1</th>
            <th>Head2</th>
            <th>Head3</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Row-1 Col-1</td>
            <td>Row-1 Col-2</td>
            <td>Row-1 Col-3</td>
        </tr>
        <tr>
            <td>Row-2 Col-1</td>
            <td>Row-2 Col-2</td>
            <td>Row-2 Col-3</td>
        </tr>
        <tr>
            <td>Row-3 Col-1</td>
            <td>Row-3 Col-2</td>
            <td>Row-3 Col-3</td>
        </tr>
    </tbody>
</table>
<pre><code>
    &lt;table class="table-hover"&gt;
        &lt;thead&gt;
            &lt;tr&gt;
                &lt;th&gt;Head1&lt;/th&gt;
                &lt;th&gt;Head2&lt;/th&gt;
                &lt;th&gt;Head3&lt;/th&gt;
            &lt;/tr&gt;
        &lt;/thead&gt;
        &lt;tbody&gt;
            &lt;tr&gt;
                &lt;td&gt;Row-1 Col-1&lt;/td&gt;
                &lt;td&gt;Row-1 Col-2&lt;/td&gt;
                &lt;td&gt;Row-1 Col-3&lt;/td&gt;
            &lt;/tr&gt;
            &lt;tr&gt;
                &lt;td&gt;Row-2 Col-1&lt;/td&gt;
                &lt;td&gt;Row-2 Col-2&lt;/td&gt;
                &lt;td&gt;Row-2 Col-3&lt;/td&gt;
            &lt;/tr&gt;
            &lt;tr&gt;
                &lt;td&gt;Row-3 Col-1&lt;/td&gt;
                &lt;td&gt;Row-3 Col-2&lt;/td&gt;
                &lt;td&gt;Row-3 Col-3&lt;/td&gt;
            &lt;/tr&gt;
        &lt;/tbody&gt;
    &lt;/table&gt;
</code></pre>
</div>

<h2 class="sample">Align</h2>
<div class="container">
<p class="text-left">This text is left.</p>
<p class="text-center">This text is center.</p>
<p class="text-right">This text is right.</p>
<pre><code>
    &lt;p class="text-left"&gt;This text is left.&lt;/p&gt;
    &lt;p class="text-center"&gt;This text is center.&lt;/p&gt;
    &lt;p class="text-right"&gt;This text is right.&lt;/p&gt;

</code></pre>
</div>

<h2 class="sample">Links / Buttons</h2>
<div class="container">
    <a href="#">Link</a>
    <a href="#" class="btn">Link</a>
    <a href="#" class="btn btn-blue">Link</a>
    <a href="#" class="btn btn-red">Link</a>
    <a href="#" class="btn btn-green">Link</a>
    <a href="#" class="btn btn-yellow">Link</a>
    <a href="#" class="btn btn-purple">Link</a>
    <a href="#" class="btn block btn-green">Link</a>
<pre><code>
    &lt;a href="#"&gt;Link&lt;/a&gt;
    &lt;a href="#" class="btn"&gt;Link&lt;/a&gt;
    &lt;a href="#" class="btn btn-blue"&gt;Link&lt;/a&gt;
    &lt;a href="#" class="btn btn-red"&gt;Link&lt;/a&gt;
    &lt;a href="#" class="btn btn-green"&gt;Link&lt;/a&gt;
    &lt;a href="#" class="btn btn-yellow"&gt;Link&lt;/a&gt;
    &lt;a href="#" class="btn btn-purple"&gt;Link&lt;/a&gt;
    &lt;a href="#" class="btn btn-green block"&gt;Link&lt;/a&gt;

</code></pre>
</div>

<h2 class="sample">Form Parts</h2>
<div class="container">
    <h3>Parts</h3>
        <p class="form-group">
            <label>Text</label>
            <input type="text" placeholder="text">
        </p>

        <p class="form-group">
            <label>Password</label>
            <input type="password" placeholder="password">
        </p>

        <p class="form-group">
            <label>Textarea</label>
            <textarea></textarea>
        </p>

        <p class="form-group">
            <label>Select</label>
            <select>
                <option>2017-01</option>
                <option>2017-02</option>
                <option>2017-03</option>
                <option>2017-04</option>
            </select>
        </p>

        <p class="form-group radio">
            <label>
                <input type="radio" name="radio">
                radio
            </label>
            <label>
                <input type="radio" name="radio">
                radio
            </label>
        </p>

        <p class="form-group checkbox">
            <label>
                <input type="checkbox" name="">
                <span>checkbox</span>
            </label>
            <label>
                <input type="checkbox" name="">
                <span>checkbox</span>
            </label>
        </p>
<pre><code>
    &lt;p class="form-group"&gt;
        &lt;label&gt;Text&lt;/label&gt;
        &lt;input type="text" placeholder="text"&gt;
    &lt;/p&gt;

    &lt;p class="form-group"&gt;
        &lt;label&gt;Password&lt;/label&gt;
        &lt;input type="password" placeholder="password"&gt;
    &lt;/p&gt;

    &lt;p class="form-group"&gt;
        &lt;label&gt;Textarea&lt;/label&gt;
        &lt;textarea&gt;&lt;/textarea&gt;
    &lt;/p&gt;

    &lt;p class="form-group"&gt;
        &lt;label&gt;Select&lt;/label&gt;
        &lt;select&gt;
            &lt;option&gt;2017-01&lt;/option&gt;
            &lt;option&gt;2017-02&lt;/option&gt;
            &lt;option&gt;2017-03&lt;/option&gt;
            &lt;option&gt;2017-04&lt;/option&gt;
        &lt;/select&gt;
    &lt;/p&gt;

    &lt;p class="form-group radio"&gt;
        &lt;label&gt;
            &lt;input type="radio" name="radio"&gt;
            radio
        &lt;/label&gt;
        &lt;label&gt;
            &lt;input type="radio" name="radio"&gt;
            radio
        &lt;/label&gt;
    &lt;/p&gt;

    &lt;p class="form-group checkbox"&gt;
        &lt;label&gt;
            &lt;input type="checkbox" name=""&gt;
            &lt;span&gt;checkbox&lt;/span&gt;
        &lt;/label&gt;
        &lt;label&gt;
            &lt;input type="checkbox" name=""&gt;
            &lt;span&gt;checkbox&lt;/span&gt;
        &lt;/label&gt;
    &lt;/p&gt;

</code></pre>
    <h3>Inline</h3>
        <p class="form-group checkbox form-group-inline">
            <label>
                <input type="checkbox" name="">
                <span>checkbox</span>
            </label>
            <label>
                <input type="checkbox" name="">
                <span>checkbox</span>
            </label>
        </p>
        <p class="form-group radio form-group-inline">
            <label>
                <input type="radio" name="radio-inline">
                <span>radio</span>
            </label>
            <label>
                <input type="radio" name="radio-inline">
                <span>radio</span>
            </label>
        </p>
        <p class="form-group form-group-inline">
            <label>Text</label>
            <input type="text" name="">
            <label>Buttons</label>
            <button class="btn btn-blue">button1</button>
            <button class="btn btn-green">button2</button>
        </p>

<pre><code>
    &lt;p class="form-group checkbox form-group-inline"&gt;
        &lt;label&gt;
            &lt;input type="checkbox" name=""&gt;
            &lt;span&gt;checkbox&lt;/span&gt;
        &lt;/label&gt;
        &lt;label&gt;
            &lt;input type="checkbox" name=""&gt;
            &lt;span&gt;checkbox&lt;/span&gt;
        &lt;/label&gt;
    &lt;/p&gt;
    &lt;p class="form-group radio form-group-inline"&gt;
        &lt;label&gt;
            &lt;input type="radio" name="radio-inline"&gt;
            &lt;span&gt;radio&lt;/span&gt;
        &lt;/label&gt;
        &lt;label&gt;
            &lt;input type="radio" name="radio-inline"&gt;
            &lt;span&gt;radio&lt;/span&gt;
        &lt;/label&gt;
    &lt;/p&gt;
    &lt;p class="form-group form-group-inline"&gt;
        &lt;label&gt;Text&lt;/label&gt;
        &lt;input type="text" name=""&gt;
        &lt;label&gt;Buttons&lt;/label&gt;
        &lt;button class="btn btn-blue"&gt;button1&lt;/button&gt;
        &lt;button class="btn btn-green"&gt;button2&lt;/button&gt;
    &lt;/p&gt;

</code></pre>
    </div>

    <h2 class="sample">Validate</h2>
    <div class="container">
        <p class="form-group warning">
            <label>Warning</label>
            <select>
                <option>option1</option>
                <option>option2</option>
                <option>option3</option>
                <option>option4</option>
            </select>
            <span class="msg">Warning message.</span>
        </p>
        <p class="form-group success">
            <label>Success</label>
            <input type="text" name="">
            <span class="msg">Success message.</span>
        </p>
        <p class="form-group danger">
            <label>Danger</label>
            <input type="password" name="">
            <span class="msg">Danger message.</span>
        </p>

<pre><code>
    &lt;p class="form-group warning"&gt;
        &lt;label&gt;Warning&lt;/label&gt;
        &lt;select&gt;
            &lt;option&gt;option1&lt;/option&gt;
            &lt;option&gt;option2&lt;/option&gt;
            &lt;option&gt;option3&lt;/option&gt;
            &lt;option&gt;option4&lt;/option&gt;
        &lt;/select&gt;
        &lt;span class="msg"&gt;Warning message.&lt;/span&gt;
    &lt;/p&gt;
    &lt;p class="form-group success"&gt;
        &lt;label&gt;Success&lt;/label&gt;
        &lt;input type="text" name=""&gt;
        &lt;span class="msg"&gt;Success message.&lt;/span&gt;
    &lt;/p&gt;
    &lt;p class="form-group danger"&gt;
        &lt;label&gt;Danger&lt;/label&gt;
        &lt;input type="password" name=""&gt;
        &lt;span class="msg"&gt;Danger message.&lt;/span&gt;
    &lt;/p&gt;

</code></pre>
    </div>


    <div style="margin-bottom: 100px">
        <div class="btn-group">
            <button class="btn">aa</button>
            <button class="btn">bb</button>
            <button class="btn">cc</button>
            <button class="btn">dd</button>
        </div>
    </div>
</div>

<?php include("../../public/assets/php/partial/footer.php"); ?>

</body>
</html>