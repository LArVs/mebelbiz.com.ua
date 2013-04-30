<div id="shopCart" class="shop-cart">
  <div class="shop-cart-head"><b>Корзина</b></div>
  <div class="empty">
    <div class="shop-cart-empty">Пусто</div>
  </div>
  [[+plugin]]
</div>
<!--tpl_separator-->
<div id="shopCart" class="shop-cart">
  <div class="shop-cart-head"><a name="shopCart"></a><b>Корзина</b></div>
  <div class="full">
    <form action="[[+this_page_url]]#shopCart" method="post">
    <fieldset>
      <div  style="text-align:right;">
        <a href="[[+empty_url]]" id="shk_butEmptyCart">Очистить корзину</a>
      </div>
      <table width="100%">
        <colgroup>
            <col width="25%" />
            <col width="40%" />
            <col width="25%" />
            <col width="10%" />
        </colgroup>
        <tbody>
          [[+inner]]
        </tbody>
      </table>
      <div  style="text-align:right;">Общая сумма: <b>[[+price_total]]</b> [[+currency]]</div>
      <noscript>
        <div><input type="submit" name="shk_recount" value="Пересчитать" /></div>
      </noscript>
      <div class="cart-order">
        <a href="[[+order_page_url]]" id="shk_butOrder">Оформить заказ</a>
      </div>
    </fieldset>
    </form>
  </div>
  [[+plugin]]
</div>