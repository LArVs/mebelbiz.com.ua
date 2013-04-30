[[+name:is=`Доставка`:then=`

<tr class="cart-order">
    <td width="45%" align="left"><b>[[+name]]</b> <small>[[+shk_delivery]]</small></td>
    <td width="30%">[[+price_total]] [[+currency]]</td>
    <td width="15%"><input type="hidden" name="count[]" value="1" /></td>
    <td width="10%" align="right"></td>
</tr>

`:else=`

<tr class="cart-order">
    <td width="45%" align="left"><b><a href="[[+link]]">[[+name]]</a></b> [[+addit_data]]</td>
    <td width="30%">[[+price]] [[+currency]]</td>
    <td width="15%">
        <input class="shk-count" type="text" size="2" name="count[]" maxlength="3" title="изменить количество" value="[[+count]]" />
    </td>
    <td width="10%" align="right">
        <a href="[[+url_del_item]]" title="Удалить" class="shk-del"><img src="assets/components/shopkeeper/css/web/default/img/delete.gif" width="17" height="17" alt="Удалить" /></a>
    </td>
</tr>

`]]