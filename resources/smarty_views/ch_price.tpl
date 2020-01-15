<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ch price</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<table border="1" cellspacing="0">
    {foreach $chPrices as $chPrice}
        <tr>
            <td>{$chPrice->from}</td>
            <td>{$chPrice->to}</td>
            <td>{$chPrice->date}</td>
            <td>
                <a href="/chPriceOne?from={$chPrice->from}&to={$chPrice->to}&date={$chPrice->date}">{$chPrice->price}</a>
            </td>
            <td>{$chPrice->created_at}</td>
            <td>{$chPrice->updated_at}</td>
        </tr>
    {/foreach}
</table>
</body>
</html>
