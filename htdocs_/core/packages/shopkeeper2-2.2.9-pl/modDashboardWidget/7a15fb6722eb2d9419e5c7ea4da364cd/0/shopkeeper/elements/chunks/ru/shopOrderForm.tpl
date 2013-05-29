
<script type="text/javascript">
jQuery(document).bind('ready',function(){
    SHK.selectDelivery(jQuery('select[name="shk_delivery"]','#shopOrderForm').val());
    jQuery('select[name="shk_delivery"]','#shopOrderForm').bind('change',function(){
        SHK.selectDelivery(jQuery(this).val());
    });
});
</script>

<p class="error">[[!+fi.error.error_message]]</p>
<br />

<form method="post" action="[[~[[*id]]]]" id="shopOrderForm">

<fieldset>

<input type="hidden" name="nospam:blank" value="" />
<input type="hidden" name="order" value="1" />

<table cellpadding="3">
<tr>
    <td>Ф.И.О.*:</td>
    <td>
        <input name="fullname" size="30" class="textfield" type="text" value="[[!+fi.fullname:default=`[[+modx.user.id:userinfo=`fullname`]]`:ne=`0`:show]]" />
        <div>[[!+fi.error.fullname]]</div>
    </td>
</tr>
<tr>
    <td>Адрес*:</td>
    <td>
          <input name="address" size="30" class="textfield" type="text" value="[[!+fi.address:default=`[[+modx.user.id:userinfo=`address`]]`:ne=`0`:show]]" />
          <div>[[!+fi.error.address]]</div>
    </td>
</tr>
<tr>
    <td>E-mail*:</td>
    <td>
        <input name="email" size="30" class="textfield" type="text" value="[[!+fi.email:default=`[[+modx.user.id:userinfo=`email`]]`:ne=`0`:show]]" />
        <div>[[!+fi.error.email]]</div>
    </td>
</tr>
<tr>
    <td>Телефон*:</td>
    <td>
        <input name="phone" size="30" class="textfield" type="text" value="[[!+fi.phone:default=`[[+modx.user.id:userinfo=`phone`]]`:ne=`0`:show]]" />
        <div>[[!+fi.error.phone]]</div>
    </td>
</tr>
<tr>
    <td>Способ доставки*:</td>
    <td>
      <select name="shk_delivery" style="width:200px;">
          [[+shk_delivery]]
      </select>
    </td>
</tr>
<tr>
    <td>Способ оплаты*:</td>
    <td>
        <select name="payment" style="width:200px;">
            <option value="При получении" [[!+fi.payment:FormItIsSelected=`При получении`]]>При получении</option>
            <option value="robokassa" [[!+fi.payment:FormItIsSelected=`robokassa`]]>Электронные деньги</option>
        </select>
    </td>
</tr>
<tr>
    <td>Комментарий:</td>
    <td>
        <textarea name="message" class="textfield" rows="4" cols="30">[[!+fi.message]]</textarea>
    </td>
</tr>
<tr>
    <td></td>
    <td><input type="submit" name="submit_button" class="button" value="Отправить" /></td>
</tr>
</table>

</fieldset>

</form>
