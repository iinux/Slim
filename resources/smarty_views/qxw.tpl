<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link href="css/ui-lightness/jquery-ui-1.10.4.custom.min.css" rel="stylesheet"/>
    <link href="css/buttons.css" rel="stylesheet"/>
    <script src="js/jquery-1.10.2.min.js"></script>
    <script src="js/jquery-ui-1.10.4.custom.min.js"></script>

    <script>
        // callback function to bring a hidden box back
        function callback() {
            setTimeout(function () {
                $("#effect").removeAttr("style").hide().fadeIn();
            }, 1000);
        }

        // run the currently selected effect
        function runEffect() {
            // get effect type from
            var selectedEffect = "shake";//$( "#effectTypes" ).val();

            // most effect types need no options passed by default
            var options = {

            };
            // some effects have required parameters
            if (selectedEffect === "scale") {
                options = {
                    percent: 0
                };
            } else if (selectedEffect === "transfer") {
                options = {
                    to: "#button",
                    className: "ui-effects-transfer"
                };
            } else if (selectedEffect === "size") {
                options = {
                    to: {
                        width: 200,
                        height: 60
                    }
                };
            }

            // run the effect
            $("#dialog").effect(selectedEffect, options, 500, callback);
        }

        function op(uri, tabName, content, link, misc) {
            var data = {
                content: content,
                link: link,
                misc: misc
            };
            localStorage.setItem('qxwCommentTemp'+tabName, JSON.stringify(data));

            $.ajax({
                type: 'POST',
                url: '/api/qxw'+uri,
                // data to be added to query string:
                data: data,
                // type of data we are expecting in return:
                dataType: 'json',
                timeout: 6000,
                context: $('body'),
                success: function(data){
                    localStorage.removeItem('qxwCommentTemp'+tabName);
                    window.location = '/qxw'
                },
                error: function(xhr, type){
                    if (xhr.status == 403) {
                        window.location = '/auth/login';
                    }
                    alert('Ajax error!')
                }
            })
        }

        $(function () {
            var data;
            var localStorageKeyName = 'qxwCommentTemp'+'tabs1';
            if (localStorage.getItem(localStorageKeyName) && confirm('want to load localStorage')) {
                data = JSON.parse(localStorage.getItem(localStorageKeyName));
                console.log(data);
                tbContent.value = data.content;
                tbLink.value = data.link;
                tbMisc.value = data.misc;
            }
            localStorageKeyName = 'qxwCommentTemp'+'tabs2';
            if (localStorage.getItem(localStorageKeyName) && confirm('want to load localStorage')) {
                data = JSON.parse(localStorage.getItem(localStorageKeyName));
                randomPassword.value = data.content;
                passwordMisc.value = data.misc;
            }

            var tabs = $("#tabs");
            tabs.tabs({
                //event: "mouseover"
                //"active": 3
            });
            $("#dialog").dialog({
                autoOpen: false,
                width: 400,
                buttons: [
                    {
                        text: "确定",
                        click: function () {
                            $(this).dialog("close");
                            //window.history.back(-1);
                        }
                    }
                ]
            });

            tabs.css("background", "springgreen");
            $("fieldset").css("background", "pink");
            
            $("#go_to_head,#go_to_foot").hover(
                    function () {
                        $(this).addClass("ui-state-hover");
                    },
                    function () {
                        $(this).removeClass("ui-state-hover");
                    }
            );
        });
    </script>
    <style>
        #go_to_head, #go_to_foot {
            padding: .4em 1em .4em 20px;
            text-decoration: none;
            position: relative;
        }

        #go_to_head span.ui-icon, #go_to_foot span.ui-icon {
            margin: 0 5px 0 0;
            position: absolute;
            left: .2em;
            top: 50%;
            margin-top: -8px;
        }
    </style>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Adolf Space</title>
</head>
<body>
<div id="dialog" title="提示" class="ui-dialog-content" style="display:none;">
    <p>用户名或密码错误，请重新输入！</p>
</div>
<div class="ui-widget">
    <div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
        <p>
            <span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
            <strong>欢迎光临！</strong>
        </p>
    </div>
</div>
<form id="form1">
    <div style="text-align: right">
        <fieldset>
            <legend>用户状态</legend>
        </fieldset>
    </div>
    <p><a href="#go_to_foot" id="go_to_head" class="ui-state-default ui-corner-all"><span
                    class="ui-icon ui-icon-newwin"></span>跳到最下</a></p>
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Links</a></li>
            <li><a href="#tabs-2">随机密码生成器</a></li>
        </ul>
        <div id="tabs-1" style="margin-left: auto; margin-right: auto; text-align: center">
            <fieldset>
                <legend>快速添加</legend>
                内容<input type="text" id="tbContent" value="{$link->content|default:""}"/>
                链接<input type="text" id="tbLink" value="{$link->link|default:""}"/>
                备注<input type="text" id="tbMisc" value="{$link->misc|default:""}"/>
                {if isset($link->id)}
                    <button type="button" onclick="op('/links/{$link->id}/edit', 'tabs1', tbContent.value, tbLink.value, tbMisc.value)" class="btn btn-default">添加一行</button>
                {else}
                    <button type="button" onclick="op('/links', 'tabs1', tbContent.value, tbLink.value, tbMisc.value)" class="btn btn-default">添加一行</button>
                {/if}
                <input type="reset" value="重置"/>
            </fieldset>
            <table style="display: inline;">
                {foreach $links as $link}
                    <tr>
                        {*<td>{$link->id}</td>*}
                        <td title="{$link->updated_at}">{date('Y-m-d', strtotime($link->updated_at))}</td>
                        <td>{$link->ip}</td>
                        {if strlen($link->content) > 30 }
                            <td title="{$link->content}">{$link->content|truncate:30:"...":TRUE}</td>
                        {else}
                            <td>{$link->content}</td>
                        {/if}
                        {if strpos($link->link, 'http') === 0 }
                            <td><a class="button" href="{$link->link}" target="_blank">链接</a></td>
                        {else}
                            <td>
                                <a class="button"
                                   onclick="$('#dialog').text('{$link->link}').dialog('open');return false;"
                                >查看</a>
                            </td>
                        {/if}
                        {if strlen($link->misc) > 20}
                            <td title="{$link->misc}">{$link->misc|truncate:20:"...":TRUE}</td>
                        {else}
                            <td>{$link->misc}</td>
                        {/if}
                        <td><a class="button" href="/qxw?linkId={$link->id}">编辑</a></td>
                        {*<td>{$link->category}</td>*}
                    </tr>
                {/foreach}
            </table>
        </div>
        <div id="tabs-2" style="margin-left: auto; margin-right: auto; text-align: center">
            <label><input type="number" name="randomBit">位数</label>
            <br/>
            <label><input type="radio" name="type" value="type1">纯数字</label>
            <label><input type="radio" name="type" value="type2">数字加字母</label>
            <label><input type="radio" name="type" value="type3">数字加字符</label>
            <label><input type="radio" name="type" value="type4">数字加符号加大小写</label>
            <br/>
            <button type="submit" class="btn btn-default">生成密码</button>
            <br/>
            <label>密码</label><input type="text" id="randomPassword">

            <hr/>

            <table style="display: inline">
                {foreach $passwords as $password}
                    <tr>
                        {*<td>{$password->id}</td>*}
                        <td title="{$password->updated_at}">{date('Y-m-d', strtotime($password->updated_at))}</td>
                        <td>{$password->ip}</td>
                        <td>{$password->password}</td>
                        <td>{$password->misc}</td>
                    </tr>
                {/foreach}
            </table>
            <br/>
            <label>备注</label><input type="text" id="passwordMisc">
            <button type="button" class="btn btn-default" onclick="op('/passwords', 'tabs2', randomPassword.value, '', passwordMisc.value)">添加到数据库</button>
        </div>
    </div>

    <p><a href="#go_to_head" id="go_to_foot" class="ui-state-default ui-corner-all"><span
                    class="ui-icon ui-icon-newwin"></span>跳到最上</a></p>
</form>
<div class="ui-widget">
    <div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
        <p>
            <span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
            <strong>欢迎光临！</strong>
        </p>
    </div>
</div>
</body>
</html>