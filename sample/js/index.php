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
                        <div class="draggable bg-green">
                            <p>Draggable-sample.</p>
                            <p>This content can move.</p>
                        </div>
<pre><code>
    &lt;div class="draggable"&gt;
        &lt;p&gt;Draggable-sample.&lt;/p&gt;
        &lt;p&gt;This content can move.&lt;/p&gt;
    &lt;/div&gt;

</code></pre>
                    </div>

                    <div class="container">
                        <h2 class="sample">Toggle</h2>
                        <dl class="toggle-menu">
                            <dt><h2>Title1</h2></dt>
                            <dd>
                                <p>sample sample sample</p>
                                <p>sample sample sample</p>
                                <p>sample sample sample</p>
                            </dd>
                            <dt><h2>title2</h2></dt>
                            <dd>
                                <p>sample sample sample</p>
                                <p>sample sample sample</p>
                                <p>sample sample sample</p>
                            </dd>
                            <dt><h2>title3</h2></dt>
                            <dd>
                                <p>sample sample sample</p>
                                <p>sample sample sample</p>
                                <p>sample sample sample</p>
                            </dd>
                        </dl>
<pre><code>
    &lt;dl class="toggle-menu"&gt;
        &lt;dt&gt;&lt;h3&gt;Title1&lt;/h3&gt;&lt;/dt&gt;
        &lt;dd&gt;
            &lt;p&gt;sample sample sample&lt;/p&gt;
            &lt;p&gt;sample sample sample&lt;/p&gt;
            &lt;p&gt;sample sample sample&lt;/p&gt;
        &lt;/dd&gt;
        &lt;dt&gt;title2&lt;/dt&gt;
        &lt;dd&gt;
            &lt;p&gt;sample sample sample&lt;/p&gt;
            &lt;p&gt;sample sample sample&lt;/p&gt;
            &lt;p&gt;sample sample sample&lt;/p&gt;
        &lt;/dd&gt;
        &lt;dt&gt;title3&lt;/dt&gt;
        &lt;dd&gt;
            &lt;p&gt;sample sample sample&lt;/p&gt;
            &lt;p&gt;sample sample sample&lt;/p&gt;
            &lt;p&gt;sample sample sample&lt;/p&gt;
        &lt;/dd&gt;
    &lt;/dl&gt;

</code></pre>

                    </div>

                    <div class="container">
                        <h2 class="sample">Tips</h2>
                        <div class="tips">
                            <p class="tips-trigger">Move the cursor to this message.</p>
                            <p class="tips-target">This message appears floating.</p>
                        </div>
<pre><code>
    &lt;div class="tips"&gt;
        &lt;p class="tips-trigger"&gt;Move the cursor to this message.&lt;/p&gt;
        &lt;p class="tips-target"&gt;This message appears floating.&lt;/p&gt;
    &lt;/div&gt;

</code></pre>
                    </div>

                    <div class="container">
                        <h2 class="sample">Modal</h2>
                        <button class="btn btn-blue modal-btn" modal-target="#modal-sample">open modal window</button>
                        <div id="modal-sample" class="modal-hide">
                            <div class="modal-header bg-blue">
                                <h2>modal window</h2>
                            </div>
                            <div class="modal-body">
                                <h2>Body</h2>
                                <p>Sample paragraph.</p>
                            </div>
                            <div class="modal-footer">
                                <h6>footer</h6>
                            </div>
                        </div>
<h2>Button</h2>
<pre><code>
    &lt;button class="btn btn-blue modal-btn" modal-target="#modal-sample"&gt;open modal window&lt;/button&gt;

</code></pre>
<h2>Contents</h2>
<pre><code>
    &lt;div id="modal-sample" class="modal-hide"&gt;
        &lt;div class="modal-header bg-blue"&gt;
            &lt;h2&gt;modal window&lt;/h2&gt;
        &lt;/div&gt;
        &lt;div class="modal-body"&gt;
            &lt;h2&gt;Body&lt;/h2&gt;
            &lt;p&gt;Sample paragraph.&lt;/p&gt;
        &lt;/div&gt;
        &lt;div class="modal-footer"&gt;
            &lt;h6&gt;footer&lt;/h6&gt;
        &lt;/div&gt;
    &lt;/div&gt;

</code></pre>
                    </div>

                </div>
            </div>
        </div>
    </div>

<?php include("../../public/assets/php/partial/footer.php"); ?>
</body>
</html>