<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <title>{$title}</title>
    {if ($useGoogleFont) }
    <link href="https://fonts.googleapis.com/css?family=Architects+Daughter" rel="stylesheet" type="text/css">
    {/if}

    <style type="text/css">

        ::selection { background-color: #E13300; color: white; }
        ::-moz-selection { background-color: #E13300; color: white; }

        body {
            background-color: #fff;
            margin: 25px;
            font: 13px/20px normal Helvetica, Arial, sans-serif;
            color: #4F5155;
        }

        a {
            color: #003399;
            background-color: transparent;
            font-weight: normal;
        }

        h1 {
            color: #444;
            background-color: transparent;
            border-bottom: 1px solid #D0D0D0;
            font-size: 19px;
            font-weight: normal;
            {if ($useGoogleFont) }
            font-family: 'Architects Daughter', 'Helvetica Neue', Helvetica, Arial, serif;
            {/if}
            margin: 0 0 14px 0;
            padding: 14px 15px 10px 15px;
        }

        code {
            font-family: Consolas, Monaco, Courier New, Courier, monospace;
            font-size: 12px;
            background-color: #f9f9f9;
            border: 1px solid #D0D0D0;
            color: #002166;
            display: block;
            margin: 14px 0 14px 0;
            padding: 12px 10px 12px 10px;
        }

        p.footer {
            text-align: right;
            font-size: 11px;
            border-top: 1px solid #D0D0D0;
            line-height: 32px;
            padding: 0 10px 0 10px;
        }

        .msfi {
            background-color: #fff !important;
            border-color: #c7d6f7;
            border-style: solid;
            border-width: 2px 1px 2px 2px;
            padding: 0;
            height: 38px;
            border: 1px solid #d9d9d9 !important;
            border-top: 1px solid silver !important;
            width: 80%;
            border-radius: 10px;
            padding-left: 8px;
            font-size: 18px;
        }

        .gb_Fa {
            border: 1px solid #4285f4;
            font-weight: bold;
            padding: 10px 30px 10px 30px;
            margin-top: 10px;
            border-radius: 10px;
            background-color: #4d90fe;
            color: white;
        }

        table {
            width: 80%;
            display: inline-table;
            font-family: '微软雅黑', '宋体', '黑体';
            text-align: left;
        }

        td {
            background-color: rgb(249,252,255);
            height: 24px;
            width: 535px;
        }

        td:hover {
            background-color: rgb(168,213,252);
            cursor: default;
        }

        #searchForm {
            text-align: center;
            padding: 120px 0 100px 0;
        }
    </style>
</head>
<body>

<h1>{$headerTitle}</h1>

<div id="body">
    <form method="post" enctype="multipart/form-data" id="searchForm" action="{$formAction}">
        <input id="searchKey" name="q" type="text" class="msfi" autocomplete="off">
        <table cellpadding='2' cellspacing='0'>
            <tbody>
            <tr><td></td></tr>
            <tr><td></td></tr>
            <tr><td></td></tr>
            <tr><td></td></tr>
            <tr><td></td></tr>
            <tr><td></td></tr>
            <tr><td></td></tr>
            <tr><td></td></tr>
            <tr><td></td></tr>
            <tr><td></td></tr>
            </tbody>
        </table>
        <br />
        {if $dns }
            <input type="button" id="searchBtn" value="Search" class="gb_Fa">
        {else}
            <input type="submit" id="searchBtn" value="Search" class="gb_Fa">
        {/if}
    </form>
</div>

<p class="footer">@{date('Y')} Perorsoft</p>

{include 'google_script.tpl'}
{if $dns }
    {include 'base64submit.tpl'}
{/if}
</body>
</html>
