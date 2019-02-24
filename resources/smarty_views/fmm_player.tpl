<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>播放示例-chplayer</title>
        <meta name="keywords" content="flv,mp4,rtmp,html5,m3u8,播放器,视频流,网页视频播放器,dkvplayer,chplayer官网" />
        <meta name="description" content="chplayer支持的视频格式：http协议的flv,mp4,m3u8及rtmp协议 " />
        <meta name="author" content="chplayer" />
        <meta name="copyright" content="http://www.chplayer.com" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <script type="text/javascript" src="https://cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
        <script type="text/javascript" src="/js/chplayer/chplayer.min.js"></script>
    </head>

    <body>
        <div class="video" style="width: 100%; height: 666px;"></div>
        <script type="text/javascript">
            var vel = $(".video");
            vel.width(window.screen.availWidth*0.99);
            vel.height(window.screen.availHeight*0.83);

            //获取地址栏里传递过来的视频地址
            function getUrlParam(name) {
                var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
                var r = window.location.search.substr(1).match(reg); //匹配目标参数
                if(r != null) return unescape(r[2]);
                return null; //返回参数值
            }
            var videoUrl =getUrlParam('videourl');

            var hls=getUrlParam('hls');
            var videoObject = {
                container: 'video',
                variable:'player',
                video: '{$url}'
            };
            if(hls!=null){
                videoObject['html5m3u8']=true;
            }
            var player=new chplayer(videoObject);
        </script>
    </body>

</html>
