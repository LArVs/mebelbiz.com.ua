<p class="error">[[+fi.error.error_message]]</p>
<br />

<form method="post" action="[[~[[*id]]]]" id="shopOrderForm">

<fieldset>

<input type="hidden" name="nospam:blank" value="" /> 

<table cellpadding="3">
<tr>
  <td>Address*:</td>
  <td>
      <input name="address" size="30" class="textfield" type="text" value="[[!+fi.address:default=`[[+modx.user.id:userinfo=`address`]]`:ne=`0`:show]]" />
      <div>[[!+fi.error.address]]</div>
  </td>
</tr>
<tr>
  <td>Delivery method*:</td>
  <td>
    <select name="shk_delivery">
        [[+shk_delivery]]
    </select>
  </td>
</tr>
<tr>
  <td>Payment method*:</td>
  <td>
    <select name="payment">
        <option value="By receipt" [[!+fi.payment:FormItIsSelected=`By receipt`]]>By receipt</option>
        <option value="Electronic money" [[!+fi.payment:FormItIsSelected=`Electronic money`]]>Electronic money</option>
    </select>
  </td>
</tr>
<tr>
  <td>Name*:</td>
  <td>
      <input name="fullname" size="30" class="textfield" type="text" value="[[!+fi.fullname:default=`[[+modx.user.id:userinfo=`fullname`]]`:ne=`0`:show]]" />
      <div>[[!+fi.error.fullname]]</div>
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
  <td>Phone*:</td>
  <td>
      <input name="phone" size="30" class="textfield" type="text" value="[[!+fi.phone:default=`[[+modx.user.id:userinfo=`phone`]]`:ne=`0`:show]]" />
      <div>[[!+fi.error.phone]]</div>
  </td>
</tr>
<tr>
  <td>Comment:</td>
  <td>
      <textarea name="message" class="textfield" rows="4" cols="30">[[!+fi.message]]</textarea>
  </td>
</tr>
<tr>
  <td></td>
  <td><input type="submit" name="submit_button" class="button" value="Submit" /></td>
</tr>
</table>

</fieldset>

</form>