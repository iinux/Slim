<html>
<head>
<style>
    img {
        width: 100px;
        height: 100px;
    }
</style>
</head>
<body>
{$i=0}
{foreach $lists as $item}
    <ul>
        <li>
            <a href="/fmm/player/{$item->play_url}" target="_blank">
                <img src="{$item->img}" />
                {$item->title}
            </a>
            <p id="foo{$i}">{$item->play_url}</p>
            <!-- Trigger -->
            <button class="btn" data-clipboard-target="#foo{$i}">
                点击复制
            </button>
        </li>
    </ul>
    {$i++}
{/foreach}
<script src="/js/clipboard.min.js"></script>
<script>
    var clipboard = new ClipboardJS('.btn');
    //成功回调
    clipboard.on('success', function(e) {
        console.info('Action:', e.action);
        console.info('Text:', e.text);
        console.info('Trigger:', e.trigger);
        alert('copy ok');
        e.clearSelection();
    });
    //失败回调
    clipboard.on('error', function(e) {
        console.error('Action:', e.action);
        console.error('Trigger:', e.trigger);
    });
</script>
</body>
</html>
