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

<p>Заказ переведен в статус &quot;[[+status]]&quot;.</p>

<div style="padding:15px 0; margin:15px 0; border-top:3px solid #BCBCBC; border-bottom:3px solid #BCBCBC;">
    
    <p>Номер заказа: [[+orderID]]</p>
    
    <p>Дата: [[+date]].</p>
    
    <b>Состав заказа:</b>
    
    <table>
        
        [[+loop]]
        [[+name:is=`Доставка`:then=`
        <tr>
            <td>
                [[+s]]<b>[[+name]]</b> [[+shk_delivery]][[+/s]]
            </td>
            <td></td>
            <td>[[+price_total]] [[+currency]]</td>
        </tr>
        `:else=`
        <tr>
            <td>
                [[+s]]
                <b><a href="[[+link]]">[[+name]]</a></b>
                [[+addit_data]]
                [[+/s]]
            </td>
            <td>
                [[+count]] шт.
            </td>
            <td>
                [[+price]] [[+currency]]
            </td>
        </tr>
        `]]
        [[+end_loop]]
        
        <tr>
            <td align="right" colspan="2"><b>Итого:</b></td>
            <td><b>[[+price_total]] [[+currency]]</b></td>
        </tr>
        
    </table>
    
    [[+order_changed_txt]]
    
    [[+order_notpossible_txt]]
    
    <p>
      <b>Данные покупателя:</b><br />
      [[+contacts]]
    </p>
    
    [[+shk_plugin]]

</div>

<a href="[[++site_url]]" target="_blank">[[++site_url]]</a>

<br /><br />

</body>
</html>