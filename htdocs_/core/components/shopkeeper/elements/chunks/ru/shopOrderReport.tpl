<!DOCTYPE html>
<html>

<head>
<style type="text/css">
body{background-color:#fff;}
table {width:550px; margin:10px 0; border:1px solid #BCBCBC; border-collapse:collapse;}
table td {padding:5px; border:1px solid #BCBCBC;}
</style>
</head>

<body>
    
<p><b>[[++site_name]]</b></p>

<p>В интернет-магазине сделан заказ.</p>

<div style="padding:15px 0; margin:15px 0; border-top:3px solid #BCBCBC; border-bottom:3px solid #BCBCBC;">
    
    <p>Номер заказа: [[+orderID]]</p>
    
    <p>Дата: [[+date]].</p>
    
    <b>Состав заказа:</b>
    
    [[+orderData]]
    
    <br />
    
    <b>Данные покупателя:</b><br />
    
    <table>
        <tr>
            <td>Ф.И.О.:</td>
            <td>[[+fullname]]</td>
        </tr>
        <tr>
            <td>Адрес:</td>
            <td>[[+address]]</td>
        </tr>
        <tr>
            <td>Адрес эл. почты:</td>
            <td><a href="mailto:[[+email]]">[[+email]]</a></td>
        </tr>
        <tr>
            <td>Телефон:</td>
            <td>[[+phone]]</td>
        </tr>
        <tr>
            <td>Способ доставки:</td>
            <td>[[+shk_delivery]]</td>
        </tr>
        <tr>
            <td>Способ оплаты:</td>
            <td>[[+payment]] </td>
        </tr>
        <tr>
            <td>Комментарий:</td>
            <td>[[+message]]</td>
        </tr>
    </table>
    
    [[+shk_plugin]]

</div>

<a href="[[++site_url]]" target="_blank">[[++site_url]]</a>

<br /><br />

</body>
</html>