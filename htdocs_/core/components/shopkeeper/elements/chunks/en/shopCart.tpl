<div id="shopCart" class="shop-cart">
  <div class="shop-cart-head"><b>Shopping cart</b></div>
  <div class="empty">
    <div class="shop-cart-empty">it's empty</div>
  </div>
  [[+plugin]]
</div>
<!--tpl_separator-->
<div id="shopCart" class="shop-cart">
  <div class="shop-cart-head"><a name="shopCart"></a><b>Shopping cart</b></div>
  <div class="full">
    <form action="[[+this_page_url]]#shopCart" method="post">
    <fieldset>
      <div  style="text-align:right;">
        <a href="[[+empty_url]]" id="shk_butEmptyCart">Empty cart</a>
      </div>
      <table width="100%">
        <tbody>
          [[+inner]]
        </tbody>
      </table>
      <div  style="text-align:right;">Total price: <b>[[+price_total]]</b> [[+currency]]</div>
      <noscript>
        <div><input type="submit" name="shk_recount" value="Calculate" /></div>
      </noscript>
      <div class="cart-order">
        <a href="[[+order_page_url]]" id="shk_butOrder">Checkout</a>
      </div>
    </fieldset>
    </form>
  </div>
  [[+plugin]]
</div>