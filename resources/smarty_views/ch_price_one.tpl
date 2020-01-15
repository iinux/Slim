<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ch price</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://as.alipayobjects.com/g/datavis/g2/1.2.1/index.js"></script>
</head>
<body>
<table border="1" cellspacing="0">
    {foreach $chPrices as $chPrice}
        <tr>
            <td>{$chPrice->from}</td>
            <td>{$chPrice->to}</td>
            <td>{$chPrice->date}</td>
            <td>{$chPrice->price}</td>
            <td>{$chPrice->updated_at}</td>
        </tr>
    {/foreach}
</table>
<div id="c1"></div>
<script>
    var chart = new G2.Chart({
        id: 'c1',
        width: 1000,
        height: 500
    });

    var data = JSON.parse('{$chartData}');
    chart.source(data);
    chart.line().position('time*price').color('line');
    // chart.interval().position('time*price').color('time');
    chart.render();
</script>
</body>
</html>
