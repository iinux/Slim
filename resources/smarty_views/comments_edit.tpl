
<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>Comment Edit</title>
    <link rel="stylesheet" href="/css/weui.min.css"/>
    <script src="/js/zepto.min.js"></script>
    <script>
        function comments_add()
        {
            var content = $('#content').val();
            localStorage.setItem('commentTemp', content);
            $.ajax({
                type: 'POST',
                url: '/api/comments/{$id}/edit',
                // data to be added to query string:
                data: { content: content },
                // type of data we are expecting in return:
                dataType: 'json',
                timeout: 6000,
                context: $('body'),
                success: function(data){
                    // this.append(data.project.html)
                    var $toast = $('#toast');
                    if ($toast.css('display') != 'none') return;

                    $toast.fadeIn(100);
                    setTimeout(function () {
                        $toast.fadeOut(100);
                    }, 2000);
                    localStorage.removeItem('commentTemp');
                    window.location = '/'
                },
                error: function(xhr, type){
                    if (xhr.status == 403) {
                        window.location = '/auth/login';
                    }
                    alert('Ajax error!')
                }
            })
        }
        function returnButton() {
            window.location = '/';
        }
        $(function () {
            if (localStorage.getItem('commentTemp') && confirm('want to load localStorage')) {
                $('#content').val(localStorage.getItem('commentTemp'));
            }
        });
    </script>
</head>
<body>
    <div class="weui-cells weui-cells_form">
        <div class="weui-cell">
            <div class="weui-cell__bd">
                <textarea id='content' class="weui-textarea" placeholder="请输入文本" rows="3">{$comment->content}</textarea>
                <div class="weui-textarea-counter"><span>0</span>/200</div>
            </div>
        </div>
    </div>
    <p style="padding: 10px">
        <a href="javascript:comments_add();" class="weui-btn weui-btn_primary">保存</a>
        <a href="javascript:returnButton();" class="weui-btn weui-btn_plain-primary">返回</a>
    </p>
    <!--BEGIN toast-->
    <div id="toast" style="display: none;">
        <div class="weui-mask_transparent"></div>
        <div class="weui-toast">
            <i class="weui-icon-success-no-circle weui-icon_toast"></i>
            <p class="weui-toast__content">Changed</p>
        </div>
    </div>
    <!--end toast-->
</body>
</html>
