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
{foreach $lists as $item}
    <ul>
        <li>
            <a href="/fmm/player/{$item->play_url}" target="_blank">
                <img src="{$item->img}" />
                {$item->title}
            </a>
        </li>
    </ul>
{/foreach}
</body>
</html>