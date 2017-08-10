<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head</>
    <link href="css/ui-lightness/jquery-ui-1.10.4.custom.min.css" rel="stylesheet"/>
    <link href="css/buttons.css" rel="stylesheet"/>
    <script src="js/jquery-1.10.2.min.js"></script>
    <script src="js/jquery-ui-1.10.4.custom.min.js"></script>

    <script>
        var isLogin = true;
        var loginIsComming = false;

        function checkLogin() {
            if (isLogin) {
                return true;
            } else {
                if (loginIsComming == false) {
                    alert("未登录！");
                }
                return false;
            }
        }

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
            var options = {};
            // some effects have required parameters
            if (selectedEffect === "scale") {
                options = {percent: 0};
            } else if (selectedEffect === "transfer") {
                options = {to: "#button", className: "ui-effects-transfer"};
            } else if (selectedEffect === "size") {
                options = {to: {width: 200, height: 60}};
            }

            // run the effect
            $("#dialog").effect(selectedEffect, options, 500, callback);
        }

        $(function () {
            $("#tabs").tabs({
                //event: "mouseover"
                //"active": 3
            });
            $("td.link_css").each(function (e) {
                if (this.innerHTML.toString().indexOf('input') == -1) {
                    if (this.innerHTML.substring(0, 4) == "http") {
                        if (this.innerHTML.length > 40) {
                            this.innerHTML = '<a class="button" href="' + this.innerHTML + '" target="_blank">链接</a>';
                        } else {
                            this.innerHTML = '<a class="button" href="' + this.innerHTML + '" target="_blank">' + this.innerHTML + '</a>';
                        }
                    } else {
                        this.innerHTML = '<a class="button" onclick="$(\'#dialog\').text(\'' + this.innerHTML + '\').dialog(\'open\');return false;">查看</a>';
                    }
                }
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

            //$("#btnLogin").click(function () {
            //    alert('hello');
            //});
            $("#tabs").css("background", "springgreen");
            $("fieldset").css("background", "pink");
            // Hover states on the static widgets
            $("#go_to_head,#go_to_foot").hover(
                function () {
                    $(this).addClass("ui-state-hover");
                },
                function () {
                    $(this).removeClass("ui-state-hover");
                }
            );
            if ($('input[name=btnLogon]').length == 0) {
                isLogin = false;
            }
            $('#tbPassword')[0].onkeypress = function (e) {
                console.log(e);
                if (e.keyCode == 13) { // Enter
                    loginIsComming = true;
                    $('#btnLogin').click();
                }
            }
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
            <strong><a href="index.aspx">首页</a></strong> 欢迎光临！
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
            <li><a href="#tabs-1">公共区</a></li>
            <li><a href="#tabs-2">第二公共区</a></li>
            <li><a href="#tabs-5">私人区</a></li>
            <li><a href="#tabs-3">登录</a></li>
            <li><a href="#tabs-4">随机密码生成器</a></li>
            <li><a href="#tabs-6">访问者信息</a></li>
            <li><a href="#tabs-7">文件上传</a></li>
            <%--<% Request.LogonUserIdentity. %>--%>
        </ul>
        <div id="tabs-1" style="margin-left: auto; margin-right: auto; text-align: center">
            <fieldset>
                <legend>快速添加</legend>
                内容<input type="text" id="tbContent" />
                链接<input type="text" id="tbLink" />
                备注<input type="text" id="tbMisc" />
                <button type="submit" class="btn btn-default">添加一行</button>
                <input type="reset" value="重置"/>
                <table>
                    {foreach $notifications as $notification}
                        <tr>
                            <td>{$notification->id}</td>
                            <td>{$notification->time}</td>
                            <td>{$notification->content}</td>
                            <td>{$notification->link}</td>
                            <td>{$notification->misc}</td>
                            <td>{$notification->category}</td>
                        </tr>
                    {/foreach}
                </table>
            </fieldset>
        </div>
        <div id="tabs-4" style="margin-left: auto; margin-right: auto; text-align: center">

            <label><input type="number" name="bit">位数</label>
            <label><input type="radio" name="type">纯数字</label>
            <label><input type="radio" name="type">数字加字母</label>
            <label><input type="radio" name="type">数字加字符</label>
            <label><input type="radio" name="type">数字加符号加大小写</label>

            <button type="submit" class="btn btn-default">生成密码</button>
            <label><input type="text" readonly id="password">密码</label>

            <hr/>

            <table>
            {foreach $passwords as $password}
                <tr>
                    <td>{$password->id}</td>
                    <td>{$password->time}</td>
                    <td>{$password->password}</td>
                    <td>{$password->misc}</td>
                </tr>
            {/foreach}
            </table>
            <br/>
            <label><input type="text" id="passwordMisc">备注</label>
            <button type="submit" class="btn btn-default">添加到数据库</button>
        </div>
        <div id="tabs-7">
            <% System.IO.DirectoryInfo dir = new System.IO.DirectoryInfo(Request.PhysicalApplicationPath + "file\\"); ;
            //不是目录
            if (dir == null) return;

            System.IO.FileSystemInfo[] files = dir.GetFileSystemInfos();
            for (int i = 0; i < files.Length; i++)
            {
            System.IO.FileInfo file = files[i] as System.IO.FileInfo;
            //是文件
            if (file != null)
            {
            %><a href="<% =Request.ApplicationPath + " file/" + file.Name %>"><% =file.Name %></a>　
            <%= file.Length %>&nbsp;Byte(s)　
            <a href="delete_file.ashx?file=<%=file.Name %>">删除</a> <br/><%
            }

            } %>
            <br/>
            <FileUpload ID="FileUpload1"<//>
            <Button ID="btnFileUpload" </Text="上传" OnClientClick="return checkLogin();"
                        OnClick="btnFileUpload_Click"/>
            <Label ID="lblFileUploadInfo" </Text="点击“上传”开始上传！"></Label>
            <br/><br/>

            地址：
            <TextBox ID="txtDownloadFromURL" </Width="55%"></TextBox>
            保存文件名：
            <TextBox ID="txtSaveFileName"</></TextBox>
            <Button ID="btnDownloadFromURL" </Text="从网络下载" OnClick="btnDownloadFromURL_Click"
                        OnClientClick="return checkLogin();"/>
            <p><%
                foreach (System.IO.DriveInfo drive in System.IO.DriveInfo.GetDrives()){
                try
                {
                Response.Write(drive.Name + " " + drive.AvailableFreeSpace + " Byte(s) <br/>");
                }
                catch (Exception e)
                {
                //Response.Write(e.Message + "<br/>");
                break;
                }
                }
                %></p>
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