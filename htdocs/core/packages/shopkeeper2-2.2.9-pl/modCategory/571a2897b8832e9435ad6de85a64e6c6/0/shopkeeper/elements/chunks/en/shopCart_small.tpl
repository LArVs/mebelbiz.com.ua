<div id="shopCart" class="shop-cart">
  <div class="shop-cart-head"><b>Shopping cart</b></div>
  <div class="empty">
    <div class="shop-cart-empty">it's empty</div>
  </div>
  [[+plugin]]
</div>
<!--tpl_separator-->
<div id="shopCart" class="shop-cart">
  <div class="shop-cart-head"><b>Shopping cart</b></div>
  <div class="full">
    <div  style="text-align:right;">
      <a href="[[+empty_url]]" id="shk_butEmptyCart">Empty cart</a>
    </div>
    <div class="shop-cart-body">Selected: <b>[[+items_total]]</b> [[+plural]]</div>
    <div  style="text-align:right;">Total price: <b>[[+price_total]]</b> [[+currency]]
    </div>
    <div class="cart-order">
      <a href="[[+order_page_url]]" id="shk_butOrder">Checkout</a>
    </div>
  </div>
  [[+plugin]]
</div>