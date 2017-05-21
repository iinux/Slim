<!DOCTYPE html>
<html>
<head>
    <title>iUI Demo Site</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
    <link rel="icon" type="image/png" href="/iui/iui-favicon.png">
    <link rel="apple-touch-icon" href="/iui/iui-logo-touch-icon.png" />
    <link rel="stylesheet" href="/iui/iui.css" type="text/css" />
    <link rel="stylesheet" title="Default" href="/iui/t/default/default-theme.css"  type="text/css"/>
    <link rel="stylesheet" href="/css/iui-panel-list.css" type="text/css" />
    <style type="text/css">
        .panel p.normalText { text-align: left;  padding: 0 10px 0 10px; }
    </style>
    <script type="text/javascript">
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-8634743-10'], ['_trackPageview']);
    </script>
    <script type="text/javascript">
        /*(function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();*/
    </script>
</head>
<body>
<div class="toolbar">
    <h1 id="pageTitle">Note</h1>
    <a id="backButton" class="button" href="#"></a>
    <a class="button" href="/comments/add">Add</a>
</div>

<div id="home" class="panel" selected="true">
    {foreach $comments as $comment}
        <fieldset>
            <p class="normalText">{$comment->content}【{$comment->ip}】【{$comment->created_at}】</p>
        </fieldset>
    {/foreach}


</div>

</body>
</html>
