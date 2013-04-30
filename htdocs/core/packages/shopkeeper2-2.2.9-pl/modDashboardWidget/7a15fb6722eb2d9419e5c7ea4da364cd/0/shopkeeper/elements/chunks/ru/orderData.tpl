
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

<br />
