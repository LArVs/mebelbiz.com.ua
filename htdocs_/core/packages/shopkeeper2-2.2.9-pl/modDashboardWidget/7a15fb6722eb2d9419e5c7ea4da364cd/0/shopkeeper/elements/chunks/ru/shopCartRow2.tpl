[[+name:is=`Доставка`:then=`

<tr class="cart-order">
    <td></td>
    <td><b>[[+name]]</b> <small>[[+shk_delivery]]</small></td>
    <td>[[+price_total]] [[+currency]]</td>
    <td>
        <input type="hidden" name="count[]" value="1" />
    </td>
</tr>

`:else=`

<tr class="cart-order">
    <td><img src="[[+image]]" width="50" alt="[[+name]]" /></td>
    <td><b><a href="[[+link]]">[[+name]]</a></b><br /><small>[[+addit_data]]</small><br /> [[+price_total]] [[+currency]]</td>
    <td>
        <input class="shk-count" type="text" size="2" name="count[]" maxlength="3" title="изменить количество" value="[[+count]]" />
    </td>
    <td align="right">
        <a href="[[+url_del_item]]" title="Удалить" class="shk-del"><img src="assets/components/shopkeeper/css/web/default/img/delete.gif" width="17" height="17" alt="Удалить" /></a>
    </td>
</tr>

`]]