<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
    <title>Login</title>
    <link rel="stylesheet" href="/iui/iui.css" type="text/css" />
    <link rel="stylesheet" href="/iui/t/default/default-theme.css"  type="text/css"/>
    <script type="application/x-javascript" src="/iui/iui.js"></script>
    <script>
        function logout() {
            window.location = '/auth/logout';
        }
        function returnButton() {
            window.location = '/';
        }
    </script>
</head>

<body>

<div class="toolbar">
    <h1 id="pageTitle"></h1>
</div>

<form id="screen1" title="Login" class="panel" name="formname" action="/auth/check" method="post" selected="true" style="height: 100%;">
    <fieldset>
        <div class="row">
            <label>Login</label>
            <input type="text" name="ident" placeholder="Your login">
        </div>
        <div class="row">
            <label>Password</label>
            <input type="password" name="password" placeholder="Your password">
        </div>
    </fieldset>
    {if (isset($errors))}
        {if (is_array($errors)) }
            {foreach $errors as $error}
                <fieldset>
                    <p class="normalText" style="text-align: center;color: red">
                        {$error}
                    </p>
                </fieldset>
            {/foreach}
        {else}
            <fieldset>
                <p class="normalText" style="text-align: center;color: red">
                    {$errors}
                </p>
            </fieldset>
        {/if}
    {/if}
    <a class="whiteButton" href="javascript:formname.submit()">Log me in!</a>
    {if (isset($smarty.session.user))}
        <a class="redButton" href="javascript:logout()">Log out</a>
    {/if}
    <a class="grayButton" href="javascript:returnButton()">Back</a>
</form>

</body>
</html>
