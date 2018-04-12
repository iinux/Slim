<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>JSON显示美化</title>
    <script src="/js/jquery-1.11.0.min.js"></script>
    <script src="/js/jquery.jsonFormatter.js" type="text/javascript"></script>

    <script type="text/javascript">
        $(function () {
            //配置项
            var options = {ldelim}{rdelim};
            //使用配置项初始化
            $("div").jsonFormat(options);
            $("textarea").change(function () {
                //填充数据
                $("div").jsonFormat({ldelim}data: $(this).val(){rdelim});
            });
            $("textarea").change();
        });
    </script>
</head>
<body>
<textarea style="width: 100%;">
    {$json}
</textarea>

<div style='font-size:13px;'></div>
</body>
</html>
